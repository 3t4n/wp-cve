(function ($){
    $(document).ready( function () {
        $(document).on('click', '.berocket_compare_products_styler .all_theme_default', function (event) {
            event.preventDefault();
            $table = $(this).parents('table').first();
            $table.find('.br_colorpicker_default').trigger('click');
            $table.find('select').each( function( i, o ) {
                $(o).val($(o).data('default'));
            });
            $table.find('input[type=text]').each( function( i, o ) {
                $(o).val($(o).data('default'));
            });
        });
    });
})(jQuery);
