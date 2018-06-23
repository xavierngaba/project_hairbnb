<?php
/**
 * Home: Search Listings
 *
 * @since Listify 1.0.0
 */
class Listify_Widget_Search_Listings extends Listify_Widget {

	/**
	 * Constructor
	 */
	public function __construct() {

		$this->widget_description = __( 'Display a search form to search listings', 'listify' );
		$this->widget_id          = 'listify_widget_search_listings';
		$this->widget_name        = __( 'Listify - Page: Search Listings', 'listify' );
		$this->settings           = array(
			'title'       => array(
				'type'  => 'text',
				'std'   => '',
				'label' => __( 'Title:', 'listify' ),
			),
			'description' => array(
				'type'  => 'text',
				'std'   => '',
				'label' => __( 'Description:', 'listify' ),
			),
		);

		if ( listify_has_integration( 'facetwp' ) ) {
			$this->settings['facets'] = array(
				'type'  => 'text',
				'std'   => implode( ',', (array) get_theme_mod( 'listing-archive-facetwp-defaults', array( 'keyword', 'location', 'category' ) ) ),
				'label' => __( 'Facets:', 'listify' ),
			);
		}

		parent::__construct();

	}

	/**
	 * widget function.
	 *
	 * @see WP_Widget
	 * @access public
	 * @param array $args
	 * @param array $instance
	 * @return void
	 */
	function widget( $args, $instance ) {
		global $listify_widget_search_listings_instance;
		$listify_widget_search_listings_instance = $instance;

		extract( $args );

		$title       = apply_filters( 'widget_title', isset( $instance['title'] ) ? $instance['title'] : '', $instance, $this->id_base );
		$description = isset( $instance['description'] ) ? $instance['description'] : false;

		if ( $description && strpos( $after_title, '</div>' ) ) {
			$after_title = str_replace( '</div>', '', $after_title ) . '<p class="home-widget-description">' . $description . '</p></div>';
		}

		ob_start();

		echo $before_widget; // WPCS: XSS ok.

		if ( $title ) {
			echo $before_title . $title . $after_title; // WPCS: XSS ok.
		}

		echo '<div class="search-filters-home">' . listify_partial_search_filters_home() . '</div>'; // WPCS: XSS ok.

		echo $after_widget; // WPCS: XSS ok

		$content = ob_get_clean();

		echo apply_filters( $this->widget_id, $content ); // WPCS: XSS ok
		$listify_widget_search_listings_instance = null;
	}
}
