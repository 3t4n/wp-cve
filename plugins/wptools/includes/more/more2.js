jQuery(document).ready(function ($) {

   //console.log('OK-wptools');

  $('#billimagewaitfbl').hide();
  
  $("*").on('click', function (evt) {
    var id = $(this).attr('id');
    var btnclass = $(this).hasClass("wt-bill-install-now")
    if (btnclass != true) {
      return;
    }
    // console.log({id});

    if (id != "database-backup" &&  id != "bigdump-restore" &&  id != "easy-update-urls" &&  id != "s3cloud" &&  id != "toolsfors3" && id != "antihacker" && id != "toolstruthsocial" && id != "stopbadbots" && id != "wptools" && id != "recaptcha-for-all" && id != "wp-memory") {
        Return;
    }
    alert_msg = 'Plugin Installed Successively!\nGo to ';

      switch (id) {
      case "database-backup":
        alert_msg = alert_msg + "Dashboard => Menu => Tools => Database-Backup";
        break;
      case "bigdump-restore":
        alert_msg = alert_msg + "Dashboard => Menu => Tools => Bigdump Restore";
        break;
      case "easy-update-urls":
        alert_msg = alert_msg + "Dashboard => Menu => Tools => Easy Update Urls";
        break;
      case "s3cloud":
          alert_msg = alert_msg + "Dashboard => Menu => Tools => S3 Cloud";
          break;  
      case "toolsfors3":
          alert_msg = alert_msg + "Dashboard => Menu => Tools => Tools For S3";
          break;  
      case "wp-memory":
        alert_msg = alert_msg + "Dashboard => Menu => Tools => WP Memory";
        break;
      case "antihacker":
        alert_msg = alert_msg + "Dashboard => Anti Hacker";
        break;
      case "stopbadbots":
        alert_msg = alert_msg + "Dashboard => Stop Bad Bots";
        break;
      case "wptools":
        alert_msg = alert_msg + "Dashboard => WP Tools";
        break;
      case "recaptcha-for-all":
        alert_msg = alert_msg + "Dashboard => Tools => reCAPTCHA For All";
        break
      default:
        alert_msg = alert_msg + "Dashboard => Menu";
        break;
    }
    

    
    $('#billimagewaitfbl').show();
    evt.preventDefault();
    //console.log(id);  
    $billmodal = $('#bill-wrap-install');
    //console.log($billmodal);
    $billmodal.prependTo($('#wpcontent')).slideDown();
    $('html, body').scrollTop(0);
    $("#billpluginslug").html(id);
    var nonce = $("#wptools_nonce").text();
    

    
    jQuery.ajax({
      url: ajaxurl,
      type: 'post',
      data: {
        'action': 'wptools_install_plugin',
        'slug': id,
        'nonce': nonce
      },
      success: function (data) {
        $('#billimagewaitfbl').hide();
        if (data == 'OK') {
          //console.log('data: '+data);
          $('#rcwimagewaitfbl').hide();
          alert(alert_msg);
        }
        else {
          $('#billimagewaitfbl').hide();
          console.log(data);
          alert('Automatic Plugin Install Fail! Please, Install Manually');
        }
        $billmodal.slideUp();
        window.location.reload(true);
      },
      error: function (errorThrown) {
        console.log(errorThrown);
        console.log(data);
        alert('Automatic Plugin Install Fail! Please, Install Manually');
        $billmodal.slideUp();
        window.location.reload(true);
      }
    }); // ajax

    

  }); //click

});  // end jQuery  