/*!
 * @author    Panagiotis Vagenas <pan.vagenas@gmail.com>
 * @link      http://erp.xdark.eu
 * @copyright 2014 Panagiotis Vagenas <pan.vagenas@gmail.com>
 */
(function($) {

    $(function() {
        /***********************************************************************
         * Display layout
         **********************************************************************/

        function displayLayoutLoader() {
            $('.dsplLayout').unbind('change')
                    .change(
                            function() {
                                var inst = $(this).attr('data-widinst');
                                $('.wid-inst-'+inst).children(".templateSettings[data-template!='" + $(this).val() + "']").hide().children().prop('disabled', true);
                                $('.wid-inst-'+inst).children(".templateSettings[data-template='" + $(this).val() + "']").show().children().prop('disabled', false);
                            });
            $('.dsplLayout').trigger('change');
        }
        /**
         * Show templates options 
         */
        displayLayoutLoader();
        $(document).ajaxStop(function() { 
            displayLayoutLoader();
        });
    });
}(jQuery));

