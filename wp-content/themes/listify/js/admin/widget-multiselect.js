/**
 * Widget Select Multiple.
 *
 * @since 2.2.0
 */
(function( window, undefined ){

	window.wp = window.wp || {};
	var document = window.document;
	var $ = window.jQuery;

	/**
	 * Bind items to to the DOM.
	 *
	 * @since 2.2.0
	 */
	$(function() {

		if ( ! $.isFunction( $.fn.chosen ) ) {
			return;
		}

		// Load chosen on load.
		$( document ).ready( function() {
			$( '.listify-multiselect' ).chosen({
				search_contains: true
			});

			$( '.listify-multiselect-wrap .chosen-container' ).css( 'width', '100%' );
		});

		// Attemp to re-load on widget add/update.
		$( document ).on( 'widget-added widget-updated', function( e, widget_el ){
			widget_el.find( '.listify-multiselect' ).chosen({
				search_contains: true
			});

			widget_el.find( '.listify-multiselect-wrap .chosen-container' ).css( 'width', '100%' );
			widget_el.find( '.listify-multiselect-wrap .chosen-container:not(:first-of-type)' ).remove();
		});

	});

})( window );
