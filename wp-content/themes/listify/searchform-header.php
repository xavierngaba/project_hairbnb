<?php
/**
 * The template for displaying the header search form. Searches listings.
 *
 * @since 1.0.0
 *
 * @package Listify
 * @category Template
 * @author Astoundify
 */

// Default search form.
$input_name = 's';
$action     = home_url();

// WP Job Manager.
if ( listify_has_integration( 'wp-job-manager' ) ) {
	$input_name = 'search_keywords';
	$action     = listify_get_listings_page_url();

	// FacetWP.
	if ( listify_has_integration( 'facetwp' ) ) {
		$fwp        = (array) get_theme_mod( 'facetwp-header-search-facet', array( 'keyword' ) );
		$input_name = FWP()->helper->get_setting( 'prefix' ) . current( $fwp );
	}
}
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( $action ); ?>">
	<label>
		<span class="screen-reader-text"><?php esc_html_e( 'Search for:', 'listify' ); ?></span>
		<input type="search" class="search-field" placeholder="<?php esc_attr_e( 'Search', 'listify' ); ?>" value="" name="<?php echo esc_attr( $input_name ); ?>" title="<?php echo esc_attr_e( 'Search for:', 'listify' ); ?>" />
	</label>
	<button type="submit" class="search-submit"></button>
</form>
