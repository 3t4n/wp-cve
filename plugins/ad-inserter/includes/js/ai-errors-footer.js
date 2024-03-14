//jQuery('body').prepend ("AI_HTML_CODE");
document.querySelector ('body').insertAdjacentHTML ('afterbegin', "AI_HTML_CODE");

ai_check_js_errors = function () {
//  jQuery('.ai-js-0').hide ();
  document.querySelectorAll ('.ai-js-0').forEach ((el, index) => {
    el.style.display = 'none';
  });

  if (ai_js_errors.length != 0) {
//    jQuery('.ai-js-2').show ();
//    jQuery('.ai-js-1').hide ();
    document.querySelectorAll ('.ai-js-2').forEach ((el, index) => {
      el.style.display = '';
    });
    document.querySelectorAll ('.ai-js-1').forEach ((el, index) => {
      el.style.display = 'none';
    });

  } else {
//      jQuery('.ai-js-1').show ();
//      jQuery('.ai-js-2').hide ();
      document.querySelectorAll ('.ai-js-1').forEach ((el, index) => {
        el.style.display = '';
      });
      document.querySelectorAll ('.ai-js-2').forEach ((el, index) => {
        el.style.display = 'none';
      });
    }
}

ai_check_js_errors ();
setTimeout (ai_check_js_errors, 500);
setTimeout (ai_check_js_errors, 3000);
