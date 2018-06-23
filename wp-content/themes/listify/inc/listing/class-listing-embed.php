<?php
/**
 * Hanle Listing Embed
 *
 * @since 2.4.0
 *
 * @package Listify
 * @category Class
 * @author Astoundify
 */

/**
 * Listing Embed
 *
 * @since 2.4.0
 */
class Listify_Listing_Embed {

	/**
	 * Init Class
	 *
	 * @since 2.4.0
	 */
	public static function init() {

		// Embed Init.
		add_action( 'listify_listing_embed_init', array( __CLASS__, 'embed_init' ) );

		// Add custom hook to easily target listing embed.
		add_action( 'enqueue_embed_scripts', array( __CLASS__, 'scripts' ) );
		add_action( 'embed_head', array( __CLASS__, 'head' ) );
		add_action( 'embed_content', array( __CLASS__, 'content' ) );
		add_action( 'embed_footer', array( __CLASS__, 'footer' ) );

		// Add Script.
		add_action( 'listify_listing_embed_scripts', array( __CLASS__, 'embed_scripts' ) );

		// Custom Excerpt.
		add_filter( 'the_excerpt_embed', array( __CLASS__, 'excerpt' ) );

		// Add Map.
		add_action( 'listify_listing_embed_content', array( __CLASS__, 'embed_map' ) );
	}

	/**
	 * Embed Init.
	 *
	 * @since 2.4.0
	 */
	public static function embed_init() {

		// Fix review URL.
		add_filter( 'listify_submit_review_link_anchor', array( __CLASS__, 'review_link' ) );
	}

	/**
	 * Review URL
	 *
	 * @since 2.4.0
	 *
	 * @param string $url Review URL.
	 * @return string
	 */
	public static function review_link( $url ) {
		if ( $url ) {
			$url = get_permalink();
		}
		return $url;
	}

	/**
	 * Embed Scripts
	 *
	 * @since 2.4.0
	 */
	public static function scripts() {
		$post = get_queried_object();

		if ( ! $post || ! $post instanceof WP_Post ) {
			return;
		}

		if ( 'job_listing' !== $post->post_type ) {
			return;
		}

		do_action( 'listify_listing_embed_scripts', $post );
	}

	/**
	 * Embed Head.
	 *
	 * @since 2.4.0
	 */
	public static function head() {
		$post = get_queried_object();

		if ( ! $post || ! $post instanceof WP_Post ) {
			return;
		}

		if ( 'job_listing' !== $post->post_type ) {
			return;
		}

		do_action( 'listify_listing_embed_head', $post );
	}

	/**
	 * Embed Content.
	 *
	 * @since 2.4.0
	 */
	public static function content() {
		$post = get_queried_object();

		if ( ! $post || ! $post instanceof WP_Post ) {
			return;
		}

		if ( 'job_listing' !== $post->post_type ) {
			return;
		}

		do_action( 'listify_listing_embed_content', $post );
	}

	/**
	 * Embed Footer.
	 *
	 * @since 2.4.0
	 */
	public static function footer() {
		$post = get_queried_object();

		if ( ! $post || ! $post instanceof WP_Post ) {
			return;
		}

		if ( 'job_listing' !== $post->post_type ) {
			return;
		}

		do_action( 'listify_listing_embed_footer', $post );
	}

	/**
	 * Embed Scripts
	 *
	 * @since 2.4.0
	 */
	public static function embed_scripts() {
		wp_enqueue_style( 'listify-embed', get_template_directory_uri() . '/css/embed.css', array(), listify_get_version() );
	}

	/**
	 * Custom Embed Excerpt.
	 *
	 * @since 2.4.0
	 */
	public static function excerpt( $excerpt ) {
		$post = get_queried_object();

		if ( ! $post || ! $post instanceof WP_Post ) {
			return;
		}

		if ( 'job_listing' !== $post->post_type ) {
			return $excerpt;
		}

		return apply_filters( 'listify_listing_embed_excerpt', wp_trim_words( $post->post_content, 10 ), $post );
	}

	/**
	 * Display Map in Embed Content
	 * HTML class ".wp-embed-featured-image" is required.
	 * to make the map height proportional in embed iframe.
	 *
	 * @see "wp-includes/js/wp-embed-template.js" (line 143).
	 * @since 2.4.0
	 *
	 * @param WP_Post $post Listing Post Object.
	 */
	public static function embed_map( $post ) {
		$listing = listify_get_listing( $post );
		$api_key = listify_get_google_maps_api_key();
		if ( ! $listing->get_lat() || ! $api_key ) {
			return;
		}
		$image_url = 'https://maps.googleapis.com/maps/api/staticmap';
		$image_url = add_query_arg(
			array(
				'size'    => '500x150',
				'markers' => "{$listing->get_lat()},{$listing->get_lng()}",
				'key'     => $api_key,
			), $image_url
		);
?>
<div class="listify-embed-map wp-embed-featured-image">
	<a href="<?php the_permalink(); ?>">
		<img width="500" height="150" class="listify-embed-map-img" src="<?php echo esc_url( $image_url ); ?>">
	</a>
</div>
<?php
	}

}

// Load Class.
Listify_Listing_Embed::init();

