(function ($) {

  wp_intel.behaviors.intel_admin_form_list = {
    attach: function (context, settings) {
console.log(context);
console.log(settings);
    // if standard admin form
      var colIndex = 0;
      var colCount = 0;
      var table_selector = '.admin--config--intel--settings--form table';
      if ($(table_selector).length > 0) {
        $( table_selector + ' thead tr:nth-child(1) th').each(function () {
          if ($(this).text() == "Title") {
            colIndex = colCount + 1;
          }
          if ($(this).attr('colspan')) {
          colCount += + $(this).attr('colspan');
        } else {
          colCount++;
        }
        });
        //alert("ci=" + colIndex + ", cc=" + colCount);
      }
console.log(colIndex);
console.log(colCount);
      if (colIndex == 0) {
        return;
      }
      $(table_selector + ' thead th:eq(' + (colIndex-1) + ')').after('<th>Views</th><th>Subs</th><th>Conv%</th>');
      $(table_selector + ' tr').each ( function( index ) {
        var href = $(this).find("td:eq(0) a").attr("href");
        if (typeof href != 'undefined') {
        var imgsrc = ('https:' == document.location.protocol) ? 'https://' : 'http://';
          imgsrc += wp_intel.settings.intel.config.cmsHostpath + wp_intel.settings.intel.config.modulePath + "/images/ajax_loader_row.gif";
          $(this).find("td:eq(" + (colIndex-1) + ")").after('<td data-path="' + href + '" colspan="3" style="text-align:center;"><img src="' + imgsrc + '"></td>');
        }
      });
    //var query = (window.location.href.indexOf('?') > 0) ? window.location.href.substring(window.location.href.indexOf('?'), window.location.href.length) : '';
    //query = query.replace("render=overlay", "");  // remove overlay shenanigans
      var url = ('https:' == document.location.protocol) ? 'https://' : 'http://';
	  url += wp_intel.settings.intel.config.cmsHostpath + "intel/admin_content_cta_alter_js"; //?callback=?";
      $.getJSON(url).success(function(data) {
        $(".cta-admin-list tr").each ( function( index ) {
          var href = $(this).find("td:eq(0) a").attr("href");
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