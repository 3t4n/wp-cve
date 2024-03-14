(function ($) {
	'use strict';


	let HTMLSpeak = function () {
		console.log('shall we ?');
		// Speacking before ? Stop !
		if(speechSynthesis.speaking) speechSynthesis.cancel()

		// Get the params
		var words = $(this).attr('data-say-content') || $(this).text();
		var speed = $(this).attr('data-speed') || 0.8;
		var lang = $(this).attr('data-lang') || 'en-US';
		var msg = new SpeechSynthesisUtterance();

		// Set the utterance
		msg.text = words;
		msg.rate = speed;
		msg.lang = lang;

		// Bind functions
		msg.onstart = (e) => $(this).addClass('active');
		msg.onend = (e) => $(this).removeClass('active');

		// Queue this utterance.
		speechSynthesis.speak(msg);
	}

	$(function () {

		// Var list
		let gAudio = null;

		// On click anywhere, should stop speaking
		$('body').on('click', function(){
			if(speechSynthesis.speaking) speechSynthesis.cancel()
			if(gAudio) gAudio.pause();
		})

		$('.sayit').on('click', function (e) {
			e.stopPropagation();

			/* We are using Google API ? Just read the audio file */
			var google_audio_src = $(this).attr('data-say-google');
			var has_google_tts = (google_audio_src)?true:false;
			if(has_google_tts){
				if(gAudio){
					gAudio.pause();
				}
				gAudio = new Audio(google_audio_src);
				gAudio.play();
				gAudio.onplay = () => $(this).addClass('active');
				gAudio.onended = () => $(this).removeClass('active');
				gAudio.onpause = () => $(this).removeClass('active');
				return;
			}

			console.log('so we try...');

			HTMLSpeak.bind(this)();
			// Speacking before ? Stop !
			/*if(speechSynthesis.speaking) speechSynthesis.cancel()

			// Get the params
			var words = $(this).attr('data-say-content') || $(this).text();
			var speed = $(this).attr('data-speed') || 0.8;
			var lang = $(this).attr('data-lang') || 'en-US';
			var msg = new SpeechSynthesisUtterance();

			// Set the utterance
			msg.text = words;
			msg.rate = speed;
			msg.lang = lang;

			// Bind functions
			msg.onstart = (e) => $(this).addClass('active');
			msg.onend = (e) => $(this).removeClass('active');

			// Queue this utterance.
			speechSynthesis.speak(msg);*/
		})

	});

})(jQuery);


/********************************
 * 		Unused Helpers 
 *******************************/
const getVoices = () => {
	return new Promise(resolve => {
		let voices = speechSynthesis.getVoices()
		if (voices.length) {
			resolve(voices)
			return
		}
		speechSynthesis.onvoiceschanged = () => {
			voices = speechSynthesis.getVoices()
			resolve(voices)
		}
	})
}

async function printVoicesList() {
	; (await getVoices()).forEach(voice => {
		console.log(voice.name, voice.lang)
	})
}
