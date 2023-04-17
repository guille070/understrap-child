<?php declare( strict_types = 1 );

/**
 * Version taxonomy
 *
 * @package UnderstrapChild
 */

namespace Understrap_Child\Taxonomies\Version;

use Understrap_Child\Taxonomies\Tax_Factory;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

class Version_Config extends Tax_Factory {

    public string $tax_key = VERSION_TAX_KEY;
    public array $which_posts = array(POKEMON_CPT_KEY);
    public array $tax_labels = array(
        'name' => 'Versions',
        'singular_name' => 'Version',
        'all_items' => 'All Versions',
        'edit_item' => 'Edit Version',
        'view_item' => 'View Version',
        'update_item' => 'Update Version',
        'add_new_item' => 'Add New Version',
        'new_item_name' => 'New Version Name',
        'search_items' => 'Search Versions',
        'popular_items' => 'Popular Versions',
        'not_found' => 'No Versions found',
        'no_terms' => 'No Versions',
        'choose_from_most_used' => 'Choose from the most used Versions',
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
