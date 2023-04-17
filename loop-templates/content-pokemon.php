<?php
/**
 * Single post partial template
 *
 * @package Understrap
 */

use Understrap_Child\ACF\Field_Group\Pokemon_Details\Pokemon_Details_Config;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Image
$image = Pokemon_Details_Config::get_pokemon_image( get_the_ID() );

// Types
$primary_type = Pokemon_Details_Config::get_type( get_the_ID() );
$secondary_type = Pokemon_Details_Config::get_type( get_the_ID(), false );

// Pokedex recent
$pokedex_recent = Pokemon_Details_Config::get_pokedex_data( get_the_ID() );

// Pokedex old
$pokedex_old = Pokemon_Details_Config::get_pokedex_data( get_the_ID(), false );

// Moves
$moves = get_the_terms( get_the_ID(), MOVE_TAX_KEY );
?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

	<header class="entry-header">

		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

		<div class="entry-meta">

			<?php understrap_posted_on(); ?>

		</div><!-- .entry-meta -->

	</header><!-- .entry-header -->

	<?php echo ( ! empty($image) ) ? "<img src='{$image}' alt='' />" : ''; ?>

	<div class="entry-content d-grid gap-3">

		<?php
		the_content();
		understrap_link_pages();
		?>

		<?php
		// Types
		if ( ! empty( $primary_type ) || ! empty( $secondary_type ) ) {
		?>
		
			<div class="pt-2">
				<h2>Types</h2>
				<?php echo ( ! empty($primary_type) ) ? "<p>Primary: {$primary_type}</p>" : ''; ?>
				<?php echo ( ! empty($secondary_type) ) ? "<p>Secondary: {$secondary_type}</p>" : ''; ?>
			</div>

		<?php
		}
		?>

		<?php
		// Pokedex Recent
		if ( ! empty( $pokedex_recent['version'] ) || ! empty( $pokedex_recent['number'] ) ) {
		?>
			<div class="pt-2">
				<h2>Recent Pokedex</h2>
				<?php echo ( ! empty($pokedex_recent['version']) ) ? "<p>Version: {$pokedex_recent['version']}</p>" : ''; ?>
				<?php echo ( ! empty($pokedex_recent['number']) ) ? "<p>Number: {$pokedex_recent['number']}</p>" : ''; ?>
			</div>

		<?php
		}
		?>

		<?php
		// Pokedex Old
		if ( ! empty( $pokedex_old['version'] ) || ! empty( $pokedex_old['number'] ) ) {
		?>

			<div class="pt-2">
				<button type="button" class="btn btn-primary" id="show_old_pokedex" data-postid="<?php echo get_the_ID(); ?>">Show oldest Pokedex</button>	
			</div>
			<div class="pt-2">
				<div id="old_pokedex"></div>
			</div>

		<?php
		}
		?>

		<?php
		// Moves
		if ( ! empty( $moves ) ) {
		?>

			<div class="pt-2">
				<h2>Moves</h2>
				<table class="table">
					<thead>
						<tr>
							<th scope="col">Name</th>
							<th scope="col">Description</th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach( $moves as $move ) {
						?>
							<tr>
								<th scope="row">
									<?php echo $move->name; ?>
								</th>
								<td>
									<?php echo $move->description; ?>
								</td>
							</tr>
						<?php
						}
						?>
					</tbody>
				</table>
			</div>

		<?php
		}
		?>

	</div><!-- .entry-content -->

	<footer class="entry-footer">

		<?php understrap_entry_footer(); ?>

	</footer><!-- .entry-footer -->

</article><!-- #post-<?php the_ID(); ?> -->
