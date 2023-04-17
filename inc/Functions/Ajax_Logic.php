<?php declare( strict_types = 1 );
/**
 * Ajax logic functions
 *
 * @package UnderstrapChild
 */

namespace Understrap_Child\Functions;

use Understrap_Child\ACF\Field_Group\Pokemon_Details\Pokemon_Details_Config;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

class Ajax_Logic {
    
    public function init() {

        add_action( 'wp_ajax_get_pokedex_old', [ $this, 'get_pokedex_old' ] );
        add_action( 'wp_ajax_nopriv_get_pokedex_old', [ $this, 'get_pokedex_old' ] );

    }


    public function get_pokedex_old(): void {

        $post_id = $_POST['post_id'];
        $pokedex_old = Pokemon_Details_Config::get_pokedex_data( $post_id, false );

        if ( empty( $pokedex_old ) ) {
            wp_die();
        }
        
        wp_die( wp_send_json_success($pokedex_old) );

    }

}
