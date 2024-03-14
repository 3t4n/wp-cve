<?php

/*

Plugin Name: Quran Multilanguage Text Audio Verse

Description: Quran Text Multilanguage translated into 29 languages. Full ajax version and responsive. Fully customizable. More reciter...

Version: 2.3.20

Author: Bahmed karim

Author URI: https://gpcodex.fr

*/

defined( 'ABSPATH' ) or die( 'Salem aleykoum!' );


define( 'QURAN__PLUGIN_URL', plugin_dir_url( __FILE__ ) );

add_action('admin_menu' , 'qtm_quranadmin');


function qtm_quranadmin(){

	add_menu_page('Quran', 'Quran', 'activate_plugins', 'qtm_quranadmin', 'qtm_renderquranadmin', ''.QURAN__PLUGIN_URL . 'icon_quran.png', 3); 

	add_action( 'admin_init', 'qtm_registeroptions' );	

}

function qtm_renderquranadmin(){

include('admin/quran-admin.php');

}


//COPY TITLE SURA IN BDD

function qtm_quraninstall(){



global $wpdb;



   $sql = "

CREATE TABLE IF NOT EXISTS `quran` (

  `id` int(11) NOT NULL AUTO_INCREMENT,

  `nom` varchar(250) NOT NULL,

  `nom_id` int(11) NOT NULL,

  `url` varchar(250) NOT NULL,

  PRIMARY KEY (`id`)

) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=115 ;

INSERT INTO `quran` (`id`, `nom`, `nom_id`, `url`) VALUES

(1, '002. Al-Baqarah', 2, 'al-baqarah'),

(2, '004. An-Nisa', 4, 'an-nisa'),

(3, '005. Al-Maidah', 5, 'al-maidah'),

(4, '006. Al-Anam', 6, 'al-anam'),

(5, '007. Al-Araf', 7, 'al-araf'),

(6, '008. Al-Anfal', 8, 'al-anfal'),

(7, '009. At-Tawbah', 9, 'at-tawbah'),

(8, '010. Yunes', 10, 'yunes'),

(9, '011. Hud', 11, 'hud'),

(10, '012. Youssouf', 12, 'youssouf'),

(11, '013. Ar-Rad', 13, 'ar-rad'),

(12, '014. Ibrahim', 14, 'ibrahim'),

(13, '015. Al-Hijr', 15, 'al-hijr'),

(14, '016. An-Nahl', 16, 'an-nahl'),

(15, '017. Al-Isra', 17, 'al-isra'),

(16, '018. Al-Kahf', 18, 'al-kahf'),

(17, '019. Maryam', 19, 'maryam'),

(18, '020. Ta-Ha', 20, 'ta-ha'),

(19, '021. Al-Anbiya', 21, 'al-anbiya'),

(20, '022. Al-Hajj', 22, 'al-hajj'),

(21, '023. Al-Mouminoune', 23, 'al-mouminoune'),

(22, '024. An-Nour', 24, 'an-nour'),

(23, '025. Al-Furqane', 25, 'al-furqane'),

(24, '026. Ash-Shuara', 26, 'ash-shuara'),

(25, '027. An-Naml', 27, 'an-naml'),

(26, '028. Al-Qasas', 28, 'al-qasas'),

(27, '030. Ar-Rum', 30, 'ar-rum'),

(28, '031. Luqman', 31, 'luqman'),

(29, '032. As-Sajda', 32, 'as-sajda'),

(30, '033. Al-Ahzab', 33, 'al-ahzab'),

(31, '034. Saba', 34, 'saba'),

(32, '035. Fatir', 35, 'fatir'),

(33, '036. Ya-Sin', 36, 'ya-sin'),

(34, '037. As-Saffat', 37, 'as-saffat'),

(35, '038. Sad', 38, 'sad'),

(36, '039. Az-Zumar', 39, 'az-zumar'),

(37, '040. Ghafir', 40, 'ghafir'),

(38, '041. Fussilat', 41, 'fussilat'),

(39, '042. Ash-shoura', 42, 'ash-shoura'),

(40, '044. Ad-Dukhan', 44, 'ad-dukhan'),

(41, '046. Al-Ahqaf', 46, 'al-ahqaf'),

(42, '047. Muhammad', 47, 'muhammad'),

(43, '048. Al-Fath', 48, 'al-fath'),

(44, '049. Al. Hujurat', 49, 'al-hujurat'),

(45, '050. Qaf', 50, 'qaf'),

(46, '051. Ad-Dariyat', 51, 'ad-dariyat'),

(47, '052. At-Tur', 52, 'at-tur'),

(48, '054. Al-Qamar', 54, 'al-qamar'),

(49, '055. Ar-Rahman', 55, 'ar-rahman'),

(50, '057. Al-Hadid - le fer', 57, 'al-hadid'),

(51, '058. Al-Mujadalah', 58, 'al-mujadalah'),

(52, '059. Al-Hashr', 59, 'al-hashr'),

(53, '061. As-Saff', 61, 'as-saff'),

(54, '062. Al-Jumua', 62, 'al-jumua'),

(55, '063. Al-Munafiqun', 63, 'al-munafiqun'),

(56, '064. At-Tagabun', 64, 'at-tagabun'),

(57, '065. At-Talaq', 65, 'at-talaq'),

(58, '067. Al-Mulk', 67, 'al-mulk'),

(59, '068. Al-Qalam', 68, 'al-qalam'),

(60, '069. Al-Haqqah', 69, 'al-haqqah'),

(61, '070. Al-Ma arij', 70, 'al-ma-arij'),

(62, '071. Nuh', 71, 'nuh-noe'),

(63, '072. Al-Jinn', 72, 'al-jinn'),

(64, '073. Al-Muzzammil', 73, 'al-muzzammil'),

(65, '074. Al-Muddattir', 74, 'al-muddattir'),

(66, '075. Al-Qiyamah', 75, 'al-qiyamah'),

(67, '076. Al-Insan', 76, 'al-insan'),

(68, '077. Al-Mursalate', 77, 'al-mursalate'),

(69, '078. An-Naba', 78, 'an-naba'),

(70, '079. An-Naziate', 79, 'an-naziate'),

(71, '082. Al-Infitar', 82, 'al-infitar'),

(72, '083. Al-Mutaffifine', 83, 'al-mutaffifine'),

(73, '084. Al-Inshiqaq', 84, 'al-inshiqaq'),

(74, '085. Al-Buraj', 85, 'al-buraj'),

(75, '087. Al-Ala', 87, 'al-ala'),

(76, '089. Al-Fajr', 89, 'al-fajr'),

(77, '090. Al-Balad', 90, 'al-balad'),

(78, '091. Ash-Shams', 91, 'ash-shams'),

(79, '092. Al-Layl', 92, 'al-layl'),

(80, '093. Ad-Duha', 93, 'ad-duha'),

(81, '095. At-Tin', 95, 'at-tin'),

(82, '097. Al-Qadr', 97, 'al-qadr'),

(83, '098. Al-Bayyinah', 98, 'al-bayyinah'),

(84, '099. Az-Zalzalah', 99, 'az-zalzalah'),

(85, '100. Al-Adiyate', 100, 'al-adiyate'),

(86, '101. Al-Qariah', 101, 'al-qariah'),

(87, '102. At-Takathur', 102, 'at-takafur'),

(88, '103. Al-Asr', 103, 'al-asr'),

(89, '104. Al-Humazah ', 104, 'al-humazah'),

(90, '105. Al-Fil', 105, 'al-fil'),

(91, '106. Quraysh', 106, 'quraysh'),

(92, '109. Al-Kafiroune', 109, 'al-kafiroune'),

(93, '110. An-Nasr', 110, 'an-nasr'),

(94, '111. Al-Masad', 111, 'al-masad'),

(95, '112. Al-Ikhlas', 112, 'al-ikhlas'),

(96, '113. Al-Falaq', 113, 'al-falaq'),

(97, '114. An-Nass', 114, 'an-nass'),

(98, '001. Al-Fatiha', 1, 'al-fatiha'),

(99, '003. Al-Imran', 3, 'al-imran'),

(100, '096. AL-ALAQ', 96, 'al-alaq'),

(101, '056. AL-WAQI', 56, 'al-waqi'),

(102, '043. AZZUKHRUF', 43, 'azzukhruf'),

(103, '045. AL-JATHYA', 45, 'al-jathya'),

(104, '053. AN-NAJM ', 53, 'an-najm'),

(105, '060. AL-MUMTAHANAH', 60, 'al-mumtahanah'),

(106, '066. AT-TAHRIM', 66, 'at-tahrim'),

(107, '080. ABASA', 80, 'abasa'),

(108, '081. AT-TAKWIR', 81, 'at-takwir'),

(109, '094. AS-SARH', 94, 'as-sarh'),

(110, '107. AL-MAUN', 107, 'al-maun'),

(111, '108. AL-KAWTAR', 108, 'al-kawtar'),

(112, '029. AL-ANKABUT', 29, 'al-ankabut'),

(113, '088. AL-GASIYAH', 88, 'al-gasiyah'),

(114, '086. AT-TARIQ', 86, 'at-tariq');

";



require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

dbDelta( $sql );

//DEFAUT OPTIONS COLORS/TEXT
add_option( 'quran_changerecitatortxt', 'Choose récitator', '', 'yes' );

add_option( 'quran_template', 'enable', '', 'yes' );

add_option( 'quran_changesuratxt', 'Choose Sura', '', 'yes' );

add_option( 'quran_changelangtxt', 'Choose Language', '', 'yes' );

add_option( 'quran_languages', 'english', '', 'yes' );

add_option( 'text_quran_title', '000000', '', 'yes' );

add_option( 'background_quran_title', 'EFF0F0', '', 'yes' );

add_option( 'verse_quran_number', '000000', '', 'yes' );

add_option( 'text_quran_trans', '000000', '', 'yes' );

add_option( 'background_quran_trans', 'FFFFFF', '', 'yes' );

add_option( 'text_quran_arabic', '000000', '', 'yes' );

add_option( 'background_quran_arabic', 'EFF0F0', '', 'yes' );

add_option( 'background_quran_number', '337ab7', '', 'yes' );

add_option( 'color_quran_number', 'FFFFFF', '', 'yes' );

add_option('quran_recitator', 'Maher_al_me-aqly', '', 'yes');

}



