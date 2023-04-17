<?php declare( strict_types = 1 );

/**
 * Type taxonomy
 *
 * @package UnderstrapChild
 */

namespace Understrap_Child\Taxonomies\Type;

use Understrap_Child\Taxonomies\Tax_Factory;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

class Type_Config extends Tax_Factory {

    public string $tax_key = TYPE_TAX_KEY;
    public array $which_posts = array(POKEMON_CPT_KEY);
    public array $tax_labels = array(
        'name' => 'Types',
        'singular_name' => 'Type',
        'all_items' => 'All Types',
        'edit_item' => 'Edit Type',
        'view_item' => 'View Type',
        'update_item' => 'Update Type',
        'add_new_item' => 'Add New Type',
        'new_item_name' => 'New Type Name',
        'search_items' => 'Search Types',
        'popular_items' => 'Popular Types',
        'not_found' => 'No Types found',
        'no_terms' => 'No Types',
        'choose_from_most_used' => 'Choose from the most used Types',
    );


    /**
     * Init method
     *
     */
    public function init(): void {
        parent::init();

        $this->admin_menu();
    }


    /**
     * Admin menu action
     *
     */
    public function admin_menu(): void {

        add_action(
            'admin_menu', 
            function () {
                remove_meta_box( $this->tax_key . 'div', $this->which_posts, 'side');
            }
        );
    }

}
