<?php declare( strict_types = 1 );

/**
 * PokeAPI config
 *
 * @package UnderstrapChild
 */

namespace Understrap_Child\Data_Providers;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

class PokeAPI {

    private string $base_url = 'https://pokeapi.co/api/v2/';
    private string $which_lang = 'en';

    public function get_pokemon_info($name): array {
        $pokemon_url = $this->base_url . 'pokemon/' . $name;
        $pokemon_data = $this->get_api_data($pokemon_url);

        if ( empty( $pokemon_data ) ) {
            wp_die( 'There was a problem obtaining the Pokemon\'s information.' );
        }

        $info = [
            'image' => $pokemon_data['sprites']['front_default'] ?? '',
            'name' => $pokemon_data['name'],
            'description' => $this->get_description( $pokemon_data['species']['url'] ),
            'primary_type' => $this->get_type( $pokemon_data['types'] ),
            'secondary_type' => $this->get_type( $pokemon_data['types'], false ),
            'weight' => $pokemon_data['weight'] ?? '',
            'pokedex_older' => $this->get_pokedex_info( $pokemon_data['game_indices'], false ),
            'pokedex_recent' => $this->get_pokedex_info( $pokemon_data['game_indices'] ),
            'attacks' => $this->get_attacks( $pokemon_data['moves'] ),
        ];

        return $info;
    }

    private function get_api_data( string $url ): mixed {
        $response = wp_remote_get($url);

        if (is_wp_error($response)) {
            http_response_code( 400 );
            wp_die( 'API request failed.' );
        }

        if ($response['response']['code'] == 404) {
            http_response_code( $response['response']['code'] );
            wp_die( 'Pokemon not found.' );
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        return $data;
    }

    private function get_description( string $species_url ): string {
        $species_data = $this->get_api_data($species_url);

        $description = '';
        foreach ($species_data['flavor_text_entries'] as $entry) {
            if ($entry['language']['name'] == $this->which_lang) {

                // Remove all \n\r breaks
                $description = preg_replace( '/(^|[^\n\r])[\r\n](?![\n\r])/', '', $entry['flavor_text'] );
                break;
            }
        }

        return $description;
    }

    private function get_type( array $types, bool $primary = true ): string|null {

        if ( empty( $types ) ){
            return null;
        }

        $slot = ($primary) ? 1 : 2;
        
        foreach ($types as $type) {
            if ($type['slot'] == $slot) {
                return $type['type']['name'];
            }
        }

        return null;
    }

    private function get_pokedex_info( array $game_indices, bool $recent_version = true ): array {

        // Older version info are in the first array key and recent version in the last one
        $array_key_to_search = 0; 
        if ( $recent_version ) {
            $array_key_to_search = array_key_last($game_indices);
        } 

        // Get pokedex number
        $pokedex_data['game_index'] = $game_indices[ $array_key_to_search ]['game_index'] ?? '';

        // Get version name
        $version_url = $game_indices[ $array_key_to_search ]['version']['url'] ?? false;

        if ( $version_url ) {
            $version = $this->get_api_data($version_url);
        }
        
        $pokedex_data['version_name'] = (! empty( $version['name'] )) ? ucfirst( str_replace( "-", " ", $version['name'] ) ) : '';

        // If 'names' are not empty try to get the english one
        // if ( ! empty( $version['names'] ) ) {
        //     foreach ($version['names'] as $version) {
        //         if ( in_array( $this->which_lang, $version['language'] ) ) {
        //             $pokedex_data['version_name'] = $version['name'];
        //             break;
        //         }
        //     }
        // } 

        return $pokedex_data;
    }

    private function get_attacks( array $moves ): array {
        $attacks = [];

        if ( empty( $moves ) ){
            return $attacks;
        }

        foreach ($moves as $move) {
            $attack_url = $move['move']['url'];
            $attack_data = $this->get_api_data($attack_url);
            $attack_name = $attack_data['name'];
            $attack_description = $this->get_attack_description($attack_data['flavor_text_entries']);

            $attacks[] = [
                'name' => $attack_name,
                'description' => $attack_description,
            ];
        }

        return $attacks;
    }

    private function get_attack_description( array $flavor_text_entries ): string|null {

        if ( empty( $flavor_text_entries ) ){
            return null;
        }

        foreach ($flavor_text_entries as $flavor_text) {
            if ($flavor_text['language']['name'] == $this->which_lang) {
                return $flavor_text['flavor_text'];
            }
        }

        return null;
    }

    public function get_total_results(): array {

        $pokemon_list = $this->base_url . 'pokemon?limit=100000&offset=0';
        $pokemon_data = $this->get_api_data($pokemon_list);

        return array(
            'count' => $pokemon_data['count'],
            'results' => $pokemon_data['results'],
        );

    }
    

}
