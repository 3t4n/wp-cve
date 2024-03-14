jQuery(document).ready(function ($) {
  // console.log("Last Feed...");
  $('#imagewaitfbl').hide();
  $deactivateSearch = $(".active");

  //$deactivateSearch.click(function (evt) {
  $deactivateSearch.on('click', function (evt) {

    billtempclass = evt.target.parentNode.className;

    //     console.log(billtempclass);
    // deactivate-wptools

    if (billtempclass != "deactivate") { return; }

    $deactivateLink = evt.target.href;

    // evt.preventDefault($deactivateLink);

    // console.log($deactivateLink)
    //https://wptoolsplugin.com/wp-admin/plugins.php?action=deacti…Fwptools.php&plugin_status=all&paged=1&s&_wpnonce=3260ac3877


    if($deactivateLink == '')
         { return; }
    
     if ($deactivateLink.includes('wptools'))
      { 
        product = 'wptools';
        prodclass = 'wp_tools';
        evt.preventDefault($deactivateLink);
      } 
      else
      {

          return;

      }






    if (prodclass != 'boat_dealer_plugin') { $('.boat_dealer_plugin-wrap-deactivate').slideUp(); }
    if (prodclass != 'anti_hacker') { $('.anti_hacker-wrap-deactivate').slideUp(); }
    if (prodclass != 'report_attacks') { $('.report_attacks-wrap-deactivate').slideUp(); }
    if (prodclass != 'stop_bad_bots') { $('.stop_bad_bots-wrap-deactivate').slideUp(); }
    if (prodclass != 'wp_tools') { $('.wp_tools-deactivate').slideUp(); }
    //evt.preventDefault(billstring);
    $billmodal = $('.' + prodclass + '-wrap-deactivate');
    $billmodal.show();
    $billmodal.prependTo($('#wpcontent')).slideDown();
    $('.' + prodclass + '-wrap-deactivate').prependTo($('#wpcontent')).slideDown();
    $('html, body').scrollTop(0);
    $("." + prodclass + "-deactivate").on('click', function () {
      $('#imagewaitfbl').show();
      if (!$(this).hasClass('disabled')) {
        $("." + prodclass + "-close-submit").addClass('disabled');
        $("." + prodclass + "-close-dialog").addClass('disabled');
        $("." + prodclass + "-deactivate").addClass('disabled');
        window.location.href = $deactivateLink;
      }
    });
    $("." + prodclass + "-close-submit").on('click', function () {
      var isAnonymousFeedback = $(".anonymous").prop("checked");
      var explanation = $("#" + prodclass + "-explanation").val();
      var username = $('#username').val();
      var version = $("#" + prodclass + "-version").val();
      var email = $('#email').val();
      var wpversion = $('#wpversion').val();
      var dom = document.domain;
      var limit = $('#limit').val();
      var wplimit = $('#wplimit').val();
      var usage = $('#usage').val();
      var errorlog = $('#billerrorlog').val();
      $('#imagewaitfbl').show();
      $("." + prodclass + "-close-submit").addClass('disabled');
      $("." + prodclass + "-close-dialog").addClass('disabled');
      $("." + prodclass + "-deactivate").addClass('disabled');
      if (isAnonymousFeedback) {
        email = 'anonymous';
        username = 'anonymous';
        dom = 'anonymous';
        version = 'anonymous';
        wpversion = 'anonymous';
      }
      $.ajax({
        url: 'https://billminozzi.com/httpapi/httpapi.php',
        withCredentials: true,
        timeout: 15000,
        method: 'POST',
        crossDomain: true,
        data: {
          email: email,
          name: username,
          obs: explanation,
          dom: dom,
          version: version,
          produto: product,
          limit: limit,
          wplimit: wplimit,
          usage: usage,
          errorlog: errorlog,
          wpversion: wpversion
        },
        complete: function () {
          //alert($deactivateLink);
          window.location.href = $deactivateLink;
        },
        error: function (xhr, status, errorThrown) {
          //Here the status code can be retrieved like;
          //alert(xhr.status);
      }
      }); // end ajax
    }); // end clicked button share ...
    $("." + prodclass + "-close-dialog").on('click', function (evt) {
      if (!$(this).hasClass('disabled')) {
        $('#imagewaitfbl').hide();
        $billmodal = $('.' + prodclass + '-wrap-deactivate');
        $billmodal.slideUp();
      }
    });
  }); // end clicked Deactivated ...
});  // end jQuery  