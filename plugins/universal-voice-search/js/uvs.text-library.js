// *****************************************************************************************************
// *******              speak2web UNIVERSAL VOICE SEARCH                                    ***********
// *******               AI Service requires subcriptions                                    ***********
// *******               Get your subscription at                                            ***********
// *******                    https://speak2web.com/plugin#plans                             ***********
// *******               Need support? https://speak2web.com/support                         ***********
// *******               Licensed GPLv2+                                                     ***********
//******************************************************************************************************


//####################################
// PLUGIN LANGUAGE
//####################################
var uvsTypeOfSelectedLanguage = (typeof (vs.uvsSelectedLanguage) != 'undefined' && vs.uvsSelectedLanguage !== null) ? vs.uvsSelectedLanguage.trim() : 'English';
var uvsSelectedLang = (typeof (vs.uvsSelectedLanguage) != 'undefined' && vs.uvsSelectedLanguage !== null) ? vs.uvsSelectedLanguage.trim() : 'en-US';

var uvsIsSttLangCtx = typeof _uvsSttLanguageContext != 'undefined' && !!_uvsSttLanguageContext && _uvsSttLanguageContext instanceof Object ? true : false;
var uvsSttLanguageContext = {
    'gcp': {
        'stt': null,
        'langCode': null,
        'endPoint': null,
        'key': null,
        'qs': { 'key': null }
    }
}

if (uvsIsSttLangCtx === true) {
    //###############################
    // GCP
    //###############################
    let gcp = 'gcp' in _uvsSttLanguageContext && _uvsSttLanguageContext['gcp'] instanceof Object ? _uvsSttLanguageContext['gcp'] : {};
    uvsSttLanguageContext['gcp']['stt'] = 'stt' in gcp && gcp['stt'] == 'Y' ? true : false;

    if (!!uvsSttLanguageContext['gcp']['stt']) {
        uvsSttLanguageContext['gcp']['endPoint'] = 'endPoint' in gcp && typeof gcp['endPoint'] != 'undefined' && !!gcp['endPoint'] ? gcp['endPoint'] : null;
        uvsSttLanguageContext['gcp']['key'] = 'key' in gcp && typeof gcp['key'] != 'undefined' && !!gcp['key'] ? gcp['key'] : null;
        uvsSttLanguageContext['gcp']['langCode'] = 'langCode' in gcp && typeof gcp['langCode'] != 'undefined' && !!gcp['langCode'] ? gcp['langCode'] : null;

        let qs = 'qs' in gcp && gcp['qs'] instanceof Object ? gcp['qs'] : {};
        uvsSttLanguageContext['gcp']['qs']['key'] = 'key' in qs && typeof qs['key'] != 'undefined' && !!qs['key'] ? qs['key'] : null;
    }
}

//####################################
// CLIENT INFO
//####################################
let uvsNavigator = { 'navigatorUserAgent': navigator.userAgent.toLowerCase(), 'navigatorPlatform': navigator.platform };
var uvsClientInfo = {
    'chrome': uvsNavigator.navigatorUserAgent.indexOf('chrome') > -1,
    'firefox': uvsNavigator.navigatorUserAgent.indexOf('firefox') > -1,
    'edge': uvsNavigator.navigatorUserAgent.indexOf('edge') > -1 || uvsNavigator.navigatorUserAgent.indexOf('edg') > -1,
    'ie': uvsNavigator.navigatorUserAgent.indexOf('msie') > -1 || uvsNavigator.navigatorUserAgent.indexOf('trident') > -1,
    'opera': uvsNavigator.navigatorUserAgent.indexOf('opera') > -1 || uvsNavigator.navigatorUserAgent.indexOf('opr') > -1,

    'ios': !!uvsNavigator.navigatorPlatform && /iPad|iPhone|iPod/.test(uvsNavigator.navigatorPlatform),
    'android': uvsNavigator.navigatorUserAgent.indexOf("android") > -1,
    'windows': uvsNavigator.navigatorUserAgent.indexOf("windows") > -1,
    'linux': uvsNavigator.navigatorUserAgent.indexOf("linux") > -1,

    'macSafari': uvsNavigator.navigatorUserAgent.indexOf('mac') > -1 && uvsNavigator.navigatorUserAgent.indexOf('safari') > -1 && uvsNavigator.navigatorUserAgent.indexOf('chrome') === -1,
    'iosSafari': this.ios === true && uvsNavigator.navigatorUserAgent.indexOf('safari') > -1,
};

