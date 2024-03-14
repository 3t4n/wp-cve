<?php


class Variables
{

    public $new_user = true;
    public $segments = [];
    public $segments_hash = [];
    public $items = [];
    public $links = [];
    public $exclusions = [];
    public $glossary = [];
    public $exclusion_blocks = [];
    public $exclusion_block_ids = [];
    public $account;
    public $api_key = '';
    public $source_language = '';
    public $target_languages = '';
    public $default_language = '';
    public $target_languages_translations = '';
    public $language_code = '';
    public $site_url;
    public $site_host;
    public $site_prefix;
    public $plan = 'free';
    public $translate_media;
    public $translate_document;
    public $translate_links;
    public $show_widget = true;
    public $exceeded = false;
    public $system_links = [];


    public $style_change_language;
    public $style_change_flag;
    public $style_flag;
    public $style_text;
    public $style_position_vertical;
    public $style_position_horizontal;
    public $style_indenting_vertical;
    public $style_indenting_horizontal;
    public $auto_translate;
    public $select_region;
    public $hide_conveythis_logo;
    public $change_direction;
    public $alternate;
    public $accept_language;
    public $blockpages;
    public $show_javascript;
    public $style_position_type;
    public $style_position_vertical_custom;
    public $style_selector_id;
    public $url_structure;
    public $style_background_color;
    public $style_hover_color;
    public $style_border_color;
    public $style_text_color;
    public $style_corner_type;
    public $style_widget;
    public $blockpages_items;
    public $referrer;
    public $lang_code_url;
    public $clear_cache;

    public $shortcode_counter = 0;

    public $pluginPath;

    public $cacheTranslateSize = 0;

    public $imageExt = [
        'gif',
        'jpg',
        'jpeg',
        'png',
        'webp',
        'svg'
    ];

    public $documentExt = [
        'pdf'
    ];

    public $avoidUrlExt = [
        'pdf',
        'xml',
        'xlsx',
        'docx',
        'gif',
        'jpg',
        'jpeg',
        'png',
        'mp3',
        'mp4',
    ];

    // Luxemb - Luxembourgish
    // Haitian - Haitian (Creole)

    public $siblingsAllowArray = ["A", "ABBR", "ACRONYM", "BDO", "BDI", "STRONG","BR", "EM", "I", "B", "CITE", "DEL", "DFN", "INS", "MARK", "Q", "BIG", "SMALL", "SUB", "SUP", "U"];
    public $siblingsAvoidArray = ["P", "DIV", "H1", "H2", "H3", "H4", "H5", "H6", "LABEL", "LI", "SVG", "PRE"];

    public $matchingLanguages = array(
        703 => array('language_id' => 703, 'title_en' => 'English', 'title' => 'English', 'code2' => 'en', 'code3' => 'eng', 'flag' => 'us', 'flag_ids' => [498, 497, 342]),
        768 => array('language_id' => 768, 'title_en' => 'Portuguese', 'title' => 'Português', 'code2' => 'pt', 'code3' => 'por', 'flag' => 'br', 'flag_ids' => [422, 318, 348]),
        777 => array('language_id' => 777, 'title_en' => 'Spanish', 'title' => 'Español', 'code2' => 'es', 'code3' => 'spa', 'flag' => 'es','flag_ids' => [336]),
    );

    public $matchingLanguageToFlag = array(
        703 => array(498, 497, 342),
        768 => array(336, 450),
        777 => array(422, 318, 348, 474)
    );

