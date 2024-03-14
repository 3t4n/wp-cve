<?php

class UvsLanguage
{
    const NAME = 'name';
    const LANG_CODE = 'langCode';
    const SHORT_PHRASE_LANG = 'shortPhraseLang';
    const COMMON_LANG_CLUSTER = array('en-US', 'es-ES', 'ar-XA');

    const MESSAGES = 'uvsMessages';
    const MIC_NOT_ACCESSIBLE = 'micNotAccessible';
    const BROWSER_DENIED_ACCESS = 'browserDenyMicAccess';
    const TRANSCRIBING = 'transcribeText';
    const UNABLE_TO_HEAR = 'unableToHear';
    const ASK_AGAIN = 'ask';
    const CAN_NOT_ACCESS_MIC = 'cantAccessMicrophone';

    const WIDGET_MESSAGES = 'uvsWidgetMessages';
    const PLACEHOLDER = 'placeholder';

    const ERRORS = 'uvsErrorLibrary';
    const OUT_OF_SERVICE = 'outOfService';

    /**
     * Static member as set of textual phrases in 130 languages (Combining GCP and IBM)
     * 
     */
    public static $textual_phrases = array(
        'ar-AR' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "لا يمكنني الوصول إلى الميكروفون",
                self::BROWSER_DENIED_ACCESS => "أمان المتصفح الخاص بك لا يسمح لي بالوصول إلى الميكروفون",
                self::TRANSCRIBING => " نسخ ....",
                self::UNABLE_TO_HEAR => "انا غير قادر على سماعك",
                self::ASK_AGAIN => " قلها ثانية ....",
                self::CAN_NOT_ACCESS_MIC => "لا يمكن الوصول إلى الميكروفون",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "أو اكتب الاستعلام الخاص بك"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "البحث الصوتي خارج الخدمة ، يرجى المحاولة مرة أخرى بعد مرور بعض الوقت"
            )
        ),
        'ar-AE' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "لا يمكنني الوصول إلى الميكروفون",
                self::BROWSER_DENIED_ACCESS => "أمان المتصفح الخاص بك لا يسمح لي بالوصول إلى الميكروفون",
                self::TRANSCRIBING => " نسخ ....",
                self::UNABLE_TO_HEAR => "انا غير قادر على سماعك",
                self::ASK_AGAIN => " قلها ثانية ....",
                self::CAN_NOT_ACCESS_MIC => "لا يمكن الوصول إلى الميكروفون",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "أو اكتب الاستعلام الخاص بك"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "البحث الصوتي خارج الخدمة ، يرجى المحاولة مرة أخرى بعد مرور بعض الوقت"
            )
        ),
        'ar-BH' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "لا يمكنني الوصول إلى الميكروفون",
                self::BROWSER_DENIED_ACCESS => "أمان المتصفح الخاص بك لا يسمح لي بالوصول إلى الميكروفون",
                self::TRANSCRIBING => " نسخ ....",
                self::UNABLE_TO_HEAR => "انا غير قادر على سماعك",
                self::ASK_AGAIN => " قلها ثانية ....",
                self::CAN_NOT_ACCESS_MIC => "لا يمكن الوصول إلى الميكروفون",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "أو اكتب الاستعلام الخاص بك"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "البحث الصوتي خارج الخدمة ، يرجى المحاولة مرة أخرى بعد مرور بعض الوقت"
            )
        ),
        'ar-DZ' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "لا يمكنني الوصول إلى الميكروفون",
                self::BROWSER_DENIED_ACCESS => "أمان المتصفح الخاص بك لا يسمح لي بالوصول إلى الميكروفون",
                self::TRANSCRIBING => " نسخ ....",
                self::UNABLE_TO_HEAR => "انا غير قادر على سماعك",
                self::ASK_AGAIN => " قلها ثانية ....",
                self::CAN_NOT_ACCESS_MIC => "لا يمكن الوصول إلى الميكروفون",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "أو اكتب الاستعلام الخاص بك"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "البحث الصوتي خارج الخدمة ، يرجى المحاولة مرة أخرى بعد مرور بعض الوقت"
            )
        ),
        'ar-EG' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "لا يمكنني الوصول إلى الميكروفون",
                self::BROWSER_DENIED_ACCESS => "أمان المتصفح الخاص بك لا يسمح لي بالوصول إلى الميكروفون",
                self::TRANSCRIBING => " نسخ ....",
                self::UNABLE_TO_HEAR => "انا غير قادر على سماعك",
                self::ASK_AGAIN => " قلها ثانية ....",
                self::CAN_NOT_ACCESS_MIC => "لا يمكن الوصول إلى الميكروفون",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "أو اكتب الاستعلام الخاص بك"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "البحث الصوتي خارج الخدمة ، يرجى المحاولة مرة أخرى بعد مرور بعض الوقت"
            )
        ),
        'ar-IL' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "لا يمكنني الوصول إلى الميكروفون",
                self::BROWSER_DENIED_ACCESS => "أمان المتصفح الخاص بك لا يسمح لي بالوصول إلى الميكروفون",
                self::TRANSCRIBING => " نسخ ....",
                self::UNABLE_TO_HEAR => "انا غير قادر على سماعك",
                self::ASK_AGAIN => " قلها ثانية ....",
                self::CAN_NOT_ACCESS_MIC => "لا يمكن الوصول إلى الميكروفون",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "أو اكتب الاستعلام الخاص بك"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "البحث الصوتي خارج الخدمة ، يرجى المحاولة مرة أخرى بعد مرور بعض الوقت"
            )
        ),
        'ar-IQ' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "لا يمكنني الوصول إلى الميكروفون",
                self::BROWSER_DENIED_ACCESS => "أمان المتصفح الخاص بك لا يسمح لي بالوصول إلى الميكروفون",
                self::TRANSCRIBING => " نسخ ....",
                self::UNABLE_TO_HEAR => "انا غير قادر على سماعك",
                self::ASK_AGAIN => " قلها ثانية ....",
                self::CAN_NOT_ACCESS_MIC => "لا يمكن الوصول إلى الميكروفون",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "أو اكتب الاستعلام الخاص بك"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "البحث الصوتي خارج الخدمة ، يرجى المحاولة مرة أخرى بعد مرور بعض الوقت"
            )
        ),
        'ar-JO' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "لا يمكنني الوصول إلى الميكروفون",
                self::BROWSER_DENIED_ACCESS => "أمان المتصفح الخاص بك لا يسمح لي بالوصول إلى الميكروفون",
                self::TRANSCRIBING => " نسخ ....",
                self::UNABLE_TO_HEAR => "انا غير قادر على سماعك",
                self::ASK_AGAIN => " قلها ثانية ....",
                self::CAN_NOT_ACCESS_MIC => "لا يمكن الوصول إلى الميكروفون",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "أو اكتب الاستعلام الخاص بك"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "البحث الصوتي خارج الخدمة ، يرجى المحاولة مرة أخرى بعد مرور بعض الوقت"
            )
        ),
        'ar-KW' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "لا يمكنني الوصول إلى الميكروفون",
                self::BROWSER_DENIED_ACCESS => "أمان المتصفح الخاص بك لا يسمح لي بالوصول إلى الميكروفون",
                self::TRANSCRIBING => " نسخ ....",
                self::UNABLE_TO_HEAR => "انا غير قادر على سماعك",
                self::ASK_AGAIN => " قلها ثانية ....",
                self::CAN_NOT_ACCESS_MIC => "لا يمكن الوصول إلى الميكروفون",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "أو اكتب الاستعلام الخاص بك"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "البحث الصوتي خارج الخدمة ، يرجى المحاولة مرة أخرى بعد مرور بعض الوقت"
            )
        ),
        'ar-LB' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "لا يمكنني الوصول إلى الميكروفون",
                self::BROWSER_DENIED_ACCESS => "أمان المتصفح الخاص بك لا يسمح لي بالوصول إلى الميكروفون",
                self::TRANSCRIBING => " نسخ ....",
                self::UNABLE_TO_HEAR => "انا غير قادر على سماعك",
                self::ASK_AGAIN => " قلها ثانية ....",
                self::CAN_NOT_ACCESS_MIC => "لا يمكن الوصول إلى الميكروفون",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "أو اكتب الاستعلام الخاص بك"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "البحث الصوتي خارج الخدمة ، يرجى المحاولة مرة أخرى بعد مرور بعض الوقت"
            )
        ),
        'ar-MA' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "لا يمكنني الوصول إلى الميكروفون",
                self::BROWSER_DENIED_ACCESS => "أمان المتصفح الخاص بك لا يسمح لي بالوصول إلى الميكروفون",
                self::TRANSCRIBING => " نسخ ....",
                self::UNABLE_TO_HEAR => "انا غير قادر على سماعك",
                self::ASK_AGAIN => " قلها ثانية ....",
                self::CAN_NOT_ACCESS_MIC => "لا يمكن الوصول إلى الميكروفون",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "أو اكتب الاستعلام الخاص بك"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "البحث الصوتي خارج الخدمة ، يرجى المحاولة مرة أخرى بعد مرور بعض الوقت"
            )
        ),
        'ar-OM' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "لا يمكنني الوصول إلى الميكروفون",
                self::BROWSER_DENIED_ACCESS => "أمان المتصفح الخاص بك لا يسمح لي بالوصول إلى الميكروفون",
                self::TRANSCRIBING => " نسخ ....",
                self::UNABLE_TO_HEAR => "انا غير قادر على سماعك",
                self::ASK_AGAIN => " قلها ثانية ....",
                self::CAN_NOT_ACCESS_MIC => "لا يمكن الوصول إلى الميكروفون",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "أو اكتب الاستعلام الخاص بك"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "البحث الصوتي خارج الخدمة ، يرجى المحاولة مرة أخرى بعد مرور بعض الوقت"
            )
        ),
        'ar-PS' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "لا يمكنني الوصول إلى الميكروفون",
                self::BROWSER_DENIED_ACCESS => "أمان المتصفح الخاص بك لا يسمح لي بالوصول إلى الميكروفون",
                self::TRANSCRIBING => " نسخ ....",
                self::UNABLE_TO_HEAR => "انا غير قادر على سماعك",
                self::ASK_AGAIN => " قلها ثانية ....",
                self::CAN_NOT_ACCESS_MIC => "لا يمكن الوصول إلى الميكروفون",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "أو اكتب الاستعلام الخاص بك"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "البحث الصوتي خارج الخدمة ، يرجى المحاولة مرة أخرى بعد مرور بعض الوقت"
            )
        ),
        'ar-QA' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "لا يمكنني الوصول إلى الميكروفون",
                self::BROWSER_DENIED_ACCESS => "أمان المتصفح الخاص بك لا يسمح لي بالوصول إلى الميكروفون",
                self::TRANSCRIBING => " نسخ ....",
                self::UNABLE_TO_HEAR => "انا غير قادر على سماعك",
                self::ASK_AGAIN => " قلها ثانية ....",
                self::CAN_NOT_ACCESS_MIC => "لا يمكن الوصول إلى الميكروفون",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "أو اكتب الاستعلام الخاص بك"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "البحث الصوتي خارج الخدمة ، يرجى المحاولة مرة أخرى بعد مرور بعض الوقت"
            )
        ),
        'ar-SA' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "لا يمكنني الوصول إلى الميكروفون",
                self::BROWSER_DENIED_ACCESS => "أمان المتصفح الخاص بك لا يسمح لي بالوصول إلى الميكروفون",
                self::TRANSCRIBING => " نسخ ....",
                self::UNABLE_TO_HEAR => "انا غير قادر على سماعك",
                self::ASK_AGAIN => " قلها ثانية ....",
                self::CAN_NOT_ACCESS_MIC => "لا يمكن الوصول إلى الميكروفون",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "أو اكتب الاستعلام الخاص بك"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "البحث الصوتي خارج الخدمة ، يرجى المحاولة مرة أخرى بعد مرور بعض الوقت"
            )
        ),
        'ar-TN' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "لا يمكنني الوصول إلى الميكروفون",
                self::BROWSER_DENIED_ACCESS => "أمان المتصفح الخاص بك لا يسمح لي بالوصول إلى الميكروفون",
                self::TRANSCRIBING => " نسخ ....",
                self::UNABLE_TO_HEAR => "انا غير قادر على سماعك",
                self::ASK_AGAIN => " قلها ثانية ....",
                self::CAN_NOT_ACCESS_MIC => "لا يمكن الوصول إلى الميكروفون",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "أو اكتب الاستعلام الخاص بك"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "البحث الصوتي خارج الخدمة ، يرجى المحاولة مرة أخرى بعد مرور بعض الوقت"
            )
        ),
        'de-DE' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "Ich kann nicht auf das Mikrofon zugreifen",
                self::BROWSER_DENIED_ACCESS => "Durch die Sicherheit Ihres Browsers kann ich nicht auf das Mikrofon zugreifen",
                self::TRANSCRIBING => " Transkribieren ....",
                self::UNABLE_TO_HEAR => "Ich kann dich nicht hören",
                self::ASK_AGAIN => " Sage es noch einmal ....",
                self::CAN_NOT_ACCESS_MIC => "kann nicht auf das Mikrofon zugreifen",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "oder geben Sie Ihre Anfrage ein"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Die Sprachsuche ist außer Betrieb. Bitte versuchen Sie es nach einiger Zeit erneut"
            )
        ),
        'en-AU' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "I am unable to access the microphone",
                self::BROWSER_DENIED_ACCESS => "Your browser security does not allow me to access the mic",
                self::TRANSCRIBING => " Transcribing ...",
                self::UNABLE_TO_HEAR => "I am unable to hear you",
                self::ASK_AGAIN => " Say it again ...",
                self::CAN_NOT_ACCESS_MIC => "can not access the microphone",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "or type your query"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Voice search is out of service, Please try again after some time"
            )
        ),
        'en-CA' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "I am unable to access the microphone",
                self::BROWSER_DENIED_ACCESS => "Your browser security does not allow me to access the mic",
                self::TRANSCRIBING => " Transcribing ...",
                self::UNABLE_TO_HEAR => "I am unable to hear you",
                self::ASK_AGAIN => " Say it again ...",
                self::CAN_NOT_ACCESS_MIC => "can not access the microphone",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "or type your query"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Voice search is out of service, Please try again after some time"
            )
        ),
        'en-GB' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "I am unable to access the microphone",
                self::BROWSER_DENIED_ACCESS => "Your browser security does not allow me to access the mic",
                self::TRANSCRIBING => " Transcribing ...",
                self::UNABLE_TO_HEAR => "I am unable to hear you",
                self::ASK_AGAIN => " Say it again ...",
                self::CAN_NOT_ACCESS_MIC => "can not access the microphone",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "or type your query"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Voice search is out of service, Please try again after some time"
            )
        ),
        'en-GH' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "I am unable to access the microphone",
                self::BROWSER_DENIED_ACCESS => "Your browser security does not allow me to access the mic",
                self::TRANSCRIBING => " Transcribing ...",
                self::UNABLE_TO_HEAR => "I am unable to hear you",
                self::ASK_AGAIN => " Say it again ...",
                self::CAN_NOT_ACCESS_MIC => "can not access the microphone",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "or type your query"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Voice search is out of service, Please try again after some time"
            )
        ),
        'en-IE' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "I am unable to access the microphone",
                self::BROWSER_DENIED_ACCESS => "Your browser security does not allow me to access the mic",
                self::TRANSCRIBING => " Transcribing ...",
                self::UNABLE_TO_HEAR => "I am unable to hear you",
                self::ASK_AGAIN => " Say it again ...",
                self::CAN_NOT_ACCESS_MIC => "can not access the microphone",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "or type your query"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Voice search is out of service, Please try again after some time"
            )
        ),
        'en-IN' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "I am unable to access the microphone",
                self::BROWSER_DENIED_ACCESS => "Your browser security does not allow me to access the mic",
                self::TRANSCRIBING => " Transcribing ...",
                self::UNABLE_TO_HEAR => "I am unable to hear you",
                self::ASK_AGAIN => " Say it again ...",
                self::CAN_NOT_ACCESS_MIC => "can not access the microphone",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "or type your query"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Voice search is out of service, Please try again after some time"
            )
        ),
        'en-KE' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "I am unable to access the microphone",
                self::BROWSER_DENIED_ACCESS => "Your browser security does not allow me to access the mic",
                self::TRANSCRIBING => " Transcribing ...",
                self::UNABLE_TO_HEAR => "I am unable to hear you",
                self::ASK_AGAIN => " Say it again ...",
                self::CAN_NOT_ACCESS_MIC => "can not access the microphone",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "or type your query"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Voice search is out of service, Please try again after some time"
            )
        ),
        'en-NG' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "I am unable to access the microphone",
                self::BROWSER_DENIED_ACCESS => "Your browser security does not allow me to access the mic",
                self::TRANSCRIBING => " Transcribing ...",
                self::UNABLE_TO_HEAR => "I am unable to hear you",
                self::ASK_AGAIN => " Say it again ...",
                self::CAN_NOT_ACCESS_MIC => "can not access the microphone",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "or type your query"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Voice search is out of service, Please try again after some time"
            )
        ),
        'en-NZ' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "I am unable to access the microphone",
                self::BROWSER_DENIED_ACCESS => "Your browser security does not allow me to access the mic",
                self::TRANSCRIBING => " Transcribing ...",
                self::UNABLE_TO_HEAR => "I am unable to hear you",
                self::ASK_AGAIN => " Say it again ...",
                self::CAN_NOT_ACCESS_MIC => "can not access the microphone",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "or type your query"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Voice search is out of service, Please try again after some time"
            )
        ),
        'en-PH' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "I am unable to access the microphone",
                self::BROWSER_DENIED_ACCESS => "Your browser security does not allow me to access the mic",
                self::TRANSCRIBING => " Transcribing ...",
                self::UNABLE_TO_HEAR => "I am unable to hear you",
                self::ASK_AGAIN => " Say it again ...",
                self::CAN_NOT_ACCESS_MIC => "can not access the microphone",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "or type your query"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Voice search is out of service, Please try again after some time"
            )
        ),
        'en-SG' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "I am unable to access the microphone",
                self::BROWSER_DENIED_ACCESS => "Your browser security does not allow me to access the mic",
                self::TRANSCRIBING => " Transcribing ...",
                self::UNABLE_TO_HEAR => "I am unable to hear you",
                self::ASK_AGAIN => " Say it again ...",
                self::CAN_NOT_ACCESS_MIC => "can not access the microphone",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "or type your query"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Voice search is out of service, Please try again after some time"
            )
        ),
        'en-TZ' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "I am unable to access the microphone",
                self::BROWSER_DENIED_ACCESS => "Your browser security does not allow me to access the mic",
                self::TRANSCRIBING => " Transcribing ...",
                self::UNABLE_TO_HEAR => "I am unable to hear you",
                self::ASK_AGAIN => " Say it again ...",
                self::CAN_NOT_ACCESS_MIC => "can not access the microphone",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "or type your query"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Voice search is out of service, Please try again after some time"
            )
        ),
        'en-US' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "I am unable to access the microphone",
                self::BROWSER_DENIED_ACCESS => "Your browser security does not allow me to access the mic",
                self::TRANSCRIBING => " Transcribing ...",
                self::UNABLE_TO_HEAR => "I am unable to hear you",
                self::ASK_AGAIN => " Say it again ...",
                self::CAN_NOT_ACCESS_MIC => "can not access the microphone",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "or type your query"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Voice search is out of service, Please try again after some time"
            )
        ),
        'en-ZA' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "I am unable to access the microphone",
                self::BROWSER_DENIED_ACCESS => "Your browser security does not allow me to access the mic",
                self::TRANSCRIBING => " Transcribing ...",
                self::UNABLE_TO_HEAR => "I am unable to hear you",
                self::ASK_AGAIN => " Say it again ...",
                self::CAN_NOT_ACCESS_MIC => "can not access the microphone",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "or type your query"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Voice search is out of service, Please try again after some time"
            )
        ),
        'es-AR' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "No puedo acceder al micrófono",
                self::BROWSER_DENIED_ACCESS => "La seguridad de su navegador no me permite acceder al micrófono",
                self::TRANSCRIBING => " Transcribiendo ...",
                self::UNABLE_TO_HEAR => "No puedo escucharte",
                self::ASK_AGAIN => " Dilo otra vez ....",
                self::CAN_NOT_ACCESS_MIC => "no puede acceder al micrófono",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "o escriba su consulta"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "La búsqueda por voz está fuera de servicio. Vuelve a intentarlo después de un tiempo."
            )
        ),
        'es-BO' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "No puedo acceder al micrófono",
                self::BROWSER_DENIED_ACCESS => "La seguridad de su navegador no me permite acceder al micrófono",
                self::TRANSCRIBING => " Transcribiendo ...",
                self::UNABLE_TO_HEAR => "No puedo escucharte",
                self::ASK_AGAIN => " Dilo otra vez ....",
                self::CAN_NOT_ACCESS_MIC => "no puede acceder al micrófono",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "o escriba su consulta"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "La búsqueda por voz está fuera de servicio. Vuelve a intentarlo después de un tiempo."
            )
        ),
        'es-CL' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "No puedo acceder al micrófono",
                self::BROWSER_DENIED_ACCESS => "La seguridad de su navegador no me permite acceder al micrófono",
                self::TRANSCRIBING => " Transcribiendo ...",
                self::UNABLE_TO_HEAR => "No puedo escucharte",
                self::ASK_AGAIN => " Dilo otra vez ....",
                self::CAN_NOT_ACCESS_MIC => "no puede acceder al micrófono",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "o escriba su consulta"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "La búsqueda por voz está fuera de servicio. Vuelve a intentarlo después de un tiempo."
            )
        ),
        'es-CO' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "No puedo acceder al micrófono",
                self::BROWSER_DENIED_ACCESS => "La seguridad de su navegador no me permite acceder al micrófono",
                self::TRANSCRIBING => " Transcribiendo ...",
                self::UNABLE_TO_HEAR => "No puedo escucharte",
                self::ASK_AGAIN => " Dilo otra vez ....",
                self::CAN_NOT_ACCESS_MIC => "no puede acceder al micrófono",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "o escriba su consulta"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "La búsqueda por voz está fuera de servicio. Vuelve a intentarlo después de un tiempo."
            )
        ),
        'es-CR' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "No puedo acceder al micrófono",
                self::BROWSER_DENIED_ACCESS => "La seguridad de su navegador no me permite acceder al micrófono",
                self::TRANSCRIBING => " Transcribiendo ...",
                self::UNABLE_TO_HEAR => "No puedo escucharte",
                self::ASK_AGAIN => " Dilo otra vez ....",
                self::CAN_NOT_ACCESS_MIC => "no puede acceder al micrófono",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "o escriba su consulta"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "La búsqueda por voz está fuera de servicio. Vuelve a intentarlo después de un tiempo."
            )
        ),
        'es-DO' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "No puedo acceder al micrófono",
                self::BROWSER_DENIED_ACCESS => "La seguridad de su navegador no me permite acceder al micrófono",
                self::TRANSCRIBING => " Transcribiendo ...",
                self::UNABLE_TO_HEAR => "No puedo escucharte",
                self::ASK_AGAIN => " Dilo otra vez ....",
                self::CAN_NOT_ACCESS_MIC => "no puede acceder al micrófono",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "o escriba su consulta"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "La búsqueda por voz está fuera de servicio. Vuelve a intentarlo después de un tiempo."
            )
        ),
        'es-EC' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "No puedo acceder al micrófono",
                self::BROWSER_DENIED_ACCESS => "La seguridad de su navegador no me permite acceder al micrófono",
                self::TRANSCRIBING => " Transcribiendo ...",
                self::UNABLE_TO_HEAR => "No puedo escucharte",
                self::ASK_AGAIN => " Dilo otra vez ....",
                self::CAN_NOT_ACCESS_MIC => "no puede acceder al micrófono",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "o escriba su consulta"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "La búsqueda por voz está fuera de servicio. Vuelve a intentarlo después de un tiempo."
            )
        ),
        'es-ES' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "No puedo acceder al micrófono",
                self::BROWSER_DENIED_ACCESS => "La seguridad de su navegador no me permite acceder al micrófono",
                self::TRANSCRIBING => " Transcribiendo ...",
                self::UNABLE_TO_HEAR => "No puedo escucharte",
                self::ASK_AGAIN => " Dilo otra vez ....",
                self::CAN_NOT_ACCESS_MIC => "no puede acceder al micrófono",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "o escriba su consulta"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "La búsqueda por voz está fuera de servicio. Vuelve a intentarlo después de un tiempo."
            )
        ),
        'es-GT' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "No puedo acceder al micrófono",
                self::BROWSER_DENIED_ACCESS => "La seguridad de su navegador no me permite acceder al micrófono",
                self::TRANSCRIBING => " Transcribiendo ...",
                self::UNABLE_TO_HEAR => "No puedo escucharte",
                self::ASK_AGAIN => " Dilo otra vez ....",
                self::CAN_NOT_ACCESS_MIC => "no puede acceder al micrófono",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "o escriba su consulta"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "La búsqueda por voz está fuera de servicio. Vuelve a intentarlo después de un tiempo."
            )
        ),
        'es-HN' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "No puedo acceder al micrófono",
                self::BROWSER_DENIED_ACCESS => "La seguridad de su navegador no me permite acceder al micrófono",
                self::TRANSCRIBING => " Transcribiendo ...",
                self::UNABLE_TO_HEAR => "No puedo escucharte",
                self::ASK_AGAIN => " Dilo otra vez ....",
                self::CAN_NOT_ACCESS_MIC => "no puede acceder al micrófono",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "o escriba su consulta"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "La búsqueda por voz está fuera de servicio. Vuelve a intentarlo después de un tiempo."
            )
        ),
        'es-MX' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "No puedo acceder al micrófono",
                self::BROWSER_DENIED_ACCESS => "La seguridad de su navegador no me permite acceder al micrófono",
                self::TRANSCRIBING => " Transcribiendo ...",
                self::UNABLE_TO_HEAR => "No puedo escucharte",
                self::ASK_AGAIN => " Dilo otra vez ....",
                self::CAN_NOT_ACCESS_MIC => "no puede acceder al micrófono",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "o escriba su consulta"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "La búsqueda por voz está fuera de servicio. Vuelve a intentarlo después de un tiempo."
            )
        ),
        'es-NI' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "No puedo acceder al micrófono",
                self::BROWSER_DENIED_ACCESS => "La seguridad de su navegador no me permite acceder al micrófono",
                self::TRANSCRIBING => " Transcribiendo ...",
                self::UNABLE_TO_HEAR => "No puedo escucharte",
                self::ASK_AGAIN => " Dilo otra vez ....",
                self::CAN_NOT_ACCESS_MIC => "no puede acceder al micrófono",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "o escriba su consulta"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "La búsqueda por voz está fuera de servicio. Vuelve a intentarlo después de un tiempo."
            )
        ),
        'es-PA' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "No puedo acceder al micrófono",
                self::BROWSER_DENIED_ACCESS => "La seguridad de su navegador no me permite acceder al micrófono",
                self::TRANSCRIBING => " Transcribiendo ...",
                self::UNABLE_TO_HEAR => "No puedo escucharte",
                self::ASK_AGAIN => " Dilo otra vez ....",
                self::CAN_NOT_ACCESS_MIC => "no puede acceder al micrófono",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "o escriba su consulta"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "La búsqueda por voz está fuera de servicio. Vuelve a intentarlo después de un tiempo."
            )
        ),
        'es-PE' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "No puedo acceder al micrófono",
                self::BROWSER_DENIED_ACCESS => "La seguridad de su navegador no me permite acceder al micrófono",
                self::TRANSCRIBING => " Transcribiendo ...",
                self::UNABLE_TO_HEAR => "No puedo escucharte",
                self::ASK_AGAIN => " Dilo otra vez ....",
                self::CAN_NOT_ACCESS_MIC => "no puede acceder al micrófono",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "o escriba su consulta"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "La búsqueda por voz está fuera de servicio. Vuelve a intentarlo después de un tiempo."
            )
        ),
        'es-PR' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "No puedo acceder al micrófono",
                self::BROWSER_DENIED_ACCESS => "La seguridad de su navegador no me permite acceder al micrófono",
                self::TRANSCRIBING => " Transcribiendo ...",
                self::UNABLE_TO_HEAR => "No puedo escucharte",
                self::ASK_AGAIN => " Dilo otra vez ....",
                self::CAN_NOT_ACCESS_MIC => "no puede acceder al micrófono",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "o escriba su consulta"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "La búsqueda por voz está fuera de servicio. Vuelve a intentarlo después de un tiempo."
            )
        ),
        'es-PY' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "No puedo acceder al micrófono",
                self::BROWSER_DENIED_ACCESS => "La seguridad de su navegador no me permite acceder al micrófono",
                self::TRANSCRIBING => " Transcribiendo ...",
                self::UNABLE_TO_HEAR => "No puedo escucharte",
                self::ASK_AGAIN => " Dilo otra vez ....",
                self::CAN_NOT_ACCESS_MIC => "no puede acceder al micrófono",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "o escriba su consulta"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "La búsqueda por voz está fuera de servicio. Vuelve a intentarlo después de un tiempo."
            )
        ),
        'es-SV' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "No puedo acceder al micrófono",
                self::BROWSER_DENIED_ACCESS => "La seguridad de su navegador no me permite acceder al micrófono",
                self::TRANSCRIBING => " Transcribiendo ...",
                self::UNABLE_TO_HEAR => "No puedo escucharte",
                self::ASK_AGAIN => " Dilo otra vez ....",
                self::CAN_NOT_ACCESS_MIC => "no puede acceder al micrófono",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "o escriba su consulta"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "La búsqueda por voz está fuera de servicio. Vuelve a intentarlo después de un tiempo."
            )
        ),
        'es-US' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "No puedo acceder al micrófono",
                self::BROWSER_DENIED_ACCESS => "La seguridad de su navegador no me permite acceder al micrófono",
                self::TRANSCRIBING => " Transcribiendo ...",
                self::UNABLE_TO_HEAR => "No puedo escucharte",
                self::ASK_AGAIN => " Dilo otra vez ....",
                self::CAN_NOT_ACCESS_MIC => "no puede acceder al micrófono",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "o escriba su consulta"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "La búsqueda por voz está fuera de servicio. Vuelve a intentarlo después de un tiempo."
            )
        ),
        'es-UY' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "No puedo acceder al micrófono",
                self::BROWSER_DENIED_ACCESS => "La seguridad de su navegador no me permite acceder al micrófono",
                self::TRANSCRIBING => " Transcribiendo ...",
                self::UNABLE_TO_HEAR => "No puedo escucharte",
                self::ASK_AGAIN => " Dilo otra vez ....",
                self::CAN_NOT_ACCESS_MIC => "no puede acceder al micrófono",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "o escriba su consulta"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "La búsqueda por voz está fuera de servicio. Vuelve a intentarlo después de un tiempo."
            )
        ),
        'es-VE' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "No puedo acceder al micrófono",
                self::BROWSER_DENIED_ACCESS => "La seguridad de su navegador no me permite acceder al micrófono",
                self::TRANSCRIBING => " Transcribiendo ...",
                self::UNABLE_TO_HEAR => "No puedo escucharte",
                self::ASK_AGAIN => " Dilo otra vez ....",
                self::CAN_NOT_ACCESS_MIC => "no puede acceder al micrófono",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "o escriba su consulta"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "La búsqueda por voz está fuera de servicio. Vuelve a intentarlo después de un tiempo."
            )
        ),
        'fr-CA' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "Je n'arrive pas à accéder au microphone",
                self::BROWSER_DENIED_ACCESS => "La sécurité de votre navigateur ne me permet pas d'accéder au micro",
                self::TRANSCRIBING => " Transcrire ....",
                self::UNABLE_TO_HEAR => "Je ne peux pas t'entendre",
                self::ASK_AGAIN => " Dis le encore ....",
                self::CAN_NOT_ACCESS_MIC => "ne peut pas accéder au microphone",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "ou saisissez votre requête"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "La recherche vocale est hors service, veuillez réessayer après un certain temps"
            )
        ),
        'fr-FR' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "Je n'arrive pas à accéder au microphone",
                self::BROWSER_DENIED_ACCESS => "La sécurité de votre navigateur ne me permet pas d'accéder au micro",
                self::TRANSCRIBING => " Transcrire ....",
                self::UNABLE_TO_HEAR => "Je ne peux pas t'entendre",
                self::ASK_AGAIN => " Dis le encore ....",
                self::CAN_NOT_ACCESS_MIC => "ne peut pas accéder au microphone",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "ou saisissez votre requête"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "La recherche vocale est hors service, veuillez réessayer après un certain temps"
            )
        ),
        'it-IT' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "Non riesco ad accedere al microfono",
                self::BROWSER_DENIED_ACCESS => "La sicurezza del tuo browser non mi consente di accedere al microfono",
                self::TRANSCRIBING => " Trascrizione ....",
                self::UNABLE_TO_HEAR => "Non riesco a sentirti",
                self::ASK_AGAIN => " Dillo di nuovo ....",
                self::CAN_NOT_ACCESS_MIC => "impossibile accedere al microfono",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "o digita la tua query"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "La ricerca vocale è fuori servizio. Riprova tra qualche istante"
            )
        ),
        'ja-JP' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "マイクにアクセスできません",
                self::BROWSER_DENIED_ACCESS => "ブラウザのセキュリティにより、マイクにアクセスできません",
                self::TRANSCRIBING => " 文字起こし....",
                self::UNABLE_TO_HEAR => "聞こえません",
                self::ASK_AGAIN => " もう一度言ってください ....",
                self::CAN_NOT_ACCESS_MIC => "マイクにアクセスできない",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "またはクエリを入力します"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "音声検索は利用できません。しばらくしてからもう一度お試しください"
            )
        ),
        'ko-KR' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "마이크에 액세스 할 수 없습니다",
                self::BROWSER_DENIED_ACCESS => "브라우저 보안으로 마이크에 액세스 할 수 없습니다",
                self::TRANSCRIBING => " 전사 ....",
                self::UNABLE_TO_HEAR => "나는 당신을들을 수 없습니다",
                self::ASK_AGAIN => " 다시 말해봐 ....",
                self::CAN_NOT_ACCESS_MIC => "마이크에 액세스 할 수 없습니다",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "또는 검색어를 입력하십시오"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "음성 검색이 작동하지 않습니다. 잠시 후 다시 시도하십시오."
            )
        ),
        'nl-NL' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "Ik krijg geen toegang tot de microfoon",
                self::BROWSER_DENIED_ACCESS => "Door uw browserbeveiliging heb ik geen toegang tot de microfoon",
                self::TRANSCRIBING => " Transcriberen ...",
                self::UNABLE_TO_HEAR => "Ik kan je niet horen",
                self::ASK_AGAIN => " Zeg het nogmaals ....",
                self::CAN_NOT_ACCESS_MIC => "heeft geen toegang tot de microfoon",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "of typ uw vraag"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Spraakgestuurd zoeken is buiten dienst. Probeer het later opnieuw"
            )
        ),
        'nl-BE' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "Ik krijg geen toegang tot de microfoon",
                self::BROWSER_DENIED_ACCESS => "Door uw browserbeveiliging heb ik geen toegang tot de microfoon",
                self::TRANSCRIBING => " Transcriberen ...",
                self::UNABLE_TO_HEAR => "Ik kan je niet horen",
                self::ASK_AGAIN => " Zeg het nogmaals ....",
                self::CAN_NOT_ACCESS_MIC => "heeft geen toegang tot de microfoon",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "of typ uw vraag"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Spraakgestuurd zoeken is buiten dienst. Probeer het later opnieuw"
            )
        ),
        'pl-PL' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "Nie mogę uzyskać dostępu do mikrofonu",
                self::BROWSER_DENIED_ACCESS => "Bezpieczeństwo twojej przeglądarki nie pozwala mi na dostęp do mikrofonu",
                self::TRANSCRIBING => " Przepisywanie ...",
                self::UNABLE_TO_HEAR => "Nie słyszę cię",
                self::ASK_AGAIN => " Powiedz to jeszcze raz ....",
                self::CAN_NOT_ACCESS_MIC => "nie ma dostępu do mikrofonu",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "lub wpisz zapytanie"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Wyszukiwanie głosowe nie działa. Spróbuj ponownie po pewnym czasie"
            )
        ),
        'pt-BR' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "Não consigo acessar o microfone",
                self::BROWSER_DENIED_ACCESS => "A segurança do seu navegador não me permite acessar o microfone",
                self::TRANSCRIBING => " Transcrevendo ....",
                self::UNABLE_TO_HEAR => "Não consigo te ouvir",
                self::ASK_AGAIN => " Diga isso de novo ....",
                self::CAN_NOT_ACCESS_MIC => "não pode acessar o microfone",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "ou digite sua consulta"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "A pesquisa por voz está fora de serviço. Tente novamente após algum tempo"
            )
        ),
        'ru-RU' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "Я не могу получить доступ к микрофону",
                self::BROWSER_DENIED_ACCESS => "Безопасность вашего браузера не позволяет мне получить доступ к микрофону",
                self::TRANSCRIBING => " Транскрибировать ....",
                self::UNABLE_TO_HEAR => "Я не слышу тебя",
                self::ASK_AGAIN => " Скажи это снова ....",
                self::CAN_NOT_ACCESS_MIC => "не может получить доступ к микрофону",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "или введите свой запрос"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Голосовой поиск не работает. Повторите попытку через некоторое время."
            )
        ),
        'th-TH' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "ฉันไม่สามารถเข้าถึงไมโครโฟน",
                self::BROWSER_DENIED_ACCESS => "ความปลอดภัยเบราว์เซอร์ของคุณไม่อนุญาตให้ฉันเข้าถึงไมโครโฟน",
                self::TRANSCRIBING => " กำลังถอดความ ....",
                self::UNABLE_TO_HEAR => "ฉันไม่ได้ยินคุณ",
                self::ASK_AGAIN => " พูดอีกครั้ง ....",
                self::CAN_NOT_ACCESS_MIC => "ไม่สามารถเข้าถึงไมโครโฟน",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "หรือพิมพ์ข้อความค้นหาของคุณ"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "การค้นหาด้วยเสียงไม่พร้อมให้บริการโปรดลองอีกครั้งในภายหลัง"
            )
        ),
        'tr-TR' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "Mikrofona erişemiyorum",
                self::BROWSER_DENIED_ACCESS => "Tarayıcı güvenliğiniz mikrofona erişmeme izin vermiyor",
                self::TRANSCRIBING => " Kopyalama ....",
                self::UNABLE_TO_HEAR => "Seni duyamıyorum",
                self::ASK_AGAIN => " Tekrar söyle ....",
                self::CAN_NOT_ACCESS_MIC => "mikrofona erişilemiyor",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "veya sorgunuzu yazın"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Sesli arama hizmet dışı, Lütfen bir süre sonra tekrar deneyin"
            )
        ),
        'zh' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "我无法使用麦克风",
                self::BROWSER_DENIED_ACCESS => "您的浏览器安全性不允许我访问麦克风",
                self::TRANSCRIBING => " 抄写....",
                self::UNABLE_TO_HEAR => "我听不到你的声音",
                self::ASK_AGAIN => " 再说一遍 ....",
                self::CAN_NOT_ACCESS_MIC => "无法访问麦克风",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "或输入查询"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "语音搜索已停止服务，请稍后再试"
            )
        ),
        'zh-HK' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "我无法使用麦克风",
                self::BROWSER_DENIED_ACCESS => "您的浏览器安全性不允许我访问麦克风",
                self::TRANSCRIBING => " 抄写....",
                self::UNABLE_TO_HEAR => "我听不到你的声音",
                self::ASK_AGAIN => " 再说一遍 ....",
                self::CAN_NOT_ACCESS_MIC => "无法访问麦克风",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "或输入查询"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "语音搜索已停止服务，请稍后再试"
            )
        ),
        'Zh-TW' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "我无法使用麦克风",
                self::BROWSER_DENIED_ACCESS => "您的浏览器安全性不允许我访问麦克风",
                self::TRANSCRIBING => " 抄写....",
                self::UNABLE_TO_HEAR => "我听不到你的声音",
                self::ASK_AGAIN => " 再说一遍 ....",
                self::CAN_NOT_ACCESS_MIC => "无法访问麦克风",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "或输入查询"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "语音搜索已停止服务，请稍后再试"
            )
        ),
        'bg-BG' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "Не мога да осъществя достъп до микрофона",
                self::BROWSER_DENIED_ACCESS => "Защитата на вашия браузър не ми позволява да влизам в микрофона",
                self::TRANSCRIBING => " Преписване ....",
                self::UNABLE_TO_HEAR => "Не мога да те чуя",
                self::ASK_AGAIN => " Кажи го пак ....",
                self::CAN_NOT_ACCESS_MIC => "не може да получи достъп до микрофона",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "или въведете заявката си"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Гласовото търсене не е в употреба. Моля, опитайте отново след известно време"
            )
        ),
        'ca-ES' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "No puc accedir al micròfon",
                self::BROWSER_DENIED_ACCESS => "La seguretat del navegador no em permet accedir al mic",
                self::TRANSCRIBING => " Transcripció ...",
                self::UNABLE_TO_HEAR => "No sóc capaç de sentir-te",
                self::ASK_AGAIN => " Dígues-ho de nou ....",
                self::CAN_NOT_ACCESS_MIC => "no pot accedir al micròfon",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "o escriviu la vostra consulta"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "La cerca per veu està fora de servei. Torneu-ho a provar després d'un temps"
            )
        ),
        'cs-CZ' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "Nemohu získat přístup k mikrofonu",
                self::BROWSER_DENIED_ACCESS => "Zabezpečení vašeho prohlížeče mi neumožňuje přístup k mikrofonu",
                self::TRANSCRIBING => " Přepisování ....",
                self::UNABLE_TO_HEAR => "Neslyším tě",
                self::ASK_AGAIN => " Řekni to znovu ....",
                self::CAN_NOT_ACCESS_MIC => "nelze přistupovat k mikrofonu",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "nebo zadejte svůj dotaz"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Hlasové vyhledávání je mimo provoz, zkuste to prosím znovu po nějaké době"
            )
        ),
        'da-DK' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "Jeg kan ikke få adgang til mikrofonen",
                self::BROWSER_DENIED_ACCESS => "Din browsersikkerhed tillader ikke mig adgang til mikrofonen",
                self::TRANSCRIBING => " Transkriberer ....",
                self::UNABLE_TO_HEAR => "Jeg kan ikke høre dig",
                self::ASK_AGAIN => " Sig det igen ....",
                self::CAN_NOT_ACCESS_MIC => "har ikke adgang til mikrofonen",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "eller skriv din forespørgsel"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Stemmesøgning er ude af drift. Prøv igen efter et stykke tid"
            )
        ),
        'el-GR' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "Δεν έχω πρόσβαση στο μικρόφωνο",
                self::BROWSER_DENIED_ACCESS => "Η ασφάλεια του προγράμματος περιήγησής μου δεν μου επιτρέπει την πρόσβαση στο μικρόφωνο",
                self::TRANSCRIBING => " Μεταγραφή ....",
                self::UNABLE_TO_HEAR => "Δεν μπορώ να σε ακούσω",
                self::ASK_AGAIN => " Πες το ξανά ....",
                self::CAN_NOT_ACCESS_MIC => "δεν είναι δυνατή η πρόσβαση στο μικρόφωνο",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "ή πληκτρολογήστε το ερώτημά σας"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Η φωνητική αναζήτηση δεν λειτουργεί, Δοκιμάστε ξανά μετά από λίγο"
            )
        ),
        'fi-FI' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "En pääse mikrofoniin",
                self::BROWSER_DENIED_ACCESS => "Selaimesi suojaus ei salli minun käyttää mikrofonia",
                self::TRANSCRIBING => " Transkribointi ....",
                self::UNABLE_TO_HEAR => "En kuule sinua",
                self::ASK_AGAIN => " Sano se uudelleen ....",
                self::CAN_NOT_ACCESS_MIC => "ei pääse mikrofoniin",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "tai kirjoita kyselysi"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Äänihaku on poissa käytöstä. Yritä uudelleen jonkin ajan kuluttua"
            )
        ),
        'he-IL' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "איני מצליח לגשת למיקרופון",
                self::BROWSER_DENIED_ACCESS => "אבטחת הדפדפן שלך לא מאפשרת לי לגשת למיקרופון",
                self::TRANSCRIBING => " מתמלל….",
                self::UNABLE_TO_HEAR => "אני לא מסוגל לשמוע אותך",
                self::ASK_AGAIN => " חזור שנית ....",
                self::CAN_NOT_ACCESS_MIC => "לא יכול לגשת למיקרופון",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "או הקלד את השאילתה שלך"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "החיפוש הקולי אינו פועל, אנא נסה שוב לאחר זמן מה"
            )
        ),
        'hi-IN' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "मैं माइक्रोफ़ोन तक पहुंचने में असमर्थ हूं",
                self::BROWSER_DENIED_ACCESS => "आपकी ब्राउज़र सुरक्षा मुझे माइक का उपयोग करने की अनुमति नहीं देती है",
                self::TRANSCRIBING => " प्रतिलेखन…।",
                self::UNABLE_TO_HEAR => "मैं आपको सुनने में असमर्थ हूं",
                self::ASK_AGAIN => " इसे फिर से कहना ....",
                self::CAN_NOT_ACCESS_MIC => "माइक्रोफ़ोन तक नहीं पहुँच सकते",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "या अपनी क्वेरी टाइप करें"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "ध्वनि खोज सेवा से बाहर है, कृपया कुछ समय बाद पुन: प्रयास करें"
            )
        ),
        'hr-HR' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "Ne mogu pristupiti mikrofonu",
                self::BROWSER_DENIED_ACCESS => "Sigurnost vašeg preglednika ne dopušta mi pristup mikrofonu",
                self::TRANSCRIBING => " Prepisivanje….",
                self::UNABLE_TO_HEAR => "Ne mogu te čuti",
                self::ASK_AGAIN => " Reci to ponovno ....",
                self::CAN_NOT_ACCESS_MIC => "ne može pristupiti mikrofonu",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "ili upišite svoj upit"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Glasovna pretraga nije u funkciji. Pokušajte ponovno nakon nekog vremena"
            )
        ),
        'hu-HU' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "Nem tudom elérni a mikrofont",
                self::BROWSER_DENIED_ACCESS => "Az Ön böngészőjének biztonsága nem teszi lehetővé a mikrofon elérését",
                self::TRANSCRIBING => " Átírás ...",
                self::UNABLE_TO_HEAR => "Nem hallom",
                self::ASK_AGAIN => " Mondd újra ....",
                self::CAN_NOT_ACCESS_MIC => "nem fér hozzá a mikrofonhoz",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "vagy írja be a lekérdezést"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "A hangkeresés nem működik. Kérjük, próbálkozzon újra egy idő után"
            )
        ),
        'id-ID' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "Saya tidak dapat mengakses mikrofon",
                self::BROWSER_DENIED_ACCESS => "Keamanan browser Anda tidak memungkinkan saya untuk mengakses mic",
                self::TRANSCRIBING => " Transkrip ....",
                self::UNABLE_TO_HEAR => "Aku tidak bisa mendengarmu",
                self::ASK_AGAIN => " Katakan lagi ....",
                self::CAN_NOT_ACCESS_MIC => "tidak dapat mengakses mikrofon",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "atau ketikkan kueri Anda"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Pencarian suara tidak berfungsi, Harap coba lagi setelah beberapa waktu"
            )
        ),
        'lt-LT' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "Aš negaliu pasiekti mikrofono",
                self::BROWSER_DENIED_ACCESS => "Jūsų naršyklės sauga neleidžia man pasiekti mikrofono",
                self::TRANSCRIBING => " Transkribavimas ...",
                self::UNABLE_TO_HEAR => "Aš negaliu tavęs išgirsti",
                self::ASK_AGAIN => " Pakartok ....",
                self::CAN_NOT_ACCESS_MIC => "negali pasiekti mikrofono",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "arba įveskite savo užklausą"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Paieška balsu neveikia. Bandykite dar kartą po kurio laiko"
            )
        ),
        'lv-LV' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "Es nevaru piekļūt mikrofonam",
                self::BROWSER_DENIED_ACCESS => "Jūsu pārlūka drošība neļauj man piekļūt mikrofonam",
                self::TRANSCRIBING => " Notiek pārrakstīšana ...",
                self::UNABLE_TO_HEAR => "Es nespēju tevi dzirdēt",
                self::ASK_AGAIN => " Pasaki to vēlreiz ....",
                self::CAN_NOT_ACCESS_MIC => "nevar piekļūt mikrofonam",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "vai ierakstiet vaicājumu"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Meklēšana ar balsi nedarbojas. Lūdzu, pēc kāda laika mēģiniet vēlreiz"
            )
        ),
        'nb-NO' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "Jeg har ikke tilgang til mikrofonen",
                self::BROWSER_DENIED_ACCESS => "Nettleserens sikkerhet tillater meg ikke tilgang til mikrofonen",
                self::TRANSCRIBING => " Transkriberer ....",
                self::UNABLE_TO_HEAR => "Jeg klarer ikke å høre deg",
                self::ASK_AGAIN => " Si det igjen ....",
                self::CAN_NOT_ACCESS_MIC => "har ikke tilgang til mikrofonen",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "eller skriv inn spørringen"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Talesøket er ikke i bruk. Prøv igjen etter litt tid"
            )
        ),
        'pt-PT' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "Não consigo acessar o microfone",
                self::BROWSER_DENIED_ACCESS => "A segurança do seu navegador não me permite acessar o microfone",
                self::TRANSCRIBING => " Transcrevendo ....",
                self::UNABLE_TO_HEAR => "Não consigo te ouvir",
                self::ASK_AGAIN => " Diga isso de novo ....",
                self::CAN_NOT_ACCESS_MIC => "não pode acessar o microfone",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "ou digite sua consulta"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "A pesquisa por voz está fora de serviço. Tente novamente após algum tempo"
            )
        ),
        'ro-RO' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "Nu pot accesa microfonul",
                self::BROWSER_DENIED_ACCESS => "Securitatea browserului meu nu îmi permite să accesez microfonul",
                self::TRANSCRIBING => " Transcriere ...",
                self::UNABLE_TO_HEAR => "Nu sunt în stare să te aud",
                self::ASK_AGAIN => " Spune-o din nou ....",
                self::CAN_NOT_ACCESS_MIC => "nu poate accesa microfonul",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "sau introduceți întrebarea"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Căutarea vocală este în afara serviciului. Vă rugăm să încercați din nou după ceva timp"
            )
        ),
        'sk-SK' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "Nemôžem získať prístup k mikrofónu",
                self::BROWSER_DENIED_ACCESS => "Zabezpečenie vášho prehliadača mi neumožňuje prístup k mikrofónu",
                self::TRANSCRIBING => " Prepisovanie ....",
                self::UNABLE_TO_HEAR => "Nemôžem ťa počuť",
                self::ASK_AGAIN => " Povedz to ešte raz ....",
                self::CAN_NOT_ACCESS_MIC => "nemá prístup k mikrofónu",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "alebo zadajte svoj dotaz"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Hlasové vyhľadávanie je mimo prevádzky. Skúste to znova po nejakom čase"
            )
        ),
        'sl-SI' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "Do mikrofona ne morem dostopati",
                self::BROWSER_DENIED_ACCESS => "Varnost vašega brskalnika mi ne omogoča dostopa do mikrofona",
                self::TRANSCRIBING => " Prepisovanje….",
                self::UNABLE_TO_HEAR => "Ne morem te slišati",
                self::ASK_AGAIN => " Povej še enkrat ....",
                self::CAN_NOT_ACCESS_MIC => "ne morejo dostopati do mikrofona",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "ali vnesite poizvedbo"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Glasovno iskanje ni v uporabi. Poskusite znova čez nekaj časa"
            )
        ),
        'sr-RS' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "Не могу приступити микрофону",
                self::BROWSER_DENIED_ACCESS => "Сигурност вашег прегледача не дозвољава ми приступ микрофону",
                self::TRANSCRIBING => " Трансцрибинг ....",
                self::UNABLE_TO_HEAR => "Не могу те чути",
                self::ASK_AGAIN => " Реци још једном ....",
                self::CAN_NOT_ACCESS_MIC => "не може да приступи микрофону",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "или упишите упит"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Гласовна претрага није у функцији. Покушајте поново након неког времена"
            )
        ),
        'sv-SE' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "Jag har inte tillgång till mikrofonen",
                self::BROWSER_DENIED_ACCESS => "Din webbläsarsäkerhet tillåter mig inte åtkomst till mikrofonen",
                self::TRANSCRIBING => " Transkriberar ....",
                self::UNABLE_TO_HEAR => "Jag kan inte höra dig",
                self::ASK_AGAIN => " Säg det igen ....",
                self::CAN_NOT_ACCESS_MIC => "har inte åtkomst till mikrofonen",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "eller skriv din fråga"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Röstsökning är ur funktion. Försök igen efter en tid"
            )
        ),
        'uk-UA' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "Я не в змозі отримати доступ до мікрофона",
                self::BROWSER_DENIED_ACCESS => "Захист вашого браузера не дозволяє мені отримати доступ до мікрофона",
                self::TRANSCRIBING => " Переписування….",
                self::UNABLE_TO_HEAR => "Я не в змозі вас почути",
                self::ASK_AGAIN => " Повтори ....",
                self::CAN_NOT_ACCESS_MIC => "не може отримати доступ до мікрофона",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "або введіть запит"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Голосовий пошук не працює, повторіть спробу через деякий час"
            )
        ),
        'vi-VN' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "Tôi không thể truy cập micro",
                self::BROWSER_DENIED_ACCESS => "Bảo mật trình duyệt của bạn không cho phép tôi truy cập mic",
                self::TRANSCRIBING => " Phiên âm ....",
                self::UNABLE_TO_HEAR => "Tôi không thể nghe thấy bạn",
                self::ASK_AGAIN => " Nói lại lần nữa ....",
                self::CAN_NOT_ACCESS_MIC => "không thể truy cập micro",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "hoặc nhập truy vấn của bạn"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Tìm kiếm bằng giọng nói đã hết dịch vụ, Vui lòng thử lại sau một thời gian"
            )
        ),
        'af-ZA' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "Ek het nie toegang tot die mikrofoon nie",
                self::BROWSER_DENIED_ACCESS => "U blaaiersekuriteit laat my nie toegang tot die mikrofoon verkry nie",
                self::TRANSCRIBING => " Transkribeer ....",
                self::UNABLE_TO_HEAR => "Ek kan jou nie hoor nie",
                self::ASK_AGAIN => " Sê dit weer ....",
                self::CAN_NOT_ACCESS_MIC => "kan nie toegang tot die mikrofoon kry nie",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "of tik u navraag in"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Stemsoektog is buite werking. Probeer dit weer na 'n geruime tyd"
            )
        ),
        'am-ET' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "ማይክሮፎኑን መድረስ አልቻልኩም",
                self::BROWSER_DENIED_ACCESS => "የአሳሽዎ ደህንነት ማይክሮፎኑን እንድደርስ አይፈቅድልኝም",
                self::TRANSCRIBING => " በመገልበጥ ላይ ....",
                self::UNABLE_TO_HEAR => "ልሰማህ አልችልም",
                self::ASK_AGAIN => " እንደገና ይናገሩ….",
                self::CAN_NOT_ACCESS_MIC => "ማይክሮፎኑን መድረስ አልተቻለም",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "ወይም ጥያቄዎን ይተይቡ"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "የድምፅ ፍለጋ ከአገልግሎት ውጭ ነው ፣ እባክዎ ከተወሰነ ጊዜ በኋላ እንደገና ይሞክሩ"
            )
        ),
        'az-AZ' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "Mikrofona girə bilmirəm",
                self::BROWSER_DENIED_ACCESS => "Brauzerinizin təhlükəsizliyi mikrofona girməyimə imkan vermir",
                self::TRANSCRIBING => " Yazı ...",
                self::UNABLE_TO_HEAR => "Səni eşitməyə qadir deyiləm",
                self::ASK_AGAIN => " Bir də de ....",
                self::CAN_NOT_ACCESS_MIC => "mikrofona daxil ola bilmir",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "ya da sorğunuzu yazın"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Səs axtarışı işləmir, bir müddətdən sonra yenidən cəhd edin"
            )
        ),
        'bn-BD' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "আমি মাইক্রোফোন অ্যাক্সেস করতে অক্ষম",
                self::BROWSER_DENIED_ACCESS => "আপনার ব্রাউজার সুরক্ষা আমাকে মাইক অ্যাক্সেস করতে দেয় না",
                self::TRANSCRIBING => " প্রতিলিপি ...",
                self::UNABLE_TO_HEAR => "আমি তোমাকে শুনতে অক্ষম",
                self::ASK_AGAIN => " আবার বল ....",
                self::CAN_NOT_ACCESS_MIC => "মাইক্রোফোন অ্যাক্সেস করতে পারবেন না",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "অথবা আপনার ক্যোয়ারী টাইপ করুন"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "ভয়েস অনুসন্ধানের কাজ শেষ নয়, দয়া করে কিছুক্ষণ পরে আবার চেষ্টা করুন"
            )
        ),
        'bn-IN' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "আমি মাইক্রোফোন অ্যাক্সেস করতে অক্ষম",
                self::BROWSER_DENIED_ACCESS => "আপনার ব্রাউজার সুরক্ষা আমাকে মাইক অ্যাক্সেস করতে দেয় না",
                self::TRANSCRIBING => " প্রতিলিপি ...",
                self::UNABLE_TO_HEAR => "আমি তোমাকে শুনতে অক্ষম",
                self::ASK_AGAIN => " আবার বল ....",
                self::CAN_NOT_ACCESS_MIC => "মাইক্রোফোন অ্যাক্সেস করতে পারবেন না",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "অথবা আপনার ক্যোয়ারী টাইপ করুন"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "ভয়েস অনুসন্ধানের কাজ শেষ নয়, দয়া করে কিছুক্ষণ পরে আবার চেষ্টা করুন"
            )
        ),
        'et-EE' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "Ma ei pääse mikrofonile juurde",
                self::BROWSER_DENIED_ACCESS => "Teie brauseri turvalisus ei võimalda mul mikrofoni juurde pääseda",
                self::TRANSCRIBING => " Transkribeerimine ...",
                self::UNABLE_TO_HEAR => "Ma ei kuule sind",
                self::ASK_AGAIN => " Ütle uuesti ...",
                self::CAN_NOT_ACCESS_MIC => "ei pääse mikrofonile juurde",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "või tippige oma päring"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Häälotsing ei tööta. Proovige mõne aja pärast uuesti"
            )
        ),
        'eu-ES' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "Ezin dut mikrofonoa sartu",
                self::BROWSER_DENIED_ACCESS => "Zure arakatzailearen segurtasunak ez dit mikrofonoa sartzeko aukera ematen",
                self::TRANSCRIBING => " Transkribapena ....",
                self::UNABLE_TO_HEAR => "Ezin dut zu entzun",
                self::ASK_AGAIN => " Esan berriro ....",
                self::CAN_NOT_ACCESS_MIC => "ezin da mikrofonoan sartu",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "edo idatzi zure kontsulta"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Ahots bidezko bilaketa kanpoan dago. Saiatu berriro denbora pixka bat igaro ondoren"
            )
        ),
        'fa-IR' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "من قادر به دسترسی به میکروفون نیستم",
                self::BROWSER_DENIED_ACCESS => "امنیت مرورگر شما به من امکان دسترسی به میکروفون را نمی دهد",
                self::TRANSCRIBING => " رونویسی ...",
                self::UNABLE_TO_HEAR => "من قادر به شنیدن شما نیستم",
                self::ASK_AGAIN => " دوباره بگو ....",
                self::CAN_NOT_ACCESS_MIC => "نمی توانید به میکروفون دسترسی پیدا کنید",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "یا درخواست خود را تایپ کنید"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "جستجوی صوتی غیرفعال است ، لطفاً پس از مدتی دوباره امتحان کنید"
            )
        ),
        'fil-PH' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "Hindi ko ma-access ang mikropono",
                self::BROWSER_DENIED_ACCESS => "Hindi pinapayagan ako ng iyong browser ng seguridad na ma-access ang mic",
                self::TRANSCRIBING => " Nagsusulat….",
                self::UNABLE_TO_HEAR => "Hindi kita makarinig",
                self::ASK_AGAIN => " Sabihin mo ulit….",
                self::CAN_NOT_ACCESS_MIC => "hindi ma-access ang mikropono",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "o i-type ang iyong query"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Ang paghahanap ng boses ay wala sa serbisyo, Mangyaring subukang muli pagkatapos ng ilang oras"
            )
        ),
        'gl-ES' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "Non podo acceder ao micrófono",
                self::BROWSER_DENIED_ACCESS => "A seguridade do teu navegador non me permite acceder ao micrófono",
                self::TRANSCRIBING => " Transcrición ...",
                self::UNABLE_TO_HEAR => "Eu son incapaz de escoitalo",
                self::ASK_AGAIN => " Dicalo de novo ....",
                self::CAN_NOT_ACCESS_MIC => "non pode acceder ao micrófono",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "ou escriba a consulta"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "A busca por voz está fóra de servizo. Por favor, téntao de novo despois"
            )
        ),
        'gu-IN' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "હું માઇક્રોફોનને toક્સેસ કરવામાં અક્ષમ છું",
                self::BROWSER_DENIED_ACCESS => "તમારી બ્રાઉઝર સુરક્ષા મને માઇકને toક્સેસ કરવાની મંજૂરી આપતી નથી",
                self::TRANSCRIBING => " લખાણ લખી રહ્યું છે ....",
                self::UNABLE_TO_HEAR => "હું તમને સાંભળવામાં અસમર્થ છું",
                self::ASK_AGAIN => " ફરી કહો ....",
                self::CAN_NOT_ACCESS_MIC => "માઇક્રોફોનને .ક્સેસ કરી શકતા નથી",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "અથવા તમારી ક્વેરી લખો"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "અવાજની શોધ સેવાની બહાર છે, કૃપા કરીને થોડા સમય પછી ફરી પ્રયાસ કરો"
            )
        ),
        'hy-AM' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "Ես ի վիճակի չեմ մուտք ունենալ խոսափող",
                self::BROWSER_DENIED_ACCESS => "Ձեր զննարկչի անվտանգությունն ինձ թույլ չի տալիս մուտք գործել միկրոֆիլ",
                self::TRANSCRIBING => " Արտագրելով…",
                self::UNABLE_TO_HEAR => "Ես չեմ կարողանում լսել քեզ",
                self::ASK_AGAIN => " Նորից ասեք….",
                self::CAN_NOT_ACCESS_MIC => "չի կարող մուտք գործել խոսափողը",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "կամ մուտքագրեք ձեր հարցումը"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Ձայնի որոնումը ծառայությունից դուրս է: Խնդրում ենք փորձել որոշ ժամանակ անց"
            )
        ),
        'is-IS' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "Ég hef ekki aðgang að hljóðnemanum",
                self::BROWSER_DENIED_ACCESS => "Öryggi vafrans þíns leyfir mér ekki aðgang að hljóðnemanum",
                self::TRANSCRIBING => " Transcribing ....",
                self::UNABLE_TO_HEAR => "Ég heyri ekki í þér",
                self::ASK_AGAIN => " Segðu það aftur ....",
                self::CAN_NOT_ACCESS_MIC => "hefur ekki aðgang að hljóðnemanum",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "eða sláðu inn fyrirspurn þína"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Raddleit er ekki í notkun. Vinsamlegast reyndu aftur eftir nokkurn tíma"
            )
        ),
        'jv-ID' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "Aku ora bisa ngakses mikropon",
                self::BROWSER_DENIED_ACCESS => "Keamanan browser sampeyan ora ngidini aku ngakses mic",
                self::TRANSCRIBING => " Nularake….",
                self::UNABLE_TO_HEAR => "Aku ora bisa ngrungokake sampeyan",
                self::ASK_AGAIN => " Dakkandhani maneh….",
                self::CAN_NOT_ACCESS_MIC => "ora bisa ngakses mikropon",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "utawa ketik query sampeyan"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Panelusuran swara ora ana ing layanan, Coba nyoba maneh sawetara wektu"
            )
        ),
        'ka-GE' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "მიკროფონის წვდომას ვერ ვახერხებ",
                self::BROWSER_DENIED_ACCESS => "თქვენი ბრაუზერის უსაფრთხოება მიკროფონზე წვდომის საშუალებას არ მაძლევს",
                self::TRANSCRIBING => " გადაწერა….",
                self::UNABLE_TO_HEAR => "მე ვერ გამიგია",
                self::ASK_AGAIN => " Გაიმეორეთ ....",
                self::CAN_NOT_ACCESS_MIC => "მიკროფონზე წვდომა შეუძლებელია",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "ან ჩაწერეთ თქვენი შეკითხვა"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "ხმოვანი ძებნა არ არის გამოსული, გთხოვთ, სცადოთ გარკვეული დროის შემდეგ"
            )
        ),
        'km-KH' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "ខ្ញុំមិនអាចចូលប្រើមីក្រូហ្វូនបានទេ",
                self::BROWSER_DENIED_ACCESS => "សុវត្ថិភាពកម្មវិធីអ៊ីនធឺណិតរបស់អ្នកមិនអនុញ្ញាតឱ្យខ្ញុំចូលប្រើមីក្រូហ្វូនទេ",
                self::TRANSCRIBING => " កំពុងចម្លង ...",
                self::UNABLE_TO_HEAR => "ខ្ញុំមិនអាចស្តាប់អ្នកបានទេ",
                self::ASK_AGAIN => " និយាយ​វា​ម្តង​ទៀត ....",
                self::CAN_NOT_ACCESS_MIC => "មិនអាចចូលប្រើមីក្រូហ្វូនបានទេ",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "ឬវាយសំណួររបស់អ្នក"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "ការស្វែងរកដោយសម្លេងអស់សេវាកម្មសូមព្យាយាមម្តងទៀតនៅពេលក្រោយ"
            )
        ),
        'kn-IN' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "ಮೈಕ್ರೊಫೋನ್ ಪ್ರವೇಶಿಸಲು ನನಗೆ ಸಾಧ್ಯವಾಗುತ್ತಿಲ್ಲ",
                self::BROWSER_DENIED_ACCESS => "ನಿಮ್ಮ ಬ್ರೌಸರ್ ಸುರಕ್ಷತೆಯು ಮೈಕ್ ಪ್ರವೇಶಿಸಲು ನನಗೆ ಅನುಮತಿಸುವುದಿಲ್ಲ",
                self::TRANSCRIBING => " ಲಿಪ್ಯಂತರ ....",
                self::UNABLE_TO_HEAR => "ನಾನು ನಿಮ್ಮ ಮಾತನ್ನು ಕೇಳಲು ಸಾಧ್ಯವಾಗುತ್ತಿಲ್ಲ",
                self::ASK_AGAIN => " ಇನ್ನೊಮ್ಮೆ ಹೇಳಿ ....",
                self::CAN_NOT_ACCESS_MIC => "ಮೈಕ್ರೊಫೋನ್ ಪ್ರವೇಶಿಸಲು ಸಾಧ್ಯವಿಲ್ಲ",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "ಅಥವಾ ನಿಮ್ಮ ಪ್ರಶ್ನೆಯನ್ನು ಟೈಪ್ ಮಾಡಿ"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "ಧ್ವನಿ ಹುಡುಕಾಟವು ಸೇವೆಯಿಂದ ಹೊರಗಿದೆ, ದಯವಿಟ್ಟು ಸ್ವಲ್ಪ ಸಮಯದ ನಂತರ ಮತ್ತೆ ಪ್ರಯತ್ನಿಸಿ"
            )
        ),
        'lo-LA' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "ຂ້ອຍບໍ່ສາມາດເຂົ້າເຖິງໄມໂຄຣໂຟນໄດ້",
                self::BROWSER_DENIED_ACCESS => "ຄວາມປອດໄພຂອງຕົວທ່ອງເວັບຂອງທ່ານບໍ່ອະນຸຍາດໃຫ້ຂ້ອຍເຂົ້າເຖິງ mic",
                self::TRANSCRIBING => " ກຳ ລັງສົ່ງຕໍ່ ....",
                self::UNABLE_TO_HEAR => "ຂ້ອຍບໍ່ສາມາດໄດ້ຍິນເຈົ້າ",
                self::ASK_AGAIN => " ເວົ້າອີກວ່າ….",
                self::CAN_NOT_ACCESS_MIC => "ບໍ່ສາມາດເຂົ້າເຖິງໄມໂຄໂຟນໄດ້",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "ຫຼືພິມ ຄຳ ຖາມຂອງທ່ານ"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "ການຄົ້ນຫາດ້ວຍສຽງບໍ່ໄດ້ໃຫ້ບໍລິການ, ກະລຸນາລອງ ໃໝ່ ພາຍຫຼັງທີ່ໃຊ້ເວລາ"
            )
        ),
        'mk-MK' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "Не можам да пристапувам до микрофонот",
                self::BROWSER_DENIED_ACCESS => "Вашата безбедност на прелистувачот не ми дозволува пристап до микрофон",
                self::TRANSCRIBING => " Препишувајќи…",
                self::UNABLE_TO_HEAR => "Не можам да те слушам",
                self::ASK_AGAIN => " Кажи повторно ....",
                self::CAN_NOT_ACCESS_MIC => "не можат да пристапат до микрофонот",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "или напишете го вашето барање"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Гласовното пребарување не е во функција, обидете се повторно по некое време"
            )
        ),
        'ml-IN' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "എനിക്ക് മൈക്രോഫോൺ ആക്സസ് ചെയ്യാൻ കഴിയില്ല",
                self::BROWSER_DENIED_ACCESS => "മൈക്ക് ആക്സസ് ചെയ്യാൻ നിങ്ങളുടെ ബ്ര browser സർ സുരക്ഷ എന്നെ അനുവദിക്കുന്നില്ല",
                self::TRANSCRIBING => " പകർത്തുന്നു ....",
                self::UNABLE_TO_HEAR => "എനിക്ക് നിങ്ങളെ കേൾക്കാൻ കഴിയുന്നില്ല",
                self::ASK_AGAIN => " വീണ്ടും പറയൂ ....",
                self::CAN_NOT_ACCESS_MIC => "മൈക്രോഫോൺ ആക്സസ് ചെയ്യാൻ കഴിയില്ല",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "അല്ലെങ്കിൽ നിങ്ങളുടെ ചോദ്യം ടൈപ്പുചെയ്യുക"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "വോയ്‌സ് തിരയൽ സേവനത്തിന് പുറത്താണ്, കുറച്ച് സമയത്തിന് ശേഷം വീണ്ടും ശ്രമിക്കുക"
            )
        ),
        'mn-MN' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "Би микрофон руу нэвтрэх боломжгүй байна",
                self::BROWSER_DENIED_ACCESS => "Таны хөтөчийн аюулгүй байдал микрофонд нэвтрэхийг зөвшөөрөхгүй байна",
                self::TRANSCRIBING => " Хөрвүүлж байна ...",
                self::UNABLE_TO_HEAR => "Би та нарыг сонсох боломжгүй байна",
                self::ASK_AGAIN => " Дахин хэлнэ үү ....",
                self::CAN_NOT_ACCESS_MIC => "микрофон руу нэвтрэх боломжгүй байна",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "эсвэл асуулгаа бичнэ үү"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Дуун хайлт идэвхгүй байна. Хэсэг хугацааны дараа дахин оролдоно уу"
            )
        ),
        'mr-IN' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "मी मायक्रोफोनवर प्रवेश करण्यात अक्षम आहे",
                self::BROWSER_DENIED_ACCESS => "आपली ब्राउझर सुरक्षितता मला माइकमध्ये प्रवेश करण्याची परवानगी देत नाही",
                self::TRANSCRIBING => " लिप्यंतरण ....",
                self::UNABLE_TO_HEAR => "मी तुला ऐकण्यास असमर्थ आहे",
                self::ASK_AGAIN => " परत बोल ....",
                self::CAN_NOT_ACCESS_MIC => "मायक्रोफोनवर प्रवेश करू शकत नाही",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "किंवा आपली क्वेरी टाइप करा"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "व्हॉइस शोध सेवाबाहेर आहे, कृपया काही वेळानंतर पुन्हा प्रयत्न करा"
            )
        ),
        'ms-MY' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "Saya tidak dapat mengakses mikrofon",
                self::BROWSER_DENIED_ACCESS => "Keselamatan penyemak imbas anda tidak membenarkan saya mengakses mikrofon",
                self::TRANSCRIBING => " Menyalin ....",
                self::UNABLE_TO_HEAR => "Saya tidak dapat mendengar anda",
                self::ASK_AGAIN => " Katakan sekali lagi ....",
                self::CAN_NOT_ACCESS_MIC => "tidak dapat mengakses mikrofon",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "atau taipkan pertanyaan anda"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Pencarian suara tidak berfungsi, Sila cuba lagi setelah beberapa ketika"
            )
        ),
        'my-MM' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "ကျွန်ုပ်မိုက်ခရိုဖုန်းကိုသုံးလို့မရပါဘူး",
                self::BROWSER_DENIED_ACCESS => "သင်၏ဘရောင်ဇာလုံခြုံရေးကကျွန်ုပ်အားမိုက်ကရိုဖုန်းကိုအသုံးပြုခွင့်မပြုပါ",
                self::TRANSCRIBING => " ကူးယူခြင်း ....",
                self::UNABLE_TO_HEAR => "သင့်စကားကိုနားမထောင်နိုင်ဘူး",
                self::ASK_AGAIN => " ထပ်ပြောပါ ....",
                self::CAN_NOT_ACCESS_MIC => "မိုက်ခရိုဖုန်းကိုသုံးလို့မရဘူး",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "သို့မဟုတ်သင်၏စုံစမ်းမှုကိုရိုက်ထည့်ပါ"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "အသံရှာဖွေမှုသည်ပျက်နေပါပြီ။ ကျေးဇူးပြု၍ ခဏကြာပြီးမှထပ်မံကြိုးစားပါ"
            )
        ),
        'ne-NP' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "म माइक्रोफोन पहुँच गर्न असमर्थ छु",
                self::BROWSER_DENIED_ACCESS => "तपाईंको ब्राउजर सुरक्षाले मलाई माइक पहुँच गर्न अनुमति दिँदैन",
                self::TRANSCRIBING => " ट्रान्सक्राइब गर्दै ....",
                self::UNABLE_TO_HEAR => "म तिमीलाई सुन्न असमर्थ छु",
                self::ASK_AGAIN => " फेरी भन ....",
                self::CAN_NOT_ACCESS_MIC => "माइक्रोफोन पहुँच गर्न सक्दैन",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "वा तपाईंको क्वेरी टाइप गर्नुहोस्"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "आवाज खोजी सेवा बाहिर छ, केहि समय पछि पुन: प्रयास गर्नुहोस्"
            )
        ),
        'pa-guru-IN' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "ਮੈਂ ਮਾਈਕ੍ਰੋਫੋਨ ਨੂੰ ਖੋਲ੍ਹਣ ਵਿੱਚ ਅਸਮਰੱਥ ਹਾਂ",
                self::BROWSER_DENIED_ACCESS => "ਤੁਹਾਡੀ ਬ੍ਰਾ .ਜ਼ਰ ਸੁਰੱਖਿਆ ਮੈਨੂੰ ਮਾਈਕ ਤਕ ਪਹੁੰਚਣ ਦੀ ਆਗਿਆ ਨਹੀਂ ਦਿੰਦੀ",
                self::TRANSCRIBING => " ਪ੍ਰਤੀਲਿਪੀ",
                self::UNABLE_TO_HEAR => "ਮੈਂ ਤੁਹਾਨੂੰ ਸੁਣਨ ਤੋਂ ਅਸਮਰੱਥ ਹਾਂ",
                self::ASK_AGAIN => " ਫੇਰ ਕਹੋ….",
                self::CAN_NOT_ACCESS_MIC => "ਮਾਈਕ੍ਰੋਫੋਨ ਐਕਸੈਸ ਨਹੀਂ ਕਰ ਸਕਦਾ",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "ਜਾਂ ਆਪਣੀ ਪੁੱਛਗਿੱਛ ਟਾਈਪ ਕਰੋ"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "ਅਵਾਜ਼ ਦੀ ਭਾਲ ਸੇਵਾ ਤੋਂ ਬਾਹਰ ਹੈ, ਕਿਰਪਾ ਕਰਕੇ ਕੁਝ ਸਮੇਂ ਬਾਅਦ ਦੁਬਾਰਾ ਕੋਸ਼ਿਸ਼ ਕਰੋ"
            )
        ),
        'si-LK' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "මට මයික්‍රෝෆෝනයට ප්‍රවේශ විය නොහැක",
                self::BROWSER_DENIED_ACCESS => "ඔබගේ බ්‍රව්සරයේ ආරක්ෂාව මට මයික් එකට ප්‍රවේශ වීමට ඉඩ නොදේ",
                self::TRANSCRIBING => " පිටපත් කිරීම ....",
                self::UNABLE_TO_HEAR => "මට ඔබව ඇහෙන්නේ නැහැ",
                self::ASK_AGAIN => " නැවත කියන්න ....",
                self::CAN_NOT_ACCESS_MIC => "මයික්‍රෝෆෝනයට ප්‍රවේශ විය නොහැක",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "හෝ ඔබේ විමසුම ටයිප් කරන්න"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "හ search සෙවීම සේවයෙන් බැහැරයි, කරුණාකර ටික වේලාවකට පසු නැවත උත්සාහ කරන්න"
            )
        ),
        'sq-AL' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "Unë nuk jam në gjendje të hyj në mikrofon",
                self::BROWSER_DENIED_ACCESS => "Siguria juaj e shfletuesit nuk më lejon të hyj në mikrofon",
                self::TRANSCRIBING => " Duke transferuar…",
                self::UNABLE_TO_HEAR => "Unë nuk jam në gjendje t'ju dëgjoj",
                self::ASK_AGAIN => " Thuaje perseri ....",
                self::CAN_NOT_ACCESS_MIC => "nuk mund të hyni në mikrofon",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "ose shtypni pyetjen tuaj"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Kërkimi me zë është jashtë shërbimit, Ju lutemi provoni përsëri pas disa kohësh"
            )
        ),
        'su-ID' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "Abdi henteu tiasa ngaksés mikropon",
                self::BROWSER_DENIED_ACCESS => "Kaamanan browser anjeun henteu kéngingkeun mic kana mic",
                self::TRANSCRIBING => " Nyalinna….",
                self::UNABLE_TO_HEAR => "Abdi henteu tiasa ngadangukeun anjeun",
                self::ASK_AGAIN => " Sebutkeun deui….",
                self::CAN_NOT_ACCESS_MIC => "teu tiasa ngaksés mikropon",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "atanapi ngetik pamundut anjeun"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Milarian sora parantos kaluar tina jasa, Punten cobian deui saatos sababaraha waktos"
            )
        ),
        'sw-KE' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "Siwezi kupata kipaza sauti",
                self::BROWSER_DENIED_ACCESS => "Usalama wa kivinjari chako hairuhusu kufikia mic",
                self::TRANSCRIBING => " Kuandika ....",
                self::UNABLE_TO_HEAR => "Siwezi kukusikia",
                self::ASK_AGAIN => " Sema tena ....",
                self::CAN_NOT_ACCESS_MIC => "haiwezi kupata kipaza sauti",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "au chapa swali lako"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Utaftaji wa sauti hautumiki, tafadhali jaribu tena baada ya muda"
            )
        ),
        'sw-TZ' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "Siwezi kupata kipaza sauti",
                self::BROWSER_DENIED_ACCESS => "Usalama wa kivinjari chako hairuhusu kufikia mic",
                self::TRANSCRIBING => " Kuandika ....",
                self::UNABLE_TO_HEAR => "Siwezi kukusikia",
                self::ASK_AGAIN => " Sema tena ....",
                self::CAN_NOT_ACCESS_MIC => "haiwezi kupata kipaza sauti",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "au chapa swali lako"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Utaftaji wa sauti hautumiki, tafadhali jaribu tena baada ya muda"
            )
        ),
        'ta-IN' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "என்னால் மைக்ரோஃபோனை அணுக முடியவில்லை",
                self::BROWSER_DENIED_ACCESS => "உங்கள் உலாவி பாதுகாப்பு என்னை மைக்கை அணுக அனுமதிக்காது",
                self::TRANSCRIBING => " படியெடுத்தல் ....",
                self::UNABLE_TO_HEAR => "என்னால் உன்னைக் கேட்க முடியவில்லை",
                self::ASK_AGAIN => " மீண்டும் கூறு ....",
                self::CAN_NOT_ACCESS_MIC => "மைக்ரோஃபோனை அணுக முடியாது",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "அல்லது உங்கள் வினவலைத் தட்டச்சு செய்க"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "குரல் தேடல் சேவையில் இல்லை, சிறிது நேரம் கழித்து மீண்டும் முயற்சிக்கவும்"
            )
        ),
        'ta-LK' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "என்னால் மைக்ரோஃபோனை அணுக முடியவில்லை",
                self::BROWSER_DENIED_ACCESS => "உங்கள் உலாவி பாதுகாப்பு என்னை மைக்கை அணுக அனுமதிக்காது",
                self::TRANSCRIBING => " படியெடுத்தல் ....",
                self::UNABLE_TO_HEAR => "என்னால் உன்னைக் கேட்க முடியவில்லை",
                self::ASK_AGAIN => " மீண்டும் கூறு ....",
                self::CAN_NOT_ACCESS_MIC => "மைக்ரோஃபோனை அணுக முடியாது",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "அல்லது உங்கள் வினவலைத் தட்டச்சு செய்க"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "குரல் தேடல் சேவையில் இல்லை, சிறிது நேரம் கழித்து மீண்டும் முயற்சிக்கவும்"
            )
        ),
        'ta-MY' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "என்னால் மைக்ரோஃபோனை அணுக முடியவில்லை",
                self::BROWSER_DENIED_ACCESS => "உங்கள் உலாவி பாதுகாப்பு என்னை மைக்கை அணுக அனுமதிக்காது",
                self::TRANSCRIBING => " படியெடுத்தல் ....",
                self::UNABLE_TO_HEAR => "என்னால் உன்னைக் கேட்க முடியவில்லை",
                self::ASK_AGAIN => " மீண்டும் கூறு ....",
                self::CAN_NOT_ACCESS_MIC => "மைக்ரோஃபோனை அணுக முடியாது",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "அல்லது உங்கள் வினவலைத் தட்டச்சு செய்க"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "குரல் தேடல் சேவையில் இல்லை, சிறிது நேரம் கழித்து மீண்டும் முயற்சிக்கவும்"
            )
        ),
        'ta-SG' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "என்னால் மைக்ரோஃபோனை அணுக முடியவில்லை",
                self::BROWSER_DENIED_ACCESS => "உங்கள் உலாவி பாதுகாப்பு என்னை மைக்கை அணுக அனுமதிக்காது",
                self::TRANSCRIBING => " படியெடுத்தல் ....",
                self::UNABLE_TO_HEAR => "என்னால் உன்னைக் கேட்க முடியவில்லை",
                self::ASK_AGAIN => " மீண்டும் கூறு ....",
                self::CAN_NOT_ACCESS_MIC => "மைக்ரோஃபோனை அணுக முடியாது",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "அல்லது உங்கள் வினவலைத் தட்டச்சு செய்க"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "குரல் தேடல் சேவையில் இல்லை, சிறிது நேரம் கழித்து மீண்டும் முயற்சிக்கவும்"
            )
        ),
        'te-IN' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "నేను మైక్రోఫోన్‌ను యాక్సెస్ చేయలేకపోతున్నాను",
                self::BROWSER_DENIED_ACCESS => "మీ బ్రౌజర్ భద్రత మైక్ యాక్సెస్ చేయడానికి నన్ను అనుమతించదు",
                self::TRANSCRIBING => " లిప్యంతరీకరణ ....",
                self::UNABLE_TO_HEAR => "నేను మీ మాట వినలేను",
                self::ASK_AGAIN => " మళ్ళీ చెప్పు ....",
                self::CAN_NOT_ACCESS_MIC => "మైక్రోఫోన్‌ను యాక్సెస్ చేయలేరు",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "లేదా మీ ప్రశ్నను టైప్ చేయండి"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "వాయిస్ శోధన సేవలో లేదు, దయచేసి కొంత సమయం తర్వాత మళ్లీ ప్రయత్నించండి"
            )
        ),
        'ur-IN' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "میں مائکروفون تک رسائی حاصل کرنے سے قاصر ہوں",
                self::BROWSER_DENIED_ACCESS => "آپ کے براؤزر کی سیکیورٹی مجھے مائک تک رسائی کی اجازت نہیں دیتی ہے",
                self::TRANSCRIBING => " نقل…",
                self::UNABLE_TO_HEAR => "میں آپ کو سننے سے قاصر ہوں",
                self::ASK_AGAIN => " پھر سے کہو ....",
                self::CAN_NOT_ACCESS_MIC => "مائکروفون تک رسائی حاصل نہیں کرسکتے ہیں",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "یا اپنی استفسار ٹائپ کریں"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "آواز کی تلاش خدمت سے باہر ہے ، براہ کرم کچھ دیر بعد دوبارہ کوشش کریں"
            )
        ),
        'ur-PK' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "میں مائکروفون تک رسائی حاصل کرنے سے قاصر ہوں",
                self::BROWSER_DENIED_ACCESS => "آپ کے براؤزر کی سیکیورٹی مجھے مائک تک رسائی کی اجازت نہیں دیتی ہے",
                self::TRANSCRIBING => " نقل…",
                self::UNABLE_TO_HEAR => "میں آپ کو سننے سے قاصر ہوں",
                self::ASK_AGAIN => " پھر سے کہو ....",
                self::CAN_NOT_ACCESS_MIC => "مائکروفون تک رسائی حاصل نہیں کرسکتے ہیں",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "یا اپنی استفسار ٹائپ کریں"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "آواز کی تلاش خدمت سے باہر ہے ، براہ کرم کچھ دیر بعد دوبارہ کوشش کریں"
            )
        ),
        'uz-UZ' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "Men mikrofonga kira olmayapman",
                self::BROWSER_DENIED_ACCESS => "Sizning brauzeringiz xavfsizligi mikrofonga kirishimga ruxsat bermaydi",
                self::TRANSCRIBING => " Yozmoqda ...",
                self::UNABLE_TO_HEAR => "Men sizni eshita olmayapman",
                self::ASK_AGAIN => " Yana ayting ...",
                self::CAN_NOT_ACCESS_MIC => "mikrofonga kira olmaydi",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "yoki so'rovingizni kiriting"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Ovozli qidiruv ishlamayapti, birozdan keyin qayta urinib ko'ring"
            )
        ),
        'yue-Hant-HK' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "我无法使用麦克风",
                self::BROWSER_DENIED_ACCESS => "您的浏览器安全性不允许我访问麦克风",
                self::TRANSCRIBING => " 抄写....",
                self::UNABLE_TO_HEAR => "我听不到你的声音",
                self::ASK_AGAIN => " 再说一遍 ....",
                self::CAN_NOT_ACCESS_MIC => "无法访问麦克风",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "或输入查询"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "语音搜索已停止服务，请稍后再试"
            )
        ),
        'zh-CN' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "我无法使用麦克风",
                self::BROWSER_DENIED_ACCESS => "您的浏览器安全性不允许我访问麦克风",
                self::TRANSCRIBING => " 抄写....",
                self::UNABLE_TO_HEAR => "我听不到你的声音",
                self::ASK_AGAIN => " 再说一遍 ....",
                self::CAN_NOT_ACCESS_MIC => "无法访问麦克风",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "或输入查询"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "语音搜索已停止服务，请稍后再试"
            )
        ),
        'zu-ZA' => array(
            self::MESSAGES => array(
                self::MIC_NOT_ACCESSIBLE => "Angikwazi ukufinyelela imakrofoni",
                self::BROWSER_DENIED_ACCESS => "Ukuphepha kwesipheqululisi sakho akusivumeli ukuthi ngithole i-mic",
                self::TRANSCRIBING => " Iyaqopha ....",
                self::UNABLE_TO_HEAR => "Angikwazi ukukuzwa",
                self::ASK_AGAIN => " Yisho futhi ....",
                self::CAN_NOT_ACCESS_MIC => "ayikwazi ukufinyelela imakrofoni",
            ),
            self::WIDGET_MESSAGES => array(
                self::PLACEHOLDER => "noma thayipha umbuzo wakho"
            ),
            self::ERRORS => array(
                self::OUT_OF_SERVICE => "Ukusesha ngezwi kuphelile, sicela uzame futhi ngemuva kwesikhashana"
            )
        ),
    );

    /**
     * Static member as set of languages supported Speech to Text (STT) service by Google Cloud Platform
     * 
     * Dated: 31/03/2020
     * Reference: https://cloud.google.com/speech-to-text/docs/languages 
     */
    public static $gcp_language_set = array(
        'ar-AE' => array(self::NAME => 'Arabic (United Arab Emirates)', self::SHORT_PHRASE_LANG => 'ar-XA', self::LANG_CODE => 'ar-AE'),
        'ar-BH' => array(self::NAME => 'Arabic (Bahrain)', self::SHORT_PHRASE_LANG => 'ar-XA', self::LANG_CODE => 'ar-BH'),
        'ar-DZ' => array(self::NAME => 'Arabic (Algeria)', self::SHORT_PHRASE_LANG => 'ar-XA', self::LANG_CODE => 'ar-DZ'),
        'ar-EG' => array(self::NAME => 'Arabic (Egypt)', self::SHORT_PHRASE_LANG => 'ar-XA', self::LANG_CODE => 'ar-EG'),
        'ar-IL' => array(self::NAME => 'Arabic (Israel)', self::SHORT_PHRASE_LANG => 'ar-XA', self::LANG_CODE => 'ar-IL'),
        'ar-IQ' => array(self::NAME => 'Arabic (Iraq)', self::SHORT_PHRASE_LANG => 'ar-XA', self::LANG_CODE => 'ar-IQ'),
        'ar-JO' => array(self::NAME => 'Arabic (Jordan)', self::SHORT_PHRASE_LANG => 'ar-XA', self::LANG_CODE => 'ar-JO'),
        'ar-KW' => array(self::NAME => 'Arabic (Kuwait)', self::SHORT_PHRASE_LANG => 'ar-XA', self::LANG_CODE => 'ar-KW'),
        'ar-LB' => array(self::NAME => 'Arabic (Lebanon)', self::SHORT_PHRASE_LANG => 'ar-XA', self::LANG_CODE => 'ar-LB'),
        'ar-MA' => array(self::NAME => 'Arabic (Morocco)', self::SHORT_PHRASE_LANG => 'ar-XA', self::LANG_CODE => 'ar-MA'),

        'ar-OM' => array(self::NAME => 'Arabic (Oman)', self::SHORT_PHRASE_LANG => 'ar-XA', self::LANG_CODE => 'ar-OM'),
        'ar-PS' => array(self::NAME => 'Arabic (State of Palestine)', self::SHORT_PHRASE_LANG => 'ar-XA', self::LANG_CODE => 'ar-PS'),
        'ar-QA' => array(self::NAME => 'Arabic (Qatar)', self::SHORT_PHRASE_LANG => 'ar-XA', self::LANG_CODE => 'ar-QA'),
        'ar-SA' => array(self::NAME => 'Arabic (Saudi Arabia)', self::SHORT_PHRASE_LANG => 'ar-XA', self::LANG_CODE => 'ar-SA'),
        'ar-TN' => array(self::NAME => 'Arabic (Tunisia)', self::SHORT_PHRASE_LANG => 'ar-XA', self::LANG_CODE => 'ar-TN'),
        'de-DE' => array(self::NAME => 'German (Germany)', self::SHORT_PHRASE_LANG => 'de-DE', self::LANG_CODE => 'de-DE'),
        'en-AU' => array(self::NAME => 'English (Australia)', self::SHORT_PHRASE_LANG => 'en-AU', self::LANG_CODE => 'en-AU'),
        'en-CA' => array(self::NAME => 'English (Canada)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'en-CA'),
        'en-GB' => array(self::NAME => 'English (United Kingdom)', self::SHORT_PHRASE_LANG => 'en-GB', self::LANG_CODE => 'en-GB'),
        'en-GH' => array(self::NAME => 'English (Ghana)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'en-GH'),

        'en-IE' => array(self::NAME => 'English (Ireland)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'en-IE'),
        'en-IN' => array(self::NAME => 'English (India)', self::SHORT_PHRASE_LANG => 'en-IN', self::LANG_CODE => 'en-IN'),
        'en-KE' => array(self::NAME => 'English (Kenya)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'en-KE'),
        'en-NG' => array(self::NAME => 'English (Nigeria)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'en-NG'),
        'en-NZ' => array(self::NAME => 'English (New Zealand)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'en-NZ'),
        'en-PH' => array(self::NAME => 'English (Philippines)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'en-PH'),
        'en-SG' => array(self::NAME => 'English (Singapore)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'en-SG'),
        'en-TZ' => array(self::NAME => 'English (Tanzania)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'en-TZ'),
        'en-US' => array(self::NAME => 'English (United States)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'en-US'),
        'en-ZA' => array(self::NAME => 'English (South Africa)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'en-ZA'),

        'es-AR' => array(self::NAME => 'Spanish (Argentina)', self::SHORT_PHRASE_LANG => 'es-ES', self::LANG_CODE => 'es-AR'),
        'es-BO' => array(self::NAME => 'Spanish (Bolivia)', self::SHORT_PHRASE_LANG => 'es-ES', self::LANG_CODE => 'es-BO'),
        'es-CL' => array(self::NAME => 'Spanish (Chile)', self::SHORT_PHRASE_LANG => 'es-ES', self::LANG_CODE => 'es-CL'),
        'es-CO' => array(self::NAME => 'Spanish (Colombia)', self::SHORT_PHRASE_LANG => 'es-ES', self::LANG_CODE => 'es-CO'),
        'es-CR' => array(self::NAME => 'Spanish (Costa Rica)', self::SHORT_PHRASE_LANG => 'es-ES', self::LANG_CODE => 'es-CR'),
        'es-DO' => array(self::NAME => 'Spanish (Dominican Republic)', self::SHORT_PHRASE_LANG => 'es-ES', self::LANG_CODE => 'es-DO'),
        'es-EC' => array(self::NAME => 'Spanish (Ecuador)', self::SHORT_PHRASE_LANG => 'es-ES', self::LANG_CODE => 'es-EC'),
        'es-ES' => array(self::NAME => 'Spanish (Spain)', self::SHORT_PHRASE_LANG => 'es-ES', self::LANG_CODE => 'es-ES'),
        'es-GT' => array(self::NAME => 'Spanish (Guatemala)', self::SHORT_PHRASE_LANG => 'es-ES', self::LANG_CODE => 'es-GT'),
        'es-HN' => array(self::NAME => 'Spanish (Honduras)', self::SHORT_PHRASE_LANG => 'es-ES', self::LANG_CODE => 'es-HN'),

        'es-MX' => array(self::NAME => 'Spanish (Mexico)', self::SHORT_PHRASE_LANG => 'es-ES', self::LANG_CODE => 'es-MX'),
        'es-NI' => array(self::NAME => 'Spanish (Nicaragua)', self::SHORT_PHRASE_LANG => 'es-ES', self::LANG_CODE => 'es-NI'),
        'es-PA' => array(self::NAME => 'Spanish (Panama)', self::SHORT_PHRASE_LANG => 'es-ES', self::LANG_CODE => 'es-PA'),
        'es-PE' => array(self::NAME => 'Spanish (Peru)', self::SHORT_PHRASE_LANG => 'es-ES', self::LANG_CODE => 'es-PE'),
        'es-PR' => array(self::NAME => 'Spanish (Puerto Rico)', self::SHORT_PHRASE_LANG => 'es-ES', self::LANG_CODE => 'es-PR'),
        'es-PY' => array(self::NAME => 'Spanish (Paraguay)', self::SHORT_PHRASE_LANG => 'es-ES', self::LANG_CODE => 'es-PY'),
        'es-SV' => array(self::NAME => 'Spanish (El Salvador)', self::SHORT_PHRASE_LANG => 'es-ES', self::LANG_CODE => 'es-SV'),
        'es-US' => array(self::NAME => 'Spanish (United States)', self::SHORT_PHRASE_LANG => 'es-ES', self::LANG_CODE => 'es-US'),
        'es-UY' => array(self::NAME => 'Spanish (Uruguay)', self::SHORT_PHRASE_LANG => 'es-ES', self::LANG_CODE => 'es-UY'),
        'es-VE' => array(self::NAME => 'Spanish (Venezuela)', self::SHORT_PHRASE_LANG => 'es-ES', self::LANG_CODE => 'es-VE'),

        'fr-CA' => array(self::NAME => 'French (Canada)', self::SHORT_PHRASE_LANG => 'fr-CA', self::LANG_CODE => 'fr-CA'),
        'fr-FR' => array(self::NAME => 'French (France)', self::SHORT_PHRASE_LANG => 'fr-FR', self::LANG_CODE => 'fr-FR'),
        'it-IT' => array(self::NAME => 'Italian (Italy)', self::SHORT_PHRASE_LANG => 'it-IT', self::LANG_CODE => 'it-IT'),
        'ja-JP' => array(self::NAME => 'Japanese (Japan)', self::SHORT_PHRASE_LANG => 'ja-JP', self::LANG_CODE => 'ja-JP'),
        'ko-KR' => array(self::NAME => 'Korean (South Korea)', self::SHORT_PHRASE_LANG => 'ko-KR', self::LANG_CODE => 'ko-KR'),
        'nl-NL' => array(self::NAME => 'Dutch (Netherlands)', self::SHORT_PHRASE_LANG => 'nl-NL', self::LANG_CODE => 'nl-NL'),
        'nl-BE' => array(self::NAME => 'Dutch (Belgium)', self::SHORT_PHRASE_LANG => 'nl-NL', self::LANG_CODE => 'nl-BE'),
        'pl-PL' => array(self::NAME => 'Polish (Poland)', self::SHORT_PHRASE_LANG => 'pl-PL', self::LANG_CODE => 'pl-PL'),
        'pt-BR' => array(self::NAME => 'Portuguese (Brazil)', self::SHORT_PHRASE_LANG => 'pt-BR', self::LANG_CODE => 'pt-BR'),
        'ru-RU' => array(self::NAME => 'Russian (Russia)', self::SHORT_PHRASE_LANG => 'ru-RU', self::LANG_CODE => 'ru-RU'),

        'th-TH' => array(self::NAME => 'Thai (Thailand)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'th-TH'),
        'tr-TR' => array(self::NAME => 'Turkish (Turkey)', self::SHORT_PHRASE_LANG => 'tr-TR', self::LANG_CODE => 'tr-TR'),
        'zh' => array(self::NAME => 'Chinese, Mandarin (Simplified, China)', self::SHORT_PHRASE_LANG => 'cmn-CN', self::LANG_CODE => 'zh'),
        'zh-HK' => array(self::NAME => 'Chinese, Mandarin (Simplified, Hong Kong)', self::SHORT_PHRASE_LANG => 'cmn-CN', self::LANG_CODE => 'zh-HK'),
        'yue-Hant-HK' => array(self::NAME => 'Chinese, Cantonese (Traditional, Hong Kong)', self::SHORT_PHRASE_LANG => 'cmn-CN', self::LANG_CODE => 'yue-Hant-HK'),
        'Zh-TW' => array(self::NAME => 'Chinese, Mandarin (Simplified, Taiwan)', self::SHORT_PHRASE_LANG => 'cmn-CN', self::LANG_CODE => 'Zh-TW'),
        'bg-BG' => array(self::NAME => 'Bulgarian (Bulgaria)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'bg-BG'),
        'ca-ES' => array(self::NAME => 'Catalan (Spain)', self::SHORT_PHRASE_LANG => 'es-ES', self::LANG_CODE => 'ca-ES'),
        'cs-CZ' => array(self::NAME => 'Czech (Czech Republic)', self::SHORT_PHRASE_LANG => 'cs-CZ', self::LANG_CODE => 'cs-CZ'),
        'da-DK' => array(self::NAME => 'Danish (Denmark)', self::SHORT_PHRASE_LANG => 'da-DK', self::LANG_CODE => 'da-DK'),
        'el-GR' => array(self::NAME => 'Greek (Greece)', self::SHORT_PHRASE_LANG => 'el-GR', self::LANG_CODE => 'el-GR'),

        'fi-FI' => array(self::NAME => 'Finnish (Finland)', self::SHORT_PHRASE_LANG => 'fi-FI', self::LANG_CODE => 'fi-FI'),
        'he-IL' => array(self::NAME => 'Hebrew (Israel)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'he-IL'),
        'hi-IN' => array(self::NAME => 'Hindi (India)', self::SHORT_PHRASE_LANG => 'hi-IN', self::LANG_CODE => 'hi-IN'),
        'hr-HR' => array(self::NAME => 'Croatian (Croatia)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'hr-HR'),
        'hu-HU' => array(self::NAME => 'Hungarian (Hungary)', self::SHORT_PHRASE_LANG => 'hu-HU', self::LANG_CODE => 'hu-HU'),
        'id-ID' => array(self::NAME => 'Indonesian (Indonesia)', self::SHORT_PHRASE_LANG => 'id-ID', self::LANG_CODE => 'id-ID'),
        'lt-LT' => array(self::NAME => 'Lithuanian (Lithuania)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'lt-LT'),
        'lv-LV' => array(self::NAME => 'Latvian (Latvia)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'lv-LV'),
        'nb-NO' => array(self::NAME => 'Norwegian Bokmål (Norway)', self::SHORT_PHRASE_LANG => 'nb-NO', self::LANG_CODE => 'nb-NO'),
        'pt-PT' => array(self::NAME => 'Portuguese (Portugal)', self::SHORT_PHRASE_LANG => 'pt-PT', self::LANG_CODE => 'pt-PT'),

        'ro-RO' => array(self::NAME => 'Romanian (Romania)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'ro-RO'),
        'sk-SK' => array(self::NAME => 'Slovak (Slovakia)', self::SHORT_PHRASE_LANG => 'sk-SK', self::LANG_CODE => 'sk-SK'),
        'sl-SI' => array(self::NAME => 'Slovenian (Slovenia)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'sl-SI'),
        'sr-RS' => array(self::NAME => 'Serbian (Serbia)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'sr-RS'),
        'sv-SE' => array(self::NAME => 'Swedish (Sweden)', self::SHORT_PHRASE_LANG => 'sv-SE', self::LANG_CODE => 'sv-SE'),
        'uk-UA' => array(self::NAME => 'Ukrainian (Ukraine)', self::SHORT_PHRASE_LANG => 'uk-UA', self::LANG_CODE => 'uk-UA'),
        'vi-VN' => array(self::NAME => 'Vietnamese (Vietnam)', self::SHORT_PHRASE_LANG => 'vi-VN', self::LANG_CODE => 'vi-VN'),
        'af-ZA' => array(self::NAME => 'Afrikaans (South Africa)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'af-ZA'),
        'am-ET' => array(self::NAME => 'Amharic (Ethiopia)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'am-ET'),
        'az-AZ' => array(self::NAME => 'Azerbaijani (Azerbaijan)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'az-AZ'),

        'bn-BD' => array(self::NAME => 'Bengali (Bangladesh)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'bn-BD'),
        'bn-IN' => array(self::NAME => 'Bengali (India)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'bn-IN'),
        'et-EE' => array(self::NAME => 'Estonian (Estonia)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'et-EE'),
        'eu-ES' => array(self::NAME => 'Basque (Spain)', self::SHORT_PHRASE_LANG => 'es-ES', self::LANG_CODE => 'eu-ES'),
        'fa-IR' => array(self::NAME => 'Persian (Iran)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'fa-IR'),
        'fil-PH' => array(self::NAME => 'Filipino (Philippines)', self::SHORT_PHRASE_LANG => 'fil-PH', self::LANG_CODE => 'fil-PH'),
        'gl-ES' => array(self::NAME => 'Galician (Spain)', self::SHORT_PHRASE_LANG => 'es-ES', self::LANG_CODE => 'gl-ES'),
        'gu-IN' => array(self::NAME => 'Gujarati (India)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'gu-IN'),
        'hy-AM' => array(self::NAME => 'Armenian (Armenia)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'hy-AM'),
        'is-IS' => array(self::NAME => 'Icelandic (Iceland)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'is-IS'),

        'jv-ID' => array(self::NAME => 'Javanese (Indonesia)', self::SHORT_PHRASE_LANG => 'id-ID', self::LANG_CODE => 'jv-ID'),
        'ka-GE' => array(self::NAME => 'Georgian (Georgia)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'ka-GE'),
        'km-KH' => array(self::NAME => 'Khmer (Cambodia)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'km-KH'),
        'kn-IN' => array(self::NAME => 'Kannada (India)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'kn-IN'),
        'lo-LA' => array(self::NAME => 'Lao (Laos)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'lo-LA'),
        'mk-MK' => array(self::NAME => 'Macedonian (North Macedonia)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'mk-MK'),
        'ml-IN' => array(self::NAME => 'Malayalam (India)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'ml-IN'),
        'mn-MN' => array(self::NAME => 'Mongolian (Mongolia)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'mn-MN'),
        'mr-IN' => array(self::NAME => 'Marathi (India)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'mr-IN'),
        'ms-MY' => array(self::NAME => 'Malay (Malaysia)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'ms-MY'),

        'my-MM' => array(self::NAME => 'Burmese (Myanmar)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'my-MM'),
        'ne-NP' => array(self::NAME => 'Nepali (Nepal)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'ne-NP'),
        'pa-guru-IN' => array(self::NAME => 'Punjabi (Gurmukhi, India)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'pa-guru-IN'),
        'si-LK' => array(self::NAME => 'Sinhala (Sri Lanka)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'si-LK'),
        'sq-AL' => array(self::NAME => 'Albanian (Albania)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'sq-AL'),
        'su-ID' => array(self::NAME => 'Sundanese (Indonesia)', self::SHORT_PHRASE_LANG => 'id-ID', self::LANG_CODE => 'su-ID'),
        'sw-KE' => array(self::NAME => 'Swahili (Kenya)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'sw-KE'),
        'sw-TZ' => array(self::NAME => 'Swahili (Tanzania)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'sw-TZ'),
        'ta-IN' => array(self::NAME => 'Tamil (India)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'ta-IN'),
        'ta-LK' => array(self::NAME => 'Tamil (Sri Lanka)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'ta-LK'),

        'ta-MY' => array(self::NAME => 'Tamil (Malaysia)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'ta-MY'),
        'ta-SG' => array(self::NAME => 'Tamil (Singapore)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'ta-SG'),
        'te-IN' => array(self::NAME => 'Telugu (India)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'te-IN'),
        'ur-IN' => array(self::NAME => 'Urdu (India)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'ur-IN'),
        'ur-PK' => array(self::NAME => 'Urdu (Pakistan)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'ur-PK'),
        'uz-UZ' => array(self::NAME => 'Uzbek (Uzbekistan)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'uz-UZ'),
        'zu-ZA' => array(self::NAME => 'Zulu (South Africa)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'zu-ZA'),
    );

    /**
     * Static member method to check given lang code is exist in Google Cloud STT language set
     * 
     * @param String $lang_code  Language code (eg: en-US)
     * 
     * @return Boolean  True if lang code exist otherwise false
     */
    public static function gcp_supported($lang_code)
    {
        return array_key_exists($lang_code, self::$gcp_language_set);
    }

    /**
     * Static member method to get combine language set of IBM Cloud and GCP
     * 
     * @return Array $uvs_all_lang_set  Array of language arrays
     */
    public static function get_all_languages()
    {
        $uvs_all_lang_set = array();

        try {
            if (!empty(self::$gcp_language_set)) {
                $uvs_all_lang_set = self::$gcp_language_set;
            } else {
                $uvs_all_lang_set = array('en-US' => array(self::NAME => 'English (United States)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'en-US'));
            }

            uksort($uvs_all_lang_set, 'strcasecmp');
        } catch (\Exception $err) {
            $uvs_all_lang_set = array('en-US' => array(self::NAME => 'English (United States)', self::SHORT_PHRASE_LANG => 'en-US', self::LANG_CODE => 'en-US'));
        }

        return $uvs_all_lang_set;
    }

    /**
     * Static member method to check lang code is one of the common language code or not
     * 
     * @return Boolean  True if lang code is common language code otherwise false
     */
    public static function from_common_lang_cluster($lang_code)
    {
        $short_phrase_lang = self::get_short_phrase_lang_code($lang_code);

        return in_array($short_phrase_lang, self::COMMON_LANG_CLUSTER, true);
    }

    /**
     * Static member method to get short phrase language code
     * 
     * @return String $short_phrase_lang  Langugage code
     */
    public static function get_short_phrase_lang_code($lang_code)
    {
        $uvs_lang = array();
        $short_phrase_lang = $lang_code;
        $uvs_valid_lang_code = false;

        if (self::gcp_supported($lang_code)) {
            $uvs_lang = self::$gcp_language_set[$lang_code];
            $uvs_valid_lang_code = true;
        }

        if ($uvs_valid_lang_code === true && array_key_exists(self::SHORT_PHRASE_LANG, $uvs_lang)) {
            $short_phrase_lang = $uvs_lang[self::SHORT_PHRASE_LANG];
        }

        return $short_phrase_lang;
    }

}