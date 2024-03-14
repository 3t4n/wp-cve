<div class="qc_audio_wrapper <?php echo esc_attr( $audio_template ); ?> <?php echo esc_attr( $audio_template . '_' . esc_attr($id) ); ?> ">
    <div class="qc_audio_animation" >
        <img src="<?php echo esc_url_raw( $featured_img_url ); ?>" alt="" />
        <div class="circle" style="animation-delay: -3s"></div>
        <div class="circle" style="animation-delay: -2s"></div>
        <div class="circle" style="animation-delay: -1s"></div>
        <div class="circle" style="animation-delay: 0s"></div>
        <div class="qc_audio_play_button qc_play_audio"><i class="fa fa-play-circle" aria-hidden="true"></i></div>
    </div>
    <div class="qc_audio_audio" style="display:none">
        <audio class="qc-audio-front" controls src="<?php echo esc_url_raw( $audio_url ); ?>"></audio>
    </div>
</div>