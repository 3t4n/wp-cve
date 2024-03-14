<?php
defined( 'ABSPATH' ) or die( 'Salem aleykoum!' );
?>



<style>



@media only screen and (max-width: 760px), (min-width: 768px) and (max-width: 1024px) {

.playsura_kb{right:5px !important;}	
#recitator2_kb{float:none !important;width:100% !important;}
.setting_kb{display:none;}
.download_kb{display:none;}
#recitator_download_kb{display:none;}
#bloc_name_sura{width:100% !important;}
#autoplay_quran{display:none;}
#pause_quran{display:none;position:absolute;right: 0 !important;left: auto !important;width: 20px !important;cursor:pointer;margin-top:auto !important;}
#play_quran{display:none;position:absolute;right: 0 !important;left: auto !important;width: 20px !important;cursor:pointer;margin-top:auto !important;}

#PrevSourate{width:25px !important;}
#NextSourate{width:25px !important;}
    .aya1 {
        width: 100% !important;
		margin-bottom:10px;
		background:#F2F3F3;
		font-size:14px !important;
    }
 #play_select_quran{display:none !important;}
	#change_sura_msdd{width:100% !important;margin-top:0 !important;}
	#change_sura_child ul{ margin:0 !important;}
	#select_language_msdd{width:100% !important;}
	#kb_select_quran{width:100% !important;margin-top:40px;}
	#kb_select_language{width:100% !important;}
	#select_languages{
	z-index: 1000;
	margin-top:0 !important;
	margin-bottom:20px !important;
	float:none !important;
	font-size:14px !important;
	width:100% !important;
	}
	#result{margin-top:5px;}
	.h-g_template {display:none;}
	.h-d_template {display:none;}
	.top_template {display:none;}
	.left_template {display:none;}
	.right_template {display:none;}
	.bottom_template {display:none;}
	.b-g_template {display:none;}
	.b-d_template{display:none;}
#quran_main{width:99% !important;margin-top:-50px !important;}
#sourateName{font-size:18px !important;}
#NextSourate{width:35px !important;margin:margin:5px !important;}
#PrevSourate{width:35px !important;}
.lcs_label{display:none;}
.lcs_cursor{height:14px !important;}
#audio_sura audio{width:96% !important;margin-bottom: 1rem !important;}
#dl_sura{
display:none;
}
#recitator_quran{margin-top:-35px !important;}
.lcs_switch.lcs_on .lcs_cursor {
    left: 24px !important;
}
#.quranbadge{display:none;}
.params1_kb{height:80px !important;}
#playeraya2 audio{margin-top:6px !important;}
#playeraya audio{margin-top:42px !important;}
#select_aya {width: 67% !important;position: absolute;margin-left: 116px;}

#change_sura_title img{
width:20px !important;
}
#recitator_kb {position: absolute;width:27% !important;}
/*FIN CSS MOBILE */
}
.kb-speaker{
	width:15px;
	position:absolute;
	right: 8px;
}
#pause_quran{display:none;position:absolute;margin-left: auto;margin-right: auto;left: 0;right: 0;width: 30px;margin-top: 25px;cursor:pointer;}
#play_quran{display:none;position:absolute;margin-left: auto;margin-right: auto;left: 0;right: 0;width: 30px;margin-top: 25px;cursor:pointer;}

#bloc_top_quran{width: 100%;}
#li_quran{
	list-style-type: none;
}

#select_name_recitator{
color:#000 !important;
width:70%;
}

@font-face {
    font-family: "noorehira";
    src: url('<?php echo plugin_dir_url(__FILE__); ?>/font/noorehira.ttf');
}
@font-face {
    font-family: "uthmanic";
    src: url('<?php echo plugin_dir_url(__FILE__); ?>/font/uthmanic.otf');
}
@font-face {
    font-family: "goldenlotus";
    src: url('<?php echo plugin_dir_url(__FILE__); ?>/font/goldenlotus.ttf');
}
@font-face {
    font-family: "swer_quran";
    src: url('<?php echo plugin_dir_url(__FILE__); ?>/font/swer_quran.ttf');
}
@font-face {
    font-family: 'quran';
    src: url('<?php echo plugin_dir_url(__FILE__); ?>/font/quran.woff2') format('woff2');
    font-display: swap;
  }

.quran {font-family:<?=get_option('quran_arabicfont');?> !important;word-spacing:<?=get_option('quran_wordspacing');?>px;}

<?php echo get_option('quran_custum_css'); ?>