function qtm_quranuninstall(){


	delete_option('quran_changerecitatortxt');

	delete_option('quran_changelangtxt');
	
	delete_option('quran_changesuratxt');

	delete_option('quran_template');	

	delete_option('quran_recitator');	

	delete_option('quran_languages');

	delete_option('text_quran_title');

	delete_option('background_quran_title');

	delete_option('verse_quran_number');

	delete_option('text_quran_trans');

	delete_option('background_quran_trans');

	delete_option('text_quran_arabic');

	delete_option('background_quran_arabic');	

	delete_option('background_quran_number');		

	delete_option('color_quran_number');	

	// delete transients

	delete_transient('quran-options');

	

	global $wpdb;

	$table_name = 'quran';

	$wpdb->query("DROP TABLE IF EXISTS {$table_name}");


}


//ACTIVATION PLUGIN INSTALL

register_activation_hook(__FILE__,'qtm_quraninstall'); 

//DELETE PLUGIN

register_uninstall_hook(__FILE__, 'qtm_quranuninstall'); 

//SCRIPTS DU PLUGIN

function qtm_quranscripts(){


    wp_register_script('quran_admin_colors',plugin_dir_url( __FILE__ ).'admin/js/jscolor/jscolor.js');	
    wp_enqueue_script('quran_admin_colors');


}

