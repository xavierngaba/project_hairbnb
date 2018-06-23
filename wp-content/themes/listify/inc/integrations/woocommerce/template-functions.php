<?php
/**
 * WooCommerce template functions.
 *
 * Functions for the templating system.
 *
 * @since 2.5.0
 *
 * @package Listify
 * @category Template
 * @author Astoundify
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Update custom cart template parts.
 *
 * @since 2.5.0
 *
 * @param array $fragments Fragments to update.
 * @return array $fragments
 */
function listify_woocommerce_cart_count_fragments( $fragments ) {
	$fragments['.current-cart-count'] = '<span class="current-cart-count">' . absint( WC()->cart->get_cart_contents_count() ) . '</span>';

	return $fragments;
}
