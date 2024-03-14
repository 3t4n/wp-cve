jQuery(function () {
  jQuery( document ).ready(function() {
    jQuery("select#sgwpmail-cf7-labels").selectize({
          plugins: ["restore_on_backspace", "clear_button"],
          delimiter: " - ",
          persist: false,
          maxItems: null,
    });

    if ( jQuery("#sgwpmail-cf7-enable").attr('checked') === 'checked' ) {
        jQuery(".sgwpmail-cf7-checkbox-toggle").show();
        jQuery(".sgwpmail-cf7-labels-dropdown").show();
        if( jQuery("#sgwpmail-cf7-checkbox-toggle").attr('checked') === 'checked' ) {
          jQuery(".sgwpmail-cf7-checkbox-label-input").css( "display", "flex");
        }
    }

    if ( jQuery("#sgwpmail-cf7-checkbox-toggle").attr('checked') === 'checked' && jQuery("#sgwpmail-cf7-enable").attr('checked') === 'checked'  ) {
        jQuery(".sgwpmail-cf7-checkbox-label-input").css( "display", "flex");
    }

    jQuery("#sgwpmail-cf7-enable").change( function($this){
      if ( $this.currentTarget.checked ) {
        jQuery(".sgwpmail-cf7-checkbox-toggle").show();
        jQuery(".sgwpmail-cf7-labels-dropdown").show();
        if( jQuery("#sgwpmail-cf7-checkbox-toggle").attr('checked') === 'checked' ) {
          jQuery(".sgwpmail-cf7-checkbox-label-input").css( "display", "flex");
        }
      } else {
        jQuery(".sgwpmail-cf7-checkbox-toggle").hide();
        jQuery(".sgwpmail-cf7-checkbox-label-input").hide();
        jQuery(".sgwpmail-cf7-labels-dropdown").hide();
      }
    });

    jQuery("#sgwpmail-cf7-checkbox-toggle").change( function($this){
      if ( $this.currentTarget.checked ) {
        jQuery(".sgwpmail-cf7-checkbox-label-input").css( "display", "flex");
      } else {
        jQuery(".sgwpmail-cf7-checkbox-label-input").hide();
      }
    });
  });
});