add_action('wp_enqueue_scripts','qtm_quranscripts'); 



function qtm_registeroptions() {


	register_setting( 'quran-options', 'quran_changerecitatortxt');

	register_setting( 'quran-options', 'quran_template');

	register_setting( 'quran-options', 'quran_changesuratxt');

	register_setting( 'quran-options', 'quran_changelangtxt');

	register_setting( 'quran-options', 'quran_recitator');

	register_setting( 'quran-options', 'quran_languages' );

	register_setting( 'quran-options', 'text_quran_title' );	

	register_setting( 'quran-options', 'background_quran_title' );	

	register_setting( 'quran-options', 'verse_quran_number' );	

	register_setting( 'quran-options', 'text_quran_trans' );	

	register_setting( 'quran-options', 'background_quran_trans' );

	register_setting( 'quran-options', 'color_quran_number' );	

	register_setting( 'quran-options', 'background_quran_number' );	

	register_setting( 'quran-options', 'background_quran_trans' );

	register_setting( 'quran-options', 'text_quran_arabic' );	

	register_setting( 'quran-options', 'background_quran_arabic' );

} 

function quran_options(){
if(is_admin()){
     include('admin/quran-admin.php');
}

} 

//LOAD JS FILE
function add_js_scripts() {
	wp_enqueue_script( 'loadquran', plugin_dir_url(__FILE__).'/js/load_sura.js', array('jquery'), '1.0', true );
	wp_enqueue_script('msdropdownddjs',plugin_dir_url( __FILE__ ).'js/jquery.dd.js', array('jquery'), '1.0', true);  
	wp_localize_script('loadquran', 'ajaxurl', admin_url( 'admin-ajax.php' ) );

   $handle = 'quran_soundmanager';
   $list = 'enqueued';
     if (wp_script_is( $handle, $list )) {
       return;
     } else {
       wp_enqueue_script( 'quran_soundmanager', plugin_dir_url(__FILE__).'js/soundmanager.js', array('jquery'), '1.0');
     }
    wp_enqueue_script( 'sm2player', plugin_dir_url(__FILE__).'js/player.js');
}


