(function ($) {

    $(document).ready(function(){

        $('#rapidload-dashboard input.rapidload-modules').change(function(){

            wp.ajax.post('rapidload_module_activation', {
                module : $(this).val(),
                activate : this.checked ? 'on' : 'off'
            }).then(function (i){
               window.location.reload();
            });

        });

    })

}(jQuery))