<?php
use WPSocialReviews\App\Services\Platforms\Chats\Helper as chatHelper;
use WPSocialReviews\Framework\Support\Arr;

$channels = Arr::get($settings, 'channels', []);
if(empty($channels)) {
    return;
}

$template = Arr::get($settings, 'template', '');
$image_url = chatHelper::getImageUrl($settings);
?>

<?php foreach ($channels as $key => $channel){
    $isUrl = chatHelper::isUrl($channel['credential']);
    $credential = $isUrl ? $channel['credential'] : $channel['webUrl'] . $channel['credential'];
    $image_url = count($settings['channels']) > 1 ?  WPSOCIALREVIEWS_URL . 'assets/images/svg/' . $channel['name'] . '.svg'  : $image_url;
    ?>
    <div class="wpsr-channel-item <?php echo esc_attr($channel['name'].$key); ?>">
        <?php if($settings['layout_type'] === 'icons') { 
                $label = Arr::get($channel, 'label');
                $label = apply_filters('wpsocialreviews/'.$channel['name'].'_chat_channel_label', $label);
            ?>
            <?php if ($label != '') { ?>
                <span class="wpsr-channel-name">
                    <?php echo esc_html($label); ?>
                </span>
            <?php } ?>
        <?php } ?>

        <?php
            if(strpos($credential, 'fluentform_modal')){
                echo do_shortcode($credential);
            }
            if(!strpos($credential, 'fluentform_modal')){
                if(strpos($credential, 'mailto') !== false || strpos($credential, 'tel') !== false){
                    $credential = chatHelper::encodeCredentials($credential);
                }
                $credential = str_replace('=+', '=', $credential);
        ?>
        <a role="button"
           data-chat-url="<?php echo esc_attr($credential); ?>"
           data-channel="<?php echo esc_attr($channel['name']); ?>"
           data-form-id="<?php echo esc_attr($credential); ?>"
           data-all-ff-ids="<?php echo esc_attr(implode(',', array_column($channels, 'credential'))); ?>"
           style="background-color:<?php echo esc_attr(Arr::get($settings, 'styles.channel_icon_bg_color', '')); ?>"
           class="wpsr-channel-btn <?php echo esc_attr($channel['name']); ?> <?php echo esc_attr($channel['name'].$key); ?>"
        >
            <?php
                if ($settings['chat_button']['display_icon'] === 'true') {
                ?>
                    <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($channel['name']); ?>" width="32" height="32">
                <?php
                }
            ?>
        </a>
        <?php
            $show_button = Arr::get($settings, 'settings.show_label', 'false');

            if ($channel['label'] != '' 
            && $settings['chat_button']['display_icon'] === 'true' 
            && $show_button === 'true' 
            && $settings['layout_type'] !== 'icons') {
            ?>
               <p class="wpsr-channel-label"><?php echo esc_html($channel['label']); ?></p>
             <?php
            }
        ?>
        
        <?php } ?>
    </div>
<?php } ?>