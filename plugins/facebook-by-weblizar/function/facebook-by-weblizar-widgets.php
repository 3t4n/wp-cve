<?php if ( ! defined( 'ABSPATH' ) ) exit;

// Register WeblizarFacebook widget.
function WeblizarFacebookWidget() {
    register_widget( 'WeblizarFacebook' );
}
add_action( 'widgets_init', 'WeblizarFacebookWidget' );
/**
 * Adds WeblizarFacebook Widget
 */
class WeblizarFacebook extends WP_Widget {

    /**
     * Register widget with WordPress
     */
    function __construct() {
		add_action('plugins_loaded', 'GetReadyFacebookTranslation');
        parent::__construct(
            'weblizar_facebook_likebox', // Base ID
            esc_html__( 'Facebook Page Like Box', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ), // Widget Name
            array( 'description' => esc_html__( 'Display Facebook Page Live Stream & Fans', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ) )
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
		// Outputs the content of the widget
		extract($args); // Make before_widget, etc available.
		$title = apply_filters('title', $instance['title']);

        $allowed_html = wp_kses_allowed_html( 'post' );

		echo wp_kses( $before_widget, $allowed_html );
		if (!empty($title)) {	echo wp_kses( $before_title . $title . $after_title, $allowed_html );	}

        $FbAppId = apply_filters( 'facebook_app_id', $instance['FbAppId'] );
        //$ColorScheme = apply_filters( 'facebook_color_scheme', $instance['ColorScheme'] );
        $ForceWall = apply_filters( 'facebook_force_wall', $instance['ForceWall'] );
        $Header = apply_filters( 'facebook_header', $instance['Header'] );
        $Height = apply_filters( 'facebook_height', $instance['Height'] );
        $FacebookPageURL = apply_filters( 'facebook_page_url', $instance['FacebookPageURL'] );
        $ShowBorder = apply_filters( 'facebook_show_border', $instance['ShowBorder'] );
        $ShowFaces = apply_filters( 'facebook_show_faces', $instance['ShowFaces'] );
        $Stream = apply_filters( 'facebook_stream', $instance['Stream'] );
        $Width = apply_filters( 'facebook_width', $instance['Width'] );
		$weblizar_lang_fb = apply_filters('weblizar_lang_fb', $instance['weblizar_lang_fb']);
        ?>
		<style>
		@media (max-width:767px) {
			.fb_iframe_widget {
				width: 100%;
			}
			.fb_iframe_widget span {
				width: 100% !important;
			}
			.fb_iframe_widget iframe {
				width: 100% !important;
			}
			._8r {
				margin-right: 5px;
				margin-top: -4px !important;
			}
		}
		</style>
        <div style="display:block;width:100%;float:left;overflow:hidden;margin-bottom:20px">
			<div id="fb-root"></div>
			<script>(function(d, s, id) {
			  var js, fjs = d.getElementsByTagName(s)[0];
			  if (d.getElementById(id)) return;
			  js = d.createElement(s); js.id = id;
			  js.src = "//connect.facebook.net/<?php echo esc_attr( $weblizar_lang_fb ); ?>/sdk.js#xfbml=1&version=v2.7";
			  fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));</script>
			<div class="fb-like-box" style="background-color: auto;" data-small-header="<?php echo esc_attr( $Header ); ?>" data-height="<?php echo esc_attr( $Height ); ?>" data-href="<?php echo esc_url( $FacebookPageURL ); ?>" data-show-border="<?php echo esc_attr( $ShowBorder ); ?>" data-show-faces="<?php echo esc_attr( $ShowFaces ); ?>" data-stream="<?php echo esc_attr( $Stream ); ?>" data-width="<?php echo esc_attr( $Width ); ?>" data-force-wall="<?php echo esc_attr( $ForceWall ); ?>"></div>

		</div>
        <?php
		echo wp_kses( $after_widget, $allowed_html );
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {

        //default values & Submitted Values
        /* $ColorScheme = 'lite';
        if ( isset( $instance[ 'ColorScheme' ] ) ) {
            $ColorScheme = $instance[ 'ColorScheme' ];
        } */

        $ForceWall = 'false';
        if ( isset( $instance[ 'ForceWall' ] ) ) {
            $ForceWall = $instance[ 'ForceWall' ];
        }

        $Header = 'true';
        if ( isset( $instance[ 'Header' ] ) ) {
            $Header = $instance[ 'Header' ];
        }

        $Height = 560;
        if ( isset( $instance[ 'Height' ] ) ) {
            $Height = $instance[ 'Height' ];
        }

        $FacebookPageURL = 'https://www.facebook.com/Weblizarwp/';
        if ( isset( $instance[ 'FacebookPageURL' ] ) ) {
            $FacebookPageURL = $instance[ 'FacebookPageURL' ];
        }

        $ShowBorder = 'true';
        if ( isset( $instance[ 'ShowBorder' ] ) ) {
            $ShowBorder = $instance[ 'ShowBorder' ];
        }

        $ShowFaces = 'true';
        if ( isset( $instance[ 'ShowFaces' ] ) ) {
            $ShowFaces = $instance[ 'ShowFaces' ];
        }

        $Stream = 'true';
        if ( isset( $instance[ 'Stream' ] ) ) {
            $Stream = $instance[ 'Stream' ];
        }

        $Width = 292;
        if ( isset( $instance[ 'Width' ] ) ) {
            $Width = $instance[ 'Width' ];
        }

        $FbAppId = '529331510739033';
        if ( isset( $instance[ 'FbAppId' ] ) ) {
            $FbAppId = $instance[ 'FbAppId' ];
        }

		if ( isset( $instance[ 'title' ] ) ) {
			 $title = $instance[ 'title' ];
		}

		$weblizar_lang_fb = 'en_GB';
		if ( isset( $instance[ 'weblizar_lang_fb' ] ) ) {
			 $weblizar_lang_fb = $instance[ 'weblizar_lang_fb' ];
		}

		else {
			 $title = esc_html__( 'LikeBox', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN' );
		}
		?>
		<p>
    		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:' ); ?></label>
    		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'FacebookPageURL' ) ); ?>"><?php esc_html_e( 'Facebook Page URL', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'FacebookPageURL' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'FacebookPageURL' ) ); ?>" type="text" value="<?php echo esc_url( $FacebookPageURL ); ?>">
        </p>
		<p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'ShowFaces' ) ); ?>"><?php esc_html_e( 'Show Faces', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?></label>
            <select id="<?php echo esc_attr( $this->get_field_id( 'ShowFaces' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'ShowFaces' ) ); ?>">
                <option value="true" <?php if($ShowFaces == "true") echo esc_attr("selected=selected") ?>><?php esc_html_e( 'Yes', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?></option>
                <option value="false" <?php if($ShowFaces == "false") echo esc_attr("selected=selected") ?>><?php esc_html_e( 'No', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'Stream' ) ); ?>"><?php esc_html_e( 'Show Live Stream', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?></label>
            <select id="<?php echo esc_attr( $this->get_field_id( 'Stream' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'Stream' ) ); ?>">
                <option value="true" <?php if($Stream == "true") echo esc_attr("selected=selected") ?>><?php esc_html_e( 'Yes', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?></option>
                <option value="false" <?php if($Stream == "false") echo esc_attr("selected=selected") ?>><?php esc_html_e( 'No', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'Width' ) ); ?>"><?php esc_html_e( 'Widget Width', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'Width' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'Width' ) ); ?>" type="text" value="<?php echo esc_attr( $Width ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'Height' ) ); ?>"><?php esc_html_e( 'Widget Height', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'Height' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'Height' ) ); ?>" type="text" value="<?php echo esc_attr( $Height ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'FbAppId' ) ); ?>"><?php esc_html_e( 'Facebook App ID', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?> (<?php esc_html_e("Optional",WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?>)</label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'FbAppId' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'FbAppId' ) ); ?>" type="text" value="<?php echo esc_attr( $FbAppId ); ?>">
            <?php esc_html_e("Get Your Facebook App. Id",WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?>: <a href="http://weblizar.com/get-facebook-app-id/" target="_blank"><?php esc_html_e( 'HERE', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?></a>
        </p>

		<p>
			<!--weblizar_locale_fb-->
			<label for="<?php echo esc_attr( $this->get_field_id( 'weblizar_lang_fb' ) ); ?>"><?php esc_html_e( 'Like Button Language', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?></label>

			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'weblizar_lang_fb' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'weblizar_lang_fb' ) ); ?>" >
				<option value="af_ZA" <?php if($weblizar_lang_fb == "af_ZA") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Afrikaans', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="ar_AR" <?php if($weblizar_lang_fb == "ar_AR") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Arabic', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="az_AZ" <?php if($weblizar_lang_fb == "az_AZ") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Azerbaijani', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="be_BY" <?php if($weblizar_lang_fb == "be_BY") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Belarusian', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="bg_BG" <?php if($weblizar_lang_fb == "bg_BG") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Bulgarian', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="bn_IN" <?php if($weblizar_lang_fb == "bn_IN") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Bengali', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="bs_BA" <?php if($weblizar_lang_fb == "bs_BA") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Bosnian', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="ca_ES" <?php if($weblizar_lang_fb == "ca_ES") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Catalan', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="cs_CZ" <?php if($weblizar_lang_fb == "cs_CZ") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Czech', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="cy_GB" <?php if($weblizar_lang_fb == "cy_GB") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Welsh', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="da_DK" <?php if($weblizar_lang_fb == "da_DK") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Danish', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="de_DE" <?php if($weblizar_lang_fb == "de_DE") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('German', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="el_GR" <?php if($weblizar_lang_fb == "el_GR") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Greek', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="en_GB" <?php if($weblizar_lang_fb == "en_GB") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('English (UK)', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="en_PI" <?php if($weblizar_lang_fb == "en_PI") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('English (Pirate)', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="en_UD" <?php if($weblizar_lang_fb == "en_UD") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('English (Upside Down)', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="en_US" <?php if($weblizar_lang_fb == "en_US") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('English (US)', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="eo_EO" <?php if($weblizar_lang_fb == "eo_EO") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Esperanto', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="es_ES" <?php if($weblizar_lang_fb == "es_ES") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Spanish (Spain)', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="es_LA" <?php if($weblizar_lang_fb == "es_LA") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Spanish', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="et_EE" <?php if($weblizar_lang_fb == "et_EE") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Estonian', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="eu_ES" <?php if($weblizar_lang_fb == "eu_ES") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Basque', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="fa_IR" <?php if($weblizar_lang_fb == "fa_IR") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Persian', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="fb_LT" <?php if($weblizar_lang_fb == "fb_LT") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Leet Speak', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="fi_FI" <?php if($weblizar_lang_fb == "fi_FI") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Finnish', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="fo_FO" <?php if($weblizar_lang_fb == "fo_FO") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Faroese', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="fr_CA" <?php if($weblizar_lang_fb == "fr_CA") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('French (Canada)', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="fr_FR" <?php if($weblizar_lang_fb == "fr_FR") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('French (France)', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="fy_NL" <?php if($weblizar_lang_fb == "fy_NL") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Frisian', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="ga_IE" <?php if($weblizar_lang_fb == "ga_IE") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Irish', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="gl_ES" <?php if($weblizar_lang_fb == "gl_ES") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Galician', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="he_IL" <?php if($weblizar_lang_fb == "he_IL") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Hebrew', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="hi_IN" <?php if($weblizar_lang_fb == "hi_IN") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Hindi', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="hr_HR" <?php if($weblizar_lang_fb == "hr_HR") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Croatian', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="hu_HU" <?php if($weblizar_lang_fb == "hu_HU") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Hungarian', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="hy_AM" <?php if($weblizar_lang_fb == "hy_AM") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Armenian', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="id_ID" <?php if($weblizar_lang_fb == "id_ID") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Indonesian', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="is_IS" <?php if($weblizar_lang_fb == "is_IS") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Icelandic', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="it_IT" <?php if($weblizar_lang_fb == "it_IT") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Italian', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="ja_JP" <?php if($weblizar_lang_fb == "ja_JP") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Japanese', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="ka_GE" <?php if($weblizar_lang_fb == "ka_GE") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Georgian', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="km_KH" <?php if($weblizar_lang_fb == "km_KH") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Khmer', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="ko_KR" <?php if($weblizar_lang_fb == "ko_KR") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Korean', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="ku_TR" <?php if($weblizar_lang_fb == "ku_TR") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Kurdish', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="la_VA" <?php if($weblizar_lang_fb == "la_VA") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Latin', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="lt_LT" <?php if($weblizar_lang_fb == "lt_LT") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Lithuanian', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="lv_LV" <?php if($weblizar_lang_fb == "lv_LV") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Latvian', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="mk_MK" <?php if($weblizar_lang_fb == "mk_MK") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Macedonian', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="ml_IN" <?php if($weblizar_lang_fb == "ml_IN") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Malayalam', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="ms_MY" <?php if($weblizar_lang_fb == "ms_MY") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Malay', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="nb_NO" <?php if($weblizar_lang_fb == "nb_NO") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Norwegian (bokmal)', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="ne_NP" <?php if($weblizar_lang_fb == "ne_NP") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Nepali', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="nl_NL" <?php if($weblizar_lang_fb == "nl_NL") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Dutch', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="nn_NO" <?php if($weblizar_lang_fb == "nn_NO") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Norwegian (nynorsk)', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="pa_IN" <?php if($weblizar_lang_fb == "pa_IN") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Punjabi', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="pl_PL" <?php if($weblizar_lang_fb == "pl_PL") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Polish', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="ps_AF" <?php if($weblizar_lang_fb == "ps_AF") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Pashto', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="pt_BR" <?php if($weblizar_lang_fb == "pt_BR") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Portuguese (Brazil)', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="pt_PT" <?php if($weblizar_lang_fb == "pt_PT") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Portuguese (Portugal)', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="ro_RO" <?php if($weblizar_lang_fb == "ro_RO") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Romanian', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="ru_RU" <?php if($weblizar_lang_fb == "ru_RU") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Russian', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="sk_SK" <?php if($weblizar_lang_fb == "sk_SK") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Slovak', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="sl_SI" <?php if($weblizar_lang_fb == "sl_SI") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Slovenian', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="sq_AL" <?php if($weblizar_lang_fb == "sq_AL") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Albanian', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="sr_RS" <?php if($weblizar_lang_fb == "sr_RS") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Serbian', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="sv_SE" <?php if($weblizar_lang_fb == "sv_SE") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Swedish', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="sw_KE" <?php if($weblizar_lang_fb == "sw_KE") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Swahili', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="ta_IN" <?php if($weblizar_lang_fb == "ta_IN") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Tamil', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="te_IN" <?php if($weblizar_lang_fb == "te_IN") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Telugu', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="th_TH" <?php if($weblizar_lang_fb == "th_TH") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Thai', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="tl_PH" <?php if($weblizar_lang_fb == "tl_PH") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Filipino', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="tr_TR" <?php if($weblizar_lang_fb == "tr_TR") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Turkish', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="uk_UA" <?php if($weblizar_lang_fb == "uk_UA") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Ukrainian', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="vi_VN" <?php if($weblizar_lang_fb == "vi_VN") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Vietnamese', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="zh_CN" <?php if($weblizar_lang_fb == "zh_CN") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Simplified Chinese (China)', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="zh_HK" <?php if($weblizar_lang_fb == "zh_HK") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Traditional Chinese (Hong Kong)', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
				<option value="zh_TW" <?php if($weblizar_lang_fb == "zh_TW") echo esc_attr('selected="selected"') ?> ><?php esc_html_e('Traditional Chinese (Taiwan)', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?></option>
			</select>
		</p>

		<p>
    		<a style="display:block;" target="_new" href="https://wordpress.org/plugins/facebook-by-weblizar/"><img src="<?php echo WEBLIZAR_FACEBOOK_PLUGIN_URL.'images/star.png' ;?>" /> </a>
    		<a href="https://wordpress.org/plugins/facebook-by-weblizar/" target="_new"> <?php esc_html_e("Rate Us on ",WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?> Wordpress.org</a>
		</p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : 'Tweets';
        $instance['FacebookPageURL'] = ( ! empty( $new_instance['FacebookPageURL'] ) ) ? strip_tags( $new_instance['FacebookPageURL'] ) : 'https://www.facebook.com/Weblizarwp/';
        $instance['Header'] = ( ! empty( $new_instance['Header'] ) ) ? strip_tags( $new_instance['Header'] ) : 'true';
        $instance['Width'] = ( ! empty( $new_instance['Width'] ) ) ? strip_tags( $new_instance['Width'] ) : '292';
        $instance['Height'] = ( ! empty( $new_instance['Height'] ) ) ? strip_tags( $new_instance['Height'] ) : '560';
        $instance['Stream'] = ( ! empty( $new_instance['Stream'] ) ) ? strip_tags( $new_instance['Stream'] ) : 'true';
        $instance['ShowFaces'] = ( ! empty( $new_instance['ShowFaces'] ) ) ? strip_tags( $new_instance['ShowFaces'] ) : 'true';
        $instance['ShowBorder'] = ( ! empty( $new_instance['ShowBorder'] ) ) ? strip_tags( $new_instance['ShowBorder'] ) : 'true';
        $instance['ForceWall'] = ( ! empty( $new_instance['ForceWall'] ) ) ? strip_tags( $new_instance['ForceWall'] ) : 'false';
        $instance['FbAppId'] = ( ! empty( $new_instance['FbAppId'] ) ) ? strip_tags( $new_instance['FbAppId'] ) : '529331510739033';
		$instance['weblizar_lang_fb'] = ( ! empty( $new_instance['weblizar_lang_fb'] ) ) ? strip_tags( $new_instance['weblizar_lang_fb'] ) : 'en_GB';
        return $instance;
    }
} // class WeblizarFacebook
?>
