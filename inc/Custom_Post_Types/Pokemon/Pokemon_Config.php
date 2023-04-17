<?php declare( strict_types = 1 );

/**
 * Pokemon CPT
 *
 * @package UnderstrapChild
 */

namespace Understrap_Child\Custom_Post_Types\Pokemon;

use Understrap_Child\Custom_Post_Types\CPT_Factory;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

class Pokemon_Config extends CPT_Factory {

    public string $cpt_key = POKEMON_CPT_KEY;

    /**
     * Init method
     *
     */
    public function init(): void {
        parent::init();

        $this->acf_save_post();
    }


    /**
     * ACF Save Post action
     *
     */
    public function acf_save_post(): void {
        add_action( 'acf/save_post', [ $this, 'save_type_meta' ], 5 );
        add_action( 'acf/save_post', [ $this, 'save_version_meta' ], 5 );
    }


    /**
     * Update Type taxonomy with same term_id saved in ACF fields
     *
     */
    public function save_type_meta( $post_id ): void {
        if ( POKEMON_CPT_KEY !== get_post_type( $post_id ) || empty( $_POST['acf'] ) ) {
            return;
        }

        $acf_data = $_POST['acf'];

        $primary_type = $acf_data[ 'field_pokemon_primary_type' ];
        $secondary_type = $acf_data[ 'field_pokemon_secondary_type' ];
        $prev_fields = get_fields( $post_id );
        $types = array(
            'primary_type' => array(
                'field_name' => 'pokemon_primary_type',
                'term_id' => $primary_type,
            ),
            'secondary_type' => array(
                'field_name' => 'pokemon_secondary_type',
                'term_id' => $secondary_type,
            ), 
        );

        foreach( $types as $key => $value ) {
            $type_id = $prev_fields[ $value['field_name'] ];
            wp_remove_object_terms( $post_id, $type_id, TYPE_TAX_KEY );

            if ( ! empty( $value['term_id'] ) ) {
                $type_id = intval( $value['term_id'] );
                wp_set_object_terms( $post_id, $type_id, TYPE_TAX_KEY, true );
            }
        }
    }


    /**
     * Update Version taxonomy with same term_id saved in ACF fields
     *
     */
    public function save_version_meta( $post_id ): void {
        if ( POKEMON_CPT_KEY !== get_post_type( $post_id ) || empty( $_POST['acf'] ) ) {
            return;
        }

        $acf_data = $_POST['acf'];

        $old_version = $acf_data[ 'field_pokedex_old_version' ];
        $recent_version = $acf_data[ 'field_pokedex_recent_version' ];
        $prev_fields = get_fields( $post_id );
        $versions = array(
            'old_version' => array(
                'field_name' => 'pokedex_old_version',
                'term_id' => $old_version,
            ),
            'recent_version' => array(
                'field_name' => 'pokedex_recent_version',
                'term_id' => $recent_version,
            ), 
        );

        foreach( $versions as $key => $value ) {
            $version_id = $prev_fields[ $value['field_name'] ];
            wp_remove_object_terms( $post_id, $version_id, VERSION_TAX_KEY );

            if ( ! empty( $value['term_id'] ) ) {
                $version_id = intval( $value['term_id'] );
                wp_set_object_terms( $post_id, $version_id, VERSION_TAX_KEY, true );
            }
        }

    }


    /**
     * Args to build CPT
     *
     */
    public function get_args(): array {

        return array(
            'labels'             => array(
                'name'               => __('Pokémons', CHILD_TEXT_DOMAIN),
                'singular_name'      => __('Pokémon', CHILD_TEXT_DOMAIN),
                'menu_name'          => __('Pokémons', CHILD_TEXT_DOMAIN),
                'name_admin_bar'     => __('Pokémon', CHILD_TEXT_DOMAIN),
                'add_new'            => __('Add new', CHILD_TEXT_DOMAIN),
                'add_new_item'       => __('Add new Pokémon', CHILD_TEXT_DOMAIN),
                'new_item'           => __('New Pokémon', CHILD_TEXT_DOMAIN),
                'edit_item'          => __('Edit Pokémon', CHILD_TEXT_DOMAIN),
                'view_item'          => __('View Pokémon', CHILD_TEXT_DOMAIN),
                'all_items'          => __('All the Pokémons', CHILD_TEXT_DOMAIN),
                'search_items'       => __('Search Pokémons', CHILD_TEXT_DOMAIN),
                'parent_item_colon'  => __('Pokémon Parent', CHILD_TEXT_DOMAIN),
                'not_found'          => __('Pokémons not found.', CHILD_TEXT_DOMAIN),
                'not_found_in_trash' => __('Pokémons not found in trash.', CHILD_TEXT_DOMAIN)
            ),
            'public'             => true,
            'has_archive'        => true,
            'supports'           => array('title', 'editor'),
            'rewrite'            => array('slug' => 'pokemon'),
            'show_in_rest'       => true
        );

    }

}
