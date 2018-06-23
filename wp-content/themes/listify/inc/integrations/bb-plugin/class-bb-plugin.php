<?php
/**
 * Beaver Builder Integration.
 *
 * @since 2.4.0
**/
class Listify_Bb_Plugin extends listify_Integration {

	/**
	 * Constructor Class.
	 *
	 * @since 2.4.0
	 */
	public function __construct() {
		$this->includes    = array();
		$this->integration = 'bb-plugin';
		parent::__construct();
	}

	/**
	 * Setup Action
	 *
	 * @since 2.4.0
	 */
	public function setup_actions() {

		// Use proper single template.
		add_filter( 'template_include', array( $this, 'single_template' ), 1000 );

		// Template Action.
		add_action( 'listify_bb_plugin_template_init', array( $this, 'template_init' ) );

		// Exclude Widgets.
		add_filter( 'fl_get_wp_widgets_exclude', array( $this, 'exclude_widgets' ) );
	}

	/**
	 * Single Template
	 *
	 * @since 2.4.0
	 */
	public function single_template( $template ) {
		global $post;
		if ( 'string' === gettype( $template ) && $post && 'fl-builder-template' === $post->post_type ) {
			$page = locate_template( array( 'single-fl-builder-template.php', 'page.php' ) );
			if ( ! empty( $page ) ) {
				return $page;
			}
		}
		return $template;
	}

	/**
	 * Template Init.
	 *
	 * @since 2.4.0
	 */
	public function template_init() {

		// Body Class.
		add_filter( 'body_class', array( $this, 'body_class' ) );

		// Load script.
		wp_enqueue_style( 'listify-bb-plugin', self::get_url() . 'css/listify-bb-plugin.css', array(), listify_get_version() );
	}

	/**
	 * Body Class.
	 *
	 * @since 2.4.0
	 *
	 * @param array $classes Body Classes.
	 * @return array
	 */
	public function body_class( $classes ) {
		$classes[] = 'listify-beaverbuilder';
		return $classes;
	}

	/**
	 * Exclude Widgets
	 *
	 * @since 2.4.0
	 *
	 * @param array $widgets Widgets to exclude
	 * @return array
	 */
	public function exclude_widgets( $widgets ) {
		// inc/authors/widgets.
		$widgets[] = 'Listify_Widget_Author_Biography';
		$widgets[] = 'Listify_Widget_Author_Listings';

		// inc/widgets.
		$widgets[] = 'Listify_Widget_Ad';
		$widgets[] = 'Listify_Widget_Call_To_Action';
		$widgets[] = 'Listify_Widget_Feature_Callout';
		$widgets[] = 'Listify_Widget_Features';
		$widgets[] = 'Listify_Widget_Recent_Posts';

		// inc/integrations/.
		$widgets[] = 'Listify_Widget_Author_Favorites';
		$widgets[] = 'Listify_Widget_Author_Private_Messages';
		$widgets[] = 'Listify_Widget_Listing_Bookings';
		$widgets[] = 'Listify_Widget_Map_Listings';
		$widgets[] = 'Listify_Widget_Recent_Listings';
		$widgets[] = 'Listify_Widget_Search_Listings';
		$widgets[] = 'Listify_Widget_Tabbed_Listings';
		$widgets[] = 'Listify_Widget_Taxonomy_Image_Grid';
		$widgets[] = 'Listify_Widget_Term_Lists';
		$widgets[] = 'Listify_Widget_Listing_Author';
		$widgets[] = 'Listify_Widget_Listing_Business_Hours';
		$widgets[] = 'Listify_Widget_Listing_Comments';
		$widgets[] = 'Listify_Widget_Listing_Content';
		$widgets[] = 'Listify_Widget_Listing_Gallery';
		$widgets[] = 'Listify_Widget_Listing_Gallery_Slider';
		$widgets[] = 'Listify_Widget_Listing_Map';
		$widgets[] = 'Listify_Widget_Listing_Related_Listings';
		$widgets[] = 'Listify_Widget_Listing_Social_Profiles';
		$widgets[] = 'Listify_Widget_Listing_Video';
		$widgets[] = 'Listify_Widget_Listing_Labels';
		$widgets[] = 'Listify_Widget_WPJMLP_Pricing_Table';
		$widgets[] = 'Listify_Widget_Listing_Products';
		$widgets[] = 'Listify_Widget_Listing_Products_Main';
		$widgets[] = 'Listify_Widget_WCPL_Pricing_Table';
		return $widgets;
	}
}

$GLOBALS['listify_bb_plugin'] = new Listify_Bb_Plugin();
