<?php

function easywpstuff_assesplus_booster_register_options_page() {
    add_options_page(
        'Accessibility Plus Options',
        'Accessibility Plus',
        'manage_options',
        'accessibilityplus',
        'easywpstuff_assesplus_booster_options_page'
    );
 
    add_settings_section(
        'easywpstuff_assesplus_booster_section_accessibility',
        'Accessibility Options',
        'easywpstuff_assesplus_booster_section_accessibility_cb',
        'easywpstuff_assesplus_booster_options'
    );
	
	add_settings_section(
        'easywpstuff_assesplus_booster_section_seo',
        'SEO Options',
        'easywpstuff_assesplus_booster_section_seo_cb',
        'easywpstuff_assesplus_booster_options'
    );
 
    add_settings_field(
        'easywpstuff_assesplus_booster_field_no_labels',
        'Form elements do not have associated labels',
        'easywpstuff_assesplus_booster_field_checkbox_cb',
        'easywpstuff_assesplus_booster_options',
        'easywpstuff_assesplus_booster_section_accessibility',
        [
            'label_for' => 'easywpstuff_assesplus_booster_field_no_labels',
            'class' => 'easywpstuff_assesplus_booster_row',
            'easywpstuff_assesplus_booster_custom_data' => 'custom',
        ]
    );
 
    add_settings_field(
        'easywpstuff_assesplus_booster_field_no_discernible_name',
        'Links do not have a discernible name',
        'easywpstuff_assesplus_booster_field_checkbox_cb',
        'easywpstuff_assesplus_booster_options',
        'easywpstuff_assesplus_booster_section_accessibility',
        [
            'label_for' => 'easywpstuff_assesplus_booster_field_no_discernible_name',
            'class' => 'easywpstuff_assesplus_booster_row',
            'easywpstuff_assesplus_booster_custom_data' => 'custom',
        ]
    );
	
	add_settings_field(
        'easywpstuff_assesplus_booster_field_no_accessible_name',
        'Buttons do not have an accessible name',
        'easywpstuff_assesplus_booster_field_checkbox_cb',
        'easywpstuff_assesplus_booster_options',
        'easywpstuff_assesplus_booster_section_accessibility',
        [
            'label_for' => 'easywpstuff_assesplus_booster_field_no_accessible_name',
            'class' => 'easywpstuff_assesplus_booster_row',
            'easywpstuff_assesplus_booster_custom_data' => 'custom',
        ]
    );
	
	add_settings_field(
        'easywpstuff_assesplus_booster_field_viewport_meta_tag',
        '[user-scalable="no"] is used in the &lt;meta name="viewport"&gt; element or the [maximum-scale] attribute is less than 5',
        'easywpstuff_assesplus_booster_field_checkbox_cb',
        'easywpstuff_assesplus_booster_options',
        'easywpstuff_assesplus_booster_section_accessibility',
        [
            'label_for' => 'easywpstuff_assesplus_booster_field_viewport_meta_tag',
            'class' => 'easywpstuff_assesplus_booster_row',
            'easywpstuff_assesplus_booster_custom_data' => 'custom',
        ]
    );
	
	add_settings_field(
        'easywpstuff_assesplus_booster_field_menuitem_accessible',
        'button, link, and menuitem elements do not have accessible names.',
        'easywpstuff_assesplus_booster_field_checkbox_cb',
        'easywpstuff_assesplus_booster_options',
        'easywpstuff_assesplus_booster_section_accessibility',
        [
            'label_for' => 'easywpstuff_assesplus_booster_field_menuitem_accessible',
            'class' => 'easywpstuff_assesplus_booster_row',
            'easywpstuff_assesplus_booster_custom_data' => 'custom',
        ]
    );
	
	add_settings_field(
        'easywpstuff_assesplus_booster_field_iframe_title_tag',
        '&lt;frame&gt or &lt;iframe&gt elements do not have a title.',
        'easywpstuff_assesplus_booster_field_checkbox_cb',
        'easywpstuff_assesplus_booster_options',
        'easywpstuff_assesplus_booster_section_accessibility',
        [
            'label_for' => 'easywpstuff_assesplus_booster_field_iframe_title_tag',
            'class' => 'easywpstuff_assesplus_booster_row',
            'easywpstuff_assesplus_booster_custom_data' => 'custom',
        ]
    );
	
	add_settings_field(
        'easywpstuff_assesplus_add_aria_label_to_progressbar',
        'ARIA progressbar elements do not have accessible names.',
        'easywpstuff_assesplus_booster_field_checkbox_cb',
        'easywpstuff_assesplus_booster_options',
        'easywpstuff_assesplus_booster_section_accessibility',
        [
            'label_for' => 'easywpstuff_assesplus_add_aria_label_to_progressbar',
            'class' => 'easywpstuff_assesplus_booster_row',
            'easywpstuff_assesplus_booster_custom_data' => 'custom',
        ]
    );
	add_settings_field(
        'easywpstuff_assesplus_modify_tabindex_to_zero',
        'Some elements have a [tabindex] value greater than 0',
        'easywpstuff_assesplus_booster_field_checkbox_cb',
        'easywpstuff_assesplus_booster_options',
        'easywpstuff_assesplus_booster_section_accessibility',
        [
            'label_for' => 'easywpstuff_assesplus_modify_tabindex_to_zero',
            'class' => 'easywpstuff_assesplus_booster_row',
            'easywpstuff_assesplus_booster_custom_data' => 'custom',
        ]
    );
	
	add_settings_field(
        'easywpstuff_assesplus_links_not_crawlable',
        'Links are not crawlable.',
        'easywpstuff_assesplus_booster_field_checkbox_cb',
        'easywpstuff_assesplus_booster_options',
        'easywpstuff_assesplus_booster_section_seo',
        [
            'label_for' => 'easywpstuff_assesplus_links_not_crawlable',
            'class' => 'easywpstuff_assesplus_booster_row',
            'easywpstuff_assesplus_booster_custom_data' => 'custom',
        ]
    );
	
	add_settings_field(
        'easywpstuff_assesplus_img_donot_alt',
        'Image elements do not have [alt] attributes.',
        'easywpstuff_assesplus_booster_field_checkbox_cb',
        'easywpstuff_assesplus_booster_options',
        'easywpstuff_assesplus_booster_section_seo',
        [
            'label_for' => 'easywpstuff_assesplus_img_donot_alt',
            'class' => 'easywpstuff_assesplus_booster_row',
            'easywpstuff_assesplus_booster_custom_data' => 'custom',
        ]
    );
 
    register_setting(
        'easywpstuff_assesplus_booster_options',
        'easywpstuff_assesplus_booster_options',
        'easywpstuff_assesplus_booster_options_sanitize'
    );
}
add_action( 'admin_menu', 'easywpstuff_assesplus_booster_register_options_page' );

