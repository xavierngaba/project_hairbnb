<?php
/**
 * Search Filters Sort Defaults
 *
 * @uses $wp_customize
 * @since 2.4.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$wp_customize->add_setting(
	'listing-filters-sort-default', array(
		'default' => 0,
	)
);

// Sort options.
$options = apply_filters( 'listify_filters_sort_by', listify_get_sort_options() );
array_unshift( $options, __( 'Default Sorting', 'listify' ) );

$wp_customize->add_control(
	'listing-filters-sort-default', array(
		'label'    => __( 'Default Sorting', 'listify' ),
		'type'     => 'select',
		'choices'  => $options,
		'priority' => 12,
		'section'  => 'search-filters',
	)
);
