<?php
/**
 * FacetWP filters for the homepage.
 *
 * @since 1.9.0
 * @package Listify
 */

global $listify_facetwp, $listify_widget_search_listings_instance;

// Make sure FacetWP Assets are loaded.
add_filter( 'facetwp_load_assets', '__return_true' );

// Get widget instance.
$instance = $listify_widget_search_listings_instance;

// Active facets for this widgets.
$facets_list = isset( $instance['facets'] ) ? array_map( 'trim', explode( ',', $instance['facets'] ) ) : listify_theme_mod( 'listing-archive-facetwp-home', array( 'keyword', 'location', 'category' ) );

// Load active facets datas.
$facets  = array();
$_facets = $listify_facetwp->get_homepage_facets( $facets );
if ( is_array( $_facets ) && $_facets ) {
	foreach ( $_facets as $_facet ) {
		if ( in_array( $_facet['name'], $facets_list ) ) {
			$facets[] = $_facet;
		}
	}
}
?>

<div class="job_search_form job_search_form--count-<?php echo absint( count( $facets ) ); ?>">
	<?php echo $listify_facetwp->template->output_facet_html( $facets ); // WPCS: XSS ok. ?>

	<div class="facetwp-submit">
		<input type="submit" value="<?php esc_attr_e( 'Search', 'listify' ); ?>" onclick="facetWpRedirect()" />
	</div>

	<div style="display: none;">
		<?php echo do_shortcode( '[facetwp template="listings"]' ); ?>
	</div>

</div>

<script>
function facetWpRedirect() {
	FWP.parse_facets();
	FWP.set_hash();
	window.location.href = '<?php echo listify_get_listings_page_url(); ?>?' + FWP.build_query_string();
}

(function( window, undefined ){
	var $ = window.jQuery;
	var document = window.document;

	$(document).on( 'keyup', '.facetwp-facet .facetwp-search', function(e) {
		if ( e.keyCode == '13' ) {
			facetWpRedirect();
		}
	} );
})( window );
</script>