    public $languages = array(
        703 => array('language_id' => 703, 'title_en' => 'English', 'title' => 'English', 'code2' => 'en', 'code3' => 'eng', 'flag' => 'us'),
        704 => array('language_id' => 704, 'title_en' => 'Afrikaans', 'title' => 'Afrikaans', 'code2' => 'af', 'code3' => 'afr', 'flag' => 'za'),
        705 => array('language_id' => 705, 'title_en' => 'Albanian', 'title' => 'Shqip', 'code2' => 'sq', 'code3' => 'sqi', 'flag' => 'al'),
        706 => array('language_id' => 706, 'title_en' => 'Amharic', 'title' => 'አማርኛ', 'code2' => 'am', 'code3' => 'amh', 'flag' => 'et'),
        707 => array('language_id' => 707, 'title_en' => 'Arabic', 'title' => 'العربية', 'code2' => 'ar', 'code3' => 'ara', 'flag' => 'sa', 'rtl' => 1),
        708 => array('language_id' => 708, 'title_en' => 'Armenian', 'title' => 'Հայերեն', 'code2' => 'hy', 'code3' => 'hye', 'flag' => 'am'),
        709 => array('language_id' => 709, 'title_en' => 'Azerbaijan', 'title' => 'Azərbaycanca', 'code2' => 'az', 'code3' => 'aze', 'flag' => 'az'),
        711 => array('language_id' => 711, 'title_en' => 'Basque', 'title' => 'Euskara', 'code2' => 'eu', 'code3' => 'eus', 'flag' => 'es-pv'),
        712 => array('language_id' => 712, 'title_en' => 'Belarusian', 'title' => 'Беларуская', 'code2' => 'be', 'code3' => 'bel', 'flag' => 'by'),
        713 => array('language_id' => 713, 'title_en' => 'Bengali', 'title' => 'বাংলা', 'code2' => 'bn', 'code3' => 'ben', 'flag' => 'bd'),
        714 => array('language_id' => 714, 'title_en' => 'Bosnian', 'title' => 'Bosanski', 'code2' => 'bs', 'code3' => 'bos', 'flag' => 'ba'),
        715 => array('language_id' => 715, 'title_en' => 'Bulgarian', 'title' => 'Български', 'code2' => 'bg', 'code3' => 'bul', 'flag' => 'bg'),
        716 => array('language_id' => 716, 'title_en' => 'Burmese', 'title' => 'မြန်မာဘာသာ', 'code2' => 'my', 'code3' => 'mya', 'flag' => 'mm'),
        717 => array('language_id' => 717, 'title_en' => 'Catalan', 'title' => 'Català', 'code2' => 'ca', 'code3' => 'cat', 'flag' => 'es-ct'),
        718 => array('language_id' => 718, 'title_en' => 'Cebuano', 'title' => 'Cebuano', 'code2' => 'ceb', 'code3' => 'ceb', 'flag' => 'es-pv'),
        719 => array('language_id' => 719, 'title_en' => 'Chinese (Simplified)', 'title' => '简体', 'code2' => 'zh', 'code3' => 'zho-sim', 'flag' => 'cn'),
        720 => array('language_id' => 720, 'title_en' => 'Croatian', 'title' => 'Hrvatski', 'code2' => 'hr', 'code3' => 'hrv', 'flag' => 'hr'),
        721 => array('language_id' => 721, 'title_en' => 'Czech', 'title' => 'Čeština', 'code2' => 'cs', 'code3' => 'cze', 'flag' => 'cz'),
        722 => array('language_id' => 722, 'title_en' => 'Danish', 'title' => 'Dansk', 'code2' => 'da', 'code3' => 'dan', 'flag' => 'dk'),
        723 => array('language_id' => 723, 'title_en' => 'Dutch', 'title' => 'Nederlands', 'code2' => 'nl', 'code3' => 'nld', 'flag' => 'nl'),
        724 => array('language_id' => 724, 'title_en' => 'Esperanto', 'title' => 'Esperanto', 'code2' => 'eo', 'code3' => 'epo', 'flag' => 'us'),
        725 => array('language_id' => 725, 'title_en' => 'Estonian', 'title' => 'Eesti', 'code2' => 'et', 'code3' => 'est', 'flag' => 'ee'),
        726 => array('language_id' => 726, 'title_en' => 'Finnish', 'title' => 'Suomi', 'title' => 'Finnish', 'code2' => 'fi', 'code3' => 'fin', 'flag' => 'fi'),
        727 => array('language_id' => 727, 'title_en' => 'French', 'title' => 'Français', 'code2' => 'fr', 'code3' => 'fre', 'flag' => 'fr'),
        728 => array('language_id' => 728, 'title_en' => 'Galician', 'title' => 'Galego', 'code2' => 'gl', 'code3' => 'glg', 'flag' => 'es'),
        729 => array('language_id' => 729, 'title_en' => 'Georgian', 'title' => 'ქართული', 'code2' => 'ka', 'code3' => 'kat', 'flag' => 'ge'),
        730 => array('language_id' => 730, 'title_en' => 'German', 'title' => 'Deutsch', 'code2' => 'de', 'code3' => 'ger', 'flag' => 'de'),
        731 => array('language_id' => 731, 'title_en' => 'Greek', 'title' => 'Ελληνικά', 'code2' => 'el', 'code3' => 'ell', 'flag' => 'gr'),
        732 => array('language_id' => 732, 'title_en' => 'Gujarati', 'title' => 'ગુજરાતી', 'code2' => 'gu', 'code3' => 'guj', 'flag' => 'in'),
        733 => array('language_id' => 733, 'title_en' => 'Haitian', 'title' => 'Kreyòl Ayisyen', 'code2' => 'ht', 'code3' => 'hat', 'flag' => 'es-pv'),
        734 => array('language_id' => 734, 'title_en' => 'Hebrew', 'title' => 'עברית', 'code2' => 'he', 'code3' => 'heb', 'flag' => 'il', 'rtl' => 1),
        736 => array('language_id' => 736, 'title_en' => 'Hindi', 'title' => 'हिन्दी', 'code2' => 'hi', 'code3' => 'hin', 'flag' => 'in'),
        737 => array('language_id' => 737, 'title_en' => 'Hungarian', 'title' => 'Magyar', 'code2' => 'hu', 'code3' => 'hun', 'flag' => 'hu'),
        738 => array('language_id' => 738, 'title_en' => 'Icelandic', 'title' => 'Íslenska', 'code2' => 'is', 'code3' => 'isl', 'flag' => 'is'),
        739 => array('language_id' => 739, 'title_en' => 'Indonesian', 'title' => 'Bahasa Indonesia', 'code2' => 'id', 'code3' => 'ind', 'flag' => 'id'),
        740 => array('language_id' => 740, 'title_en' => 'Irish', 'title' => 'Gaeilge', 'code2' => 'ga', 'code3' => 'gle', 'flag' => 'ie'),
        741 => array('language_id' => 741, 'title_en' => 'Italian', 'title' => 'Italiano', 'code2' => 'it', 'code3' => 'ita', 'flag' => 'it'),
        742 => array('language_id' => 742, 'title_en' => 'Japanese', 'title' => '日本語', 'code2' => 'ja', 'code3' => 'jpn', 'flag' => 'jp'),
        743 => array('language_id' => 743, 'title_en' => 'Javanese', 'title' => 'Basa Jawa', 'code2' => 'jv', 'code3' => 'jav', 'flag' => 'my'),
        744 => array('language_id' => 744, 'title_en' => 'Kannada', 'title' => 'ಕನ್ನಡ', 'code2' => 'kn', 'code3' => 'kan', 'flag' => 'in'),
        745 => array('language_id' => 745, 'title_en' => 'Kazakh', 'title' => 'Қазақша', 'code2' => 'kk', 'code3' => 'kaz', 'flag' => 'kz'),
        746 => array('language_id' => 746, 'title_en' => 'Khmer', 'title' => 'ភាសាខ្មែរ', 'code2' => 'km', 'code3' => 'khm', 'flag' => 'kh'),
        747 => array('language_id' => 747, 'title_en' => 'Korean', 'title' => '한국어', 'code2' => 'ko', 'code3' => 'kor', 'flag' => 'kr'),
        748 => array('language_id' => 748, 'title_en' => 'Kyrgyz', 'title' => 'Кыргызча', 'code2' => 'ky', 'code3' => 'kir', 'flag' => 'kg'),
        749 => array('language_id' => 749, 'title_en' => 'Laotian', 'title' => 'ພາສາລາວ', 'code2' => 'lo', 'code3' => 'lao', 'flag' => 'la'),
        750 => array('language_id' => 750, 'title_en' => 'Latin', 'title' => 'Latina', 'code2' => 'la', 'code3' => 'lat', 'flag' => 'it'),
        751 => array('language_id' => 751, 'title_en' => 'Latvian', 'title' => 'Latviešu', 'code2' => 'lv', 'code3' => 'lav', 'flag' => 'lv'),
        752 => array('language_id' => 752, 'title_en' => 'Lithuanian', 'title' => 'Lietuvių', 'code2' => 'lt', 'code3' => 'lit', 'flag' => 'lt'),
        753 => array('language_id' => 753, 'title_en' => 'Luxemb', 'title' => 'Lëtzebuergesch', 'code2' => 'lb', 'code3' => 'ltz', 'flag' => 'lu'),
        754 => array('language_id' => 754, 'title_en' => 'Macedonian', 'title' => 'Македонски', 'code2' => 'mk', 'code3' => 'mkd', 'flag' => 'mk'),
        755 => array('language_id' => 755, 'title_en' => 'Malagasy', 'title' => 'Malagasy', 'code2' => 'mg', 'code3' => 'mlg', 'flag' => 'mg'),
        756 => array('language_id' => 756, 'title_en' => 'Malay', 'title' => 'Bahasa Melayu', 'code2' => 'ms', 'code3' => 'msa', 'flag' => 'my'),
        757 => array('language_id' => 757, 'title_en' => 'Malayalam', 'title' => 'മലയാളം', 'code2' => 'ml', 'code3' => 'mal', 'flag' => 'in'),
        758 => array('language_id' => 758, 'title_en' => 'Maltese', 'title' => 'Malti', 'code2' => 'mt', 'code3' => 'mlt', 'flag' => 'mt'),
        759 => array('language_id' => 759, 'title_en' => 'Maori', 'title' => 'Māori', 'code2' => 'mi', 'code3' => 'mri', 'flag' => 'nz'),
        760 => array('language_id' => 760, 'title_en' => 'Marathi', 'title' => 'मराठी', 'code2' => 'mr', 'code3' => 'mar', 'flag' => 'in'),
        762 => array('language_id' => 762, 'title_en' => 'Mongolian', 'title' => 'Монгол', 'code2' => 'mn', 'code3' => 'mon', 'flag' => 'nm'),
        763 => array('language_id' => 763, 'title_en' => 'Nepali', 'title' => 'नेपाली', 'code2' => 'ne', 'code3' => 'nep', 'flag' => 'np'),
        764 => array('language_id' => 764, 'title_en' => 'Norwegian', 'title' => 'Norsk', 'code2' => 'no', 'code3' => 'nor', 'flag' => 'no'),
        766 => array('language_id' => 766, 'title_en' => 'Persian', 'title' => 'فارسی', 'code2' => 'fa', 'code3' => 'per', 'flag' => 'ir', 'rtl' => 1),
        767 => array('language_id' => 767, 'title_en' => 'Polish', 'title' => 'Polski', 'code2' => 'pl', 'code3' => 'pol', 'flag' => 'pl'),
        768 => array('language_id' => 768, 'title_en' => 'Portuguese', 'title' => 'Português', 'code2' => 'pt', 'code3' => 'por', 'flag' => 'br'),
        769 => array('language_id' => 769, 'title_en' => 'Punjabi', 'title' => 'ਪੰਜਾਬੀ', 'code2' => 'pa', 'code3' => 'pan', 'flag' => 'pk'),
        770 => array('language_id' => 770, 'title_en' => 'Romanian', 'title' => 'Română', 'code2' => 'ro', 'code3' => 'rum', 'flag' => 'ro'),
        771 => array('language_id' => 771, 'title_en' => 'Russian', 'title' => 'Русский', 'code2' => 'ru', 'code3' => 'rus', 'flag' => 'ru'),
        772 => array('language_id' => 772, 'title_en' => 'Scottish', 'title' => 'Gàidhlig', 'code2' => 'gd', 'code3' => 'gla', 'flag' => 'gb-sct'),
        773 => array('language_id' => 773, 'title_en' => 'Serbian', 'title' => 'Српски', 'code2' => 'sr', 'code3' => 'srp', 'flag' => 'rs'),
        774 => array('language_id' => 774, 'title_en' => 'Sinhala', 'title' => 'සිංහල', 'code2' => 'si', 'code3' => 'sin', 'flag' => 'lk'),
        775 => array('language_id' => 775, 'title_en' => 'Slovakian', 'title' => 'Slovenčina', 'code2' => 'sk', 'code3' => 'slk', 'flag' => 'sk'),
        776 => array('language_id' => 776, 'title_en' => 'Slovenian', 'title' => 'Slovenščina', 'code2' => 'sl', 'code3' => 'slv', 'flag' => 'si'),
        777 => array('language_id' => 777, 'title_en' => 'Spanish', 'title' => 'Español', 'code2' => 'es', 'code3' => 'spa', 'flag' => 'es'),
        778 => array('language_id' => 778, 'title_en' => 'Sundanese', 'title' => 'Basa Sunda', 'code2' => 'su', 'code3' => 'sun', 'flag' => 'sd'),
        779 => array('language_id' => 779, 'title_en' => 'Swahili', 'title' => 'Kiswahili', 'code2' => 'sw', 'code3' => 'swa', 'flag' => 'ke'),
        780 => array('language_id' => 780, 'title_en' => 'Swedish', 'title' => 'Svenska', 'code2' => 'sv', 'code3' => 'swe', 'flag' => 'se'),
        781 => array('language_id' => 781, 'title_en' => 'Tagalog', 'title' => 'Tagalog', 'code2' => 'tl', 'code3' => 'tgl', 'flag' => 'ph'),
        782 => array('language_id' => 782, 'title_en' => 'Tajik', 'title' => 'Тоҷикӣ', 'code2' => 'tg', 'code3' => 'tgk', 'flag' => 'tj'),
        783 => array('language_id' => 783, 'title_en' => 'Tamil', 'title' => 'தமிழ்', 'code2' => 'ta', 'code3' => 'tam', 'flag' => 'in'),
        784 => array('language_id' => 784, 'title_en' => 'Tatar', 'title' => 'Татарча', 'code2' => 'tt', 'code3' => 'tat', 'flag' => 'ru'),
        785 => array('language_id' => 785, 'title_en' => 'Telugu', 'title' => 'తెలుగు', 'code2' => 'te', 'code3' => 'tel', 'flag' => 'in'),
        786 => array('language_id' => 786, 'title_en' => 'Thai', 'title' => 'ภาษาไทย', 'code2' => 'th', 'code3' => 'tha', 'flag' => 'th'),
        787 => array('language_id' => 787, 'title_en' => 'Turkish', 'title' => 'Türkçe', 'code2' => 'tr', 'code3' => 'tur', 'flag' => 'tr'),
        789 => array('language_id' => 789, 'title_en' => 'Ukrainian', 'title' => 'Українська', 'code2' => 'uk', 'code3' => 'ukr', 'flag' => 'ua'),
        790 => array('language_id' => 790, 'title_en' => 'Urdu', 'title' => 'اردو', 'code2' => 'ur', 'code3' => 'urd', 'flag' => 'pk', 'rtl' => 1),
        791 => array('language_id' => 791, 'title_en' => 'Uzbek', 'title' => 'O‘zbek', 'code2' => 'uz', 'code3' => 'uzb', 'flag' => 'uz'),
        792 => array('language_id' => 792, 'title_en' => 'Vietnamese', 'title' => 'Tiếng Việt', 'code2' => 'vi', 'code3' => 'vie', 'flag' => 'vn'),
        793 => array('language_id' => 793, 'title_en' => 'Welsh', 'title' => 'Cymraeg', 'code2' => 'cy', 'code3' => 'wel', 'flag' => 'gb-wls'),
        794 => array('language_id' => 794, 'title_en' => 'Xhosa', 'title' => 'isiXhosa', 'code2' => 'xh', 'code3' => 'xho', 'flag' => 'za'),
        795 => array('language_id' => 795, 'title_en' => 'Yiddish', 'title' => 'ייִדיש', 'code2' => 'yi', 'code3' => 'yid', 'flag' => 'il'),
        796 => array('language_id' => 796, 'title_en' => 'Chinese (Traditional)', 'title' => '繁體', 'code2' => 'zh-tw', 'code3' => 'zho-tra', 'flag' => 'cn'),
        797 => array('language_id' => 797, 'title_en' => 'Somali', 'title' => 'Soomaali', 'code2' => 'so', 'code3' => 'som', 'flag' => 'so'),
        798 => array('language_id' => 798, 'title_en' => 'Corsican', 'title' => 'Corsu', 'code2' => 'co', 'code3' => 'cos', 'flag' => 'fr'),
        799 => array('language_id' => 799, 'title_en' => 'Frisian', 'title' => 'Frysk', 'code2' => 'fy', 'code3' => 'fry', 'flag' => 'nl'),
        800 => array('language_id' => 800, 'title_en' => 'Hausa', 'title' => 'Hausa', 'code2' => 'ha', 'code3' => 'hau', 'flag' => 'ng'),
        801 => array('language_id' => 801, 'title_en' => 'Hawaiian', 'title' => 'Ōlelo Hawaiʻi', 'code2' => 'haw', 'code3' => 'haw', 'flag' => 'ha'),
        802 => array('language_id' => 802, 'title_en' => 'Hmong', 'title' => 'Hmong', 'code2' => 'hmn', 'code3' => 'hmn', 'flag' => 'cn'),
        803 => array('language_id' => 803, 'title_en' => 'Igbo', 'title' => 'Igbo', 'code2' => 'ig', 'code3' => 'ibo', 'flag' => 'ng'),
        804 => array('language_id' => 804, 'title_en' => 'Kinyarwanda', 'title' => 'Kinyarwanda', 'code2' => 'rw', 'code3' => 'kin', 'flag' => 'rw'),
        805 => array('language_id' => 805, 'title_en' => 'Kurdish', 'title' => 'Kurdî', 'code2' => 'ku', 'code3' => 'kur', 'flag' => 'tr'),
        806 => array('language_id' => 806, 'title_en' => 'Chichewa', 'title' => 'Chichewa', 'code2' => 'ny', 'code3' => 'nya', 'flag' => 'mw'),
        807 => array('language_id' => 807, 'title_en' => 'Odia', 'title' => 'ଓଡିଆ', 'code2' => 'or', 'code3' => 'ori', 'flag' => 'in'),
        808 => array('language_id' => 808, 'title_en' => 'Samoan', 'title' => 'Faasamoa', 'code2' => 'sm', 'code3' => 'smo', 'flag' => 'ws'),
        809 => array('language_id' => 809, 'title_en' => 'Sesotho', 'title' => 'Sesotho', 'code2' => 'st', 'code3' => 'sot', 'flag' => 'za'),
        810 => array('language_id' => 810, 'title_en' => 'Shona', 'title' => 'Shona', 'code2' => 'sn', 'code3' => 'sna', 'flag' => 'zw'),
        811 => array('language_id' => 811, 'title_en' => 'Sindhi', 'title' => 'سنڌي', 'code2' => 'sd', 'code3' => 'snd', 'flag' => 'pk'),
        812 => array('language_id' => 812, 'title_en' => 'Turkmen', 'title' => 'Türkmenler', 'code2' => 'tk', 'code3' => 'tuk', 'flag' => 'tm'),
        813 => array('language_id' => 813, 'title_en' => 'Uyghur', 'title' => 'ئۇيغۇر', 'code2' => 'ug', 'code3' => 'uig', 'flag' => 'cn'),
        814 => array('language_id' => 814, 'title_en' => 'Yoruba', 'title' => 'Yoruba', 'code2' => 'yo', 'code3' => 'yor', 'flag' => 'ng'),
        815 => array('language_id' => 815, 'title_en' => 'Zulu', 'title' => 'Zulu', 'code2' => 'zu', 'code3' => 'zul', 'flag' => 'za'),
    );

