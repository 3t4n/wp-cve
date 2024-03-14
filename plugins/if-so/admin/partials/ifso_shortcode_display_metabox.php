<?php
if ( ! defined( 'ABSPATH' ) ) exit; 


$current_post_id = get_the_ID();
$published = (get_post_status( $current_post_id ) == 'publish' );

if($published && !isset($_COOKIE['ifso_hide_pagebuilder_notice'])): ?>
	<?php do_action('show_pagebuilders_noticebox'); ?>
<?php endif; ?>
<h4 style="margin-bottom:8px;font-weight:normal;"><?php _e('Paste this shortcode to display the trigger', 'if-so'); ?></h4>
<?php
if ($published):
$shortcode = sprintf( '[ifso id="%1$d"]', $current_post_id);
$trigger_name = get_the_title($current_post_id);?>
<span class="shortcode"><input type="text" onfocus="this.select();" readonly="readonly" value='<?php echo $shortcode; ?>' class="large-text code"></span>
<!--<p style="text-align: center; margin: 5px auto;">-- <?php _e('Or', 'if-so'); ?> --</p>
<h4 style="margin-top:0; margin-bottom:0;"><?php _e('PHP code to paste in your template', 'if-so'); ?></h4>-->

<div class="ifso-toggle-sections-wrap">
    <p class="ifso-sc-metabox-toggle-link ifso-popup-shortcode-link"><span class="ifso-turnme"><i class="fa fa-angle-down" aria-hidden="true"></i></span><?php _e('Display trigger as pop-up', 'if-so'); ?></p>
    <div class="metabox-item ifso-popup-notice-wrap ifso-sc-metabox-toggle-wrap" style="display:none;">
        <?php
        if(defined('IFSO_TRIGGER_EVENTS_ON') && IFSO_TRIGGER_EVENTS_ON)
            do_action('ifso_shortcode_metabox_option_popup');
        else{
            ?>
            <p class="red-noticebox">Requires the Trigger Events extension. <a href="https://www.if-so.com/trigger-events-extension?utm_source=Plugin&utm_medium=helpBox&utm_campaign=TriggerEvents" target="_blank">Learn more &gt;</a></p>
        <?php } ?>
    </div>


    <p class="ifso-sc-metabox-toggle-link php-shortcode-toggle-link"><span class="ifso-turnme"><i class="fa fa-angle-down" aria-hidden="true"></i></span><?php _e('PHP code (for developers)', 'if-so'); ?></p>

    <div class="php-shortcode-toggle-wrap ifso-sc-metabox-toggle-wrap">
        <?php $php_code = sprintf( '<?php ifso(%1$d); ?>', $current_post_id);
        /*$php_code = sprintf( "<?php echo do_shortcode(\"{$shortcode}\"); ?>", $current_post_id);*/?>
        <div class="metabox-item">
            <p>Paste this function call into your website's code</p>
            <span class="shortcode"><input type="text" onfocus="this.select();" readonly="readonly" value='<?php echo $php_code; ?>' class="large-text code"></span>
        </div>

    </div>

    <p class="ifso-sc-metabox-toggle-link shortcode-withtitle-toggle-link"><span class="ifso-turnme"><i class="fa fa-angle-down" aria-hidden="true"></i></span><?php _e('Shortcode with title', 'if-so'); ?></p>
    <div class="shortcode-withtitle-toggle-wrap ifso-sc-metabox-toggle-wrap">
        <?php
        if(!empty($trigger_name)){
            $shortcode_with_title = sprintf( '[ifso id="%1$d" title="%2$s"]', $current_post_id,$trigger_name);?>
            <div class="metabox-item">
                <p>For your convenience (no extra functionality)</p>
                <span class="shortcode"><input type="text" onfocus="this.select();" readonly="readonly" value='<?php echo $shortcode_with_title; ?>' class="large-text code"></span>
            </div>
        <?php }?>
    </div>
</div>

<?php else: ?>
<span class="shortcode"><input type="text" readonly="readonly" value='<?php _e('Publish to get the shortcode', 'if-so');?>' class="large-text code"></span>
<?php endif; ?>