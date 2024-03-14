<?php
$custom_link = tochatbe_just_whatsapp_icon_option( 'icon_link' );

$icon = $custom_link ? $custom_link : TOCHATBE_PLUGIN_URL . 'assets/images/whatsapp-icon.svg';
$link = 'https://api.whatsapp.com/send?phone=' . tochatbe_just_whatsapp_icon_option( 'number' );

?>
<a class="tochatbe_jwi" href="<?php echo esc_url( $link ); ?>" target="_blank">
    <img src="<?php echo esc_url( $icon ); ?>" width="60" alt="//">
</a>