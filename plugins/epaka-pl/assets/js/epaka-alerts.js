(function( $ ) {
    var alerts = [];

    window.addAlert = function addAlert(text,type){
        alerts.push({
            message: text,
            type: type
        });
        refreshAlerts();
    }

    function refreshAlerts(){
        $("#epaka-alerts .epaka-alert").remove();
        
        $(alerts).each(function(index){
            var alertElement = document.createElement('div');
            $(alertElement).addClass('epaka-alert epaka-alert-'+this.type);
            $(alertElement).html('<span class="epaka-alert-closebtn">&times;</span>'+this.message);

            $("#epaka-alerts").append(alertElement);

            var delAlertTimeout = setTimeout(function() { 
                alerts.splice(index,1);
                $(alertElement).remove();
            }, 10000);

            $(alertElement).find('.epaka-alert-closebtn').click(function(){
                alerts.splice(index,1);
                $(this).parent().remove();
                clearTimeout(delAlertTimeout);
            });

           
        });
    }

    $( window ).load(function() {
        refreshAlerts();
    });
})( jQuery );