jQuery(document).ready(function($){


/*************************************************GET PARAM FROM URL***************************************************/
  $.urlParam = function (name) {
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    if (results == null) {
      return null;
    }
    else {
      return results[1] || 0;
    }

  };
/*************************************************PARSE PARAM FROM URL**************************************************/
    function getParameterByName(name, url) {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
      var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)");
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }

/*************************************************CHANGE SURA**********************************************************/
      $('#change_sura').change(function(){
          $.each($('audio'), function () {
          this.pause();
          this.currentTime = 0;
          });
          var clickBtnValue = $(this).val();
          var paramsLang = getParameterByName('lang'); 
          history.pushState({ path: this.path }, '', ''+clickBtnValue+'&lang='+paramsLang+'');
          var sura = clickBtnValue.match( /\d+/ );

          jQuery.post(
              ajaxurl,
              {
                    'action': 'qtm_changesura',
                    'sura': clickBtnValue,
                    'lang': paramsLang
              },
            function(response){

              $('#result').html(response);
              
           
            }
          );

      });
/*************************************************CHANGE LANGUAGE*****************************************************/
      $('#select_language').change(function(){

        var paramsSura = getParameterByName('sourate'); 
        var clickBtnValue = $(this).val();
        var sura = paramsSura.match( /\d+/ );
        history.pushState({ path: this.path }, '', '?sourate='+paramsSura+'&lang='+clickBtnValue+'');

        jQuery.post(
          ajaxurl,
          {
            'action': 'qtm_changelanguage',
            'lang': clickBtnValue,
            'paramsSura': paramsSura
          },
          function(response){
             $('#result').html(response);                
          }
        );

      });
/*************************************************BUTTON PREVIOUS SURA**********************************************************************/
  $("body").on("click","#PrevSourate", function(){

  var sourate  = $.urlParam('sourate');

  var lang  = $.urlParam('lang');
 
  var Nbrsourate = sourate.match( /\d+/ );

  var PrevSourate = Nbrsourate - 1;

  var UrlPrevSourate = "?sourate="+PrevSourate+"";

  if(Nbrsourate < 114 && Nbrsourate >= 2){

    history.pushState({ path: this.path }, '', ''+UrlPrevSourate+'&lang='+lang+'');

          jQuery.post(
              ajaxurl,
              {
                    'action': 'qtm_changeprevsura',
                    'sura': PrevSourate,
                    'lang': lang
              },
            function(response){
              $('#result').html(response);       
            }
          );

  }
        if(Nbrsourate == 1){$("#PrevSourate").hide();}  

  });

/*************************************************BUTTON NEXT SURA**********************************************************************/


  $("body").on("click","#NextSourate", function(){

  var sourate  = $.urlParam('sourate');

  var lang  = $.urlParam('lang');
 
  var Nbrsourate = sourate.match( /\d+/ );

  var NextSourate = parseInt(Nbrsourate) + 1;

  var UrlNextSourate = "?sourate="+NextSourate+"";

  if(Nbrsourate < 114 && Nbrsourate >= 1){

    history.pushState({ path: this.path }, '', ''+UrlNextSourate+'&lang='+lang+'');


          jQuery.post(
              ajaxurl,
              {
                    'action': 'qtm_changenextsura',
                    'sura': NextSourate,
                    'lang': lang
              },
            function(response){
              $('#result').html(response);     
            }
          );

  }
        if(Nbrsourate == 114){$("#NextSourate").hide();}  

  });


});

jQuery(function($) {

    $("body").on("click","#click_params_kb", function(){  

        $.each($('audio'), function () {
            this.pause();
            this.currentTime = 0;
        });

        $('.params_kb').css('height', '50px');
        $('.params1_kb').hide();
        $('.params_download_kb').hide();
        $('#playeraya2').html(""); 
        $('#playeraya').html(""); 
        $('kb-select_text').prop('selectedIndex',0);
        $('#select_name_recitator').prop('selectedIndex',0);
        $('#kb-select_debut').prop('selectedIndex',0);
        $('#kb-select_fin').prop('selectedIndex',0);
        $('#play_select_quran').hide();
        $('#suraplayer').empty();

        $('.params_kb').toggle( "fast", function() {
          $('#kb-select_text').prop( "disabled", true );  
          $('#kb-select_debut').prop( "disabled", true );
          $('#kb-select_fin').prop( "disabled", true );
        });

        $('.quran').removeAttr("style");  
        $('.trans').removeAttr("style");

    }); 
    
    $("body").on("click","#click_playsura_kb", function(){

      $.each($('audio'), function () {
         this.pause();
        this.currentTime = 0;
      }); 
      $('.aya').css('display', 'block');
      $('#playeraya2').html(""); 
      $('.params1_kb').toggle('fast');

      $('.params1_kb').css('height', '32px');

       $('#playeraya').html(""); 

      $('.params_kb').hide(); 
      $('.params_download_kb').hide();
      $('.quran').removeAttr("style");  
      $('.trans').removeAttr("style");

    });

   $("body").on("click","#click_download_kb", function(){

      $.each($('audio'), function () {
          this.pause();
          this.currentTime = 0;
      }); 
      $('.params_download_kb').toggle('fast');
      $('#name_recitator').show();
      $('.params1_kb').hide();
      $('.params_kb').hide();
  });  }); 
