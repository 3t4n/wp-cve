wp.customize.controlConstructor['gridchamp-sortable-section'] = wp.customize.Control.extend({
    ready: function() {
        var control = this;
        // Init sortable.
        jQuery( control.container.find( 'ul.walkermag-sortable-list' ).first() ).sortable({
            update: function() {
                control.setting.set( control.sectionNewValue() );
            }
        }).disableSelection().find( 'li' ).each( function() {
        }).click( function() {
            control.setting.set( control.sectionNewValue() );
        });
    },

    /**
     * Get the new value.
     * Return value as an Array
     */
    sectionNewValue: function() {
        var items  = jQuery( this.container.find( 'li' ) ),
            newValue = [];
        _.each( items, function( item ) {
            if ( ! jQuery( item ).hasClass( 'invisible' ) ) {
                newValue.push( jQuery( item ).data( 'value' ) );
            }
        });
        return newValue;
    }
});