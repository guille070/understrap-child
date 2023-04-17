<?php declare( strict_types = 1 );

/**
 * Pokemon Details ACF Fields config
 *
 * @package UnderstrapChild
 */

namespace Understrap_Child\ACF\Field_Group\Pokemon_Details;

use Understrap_Child\ACF\Field_Group\Field_Group_Factory;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

class Pokemon_Details_Config extends Field_Group_Factory {


    /**
     * Get Fields
     *
     */
    public function get_fields(): array {

        return array(
            'key' => 'group_pokemon_details',
            'title' => 'Pokemon Details',
            'fields' => array(
                array(
                    'key' => 'field_pokemon_image',
                    'label' => 'Image URL',
                    'name' => 'pokemon_image',
                    'type' => 'url',
                ),
                array(
                    'key' => 'field_pokemon_weight',
                    'label' => 'Weight',
                    'name' => 'pokemon_weight',
                    'type' => 'number',
                ),
                array(
                    'key' => 'field_pokedex_old_version',
                    'label' => 'Pokedex Version (old)',
                    'name' => 'pokedex_old_version',
                    'type' => 'taxonomy',
                    'taxonomy' => VERSION_TAX_KEY,
                    'field_type' => 'select',
                    'allow_null' => true,
                    'return_format' => 'id',
                ),
                array(
                    'key' => 'field_pokedex_old_number',
                    'label' => 'Pokedex Number (old)',
                    'name' => 'pokedex_old_number',
                    'aria-label' => '',
                    'type' => 'number',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'min' => '',
                    'max' => '',
                    'placeholder' => '',
                    'step' => '',
                    'prepend' => '',
                    'append' => '',
                ),
                array(
                    'key' => 'field_pokedex_recent_version',
                    'label' => 'Pokedex Version (recent)',
                    'name' => 'pokedex_recent_version',
                    'type' => 'taxonomy',
                    'taxonomy' => VERSION_TAX_KEY,
                    'field_type' => 'select',
                    'allow_null' => true,
                    'return_format' => 'id',
                ),
                array(
                    'key' => 'field_pokedex_recent_number',
                    'label' => 'Pokedex Number (recent)',
                    'name' => 'pokedex_recent_number',
                    'aria-label' => '',
                    'type' => 'number',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'min' => '',
                    'max' => '',
                    'placeholder' => '',
                    'step' => '',
                    'prepend' => '',
                    'append' => '',
                ),
                array(
                    'key' => 'field_pokemon_primary_type',
                    'label' => 'Primary Type',
                    'name' => 'pokemon_primary_type',
                    'type' => 'taxonomy',
                    'taxonomy' => TYPE_TAX_KEY,
                    'field_type' => 'select',
                    'allow_null' => true,
                    'return_format' => 'id',
                ),
                array(
                    'key' => 'field_pokemon_secondary_type',
                    'label' => 'Secondary Type',
                    'name' => 'pokemon_secondary_type',
                    'type' => 'taxonomy',
                    'taxonomy' => TYPE_TAX_KEY,
                    'field_type' => 'select',
                    'allow_null' => true,
                    'return_format' => 'id',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => POKEMON_CPT_KEY,
                    ),
                ),
            ),
            'show_in_rest' => 1,
        );
            
    }


    public static function get_pokedex_data( $post_id, bool $recent_version = true ): array|null {

        $output = array();
        $field_name = ($recent_version) ? 'recent' : 'old';
        $version_field = get_field( 'pokedex_' . $field_name . '_version', $post_id );
        $number_field = get_field( 'pokedex_' . $field_name . '_number', $post_id );

        if ( empty( $version_field ) && empty( $number_field ) ) {
            return null;
        }

        $version = get_term( $version_field )->name ?? null;
        $number = $number_field ?? null;

        if( $version ) {
            $output['version'] = $version;
        }

        if( $number ) {
            $output['number'] = $number;
        }

        return $output;

    }


    public static function get_pokemon_image( $post_id = false ): string|null {
        return get_field('pokemon_image', $post_id);
    }


    public static function get_type( $post_id = false, bool $primary = true ): string|null {
        $field_name = ($primary) ? 'primary' : 'secondary';
        $field_value = get_field('pokemon_'.$field_name.'_type', $post_id);

        return get_term( $field_value )->name ?? '';
    }


    public static function get_weight( $post_id = false ): string|null {
        return get_field('pokemon_weight', $post_id);
    }

    public static function get_attacks_raw( $post_id ): array {
        $moves = get_the_terms( $post_id, MOVE_TAX_KEY );
        $output = array();

        if ( empty( $moves ) ){
            return [];
        }

        foreach( $moves as $move ) {
            $output[] = array(
                'name' => $move->name,
                'description' => $move->description,
            );
        }

        return $output;
    }


}
