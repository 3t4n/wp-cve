(function ($) {
    $(document).ready(function () {
        $('.stm-header .modal').each(function(){
            var modal = $(this);
            var clonedModal = modal.clone();
            modal.remove();
            $('body').append(clonedModal);
        });
    });
})(jQuery)