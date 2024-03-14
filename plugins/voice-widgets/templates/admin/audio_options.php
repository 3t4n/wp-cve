<?php
defined('ABSPATH') or die("No direct script access!");

/**
 * Template audio upload metabox
 */

$audio_url = get_post_meta( $post->ID, 'qc_audio_url', true );
$is_audio_uploaded = ( $audio_url && '' !== $audio_url ? true : false );

?>
<div class="qc_voice_audio_wrapper" >
    <div class="qc_voice_audio_container">

        <div class="qc_voice_audio_upload_main" id="qc_audio_main" <?php echo esc_attr( $is_audio_uploaded ? 'style=display:none':'' ); ?>>
            <a class="button button-default button-large" id="qc_audio_record" href="#"><span class="dashicons dashicons-microphone"></span> <?php echo esc_html__( 'Record Audio', 'voice-widgets' ); ?></a> <span class="qc_audio_or"><?php echo esc_html__( 'OR', 'voice-widgets' ); ?></span> <a class="button button-default button-large" id="qc_audio_upload" href="#"><span class="dashicons dashicons-upload"></span> <?php echo esc_html__( 'Upload Audio', 'voice-widgets' ); ?></a>
        </div>

        <div class="qc_voice_audio_recorder" id="qc_audio_recorder" style="display:none">

        </div>

        <div class="qc_voice_audio_display" id="qc_audio_display" <?php echo esc_attr( $is_audio_uploaded ? 'style=display:block':'style=display:none' ); ?> >
            <audio id="qc-audio" controls src="<?php echo esc_url_raw( $is_audio_uploaded ? $audio_url : '' ); ?>">
            </audio>
            <span title="Remove and back to main upload screen." class="qc_audio_remove_button dashicons dashicons-trash"></span>
        </div>
    </div>
    <input type="hidden" value="<?php echo esc_url_raw( $is_audio_uploaded ? $audio_url : '' ); ?>" name="qc_audio_url" id="qc_audio_url" />
</div>