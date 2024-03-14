<?php
    global $shortcode_tags;

    // get the max number of suggestions that will be shown
    $max_suggestion_count = get_option('wpil_max_suggestion_count', 0);

    // get the content formatting level
    $formatting_level = Wpil_Settings::getContentFormattingLevel();

    // get the section skip type
    $skip_type = Wpil_Settings::getSkipSectionType();

?>
<div class="wrap wpil_styles" id="settings_page">
    <?=Wpil_Base::showVersion()?>
    <h1 class="wp-heading-inline"><?php _e('Link Whisper Settings', 'wpil'); ?></h1>
    <hr class="wp-header-end">
    <div id="poststuff">
        <div id="post-body" class="metabox-holder">
            <div id="post-body-content" style="position: relative;">
                <?php if (isset($_REQUEST['success'])) : ?>
                    <div class="notice update notice-success" id="wpil_message" >
                        <p><?php _e('The Link Whisper Settings have been updated successfully!', 'wpil'); ?></p>
                    </div>
                <?php endif; ?>
                <?php if(!extension_loaded('mbstring')){?>
                    <div class="notice update notice-error" id="wpil_message" >
                        <p><?php _e('Dependency Missing: Multibyte String.', 'wpil'); ?></p>
                        <p><?php _e('The Multibyte String PHP extension is not active on your site. Link Whisper uses this extension to process text when making suggestions. Without this extension, Link Whisper will not be able to make suggestions.', 'wpil'); ?></p>
                        <p><?php _e('Please contact your hosting provider about enabling the Multibyte String PHP extension.', 'wpil'); ?></p>
                    </div>
                <?php } ?>
                <?php if(!extension_loaded('zlib') && !extension_loaded('Bz2')){?>
                    <div class="notice update notice-error" id="wpil_message" >
                        <p><?php _e('Dependency Missing: Data Compression Library.', 'wpil'); ?></p>
                        <p><?php _e('Link Whisper hasn\'t detected a useable compression library on this site. Link Whisper uses compression libraries to reduce how much memory is used when generating suggestions.', 'wpil'); ?></p>
                        <p><?php _e('It will try to generate suggestions without compressing the suggestion data. If Link Whisper runs out of memory, the suggestion loading will hang in place indefinitely.', 'wpil'); ?></p>
                        <p><?php _e('If you experience this, please contact your hosting provider about enabling either the "Zlib" compression library, or the "Bzip2" compression library.', 'wpil'); ?></p>
                    </div>
                <?php } ?>
                <?php if(!function_exists('base64_decode') || !function_exists('base64_encode')){?>
                    <div class="notice update notice-error" id="wpil_message" >
                        <p><?php _e('Dependency Missing: Base64 String Processing.', 'wpil'); ?></p>
                        <p><?php _e('It appears that the "base64_decode" or the "base64_encode" functions aren\'t available. Link Whisper uses these functions to store and process text data in a way that prevents formatting mistakes.', 'wpil'); ?></p>
                        <p><?php _e('Without these functions, Link Whisper won\'t be able to preform many of it\'s operations, including Suggestion Generation, Link Deleting, and Autolink Creating.', 'wpil'); ?></p>
                        <p><?php _e('Please contact your hosting provider or developer about enabling these functions.', 'wpil'); ?></p>
                    </div>
                <?php } ?>
                <form name="frmSaveSettings" id="frmSaveSettings" action='' method='post'>
                    <?php wp_nonce_field('wpil_save_settings','wpil_save_settings_nonce'); ?>
                    <input type="hidden" name="hidden_action" value="wpil_save_settings" />
                    <input type="hidden" name="wpil_related_post_preview_nonce" value="<?php echo wp_create_nonce('wpil-related-posts-preview-nonce');?>" />
                    <table class="form-table">
                        <tbody>
                        <tr>
                            <td scope='row'><?php _e('Ignore numbers', 'wpil'); ?></td>
                            <td>
                                <input type="hidden" name="wpil_2_ignore_numbers" value="0" />
                                <input type="checkbox" name="wpil_2_ignore_numbers" <?=get_option('wpil_2_ignore_numbers')==1?'checked':''?> value="1" />
                            </td>
                        </tr>
                        <tr class="wpil-general-settings wpil-setting-row">
                            <td scope='row'><?php _e('Selected Language', 'wpil'); ?></td>
                            <td>
                                <select id="wpil-selected-language" name="wpil_selected_language">
                                    <?php
                                        $languages = Wpil_Settings::getSupportedLanguages();
                                        $selected_language = Wpil_Settings::getSelectedLanguage();
                                    ?>
                                    <?php foreach($languages as $language_key => $language_name) : ?>
                                        <option value="<?php echo $language_key; ?>" <?php selected($language_key, $selected_language); ?>><?php echo $language_name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="hidden" id="wpil-currently-selected-language" value="<?php echo $selected_language; ?>">
                                <input type="hidden" id="wpil-currently-selected-language-confirm-text-1" value="<?php echo esc_attr__('Changing Link Whisper\'s language will replace the current Words to be Ignored with a new list of words.', 'wpil') ?>">
                                <input type="hidden" id="wpil-currently-selected-language-confirm-text-2" value="<?php echo esc_attr__('If you\'ve added any words to the Words to be Ignored area, this will erase them.', 'wpil') ?>">
                            </td>
                        </tr>
                        <tr class="wpil-general-settings wpil-setting-row">
                            <td scope='row'><?php _e('Words to be Ignored', 'wpil'); ?></td>
                            <td>
                                <?php
                                    $lang_data = array();
                                    foreach(Wpil_Settings::getAllIgnoreWordLists() as $lang_id => $words){
                                        $lang_data[$lang_id] = $words;
                                    }
                                ?>
                                <textarea id='ignore_words_textarea' class='regular-text' style="float:left;" rows=10><?php echo esc_textarea(implode("\n", $lang_data[$selected_language])); ?></textarea>
                                <input type="hidden" name='ignore_words' id='ignore_words' value="<?php echo base64_encode(implode("\n", $lang_data[$selected_language])); ?>">
                                <div class="wpil_help">
                                    <i class="dashicons dashicons-editor-help"></i>
                                    <div><?php _e('Link Whisper will ignore these words when making linking suggestions. Please enter each word on a new line', 'wpil'); ?></div>
                                </div>
                                <input type="hidden" id="wpil-available-language-word-lists" value="<?php echo esc_attr( wp_json_encode($lang_data, JSON_UNESCAPED_UNICODE) ); ?>">
                            </td>
                        </tr>
                        <tr>
                            <td scope='row'><?php _e('Post Types to Create Links For', 'wpil'); ?></td>
                            <td>
                                <div style="display: inline-block;">
                                    <div class="wpil_help" style="float:right; position: relative; left: 30px;">
                                        <i class="dashicons dashicons-editor-help" style="margin-top: 6px;"></i>
                                        <div>
                                            <?php
                                                _e('This setting controls the post types that Link Whisper is active in.', 'wpil');
                                                echo '<br /><br />';
                                                _e('Link Whisper will create links in the selected post types, scan the post types for links, and will operate all of Link Whisper\'s Advanced Functionality in the post types.', 'wpil');
                                                echo '<br /><br />';
                                                _e('After changing the post type selection, please go to the Report page and click the "Run a Link Scan" button to clear the old link data.', 'wpil');
                                            ?>
                                        </div>
                                    </div>
                                    <?php foreach ($types_available as $type => $label) : ?>
                                        <input type="checkbox" name="wpil_2_post_types[]" value="<?=$type?>" <?=in_array($type, $types_active)?'checked':''?>><label><?=ucfirst($label)?></label><br>
                                    <?php endforeach; ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="wpil-general-settings wpil-setting-row wpil-suggestion-post-type-limit-setting <?php echo (empty(get_option('wpil_limit_suggestions_to_post_types', false))) ? 'hide-setting': '';?>">
                            <td scope='row'><?php _e('Post Types to Point Suggestions to', 'wpil'); ?></td>
                            <td>
                                <div style="display: inline-block;">
                                    <div class="wpil_help" style="float:right; position: relative; left: 30px;">
                                        <i class="dashicons dashicons-editor-help" style="margin-top: 6px;"></i>
                                        <div>
                                            <?php _e('Link Whisper will only offer suggestions that point to posts in the selected post types.', 'wpil'); ?>
                                            <br /><br />
                                            <?php _e('Only post types that Link Whisper is set to process will be listed here. If you don\'t see a post type listed here, please try selecting it in the "Post Types to Create Links For" setting.', 'wpil'); ?>
                                        </div>
                                    </div>
                                    <?php foreach ($types_available as $type => $label) : ?>
                                        <?php 
                                            $class = 'wpil-suggestion-limit-type-' . $type;
                                            $class .= !in_array($type, $types_active) ? ' hide-setting': ''; 
                                        ?>
                                        <input type="checkbox" name="wpil_suggestion_limited_post_types[]" value="<?=$type?>" <?php echo in_array($type, $suggestion_types_active)?'checked':''?> class="<?php echo $class; ?>"><label class="<?php echo $class; ?>"><?=ucfirst($label)?></label><br class="<?php echo $class; ?>">
                                    <?php endforeach; ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="wpil-general-settings wpil-setting-row">
                            <td scope='row'><?php _e('Term Types to Process', 'wpil'); ?></td>
                            <td>
                                <div style="display: inline-block;">
                                    <div class="wpil_help" style="float:right; position: relative; left: 30px;">
                                        <i class="dashicons dashicons-editor-help" style="margin-top: 6px;"></i>
                                        <div>
                                            <?php
                                                _e('This setting controls the term types that Link Whisper is active in.', 'wpil');
                                                echo '<br /><br />';
                                                _e('Link Whisper will create links in the selected term\'s archive pages, scan the term\'s archive pages for links, and will operate all of Link Whisper\'s Advanced Functionality in the term\'s archive pages.', 'wpil');
                                                echo '<br /><br />';
                                                _e('After changing the term type selection, please go to the Report page and click the "Run a Link Scan" button to clear the old link data.', 'wpil');
                                            ?>
                                        </div>
                                    </div>
                                    <?php foreach ($term_types_available as $type) : ?>
                                        <input type="checkbox" name="wpil_2_term_types[]" value="<?=$type?>" <?=in_array($type, $term_types_active)?'checked':''?>><label><?=ucfirst($type)?></label><br>
                                    <?php endforeach; ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="wpil-general-settings wpil-setting-row">
                            <td scope="row"><span><?php _e('Number of', 'wpil'); ?></span>
                                <select name="wpil_skip_section_type" class="wpil-setting-inline-select">
                                    <option value="sentences"<?php selected($skip_type, 'sentences');?>><?php _e('Sentences', 'wpil'); ?></option>
                                    <option value="paragraphs"<?php selected($skip_type, 'paragraphs');?>><?php _e('Paragraphs', 'wpil'); ?></option>
                                </select>
                                <span><?php _e('to Skip', 'wpil');?></span>
                            </td>
                            <td>
                                <select name="wpil_skip_sentences" style="float:left; max-width:100px">
                                    <?php for($i = 0; $i <= 10; $i++) : ?>
                                        <option value="<?=$i?>" <?=$i==Wpil_Settings::getSkipSentences() ? 'selected' : '' ?>><?=$i?></option>
                                    <?php endfor; ?>
                                </select>
                                <div class="wpil_help">
                                    <i class="dashicons dashicons-editor-help" style="margin-top: 4px;"></i>
                                    <div><?php _e('Link Whisper will not suggest links for this number of sentences or paragraphs appearing at the beginning of a post.', 'wpil'); ?></div>
                                </div>
                            </td>
                        </tr>
                        <tr class="wpil-general-settings wpil-setting-row">
                            <td scope="row"><?php _e('Max Number of Suggestions to Display', 'wpil'); ?></td>
                            <td>
                                <select name="wpil_max_suggestion_count" style="float:left; max-width:100px">
                                    <option value="0" <?=0===(int)$max_suggestion_count ? 'selected' : '' ?>><?php _e('No Limit', 'wpil'); ?></option>
                                    <?php for($i = 1; $i <= 100; $i++) : ?>
                                        <option value="<?=$i?>" <?=$i===(int)$max_suggestion_count ? 'selected' : '' ?>><?php echo $i; ?></option>
                                    <?php endfor; ?>
                                </select>
                                <div class="wpil_help">
                                    <i class="dashicons dashicons-editor-help" style="margin-top: 4px;"></i>
                                    <div style="margin: -130px 0px 0px 30px;">
                                        <?php 
                                        _e('This is the maximum number of suggestions that Link Whisper will show you at once in the Suggestion Panels.', 'wpil');
                                        ?>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php if(class_exists('ACF')){ ?>
                        <tr>
                            <td scope='row'><?php _e('Disable Linking for Advanced Custom Fields', 'wpil'); ?></td>
                            <td>
                                <input type="hidden" name="wpil_disable_acf" value="0" />
                                <div style="max-width: 80px;">
                                    <input type="checkbox" name="wpil_disable_acf" <?=get_option('wpil_disable_acf', false)==1?'checked':''?> value="1" />
                                    <div class="wpil_help" style="float: right;">
                                        <i class="dashicons dashicons-editor-help" style="margin-top: 6px;"></i>
                                        <div style="margin-left: 30px; margin-top: -190px;">
                                            <p><?php _e('Checking this will tell Link Whisper to not process any data created by Advanced Custom Fields.', 'wpil'); ?></p>
                                            <p><?php _e('This will speed up the suggestion making and data saving, but will not update the ACF data.', 'wpil'); ?></p>
                                            <p><?php _e('If you don\'t see Advanced Custom Fields in your Installed Plugins list, it may be included as a component in a plugin or your theme.', 'wpil'); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                        <tr class="wpil-advanced-settings wpil-setting-row">
                            <td scope='row'><?php _e('Content Formatting Level in Link Scan', 'wpil'); ?></td>
                            <td>
                                <input type="range" name="wpil_content_formatting_level" class="wpil-thick-range" min="0" max="2" value="<?php echo $formatting_level; ?>">
                                <div class="wpil_help" style="display: inline-block; float: none; margin: 0px 0 0 5px;">
                                    <i class="dashicons dashicons-editor-help"></i>
                                    <div style="width: 340px;margin-top: -280px;">
                                        <?php _e('The setting controls how much content formatting Link Whisper does with content when searching it for links.', 'wpil'); ?>
                                        <br />
                                        <br />
                                        <?php _e('By default, Link Whisper fully formats the content with WordPress\'s "the_content" filter so it\'s closer to what a visitor would see.', 'wpil'); ?>
                                        <br />
                                        <br />
                                        <?php _e('But for some themes and page builders, this causes issues with links. And the answer is to reduce how much Link Whisper formats the content.', 'wpil'); ?>
                                        <br />
                                        <br />
                                        <?php _e('Setting this to "Only Shortcodes" will render the shortcodes in post content, but otherwise leave the content unchanged. Setting it to "No Formatting" will disable the formatting entirely.', 'wpil'); ?>
                                    </div>
                                    </div>
                                </div>
                                <div>
                                    <span style="<?php echo ($formatting_level === 0) ? '': 'display:none';?>" class="wpil-content-formatting-text wpil-format-0"><?php _e('No Formatting', 'wpil'); ?></span>
                                    <span style="<?php echo ($formatting_level === 1) ? '': 'display:none';?>" class="wpil-content-formatting-text wpil-format-1"><?php _e('Only Shortcodes', 'wpil'); ?></span>
                                    <span style="<?php echo ($formatting_level === 2) ? '': 'display:none';?>" class="wpil-content-formatting-text wpil-format-2"><?php _e('Full Formatting', 'wpil'); ?></span>
                                </div>
                            </td>
                        </tr>
                        <tr class="wpil-advanced-settings wpil-setting-row">
                            <td scope='row'><?php _e('Override Global Post During Link Scan', 'wpil'); ?></td>
                            <td>
                                <input type="hidden" name="wpil_override_global_post_during_scan" value="0" />
                                <input type="checkbox" name="wpil_override_global_post_during_scan" <?=!empty(get_option('wpil_override_global_post_during_scan', false))?'checked':''?> value="1" />
                                <div class="wpil_help" style="display: inline-block; float: none; margin: 0px 0 0 5px;">
                                    <i class="dashicons dashicons-editor-help"></i>
                                    <div style="width: 340px; margin-top: -300px;">
                                        <?php _e('This setting temporarily overrides global WordPress $post variable with one that matches the post currently being scanned.', 'wpil'); ?>
                                        <br />
                                        <br />
                                        <?php _e('This is a compatibility measure for shortcodes that rely on the global $post variable to get content information, or to conditionally display content.', 'wpil'); ?>
                                        <br />
                                        <br />
                                        <?php _e('When the post scanning is completed, the $post variable is reset to its original value.', 'wpil'); ?>
                                        <br />
                                        <br />
                                        <?php _e('One of the main indicators that this needs to be activated is if after the Link Scan completes, many posts are reporting that they have the same links. Especially if they\'re from "related post" sections.', 'wpil'); ?>
                                    </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php /*
                        <tr>
                            <td scope='row'><?php _e('Count Related Post Links', 'wpil'); ?></td>
                            <td>
                                <div style="max-width:80px;">
                                    <input type="hidden" name="wpil_count_related_post_links" value="0" />
                                    <input type="checkbox" name="wpil_count_related_post_links" <?=get_option('wpil_count_related_post_links')==1?'checked':''?> value="1" />
                                    <div class="wpil_help" style="float:right;">
                                        <i class="dashicons dashicons-editor-help" style="margin-top: 6px;"></i>
                                        <div>
                                            <?php _e('Turning this on will tell Link Whisper to scan and process links in related post areas that are separate from the post content.', 'wpil'); ?>
                                            <br>
                                            <br>
                                            <?php _e('Currently supports links generated by YARPP.', 'wpil'); ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        */ ?>
                        <tr class="wpil-advanced-settings wpil-setting-row">
                            <td scope='row'><?php _e('Monitor Link Changes in Gutenberg Reusable Blocks', 'wpil'); ?></td>
                            <td>
                                <input type="hidden" name="wpil_update_reusable_block_links" value="0" />
                                <input type="checkbox" name="wpil_update_reusable_block_links" <?=!empty(get_option('wpil_update_reusable_block_links', false))?'checked':''?> value="1" />
                                <div class="wpil_help" style="display: inline-block; float: none; margin: 0px 0 0 5px;">
                                    <i class="dashicons dashicons-editor-help"></i>
                                    <div>
                                        <?php _e('Checking this option will tell Link Whisper to monitor changes to Gutenberg reusable blocks and update the link stats of any posts that use the modified blocks.', 'wpil'); ?>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr class="wpil-advanced-settings wpil-setting-row">
                            <td scope='row'><?php _e('Use "Ugly" Permalinks In Reports', 'wpil'); ?></td>
                            <td>
                                <input type="hidden" name="wpil_use_ugly_permalinks" value="0" />
                                <input type="checkbox" name="wpil_use_ugly_permalinks" <?=!empty(get_option('wpil_use_ugly_permalinks', false))?'checked':''?> value="1" />
                                <div class="wpil_help" style="display: inline-block; float: none; margin: 0px 0 0 5px;">
                                    <i class="dashicons dashicons-editor-help"></i>
                                    <div style="width: 300px;">
                                        <?php _e('Checking this will tell Link Whisper to use WordPress\' "Ugly Permalinks" for the "View" links in the Link Whisper Reports.', 'wpil'); ?>
                                        <br />
                                        <br />
                                        <?php _e('Using the "Ugly" permalinks can save a surprising amount of time when loading the reports because we don\'t have to process all the rules required to calculate the correct URL for each post.', 'wpil'); ?>
                                        <br />
                                        <br />
                                        <?php _e('One downside is that the Link Report\'s "Hidden by Redirect" icons may not be able to tell that the post is hidden, so the icons may fail to display on redirected posts.', 'wpil'); ?>
                                        <br />
                                        <br />
                                        (<?php _e('This won\'t affect the inserted links or Suggestions, and it also won\'t change the links on the site itself. The "Ugly" permalinks will only be used for the Link Whisper "View" buttons in the Reports.', 'wpil'); ?>)</div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr class="wpil-content-ignoring-settings wpil-setting-row">
                            <td scope='row'><?php _e('Shortcodes to Ignore by Name.', 'wpil'); ?></td>
                            <td>
                                <textarea name='wpil_ignore_shortcodes_by_name' id='wpil_ignore_shortcodes_by_name' style="width: 400px;float:left;" class='regular-text' rows=10><?php echo esc_textarea(get_option('wpil_ignore_shortcodes_by_name', '')); ?></textarea>
                                <div class="wpil_help">
                                    <i class="dashicons dashicons-info"></i>    
                                    <div style="margin: 0px 0px 0px -500px; width: 500px; overflow: auto; max-height: 200px;">
                                        <?php 
                                        echo '<h3 style="color:#fff; margin-top: 0px;">';
                                        _e('The known shortcode names are:', 'wpil');
                                        echo '</h3>';
                                        echo '<thing style="display:flex; flex-wrap: wrap;">'; // not div since that gets hidden in wpil_helps
                                        foreach($shortcode_tags as $tag_name => $dat){
                                            echo '<span style="padding: 0 10px 0 0;">' . $tag_name . '</span>';
                                        }
                                        echo '</thing>';
                                        echo '<br />';
                                        echo '<br />';
                                        echo '<span style="color:#fff;">';
                                        echo '(' . __('There may be other shortcodes active, but this is what we could find.', 'wpil') . ')';
                                        echo '</span>';
                                        ?>
                                    </div>
                                </div>
                                <div class="wpil_help">
                                    <i class="dashicons dashicons-editor-help"></i>
                                    <div style="margin: -160px 0px 0px 30px; width: 300px;">
                                        <?php 
                                        _e('Link Whisper will ignore any shortcodes listed in this field. It won\'t extract links from the listed shortcodes, or create links in any text content of the shortcode.', 'wpil');
                                        echo '<br /><br />';
                                        _e('To ignore a shortcode, enter it\'s name (without square brackets) in this field on it\'s own line.', 'wpil');
                                        echo '<br /><br />';
                                        _e('So for example, to ignore the WordPress [caption][/caption] shortcode, enter "caption" (without quotes) on it\'s own line in the field', 'wpil');
                                        echo '<br /><br />';
                                        _e('After entering a shortcode, you may want to run a link scan to refresh any stored link data based on shortcodes.', 'wpil');
                                        echo '<br /><br />';
                                        ?>
                                    </div>
                                </div>
                                <div style="clear:both;"></div>
                            </td>
                        </tr>
                        <tr>
                            <td scope='row'><?php _e('Delete all Link Whisper Data', 'wpil'); ?></td>
                            <td>
                                <div style="max-width:80px;">
                                    <input type="hidden" name="wpil_delete_all_data" value="0" />
                                    <input type="checkbox" class="danger-zone" name="wpil_delete_all_data" <?=get_option('wpil_delete_all_data', false)==1?'checked':''?> value="1" />
                                    <input type="hidden" class="wpil-delete-all-data-message" value="<?php echo sprintf(__('Activating this will tell Link Whisper to delete ALL link Whisper related data when the plugin is deleted. %s This will remove all settings and stored data. Links inserted into content by Link Whisper will still exist. %s Please only activate this option if you\'re sure you want to delete all data.', 'wpil'), '&lt;br&gt;&lt;br&gt;', '&lt;br&gt;&lt;br&gt;'); ?>">
                                    <div class="wpil_help" style="float:right;">
                                        <i class="dashicons dashicons-editor-help" style="margin-top: 6px;"></i>
                                        <div style="margin: -260px 0 0 30px;">
                                            <?php _e("Activating this will tell Link Whisper to delete ALL link Whisper related data when the plugin is deleted.", 'wpil'); ?>
                                            <br>
                                            <br>
                                            <?php _e("This includes any Settings, Autolinking Rules, URL Changing Rules, and Report Data. This will not delete any links that have been created.", 'wpil'); ?>
                                            <br>
                                            <br>
                                            <?php _e("Please only activate this option if you're sure you want to delete ALL link Whisper data.", 'wpil'); ?>
                                            <br>
                                            <br>
                                            <?php _e("It is not required to delete the data when upgrading to the Premium version of Link Whisper.", 'wpil'); ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <p class='submit'>
                        <input type='submit' name='btnsave' id='btnsave' value="<?php echo esc_attr__('Save Settings', 'wpil'); ?>" class='button-primary' />
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>