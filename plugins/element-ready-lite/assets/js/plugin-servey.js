(function($) {

    var deactivate_url = '#';
    var _pass = 'https://quomodosoft.com/wp-json/qs-plugins-servey/v1/data'; 
    document.getElementById('deactivate-element-ready-lite').addEventListener('click', function(e) {
        e.preventDefault();
        deactivate_url = e.target.href;
        document.getElementById('element-ready-deactivate-servey-overlay').classList.add('element-ready-deactivate-servey-is-visible');
        document.getElementById('element-ready-deactivate-servey-modal').classList.add('element-ready-deactivate-servey-is-visible');
    });
   
    document.getElementById('element-ready-dialog-lightbox-skip').addEventListener('click', function(e) {
        this.disabled = true;
        window.location.replace(deactivate_url);
    });

    document.getElementById('element-ready-dialog-lightbox-submit').addEventListener('click', function(e) {

        this.disabled = true;
        
        var reason = '';

        var data = $('.element-ready-deactivate-form-wrapper').serializeArray();
        $.each( data, function( index, value ){
           
           if('reason_key' == value.name && value.value !='' ){
            reason = value.value;
           }

           if('reason_other' == value.name && value.value !=''){
            reason = value.value;
           }

        });
       
       
        $.ajax({
            url: _pass,
            dataType: 'jsonp',
            data: {
                'plugin-type':'element-ready-lite',
                'reason':reason,
                'domain':window.location.hostname
            },
            success: function (data, textStatus) {
             
            },
            jsonpCallback: 'mycallback'
        });

        window.location.replace(deactivate_url);
    });

    document.getElementById('element-ready-deactivate-servey-overlay').addEventListener('click', function() {
    document.getElementById('element-ready-deactivate-servey-overlay').classList.remove('element-ready-deactivate-servey-is-visible');
    document.getElementById('element-ready-deactivate-servey-modal').classList.remove('element-ready-deactivate-servey-is-visible');
    });

})(jQuery);