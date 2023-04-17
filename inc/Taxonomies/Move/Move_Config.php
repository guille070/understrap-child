<?php declare( strict_types = 1 );

/**
 * Move taxonomy
 *
 * @package UnderstrapChild
 */

namespace Understrap_Child\Taxonomies\Move;

use Understrap_Child\Taxonomies\Tax_Factory;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

class Move_Config extends Tax_Factory {

    public string $tax_key = MOVE_TAX_KEY;
    public array $which_posts = array(POKEMON_CPT_KEY);
    public array $tax_labels = array(
        'name' => 'Moves (Attacks)',
        'singular_name' => 'Move',
        'all_items' => 'All Moves',
        'edit_item' => 'Edit Move',
        'view_item' => 'View Move',
        'update_item' => 'Update Move',
        'add_new_item' => 'Add New Move',
        'new_item_name' => 'New Move Name',
        'search_items' => 'Search Moves',
        'popular_items' => 'Popular Moves',
        'not_found' => 'No Moves found',
        'no_terms' => 'No Moves',
        'choose_from_most_used' => 'Choose from the most used Moves',
    );

}
