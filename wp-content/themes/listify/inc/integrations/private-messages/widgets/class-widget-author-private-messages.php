<?php
/**
 * Author Private Messages Contact Form/Link.
 *
 * @since Listify 2.4.0
 *
 * @package Listify
 * @category Widget
 * @author Astoundify
 */
class Listify_Widget_Author_Private_Messages extends Listify_Widget {

	/**
	 * Register widget settings.
	 *
	 * @since 2.4.0
	 */
	public function __construct() {
		$this->widget_description = __( 'Link or form to contact author using private messages plugin.', 'listify' );
		$this->widget_id          = 'listify_widget_author_private_messages';
		$this->widget_name        = __( 'Listify - Author: Private Messages', 'listify' );
		$this->widget_areas       = array( 'widget-area-author-main', 'widget-area-author-sidebar' );
		$this->widget_notice      = __( 'Add this widget only in "Author - Main Content" and "Author - Sidebar" widget area.', 'listify' );
		$this->settings           = array(
			'title'     => array(
				'type'  => 'text',
				'std'   => 'Send Message to [username]',
				'label' => __( 'Title:', 'listify' ),
			),
			'display'   => array(
				'type'    => 'select',
				'std'     => 'form',
				'options' => array(
					'form' => __( 'Compose form', 'listify' ),
					'link' => __( 'Link to compose form', 'listify' ),
				),
				'label'   => __( 'Display:', 'listify' ),
			),
			'subject'   => array(
				'type'  => 'text',
				'std'   => '',
				'label' => __( 'Default Subject:', 'listify' ),
			),
			'message'   => array(
				'type'  => 'text',
				'std'   => '',
				'label' => __( 'Default Message:', 'listify' ),
			),
			'link_text' => array(
				'type'  => 'text',
				'std'   => __( 'Send a Message', 'listify' ),
				'label' => __( 'Link text:', 'listify' ),
			),
		);

		parent::__construct();
	}

	/**
	 * Echoes the widget content.
	 *
	 * @since 2.4.0
	 * @access public
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance The settings for the particular instance of the widget.
	 */
	public function widget( $args, $instance ) {
		// Check context.
		if ( ! is_author() ) {
			echo $this->widget_areas_notice(); // WPCS: XSS ok.

			return false;
		}

		// Author ID.
		$author_id = get_queried_object_id();

		// Check if own archive. Cannot send message to self.
		if ( is_user_logged_in() && get_current_user_id() === $author_id ) {
			return false;
		}

		// Widget options.
		$title     = apply_filters( 'widget_title', isset( $instance['title'] ) ? $instance['title'] : '[username]&#39;s Favorites ([count])', $instance, $this->id_base );
		$display   = isset( $instance['display'] ) && 'link' === $instance['display'] ? 'link' : 'form';
		$subject   = isset( $instance['subject'] ) ? $this->replace( $instance['subject'] ) : '';
		$message   = isset( $instance['message'] ) ? $this->replace( $instance['message'] ) : '';
		$link_text = isset( $instance['link_text'] ) ? $this->replace( $instance['link_text'] ) : '';

		ob_start();

		echo $args['before_widget']; // WPCS: XSS ok.

		if ( $title ) {
			echo $args['before_title'] . $this->replace( $title ) . $args['after_title']; // WPCS: XSS ok.
		}

		if ( 'form' === $display ) {
			echo do_shortcode( '[private_message_compose subject="' . $subject . '" message="' . $message . '"]' );
		} else { // Link.
			echo do_shortcode( '[private_message subject="' . $subject . '" message="' . $message . '" title="' . $link_text . '"]' );
		}

		echo $args['after_widget']; // WPCS: XSS ok.

		echo apply_filters( $this->widget_id, ob_get_clean() ); // WPCS: XSS ok.
	}

	/**
	 * Utility to change [username] to current author display name.
	 *
	 * @since 2.4.0
	 *
	 * @param string $content Content to replace.
	 * @return string
	 */
	public function replace( $content ) {
		return str_replace( '[username]', get_the_author_meta( 'display_name', get_queried_object_id() ), $content );
	}

}