if (uvsClientInfo['chrome'] === true && (uvsClientInfo['opera'] === true || uvsClientInfo['edge'] === true)) {
    uvsClientInfo['chrome'] = false;
}

/**
 * Path map for audio files of short phrases
 * 
 */
var uvsAudioShortPharasesPaths = {
    'root': 'short_phrases/',
    'voice': uvsSelectedLang + '/',
    'random': 'random/',
    'general': 'general/',
    'getRandomVoicesPath': function () {
        return this.root + this.random + this.voice + uvsSelectedLang + '_';
    },
    'getGeneralVoicesPath': function () {
        return this.root + this.general + this.voice + uvsSelectedLang + '_';
    }
}

let uvsRandomShortPhrasePath = uvsAudioShortPharasesPaths.getRandomVoicesPath();
let uvsGeneralShortPhrasePath = uvsAudioShortPharasesPaths.getGeneralVoicesPath();
let uvsSilenceSoundPath = uvsAudioShortPharasesPaths.root + 'silence.mp3';

/**
 * Alternative response audio files to be played/spoken
 *
 */
var uvsAlternativeResponse = {
    /**
     * Text in audio file: Let me search it
     */
    'basic': uvsGeneralShortPhrasePath + "basic.mp3",
    /**
     * Text in audio file: I am sorry but I am unable to access your microphone, Please connect a microphone or you can also type your question if needed
     */
    'micConnect': uvsGeneralShortPhrasePath + "mic_connect.mp3",
    /**
     * Text in audio file: Voice search is currently unavailable, Please try again after some time
     */
    'unavailable': uvsGeneralShortPhrasePath + "unavailable.mp3",
    /**
     * Text in audio file: I am unable to hear you
     */
    'notAudible': uvsGeneralShortPhrasePath + "not_audible.mp3",
    'randomLib': [
        /**
         * Text in audio file: Just a second please
         */
        uvsRandomShortPhrasePath + "0.mp3",
        /**
         * Text in audio file: I am on it
         */
        uvsRandomShortPhrasePath + "1.mp3",
        /**
         * Text in audio file: No problem
         */
        uvsRandomShortPhrasePath + "2.mp3",
        /**
         * Text in audio file: Just a moment, I need a brief rest
         */
        uvsRandomShortPhrasePath + "3.mp3",
        /**
         * Text in audio file: You seem to work too hard, Get your self a coffee and I will find it up for you
         */
        uvsRandomShortPhrasePath + "4.mp3",
        /**
         * Text in audio file: Coming right up
         */
        uvsRandomShortPhrasePath + "5.mp3",
        /**
         * Text in audio file: I will do my best
         */
        uvsRandomShortPhrasePath + "6.mp3",
        /**
         * Text in audio file: Anything for you. I will get right on it
         */
        uvsRandomShortPhrasePath + "7.mp3",
        /**
         * Text in audio file: Working on it, One moment please
         */
        uvsRandomShortPhrasePath + "8.mp3",
        /**
         * Text in audio file: Beep - Beep - Beep, just kidding, One moment please
         */
        uvsRandomShortPhrasePath + "9.mp3"
    ],
};

var uvsMessages = _uvsTextPhrases['uvsMessages'];
var uvsErrorLibrary = _uvsTextPhrases['uvsErrorLibrary'];
var uvsWidgetMessages = _uvsTextPhrases['uvsWidgetMessages'];

var uvsIsMuteSimon = typeof vs._uvsMuteAudioPhrases != 'undefined' && !!vs._uvsMuteAudioPhrases && vs._uvsMuteAudioPhrases == 'yes' ? true : false;
var uvsIsSingleClick = typeof vs._uvsSingleClick != 'undefined' && !!vs._uvsSingleClick && vs._uvsSingleClick == 'yes' ? true : false;
var uvsIsElementor = typeof vs._uvsElementor != 'undefined' && !!vs._uvsElementor && vs._uvsElementor == 'yes' ? true : false;
