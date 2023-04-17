<?php declare( strict_types = 1 );

/**
 * ACF Fields config
 *
 * @package UnderstrapChild
 */

namespace Understrap_Child\ACF\Field_Group;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

abstract class Field_Group_Factory {

    /**
     * Load ACF Field Groups
     *
     */
    public static function load(): void {
        
        $field_group_namespace = 'Understrap_Child\\ACF\\Field_Group\\';
        $field_groups = glob( __DIR__ . '/*', GLOB_ONLYDIR | GLOB_NOSORT );

        if ( ! empty( $field_groups ) ) {
            foreach ( $field_groups as $field_group ) {
                $field_group_name = ucfirst( basename( $field_group ) );
                $class_name = $field_group_namespace . $field_group_name . '\\' . $field_group_name . '_Config';

                if ( ! class_exists($class_name) ) {
                    continue;
                }

                $field_group_loaded = new $class_name;
                $field_group_loaded->add_field_group();

            }
        }

    }

    /**
     * Add Field Group
     *
     */
    public function add_field_group(): void {

        acf_add_local_field_group( $this->get_fields() );

    }

    /**
     * Get fields 
     *
     */
    public function get_fields(): array {
        return array();
    }

}
