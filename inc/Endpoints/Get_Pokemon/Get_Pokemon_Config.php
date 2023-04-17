<?php declare( strict_types = 1 );
/**
 * Endpoint factory
 *
 * @package UnderstrapChild
 */

namespace Understrap_Child\Endpoints\Get_Pokemon;

use Understrap_Child\ACF\Field_Group\Pokemon_Details\Pokemon_Details_Config;
use Understrap_Child\Endpoints\Endpoint_Factory;
use WP_Error;
use WP_Query;
use WP_REST_Response;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

class Get_Pokemon_Config extends Endpoint_Factory {

    public string $route = '/get/(?P<id>\d+)';
    

    /**
     * Init action
     *
     */
    public function init( $request ): WP_REST_Response|WP_Error {

        $args = array(
            'post_type' => POKEMON_CPT_KEY,
            'p' => $request['id'],
        );
        $query = new WP_Query( $args );

        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                $post = array(
                    'image' => Pokemon_Details_Config::get_pokemon_image( get_the_ID() ),
                    'name' => get_the_title(),
                    'description' => get_the_content(),
                    'primary_type' => Pokemon_Details_Config::get_type( get_the_ID() ),
                    'secondary_type' => Pokemon_Details_Config::get_type( get_the_ID(), false ),
                    'weight' => Pokemon_Details_Config::get_weight( get_the_ID() ),
                    'pokedex_number_recent' => Pokemon_Details_Config::get_pokedex_data( get_the_ID() )['number'] ?? '',
                    'pokedex_number_old' => Pokemon_Details_Config::get_pokedex_data( get_the_ID(), false )['number'] ?? '',
                    'attacks' => Pokemon_Details_Config::get_attacks_raw( get_the_ID() ),
                );
            }
            wp_reset_postdata();
        }
        return rest_ensure_response( $post );
        
    }
    

}