add_action('wp_enqueue_scripts', 'add_js_scripts');

//LOAD STYLE MENU

function add_css_menu(){

	wp_enqueue_style('msdropdownddcss',plugin_dir_url( __FILE__ ).'css/msdropdown/dd.css');

}

add_action('wp_enqueue_scripts', 'add_css_menu');

	
add_action( 'wp_ajax_qtm_changesura', 'qtm_changesura' );
add_action( 'wp_ajax_nopriv_qtm_changesura', 'qtm_changesura' );
add_action( 'wp_ajax_qtm_changelanguage', 'qtm_changelanguage' );
add_action( 'wp_ajax_nopriv_qtm_changelanguage', 'qtm_changelanguage' );
add_action( 'wp_ajax_qtm_changeprevsura', 'qtm_changeprevsura' );
add_action( 'wp_ajax_nopriv_qtm_changeprevsura', 'qtm_changeprevsura' );
add_action( 'wp_ajax_qtm_changenextsura', 'qtm_changenextsura' );
add_action( 'wp_ajax_nopriv_qtm_changenextsura', 'qtm_changenextsura' );



require('inc/functions_quran.php');


function qtm_renderquran(){
	
require('inc/quran_style.php');

require('inc/template.php');
init_quran();

?>



  <script language="javascript">
jQuery(document).ready(function(e) {

try {
jQuery("#select_language,#change_sura").msDropDown();
} catch(e) {
alert(e.message);
}
});
</script>


<?php

}

add_shortcode('quran', 'quran_shortcode');

function quran_shortcode() {

	return qtm_renderquran();

}