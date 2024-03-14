<?php
class Coinmotion_Affiliate_Button{
	public function generateButton(): string
    {
		$params = coinmotion_get_widget_data();
		$data_lang = explode("_", get_locale());
		$lang = $data_lang[0];
		
		if (!in_array($lang, ['es', 'en', 'fi'])){
			$lang = 'en';
		}
		$output = "<style>
			.coinmotion_button_affiliate{
				color: ".$params['register_text_color'].";
				background-color: ".$params['register_button_color'].";
				outline: 0;
				text-decoration: none;
				border-radius: 40px;
				padding: 5px 20px;
				/*text-transform: uppercase;*/
				margin: 0 auto;
			}

			a.coinmotion_button_affiliate:hover{
				background-color: ".$params['register_button_hover_color'].";
			}
		</style>";
		$url = "https://app.coinmotion.com/".$lang."/register/signup?referral_code=".$params['refcode']."&utm_campaign=price_widget_".$lang."&utm_source=".$params['refcode']."&utm_medium=referral_button";
		$output .= "<div style='display: grid; height: auto;'><a rel='nofollow' class='coinmotion_button_affiliate' href='".$url."' target='_blank'>".$params['register_text']."</a></div>";
		return $output;
	}

	public function generateCMLink($widget_name, $text_color = 'black'): string
    {
		$params = coinmotion_get_widget_data();
		$data_lang = explode("_", get_locale());
		$lang = $data_lang[0];

		if (!in_array($lang, ['es', 'en', 'fi'])){
			$lang = 'en';
		}
		$campaign = "price_widget_".$lang;
		if ($lang === 'en'){
			$lang = "";
		}
		
		$track = "?referral_code=".$params['refcode']."&utm_campaign=price_widget_".$lang."&utm_source=".$params['refcode']."&utm_medium=poweredby_button";
        $data_lang = explode('_', get_locale());
        $lang = $data_lang[0];

        if (!in_array($lang, ['es', 'en', 'fi'])){
            $lang = 'en';
        }

        $url = get_option('coinmotion_anchor');
        $url = str_replace('_LANG_', $lang, $url);
        $url = str_replace('_TRACK_CODE_', $track, $url);
        return "<p style='text-align: center; color: " . $text_color . "; margin-bottom: 0px;'>". $url ."</p>";
	}
}
