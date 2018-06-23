<?php
/**
 * Beaver Builder Template.
 *
 * @since 2.4.0
 * @version 2.4.0
 */
do_action( 'listify_bb_plugin_template_init' );
get_header(); ?>

	<?php
	while ( have_posts() ) :
		the_post();
?>

		<?php do_action( 'listify_page_before' ); ?>

		<div id="primary" class="container">
			<div class="content-area">

				<main id="main" class="site-main" role="main">

					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<?php the_content(); ?>
					</article>

					<?php comments_template(); ?>

				</main>

			</div>
		</div>

	<?php endwhile; ?>

<?php get_footer(); ?>
