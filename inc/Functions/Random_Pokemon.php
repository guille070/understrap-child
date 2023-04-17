<?php declare( strict_types = 1 );
/**
 * Function to show a random Pokemon
 *
 * @package UnderstrapChild
 */

namespace Understrap_Child\Functions;

use WP_Query;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

class Random_Pokemon {
    
    public static function query() {

        $args = array(
            'post_type' => POKEMON_CPT_KEY,
            'orderby'   => 'rand',
            'posts_per_page' => 1,
        );
         
        $query = new WP_Query( $args );
        $post = $query->post;

        if ( empty( $post ) ) {
            wp_die( 'No Pokemon found in the DB.' );
        }

        $permalink = get_permalink( $post->ID );

        wp_redirect( $permalink );
        exit;

    }

}
