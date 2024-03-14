jQuery( document ).ready( function() {

  console.log( 'Scripts Loaded' );

  // Tabbed Content
  jQuery( 'html' ).on( 'click', '#Covid-Tabs button.nav-tab', function() {

    var button = jQuery( this );

    // Get all elements with class="tabcontent" and hide them
    jQuery( 'div.tab-content-area' ).attr( 'style', 'display:none;' );
    // Get all elements with class='tablinks' and remove the class 'active'
    jQuery( '#Covid-Tabs button.nav-tab-active' ).removeClass( 'nav-tab-active' );

    // Set the associated tab to display block
    jQuery( 'div.tab-content-area[data-tab="'+jQuery( button ).attr( 'data-tab' )+'"]' ).attr( 'style', 'display:block;' );
    // Add the class of active to the clicked button
    jQuery( button ).addClass( 'nav-tab-active' );

  });


  jQuery( '#Positioning [data-badge-position]' ).click( function() {

    jQuery( 'input[name="devign_covid_ninteen_badge_location"]' ).attr(
      'value',
      jQuery( this ).attr( 'data-badge-position' )
    );

  });


  jQuery('.posbtn').on('click', function(){
    var radio = jQuery(this).children('input[type="radio"]');
    radio.prop('checked', !radio.prop('checked'));
  });


  jQuery('body').on('click', '.posbtn', function () {
    var self = jQuery(this);

    if (self.hasClass('checked')) {
        jQuery('.posbtn').removeClass('checked');
        return false;
    }

    jQuery('.posbtn').removeClass('checked');

    self.toggleClass('checked');
    hide = false;
  });

  jQuery('input[name="devign_covid_ninteen_theme_color"]').wpColorPicker();
  jQuery('input[name="devign_covid_ninteen_text_color"]').wpColorPicker();
  jQuery('input[name="devign_covid_ninteen_background_color"]').wpColorPicker();
  jQuery('input[name="devign_covid_ninteen_content_text_color"]').wpColorPicker();

});
