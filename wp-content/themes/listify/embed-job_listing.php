<?php
/**
 * Job Listing Embed Template
 *
 * @see "wp-includes/theme-compat/embed.php"
 * @version 2.4.0
 *
 * @since 2.4.0
 * @package Listify
 */
do_action( 'listify_listing_embed_init' );
get_header( 'embed' );

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		get_template_part( 'content', 'embed-job_listing' );
	endwhile;
else :
	get_template_part( 'embed', '404' );
endif;

get_footer( 'embed' );
