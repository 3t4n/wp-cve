<div class="misc-pub-section misc-pub-section-last atp-container"><span id="timestamp">
    
    <p class="post-attributes-label-wrapper"><label class="post-attributes-label" for="atp_custom_pixel"><?php echo  esc_html__( 'Custom Event Tag', $text_domain ); ?></label></p>

    <label class="atp-toggle"><input id="atp_custom_pixel" type="checkbox" name="atp_custom_pixel" value="atp_custom_pixel" <?php if ( isset( $atp_custom_pixel ) && !empty( $atp_custom_pixel ) ) echo 'checked="checked"'; ?> />
    <span class='atp-toggle-slider atp-toggle-round'></span></label>

    <p class="post-attributes-label-wrapper"><label class="post-attributes-label" for="atp_custom_pixel_code"><?php echo  esc_html__( 'Custom Code', $text_domain ) ;?></label></p>

    <textarea id="atp_custom_pixel_code" name="atp_custom_pixel_code" class="atp-textarea"><?php if ( isset($atp_custom_pixel_code) && !empty( $atp_custom_pixel_code ) ) { echo $atp_custom_pixel_code; } ?></textarea>

    <p><?php echo  esc_html__( 'Note: Make sure to choose Yes and then copy-paste code in textarea. Please read more details about Twitter Event Pixel in FAQ', $text_domain ); ?></p>

</div>