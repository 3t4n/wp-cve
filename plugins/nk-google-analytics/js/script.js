jQuery(document).ready(function(){
    jQuery(".nk-tabs-menu a").click(function(event) {
        event.preventDefault();
        jQuery(this).parent().addClass("current");
        jQuery(this).parent().siblings().removeClass("current");
        var tab = jQuery(this).attr("href");
        jQuery(".nk-tab-content").not(tab).css("display", "none");
        jQuery(tab).fadeIn();
    });
    jQuery('input[name=nkweb_Use_Custom][value=true]').click(function() {
	jQuery('textarea[name=nkweb_Custom_Code]').removeClass('nk-input-disabled').attr('readonly', false);
    });
    jQuery('input[name=nkweb_Use_Custom][value!=true]').click(function() {
	jQuery('textarea[name=nkweb_Custom_Code]').addClass('nk-input-disabled').attr('readonly', true);
    });
    jQuery('input[name=nkweb_Domain_Auto][value=true]').click(function() {
	jQuery('input[name=nkweb_Domain]').addClass('nk-input-disabled').attr('readonly', true);
    });
    jQuery('input[name=nkweb_Domain_Auto][value!=true]').click(function() {
	jQuery('input[name=nkweb_Domain]').removeClass('nk-input-disabled').attr('readonly', false);
    });
    jQuery('option[name=nkweb_Use_Custom_js][value=true]').click(function () {
	jQuery('textarea[name=nkweb_Custom_js]').removeClass('nk-input-disabled').attr('readonly', false);
    });
    jQuery('option[name=nkweb_Use_Custom_js][value!=true]').click(function () {
	jQuery('textarea[name=nkweb_Custom_js]').addClass('nk-input-disabled').attr('readonly', true);
    });
    jQuery('option[name=nkweb_Use_Custom_Values][value=true]').click(function () {
	jQuery('textarea[name=nkweb_Custom_Values]').removeClass('nk-input-disabled').attr('readonly', false);
    });
    jQuery('option[name=nkweb_Use_Custom_Values][value!=true]').click(function () {
	jQuery('textarea[name=nkweb_Custom_Values]').addClass('nk-input-disabled').attr('readonly', true);
    });
});