function easywpstuff_assesplus_booster_options_page() {
    // check user capabilities
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
 
    // add error/update messages
 
    // check if the user have submitted the settings
    // wordpress will add the "settings-updated" $_GET parameter to the url
    if ( isset( $_GET['settings-updated'] ) ) {
        // add settings saved message with the class of "updated"
        add_settings_error( 'easywpstuff_assesplus_booster_messages', 'easywpstuff_assesplus_booster_message', __( 'Settings Saved. Clear Cache if you are using any cache plugin', 'easywpstuff_assesplus_booster' ), 'updated' );
    }
 
    // show error/update messages
    settings_errors( 'easywpstuff_assesplus_booster_messages' );
    ?><div class="lhbmain">
    <div class="lhbwrap">
        
        <form action="options.php" method="post">
		<h1>Accessibility Plus </h1>
            <?php
            // output security fields for the registered setting "easywpstuff_assesplus_booster_options"
            settings_fields( 'easywpstuff_assesplus_booster_options' );
            // output setting sections and their fields
            // (sections are registered for "easywpstuff_assesplus_booster_options", each field is registered to a specific section)
            do_settings_sections( 'easywpstuff_assesplus_booster_options' );
            // output save settings button
            submit_button( 'Save Settings' );
	
	        $easyurl = site_url( '', 'https' );
            $siteurl = preg_replace( '(^https?://)', '', $easyurl ); 
            ?> <!--<a class="happy" href="https://easywpstuff.com/go/webdexdev" target="_blank">Get upto 90+ pagespeed score</a> <a class="issue" href="https://easywpstuff.com/go/ju0q" target="_blank">Slow backend? try cloud hosting</a> -->
        </form>
    </div><div class="lhbside">
		<p>
			Convert Images to WEBP or AVIF on fly, Lazyload images, fix the following PageSpeed Recommendations with a super Lightweight Plugin (32KB only)
	</p><p class="pli">
	✅ Serve images in next-gen formats.
	</p><p class="pli">
	✅ Properly size images.
	</p>
	<p class="pli">
	✅ Images don't have width and height.
	</p> <a class="downeasy" href="https://<?php echo $siteurl; ?>/wp-admin/plugin-install.php?s=easyoptimizer&tab=search&type=term">Install and Activate EasyOptimizer</a>
</div></div>
    <?php
}


