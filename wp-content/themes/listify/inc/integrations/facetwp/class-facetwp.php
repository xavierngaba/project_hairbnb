<?php
/**
 * FacetWP
 */

class Listify_FacetWP extends listify_Integration {

	public $facets;
	public $template;

	public function __construct() {
		$this->includes = array(
			'class-facetwp-template.php',
		);

		$this->integration = 'facetwp';

		parent::__construct();
	}

	public function setup_actions() {
		add_filter( 'facetwp_gmaps_api_key', array( $this, 'gmaps_api_key' ) );

		// a few things need to run late
		add_action( 'init', array( $this, 'init' ), 12 );

		// register default template and facets
		add_filter( 'facetwp_templates', array( $this, 'register_listings_template' ) );
		add_filter( 'facetwp_facets', array( $this, 'register_facets' ) );

		// proximity filter
		add_filter( 'facetwp_index_row', array( $this, 'index_listify_latlng' ), 10, 2 );

		// Auto detect archives.
		add_filter( 'facetwp_template_use_archive', '__return_true' );

		// Ensure job_listing archives are seen as the main query for FacetWP.
		add_filter( 'facetwp_is_main_query', function( $is_main_query, $query ) {
			if ( $query->is_archive && 'job_listing' === $query->get( 'post_type' ) ) {
				$is_main_query = false;
			}

			return $is_main_query;
		}, 10, 2 );
	}

	/**
	 * Filter FacetWP's Google Maps key.
	 *
	 * @see https://facetwp.com/documentation/proximity/
	 *
	 * @since 1.7.0
	 * @param string $url
	 * @return string $url
	 */
	public function gmaps_api_key( $key ) {
		if ( '' != $key ) {
			return $key;
		}

		return listify_get_google_maps_api_key();
	}

	public function init() {
		$this->template = new Listify_FacetWP_Template;

		add_filter( 'facetwp_render_output', array( $this, 'get_listings' ), 10, 2 );
		add_filter( 'facetwp_query_args', array( $this, 'facetwp_query_args' ), 10, 2 );
	}

	/**
	 * Register default facet template to allow users to get started more easil.y
	 *
	 * @since unknown
	 * @param array $templates
	 * @return array $templates
	 */
	public function register_listings_template( $templates ) {
		$templates[] = array(
			'label'    => 'Listings',
			'name'     => 'listings',
			'query'    => "<?php
return array(
	'post_type' => 'job_listing',
	'orderby' => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
	'order' => 'asc',
	'post_status' => 'publish'
);",
			'template' => '',
		);

		return $templates;
	}

	/**
	 * Register default facets to allow users to get started more easily.
	 *
	 * @since 1.5.0
	 * @param array $facets
	 * @return array $facets
	 */
	public function register_facets( $facets ) {
		$facets[] = array(
			'label'         => 'Keywords',
			'name'          => 'keyword',
			'type'          => 'search',
			'search_engine' => '',
			'placeholder'   => 'What are you looking for?',
		);

		$facets[] = array(
			'label'       => 'Location',
			'name'        => 'location',
			'type'        => 'proximity',
			'placeholder' => 'Location',
			'source'      => 'cf/geolocation_lat',
		);

		$facets[] = array(
			'label'   => 'Category',
			'name'    => 'category',
			'type'    => 'dropdown',
			'source'  => 'tax/job_listing_category',
			'orderby' => 'count',
			'count'   => 10,
		);

		return $facets;
	}

	/**
	 * Get the available facets to be used in a multiselect.
	 *
	 * @since 1.5.0
	 * @return array $facets
	 */
	public function get_facet_choices( $blacklist = array() ) {
		$facets  = FWP()->helper->get_facets();
		$_facets = array();

		if ( empty( $facets ) ) {
			return $_facets;
		}

		foreach ( $facets as $facet ) {
			if ( in_array( $facet['type'], $blacklist ) ) {
				continue;
			}

			$_facets[ $facet['name'] ] = $facet['label'];
		}

		return $_facets;
	}

	public function index_listify_latlng( $params, $class ) {
		if ( 'cf/geolocation_lat' == $params['facet_source'] ) {
			$lat = $params['facet_value'];

			if ( ! empty( $lat ) ) {
				$listing = listify_get_listing( $params['post_id'] );

				$params['facet_value']         = $listing->get_lat();
				$params['facet_display_value'] = $listing->get_lng();
			}
		}

		return $params;
	}

	/**
	 * Get listings associated with the current search.
	 *
	 * Instead of building an HTML template the listings are constructed
	 * via Javascript.
	 *
	 * @since 2.0.0
	 */
	public function get_listings( $return, $params ) {
		$query = FWP()->facet->query;
		$ids   = wp_list_pluck( $query->get_posts(), 'ID' );

		if ( isset( $query->query['post_type'] ) && 'job_listing' !== $query->query['post_type'] ) {
			return $return;
		}

		$return['settings']['listify'] = array(
			'listings' => array(),
		);

		if ( empty( $ids ) ) {
			return $return;
		}

		$return['settings']['listify']['found_jobs']  = true;
		$return['settings']['listify']['found_posts'] = $query->found_posts;

		foreach ( $ids as $id ) {
			$return['settings']['listify']['listings'][] = listify_get_listing( $id )->to_array();
		}

		return $return;
	}

	/**
	 * Suppliment the default query arguments with the same per_page setting
	 * that can be set in "Listings > Settings"
	 *
	 * @since 1.5.0
	 * @param array  $query_args
	 * @param object $facet
	 * @return array $query_args
	 */
	public function facetwp_query_args( $query_args, $facet ) {
		if ( 'listings' != $facet->template['name'] ) {
			return $query_args;
		}

		if ( '' == $query_args ) {
			$query_args = array();
		}

		$defaults = array(
			'posts_per_page' => get_option( 'job_manager_per_page' ),
		);

		$query_args = wp_parse_args( $query_args, $defaults );

		return $query_args;
	}

	/**
	 * Get facets
	 *
	 * Using an array of facet slugs gather the entire facet object
	 *
	 * @since unknown
	 * @return array $_facets
	 */
	public function get_facets( $facets = array() ) {
		if ( empty( $facets ) ) {
			return $facets;
		}

		$_facets = array();

		if ( ! is_array( $facets ) ) {
			$facets = array_map( 'trim', explode( ',', $facets ) );
		}

		foreach ( $facets as $key => $facet_name ) {
			$facet = FWP()->helper->get_facet_by_name( $facet_name );

			if ( ! $facet ) {
				continue;
			}

			$_facets[] = $facet;
		}

		return $_facets;
	}

	public function get_homepage_facets( $facets ) {
		return $this->get_facets( listify_theme_mod( 'listing-archive-facetwp-home', array( 'keyword', 'location', 'category' ) ) );
	}
}

$GLOBALS['listify_facetwp'] = new Listify_FacetWP();
