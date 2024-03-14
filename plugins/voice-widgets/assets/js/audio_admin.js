document.addEventListener('DOMContentLoaded', function() {

  URL = window.URL || window.webkitURL; // webkitURL is deprecated but nevertheless
  let gumStream; // Stream from getUserMedia()
  let rec; // Recorder.js object
  let input; // MediaStreamAudioSourceNode we'll be recording
  let AudioContext = window.AudioContext || window.webkitAudioContext;
  let audioContext;
  let audioBlob;
  let drawVisual;
  let draw;
  let timerInterval;
  let countdownInterval;
  let isTimerPaused = false;
  let isCountdownPaused = false;
  let form;
  let sampleRate;
  
  let recordBtn = document.getElementById( 'qc_audio_record' );
  const cForm = document.querySelectorAll( '#qc_audio_recorder' ); // .main_record_div

  /** Start Recording Button */
  if ( recordBtn !== null ) {
    recordBtn.addEventListener( 'click', () => { startRecordingButtonClick( cForm ); } );
  }

  jQuery(document).on('click', '#botbtnStop', function(e){
    e.preventDefault();
    /** Stop Recording. */
    if ( rec.recording ) { rec.stop(); }
    //console.log( 'Recording stopped.' );

    /** Stop timer. */
    clearInterval( timerInterval );

    /** Stop countdown. */
    clearInterval( countdownInterval );

    /** Stop Animation. */
    window.cancelAnimationFrame( drawVisual );
    jQuery('.voice_countdown').hide();

    /** Stop microphone access. */
    gumStream.getAudioTracks()[0].stop();

    /** Create the wav blob and pass it to createPayer. */
    rec.exportWAV( ( blob ) => { createPayer( blob, cForm ) } );
  })
        
  function addvoicecontainer(){
    let html = '<div class="wpbot_voice_addon_container qc_audio_record_div" role="alert">'+
                '<div class="bot_voice_wrapper">'+
                  '<div class="bot_recoding_animation">'+
                    '<h2>Speak now</h2>'+
                    '<div class="voice_countdown"></div>'+
                    '<canvas width="384" height="60">'+
                      '<div>Canvas not available.</div>'+
                    '</canvas>'+
                    '<p style="display:none">Please wait while proccsing your request.</p>'+
                    '<button class="button button-primary button-large" id="botbtnStop">Stop & Save</button>'+
                  '</div>'+
                  '<div class="wpbot_tts_wrapper"></div>'+
                '</div>'+
              '</div>';
    jQuery('#qc_audio_recorder').append(html);

  }
  
  function startRecordingButtonClick( cForm ) {
      jQuery( '#qc_audio_main' ).hide();
      jQuery( '.wpbot_voice_addon_container' ).remove();

      addvoicecontainer();
      jQuery( '#qc_audio_recorder' ).show();
      /**
       * We're using the standard promise based getUserMedia()
       * @see: https://developer.mozilla.org/en-US/docs/Web/API/MediaDevices/getUserMedia
       * @see: https://addpipe.com/blog/audio-constraints-getusermedia/
       **/

      var constraints_audio = {
            audio: {
                sampleRate: 48000,
                sampleSize: 1600,
                channelCount: 2,
                volume: 1.0, 
                echoCancellation: false,
                autoGainControl: false,
                noiseSuppression: false,
                latency: 0
            },
            video: false
        }

      //navigator.mediaDevices.getUserMedia( { audio: true, video: false } ).then( function( stream ) {
      navigator.mediaDevices.getUserMedia( constraints_audio ).then( function( stream ) {

        //  console.log( 'getUserMedia() success. Stream created. Initializing Recorder.' );

          
          
          jQuery('.bot_voice_wrapper').removeAttr('style');
          jQuery('.bot_voice_wrapper').css("display","flex");
          jQuery('.voice_countdown').html('');
          jQuery('.voice_countdown').show();

          /**
           * Create an audio context after getUserMedia is called.
           * SampleRate might change after getUserMedia is called, like it does on macOS when recording through AirPods
           * the sampleRate defaults to the one set in your OS for your playback device.
           **/
          audioContext = new AudioContext();

          sampleRate = audioContext.sampleRate;
          /** Log the format. */
        //  console.log( 'Format: 1 channel pcm @ ' + sampleRate/1000 + 'kHz' );


          /** Assign to gumStream for later use.  */
          gumStream = stream;

          /** Use the stream. */
          input = audioContext.createMediaStreamSource( stream );

          /**
           * Create the Recorder object and configure to record mono sound (1 channel)
           * Recording 2 channels  will double the file size.
           **/
          rec = new Recorder( input,{ numChannels: 1 } );

          /** Start the recording. */
          rec.record();
        //  console.log( 'Recording started.' );
          createCountdown();
          /** Create Animation. */
          createAnimation( cForm );
          /** Create Timer. */
        createTimer( cForm );

      } ).catch( function( err ) {
          jQuery('.wpbot_voice_addon_container').remove();
          /** Show Error if getUserMedia() fails. */
          console.log( 'Error getUserMedia() fails. See details below.', 'warn', true );
          console.log( err, 'error', true );
          alert( 'Error getUserMedia() fails' );


      } );

  }
  
  function createCountdown(){
    
    const countdownElement = document.querySelector( '.voice_countdown' );
    /** Reset previously countdowns. */
    clearInterval( countdownInterval );
    let maxDuration = voice_obj.capture_duration;
    let countdown = voice_obj.capture_duration;
    isCountdownPaused = false;
    let resetMinutes = Math.floor( maxDuration / 60 );
    let resetSeconds = maxDuration - resetMinutes * 60;
    countdownElement.innerHTML = resetMinutes + ':' + resetSeconds;

    /** Start new countdown. */
    countdownInterval = setInterval( function () {

        if ( isCountdownPaused ) { return; } // Pause.

        countdown--;

        /** If timer lower than 0 Stop recording. */
        if ( maxDuration !== 0 && countdown < 0 ) {
          jQuery('#botbtnStop').trigger('click');
        }

        let minutes = Math.floor( countdown / 60 );
        let seconds = countdown - minutes * 60;
        countdownElement.innerHTML = minutes + ':' + seconds;

    }, 1000 );
  }
  
  function createTimer( cForm ) {
    let timer = 0;
    let maxDuration = voice_obj.capture_duration;
    /** Reset previously timers. */
    clearInterval( timerInterval );
    isTimerPaused = false;
    /** Start new timer. */
    timerInterval = setInterval( function () {
        if ( isTimerPaused ) { return; } // Pause.
        timer++;
        /** If timer bigger than max-duration Stop recording. */
        if ( maxDuration !== 0 && timer > maxDuration ) {
            jQuery('#botbtnStop').trigger('click');
        }
    }, 1000 );
  }
        
    /**
   * Create Animation.
   **/
  function createAnimation( cForm ) {

    jQuery('.bot_recoding_animation canvas').show();
    jQuery('.bot_recoding_animation h2').show();
    jQuery('#botbtnStop').show();
    jQuery('.bot_recoding_animation p').hide();
      /**
       * Create Analyser to extract data from audio source.
       * The AnalyserNode interface represents a node able to provide real-time frequency and time-domain analysis information.
       **/
      let analyser = audioContext.createAnalyser();

      /** Connect analyser to audio source. */
      input.connect( analyser );

      /** Array to receive the data from audio source. */
      analyser.fftSize = 2048;
      let bufferLength = analyser.frequencyBinCount;
      let dataArray = new Uint8Array( bufferLength );

      /** Canvas for animation. */
      let animation = document.querySelector( '.bot_recoding_animation canvas' );

      let animationCtx = animation.getContext( "2d" );

      /** Clear the canvas. */
      animationCtx.clearRect( 0, 0, animation.width, animation.height );

      draw = function() {

          /** Using requestAnimationFrame() to keep looping the drawing function once it has been started. */
          drawVisual = requestAnimationFrame( draw );

          /** Grab the time domain data and copy it into our array. */
          analyser.getByteTimeDomainData( dataArray );

          /** Fill the canvas with a solid colour to start. */
          animationCtx.clearRect( 0, 0, animation.width, animation.height ); // Clear the canvas.
          animationCtx.fillStyle = 'rgba( 255, 255, 255, 0.01 )'; // Almost transparent
          animationCtx.fillRect( 0, 0, animation.width, animation.height );

          /** Set a line width and stroke colour for the wave we will draw, then begin drawing a path. */
          animationCtx.lineWidth = 2;

          let startColor = '#0274e6';
          let endColor = '#0274e6';

          const gradient = animationCtx.createLinearGradient(0, 0, 384, 0);
          gradient.addColorStop( 0, startColor );
          gradient.addColorStop( .25 , endColor );
          gradient.addColorStop( .75 , endColor );
          gradient.addColorStop( 1, startColor );
          animationCtx.strokeStyle = gradient;

          animationCtx.beginPath();

          /**
           * Determine the width of each segment of the line to be drawn
           * by dividing the canvas width by the array length (equal to the FrequencyBinCount, as defined earlier on),
           * then define an x variable to define the position to move to for drawing each segment of the line.
           **/
          let sliceWidth = animation.width * 1.0 / bufferLength;
          let x = 0;

          /**
           * Run through a loop, defining the position of a small segment of the wave
           * for each point in the buffer at a certain height based on the data point value form the array,
           * then moving the line across to the place where the next wave segment should be drawn.
           **/
          for ( let i = 0; i < bufferLength; i++ ) {

              let v = dataArray[i] / 128.0;
              let y = v * animation.height/2;

              if ( i === 0 ) {
                  animationCtx.moveTo( x, y );
              } else {
                  animationCtx.lineTo( x, y );
              }

              x += sliceWidth;
          }

          /**
           * Finish the line in the middle of the right hand side of the canvas,
           * then draw the stroke we've defined.
           **/
          animationCtx.lineTo( animation.width, animation.height/2 );
          animationCtx.stroke();
      };

      /** Call the draw() function to start off the whole process. */
      draw();

  }
        
  /**
   * Get recorded audio create player.
   **/
  function createPayer( blob, cForm ) {
    jQuery('.bot_recoding_animation h2').hide();
    jQuery('#botbtnStop').hide();
    jQuery('.bot_recoding_animation canvas').hide();
    jQuery('.bot_recoding_animation p').show();
    jQuery( '#qc_audio_display' ).show();
    jQuery( '#qc_audio_recorder' ).hide();
    let url = URL.createObjectURL( blob );
    audioBlob = blob;
    let audioEl = document.getElementById( 'qc-audio' );
    audioEl.src = url;

    jQuery("#publishing-action .spinner").css("visibility", "visible");
    jQuery("#publish").prop('disabled', true);
    var form_data = new FormData();                  // Creating object of FormData class

    form_data.append("audio_data", blob);
    form_data.append("action", "qcld_audio_save");                 // Adding extra parameters to form_data
    jQuery.ajax({
        url: voice_obj.ajax_url,
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,                         // Setting the data attribute of ajax with file_data
        type: 'post',
        
        success: function(response) {
            var obj = jQuery.parseJSON(response);
            jQuery("#publishing-action .spinner").css("visibility", "hidden");
            jQuery("#publish").prop('disabled', false);
            if(obj.status == 'success'){
              jQuery('#qc_audio_url').val( obj.url );
            }else{
              alert( obj.message );

            }
        },
        error: function() {
            alert("An error occured, please try again.");
        },
        timeout: 30000 // sets timeout to 30 seconds
                
      })

  }

  // Audio file uploader API.
  jQuery('#qc_audio_upload').on('click',function(e){
    //e.preventDefault();
    //alert( 'Need Voice Widgets Pro version to unlock this feature.' )
    e.preventDefault();
      var title = 'Audio Uploader';
      var image = wp.media({ 
          title: title,
          // mutiple: true if you want to upload multiple files at once
          multiple: false
      }).open()
      .on('select', function(e){
          // This will return the selected image from the Media Uploader, the result is an object
          var uploaded_image = image.state().get('selection').first();
          var audio_url = uploaded_image.toJSON().url;
          if ( uploaded_image.attributes.type == 'audio' ) {
            jQuery( '#qc_audio_display' ).show();
            jQuery( '#qc_audio_main' ).hide();
            let audioEl = document.getElementById( 'qc-audio' );
            audioEl.src = audio_url;
            jQuery('#qc_audio_url').val( audio_url );
          } else {
            alert( 'Please add only audio file.' );
          }

      });



  })

  jQuery( '.qc_audio_remove_button' ).on( 'click', function(e) {
    e.preventDefault();
    jQuery( '#qc_audio_display' ).hide();
    jQuery( '#qc_audio_main' ).show();
    jQuery('#qc_audio_url').val( "" );
  } )

  // Color picker
  jQuery('.qc_audio_color').wpColorPicker();

  // select option config
  const template_selected = jQuery('#qc_audio_template_selector');
  function qc_alter_settings_field() {

    if ( template_selected.val() == 'call_to_action' ) {
      jQuery('.qc_audio_call_to_action').show();
    } else {
      jQuery('.qc_audio_call_to_action').hide();
    }
    if ( jQuery( '.qc_audio_template_preview' ).length > 0 ) {
      jQuery( '.qc_audio_template_preview img' ).attr( 'src', voice_obj.templates[template_selected.val()].image )
    }

    if ( template_selected.length > 0 && 'default' != template_selected.val() ) {
      alert( 'You need to have Voice Widgets Pro to unlock this template. You can only use Default template for now.' );
    }
    
  }
  template_selected.on( 'change', function() {
    qc_alter_settings_field()
  });

  qc_alter_settings_field();

  jQuery('.qc_audio_shortcode_elem').on('click', function(){
    jQuery(this).select();		  
    document.execCommand("copy");
  })

} );

// functions for tooltip and copy shortcode.
function qc_myFunction() {
  var copyText = document.getElementById("qc_audio_shortcode");
  copyText.select();
  copyText.setSelectionRange(0, 99999);
  navigator.clipboard.writeText(copyText.value);
  
  var tooltip = document.getElementById("qc_myTooltip");
  tooltip.innerHTML = "Copied: " + copyText.value;
}
function qc_outFunc() {
  var tooltip = document.getElementById("qc_myTooltip");
  tooltip.innerHTML = "Copy to clipboard";
}


//jquery-ui-tabs
(function($) {
  'use strict';
    // After the document is ready
    $(function(){
        $('#tabs').tabs();

        $(document).on('click', '.qc_voice_click_handle', function(e){

          $('.qc_voice_click_handle').removeClass('nav-tab-active');
          $(this).addClass('nav-tab-active');

        });

    });

})(jQuery);