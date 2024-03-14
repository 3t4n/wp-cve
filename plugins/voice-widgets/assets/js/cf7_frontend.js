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
    recordBtn.addEventListener( 'click', (event) => { startRecordingButtonClick(event, cForm ); } );
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
                    '<h2>'+voice_obj.qc_voice_widget_lan_speak_now+'</h2>'+
                    '<div class="voice_countdown"></div>'+
                    '<canvas width="384" height="60">'+
                      '<div>'+voice_obj.qc_voice_widget_lan_canvas_not_available+'</div>'+
                    '</canvas>'+
                    '<p style="display:none">'+voice_obj.qc_voice_widget_lan_please_wait+'</p>'+
                    '<button class="qc_audio_record_button" id="botbtnStop">'+voice_obj.qc_voice_widget_lan_stop_save+'</button>'+
                  '</div>'+
                  '<div class="wpbot_tts_wrapper"></div>'+
                '</div>'+
              '</div>';
    jQuery('#qc_audio_recorder').append(html);

  }
  
  function startRecordingButtonClick(event, cForm ) {
      event.preventDefault();
      jQuery( '#qc_audio_main' ).hide();
      jQuery( '.wpbot_voice_addon_container' ).remove();

      addvoicecontainer();
      jQuery( '#qc_audio_recorder' ).show();

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

      // navigator.mediaDevices.getUserMedia( { audio: true, video: false } ).then( function( stream ) {
      navigator.mediaDevices.getUserMedia( constraints_audio ).then( function( stream ) {

          jQuery('.bot_voice_wrapper').removeAttr('style');
          jQuery('.bot_voice_wrapper').css("display","flex");
          jQuery('.voice_countdown').html('');
          jQuery('.voice_countdown').show();


          audioContext = new AudioContext();

          sampleRate = audioContext.sampleRate;

          /** Assign to gumStream for later use.  */
          gumStream = stream;

          /** Use the stream. */
          input = audioContext.createMediaStreamSource( stream );

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
          console.log( 'Error connecting with Mic. Please check your Mic.', 'warn', true );
          console.log( err, 'error', true );
          alert( 'Error connecting with Mic. Please check your Mic' );


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

          let sliceWidth = animation.width * 1.0 / bufferLength;
          let x = 0;

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

    jQuery('.wpcf7-submit').prop('disabled', true);

    var form_data = new FormData();  

    form_data.append("audio_data", blob);
    form_data.append("action", "qcld_audio_save");  // Adding extra parameters to form_data
    jQuery.ajax({
        url: voice_obj.ajax_url,
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,   // Setting the data attribute of ajax with file_data
        type: 'post',
        
        success: function(response) {
            var obj = jQuery.parseJSON(response);
            jQuery(".wpcf7-submit").prop('disabled', false);
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


  jQuery( '.qc_audio_remove_button' ).on( 'click', function(e) {
    e.preventDefault();
    jQuery( '#qc_audio_display' ).hide();
    jQuery( '#qc_audio_main' ).show();
    jQuery('#qc_audio_url').val( "" );

  });

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