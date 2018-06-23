<?php
/**
 * WooCommerce template hooks.
 *
 * Action/filter hooks used for WooCommerce functions/templates.
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

// Cart fragments on "Add to Cart" archives.
add_filter( 'woocommerce_add_to_cart_fragments', 'listify_woocommerce_cart_count_fragments' );
