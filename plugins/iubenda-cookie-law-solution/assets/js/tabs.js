/**
 * Main iubenda tabs functions
 *
 * @package  Iubenda
 */

(function (global, $) {

	$( document ).on(
		'click',
		'.tabs__nav__item',
		function (e) {

			var navItem      = $( this );
			var parent       = navItem.closest( '.tabs' );
			var target       = parent.find( '.tabs__target[data-target="' + navItem.data( 'target' ) + '"]' );
			var groupNavs    = parent.find( '.tabs__nav__item[data-group="' + navItem.data( 'group' ) + '"]' );
			var groupTargets = parent.find( '.tabs__target[data-group="' + navItem.data( 'group' ) + '"]' );

			groupNavs.removeClass( 'active' );
			groupTargets.removeClass( 'active' );
			navItem.addClass( 'active' );

			groupTargets.hide();

			target.addClass( 'active' );
			target.show();

			// target.find('.tabs__nav__item:first-of-type').click();.
		}
	);

	$( document ).ready(
		function () {
			// $('.tabs__nav__item:first-of-type').click();.
		}
	);
}(window, jQuery));
