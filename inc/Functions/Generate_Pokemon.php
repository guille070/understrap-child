<?php declare( strict_types = 1 );
/**
 * Function to generate a random Pokemon by calling the PokeAPI and store in the DB
 *
 * @package UnderstrapChild
 */

namespace Understrap_Child\Functions;

use Understrap_Child\Data_Providers\PokeAPI;
use WP_Error;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

class Generate_Pokemon {
    
    public static function init(): void {

        self::check_user_permissions();
        self::generate();

    }

    public static function check_user_permissions(): void {

        if( ! current_user_can('publish_posts') ) {
            wp_die( 'Sorry, only users with permissions to publish posts can generate a pokemon.' );
        }

    }

    public static function generate(): void {

        $api_instance = new PokeAPI();
        $results = $api_instance->get_total_results();

        // Generate rand number with max of pokemon total count
        $rand_index = rand(0, $results['count']);

        // Get pokemon based of random index
        $choosen_item = $results['results'][$rand_index];
        $info = $api_instance->get_pokemon_info( $choosen_item['name'] );

        self::insert_or_update( $info );

    }

    public static function insert_or_update( array $info ) {

        if ( empty( $info ) ){
            return false;
        }

        // Get the taxonomies and terms to save in the post
        $tax_input = self::manage_taxonomies( $info );

        // Get the pokedex name versions and type names and build the meta input. Sanitize them to find a term with same slug. If there's any, save the term_id as meta value.
        $pokedex_old_version = sanitize_title( $info['pokedex_older']['version_name'] );
        $pokedex_recent_version = sanitize_title( $info['pokedex_recent']['version_name'] );
        $pokemon_primary_type = sanitize_title( $info['primary_type'] );
        $pokemon_secondary_type = sanitize_title( $info['secondary_type'] );
        $pokedex_old_version_id = get_term_by( 'slug', $pokedex_old_version, VERSION_TAX_KEY );
        $pokedex_recent_version_id = get_term_by( 'slug', $pokedex_recent_version, VERSION_TAX_KEY );
        $pokemon_primary_type_id = get_term_by( 'slug', $pokemon_primary_type, TYPE_TAX_KEY );
        $pokemon_secondary_type_id = get_term_by( 'slug', $pokemon_secondary_type, TYPE_TAX_KEY );

        $metas_acf = array(
            'field_pokemon_image' => $info['image'],
            'field_pokemon_weight' => $info['weight'],
            'field_pokedex_old_number' => $info['pokedex_older']['game_index'],
            'field_pokedex_old_version' => $pokedex_old_version_id->term_id ?? '',
            'field_pokedex_recent_number' => $info['pokedex_recent']['game_index'],
            'field_pokedex_recent_version' => $pokedex_recent_version_id->term_id ?? '',
            'field_pokemon_primary_type' => $pokemon_primary_type_id->term_id ?? '',
            'field_pokemon_secondary_type' => $pokemon_secondary_type_id->term_id ?? '',
        );

        $pokemon_slug = sanitize_title($info['name']);

        $post_arr = array(
            'post_title' => ucfirst( str_replace( "-", " ", $info['name'] ) ),
            'post_content' => $info['description'],
            'post_name' => $pokemon_slug,
        );

        // Use the pokemon name as the post slug to search a post with same slug to avoid inserting a duplicate one
        $posts = get_posts(array(
            'name' => $pokemon_slug,
            'numberposts' => 1,
            'post_type' => POKEMON_CPT_KEY,
        ));

        if( empty( $posts ) ) {
            
            // Insert
            $post_arr['post_status'] = 'publish';
            $post_arr['post_type'] = POKEMON_CPT_KEY;

            $post_id = wp_insert_post( $post_arr, true );

        } else {
            
            // Update
            $post_id_to_update = $posts[0]->ID;
            $post_arr['ID'] = $post_id_to_update;

            $post_id = wp_update_post( $post_arr, true );

        }

        if ( is_wp_error( $post_id ) ) {
            return $post_id->get_error_message();
        }

        self::set_acf_values( $post_id, $metas_acf );
        self::set_object_terms( $post_id, $tax_input );

        wp_die(
            'The pokémon has been successfully created or updated.',
            'Pokémon generated',
            array(
                'response' => 200,
                'link_url' => get_permalink( $post_id ),
                'link_text' => 'View',
            )
        );

    }

    public static function set_acf_values( int $post_id, array $metas ) {

        foreach( $metas as $key => $value ) {
            update_field( $key, $value, $post_id );
        }

    }


    public static function set_object_terms( int $post_id, array $taxonomies ): bool|WP_Error {

        if ( empty( $post_id ) || empty( $taxonomies ) ) {
            return false;
        }

        foreach( $taxonomies as $tax_name => $term_ids ) {
            $set_terms = wp_set_object_terms( $post_id, $term_ids, $tax_name );

            if ( is_wp_error( $set_terms ) ) {
                return $set_terms->get_error_message();
            }
        }

        return true;

    }

    public static function manage_taxonomies( $info ) {

        if ( empty( $info ) ){
            return array();
        }

        $output = array();
        $term_data = null;

        // Build array with terms info to process later
        $terms = array(
            TYPE_TAX_KEY => array( 
                array(
                    'name' => $info['primary_type'] ?? '',
                ),
                array(
                    'name' => $info['secondary_type'] ?? '',
                ),
            ),
            VERSION_TAX_KEY => array(
                array(
                    'name' => $info['pokedex_older']['version_name'] ?? '',
                ),
                array(
                    'name' => $info['pokedex_recent']['version_name'] ?? '',
                ),
            ),
            MOVE_TAX_KEY => $info['attacks'],
        );

        foreach( $terms as $tax_key => $values ) {
            
            foreach( $values as $value ) {

                if ( empty( $value['name'] ) ) {
                    continue;
                }

                $term_slug = sanitize_title( $value['name'] );
                $term_description = $value['description'] ?? '';
                $term_exists = term_exists( $term_slug, $tax_key );

                // Term does not exist, insert it
                if ( empty( $term_exists ) ) {
                    $term_name = ucfirst( str_replace( "-", " ", $term_slug ) );

                    $args_insert = array(
                        'slug' => $term_slug,
                    );

                    // In this taxonomy we need to add the term description
                    if ( $tax_key == MOVE_TAX_KEY ) { 
                        $args_insert['description'] = $term_description;
                    }

                    $term_data = wp_insert_term( 
                        $term_name, 
                        $tax_key, 
                        $args_insert
                    );
                } 
                
                // Term exist, update it
                if ( ! empty( $term_exists['term_id'] ) ) {
                    $term_data['term_id'] = $term_exists['term_id'];

                    // In this taxonomy we need to update the term description. In the others taxonomies it's not neccesary to update anything. The only attribute is the term name and is setted by the slug and we are using the slug to check if exists in DB, so no need to updated it.
                    if ( $tax_key == MOVE_TAX_KEY ) { 
                        $term_data = wp_update_term( 
                            $term_data['term_id'], 
                            $tax_key, 
                            array(
                                'description' => $term_description,
                            )
                        );
                    }
                }

                if ( ! empty( $term_data ) && is_wp_error( $term_data ) ) {
                    return $term_data->get_error_message();
                }

                if ( ! empty( $term_data ) ) {
                    $output[ $tax_key ][] = (int) $term_data['term_id'];
                }
            }

        }

        return $output;

    }



}
