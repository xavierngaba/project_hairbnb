<?php
/**
 * The template for displaying embed listing's content.
 *
 * @see "wp-includes/theme-compat/embed-content.php"
 * @version 2.4.0
 *
 * @since 2.4.0
 * @package Listify
 */
?>
<div <?php post_class( 'wp-embed' ); ?>>
	
	<div class="listing-embed-heading">
		<p class="wp-embed-heading">
			<a href="<?php the_permalink(); ?>" target="_top">
				<?php the_title(); ?>
			</a>
		</p>
		<?php listify_the_listing_rating(); ?>
	</div>

	<div class="wp-embed-excerpt"><?php the_excerpt_embed(); ?></div>

	<?php do_action( 'embed_content' ); ?>

	<div class="wp-embed-footer">
		<?php the_embed_site_title(); ?>
		<div class="wp-embed-meta">
			<?php do_action( 'embed_content_meta' ); ?>
		</div><!-- .wp-embed-meta -->
	</div><!-- .wp-embed-footer -->

</div>
