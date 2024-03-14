var modJsLiveMatchesTimer;

function modJsLiveMatchesTimerStart() {
    modJsLiveMatchesTimer = setInterval(updLiveMatchScore, 10000);
}

function modJsLiveMatchesTimerStop() {
    clearInterval(modJsLiveMatchesTimer);
}

function updLiveMatchScore() {
    var Items = new Array();
    jQuery(".fa-heart-o").each(function(){
        var itemID = jQuery(this).attr("data-id");
        Items.push(itemID);
    });
    jQuery(".fa-heart").each(function(){
        var itemID = jQuery(this).attr("data-id");
        Items.push(itemID);
    });
    var data = {
        'action': 'joomsport_liveshrtc_reload',
        'matches': Items
    };

    jQuery.post(ajaxurl, data, function(response) {
        var res = JSON.parse(response);
        if(res){
            for(var key in res){
                jQuery("#modJsUpdScore"+key).html(res[key]);
                console.log(res[key]);
            }
        }
    });

}

function reCheckFavourites(){
    var favMatches = JSON.parse(localStorage.getItem("favMatches"));

    if(!Array.isArray(favMatches)){
        favMatches = new Array();
    }

    jQuery(".fa-heart-o").each(function(){
        var itemID = jQuery(this).attr("data-id");
        var index = favMatches.indexOf(itemID);
        if (index > -1) {
            jQuery(this).removeClass("fa-heart-o").addClass("fa-heart");
        }
    });
    jQuery("#modJsFavMatchCounter").html(parseInt(favMatches.length));
}
function chngFilterLiveMatches(val){
    jQuery("#modJSLiveMatchesPrev").prop('disabled', true);
    jQuery("#modJSLiveMatchesNext").prop('disabled', true);
    jQuery("#modJSLiveMatchesContainer").fadeOut(300);
    var played = jQuery("#modJSLiveMatchesFiltersSelect").val();
    modJsLiveMatchesTimerStop();
    var data = {
        'action': 'joomsport_liveshrtc_reload_matches',
        'jdate': val,
        'played': played,
    };

    jQuery.post(ajaxurl, data, function(response) {

            jQuery("#modJSLiveMatchesContainer").html(response);
            jQuery("#modJSLiveMatchesContainer").fadeIn(300);
            jQuery("#modJSLiveMatchesPrev").prop('disabled', false);
            jQuery("#modJSLiveMatchesNext").prop('disabled', false);
            reCheckFavourites();
            modJsLiveMatchesTimerStart();

    });
    jQuery("#modJSLiveMatchesTabAll").removeClass("activeTab");
    jQuery("#modJSLiveMatchesTabFav").removeClass("activeTab");

    jQuery("#modJSLiveMatchesTabAll").addClass("activeTab");

}
jQuery(document).ready(function() {

    jQuery("#modJSLiveMatchesPrev").on("click", function () {
        curDate = jQuery("#mod_filter_date").val();
        dateObj = new Date(curDate.substr(0,4), curDate.substr(5,2)-1, curDate.substr(8,2));
        console.log(curDate.substr(0,4), curDate.substr(5,2), curDate.substr(8,2));
        dateObj.setDate(dateObj.getDate()-1);
        month = dateObj.getMonth()+1;
        if(month < 10){
            month = "0" + month;
        }
        day = dateObj.getDate();
        if(day < 10){
            day = "0" + day;
        }

        console.log(dateObj.getFullYear()  + "-" + month + "-" + day);
        var datestring = dateObj.getFullYear()  + "-" + month + "-" + day;
        jQuery("#mod_filter_date").val(datestring);
        jQuery("#mod_filter_date").trigger("change");
    });
    jQuery("#modJSLiveMatchesNext").on("click", function () {
        curDate = jQuery("#mod_filter_date").val();
        dateObj = new Date(curDate.substr(0,4), curDate.substr(5,2)-1, curDate.substr(8,2));
        console.log(curDate.substr(0,4), curDate.substr(5,2), curDate.substr(8,2));
        dateObj.setDate(dateObj.getDate()+1);
        month = dateObj.getMonth()+1;
        if(month < 10){
            month = "0" + month;
        }
        day = dateObj.getDate();
        if(day < 10){
            day = "0" + day;
        }

        console.log(dateObj.getFullYear()  + "-" + month + "-" + day);
        var datestring = dateObj.getFullYear()  + "-" + month + "-" + day;
        jQuery("#mod_filter_date").val(datestring);
        jQuery("#mod_filter_date").trigger("change");
    });

    jQuery("#modJSLiveMatchesFiltersSelect").on("change", function () {
        jQuery("#mod_filter_date").trigger("change");
    });

    jQuery("body").on("click", ".fa-heart-o", function () {
        jQuery(this).removeClass("fa-heart-o").addClass("fa-heart");
        var itemID = jQuery(this).attr("data-id");
        if(itemID){
            modFavAddItem(itemID);
        }
    });
    jQuery("body").on("click", ".fa-heart", function () {
        jQuery(this).removeClass("fa-heart").addClass("fa-heart-o");
        var itemID = jQuery(this).attr("data-id");
        if(itemID){
            modFavRemoveItem(itemID);
        }
    });

    function modFavAddItem(itemID){
        let favMatches = JSON.parse(localStorage.getItem("favMatches"));

        if(!Array.isArray(favMatches)){
            favMatches = new Array();
        }
        var index = favMatches.indexOf(itemID);
        if (index == -1) { //if found
            favMatches.push(itemID);
        }
        console.log(favMatches);
        localStorage.setItem("favMatches", JSON.stringify(favMatches));
        jQuery("#modJsFavMatchCounter").html(parseInt(favMatches.length));

    }
    function modFavRemoveItem(itemID){
        let favMatches = JSON.parse(localStorage.getItem("favMatches"));

        if(!Array.isArray(favMatches)){
            favMatches = new Array();
        }
        var index = favMatches.indexOf(itemID); // get index if value found otherwise -1

        if (index > -1) { //if found
            favMatches.splice(index, 1);
        }
        console.log(favMatches);
        localStorage.setItem("favMatches", JSON.stringify(favMatches));
        jQuery("#modJsFavMatchCounter").html(parseInt(favMatches.length));

    }

    jQuery("#modJSLiveMatchesTabAll").on("click", function(){
        jQuery("#mod_filter_date").trigger("change");
    });
    jQuery("#modJSLiveMatchesTabFav").on("click", function(){
        let favMatches = JSON.parse(localStorage.getItem("favMatches"));

        if(!Array.isArray(favMatches)){
            favMatches = new Array();
        }
        modJsLiveMatchesTimerStop();
        var data = {
            'action': 'joomsport_liveshrtc_favreload',
            'matches': favMatches
        };

        jQuery.post(ajaxurl, data, function(response) {

                jQuery("#modJSLiveMatchesContainer").html(response);
                jQuery("#modJSLiveMatchesContainer").fadeIn(300);
                jQuery("#modJSLiveMatchesPrev").prop('disabled', false);
                jQuery("#modJSLiveMatchesNext").prop('disabled', false);
                reCheckFavourites();
                modJsLiveMatchesTimerStart();

        });
    });
    jQuery(".modJSLiveMatchesTabUL > li").on("click", function(){
        jQuery(".modJSLiveMatchesTabUL > li").removeClass("activeTab");
        jQuery(this).addClass('activeTab');
    });


    reCheckFavourites();
    modJsLiveMatchesTimerStart();
});