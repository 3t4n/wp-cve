(function($){
    $(document).ready(function(){
        var widget = $('.vc_edit_form_elements');
        var checkbox_group = widget.find('.checkbox_group');
        var input = checkbox_group.find('input[fmc-type="text"]');
        var checkboxes = checkbox_group.find('input[type="checkbox"]');
        var value = [];

        console.log('init cb', );
        

        checkboxes.each(function(index){
            var cb = $(this);
            if(cb.is(':checked')){
                value[index] = cb.val();
            } else {
                value[index] = '';
            }

            cb.click(function(){
                if(cb.is(':checked')){
                    set_value(cb.val(), index, 1);
                } else {
                    set_value(cb.val(), index, 0);
                }
            });
        });

        function set_value(val, index, n){
            var new_val = [];
            for (let i = 0; i < value.length; i++) {
                if(i == index){
                    if(n === 1){
                        value[i] = val;
                    } else {
                        value[i] = '';
                    }
                }
            }
            console.log(value);
            new_val = value.filter(element => element !== '');
            input.val(new_val.join(','));
        }
    })
})(jQuery)