    public $flags = array(
            312 => array( 'flag_id' => 312, 'title' => 'Afghanistan', 'code' => 'af'),
            313 => array( 'flag_id' => 313, 'title' => 'Albania', 'code' => 'al'),
            314 => array( 'flag_id' => 314, 'title' => 'Algeria', 'code' => 'dz'),
            315 => array( 'flag_id' => 315, 'title' => 'Andorra', 'code' => 'ad'),
            316 => array( 'flag_id' => 316, 'title' => 'Angola', 'code' => 'ao'),
            317 => array( 'flag_id' => 317, 'title' => 'Antigua and Barbuda', 'code' => 'ag'),
            318 => array( 'flag_id' => 318, 'title' => 'Argentina', 'code' => 'ar'),
            319 => array( 'flag_id' => 319, 'title' => 'Armenia', 'code' => 'am'),
            320 => array( 'flag_id' => 320, 'title' => 'Australia', 'code' => 'au'),
            321 => array( 'flag_id' => 321, 'title' => 'Austria', 'code' => 'at'),
            322 => array( 'flag_id' => 322, 'title' => 'Azerbaijan', 'code' => 'az'),
            509 => array( 'flag_id' => 322, 'title' => 'Basque', 'code' => 'es-pv'),
            323 => array( 'flag_id' => 323, 'title' => 'Bahamas', 'code' => 'bs'),
            324 => array( 'flag_id' => 324, 'title' => 'Bahrain', 'code' => 'bh'),
            325 => array( 'flag_id' => 325, 'title' => 'Bangladesh', 'code' => 'bd'),
            326 => array( 'flag_id' => 326, 'title' => 'Barbados', 'code' => 'bb'),
            327 => array( 'flag_id' => 327, 'title' => 'Belarus', 'code' => 'by'),
            328 => array( 'flag_id' => 328, 'title' => 'Belgium', 'code' => 'be'),
            329 => array( 'flag_id' => 329, 'title' => 'Belize', 'code' => 'bz'),
            330 => array( 'flag_id' => 330, 'title' => 'Benin', 'code' => 'bj'),
            331 => array( 'flag_id' => 331, 'title' => 'Bhutan', 'code' => 'bt'),
            332 => array( 'flag_id' => 332, 'title' => 'Bulgaria', 'code' => 'bg'),
            333 => array( 'flag_id' => 333, 'title' => 'Bolivia', 'code' => 'bo'),
            334 => array( 'flag_id' => 334, 'title' => 'Bosnia and Herzegovina', 'code' => 'ba'),
            335 => array( 'flag_id' => 335, 'title' => 'Botswana', 'code' => 'bw'),
            336 => array( 'flag_id' => 336, 'title' => 'Brazil', 'code' => 'br'),
            337 => array( 'flag_id' => 337, 'title' => 'Brunei', 'code' => 'bn'),
            338 => array( 'flag_id' => 338, 'title' => 'Burkina Faso', 'code' => 'bf'),
            339 => array( 'flag_id' => 339, 'title' => 'Burundi', 'code' => 'bi'),
            340 => array( 'flag_id' => 340, 'title' => 'Cambodia', 'code' => 'kh'),
            341 => array( 'flag_id' => 341, 'title' => 'Cameroon', 'code' => 'cm'),
            342 => array( 'flag_id' => 342, 'title' => 'Canada', 'code' => 'ca'),
            343 => array( 'flag_id' => 343, 'title' => 'Cape Verde', 'code' => 'cv'),
            344 => array( 'flag_id' => 344, 'title' => 'Central African Republic', 'code' => 'cf'),
            345 => array( 'flag_id' => 345, 'title' => 'Chad', 'code' => 'td'),
            346 => array( 'flag_id' => 346, 'title' => 'Chile', 'code' => 'cl'),
            347 => array( 'flag_id' => 347, 'title' => 'China', 'code' => 'cn'),
            348 => array( 'flag_id' => 348, 'title' => 'Colombia', 'code' => 'co'),
            349 => array( 'flag_id' => 349, 'title' => 'Comoros', 'code' => 'km'),
            350 => array( 'flag_id' => 350, 'title' => 'Congo', 'code' => 'cg'),
            351 => array( 'flag_id' => 351, 'title' => 'Costa Rica', 'code' => 'cr'),
            352 => array( 'flag_id' => 352, 'title' => 'Cote d\'Ivoire', 'code' => 'ci'),
            353 => array( 'flag_id' => 353, 'title' => 'Croatia', 'code' => 'hr'),
            354 => array( 'flag_id' => 354, 'title' => 'Cuba', 'code' => 'cu'),
            355 => array( 'flag_id' => 355, 'title' => 'Cyprus', 'code' => 'cy'),
            356 => array( 'flag_id' => 356, 'title' => 'Czech Republic', 'code' => 'cz'),
            357 => array( 'flag_id' => 357, 'title' => 'Democratic Republic of the Congo', 'code' => 'cd'),
            358 => array( 'flag_id' => 358, 'title' => 'Denmark', 'code' => 'dk'),
            359 => array( 'flag_id' => 359, 'title' => 'Djibouti', 'code' => 'dj'),
            360 => array( 'flag_id' => 360, 'title' => 'Dominica', 'code' => 'dm'),
            361 => array( 'flag_id' => 361, 'title' => 'Dominican Republic', 'code' => 'do'),
            362 => array( 'flag_id' => 362, 'title' => 'Ecuador', 'code' => 'ec'),
            363 => array( 'flag_id' => 363, 'title' => 'Egypt', 'code' => 'eg'),
            364 => array( 'flag_id' => 364, 'title' => 'El Salvador', 'code' => 'sv'),
            365 => array( 'flag_id' => 365, 'title' => 'Equatorial Guinea', 'code' => 'gq'),
            366 => array( 'flag_id' => 366, 'title' => 'Eritrea', 'code' => 'er'),
            367 => array( 'flag_id' => 367, 'title' => 'Estonia', 'code' => 'ee'),
            368 => array( 'flag_id' => 368, 'title' => 'Ethiopia', 'code' => 'et'),
            369 => array( 'flag_id' => 369, 'title' => 'Fiji', 'code' => 'fj'),
            370 => array( 'flag_id' => 370, 'title' => 'Finland', 'code' => 'fi'),
            371 => array( 'flag_id' => 371, 'title' => 'France', 'code' => 'fr'),
            372 => array( 'flag_id' => 372, 'title' => 'Gabon', 'code' => 'ga'),
            373 => array( 'flag_id' => 373, 'title' => 'Gambia', 'code' => 'gm'),
            374 => array( 'flag_id' => 374, 'title' => 'Georgia', 'code' => 'ge'),
            375 => array( 'flag_id' => 375, 'title' => 'Germany', 'code' => 'de'),
            376 => array( 'flag_id' => 376, 'title' => 'Ghana', 'code' => 'gh'),
            377 => array( 'flag_id' => 377, 'title' => 'Greece', 'code' => 'gr'),
            378 => array( 'flag_id' => 378, 'title' => 'Grenada', 'code' => 'gd'),
            379 => array( 'flag_id' => 379, 'title' => 'Guatemala', 'code' => 'gt'),
            380 => array( 'flag_id' => 380, 'title' => 'Guinea', 'code' => 'gn'),
            381 => array( 'flag_id' => 381, 'title' => 'Guinea-Bissau', 'code' => 'gw'),
            382 => array( 'flag_id' => 382, 'title' => 'Guyana', 'code' => 'gy'),
            383 => array( 'flag_id' => 383, 'title' => 'Haiti', 'code' => 'ht'),
            384 => array( 'flag_id' => 384, 'title' => 'Honduras', 'code' => 'hn'),
            385 => array( 'flag_id' => 385, 'title' => 'Hungary ', 'code' => 'hu'),
            386 => array( 'flag_id' => 386, 'title' => 'Iceland', 'code' => 'is'),
            387 => array( 'flag_id' => 387, 'title' => 'India', 'code' => 'in'),
            388 => array( 'flag_id' => 388, 'title' => 'Indonesia', 'code' => 'id'),
            389 => array( 'flag_id' => 389, 'title' => 'Iran', 'code' => 'ir'),
            390 => array( 'flag_id' => 390, 'title' => 'Iraq', 'code' => 'iq'),
            391 => array( 'flag_id' => 391, 'title' => 'Ireland', 'code' => 'ie'),
            392 => array( 'flag_id' => 392, 'title' => 'Israel', 'code' => 'il'),
            393 => array( 'flag_id' => 393, 'title' => 'Italy', 'code' => 'it'),
            394 => array( 'flag_id' => 394, 'title' => 'Jamaica', 'code' => 'jm'),
            395 => array( 'flag_id' => 395, 'title' => 'Japan', 'code' => 'jp'),
            396 => array( 'flag_id' => 396, 'title' => 'Jordan', 'code' => 'jo'),
            397 => array( 'flag_id' => 397, 'title' => 'Kazakhstan', 'code' => 'kz'),
            398 => array( 'flag_id' => 398, 'title' => 'Kenya', 'code' => 'ke'),
            399 => array( 'flag_id' => 399, 'title' => 'Kiribati', 'code' => 'ki'),
            400 => array( 'flag_id' => 400, 'title' => 'Kosova', 'code' => 'xk'),
            401 => array( 'flag_id' => 401, 'title' => 'Kuwait', 'code' => 'kw'),
            402 => array( 'flag_id' => 402, 'title' => 'Kyrgyzstan', 'code' => 'kg'),
            403 => array( 'flag_id' => 403, 'title' => 'Laos', 'code' => 'la'),
            404 => array( 'flag_id' => 404, 'title' => 'Latvia', 'code' => 'lv'),
            405 => array( 'flag_id' => 405, 'title' => 'Lebanon', 'code' => 'lb'),
            406 => array( 'flag_id' => 406, 'title' => 'Lesotho', 'code' => 'ls'),
            407 => array( 'flag_id' => 407, 'title' => 'Liberia', 'code' => 'lr'),
            408 => array( 'flag_id' => 408, 'title' => 'Libya', 'code' => 'ly'),
            409 => array( 'flag_id' => 409, 'title' => 'Liechtenstein', 'code' => 'li'),
            410 => array( 'flag_id' => 410, 'title' => 'Lithuania', 'code' => 'lt'),
            411 => array( 'flag_id' => 411, 'title' => 'Luxembourg', 'code' => 'lu'),
            412 => array( 'flag_id' => 412, 'title' => 'Macedonia', 'code' => 'mk'),
            413 => array( 'flag_id' => 413, 'title' => 'Madagascar', 'code' => 'mg'),
            414 => array( 'flag_id' => 414, 'title' => 'Malawi', 'code' => 'mw'),
            415 => array( 'flag_id' => 415, 'title' => 'Malaysia', 'code' => 'my'),
            416 => array( 'flag_id' => 416, 'title' => 'Maldives', 'code' => 'mv'),
            417 => array( 'flag_id' => 417, 'title' => 'Mali', 'code' => 'ml'),
            418 => array( 'flag_id' => 418, 'title' => 'Malta', 'code' => 'mt'),
            419 => array( 'flag_id' => 419, 'title' => 'Marshall Islands', 'code' => 'mh'),
            420 => array( 'flag_id' => 420, 'title' => 'Mauritania', 'code' => 'mr'),
            421 => array( 'flag_id' => 421, 'title' => 'Mauritius', 'code' => 'mu'),
            422 => array( 'flag_id' => 422, 'title' => 'Mexico', 'code' => 'mx'),
            423 => array( 'flag_id' => 423, 'title' => 'Micronesia', 'code' => 'fm'),
            424 => array( 'flag_id' => 424, 'title' => 'Moldova', 'code' => 'md'),
            425 => array( 'flag_id' => 425, 'title' => 'Monaco', 'code' => 'mc'),
            426 => array( 'flag_id' => 426, 'title' => 'Mongolia', 'code' => 'mn'),
            427 => array( 'flag_id' => 427, 'title' => 'Montenegro', 'code' => 'me'),
            428 => array( 'flag_id' => 428, 'title' => 'Morocco', 'code' => 'ma'),
            429 => array( 'flag_id' => 429, 'title' => 'Mozambique', 'code' => 'mz'),
            430 => array( 'flag_id' => 430, 'title' => 'Myanmar ', 'code' => 'mm'),
            431 => array( 'flag_id' => 431, 'title' => 'Namibia', 'code' => 'na'),
            432 => array( 'flag_id' => 432, 'title' => 'Nauru', 'code' => 'nr'),
            433 => array( 'flag_id' => 433, 'title' => 'Nepal', 'code' => 'np'),
            434 => array( 'flag_id' => 434, 'title' => 'Netherlands', 'code' => 'nl'),
            435 => array( 'flag_id' => 435, 'title' => 'New Zealand', 'code' => 'nz'),
            436 => array( 'flag_id' => 436, 'title' => 'Nicaragua', 'code' => 'ni'),
            437 => array( 'flag_id' => 437, 'title' => 'Niger', 'code' => 'ne'),
            438 => array( 'flag_id' => 438, 'title' => 'Nigeria', 'code' => 'ng'),
            439 => array( 'flag_id' => 439, 'title' => 'North Korea', 'code' => 'kp'),
            440 => array( 'flag_id' => 440, 'title' => 'Norvay', 'code' => 'no'),
            441 => array( 'flag_id' => 441, 'title' => 'Oman', 'code' => 'om'),
            442 => array( 'flag_id' => 442, 'title' => 'Pakistan', 'code' => 'pk'),
            443 => array( 'flag_id' => 443, 'title' => 'Palau', 'code' => 'pw'),
            444 => array( 'flag_id' => 444, 'title' => 'Panama', 'code' => 'pa'),
            445 => array( 'flag_id' => 445, 'title' => 'Papua New Guinea', 'code' => 'pg'),
            446 => array( 'flag_id' => 446, 'title' => 'Paraguay', 'code' => 'py'),
            447 => array( 'flag_id' => 447, 'title' => 'Peru', 'code' => 'pe'),
            448 => array( 'flag_id' => 448, 'title' => 'Philippines', 'code' => 'ph'),
            449 => array( 'flag_id' => 449, 'title' => 'Poland ', 'code' => 'pl'),
            450 => array( 'flag_id' => 450, 'title' => 'Portugal', 'code' => 'pt'),
            451 => array( 'flag_id' => 451, 'title' => 'Qatar', 'code' => 'qa'),
            452 => array( 'flag_id' => 452, 'title' => 'Romania', 'code' => 'ro'),
            453 => array( 'flag_id' => 453, 'title' => 'Russia', 'code' => 'ru'),
            454 => array( 'flag_id' => 454, 'title' => 'Rwanda', 'code' => 'rw'),
            455 => array( 'flag_id' => 455, 'title' => 'Saint Kitts and Nevis', 'code' => 'kn'),
            456 => array( 'flag_id' => 456, 'title' => 'Saint Lucia', 'code' => 'lc'),
            457 => array( 'flag_id' => 457, 'title' => 'Saint Vincent and the Grenadines', 'code' => 'vc'),
            458 => array( 'flag_id' => 458, 'title' => 'Samoa', 'code' => 'ws'),
            459 => array( 'flag_id' => 459, 'title' => 'San Marino', 'code' => 'sm'),
            460 => array( 'flag_id' => 460, 'title' => 'Sao Tome and Principe', 'code' => 'st'),
            461 => array( 'flag_id' => 461, 'title' => 'Saudi Arabia', 'code' => 'sa'),
            462 => array( 'flag_id' => 462, 'title' => 'Senegal', 'code' => 'sn'),
            463 => array( 'flag_id' => 463, 'title' => 'Serbia', 'code' => 'rs'),
            464 => array( 'flag_id' => 464, 'title' => 'Seychelles', 'code' => 'sc'),
            465 => array( 'flag_id' => 465, 'title' => 'Sierra Leone', 'code' => 'sl'),
            466 => array( 'flag_id' => 466, 'title' => 'Singapore', 'code' => 'sg'),
            467 => array( 'flag_id' => 467, 'title' => 'Slovakia', 'code' => 'sk'),
            468 => array( 'flag_id' => 468, 'title' => 'Slovenia', 'code' => 'si'),
            469 => array( 'flag_id' => 469, 'title' => 'Solomon Islands', 'code' => 'sb'),
            470 => array( 'flag_id' => 470, 'title' => 'Somalia', 'code' => 'so'),
            471 => array( 'flag_id' => 471, 'title' => 'South Africa', 'code' => 'za'),
            472 => array( 'flag_id' => 472, 'title' => 'South Korea', 'code' => 'kr'),
            473 => array( 'flag_id' => 473, 'title' => 'South Sudan', 'code' => 'ss'),
            474 => array( 'flag_id' => 474, 'title' => 'Spain', 'code' => 'es'),
            475 => array( 'flag_id' => 475, 'title' => 'Sri Lanka', 'code' => 'lk'),
            476 => array( 'flag_id' => 476, 'title' => 'Sudan', 'code' => 'sd'),
            477 => array( 'flag_id' => 477, 'title' => 'Suriname', 'code' => 'sr'),
            478 => array( 'flag_id' => 478, 'title' => 'Swaziland', 'code' => 'sz'),
            479 => array( 'flag_id' => 479, 'title' => 'Sweden', 'code' => 'se'),
            480 => array( 'flag_id' => 480, 'title' => 'Switzerland', 'code' => 'ch'),
            481 => array( 'flag_id' => 481, 'title' => 'Syria', 'code' => 'sy'),
            // 482 => array( 'flag_id' => 482, 'title' => 'Taiwan', 'code' => 'Rg9'),
            482 => array( 'flag_id' => 482, 'title' => 'Taiwan', 'code' => 'tw'),
            483 => array( 'flag_id' => 483, 'title' => 'Tajikistan', 'code' => 'tj'),
            484 => array( 'flag_id' => 484, 'title' => 'Tanzania', 'code' => 'tz'),
            485 => array( 'flag_id' => 485, 'title' => 'Thailand', 'code' => 'th'),
            486 => array( 'flag_id' => 486, 'title' => 'Timor-Leste', 'code' => 'tl'),
            487 => array( 'flag_id' => 487, 'title' => 'Togo', 'code' => 'tg'),
            488 => array( 'flag_id' => 488, 'title' => 'Tonga', 'code' => 'to'),
            489 => array( 'flag_id' => 489, 'title' => 'Trinidad and Tobago', 'code' => 'tt'),
            490 => array( 'flag_id' => 490, 'title' => 'Tunisia', 'code' => 'tn'),
            491 => array( 'flag_id' => 491, 'title' => 'Turkey', 'code' => 'tr'),
            492 => array( 'flag_id' => 492, 'title' => 'Turkmenistan', 'code' => 'tm'),
            493 => array( 'flag_id' => 493, 'title' => 'Tuvalu', 'code' => 'tv'),
            494 => array( 'flag_id' => 494, 'title' => 'Uganda', 'code' => 'ug'),
            495 => array( 'flag_id' => 495, 'title' => 'Ukraine', 'code' => 'ua'),
            496 => array( 'flag_id' => 496, 'title' => 'United Arab Emirates', 'code' => 'ae'),
            497 => array( 'flag_id' => 497, 'title' => 'United Kingdom', 'code' => 'gb'),
            498 => array( 'flag_id' => 498, 'title' => 'United States of America', 'code' => 'us'),
            499 => array( 'flag_id' => 499, 'title' => 'Uruguay', 'code' => 'uy'),
            500 => array( 'flag_id' => 500, 'title' => 'Uzbekistan', 'code' => 'uz'),
            501 => array( 'flag_id' => 501, 'title' => 'Vanuatu', 'code' => 'vu'),
            502 => array( 'flag_id' => 502, 'title' => 'Vatican City', 'code' => 'va'),
            503 => array( 'flag_id' => 503, 'title' => 'Venezuela', 'code' => 've'),
            504 => array( 'flag_id' => 504, 'title' => 'Vietnam', 'code' => 'vn'),
            505 => array( 'flag_id' => 505, 'title' => 'Yemen', 'code' => 'ye'),
            506 => array( 'flag_id' => 506, 'title' => 'Zambia', 'code' => 'zm'),
            507 => array( 'flag_id' => 507, 'title' => 'Zimbabwe', 'code' => 'zw'),
            508 => array( 'flag_id' => 508, 'title' => 'Hong Kong', 'code' => 'hk'),
            511 => array( 'flag_id' => 511, 'title' => 'Hawaii', 'code' => 'ha'),
        );

