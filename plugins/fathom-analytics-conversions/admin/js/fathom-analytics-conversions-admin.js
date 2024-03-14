(function ($) {
    'use strict';

    /**
     * All of the code for your admin-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */

    $(window).load(function () {
        $('#nf-drawer-content').on('DOMSubtreeModified', function () {
            $("input#fathom_analytics").prop("readonly", true);
        });
    });

    $(function () {
        let _site_id = $('#fac4wp-options_fac-site-id');
        $('.installed_tc_elsewhere input').on('change', function () {
            if (this.checked) {
                _site_id.prop('readonly', false);
            } else _site_id.prop('readonly', true);
        });
    });

    $(document).on('click','.addNewRow', function (e) {
        e.preventDefault();
        let wrap = $(this).closest('td'),
            wrapO = wrap.find('table tbody'),
            newRow = $(document.createElement('tr')).attr("class", 'table_row');
        //var options = ldcae_object.courses;
        let counter = $(this).attr('data-counter');
        newRow.after().html('<td>' +
            '<input type="text" name="fac4wp-options[classes_ids]['+counter+'][name]" class="classes_ids_name" required>' +
            '</td>' +
        '<td class="classes_ids_class_td">' +
            '<input type="text" name="fac4wp-options[classes_ids]['+counter+'][class]" id="course_0" class="classes_ids_class" required>' +
        '</td>' +
        '<td>' +
            '<input type="number" name="fac4wp-options[classes_ids]['+counter+'][value]" value="" class="classes_ids_value">' +
        '</td>');
        newRow.appendTo(wrapO);
        $(this).attr('data-counter', Number(counter)+1);
        //$('#course'+counter).select2();
    });
    $(document).on('click', '.removeRow', function(e) {
        e.preventDefault();
        var wrapCol = $(this).closest('.table_row');
        wrapCol.remove();
    });
    //$('.domain_course_2').select2();
})(jQuery);
