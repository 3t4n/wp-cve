jQuery(document).ready(function(){
    var teamPlus = jQuery('.plus_click');
    teamPlus.on('click', function (e) {
    	e.preventDefault();
    	jQuery( this ).parent('.htmegavc-team-click-action').toggleClass('visible');

    	jQuery( this ).toggleClass('team-minus');
    });

});