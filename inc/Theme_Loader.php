<?php declare( strict_types = 1 );
/**
 * Load child functionality
 *
 * @package UnderstrapChild
 */

namespace Understrap_Child;

use Understrap_Child\ACF\Field_Group\Field_Group_Factory;
use Understrap_Child\Custom_Post_Types\CPT_Factory;
use Understrap_Child\Endpoints\Endpoint_Factory;
use Understrap_Child\Functions\Ajax_Logic;
use Understrap_Child\Taxonomies\Tax_Factory;
use WP_REST_Request;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

class Theme_Loader {
    
    /**
     * Loads all the required methods
     *
     */
    public function run(): void {
        self::define_constants();
        self::load_cpts();
        self::load_taxonomies();
        self::enqueue_child_assets();
        self::disable_gutenberg();
        self::load_acf_fields();
        self::script_loader_tag();
        self::init_ajax();
        self::rest_query();
        self::rest_init();
    }


    /**
     * Define constants
     *
     */
    public static function define_constants(): void {
        define( 'POKEMON_CPT_KEY', 'pokemon' );
        define( 'TYPE_TAX_KEY', 'type' );
        define( 'MOVE_TAX_KEY', 'move' );
        define( 'VERSION_TAX_KEY', 'version' );
    }

    
    /**
     * Load CPTs
     *
     */
    public static function load_cpts(): void {
        CPT_Factory::load();
    }


    /**
     * Load taxonomies
     *
     */
    public static function load_taxonomies(): void {
        Tax_Factory::load();
    }

    /**
     * Init ajax functions
     *
     */
    public static function init_ajax(): void {
        $ajax_logic = new Ajax_Logic();
        $ajax_logic->init();
    }


    /**
     * Disable Gutenberg
     *
     */
    public static function disable_gutenberg(): void {
        add_filter( 'use_block_editor_for_post', '__return_false' );

        add_filter( 'use_widgets_block_editor', '__return_false' );

        add_action( 
            'wp_enqueue_scripts', 
            function() {
                wp_dequeue_style( 'wp-block-library' );
                wp_dequeue_style( 'wp-block-library-theme' );
                wp_dequeue_style( 'global-styles' );
            }, 
            20
        );
    }

    public function enqueue_child_assets(): void {

        add_action( 
            'wp_enqueue_scripts', 
            function() {
                wp_enqueue_script( 'axios', 'https://cdnjs.cloudflare.com/ajax/libs/axios/1.2.6/axios.min.js', array() );

                wp_enqueue_script( 'js-logic', get_stylesheet_directory_uri() . '/src/js/index.js', array() );
                wp_localize_script( 'js-logic', 'vars', array(
                    'ajaxurl' => admin_url( 'admin-ajax.php' ),
                    'homeurl' => get_home_url(),
                    'nonce' => wp_create_nonce( 'js-logic-nonce' ),
                ));
            }
        );
        
    }


    /**
     * Filter: script loader tag
     *
     */
    public function script_loader_tag(): void {

        add_filter( 
            'script_loader_tag',
            function ( string $tag, string $handle, string $src ): string {

                if ($handle == 'js-logic') {
                    $tag = '<script type="module" src="' . $src . '" id="' . $handle . '" ></script>';
                }
                
                return $tag;
                
            },
            10,
            3
        );

    }


    /**
     * Load ACF Fields
     *
     */
    public static function load_acf_fields(): void {

        // Checks if ACF is active to add the field groups
        if( ! function_exists('acf_add_local_field_group') ) {
            add_action( 
                'admin_notices',
                function() {
                    $class = 'notice notice-error';
                    $message = __( 'The ACF plugin is required. Please activate it.', CHILD_TEXT_DOMAIN );
                
                    printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
                }
            );
            
            return;
        }

        Field_Group_Factory::load();

    }


    /**
     * Rest query for pokemon to be able to find by term slug
     *
     */
    public static function rest_query(): void {

        add_filter(
            "rest_pokemon_query",
            function ( array $args, WP_REST_Request $request ): array {
                
                // Get parameters of the query
                $params = $request->get_params();
                
                $custom_taxonomies = [
                    'type'
                ];
                
                foreach ( $custom_taxonomies as $custom_taxonomy ) {
                    
                    if ( isset( $params["{$custom_taxonomy}_slug"] ) ) {
                        
                        $args['tax_query'][] = [
                            'taxonomy' => $custom_taxonomy,
                            'field'    => 'slug',
                            'terms'    => explode(
                                ",",
                                $params["{$custom_taxonomy}_slug"]
                            )
                        ];
                        
                    }
                    
                }
                
                return $args;
                
            },
            10,
            2
        );

    }

    /**
     * Load endpoints
     *
     */
    public static function rest_init(): void {
        Endpoint_Factory::load();
    }
}
