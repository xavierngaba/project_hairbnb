<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package Listify
 */

// If searching for listings use the listings archive template
if ( isset( $_GET['listings'] ) ) {
	return locate_template( array( 'archive-job_listing.php' ), true );
}

global $style;

$blog_style = get_theme_mod( 'content-blog-style', 'default' );
$style      = 'grid-standard' == $blog_style ? 'standard' : 'cover';
$sidebar    = 'none' != esc_attr( listify_theme_mod( 'content-sidebar-position', 'right' ) ) && is_active_sidebar( 'widget-area-sidebar-1' );

get_header(); ?>

	<div <?php echo apply_filters( 'listify_cover', 'page-cover' ); ?>>
		<h1 class="page-title cover-wrapper"><?php printf( __( 'Search: %s', 'listify' ), get_search_query() ); ?></h1>
	</div>

	<div id="primary" class="container">
		<div class="row content-area">

			<?php if ( 'left' == esc_attr( listify_theme_mod( 'content-sidebar-position', 'right' ) ) ) : ?>
				<?php get_sidebar(); ?>
			<?php endif; ?>

			<main id="main" class="site-main col-xs-12 <?php if ( $sidebar ) : ?> col-sm-7 col-md-8<?php endif; ?>" role="main">

				<?php if ( 'default' != $blog_style ) : ?>
				<div class="blog-archive blog-archive--grid <?php if ( $sidebar ) : ?> blog-archive--has-sidebar<?php endif; ?>" data-columns>
					<?php add_filter( 'excerpt_length', 'listify_short_excerpt_length' ); ?>
				<?php endif; ?>

				<?php
				while ( have_posts() ) :
					the_post();

					if ( 'default' == $blog_style ) :
						get_template_part( 'content' );
					else :
						get_template_part( 'content', 'recent-posts' );
					endif;
				endwhile;
				?>

				<?php if ( 'default' != $blog_style ) : ?>
					<?php remove_filter( 'excerpt_length', 'listify_short_excerpt_length' ); ?>
					</div>
				<?php endif; ?>

				<?php get_template_part( 'content', 'pagination' ); ?>

			</main>

			<?php if ( 'right' == esc_attr( get_theme_mod( 'content-sidebar-position', 'right' ) ) ) : ?>
				<?php get_sidebar(); ?>
			<?php endif; ?>

		</div>
	</div>

<?php get_footer(); ?>
