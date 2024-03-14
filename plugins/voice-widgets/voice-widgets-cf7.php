<?php

class QC_VOICEWIDGET_CF7_Voice_Message{

    public function __construct(){

        add_action( 'wpcf7_init', [$this, 'QC_qcwpvoicemessage_add_form_tag'] );
        // add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts'], 100);
        add_shortcode( 'qcwpvoicemessage', [ $this, 'qcwpvoicemessage_shortcode' ] );
        // Tag Generator Button
        add_action('admin_init', [$this, 'QC_qcwpvoicemessage_tag_generator']);

        add_filter( 'wpcf7_posted_data', [$this, 'QC_qcwpvoicemessage_send_mail_function'], 10, 1 );

    }

    function QC_qcwpvoicemessage_send_mail_function( $posted_data ) {

        $html = '';

        foreach ($posted_data as $post_key => $data) {

            if( $post_key == 'qcwpvoicemessage' ){
                $html .= $data;
            }
        }
        $posted_data['qcwpvoicemessage'] = $html;
        return $posted_data;
    }

    public function abs_path_to_url( $path = '' ) {

        $url = str_replace(
            wp_normalize_path( untrailingslashit( ABSPATH ) ),
            site_url(),
            wp_normalize_path( $path )
        );

        return esc_url_raw( $url );
    }



    public function QC_qcwpvoicemessage_add_form_tag() {

      wpcf7_add_form_tag( 'qcwpvoicemessage', [$this,'QC_qcwpvoicemessage_form_tag_handler'] ); // "clock" is the type of the form-tag

    }

    public function QC_qcwpvoicemessage_form_tag_handler( $tag ) {

        $tag = new WPCF7_FormTag($tag);

        $form_id = $tag->get_option('form_id', '', true);

        $name = '';

        if( isset($tag['options'][0]) && !empty($tag['options'][0]) ){

            $name = $tag['options'][0];

        }

        // echo $form_id;

        /*$args = array(
                'post_type' =>'voicemssg_form_qcwp',
                'numberposts' => -1,
                'posts_per_page' => 1,
                'order' => 'ASC' );

        $form_post_id = get_posts($args);*/


        $form_id = $form_id ? $form_id : 125;

        if( isset($form_id) && $form_id > 0 ){

            return do_shortcode('[qcwpvoicemessage  id="'.$form_id.'"]');

        }else{

            return false;

        }

    }



    public function QC_qcwpvoicemessage_tag_generator(){

        if (! function_exists( 'wpcf7_add_tag_generator'))
            return;

        wpcf7_add_tag_generator(
            'qcwpvoicemessage',
            __('Voice Widget Message', 'wpvoice-widgets'),
            'qcwpvoicemessage',
            [$this, 'QC_qcwpvoicemessage_tab_generator_cb']

        );

    }

    

    public function QC_qcwpvoicemessage_tab_generator_cb($args){

        $args = wp_parse_args( $args, array() );

        $type = 'qcwpvoicemessage';

        $description = __( "Generate a voicemessage tag to display the voicemessage recorder on the form.", 'voice-widgets' );

        ?>
            <div class="control-box">
                <fieldset>
                    <legend><?php echo sprintf( esc_html( $description ) ); ?></legend>
                   
                </fieldset>
            </div>
            <div class="insert-box">
                <input type="text" name="<?php echo $type; ?>" class="tag code" readonly="readonly" onfocus="this.select()" />
                <div class="submitbox">
                    <input type="button" class="button button-primary insert-tag" value="<?php echo esc_attr( __( 'Insert Tag', 'contact-form-7' ) ); ?>" />
                </div>
                <br class="clear" />
            </div>

        <?php

    }

