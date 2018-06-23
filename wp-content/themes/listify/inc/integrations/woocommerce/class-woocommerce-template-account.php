<?php
/**
 * WooCommerce "My Account" page.
 *
 * @since 1.5.0
 */
class Listify_WooCommerce_Template_Account {

	/**
	 * Hook in to WordPress
	 *
	 * @since unknown
	 * @return void
	 */
	public static function setup_actions() {
		// remove account navigation
		remove_action( 'woocommerce_account_navigation', 'woocommerce_account_navigation' );

		// add the account avatar
		add_action( 'woocommerce_account_navigation', array( __CLASS__, 'add_avatar_to_dashboard' ), 99 );
	}

	/**
	 * Add the avatar to the My Account dashboard page.
	 *
	 * @since 1.5.0
	 * @return void
	 */
	public static function add_avatar_to_dashboard() {
		if ( '' != WC()->query->get_current_endpoint() ) {
			return;
		}

		$current_user = wp_get_current_user();

		printf(
			'<div class="woocommerce-MyAccount-avatar">%s</div>',
			get_avatar( $current_user->user_email, 100 )
		);
	}

}
add_action( 'after_setup_theme', array( 'Listify_WooCommerce_Template_Account', 'setup_actions' ) );
