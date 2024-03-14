
(function($){
    $(document).ready(function(){

        var panel = $('div[data-vc-shortcode="idx_slideshow"]');

        var element = panel.find('.wpb_vc_param_value.wpb-select[name="display"]');
        var dependency_element = panel.find('div[data-vc-shortcode-param-name="sort"]');
        
        setDependency();
        
        element.change(function(){
            setDependency();
        });
        
        function setDependency(){
            if(element.val() == 'all'){
                dependency_element.hide();
            } else {
                dependency_element.show();
            }
        }
    })
})(jQuery)
