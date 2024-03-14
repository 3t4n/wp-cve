(function( $ ) {
  /*
  * Variables from php-settings file
  * Can be overwritten
  */

  var TIME_MARK_APPEND = !!optionsArray['time_mark'];
  var SLEEP_TIME = Number(optionsArray['interval']) * 1000;
  var SAVE_BY_TIMER = optionsArray['type_save'] === 1;
  var DEBOUNCE_INTERVAL = 2500;

  /**
   * Creating events
   */

  var STATE = 'state';
  var STATE_SYNC_FAILED = 'syncfailed';
  var STATE_DIRTY = 'dirty';
  var STATE_SYNC_REQUIRED = 'syncrequired';
  var STATE_SYNC_START = 'syncstart';
  var STATE_SYNC_DONE = 'syncdone';

  var CONTENT = 'content';
  var CONTENT_DIRTY = true;
  var CONTENT_CLEAN = false;

  /**
   * Runtime initialization
   */

  $(document).ready(function () {

    /**
     * Helpers initialization
     */

    var EVENT_DISPATCHER = new tinymce.util.EventDispatcher();

    var debounce = function (cb) { return tinymce.util.Delay.debounce(cb, DEBOUNCE_INTERVAL) };
    var interval = function (cb) { return setInterval(cb, SLEEP_TIME) };
    var post = function () { return prepareMultipartRequest(obtainFormData(TIME_MARK_APPEND)) };
    var fire = function(evt, state) { EVENT_DISPATCHER.fire(evt, {data: state}) }
    var on = function(evt, stateOrCb, mayBeCb) { EVENT_DISPATCHER.on(evt, arguments.length > 2
      ? function(v) { if (v.data === stateOrCb) mayBeCb(v.data) }
      : function(v) { stateOrCb(v.data) }) }
    

    var stateOffline = function() { fire(STATE, STATE_SYNC_FAILED) };
    var stateSyncRequired = function() { fire(STATE, STATE_SYNC_REQUIRED) };
    var stateSyncDone = function() { fire(STATE, STATE_SYNC_DONE) };
    var stateSyncStart = function() { fire(STATE, STATE_SYNC_START) };
    var contentDirty = function() { fire(CONTENT, CONTENT_DIRTY) };
    var contentClean = function() { fire(CONTENT, CONTENT_CLEAN) };

    /**
     * Initializing state and events flow
     */

    var state = {
      content: CONTENT_CLEAN,
      sync: STATE_SYNC_DONE
    };

    var sendRequest = function () { post().fail(stateOffline).done(stateSyncDone) };
    var updateContent = function(v) { state.content = v };
    var updateState = function(v) { state.sync = v };
    var requireSyncIfDirty = function () { (state.content === CONTENT_DIRTY) && stateSyncRequired() };
    var requireSyncIfAllowed = function() { (state.sync !== STATE_SYNC_START) && stateSyncRequired() };

    on(CONTENT, updateContent);
    on(CONTENT, CONTENT_DIRTY, requireSyncIfAllowed);
    on(STATE, updateState);
    on(STATE, visualizeState);
    on(STATE, STATE_SYNC_START, contentClean);
    on(STATE, STATE_SYNC_START, sendRequest);
    on(STATE, STATE_SYNC_DONE, requireSyncIfDirty);

    if (SAVE_BY_TIMER) {
      var startSyncIfRequired = function() { (state.sync === STATE_SYNC_REQUIRED) && stateSyncStart() }
      interval(startSyncIfRequired);
    } else {
      on(STATE, STATE_SYNC_REQUIRED, debounce(stateSyncStart));
    }



    $('textarea#content').on('input', contentDirty );

    $(document).on('tinymce-editor-init', function (_, editor) {
      var setEditorDirty = function() { editor.setDirty(true) };
      var contentDirtyIfEditorDirty = function() { editor.isDirty() && (editor.save(), contentDirty()) };
  
      editor.on('KeyPress', setEditorDirty);
      editor.on('Paste', setEditorDirty);
      editor.on('Dirty', contentDirtyIfEditorDirty);
    })

    stateSyncDone();
  })



  /**
   * 
   * @param {*} state 
   */
  function visualizeState(state) {
    var statusFieldId = 'wp-autosave-sync-status';
    var statusField = $('#' + statusFieldId);
    if (!statusField[0]) {
      statusField = $('<span id="' + statusFieldId + '"></span>');
      statusField.css({
        'border-radius': '50%',
        'width': '8px',
        'height': '8px',
        'display': 'inline-block',
        'transition': 'background-color .2s',
        'background-color': 'green'
      });
      $('#last-edit').remove();
      $('.autosave-info').append(statusField);
    }
    switch (state) {
      case STATE_SYNC_DONE:
        statusField.css("background-color", 'green');
        break;
      case STATE_SYNC_REQUIRED:
        statusField.css("background-color", 'orange');
        break;
      case STATE_SYNC_START:
        statusField.css("background-color", 'yellow');
        break;
      case STATE_SYNC_FAILED:
      default:
        statusField.css("background-color", 'red');
        break;
    }
  }
  
  
  
  
  /**
   * 
   * @param {boolean} addTimeMark 
   */
  function obtainFormData(addTimeMark) {
    var overrides = {
      '_wp_http_referer': '/wp-admin/post.php?post=' + document.getElementById('post_ID').value + '&action=edit',
    };

    var data = $('form#post')
      .serializeArray()
      .map(function (v) {
        if (v.name in overrides) {
          return {
            name: v.name,
            value: overrides[v.name]
          }
        }
        return v;
      })
      
    if (addTimeMark) {
      data.push({
        name: 'timeMark',
        value: Date.now().toString()
      });
    }

    return data;
  }

  
  
  /**
   * 
   * @param {any} data 
   * @param {function} onresponse 
   */
  function prepareMultipartRequest(data) {
    /** 
      * Generate multipart boundary based on 
      * https://stackoverflow.com/questions/2071257/generating-multipart-boundary/5686863.
      */  

    // Bugfix: Force call to TinyMCE lib to update textarea and save last chars
    try {
      tinyMCE.get('content').save();
    } catch(e) {}

    var boundary = generateRandomBoundary(56);
    var endline = '\r\n';
    var separator = '--';

    var body = ['']
      .concat(data
        .map(function (v) {
          return 'Content-Disposition: form-data; name="' + v.name + '"' + endline + endline + v.value + endline;
        }))
      .join(separator + boundary + endline) + separator + boundary + separator + endline;

    return $.ajax({
      url: '/wp-admin/post.php',
      method: 'POST',
      contentType: 'multipart/form-data; boundary=' + boundary,
      processData: false,
      cache: false,
      data: body
    });
  }
})( jQuery );



/**
 * 
 * @param {*} length 
 */
function generateRandomBoundary(length) {
  var chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  var charAt = function(i) {
    return chars[i % chars.length];
  }

  var boundary = new Uint8Array(length);
  if (window.crypto && window.crypto.getRandomValues && Uint8Array) {
    window.crypto.getRandomValues(boundary);
  } else {
    boundary.forEach((_, i) => {boundary[i] = Math.random()*chars.length});
  }

  return Array
    .from(boundary, (v) => charAt(v))
    .slice(0, length)
    .join('');
}
