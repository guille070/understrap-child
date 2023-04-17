<?php
/**
 * Template Name: Filter
 *
 * Template for displaying a Filter Pokemon.
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();
$container = get_theme_mod( 'understrap_container_type' );
$wrapper_id = 'full-width-page-wrapper';
?>

<div class="wrapper" id="<?php echo $wrapper_id; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- ok. ?>">

	<div class="<?php echo esc_attr( $container ); ?>" id="content">

		<div class="row">

			<div class="col-md-12 content-area" id="primary">

				<main class="site-main" id="main" role="main">

					<div id="filter-wrapper">
                        <h3>Types</h3>
                        <div class="btn-group" role="group" id="types-container"></div>

                        <div class="container">
                            <div class="row" id="grid-container"></div>
                        </div>

                        <nav>
                            <ul class="pagination" id="pagination-container"></ul>
                        </nav>
                    </div>

				</main>

			</div><!-- #primary -->

		</div><!-- .row -->

	</div><!-- #content -->

</div><!-- #<?php echo $wrapper_id; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- ok. ?> -->

<?php
get_footer();