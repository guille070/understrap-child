<?php declare( strict_types = 1 );
/**
 * Endpoint factory
 *
 * @package UnderstrapChild
 */

namespace Understrap_Child\Endpoints;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

abstract class Endpoint_Factory {

    public string $route;
    

    /**
     * Register rest init action
     *
     */
    public function register(): void {

        add_action( 'rest_api_init', function () {
            register_rest_route( 'pokemon/v1', $this->route, array(
                'methods' => 'GET',
                'callback' => [ $this, 'init' ],
            ) );
        } );
        
    }


    /**
     * Load and instance the endpoints
     *
     */
    public static function load(): void {
        
        $endpoints_namespace = 'Understrap_Child\\Endpoints\\';
        $endpoints = glob( __DIR__ . '/*', GLOB_ONLYDIR | GLOB_NOSORT );

        if ( ! empty( $endpoints ) ) {
            foreach ( $endpoints as $endpoint ) {
                $endpoint_name = ucfirst( basename( $endpoint ) );
                $class_name = $endpoints_namespace . $endpoint_name . '\\' . $endpoint_name . '_Config';

                if ( ! class_exists($class_name) ) {

                    add_action( 
                        'admin_notices',
                        function() use ( $endpoint_name ) {
                            $class = 'notice notice-error';
                            $message = $endpoint_name . ' ' . __( 'Endpoint not found', CHILD_TEXT_DOMAIN );
                        
                            printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
                        }
                    );
                    
                    return;

                }

                $endpoint_loaded = new $class_name;
                $endpoint_loaded->register();

            }
        }

    }
    

}