#change_sura_child ul{ margin:0 !important;}
#select_language_child ul{ margin:0 !important;}
	.suraName {
		border-bottom: 1px solid #<?php echo get_option('background_quran_title'); ?>;text-align: center; font-size: 20px; padding: 10px 0px; background-color: #<?php echo get_option('background_quran_title'); ?>; margin-top: 7px;color:#<?php echo get_option('text_quran_title'); ?>;}


#li_quran {
    display:none;
}
	.aya {margin:auto;background-color: #fff;border: 1px solid #EFF0F0; border-top: 0px;}



	.aya2 {font-size:16px;color:#797979;}



	.aya1 {margin-top:20px;font-size:1.27em;}	

	#change_sura_msdd{width:100% !important;}

	#select_language_msdd{width:100% !important;}
    

	.quran {line-height: 1.7em; padding: 10px;color:#<?php echo get_option('text_quran_arabic'); ?>;border-right: 1px solid #<?php echo get_option('background_quran_arabic'); ?>;border-left: 1px solid #<?php echo get_option('background_quran_arabic'); ?>; font-size: 28px; direction: rtl;background-color:#<?php  echo get_option('background_quran_arabic');?>}



	.trans { padding:10px;font-family: Calibri;text-align:justify;border-right: 1px solid #<?php echo get_option('background_quran_trans'); ?>;border-left: 1px solid #<?php echo get_option('background_quran_trans'); ?>;border-bottom: 1px solid #<?php echo get_option('background_quran_trans');?>;border-top: 1px solid #<?php echo get_option('background_quran_trans'); ?>; color:#<?php echo get_option('text_quran_trans'); ?>;font-size: 16px; background-color: #<?php echo get_option('background_quran_trans'); ?>;}



	.tabSura{position:relative;width:auto;}



	.ayaNum{color:#<?php echo get_option('color_quran_number'); ?>;background-color:#<?php echo get_option('background_quran_number');?>}



	#template_quran{
<?php 
if(get_option('quran_template') == "disabled"){

 echo "border: 1px  #".get_option('border_quran_color')."  solid;";

}
?>


border-radius: 5px;

		position:relative;

		padding:5px;

		width :90%;

		margin: 0 auto;
		margin-bottom:50px;

		}



	.top_template{


		position:absolute;top:0;left:0;width:100%;height:50px;



		margin-top:-50px;



		background:url(<?php echo plugin_dir_url(__FILE__); ?>templates/<?php echo get_option('template_quran');?>/top.png) repeat-x center top;



		background-size: 50px 50px;



	}	



	.bottom_template{



		position:absolute;bottom:0;left:0;width:100%;height:50px;



		margin-bottom:-50px;



		background:url(<?php echo plugin_dir_url(__FILE__); ?>templates/<?php echo get_option('template_quran');?>/bottom.png) repeat-x center top;



		background-size: 50px 50px;



	}



	.h-g_template{position:absolute;top:0;left:0;width:50px;height:50px;


				margin-top:-50px;

				margin-left:-50px;

				background:url(<?php echo plugin_dir_url(__FILE__); ?>templates/<?php echo get_option('template_quran');?>/h-g.png) repeat-x left top;

				background-size: 50px 50px;


				}



	.h-d_template{position:absolute;top:0;right:0;width:50px;height:50px;



				margin-top:-50px;



				margin-right:-50px;



				background:url(<?php echo plugin_dir_url(__FILE__); ?>templates/<?php echo get_option('template_quran');?>/h-d.png) repeat-x right top;



				background-size: 50px 50px;



				}	



	.b-g_template{position:absolute;bottom:0;left:0;width:50px;height:50px;



				margin-bottom:-50px;



				margin-left:-50px;



				background:url(<?php echo plugin_dir_url(__FILE__); ?>templates/<?php echo get_option('template_quran');?>/b-g.png) repeat-x left top;



				background-size: 50px 50px;



				}



	.b-d_template{position:absolute;bottom:0;right:0;width:50px;height:50px;



				margin-bottom:-50px;



				margin-right:-50px;



				background:url(<?php echo plugin_dir_url(__FILE__); ?>templates/<?php echo get_option('template_quran');?>/b-d.png) repeat-x right top;



				background-size: 50px 50px;



				}	



		



	.left_template{position:absolute;top:0;left:0;width:50px;height:100%;margin-left:-50px;


					background:url(<?php echo plugin_dir_url(__FILE__); ?>templates/<?php echo get_option('template_quran');?>/left.png) repeat-y left top;

					background-size: 50px 50px;



	}



	.right_template{position:absolute;top:0;right:0;width:50px;height:100%;margin-right:-50px;
					background:url(<?php echo plugin_dir_url(__FILE__); ?>templates/<?php echo get_option('template_quran');?>/right.png) repeat-y right top;
					background-size: 50px 50px;

	}



	#quran_main{width:80%;position:relative;margin : 0 auto;padding:5px;}



	#select_languages{font-size:1.27em;margin-top: 20px;color:#797979;float:right;width:100%;}


	#audio_sura { width: 100%;margin-top:10px;margin:5px auto;}

	#audio_sura #kb-idaudio{
		width: 80%;
		margin-top: 15px;
		-webkit-transition:all 0.5s linear;
		-moz-transition:all 0.5s linear;
		-o-transition:all 0.5s linear;
		transition:all 0.5s linear;
		-moz-box-shadow: 2px 2px 4px 0px #006773;
		-webkit-box-shadow:  2px 2px 4px 0px #006773;
		background: rgb(0, 156, 255) none repeat scroll 0% 0%;
		-moz-border-radius:7px 7px 7px 7px ;
		-webkit-border-radius:7px 7px 7px 7px ;
		border-radius:7px 7px 7px 7px ;	
		
	}
	#audio_sura #kb-idaudio2{
		width: 80%;
		-webkit-transition:all 0.5s linear;
		-moz-transition:all 0.5s linear;
		-o-transition:all 0.5s linear;
		transition:all 0.5s linear;
		-moz-box-shadow: 2px 2px 4px 0px #006773;
		-webkit-box-shadow:  2px 2px 4px 0px #006773;
		background: rgb(0, 156, 255) none repeat scroll 0% 0%;
		-moz-border-radius:7px 7px 7px 7px ;
		-webkit-border-radius:7px 7px 7px 7px ;
		border-radius:7px 7px 7px 7px ;	
		
	}


	.quranbadge{font-family: Arial, Helvetica, sans-serif;float: right;margin-left:5px;padding:1px 8px 1px;font-size:20px;font-weight:bold;white-space:nowrap;color:#ffffff;background-color:#999999;-webkit-border-radius:9px;-moz-border-radius:9px;border-radius:9px;}



	.quranbadge-info{background-color:#<?php echo get_option('background_quran_number'); ?>;color:#<?php echo get_option('color_quran_number'); ?>}


#kb_select_quran {
    display: inline-block;
    float: left;
    width: 50%;
}
 

 
#kb_select_language {
    display: inline-block;
    width: 50%;
}
 .dd .ddArrow{width:16px;height:16px; margin-top:-8px; background:url(<?php echo plugin_dir_url(__FILE__); ?>skin1/dd_arrow.gif) no-repeat;}


#autoplay_quran{width: 25%;float:left;}
/*fixed width: 30%;*/
#bloc_name_sura{width:55%;margin:0 auto;}
/*fixed width: 30%;*/
#dl_sura{cursor:pointer;}
.dl_sourate{width:20%;float:left;}
.ddlabel{font-size:18px}

.download_kb{
padding: 2px;
border: 1px solid #3A87AD;
border-radius: 5px;
width: 38px;
background: rgb(255, 255, 255) none repeat scroll 0% 0%;
position: absolute;
right: 99px;
cursor:pointer;	
}
.setting_kb{
padding: 2px;
border: 1px solid #3A87AD;
border-radius: 5px;
width: 38px;
background: rgb(255, 255, 255) none repeat scroll 0% 0%;
position: absolute;
right: 10px;
cursor:pointer;
}
.playsura_kb{
padding: 2px;
border: 1px solid #3A87AD;
border-radius: 5px;
width: 38px;
background: rgb(255, 255, 255) none repeat scroll 0% 0%;
right: 55px;
cursor: pointer;
position: absolute;
}
.params_kb{
display:none;
width: 95%;
margin: 15px auto;
background: #fff;
border-radius: 5px !important;
border: 1px solid #3A87AD;
padding: 10px;
height:72px;
}
.params1_kb{
display:none;
width: 90%;
margin: 15px auto;
background: #fff;
border-radius: 5px !important;
border: 1px solid #3A87AD;
padding: 10px;
height:50px;
}
#recitator_kb{float:left;width:35%;}
#recitator2_kb{float:left;width:50%;}
#play_select_quran{
	cursor:pointer;
}
#play_select_quran{display:none;}
#quran_player_sura{width:90%;margin: 0 auto;}
#playeraya{display:block;}
#suraplayer{display:none !important;}
#select_aya{width: 63%;float: left;}
#saveposition_kb{
color: #fff;
background-color: #337ab7;
border-color: #2e6da4;
padding: 4px 12px;
margin-bottom: 0;
font-size: 14px;
font-weight: 400;
line-height: 1.42857143;
text-align: center;
white-space: nowrap;
vertical-align: middle;
-ms-touch-action: manipulation;
touch-action: manipulation;
cursor: pointer;
-webkit-user-select: none;
-moz-user-select: none;
-ms-user-select: none;
user-select: none;
background-image: none;
border: 1px solid transparent;
border-radius: 4px;
}
#savemail_kb{width:37%;}
#savemail_kb input[type="text"]{width:63%;}
#formsave_kb{margin-bottom: 10px;}
#kb-idaudio2{width:40% !important;}
.params_download_kb{
display:none;
width: 90%;
margin: 15px auto;
background: #fff;
border-radius: 5px !important;
border: 1px solid #3A87AD;
padding: 10px;
height: 50px;
}
#dl_count_kb{display:none;}
#kb-select_debut, #kb-select_fin, #kb-select_text, #select_name_recitator{height:30px !important;display:inline;font-size:16px !important;padding:2px;}
#kb-select_debut{width:24%;}
#kb-select_fin{width:18%;}
#kb-select_text{width:40%;}
#click_download_kb{width:38px;}
#click_playsura_kb{width:38px;}
#click_params_kb{width:38px;}
.sm2_link{line-height: 1em;}
#select_name_recitatorkb{width:155px;height:30px;}
#select_name_recitator2{
	height:30px;
}
</style>



