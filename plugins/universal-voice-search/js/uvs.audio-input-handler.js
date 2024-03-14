// *****************************************************************************************************
// *******              speak2web UNIVERSAL VOICE SEARCH                                     ***********
// *******               Get your subscription at                                            ***********
// *******                    https://speak2web.com/plugin#plans                             ***********
// *******               Need support? https://speak2web.com/support                         ***********
// *******               Licensed GPLv2+                                                     ***********
//******************************************************************************************************

window.AudioContext = window.AudioContext || window.webkitAudioContext;

var uvsAudioContext = null;
var uvsAudioInput = null,
    uvsRealAudioInput = null,
    uvsInputPoint = null,
    uvsAudioRecorder = null;
var uvsRecIndex = 0;
var initCB = null;
let uvsStream = null;

/**
 * Function to initialize capture audio resources
 * 
 * @param { cb: function } A callback function
 */
function uvsInitAudio(cb) {
    initCB = cb;

    // Check when last service log was updated
    try {
        let uvsLastUpdatedAtTimestamp = uvsServiceLogs.updatedAt || null;

        if (uvsLastUpdatedAtTimestamp !== null) {
            uvsLastUpdatedAtTimestamp = Number(uvsLastUpdatedAtTimestamp);
            let currentUtcTimestamp = Math.round(new Date().getTime() / 1000);

            // Add 24 hours to last updated timestamp
            uvsLastUpdatedAtTimestamp = uvsLastUpdatedAtTimestamp + (24 * 3600);

            // Check if last service call log update was older than 24 hours
            if (currentUtcTimestamp >= uvsLastUpdatedAtTimestamp) {
                // Log service call count
                uvsLogServiceCall(1);
            }
        }
    } catch (err) {
        // do nothing
    }

    uvsAudioContext = new AudioContext();

    navigator.mediaDevices.getUserMedia({ "audio": !0 })
        .then(uvsGotStream)
        .catch(function (e) {
            // Play 'micConnect' playback
            uvsAudioPlayer.configure(uvsAlternativeResponse['micConnect']);
            uvsAudioPlayer.play();
            console.log("VF: We caught an error while gaining access to audio input due to: ", e.message);
        }
        );
}

/**
 * A callback function to obtain audio stream
 * 
 * @param { stream: MediaStream } An audio track 
 */
function uvsGotStream(stream) {
    uvsInputPoint = uvsAudioContext.createGain();
    uvsStream = stream;

    // Create an AudioNode from the stream.
    uvsRealAudioInput = uvsAudioContext.createMediaStreamSource(stream);
    uvsAudioInput = uvsRealAudioInput;
    uvsAudioInput.connect(uvsInputPoint);

    uvsAudioRecorder = new Recorder(uvsInputPoint);
    initCB(uvsAudioRecorder);
}

/**
 * Function to stop accessing audio resource
 *
 */
function uvsStopAudio() {
    try {
        uvsStream.getTracks().forEach(function (track) {
            track.stop();
        });

        uvsAudioContext.close();
        uvsAudioContext = null;
    } catch (err) {
        console.log('UVS Exception: Unable to release audio resource due to: ' + err.message);
    }
}
