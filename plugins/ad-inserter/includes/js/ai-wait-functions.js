function ai_wait_for_functions () {
  var timeout = 5 * 1000;
  var start = Date.now ();

  return new Promise (wait_for_functions);

  function wait_for_functions (resolve, reject) {
    if (typeof ai_functions !== 'undefined')
      resolve (ai_functions);
    else if (timeout && (Date.now () - start) >= timeout)
      reject (new Error ("AI FUNCTIONS NOT LOADED"));
    else
      setTimeout (wait_for_functions.bind (this, resolve, reject), 50);
  }
}
