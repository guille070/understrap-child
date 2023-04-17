<?php declare( strict_types = 1 );
/**
 * Taxonomies factory
 *
 * @package UnderstrapChild
 */

namespace Understrap_Child\Taxonomies;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

abstract class Tax_Factory {

    public string $tax_key;
    public array $tax_labels;
    public array $which_posts;


    /**
     * Init action
     *
     */
    public function init(): void {
        add_action( 'init', array( $this, 'register' ) );
    }
    

    /**
     * Register taxonomies
     *
     */
    public function register(): void {
        
        register_taxonomy(
            $this->tax_key,
            $this->which_posts,
            array(
                'labels' => $this->tax_labels,
                'rewrite' => array(
                    'slug' => 'pokemon_type',
                    'with_front' => false,
                ),
                'hierarchical' => true,
                'show_admin_column' => true,
                'show_in_rest' => true,
                'query_var' => true,
            ),
        );

    }


    /**
     * Load and instance the taxonomies
     *
     */
    public static function load(): void {
        
        $taxonomies_namespace = 'Understrap_Child\\Taxonomies\\';
        $taxonomies = glob( __DIR__ . '/*', GLOB_ONLYDIR | GLOB_NOSORT );

        if ( ! empty( $taxonomies ) ) {
            foreach ( $taxonomies as $taxonomy ) {
                $tax_name = ucfirst( basename( $taxonomy ) );
                $class_name = $taxonomies_namespace . $tax_name . '\\' . $tax_name . '_Config';

                if ( ! class_exists($class_name) ) {

                    add_action( 
                        'admin_notices',
                        function() use ( $tax_name ) {
                            $class = 'notice notice-error';
                            $message = $tax_name . ' ' . __( 'Taxonomy not found', CHILD_TEXT_DOMAIN );
                        
                            printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
                        }
                    );
                    
                    return;

                }

                $tax_loaded = new $class_name;
                $tax_loaded->init();

            }
        }

    }
    
}
