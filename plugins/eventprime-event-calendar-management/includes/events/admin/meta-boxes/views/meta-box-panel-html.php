<?php
/**
 * Event meta box html
 */
defined( 'ABSPATH' ) || exit;
?>
<div class="emagic">
    <div class="panel-wrap ep_event_metabox ep-box-wrap ep-p-0">
        <div class="ep-box-row">
            <div class="ep-box-col-left-2 ep-box-pr-0">
                <ul class="ep_event_metabox_tabs ep-m-0 ep-p-0 ep-box-h-100">
                    <?php foreach (self::get_ep_event_meta_tabs() as $key => $tab) : ?>
                        <?php $active_class = ( $key == 'datetime' ) ? 'ep-tab-active' : '';?>
                        <li class="ep-event-metabox-tab <?php echo esc_attr($key); ?>_options <?php echo esc_attr($key); ?>_tab <?php echo esc_attr(isset($tab['class']) ? implode(' ', (array) $tab['class']) : '' ); ?> <?php echo esc_attr( $active_class );?>">
                            <a href="#" data-src="<?php echo esc_attr($tab['target']); ?>"><span><?php echo esc_html($tab['label']); ?></span></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="ep-box-col-right-10 ep-box-pl-0">
                <?php self::ep_event_tab_content();?>
            </div>
        </div>
    </div>
</div>