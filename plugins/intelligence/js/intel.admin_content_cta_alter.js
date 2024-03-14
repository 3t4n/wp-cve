(function ($) {

  Drupal.behaviors.intel_admin_content_cta_alter = {
    attach: function (context, settings) {
    // if standard admin form
    if (jQuery(".cta-admin-list").length > 0) {
      var colIndex = 0;
      var colCount = 0;
      jQuery('.cta-admin-list thead tr:nth-child(1) th').each(function () {

        if ($(this).text() == "Title") {
          colIndex = colCount+1;
        }
        if ($(this).attr('colspan')) {
        colCount += +$(this).attr('colspan');
      } else {
        colCount++;
      }
      });
      //alert("ci=" + colIndex + ", cc=" + colCount);
    }
    if (colIndex == 0) {
      return;
    }
    $(".cta-admin-list thead th:eq(" + (colIndex-1) + ")").after('<th>Views</th><th>Clicks</th><th>Click%</th><th>Convs</th><th>Conv%</th>');
    $(".cta-admin-list tr").each ( function( index ) {
      var href = $(this).find("td:eq(0) a").attr("href");
      if (typeof href != 'undefined') {
	    var imgsrc = ('https:' == document.location.protocol) ? 'https://' : 'http://';
        imgsrc += Drupal.settings.intel.config.cmsHostpath + Drupal.settings.intel.config.modulePath + "/images/ajax_loader_row.gif";
        $(this).find("td:eq(" + (colIndex-1) + ")").after('<td data-path="' + href + '" colspan="5" style="text-align:center;"><img src="' + imgsrc + '"></td>');        
      }
    }
        
    );
    //var query = (window.location.href.indexOf('?') > 0) ? window.location.href.substring(window.location.href.indexOf('?'), window.location.href.length) : '';
    //query = query.replace("render=overlay", "");  // remove overlay shenanigans
      var url = ('https:' == document.location.protocol) ? 'https://' : 'http://';
	  url += Drupal.settings.intel.config.cmsHostpath + "intel/admin_content_cta_alter_js"; //?callback=?";
      jQuery.getJSON(url).success(function(data) {
        $(".cta-admin-list tr").each ( function( index ) {
            console.log(this);
          var href = $(this).find("td:eq(0) a").attr("href");
            console.log(href);
          if (typeof href != 'undefined') {
            if (typeof data.rows[href] != 'undefined') {
              $(this).find("td:eq(" + colIndex + ")").replaceWith(data.rows[href]);
            }
            else {
              $(this).find("td:eq(" + colIndex + ")").replaceWith('<td colspan="5" style="text-align:center;">NA</td>');
            }
          }
        });
      });  
    }
  };

})(jQuery);

// jQuery('#node-admin-content thead tr:nth-child(1) th').css("border","5px solid red");