    public $widgetStyles = [
        'dropdown' => 'Dropdown',
        'list' => 'Vertical list ',
        'popup' => 'Popup'
    ];

    public $wp_patterns = [
        "/\/sitemap\b/",
        "/\/roboto\b/",
        "/\/wp-admin\b/",
        "/\/wp-content\b/",
        "/\/wp-includes\b/",
        "/\/wp-json\b/",
        "/wp-login\.php\b/",
        "/wp-cron\.php\b/",
        "/wp-signup\.php\b/",
        "/wp-activate\.php\b/",
        "/wp-mail\.php\b/",
        "/wp-load\.php\b/",
        "/wp-blog-header\.php\b/",
        "/wp-links-opml\.php\b/",
        "/wp-trackback\.php\b/",
        "/wp-comments-post\.php\b/",
        "/\.(zip|tar\.gz|tar\.bz2|\.xml|\.txt)\b/"
    ];

    public $query_params_block = ['msclkid', 'utm_source', 'locale', 'link', 'affiliateID', 'fbclid'];

    public $menu = [
        'Main configuration' => [
            'tag' => 'main',
            'active' => true, // Select default
            'widget_preview' => true, // Visible widget on page
            'status' => true // On\Off item menu
        ],
        'Extended settings' => ['tag' => 'general', 'active' => false, 'widget_preview' => false, 'status' => true],
        'Widget Style' => ['tag' => 'widget', 'active' => false, 'widget_preview' => true, 'status' => true],
        'Block pages' => ['tag' => 'block', 'active' => false, 'widget_preview' => false, 'status' => true],
        'Glossary' => ['tag' => 'glossary', 'active' => false, 'widget_preview' => false, 'status' => true],
        'Links' => ['tag' => 'links', 'active' => false, 'widget_preview' => false, 'status' => true]
    ];

