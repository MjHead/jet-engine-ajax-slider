<?php
/**
 * Plugin Name: JetEngine - AJAX slider
 * Plugin URI:
 * Description:
 * Version:     1.0.0
 * Author:      Crocoblock
 * Author URI:  https://crocoblock.com/
 * License:     GPL-3.0+
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die();
}

add_action( 'jet-engine/listings/frontend-scripts', function() {

	ob_start();
	?>
	( function( $ ) {

		"use strict";

		var toPage = 1;
		var handleMore = function( event ) {
			event.preventDefault();
			getListing( $( event.target ).closest( '.jet-listing-grid__items' ), this.direction );
		};

		var getListing = function( $container, direction ) {

			direction = direction || 'next';

			if ( 'next' === direction ) {
				toPage++;
			} else {
				toPage--;
			}

			var settings = $container.data( 'nav' );

			JetEngine.ajaxGetListing( {
				handler: 'listing_load_more',
				container: $container,
				masonry: false,
				slider: false,
				append: false,
				query: settings.query,
				widgetSettings: settings.widget_settings,
				page: toPage,
			} );

		}
		
		$( document ).on( 'click', '.jet-listing-ajax-next', handleMore.bind( {
			direction: 'next',
		} ) );

		$( document ).on( 'click', '.jet-listing-ajax-prev', handleMore.bind( {
			direction: 'prev',
		} ) );

		$( document ).on( 'jet-engine-on-add-to-store', function( event, $button, args ) {
			if ( 'store-2' === args.store.slug ) {
				getListing( $button.closest( '.jet-listing-grid__items' ), 'next' );
			}
		} );


	}( jQuery ) );
	<?php
	$script = ob_get_clean();

	wp_add_inline_script( 'jet-engine-frontend', $script );

} );
