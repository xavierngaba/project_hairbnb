<?php
/**
 * Private Messages
 *
 * @since 1.8.0
 */
class Listify_PrivateMessages extends Listify_Integration {

	/**
	 * Constructor
	 *
	 * @since 1.8.0
	 */
	public function __construct() {
		$this->integration = 'private-messages';

		$this->includes = array(
			'widgets/class-widget-author-private-messages.php',
		);

		parent::__construct();
	}

	/**
	 * Hook in to WordPress
	 *
	 * @since 1.8.0
	 */
	public function setup_actions() {
		// Script.
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ), 99 );

		// Menu item.
		add_filter( 'walker_nav_menu_start_el', array( $this, 'menu_item' ), 10, 4 );
		add_filter( 'nav_menu_css_class', array( $this, 'menu_item_class' ), 10, 3 );

		// Register Widgets.
		add_action( 'widgets_init', array( $this, 'register_widgets' ) );
	}

	/**
	 * Scripts
	 *
	 * @since 2.3.0
	 */
	public function wp_enqueue_scripts() {
		wp_register_script( 'listify-private-messages', self::get_url() . 'js/listify-private-messages.js', array( 'jquery' ), listify_get_version(), true );

		if ( pm_get_option( 'pm_wpjm_contact_method', true ) && is_singular( 'job_listing' ) ) {
			wp_enqueue_editor();
			wp_enqueue_script( 'listify-private-messages' );
		}
	}

	/**
	 * Custom private messages menu item.
	 *
	 * Look for a menu item with a title of `{{private-messages}}` and replace the
	 * content with an icon and unread status.
	 *
	 * @since Listify 1.8.0
	 *
	 * @param string $item_output
	 * @param object $item
	 * @param int    $depth
	 * @param array  $args
	 * @return string $item_output
	 */
	public function menu_item( $item_output, $item, $depth, $args ) {
		if ( '{{private-messages}}' != $item->title ) {
			return $item_output;
		}

		$user       = wp_get_current_user();
		$count      = pm_get_unread_count( $user->ID );
		$has_unread = $count > 0;

		ob_start();
?>

<i class="mail-icon<?php echo esc_attr( $has_unread ? ' mail-icon--unread' : '' ); ?>"></i>
<span class="screen-reader-text">
	<?php printf( __( '%d Unread Messages', 'listify' ), $count ); ?>
</span>

<?php
		$mail = ob_get_clean();

		$item_output = str_replace( '{{private-messages}}', $mail, $item_output );

		return $item_output;
	}

	/**
	 * If the menu item has the `{{private-messages}}` tag add a custom class to the item.
	 *
	 * @since Listify 1.8.0
	 *
	 * @param array  $classes
	 * @param object $item
	 * @param array  $args
	 * @return array $classes
	 */
	public function menu_item_class( $classes, $item, $args ) {
		if ( 'primary' != $args->theme_location ) {
			return $classes;
		}

		if ( '{{private-messages}}' != $item->title || ! is_user_logged_in() ) {
			return $classes;
		}

		$classes[] = 'private-message-menu-item';

		return $classes;
	}

	/**
	 * Register the widgets for the main content and sidebar of the
	 * author.php page template.
	 *
	 * @since 2.4.0
	 */
	public function register_widgets() {
		register_widget( 'Listify_Widget_Author_Private_Messages' );
	}

}

new Listify_PrivateMessages();
