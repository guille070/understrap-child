<?php declare( strict_types = 1 );
/**
 * Post Type factory
 *
 * @package UnderstrapChild
 */

namespace Understrap_Child\Custom_Post_Types;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

abstract class CPT_Factory {

    public string $cpt_key;
    

    /**
     * Init action
     *
     */
    public function init(): void {
        add_action( 'init', array( $this, 'register' ) );
    }


    /**
     * Register CPT
     *
     */
    public function register(): void {
        
        register_post_type( $this->cpt_key, $this->get_args() );

    }


    /**
     * Load CPTs
     *
     */
    public static function load(): void {
        
        $cpts_namespace = 'Understrap_Child\\Custom_Post_Types\\';
        $cpts = glob( __DIR__ . '/*', GLOB_ONLYDIR | GLOB_NOSORT );

        if ( ! empty( $cpts ) ) {
            foreach ( $cpts as $cpt ) {
                $cpt_name = ucfirst( basename( $cpt ) );
                $class_name = $cpts_namespace . $cpt_name . '\\' . $cpt_name . '_Config';

                if ( ! class_exists($class_name) ) {

                    add_action( 
                        'admin_notices',
                        function() use ( $cpt_name ) {
                            $class = 'notice notice-error';
                            $message = $cpt_name . ' ' . __( 'CPT not found', CHILD_TEXT_DOMAIN );
                        
                            printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
                        }
                    );
                    
                    return;

                }

                $cpt_loaded = new $class_name;
                $cpt_loaded->init();

            }
        }

    }


    /**
     * Get args to create the CPT
     *
     */
    public function get_args(): array {
        return array();
    }

}
