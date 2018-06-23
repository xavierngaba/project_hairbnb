<?php
/**
 * The template for displaying a login popup form.
 * This form is loaded in footer for non-logged-in user for easy access.
 *
 * @package Listify
 * @since 2.3.0
 * @version 2.3.0
 */
?>

<?php if ( listify_has_integration( 'woocommerce' ) ) : ?>

	<?php
	$popup_class = 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ? 'popup popup-wide' : 'popup';
	?>

	<div id="listify-login-popup" class="<?php echo esc_attr( $popup_class ); ?>">

		<?php get_template_part( 'woocommerce/myaccount/form-login' ); ?>

	</div>

<?php else : ?>

	<div id="listify-login-popup" class="popup">

		<h2 class="popup-title"><?php echo esc_html( get_theme_mod( 'content-login-title', __( 'Login', 'listify' ) ) ); ?></h2>

		<?php listify_login_form(); ?>

	</div>

<?php endif; ?>