    public function __construct() {
        $this->api_key = get_option( 'api_key' );
        $this->new_user = get_option( 'conveythis_new_user' );
        $this->source_language = get_option( 'source_language' );
        $this->target_languages = get_option( 'target_languages', array() );
        $this->default_language = get_option( 'default_language' );
        $this->target_languages_translations = get_option( 'target_languages_translations', array() );
        $this->target_languages_translations = $this->target_languages_translations ? json_decode($this->target_languages_translations, true) : array();
        $this->style_change_language = get_option( 'style_change_language', array() );
        $this->style_change_flag = get_option( 'style_change_flag', array() );
        $this->style_flag = get_option( 'style_flag', 'rect' );
        $this->style_text = get_option( 'style_text', 'full-text' );
        $this->style_position_vertical = get_option( 'style_position_vertical', 'top' );
        $this->style_position_horizontal = get_option( 'style_position_horizontal', 'left' );
        $this->style_indenting_vertical = get_option( 'style_indenting_vertical', '12' );
        $this->style_indenting_horizontal = get_option( 'style_indenting_horizontal', '24' );
        $this->auto_translate = get_option( 'auto_translate', '1' );
        $this->select_region = get_option( 'conveythis_select_region', 'US' );
        $this->hide_conveythis_logo = get_option( 'hide_conveythis_logo', '0' );
        $this->translate_media = get_option( 'translate_media', '0' );
        $this->translate_document = get_option( 'translate_document', '0' );
        $this->translate_links = get_option( 'translate_links', '0' );
        $this->change_direction = get_option( 'change_direction', '0' );
        $this->alternate = get_option( 'alternate', '1' );
        $this->accept_language = get_option( 'accept_language', '0' );
        $this->blockpages = get_option( 'blockpages', array() );
        $this->show_javascript = get_option( 'show_javascript', '1' );
        $this->lang_code_url = get_option( 'conveythis_lang_code_url', '1' );
        $this->clear_cache = get_option( 'conveythis_clear_cache', '0' );



        $this->style_position_type = get_option( 'style_position_type', 'fixed' );
        $this->style_position_vertical_custom = get_option( 'style_position_vertical_custom', 'bottom' );
        $this->style_selector_id = get_option( 'style_selector_id', '' );

        $this->url_structure = get_option( 'url_structure', 'regular' );

        $this->style_background_color = get_option( 'style_background_color', '#ffffff' );
        $this->style_hover_color = get_option( 'style_hover_color', '#f6f6f6' );
        $this->style_border_color = get_option( 'style_border_color', '#e0e0e0' );
        $this->style_text_color = get_option( 'style_text_color', '#000000' );
        $this->style_corner_type = get_option( 'style_corner_type', 'cir' );
        $this->style_widget = get_option( 'style_widget', 'dropdown' );

        $this->system_links = get_option( 'conveythis_system_links', array() );
        $this->system_links = $this->system_links ? json_decode($this->system_links, true) : array();

    }


}