function makeRequest( dataObj , dbOptions){

    var chatRequestBody = {
        temperature: dbOptions.chatTemperature,
        top_p: dbOptions.chatTopP,
        max_tokens: dbOptions.chatMaxTokents,
        frequency_penalty: dbOptions.chatFrequencyPenalty,
        presence_penalty:  dbOptions.chatPresencePenalty,
    };

    chatRequestBody.model = dbOptions.chatModel;

    switch(dbOptions.chatModel){
        case 'gpt-3.5-turbo':
        case 'gpt-3.5-turbo-16k':
            dataObj.chatConversation.push({role: 'user', content: dataObj.prompt});
            chatRequestBody.messages = (dataObj.chatConversation);
            break;
        default:
            dataObj.chatConversation.push("Human: " + dataObj.prompt + '.');
            chatRequestBody.best_of = dbOptions.chatBestOf;
            chatRequestBody.prompt = dataObj.chatConversation.join("");
            break;
    }

    return fetch(dataObj.requestUrl, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Authorization": "Bearer " + dataObj.apiKey
            },
            body: JSON.stringify(chatRequestBody)
        })
        .then(response => {
            return response.json();
        })
}
	
function getAIButtons(dbOptions) {
    var copyButtonSvg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="#636a84"><path d="M64 464H288c8.8 0 16-7.2 16-16V384h48v64c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V224c0-35.3 28.7-64 64-64h64v48H64c-8.8 0-16 7.2-16 16V448c0 8.8 7.2 16 16 16zM224 304H448c8.8 0 16-7.2 16-16V64c0-8.8-7.2-16-16-16H224c-8.8 0-16 7.2-16 16V288c0 8.8 7.2 16 16 16zm-64-16V64c0-35.3 28.7-64 64-64H448c35.3 0 64 28.7 64 64V288c0 35.3-28.7 64-64 64H224c-35.3 0-64-28.7-64-64z"/></svg>'
    if (dbOptions.chatboxTheme == 'chatgpt') {
        copyButtonSvg = '<svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg>';
    }

    var buttons = '';
    buttons += '<div class="ays-assistant-chatbox-ai-message-buttons">';
        buttons += '<div class="ays-assistant-chatbox-ai-message-copy" title="Click to Copy">' +copyButtonSvg + '</div>';
    buttons += '</div>';
    return buttons;
}
	
function getCountries() {
    return ( {
        'sq': 'Albanian',
        'ar': 'Arabic',
        'hy': 'Armenian',
        'awa': 'Awadhi',
        'az': 'Azerbaijani',
        'ba': 'Bashkir',
        'eu': 'Basque',
        'be': 'Belarusian',
        'bn': 'Bengali',
        'bho': 'Bhojpuri',
        'bs': 'Bosnian',
        'pt-BR': 'Brazilian Portuguese',
        'bg': 'Bulgarian',
        'yue': 'Cantonese (Yue)',
        'ca': 'Catalan',
        'hne': 'Chhattisgarhi',
        'zh': 'Chinese',
        'hr': 'Croatian',
        'cs': 'Czech',
        'da': 'Danish',
        'doi': 'Dogri',
        'nl': 'Dutch',
        'en': 'English',
        'et': 'Estonian',
        'fo': 'Faroese',
        'fi': 'Finnish',
        'fr': 'French',
        'gl': 'Galician',
        'ka': 'Georgian',
        'de': 'German',
        'el': 'Greek',
        'gu': 'Gujarati',
        'bgc': 'Haryanvi',
        'hi': 'Hindi',
        'hu': 'Hungarian',
        'id': 'Indonesian',
        'ga': 'Irish',
        'it': 'Italian',
        'ja': 'Japanese',
        'jv': 'Javanese',
        'kn': 'Kannada',
        'ks': 'Kashmiri',
        'kk': 'Kazakh',
        'kok': 'Konkani',
        'ko': 'Korean',
        'ky': 'Kyrgyz',
        'lv': 'Latvian',
        'lt': 'Lithuanian',
        'mk': 'Macedonian',
        'mai': 'Maithili',
        'ms': 'Malay',
        'mt': 'Maltese',
        'zh': 'Mandarin',
        'mr': 'Marathi',
        'mwr': 'Marwari',
        'nan': 'Min Nan',
        'ro': 'Moldovan',
        'mn': 'Mongolian',
        'sr-ME': 'Montenegrin',
        'ne': 'Nepali',
        'no': 'Norwegian',
        'or': 'Oriya',
        'ps': 'Pashto',
        'fa': 'Persian (Farsi)',
        'pl': 'Polish',
        'pt': 'Portuguese',
        'pa': 'Punjabi',
        'raj': 'Rajasthani',
        'ro': 'Romanian',
        'ru': 'Russian',
        'sa': 'Sanskrit',
        'sat': 'Santali',
        'sr': 'Serbian',
        'sd': 'Sindhi',
        'si': 'Sinhala',
        'sk': 'Slovak',
        'sl': 'Slovene',
        'es': 'Spanish',
        'sw': 'Swahili',
        'sv': 'Swedish',
        'tg': 'Tajik',
        'ta': 'Tamil',
        'tt': 'Tatar',
        'te': 'Telugu',
        'th': 'Thai',
        'tr': 'Turkish',
        'tk': 'Turkmen',
        'uk': 'Ukrainian',
        'ur': 'Urdu',
        'uz': 'Uzbek',
        'vi': 'Vietnamese',
        'cy': 'Welsh',
        'wu': 'Wu'
      }
    );
      
}

function setCookie (name, value, options = {}) {
    options = {
        path: '/',
        ...options
    };
  
    if (options.expires instanceof Date) {
        options.expires = options.expires.toUTCString();
    }
  
    var updatedCookie = encodeURIComponent(name) + "=" + encodeURIComponent(value);
  
    for (var optionKey in options) {
        updatedCookie += "; " + optionKey;
        var optionValue = options[optionKey];
        if (optionValue !== true) {
            updatedCookie += "=" + optionValue;
        }
    }
  
    document.cookie = updatedCookie;
}

function getCookie (name) {
    var matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));

    return matches ? decodeURIComponent(matches[1]) : undefined;
}

function deleteCookie (name) {
    setCookie(name, "", {'max-age': -1});
}