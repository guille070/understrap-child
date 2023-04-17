<?php declare( strict_types = 1 );
/**
 * Endpoint factory
 *
 * @package UnderstrapChild
 */

namespace Understrap_Child\Endpoints\List_Pokemon;

use Understrap_Child\ACF\Field_Group\Pokemon_Details\Pokemon_Details_Config;
use Understrap_Child\Endpoints\Endpoint_Factory;
use WP_Error;
use WP_Query;
use WP_REST_Response;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

class List_Pokemon_Config extends Endpoint_Factory {

    public string $route = '/list';
    

    /**
     * Init action
     *
     */
    public function init(): WP_REST_Response|WP_Error {

        $args = array(
            'post_type' => POKEMON_CPT_KEY,
            'posts_per_page'  => -1,
        );
        $query = new WP_Query( $args );

        $posts = array();
        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();

                $pokedex_number_recent = Pokemon_Details_Config::get_pokedex_data( get_the_ID() )['number'] ?? '';

                $post = array(
                    'ID' => $pokedex_number_recent,
                    'title' => get_the_title(),
                );

                $posts[] = $post;
            }
            wp_reset_postdata();
        }
        return rest_ensure_response( $posts );

        
    }
    

}
