jQuery(function () {
  jQuery(".bang-indicator").each(function () {
    var brand = jQuery(this).attr('data-brand');
    if (brand == null || brand == "")
      brand = "bang";    
    jQuery(this).parents(".widget").addClass(brand+"-widget");
  });

  jQuery("#bang-leftbar").each(function () {
    // jQuery("ul#adminmenu a.wp-has-current-submenu:after, ul#adminmenu>li.current>a.current:after").css("border-right-color", "#f8e616");
    jQuery("ul#adminmenu a.wp-has-current-submenu").addClass("bang-leftbar-arrow");
    jQuery("#wpbody, #footer-left").css("padding-left", "30px");

    // var width = jQuery("#adminmenu").width();
    // jQuery("#bang-leftbar").css("left", width+"px");
  });

  jQuery("table.plugins tr").each(function () {
    var isbangplugin = false;
    jQuery(this).find("a").each(function () {
      var href = jQuery(this).attr('href');
      if (href == 'http://www.bang-on.net' || href == 'http://www.bang-on.net/') {
        isbangplugin = true;
      }
    });
    if (isbangplugin) {
      jQuery(this).addClass("bang-plugin");
    }
  });
});