// Display the section for accessibility options
function easywpstuff_assesplus_booster_section_accessibility_cb() {
    echo '<p class="desc">Enable only those options that are required for your website. it will not work for those elements added by Javascript after page load or after plugin load.</p>';
}
 
function easywpstuff_assesplus_booster_section_seo_cb() {
    echo '<p>Enable only those options that are required for your website</p>';
}
// Display the checkbox fields
function easywpstuff_assesplus_booster_field_checkbox_cb( $args ) {
    $options = get_option( 'easywpstuff_assesplus_booster_options' );
 
    $label_for = $args['label_for'];
    $class = $args['class'];
    $custom_data = $args['easywpstuff_assesplus_booster_custom_data'];
 
    $value = isset( $options[$label_for] ) ? 1 : 0;
 
    ?>
<div class="checkbox-wrapper-8"><input type="checkbox" id="<?php echo esc_attr( $label_for ); ?>" name="easywpstuff_assesplus_booster_options[<?php echo esc_attr( $label_for ); ?>]" value="1" <?php checked( $value, 1 ); ?> class="tgl tgl-skewed <?php echo esc_attr( $class ); ?>" data-custom="<?php echo esc_attr( $custom_data ); ?>" /><label class="tgl-btn" data-tg-off="OFF" data-tg-on="ON" for="<?php echo esc_attr( $label_for ); ?>"></label></div>
    <?php
}
 
// Sanitize user input
function easywpstuff_assesplus_booster_options_sanitize( $input ) {
    $output = [];
 
    // Loop through each option and sanitize the input
    foreach ( $input as $key => $value ) {
        if ( $key == 'easywpstuff_assesplus_booster_field_no_labels' ) {
            $output[$key] = absint( $value );
        } elseif ( $key == 'easywpstuff_assesplus_booster_field_no_discernible_name' ) {
            $output[$key] = absint( $value );
        } elseif ( $key == 'easywpstuff_assesplus_booster_field_no_accessible_name' ) {
            $output[$key] = absint( $value );
        } elseif ( $key == 'easywpstuff_assesplus_booster_field_viewport_meta_tag' ) {
            $output[$key] = absint( $value );
        } elseif ( $key == 'easywpstuff_assesplus_booster_field_menuitem_accessible' ) {
            $output[$key] = absint( $value );
        } elseif ( $key == 'easywpstuff_assesplus_booster_field_iframe_title_tag' ) {
            $output[$key] = absint( $value );
		} elseif ( $key == 'easywpstuff_assesplus_add_aria_label_to_progressbar' ) {
            $output[$key] = absint( $value );
		} elseif ( $key == 'easywpstuff_assesplus_links_not_crawlable' ) {
            $output[$key] = absint( $value );
		} elseif ( $key == 'easywpstuff_assesplus_img_donot_alt' ) {
            $output[$key] = absint( $value );
		} elseif ( $key == 'easywpstuff_assesplus_modify_tabindex_to_zero' ) {
            $output[$key] = absint( $value );
		}
    }
 
    return $output;
}