    public function qcwpvoicemessage_shortcode( $atts = [] ) {

        $atts = shortcode_atts( [
            'id' => '',
            'title' => '',
            'name'  =>  ''
        ], $atts );

        /** Nothing to show without any parameters. */

        if ( '' === $atts['id'] ) { return ''; }

        global $post;

        wp_enqueue_style( 'qc_audio_font_awesomess', QC_VOICEWIDGET_ASSETS_URL . 'css/font-awesome.min.css', false );
        wp_enqueue_style( 'qc-voice-widget-frontend-css',  QC_VOICEWIDGET_ASSETS_URL . 'css/cf7_frontend.css', false );

        wp_enqueue_script( 'qc-voice-widgets-recorders-js', QC_VOICEWIDGET_ASSETS_URL .  'js/recorder.js', ['jquery'], QC_VOICEWIDGET_VERSION, true );

        wp_enqueue_script( 'qc-voice-widget-audio-frontend-js', QC_VOICEWIDGET_ASSETS_URL .  'js/cf7_frontend.js', ['jquery', 'qc-voice-widgets-recorders-js'], QC_VOICEWIDGET_VERSION, true );

        $qc_voice_widget_lan_speak_now      =  get_option('qc_voice_widget_lan_speak_now') ? get_option('qc_voice_widget_lan_speak_now'): 'Speak now';
        $qc_voice_widget_lan_stop_save      =  get_option('qc_voice_widget_lan_stop_save')?get_option('qc_voice_widget_lan_stop_save'):'Stop & Save';
        $qc_voice_widget_lan_canvas_not_available      =  get_option('qc_voice_widget_lan_canvas_not_available')?get_option('qc_voice_widget_lan_canvas_not_available'):'Canvas not available.';
        $qc_voice_widget_lan_please_wait      =  get_option('qc_voice_widget_lan_please_wait')?get_option('qc_voice_widget_lan_please_wait'):'Please wait while proccsing your request.';

                $voice_obj = array(
                    'ajax_url' => admin_url('admin-ajax.php'),
                    'capture_duration'  => (get_option('stt_sound_duration') && get_option('stt_sound_duration') != '' ? MINUTE_IN_SECONDS * get_option('stt_sound_duration') : MINUTE_IN_SECONDS * 10 ),
                    'post_id' => isset( $post->ID ) ? $post->ID : 0,
                    'templates' => $this->get_templates(),
                    'qc_voice_widget_lan_speak_now' => $qc_voice_widget_lan_speak_now,
                    'qc_voice_widget_lan_stop_save' => $qc_voice_widget_lan_stop_save,
                    'qc_voice_widget_lan_canvas_not_available' => $qc_voice_widget_lan_canvas_not_available,
                    'qc_voice_widget_lan_please_wait' => $qc_voice_widget_lan_please_wait,
                );
                wp_localize_script('qc-voice-widget-audio-frontend-js', 'voice_obj', $voice_obj);

        ob_start();

        $qc_voice_widget_lan_record_audio   =  get_option('qc_voice_widget_lan_record_audio');

        ?>
        <div class="qc_voice_audio_wrapper">
            <div class="qc_voice_audio_container">
                <div class="qc_voice_audio_upload_main" id="qc_audio_main">
                    <a class="qc_audio_record_button" id="qc_audio_record" href="#">
                        <span class="dashicons dashicons-microphone"></span> <?php esc_html_e( $qc_voice_widget_lan_record_audio, 'voice-widgets' ); ?></a> 
                </div>

                <div class="qc_voice_audio_recorder" id="qc_audio_recorder" style="display:none">

                </div>
                <div class="qc_voice_audio_display" id="qc_audio_display"  style="display:none">
                    <audio id="qc-audio" controls src=""></audio>
                    <span title="Remove and back to main upload screen." class="qc_audio_remove_button dashicons dashicons-trash"></span>
                </div>
            </div>
            <input type="hidden" value="" name="qcwpvoicemessage" id="qc_audio_url" />
        </div>
        <?php
        return ob_get_clean();

    }

    public function get_templates() {
        return array(
            'default'    => array(
                'name' => esc_html__( 'Default', 'voice-widgets' ),
                'image' => esc_url_raw( QC_VOICEWIDGET_PLUGIN_URL . 'templates/admin/images/default-template.png' )
            )
        );
    }




}



new QC_VOICEWIDGET_CF7_Voice_Message();