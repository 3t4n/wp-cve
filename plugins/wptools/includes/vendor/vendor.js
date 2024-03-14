/*
 * @ Author: Bill Minozzi
 * @ Copyright: 2021 www.BillMinozzi.com
 * @ Modified time: 2021-29-11 09:17:42
 * */
jQuery(document).ready(function ($) {
    // console.log('vendor-wpt');

    $("#wptools-scan-ok").on('click', );
    $("#TB_title").hide();
    if (!$("#TB_window").is(':visible')) {
        $("#wptools-scan-ok").on('click', );
        // console.log('auto click');
    }

    $("*").on('keydown', function(event) {
        if (event.key == "Escape") {
            jQuery.ajax({
                method: 'post',
                url: ajaxurl,
                data: {
                    action: "wptools_bill_go_pro_hide"
                },
                success: function (data) {
                     console.log('OK-dismissed!!!');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('error' + errorThrown + ' ' + textStatus);
                }
            });
            self.parent.tb_remove();
            $('#TB_window').fadeOut();
            $("#TB_closeWindowButton").on('click', );
        }
    });

    $("*").on('click', function (ev) {


      //  ev.preventDefault();

        //  alert('2');
        // console.log('click');
        // console.log(ev.target.id);
         //$(this).attr("class");
        // console.log($(this).attr("class"));



        if (ev.target.id == "bill-vendor-button-ok-wpt") {
         //    console.log("Learn More");
            window.location.replace("http://wptoolsplugin.com/premium//");
        }


        if (ev.target.id == "bill-vendor-button-again-wpt") {
           //  console.log("watch again");
           // $("#bill-banner-wpt").get(0).play();
            $("#bill-banner-wpt").get(0).play().catch(function () {
                // console.log("Fail to Play.");
                self.parent.tb_remove();
                $('#TB_window').fadeOut();
                $("#TB_closeWindowButton").on('click', );
            });

        }

        if ( ev.target.id == "bill-vendor-button-dismiss-wpt" || $(this).attr("class") == "tb-close-icon"  ) {
            // event.preventDefault()

            $("#bill-banner-wpt").hide();
             
             $("#wptools-wait").show();
             $("#wptools-wait").addClass("is-active");

             console.log('clicked Dimiss !!!!!!');
             wptools_setCookie('wpt_dismiss', '1', '1');

             $("#bill-vendor-button-dismiss-wpt").hide();
             $("#bill-vendor-button-again-wpt").hide();
             $("#bill-vendor-button-ok-wpt").hide();

             $(".spinner").addClass("is-active");
             $(".spinner").show();

            jQuery.ajax({
                method: 'post',
                url: ajaxurl,
                data: {
                    action: "wptools_bill_go_pro_hide"
                },
                success: function (data) {
                   // console.log('OK-dismissed!!!');
                    // return data;
                    setTimeout(wpt_myFunction, 3000);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('error' + errorThrown + ' ' + textStatus);
                }
            });
            //console.log("fechar");
            function wpt_myFunction() {
                self.parent.tb_remove();
                $('#TB_window').fadeOut();
                $("#TB_closeWindowButton").on('click', );
            }

        }

    }); // click


    if ($('#bill-banner-wpt').length) {
        //  $("#bill-banner-wpt").get(0).play();
        $("#bill-banner-wpt").get(0).play().catch(function () {
            // console.log("Fail to Play.");
            self.parent.tb_remove();
            $('#TB_window').fadeOut();
            $("#TB_closeWindowButton").on('click', );
        });
    }


    $("#TB_window").height(260);
    $("#TB_window").width(550);
    $("#TB_window").addClass("bill_TB_window");

    function wptools_setCookie(cname, cvalue, exdays) {
        const d = new Date();
        d.setTime(d.getTime() + (exdays*24*60*60*1000));
        let expires = "expires="+ d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
      }
      
      function wptools_getCookie(cookieName) {
        let cookie = {};
        document.cookie.split(';').forEach(function(el) {
          let [key,value] = el.split('=');
          cookie[key.trim()] = value;
        })
        return cookie[cookieName];
      }





});