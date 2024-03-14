var elapsedTime = 0;

  /*var EXAM_TIME_LEFT = jQuery('#skwp_exam_time_left').val();
  var EXAM_REQUEST_ID = jQuery('#skwp_exam_req_id').val();*/

jQuery(function() {
  var EXAM_TIME_LEFT = jQuery('#skwp_exam_time_left').val();
  var EXAM_REQUEST_ID = jQuery('#skwp_exam_req_id').val();
  if (jQuery('#exam-time-left').length && EXAM_TIME_LEFT) 
  {
    updateExamTimer();  
  }
});

function updateExamTimer() 
{
  var EXAM_TIME_LEFT = jQuery('#skwp_exam_time_left').val();
  var exam_code1 = jQuery('#exam_code').val();
  var student_id1 = jQuery('#student_id').val();
  var exam_code2 = jQuery('#exam_code').val();
  var student_id2 = jQuery('#student_id').val();
  var cookieName2 = 'firsttime_'+exam_code2+'_'+student_id2;
  var cookieName1 = 'time_'+exam_code1+'_'+student_id1;
  var timeLeft = EXAM_TIME_LEFT - elapsedTime;
  if (!Cookies.get(cookieName1)) {
      var now = jQuery.now(); // First time on page
      Cookies.set(cookieName2, now, {
         expires: 1,
         path: '/'
      });
      Cookies.set(cookieName1, timeLeft, {
         expires: 1,
         path: '/'
      });
      var runTimer = Cookies.get(cookieName1);
   } else {
      var currentTime = jQuery.now();
      var usedTime = (currentTime - Cookies.get(cookieName2)) / 1000; // Calculate and convert into seconds
      var runTimer = Cookies.get(cookieName1) - usedTime;
   }


   // First time on page
  if (typeof(Storage) !== "undefined") {
    // Store
    if (!sessionStorage.getItem(cookieName1)) {
      var now = jQuery.now();
      sessionStorage.setItem(cookieName2, now);
      sessionStorage.setItem(cookieName1, timeLeft);
      var runTimer = sessionStorage.getItem(cookieName1);
    }
    else {
      var currentTime = jQuery.now();
      var usedTime = (currentTime - sessionStorage.getItem(cookieName2)) / 1000; // Calculate and convert into seconds
      var runTimer = sessionStorage.getItem(cookieName1) - usedTime;
   }
  } else {
    document.getElementById("result").innerHTML = "Sorry, your browser does not support Web Storage...";
  }

  elapsedTime += 1;
  var minutes = Math.floor(runTimer / 60);
  var seconds = runTimer % 60;
  var hours = Math.floor(minutes / 60);
  var minutes = minutes % 60;


  if (hours < 10) { hours = '0' + hours; }
  if (minutes < 10) { minutes = '0' + minutes; }
  if (seconds > 10) { seconds = Math.floor(seconds); }
  if (seconds < 10) { seconds = '0' + Math.floor(seconds); }
  if (runTimer <= 0) 
  {

    var exam_code = jQuery('#exam_code').val()
    var student_id = jQuery('#student_id').val()

    var cookieName = exam_code+'_'+student_id
    var savedAnswer = Cookies.get(cookieName);

    //updateAnswered()

    if(savedAnswer != '' && savedAnswer != undefined){
      var answerArray = JSON.parse(savedAnswer)
      jQuery.each(answerArray,function(name,id){

        // mark the checkbox
        var theAnswer = jQuery('input[name="'+ name +'"]#'+id)
        jQuery(theAnswer).prop('checked',true)

        var theAnswer2 = jQuery('input[name="'+ name +'"]#'+id)
        jQuery(theAnswer2).prop('checked',true)

        // update pagination
        var answered = jQuery(theAnswer).parents('.soal-ujian-wrapper').attr('data-index')
        jQuery(document).find('[data-simple-pagination-page-number="'+answered+'"]').addClass('answered')

        if(theAnswer2.val() == "ragu2wae") {
          var answered2 = jQuery(theAnswer2).parents('.soal-ujian-wrapper').attr('data-index')
          jQuery(document).find('[data-simple-pagination-page-number="'+answered2+'"]').addClass('ragu')
        }
        else {
          var answered2 = jQuery(theAnswer2).parents('.soal-ujian-wrapper').attr('data-index')
          jQuery(document).find('[data-simple-pagination-page-number="'+answered2+'"]').removeClass('ragu')
        }
      })
    }

    var minutes = Math.floor(timeLeft / 60);
    var seconds = timeLeft % 60;
    var hours = Math.floor(minutes / 60);
    var minutes = timeLeft % 60;
    if (hours < 10) { hours = '0' + hours; }
    if (minutes < 10) { minutes = '0' + minutes; }
    if (seconds > 10) { seconds = Math.floor(seconds); }
    if (seconds < 10) { seconds = '0' + Math.floor(seconds); }

    jQuery('#exam-time-left').val(hours + ':' + minutes + ':' + seconds);

    alert('Waktu telah berakhir, Anda akan diarahkan ke ujian online');
    jQuery('#subbutton').trigger('click');
  } 
  else 
  {
    jQuery('#exam-time-left').val(hours + ':' + minutes + ':' + seconds);
    setTimeout('updateExamTimer()', 1000);
  }
}


// store answer to cookie
jQuery(function(jQuery){

  var exam_code = jQuery('#exam_code').val()
  var student_id = jQuery('#student_id').val()
  var cookieName = exam_code+'_'+student_id
  var savedAnswer = Cookies.get(cookieName);
  var jQueryans = savedAnswer != '' && savedAnswer != undefined ? JSON.parse(savedAnswer) : {}

  function pushToCookie(jQuerycookie_name, jQueryquestions, jQueryanswer){
    jQueryans[jQueryquestions] = jQueryanswer
    Cookies.set(jQuerycookie_name, JSON.stringify(jQueryans), { expires: 1 })
  }


  // on answer filled
  jQuery('.jawaban-wrap input').change(function(){

      var jQuerythis = jQuery(this)
      var theName = jQuerythis.attr('name')
      var theValue = jQuerythis.attr('id')

      pushToCookie(cookieName, theName, theValue)
      updateAnswered()

  })

  function updateAnswered(){
    var savedAnswer = Cookies.get(cookieName);

    if(savedAnswer != '' && savedAnswer != undefined){
      var answerArray = JSON.parse(savedAnswer)
      jQuery.each(answerArray,function(name,id){

        // mark the checkbox
        var theAnswer = jQuery('input[name="'+ name +'"]#'+id)
        jQuery(theAnswer).prop('checked',true)

        var theAnswer2 = jQuery('input[name="'+ name +'"]#'+id)
        jQuery(theAnswer2).prop('checked',true)

        // update pagination
        var answered = jQuery(theAnswer).parents('.soal-ujian-wrapper').attr('data-index')
        jQuery(document).find('[data-simple-pagination-page-number="'+answered+'"]').addClass('answered')

        if(theAnswer2.val() == "ragu2wae") {
          var answered2 = jQuery(theAnswer2).parents('.soal-ujian-wrapper').attr('data-index')
          jQuery(document).find('[data-simple-pagination-page-number="'+answered2+'"]').addClass('ragu')
        }
        else {
          var answered2 = jQuery(theAnswer2).parents('.soal-ujian-wrapper').attr('data-index')
          jQuery(document).find('[data-simple-pagination-page-number="'+answered2+'"]').removeClass('ragu')
        }
      })
    }
  }

  // on ready
  jQuery(window).load(function(){
    updateAnswered()
    jQuery(document).bind('pagination_refresh',function(){
      updateAnswered()
    })
  })

  

})