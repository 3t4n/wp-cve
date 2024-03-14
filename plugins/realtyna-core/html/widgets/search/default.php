<?php
/**
 * Overriden file of WPL Plugin to make it customized for Sesame theme. 
 * This view is showing WPL Search Widget.
 * @author Realtyna Inc.
 */
defined('_WPLEXEC') or die('Restricted access');

if(wpl_global::check_addon('membership')) $this->membership = new wpl_addon_membership();
?>
<div id="wpl_default_search_<?php echo esc_attr($widget_id); ?>">
    <form action="<?php echo wpl_property::get_property_listing_link(); ?>" id="wpl_search_form_<?php echo esc_attr($widget_id); ?>" method="GET" onsubmit="return wpl_do_search_<?php echo esc_attr($widget_id); ?>('wpl_searchwidget_<?php echo esc_attr($widget_id); ?>');" class="wpl_search_from_box clearfix wpl_search_kind<?php echo esc_attr($this->kind); ?> <?php echo esc_attr($this->style).' '.esc_attr($this->css_class); ?>">
        <!-- Do not change the ID -->
        <div class="wpl-search-container">
            <div id="wpl_searchwidget_<?php echo esc_attr($widget_id); ?>" class="clearfix">
                <?php
                $top_div = '';
                $bott_div = '';
                $bott_div_open = false;

                $is_separator = false;
                $top_array = array();

                $counter = 1;
                foreach($this->rendered as $data)
                {
                    if(($data['field_data']['type'] == 'separator') && $counter > 1)
                    {
                        $is_separator = true;
                        break;
                    }

                    $counter++;
                }

                if(!$is_separator) $top_array = array(41, 3, 6, 8, 9, 2);

                $counter = 1;
                foreach($this->rendered as $data)
                {
                    if($is_separator or (!$is_separator and in_array($data['id'], $top_array))) $top_div .= $data['html'];
                    else
                    {
                        if(is_string($data['current_value']) and trim($data['current_value']) and $data['current_value'] != '-1') $bott_div_open = true;
                        $bott_div .= $data['html'];
                    }

                    if($data['field_data']['type'] == 'separator' and $counter > 1) $is_separator = false;
                    $counter++;
                }
                ?>
                <div class="wpl_search_from_box_top">
                    <?php echo $top_div; ?>
                    <?php if($this->show_reset_button): ?>
                        <div class="wpl_search_reset" onclick="wpl_do_reset<?php echo esc_attr($this->widget_id); ?>([], <?php echo ($this->ajax == 2 ? 'true' : 'false'); ?>);" id="wpl_search_reset<?php echo esc_attr($widget_id); ?>"><?php echo esc_html__('Reset', 'sesame'); ?></div>
                    <?php endif; ?>
                    <div class="search_submit_box">
                        <input id="wpl_search_widget_submit<?php echo esc_attr($widget_id); ?>" class="wpl_search_widget_submit" type="submit" value="<?php echo esc_attr__('Search', 'sesame'); ?>" />
                        <?php if($this->show_total_results == 1): ?><span id="wpl_total_results<?php echo esc_attr($widget_id); ?>" class="wpl-total-results">(<span></span>)</span><?php endif; ?>
                    </div>
                    <?php if($this->show_total_results == 2): ?><span id="wpl_total_results<?php echo esc_attr($widget_id); ?>" class="wpl-total-results-after"><?php echo sprintf('%s listings', '<span></span>'); ?></span><?php endif; ?>
                    <?php if(wpl_global::check_addon('membership') and ($this->kind == 0 or $this->kind == 1)): ?>
                        <div class="wpl_dashboard_links_container">
                            <?php if(wpl_global::check_addon('save_searches') and ($this->show_saved_searches)) : ?>
                                <a class="wpl-addon-save-searches-link" href="<?php echo esc_url($this->membership->URL('searches')); ?>"><?php echo esc_html__('Saved Searches', 'sesame'); ?>
                                    <span id="wpl-addon-save-searches-count<?php echo esc_attr($widget_id); ?>"><?php echo esc_html($this->saved_searches_count); ?></span>
                                </a>
                            <?php endif; ?>
                            <?php if($this->show_favorites): ?>
                                <a class="wpl-widget-favorites-link" href="<?php echo esc_url($this->membership->URL('favorites')); ?>"><?php echo esc_html__('Favorites', 'sesame'); ?>
                                    <span id="wpl-widget-favorites-count<?php echo esc_attr($widget_id); ?>"><?php echo esc_html($this->favorites_count); ?></span>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <div class="wpl_search_widget_links">
                        <?php if(wpl_global::check_addon('save_searches') and wpl_global::get_setting('ss_button_status')): ?>
                            <div class="wpl-save-search-wp wpl-plisting-link-btn">
                                <a id="wpl_save_search_link_lightbox" class="wpl-save-search-link" data-realtyna-href="#wpl_plisting_lightbox_content_container" onclick="return wpl_generate_save_search();" data-realtyna-lightbox-opts="title:<?php echo esc_attr__('Save this Search', 'sesame'); ?>"><span><?php echo esc_html__('Save', 'sesame'); ?></span></a>
                            </div>
                        <?php endif; ?>
                        <?php if(wpl_global::check_addon('pro') and wpl_global::get_setting('listings_rss_enabled')): ?>
                            <div class="wpl-rss-wp">
                                <a class="wpl-rss-link" href="#" onclick="wpl_generate_rss();"><span><?php echo esc_html__('RSS', 'sesame'); ?></span></a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="wpl_search_from_box_bot" id="wpl_search_from_box_bot<?php echo esc_attr($widget_id); ?>">
                    <?php echo $bott_div; ?>
                </div>
            </div>
            <?php if($bott_div): ?>
            <div class="more_search_option" data-widget-id="<?php echo esc_attr($widget_id); ?>" id="more_search_option<?php echo esc_attr($widget_id); ?>"><?php echo esc_html__('More options', 'sesame'); ?></div>
            <?php endif; ?>
        </div>
    </form>
</div>

<?php if($this->more_options_type): ?>
<!-- Advanced Search -->
<div id="wpl_advanced_search<?php echo esc_attr($widget_id); ?>" class="wpl-advanced-search-wp wpl-util-hidden">
    <div class="container">
        <div id="wpl_form_override_search<?php echo esc_attr($widget_id); ?>" class="wpl-advanced-search-popup">
            
        </div>
    </div>
</div>
<?php endif;
// Import JS Codes
$this->_wpl_import('widgets.search.scripts.js', true, true);