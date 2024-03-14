jQuery(function($) {

	"use strict";

	var adv_windows = $( window ),
		adv_navWrapper = $('.fl-node-<?php echo esc_attr( $id ); ?> .tnit-advance-menu-wrapper');

	<?php
	$hambruger_show_on = ( 'medium' === $settings->responsive_breakpoint ) ? $global_settings->medium_breakpoint : ( ( 'responsive' === $settings->responsive_breakpoint ) ? $global_settings->responsive_breakpoint : 0 );

	if ( 'all' !== $settings->responsive_breakpoint && 'accordion' !== $settings->menu_layout ) {
		?>

	checkDimension();
	adv_windows.resize(function() {
		checkDimension();
	});

<?php } ?>

	$(".fl-node-<?php echo esc_attr( $id ); ?> div:not(.tnit-hamburger-menu-expand) .tnit-menu-has-child").hover(function(){
		$(this).addClass('active');
	}, function(){
		$(this).removeClass('active');
	});

	$('.fl-node-<?php echo esc_attr( $id ); ?> .tnit-advance-menu-toggle-wrapper').on('click',function (e){
		e.preventDefault();
		e.stopPropagation();

		$(this).parent().find('nav').slideToggle();
		$(this).parent().toggleClass('tnit-open-menu');
	});

	$('.fl-node-<?php echo esc_attr( $id ); ?> .tnit-advance-menu-close').on('click',function (){
		$(this).parents('.tnit-advance-menu-wrapper').removeClass('tnit-open-menu');
	});

	$('body').delegate('.fl-node-<?php echo esc_attr( $id ); ?> .tnit-advance-menu-dropdown-toggle','click',function (e){
		e.preventDefault();
		e.stopPropagation();

		if($(this).hasClass('active')){
			$(this).removeClass('active');
			$(this).parents('.tnit-menu-has-child').find('.tnit-advance-sub-menu').slideUp();
		}else{
			$(this).addClass('active');
			$(this).parents('.tnit-menu-has-child').find('.tnit-advance-sub-menu').slideDown();
		}
	});

	<?php if ( 'all' !== $settings->responsive_breakpoint && 'accordion' !== $settings->menu_layout ) { ?>

	function checkDimension(){

		if(adv_windows.width() > <?php echo esc_attr( $hambruger_show_on ); ?>){
			adv_navWrapper.find('nav').removeClass('.fl-node-<?php echo esc_attr( $id ); ?> tnit-hamburger-menu-expand');
			<?php if ( 'accordion' === $settings->responsive_layout ) { ?>
			adv_navWrapper.find('nav').show();
			<?php } ?>
			$('.fl-node-<?php echo esc_attr( $id ); ?> .tnit-advance-sub-menu').show();
		}else{
			adv_navWrapper.find('nav').addClass('tnit-hamburger-menu-expand');
			<?php if ( 'accordion' === $settings->responsive_layout ) { ?>
			adv_navWrapper.find('nav').hide();
			<?php } ?>
			$('.fl-node-<?php echo esc_attr( $id ); ?> .tnit-advance-sub-menu').hide();
		}

	}

	<?php } ?>

});
