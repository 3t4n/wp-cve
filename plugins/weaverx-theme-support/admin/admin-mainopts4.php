<?php
if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly
/* Weaver Xtreme - admin Main Options
 *
 *  __ added: 12/9/14
 * This function will start the main sapi form, which will be closed in admin-adminopts
 */

function weaverx_admin_mainopts4(): void
{
    // Weaver 4 legacy interface
    ?>
    <div id="tabwrap_main" style="padding-left:4px;">

        <div id="tab-container-main" class='yetiisub'>
            <ul id="tab-container-main-nav" class='yetiisub'>
                <?php
                weaverx_elink('#asp_genappear', esc_html__('Wrapping background colors, rounded corners, borders, fade, shadow', 'weaver-xtreme' /*adm*/), esc_html__('Wrapping Areas', 'weaver-xtreme' /*adm*/), '<li>', '</li>');
                weaverx_elink('#asp_widgets', esc_html__('Settings for Sidebars and Sidebar Layout', 'weaver-xtreme' /*adm*/), esc_html__('Sidebars &amp; Layout', 'weaver-xtreme' /*adm*/), '<li>', '</li>');
                weaverx_elink('#asp_full', esc_html__('Settings to create full width sites', 'weaver-xtreme' /*adm*/), esc_html__('Full Width', 'weaver-xtreme' /*adm*/), '<li>', '</li>');
                weaverx_elink('#asp_headeropts', esc_html__('Site Title/Tagline properties, Header Image', 'weaver-xtreme' /*adm*/), esc_html__('Header', 'weaver-xtreme' /*adm*/), '<li>', '</li>');
                weaverx_elink('#asp_menus', esc_html__('Menu text and bg colors and other properties; Info Bar properties', 'weaver-xtreme' /*adm*/), esc_html__('Menus', 'weaver-xtreme'  /*adm*/), '<li>', '</li>');
                weaverx_elink('#asp_content', esc_html__('Text colors and bg, image borders, featured image, other properties related to all content', 'weaver-xtreme' /*adm*/), esc_html__('Content Areas', 'weaver-xtreme' /*adm*/), '<li>', '</li>');
                weaverx_elink('#asp_postspecific', esc_html__('Properties related to posts: titles, meta info, navigation, excerpts, featured images, and more', 'weaver-xtreme' /*adm*/), esc_html__('Post Specifics', 'weaver-xtreme' /*adm*/), '<li>', '</li>');
                weaverx_elink('#asp_footer', esc_html__('Footer options: bg color, borders, more. Site Copyright', 'weaver-xtreme' /*adm*/), esc_html__('Footer', 'weaver-xtreme' /*adm*/), '<li>', '</li>');
                weaverx_elink('#asp_custom', esc_html__('Font settings &amp; Custom Settings', 'weaver-xtreme' /*adm*/), esc_html__('Fonts &amp; Custom', 'weaver-xtreme' /*adm*/), '<li>', '</li>');
                ?>
            </ul>

            <?php weaverx_tab_title(esc_html__('Main Options', 'weaver-xtreme' /*adm*/), 'help.html#MainOptions', esc_html__('Help for Main Options', 'weaver-xtreme' /*adm*/)); ?>

            <div id="asp_genappear" class="tab_mainopt">
                <?php weaverx_mainopts_general(); ?>
            </div>

            <div id="asp_widgets" class="tab_mainopt">
                <?php
                weaverx_mainopts_layout();
                weaverx_mainopts_widgets();
                ?>
            </div>

            <div id="asp_full" class="tab_mainopt">
                <?php
                weaverx_mainopts_fullwidth();
                ?>
            </div>

            <div id="asp_headeropts" class="tab_mainopt">
                <?php weaverx_mainopts_header(); ?>
            </div>

            <div id="asp_menus" class="tab_mainopt">
                <?php weaverx_mainopts_menus(); ?>
            </div>

            <div id="asp_content" class="tab_mainopt">
                <?php weaverx_mainopts_content(); ?>
            </div>

            <div id="asp_postspecific" class="tab_mainopt">
                <?php weaverx_mainopts_posts(); ?>
            </div>

            <div id="asp_footer" class="tab_mainopt">
                <?php weaverx_mainopts_footer(); ?>
            </div>


            <div id="asp_links" class="tab_mainopt">
                <?php weaverx_mainopts_custom(); ?>
            </div>

        </div> <!-- #tab-container-main -->
        <?php weaverx_sapi_submit(); ?>
    </div>    <!-- #tabwrap_main -->
    <script type="text/javascript">
        let tabberMainOpts = new Yetii({
            id: 'tab-container-main',
            tabclass: 'tab_mainopt',
            persist: true
        });
    </script>
    <?php
}

// ======================== Main Options > Wrapping Areas ========================
function weaverx_mainopts_general(): void
{

    $font_size = weaverx_getopt_default('site_fontsize_int', 16);

    $opts = array(
        array('type' => 'submit'),
        array(
            'name' => esc_html__('Wrapping Areas', 'weaver-xtreme' /*adm*/),
            'id' => '-admin-generic',
            'type' => 'header',
            'info' => esc_html__('Settings for wrapping areas', 'weaver-xtreme' /*adm*/),
            'help' => 'help.html#GenApp',
        ),
        array(
            'name' => esc_html__('GLOBAL SETTINGS', 'weaver-xtreme' /*adm*/),
            'type' => 'note',
            'info' => esc_html__('These settings control site outer background and the standard link colors.', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => esc_html__('Site Background Color', 'weaver-xtreme' /*adm*/),
            'id' => 'body_bgcolor',
            'type' => 'ctext',
            'info' => esc_html__('Background color for &lt;body&gt;, wraps entire page.', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => esc_html__('Fade Outside BG', 'weaver-xtreme' /*adm*/),
            'id' => 'fadebody_bg',
            'type' => 'checkbox',
            'info' => esc_html__('Will fade the Outside BG color, darker at top to lighter at bottom.', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => esc_html__('Full Browser Height', 'weaver-xtreme' /*adm*/),
            'id' => 'full_browser_height',
            'type' => 'checkbox',
            'info' => esc_html__('For short pages, add extra padding to bottom of content to force full browser height.', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => esc_html__('Standard Links', 'weaver-xtreme' /*adm*/),
            'id' => 'link',
            'type' => 'link',
            'info' => esc_html__('Global default for link typography ( not including menus and titles ). Set Bold, Italic, and Underline by setting those options for specific areas rather than globally to have more control.', 'weaver-xtreme' /*adm*/),
        ),

        // array('name' => '#070' . esc_html__('No Auto-Underline Links', 'weaver-xtreme' /*adm*/), 'id' => 'mobile_nounderline', 'type' => 'checkbox',
        //	'info' => esc_html__('Underlined links are easier to use on most mobile devices. This will disable auto-underlined links.', 'weaver-xtreme' /*adm*/)),

        array(
            'name' => esc_html__('Current Base Font Size:', 'weaver-xtreme' /*adm*/),
            'type' => 'note',
            'info' => '<span style="font-size:' . $font_size . 'px;">' . $font_size . esc_html__('px.', 'weaver-xtreme' /*adm*/) . '</span> ' . esc_html__('Change on Custom Tab', 'weaver-xtreme' /*adm*/),
        ),
        array('type' => 'submit'),


        array(
            'name' => esc_html__('Wrapper Area', 'weaver-xtreme' /*adm*/),
            'id' => 'wrapper',
            'type' => 'widget_area_submit',
            'info' => esc_html__('Wrapper wraps entire site (CSS id: #wrapper). Colors and font settings will be the default values for all other areas.', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => esc_html__('Container Area', 'weaver-xtreme' /*adm*/),
            'id' => 'container',
            'type' => 'widget_area_submit',
            'info' => esc_html__('Container (#container div) wraps content and sidebars.', 'weaver-xtreme' /*adm*/),
        ),

    );

    ?>

    <div class="options-intro"><?php echo wp_kses_post(__('<strong>Wrapping Areas:</strong>
The options on this tab affect the overall site appearance.
The main <strong>Wrapper Area</strong> wraps the entire site, and is used to specify default text and background colors, site width, font families, and more.
With <em>Weaver Xtreme Plus</em>, you can also specify background images for various areas of your site.', 'weaver-xtreme' /*adm*/)); ?>
        <div class="options-intro-menu"><a
                    href="#wrapping-areas"><?php esc_html_e('Wrapping Areas', 'weaver-xtreme' /*adm*/); ?></a> |
            <a href="#wrapper-area"><?php esc_html_e('Wrapper Area', 'weaver-xtreme' /*adm*/); ?></a> |
            <a href="#container-area"><?php esc_html_e('Container Area', 'weaver-xtreme' /*adm*/); ?></a> |
            <a href="#background-images"><?php esc_html_e('Background Image (X-Plus)', 'weaver-xtreme' /*adm*/); ?></a>
        </div>
    </div>
    <?php
    weaverx_form_show_options($opts);
    do_action('weaverxplus_admin', 'general_appearance');
}

function wvrx_ts_new_xp_opt($vers, $opt)
{
    // don't support new xp opts in old xp
    if (function_exists('weaverxplus_plugin_installed') && version_compare(WEAVER_XPLUS_VERSION, $vers, '>=')) {
        return $opt;
    }

    return array('name' => $opt['name'], 'info' => esc_html__('This option requires X-Plus Version greater or equal to ', 'weaver-xtreme') . $vers, 'type' => 'note');
}

// ======================== Main Options > Custom ========================

function weaverx_mainopts_custom(): void
{
    $opts = array(
        array('type' => 'submit'),
        array(
            'name' => esc_html__('Custom Options', 'weaver-xtreme' /*adm*/),
            'id' => '-admin-generic',
            'type' => 'header',
            'info' => esc_html__('Set various global custom values.', 'weaver-xtreme' /*adm*/),
            'help' => 'help.html#Custom',
        ),

        array(
            'name' => esc_html__('Various Custom Values', 'weaver-xtreme' /*adm*/),
            'id' => '-admin-settings',
            'type' => 'subheader',
            'info' => esc_html__('Adjust various global settings', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<span class="i-left dashicons dashicons-align-none"></span>' . esc_html__('Smart Margin Width', 'weaver-xtreme' /*adm*/),
            'id' => 'smart_margin_int',
            'type' => '+val_percent',
            'info' => esc_html__('Width used for smart column margins for Sidebars and Content Area. (Default: 1%) (&#9733;Plus)', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => esc_html__('Border Color', 'weaver-xtreme' /*adm*/),
            'id' => 'border_color',
            'type' => 'color',
            'info' => esc_html__('Global color of borders. (Default: #222)', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<small>' . esc_html__('Border Width', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'border_width_int',
            'type' => 'val_px',
            'info' => esc_html__('Global Width of borders. (Default: 1px)', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<span class="i-left" style="font-size:200%;margin-left:4px;">&#x25a1;</span><small>' . esc_html__('Border Style', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'border_style',
            'type' => '+select_id',
            'info' => esc_html__('Style of borders - width needs to be > 1 for some styles to work correctly (&#9733;Plus)', 'weaver-xtreme' /*adm*/),
            'value' => array(
                array('val' => 'solid', 'desc' => esc_html__('Solid', 'weaver-xtreme' /*adm*/)),
                array('val' => 'dotted', 'desc' => esc_html__('Dotted', 'weaver-xtreme' /*adm*/)),
                array('val' => 'dashed', 'desc' => esc_html__('Dashed', 'weaver-xtreme' /*adm*/)),
                array('val' => 'double', 'desc' => esc_html__('Double', 'weaver-xtreme' /*adm*/)),
                array('val' => 'groove', 'desc' => esc_html__('Groove', 'weaver-xtreme' /*adm*/)),
                array('val' => 'ridge', 'desc' => esc_html__('Ridge', 'weaver-xtreme' /*adm*/)),
                array('val' => 'inset', 'desc' => esc_html__('Inset', 'weaver-xtreme' /*adm*/)),
                array('val' => 'outset', 'desc' => esc_html__('Outset', 'weaver-xtreme' /*adm*/)),
            ),
        ),

        array(
            'name' => esc_html__('Corner Radius', 'weaver-xtreme' /*adm*/),
            'id' => 'rounded_corners_radius',
            'type' => '+val_px',
            'info' => esc_html__('Controls how "round" corners are. Specify a value (5 to 15 look best) for corner radius. (Default: 8) (&#9733;Plus)', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => esc_html__('Hide Menu/Link Tool Tips', 'weaver-xtreme' /*adm*/),
            'id' => 'hide_tooltip',
            'type' => '+checkbox',
            'info' => esc_html__('Hide the tool tip pop up over all menus and links. (&#9733;Plus)', 'weaver-xtreme' /*adm*/),
        ),


        array(
            'name' => esc_html__('Custom Shadow', 'weaver-xtreme' /*adm*/),
            'id' => 'custom_shadow',
            'type' => '+widetext',
            'info' => wp_kses_post(__('Specify full <em>box-shadow</em> CSS rule, e.g., <em>{box-shadow: 0 0 3px 1px rgba(0,0,0,0.25);}</em> (&#9733;Plus)', 'weaver-xtreme' /*adm*/)),
        ),

        array('type' => 'submit'),

        array(
            'name' => esc_html__('Custom CSS', 'weaver-xtreme' /*adm*/),
            'id' => 'custom_css',
            'type' => 'custom_css',
            'info' => esc_html__('Create Custom CSS Rules', 'weaver-xtreme' /*adm*/),
        ),

        array('type' => 'submit'),


        array(
            'name' => esc_html__('Fonts', 'weaver-xtreme' /*adm*/),
            'id' => '-editor-textcolor',
            'type' => 'header',
            'info' => esc_html__('Font Base Sizes', 'weaver-xtreme' /*adm*/),
            'help' => 'font-demo.html',
        ),

        array(
            'name' => esc_html__('Site Base Font Size', 'weaver-xtreme' /*adm*/),
            'id' => 'site_fontsize_int',
            'type' => 'val_px',
            'info' => esc_html__('Base font size of standard text. This value determines the default medium font size. Note that visitors can change their browser\'s font size, so final font size can vary, as expected. (Default: 16px)', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => esc_html__('Site Base Line Height', 'weaver-xtreme' /*adm*/),
            'id' => 'site_line_height_dec',
            'type' => '+val_num',
            'info' => esc_html__('Set the Base line-height. Most other line heights based on this multiplier. (Default: 1.5 - no units) (&#9733;Plus)', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<small>' . esc_html__('Site Base Font Size - Small Tablets', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'site_fontsize_tablet_int',
            'type' => '+val_px',
            'info' => esc_html__('Small Tablet base font size of standard text. (Default medium font size: 16px) (&#9733;Plus)', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<small>' . esc_html__('Site Base Font Size - Phones', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'site_fontsize_phone_int',
            'type' => '+val_px',
            'info' => esc_html__('Phone base font size of standard text. (Default medium font size: 16px)  (&#9733;Plus)', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => esc_html__('Custom Font Size A', 'weaver-xtreme' /*adm*/),
            'id' => 'custom_fontsize_a',
            'type' => '+val_em',
            'info' => esc_html__('Specify font size in em for Custom Size A (&#9733;Plus)', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => esc_html__('Custom Font Size B', 'weaver-xtreme' /*adm*/),
            'id' => 'custom_fontsize_b',
            'type' => '+val_em',
            'info' => esc_html__('Specify font size in em for Custom Size B (&#9733;Plus)', 'weaver-xtreme' /*adm*/),
        ),



        array('type' => 'submit'),
    );
    if (version_compare(WEAVERX_VERSION, '6.2.0.90', '<')) {
        $opts[] = array(
            'name' => '<small>' . esc_html__('Disable Google Font Integration', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'disable_google_fonts',
            'type' => '+checkbox',
            'info' => wp_kses_post(__('<strong>ADVANCED OPTION!</strong> <em>Be sure you understand the consequences of this option.</em> By disabling Google Font Integration, the Google Fonts definitions will <strong>not</strong> be loaded for your site. <strong style="color:red;font-weight:bold;">Please note:</strong> Any previously selected Google Font Families will revert to generic serif, sans, mono, and script fonts.', 'weaver-xtreme')) . ' ' . esc_html__('Note: Weaver Xtreme Self-hosts Google fonts now, and this option is not really useful any longer','weaver-xtreme'),
        );
    }

    ?>
    <div class="options-intro"><strong><?php esc_html_e('Custom &amp; Fonts:', 'weaver-xtreme' /*adm*/); ?> </strong>
        <?php esc_html_e('Set values for Custom options and Fonts: Smart Margin, Borders, Corners, Shadows, Custom CSS, and Fonts', 'weaver-xtreme' /*adm*/); ?>
        <br/>
        <div class="options-intro-menu">
            <a href="#various-custom-values"><?php esc_html_e('Various Custom Values', 'weaver-xtreme' /*adm*/); ?></a>
            |
            <a href="#custom-css-rules"><?php esc_html_e('Custom CSS Rules', 'weaver-xtreme' /*adm*/); ?></a> |
            <a href="#fonts">Fonts</a>
        </div>
    </div>
    <?php
    weaverx_form_show_options($opts);

    do_action('weaverxplus_admin', 'fonts');
}

// ======================== Main Options > Full Width ========================

function weaverx_mainopts_fullwidth(): void
{

    if (version_compare(WEAVERX_VERSION, '4.9.0', '>=')) {
        $opts = array(
            array('type' => 'submit'),
            array(
                'name' => esc_html__('Full Width Site', 'weaver-xtreme' /*adm*/),
                'id' => '-editor-justify',
                'type' => 'header',
                'info' => esc_html__('One-Step Site Layout, Extend, and Stretch options are no longer supported in Weaver Xtreme V5. You can use Full and Wide alignment to achieve similar results. If you used Extend or Stretch settings, they will be automatically converted to equivalent settings when you load your settings.', 'weaver-xtreme' /*adm*/),
            ),
        );
    } else {
        $opts = array(
            array('type' => 'submit'),
            array(
                'name' => esc_html__('Full Width Site', 'weaver-xtreme' /*adm*/),
                'id' => '-editor-justify',
                'type' => 'header',
                'info' => esc_html__('Options to easily create full width site designs', 'weaver-xtreme' /*adm*/),
                'help' => 'help.html#FullWidth',
            ),


            array(
                'name' => esc_html__('One-Step Site Layout', 'weaver-xtreme' /*adm*/),
                'id' => 'site_layout',
                'type' => 'select_id',
                'info' => esc_html__('Easiest way to set overall site width layout. Settings other than Custom or blank <strong>automatically</strong> set and clear other Extend BG and Stretch Width Options. Use Custom to enable manual Custom Full Width Options. You can also use <em>Full</em> and <em>Wide Align</em> options for individual areas to enhance these one-step settings.', 'weaver-xtreme' /*adm*/),
                'value' => array(
                    array('val' => '', 'desc' => ''),
                    array('val' => 'fullwidth', 'desc' => esc_html__('Full Width - Extends BG to full width', 'weaver-xtreme')),
                    array('val' => 'stretched', 'desc' => esc_html__('Stretched - Expand to full width', 'weaver-xtreme')),
                    array('val' => 'custom', 'desc' => esc_html__('Traditional - Use Traditional Width Options', 'weaver-xtreme')),
                ),
            ),
        );
    }

    if (version_compare(WEAVERX_VERSION, '4.9.0', '<')) {
        $opts[] = array(
            'name' => esc_html__('Wide and Full Alignment', 'weaver-xtreme' /*adm*/),
            'id' => '-admin-appearance3',
            'type' => 'header_area',
            'info' => esc_html__('Many wrapping areas and other items include Full and Wide alignment for a different way to get full or wide width.', 'weaver-xtreme' /*adm*/),
        );
    }

    $opts[] = array(
        'name' => '<small>' . esc_html__('Align Full and Wide', 'weaver-xtreme' /*adm*/) . '</small>',
        'type' => 'note',
        'info' => esc_html__('Two new alignment classes, .alignwide and .alignfull are supported by Weaver Xtreme. Most options with the Align option include options for full and wide alignment. Using a width alignment option will extend the full item, including content, to the specified width.', 'weaver-xtreme' /*adm*/),
    );

    if (version_compare(WEAVERX_VERSION, '4.9.0', '>=')) {

        $opts[] = array(
            'name' => esc_html__('Extend BG Color', 'weaver-xtreme' /*adm*/),
            'id' => '-admin-appearance',
            'type' => 'header_area',
            'info' => esc_html__('These options were first added to Weaver Xtreme many years ago, and were a "state-of-the-art" technique at the time to achieve full-width layouts. This technique has now been largely replaced by Align options. However, these old options do allow designs to use different colors for possibly interesting effects. However, there are many ways to achieve similar results, and so these options will be REMOVED from future versions of Weaver Xtreme. For now, we strongly urge you to not use these options on new sites, and to convert any use on old sites to new design. When these options are eventually dropped, they will be automatically converted to the Extend BG Attributes alignment. (&#9733;Plus)', 'weaver-xtreme' /*adm*/),
        );
    } else {
        $opts[] = array(
            'name' => esc_html__('Extend BG Attributes to Full Width', 'weaver-xtreme' /*adm*/),
            'id' => '-editor-code',
            'type' => 'header_area',
            'info' => esc_html__('The Extend BG Attributes options in this section <em>retain the original content width</em>, while <em>extending the area\'s Background attributes to full width</em>. These include BG color, BG image, and borders, for example. IMPORTANT: Extend options override wide and full alignment options.', 'weaver-xtreme' /*adm*/),
        );


        $extend = array(
            'container' => array(__('Container Area Extend BG', 'weaver-xtreme'), esc_html__('Extend Container Area BG Attributes to full width.', 'weaver-xtreme')),
            'header' => array(__('Header Area Extend BG', 'weaver-xtreme'), esc_html__(' Extend Header Area BG Attributes to full width.', 'weaver-xtreme')),
            'header_sb' => array(__('Header Widget Area Extend BG', 'weaver-xtreme'), esc_html__('Extend Header Widget Area BG Attributes to full width.', 'weaver-xtreme')),
            'header_html' => array(__('Header HTML Area Extend BG', 'weaver-xtreme'), esc_html__('Extend Header HTML Area BG Attributes to full width.', 'weaver-xtreme')),
            'm_primary' => array(__('Primary Menu Extend BG', 'weaver-xtreme'), esc_html__('Extend Primary Menu BG Attributes to full width, keep menu items constrained to theme width.', 'weaver-xtreme')),
            'm_secondary' => array(__('Secondary Menu Extend BG', 'weaver-xtreme'), esc_html__('Extend Secondary Menu BG Attributes to full width, keep menu items constrained to theme width.', 'weaver-xtreme')),
            'infobar' => array(__('Info Bar Extend BG', 'weaver-xtreme'), esc_html__('Extend Info Bar BG Attributes to full width.', 'weaver-xtreme')),
            //'content' => array( esc_html__('Content Area Fullwidth BG', 'weaver-xtreme'), esc_html__('Extend Content Area BG Attributes to full width.','weaver-xtreme' )),
            'post' => array(__('Post Area Extend BG', 'weaver-xtreme'), esc_html__('Extend each Post Area BG Attributes to full width.', 'weaver-xtreme')),
            'footer' => array(__('Footer Area Extend BG', 'weaver-xtreme'), esc_html__('Extend Footer Area BG Attributes to full width.', 'weaver-xtreme')),
            'footer_sb' => array(__('Footer Widget Area Extend BG', 'weaver-xtreme'), esc_html__('Extend Footer Widget Area BG Attributes to full width.', 'weaver-xtreme')),
            'footer_html' => array(__('Footer HTML Area Extend BG', 'weaver-xtreme'), esc_html__('Extend Footer HTML Area BG Attributes to full width.', 'weaver-xtreme')),

        );

        foreach ($extend as $id => $vals) {
            $type = 'checkbox';
            if ($id == 'm_extra') {
                $type = '+checkbox';
            }
            $opts[] = array(
                'name' => '<span class="i-left" style="font-size:150%;">&harr;</span><small>' . $vals[0],
                'id' => $id . '_extend_width',
                'type' => $type,
                'info' => $vals[1],
            );
        }


        $opts[] = array(
            'name' => esc_html__('Stretch Areas (Expand)', 'weaver-xtreme' /*adm*/),
            'id' => '-editor-expand',
            'type' => 'header_area',
            'info' => esc_html__('This section has options that let you stretch or expand selected content areas of your site to the full browser width. The content will be responsively displayed - and fully occupy the browser window.', 'weaver-xtreme' /*adm*/),
        );
        $opts[] = array(
            'name' => '<small>' . esc_html__('These Options OBSOLETE', 'weaver-xtreme' /*adm*/) . '</small>',
            'type' => 'note',
            'info' => esc_html__('Due to the added support for Wide and Full Alignment, the Stretch options are essentially obsolete. Please use the Full and Wide align options available for most of these Stretch items.', 'weaver-xtreme' /*adm*/),
        );

        $opts[] = array(
            'name' => '<span class="i-left dashicons dashicons-editor-expand"></span>' . esc_html__('Entire Site Full Width', 'weaver-xtreme' /*adm*/),
            'id' => 'wrapper_fullwidth',
            'type' => 'checkbox',
            'info' => esc_html__('Checking this option will display the <strong>ENTIRE SITE</strong> in the full width of the browser. This option overrides the <em>Theme Width</em> option on the <em>Wrapping Areas : Wrapper Area</em> menu.', 'weaver-xtreme' /*adm*/),
        );


        $stretch = array(
            'header' => array(__('Header Area Stretch', 'weaver-xtreme'), esc_html__('Stretch Header Area to full width. This will include all other Header Area sub-areas as well.', 'weaver-xtreme')),
            'header-image' => array(__('Header Image Stretch', 'weaver-xtreme'), esc_html__('Stretch Header Image to full width.', 'weaver-xtreme')),
            'site_title' => array(__('Site Title/Tagline Stretch', 'weaver-xtreme'), esc_html__('This option includes the Site Title, Tagline, Search Button, and MiniMenu.', 'weaver-xtreme')),
            'header-widget-area' => array(__('Header Widget Area Stretch', 'weaver-xtreme'), esc_html__('Stretch Header Widget Area to full width.', 'weaver-xtreme')),
            'header-html' => array(__('Header HTML Area Stretch', 'weaver-xtreme'), esc_html__('Stretch Header HTML Area to full width.', 'weaver-xtreme')),
            'm_primary' => array(__('Primary Menu Stretch', 'weaver-xtreme'), esc_html__('Stretch Primary Menu to full width.', 'weaver-xtreme')),
            'm_secondary' => array(__('Secondary Menu Stretch', 'weaver-xtreme'), esc_html__('Stretch Secondary Menu to full width.', 'weaver-xtreme')),
            'container' => array(__('Container Area Stretch', 'weaver-xtreme'), esc_html__('Stretch Container Area to full width.', 'weaver-xtreme')),
            'infobar' => array(__('Info Bar Stretch', 'weaver-xtreme'), esc_html__('Stretch Info Bar to full width.', 'weaver-xtreme')),
            'post' => array(__('Post Area Stretch', 'weaver-xtreme'), esc_html__('Stretch Post Area to full width.', 'weaver-xtreme')),
            'footer' => array(__('Footer Area Stretch', 'weaver-xtreme'), esc_html__('Checking this option will automatically include the other Footer Area Stretch options as well.', 'weaver-xtreme')),
            'footer_sb' => array(__('Footer Widget Area Stretch', 'weaver-xtreme'), esc_html__('Stretch Footer Widget Area to full width.', 'weaver-xtreme')),
            'footer_html' => array(__('Footer HTML Area Stretch', 'weaver-xtreme'), esc_html__('Stretch Footer HTML Area to full width.', 'weaver-xtreme')),
            'site-ig-wrap' => array(__('Footer Copyright Area Stretch', 'weaver-xtreme'), esc_html__('Stretch Footer Copyright Area to full width.', 'weaver-xtreme')),

        );

        foreach ($stretch as $id => $vals) {
            $opts[] = array(
                'name' => '<span class="i-left dashicons dashicons-editor-expand"></span>' . $vals[0],
                'id' => 'expand_' . $id,
                'type' => 'checkbox',
                'info' => $vals[1],
            );
        }

        $opts[] = array(
            'name' => esc_html__('Extend BG Color', 'weaver-xtreme' /*adm*/),
            'id' => '-admin-appearance',
            'type' => 'header_area',
            'info' => esc_html__('These options, available with Weaver Xtreme Plus, allow you to stretch the BG color of various area to full width. This is different than the Extend BG Attributes in that only the color is extended, and that color can be different than the content. (&#9733;Plus)', 'weaver-xtreme' /*adm*/),
        );
    }


    $extend = array(
        'header' => array(__('Header Area Extend BG Color', 'weaver-xtreme'), esc_html__('Extend Header Area BG Color to full width.', 'weaver-xtreme')),
        'm_primary' => array(__('Primary Menu Extend BG', 'weaver-xtreme'), esc_html__('Extend Primary Menu BG Color to full width.', 'weaver-xtreme')),
        'm_secondary' => array(__('Secondary Menu Extend BG', 'weaver-xtreme'), esc_html__('Extend Secondary Menu BG Color to full width.', 'weaver-xtreme')),
        'm_extra' => array(__('Extra Menu Extend BG', 'weaver-xtreme'), esc_html__('Extend Extra Menu BG Color to full width.', 'weaver-xtreme')),
        'container' => array(__('Container Extend BG', 'weaver-xtreme'), esc_html__('Extend Container Area BG Color to full width.', 'weaver-xtreme')),
        'content' => array(__('Content Extend BG', 'weaver-xtreme'), esc_html__('Extend Content Area BG Color to full width.', 'weaver-xtreme')),
        'footer' => array(__('Footer Extend BG', 'weaver-xtreme'), esc_html__('Extend Footer Area BG Color to full width.', 'weaver-xtreme')),
    );

    foreach ($extend as $id => $vals) {
        $opts[] = array(
            'name' => $vals[0],
            'id' => $id . '_extend_bgcolor',
            'type' => '+color',
            'info' => $vals[1] . ' (&#9733;Plus)',
        );
    }


    ?>
    <div class="options-intro">
        <?php
        if (version_compare(WEAVERX_VERSION, '4.9.0', '>=')) {
            _e('<strong>OBSOLETE: Full Width:</strong> Options to create full width sites.', 'weaver-xtreme' /*adm*/);
            echo '<p>';
            echo wp_kses_post(__('<strong style="color:red;">IMPORTANT NOTE:</strong> Full Width options have been replaced by Align Full or Align Wide in Weaver Xtreme V5.', 'weaver-xtreme'));

        } else {

            echo wp_kses_post(__('<strong>Full Width:</strong> Options to create full width sites.', 'weaver-xtreme' /*adm*/));
            echo '<p>';
            echo wp_kses_post(__('<strong style="color:red;">IMPORTANT NOTE:</strong> A better way to create Full and Wide Sites is to use Align Full or Align Wide on the four major areas: Wrapper, Header, Container, and the Footer. The new Left/Right Padding in percent is available for responsive padding with these areas.', 'weaver-xtreme'));
        } ?>

        </p></div>
    <?php
    weaverx_form_show_options($opts);
}

// ======================== Main Options > Header ========================
function weaverx_mainopts_header(): void
{

    $wp_logo = weaverx_get_wp_custom_logo_url();

    if ($wp_logo) {
        $wp_logo_html = "<img src='$wp_logo' alt='logo' style='max-height:16px;margin-left:10px;' />";
    } else {
        $wp_logo_html = esc_html__('Not set', 'weaver-xtreme');
    }


    $opts = array(
        array('type' => 'submit'),
        array(
            'name' => esc_html__('Header Options', 'weaver-xtreme' /*adm*/),
            'id' => '-admin-generic',
            'type' => 'header',
            'info' => esc_html__('Options affecting site Header', 'weaver-xtreme' /*adm*/),
            'help' => 'help.html#HeaderOpt',
        ),


        array(
            'name' => esc_html__('Header Area', 'weaver-xtreme' /*adm*/),
            'id' => 'header',
            'type' => 'widget_area',
            'info' => esc_html__('The Header Area includes: menu bars, standard header image, title, tagline, header widget area, header HTML area', 'weaver-xtreme' /*adm*/),
        ),

        array('name' => esc_html__('Header Other options', 'weaver-xtreme'), 'type' => 'break'),

        array(
            'name' => '<span class="i-left dashicons dashicons-visibility"></span>' . esc_html__('Hide Search on Header', 'weaver-xtreme' /*adm*/),
            'id' => 'header_search_hide',
            'type' => 'select_hide',
            'info' => esc_html__('Selectively hide the Search Box Button on top right of header', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<small>' . esc_html__('Search Area Options:', 'weaver-xtreme' /*adm*/) . '</small>',
            'type' => 'note',
            'info' => esc_html__('Specify search icon, text and background colors Search section of Content Areas tab.', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => esc_html__('Global Header Area Replacement', 'weaver-xtreme'),
            'id' => 'pb_header_replace_page_id',
            'type' => 'widetext',
            'info' => esc_html__('Provide any page or post ID to serve as global replacement for Header area. This will override and replace most other settings in this section.', 'weaver-xtreme'),
        ),
        array(
            'name' => '<small>' . esc_html__('Page Builder Replacements', 'weaver-xtreme' /*adm*/) . '</small>',
            'type' => 'note',
            'info' => esc_html__('The Customizer interface has options to specify a Replacement Area from page builders.', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<small>' . esc_html__('Hide Weaver Menus', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'pb_header_hide_menus',
            'type' => 'checkbox',
            'info' => esc_html__('Check to hide the Weaver Primary Menu normally displayed below the replacement page.', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<small>' . esc_html__('Show Only Menus in Header Area', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'menus_only_header',
            'type' => 'checkbox',
            'info' => esc_html__('Show only the Primary and Secondary Menus in Header area. (Hides all Header elements except menus.)', 'weaver-xtreme' /*adm*/),
        ),


        array('type' => 'submit'),

        array(
            'name' => esc_html__('Header Image', 'weaver-xtreme' /*adm*/),
            'id' => '-format-image',
            'type' => 'subheader',
            'info' => esc_html__('Settings related to standard header image (Set on Appearance&rarr;Header)', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<span class="i-left dashicons dashicons-visibility"></span>' . esc_html__('Hide Header Image', 'weaver-xtreme' /*adm*/),
            'id' => 'hide_header_image',
            'type' => 'select_hide',
            'info' => esc_html__('Check to selectively hide standard header image', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<small>' . esc_html__('Suggested Header Image Height', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'header_image_height_int',
            'type' => 'val_px',
            'info' => esc_html__('Change the suggested height of the Header Image. This only affects the clipping window on the Appearance:Header page. Header images will be responsively sized. If used with <em>Header Image Rendering</em>, this value will be used to set the minimum height of the BG image. (Default: 188px)', 'weaver-xtreme' /*adm*/),
        ),

        wvrx_ts_new_xp_opt('3.0',        // >= 3.0
            array(
                'name' => esc_html__('Header Image Rendering', 'weaver-xtreme' /*adm*/) . '</small>',
                'id' => 'header_image_render',
                'type' => '+select_id',    //code
                'info' => esc_html__('How to render header image: as img in header or as header area bg image. When rendered as a BG image, other options such as moving Title/Tagline or having image link to home page are not meaningful. (Default: &lt;img&gt; in header div) (&#9733;Plus)', 'weaver-xtreme' /*adm*/),
                'value' => array(
                    array('val' => 'header-as-img', 'desc' => esc_html__('As img in header', 'weaver-xtreme' /*adm*/)),
                    array('val' => 'header-as-bg', 'desc' => esc_html__('As static BG image', 'weaver-xtreme' /*adm*/)),
                    array('val' => 'header-as-bg-responsive', 'desc' => esc_html__('As responsive BG image', 'weaver-xtreme' /*adm*/)),
                    array('val' => 'header-as-bg-parallax', 'desc' => esc_html__('As parallax BG image', 'weaver-xtreme' /*adm*/)),

                ),
            )),

        array(
            'name' => '<small>' . esc_html__('Minimum Header Height', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'header_min_height',
            'type' => '+val_px',
            'info' => esc_html__('Set Minimum Height for Header Area. Most useful used with Parallax Header BG Image. Adding Top Margin to Primary Menu bar can also add height. (&#9733;Plus)', 'weaver-xtreme' /*adm*/),
        ),


        array(
            'name' => '<span class="i-left" style="font-size:120%;">&harr;</span><small>' . esc_html__('Maximum Image Width', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'header_image_max_width_dec',
            'type' => '+val_percent',
            'info' => esc_html__('Maximum width of Image (Default: 100%) (&#9733;Plus)', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<small>' . esc_html__('Use Actual Image Size', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'header_actual_size',
            'type' => '+checkbox',
            'info' => esc_html__('Check to use actual header image size. (Default: theme width) (&#9733;Plus)', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<span class="i-left dashicons dashicons-editor-alignleft"></span><small>' . esc_html__('Align Header Image', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'header_image_align',
            'type' => 'align',
            'info' => esc_html__('How to align header image. Wide and Full do not apply to BG header image.', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . esc_html__('Hide Header Image Front Page', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'hide_header_image_front',
            'type' => 'checkbox',
            'info' => esc_html__('Check to hide display of standard header image on front page only.', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<span class="i-left">{ }</span> <small>' . esc_html__('Add Classes', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'header_image_add_class',
            'type' => '+widetext',
            'info' => '<em>' . wp_kses_post(__('Header Image', 'weaver-xtreme' /*adm*/) . '</em>' . wp_kses_post(__(': Space separated class names to add to this area (<em>Advanced option</em>) (&#9733;Plus)', 'weaver-xtreme' /*adm*/))),
        ),

        array(
            'name' => '<small>' . esc_html__('Header Image Links to Site', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'link_site_image',
            'type' => 'checkbox',
            'info' => wp_kses_post(__('Check to add a link to site home page for Header Image. Note: If used with <em>Move Title/Tagline over Image</em>, parts of the header image will not be clickable.', 'weaver-xtreme' /*adm*/)),
        ),

        array(
            'name' => '<small>' . esc_html__('Alternate Header Images:', 'weaver-xtreme' /*adm*/) . '</small>',
            'type' => 'note',
            'info' => wp_kses_post(__('Specify alternate header images using the <em>Featured Image Location</em> options on the <em>Content Areas</em> tab for pages, or the <em>Post Specifics</em> tab for single post views.', 'weaver-xtreme' /*adm*/)),
        ),

        array(
            'name' => '<span class="i-left dashicons dashicons-editor-code"></span>' . esc_html__('Image HTML Replacement', 'weaver-xtreme' /*adm*/),
            'id' => 'header_image_html_text',
            'type' => 'textarea',
            'placeholder' => esc_html__('Any HTML, including shortcodes', 'weaver-xtreme' /*adm*/),
            'info' => esc_html__('Replace Header image with arbitrary HTML. Useful for slider shortcodes in place of image. FI as Header Image has priority over HTML replacement. Extreme Plus also supports this option on a Per Page/Post basis.', 'weaver-xtreme' /*adm*/),
            'val' => 1,
        ),

        array(
            'name' => '<small>' . esc_html__('Show On Home Page Only', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'header_image_html_home_only',
            'type' => 'checkbox',
            'info' => esc_html__('Check to use the Image HTML Replacement only on your Front/Home page.', 'weaver-xtreme' /*adm*/),
        ),

        wvrx_ts_new_xp_opt('3.0', // >= 3.0
            array(
                'name' => '<small>' . esc_html__('Also show BG Header Image', 'weaver-xtreme' /*adm*/) . '</small>',
                'id' => 'header_image_html_plus_bg',
                'type' => '+checkbox',
                'info' => wp_kses_post(__('If you have Image HTML Replacement defined - including Per Page/Post - and also have set the standard Header Image to display as a BG image, then show <em>both</em> the BG image and the replacement HTML. (&#9733;Plus)', 'weaver-xtreme' /*adm*/)),
            )),


        array(
            'name' => esc_html__('Header Video', 'weaver-xtreme' /*adm*/),
            'id' => '-format-video',
            'type' => 'subheader',
            'info' => esc_html__('Settings related to Header Video (Set on Appearance&rarr;Header or on the Customize&rarr;Images&rarr;Header Media menu.)', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => esc_html__('Header Video Rendering', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'header_video_render',
            'type' => 'select_id',    //code
            'info' => wp_kses_post(__('How to render Header Video: as image substitute in header or as full browser background cover image will parallax effect. <em style="color:red;">Note that the Header Image options above do not apply to the Header Video media.</em>', 'weaver-xtreme' /*adm*/)),
            'value' => array(
                array('val' => 'has-header-video', 'desc' => esc_html__('As video in header only', 'weaver-xtreme' /*adm*/)),
                array('val' => 'has-header-video-cover', 'desc' => esc_html__('As full cover Parallax BG Video', 'weaver-xtreme' /*adm*/)),
                array('val' => 'has-header-video-none', 'desc' => esc_html__('Disable Header Video', 'weaver-xtreme' /*adm*/)),
            ),
        ),

        array(
            'name' => esc_html__('Header Video Aspect Ratio', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'header_video_aspect',
            'type' => 'select_id',    //code
            'info' => esc_html__('It is critical to select aspect ratio of your video. If you see letterboxing black bars, you have the wrong aspect ratio selected.', 'weaver-xtreme' /*adm*/),
            'value' => array(
                array('val' => '16:9', 'desc' => esc_html__('16:9 HDTV', 'weaver-xtreme' /*adm*/)),
                array('val' => '4:3', 'desc' => esc_html__('4:3 Std TV', 'weaver-xtreme' /*adm*/)),
                array('val' => '3:2', 'desc' => esc_html__('3:2 35mm Photo', 'weaver-xtreme' /*adm*/)),
                array('val' => '5:3', 'desc' => esc_html__('5:3 Alternate Photo', 'weaver-xtreme' /*adm*/)),
                array('val' => '64:27', 'desc' => esc_html__('2.37:1 Cinemascope', 'weaver-xtreme' /*adm*/)),
                array('val' => '37:20', 'desc' => esc_html__('1.85:1 VistaVision', 'weaver-xtreme' /*adm*/)),
                array('val' => '3:1', 'desc' => esc_html__('3:1 Banner', 'weaver-xtreme' /*adm*/)),
                array('val' => '4:1', 'desc' => esc_html__('4:1 Banner', 'weaver-xtreme' /*adm*/)),
                array('val' => '9:16', 'desc' => esc_html__('9:16 Vertical HD (Please avoid!)', 'weaver-xtreme' /*adm*/)),
            ),
        ),


        array(
            'name' => esc_html__('Custom Logo', 'weaver-xtreme' /*adm*/),
            'id' => '-menu',
            'type' => 'subheader',
            'info' => esc_html__('The native WP Custom Logo, set on the Site Identity Customizer menu.', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<small>' . esc_html__('Replace Title with Site Logo', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'wplogo_for_title',
            'type' => 'checkbox',
            'info' => esc_html__('Replace the Site Title text with the WP Custom Logo Image. Logo: ', 'weaver-xtreme' /*adm*/) . $wp_logo_html,
        ),

        array(
            'name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . esc_html__('Hide WP Custom Logo', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'hide_wp_site_logo',
            'type' => 'select_hide',
            'info' => esc_html__('Hide native WP Custom Site Logo in Header, by device. (This is not the Weaver Logo/HTML!)', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<span class="i-left dashicons dashicons-align-none"></span><small>' . esc_html__('Logo for Title Height', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'header_logo_height_dec',
            'type' => 'val_px',
            'info' => esc_html__('Set maximum height of Logo when used to replace Site Title. Default 0 uses the actual image size. This is the maximum height. If the actual image height is smaller, the smaller value is used.', 'weaver-xtreme' /*adm*/),
        ),


        array('type' => 'submit'),


        array(
            'name' => esc_html__('Site Title/Tagline', 'weaver-xtreme' /*adm*/),
            'id' => '-text',
            'type' => 'subheader',
            'info' => esc_html__('Settings related to the Site Title and Tagline (Tagline sometimes called Site Description)', 'weaver-xtreme' /*adm*/),
        ),


        array(
            'name' => esc_html__('Site Title', 'weaver-xtreme' /*adm*/),
            'id' => 'site_title',
            'type' => 'titles',
            'info' => esc_html__("The site's main title in the header (blog title)", 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<span class="i-left font-bold" style="font-size:120%;">&#x21cc;</span><small>' . esc_html__('Title Position', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'site_title_position_xy',
            'type' => 'text_xy_percent',
            'info' => esc_html__('Adjust left and top margins for Title. Decimal and negative values allowed. (Default: X: 7%, Y:0.25%)', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<span class="i-left" style="font-size:150%;">&harr;</span><small>' . esc_html__('Title Max Width', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'site_title_max_w',
            'type' => 'val_percent',
            'info' => esc_html__("Maximum width of title in header area (Default: 90%)", 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . esc_html__('Hide Site Title', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'hide_site_title',
            'type' => 'select_hide',
            'info' => esc_html__('Hide Site Title (Uses "display:none;" : SEO friendly.)', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => esc_html__('Move Title/Tagline over Image', 'weaver-xtreme' /*adm*/),
            'id' => 'title_over_image',
            'type' => 'checkbox',
            'info' => esc_html__('Move the Title, Tagline, Search, Logo/HTML and Mini-Menu over the Header Image. This can make a very attractive header,', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => esc_html__('Site Tagline', 'weaver-xtreme' /*adm*/),
            'id' => 'tagline',
            'type' => 'titles',
            'info' => esc_html__("The site's tagline (blog description)", 'weaver-xtreme' /*adm*/),
        ),


        array(
            'name' => '<span class="i-left font-bold" style="font-size:120%;">&#x21cc;</span><small>' . esc_html__('Tagline Position', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'tagline_xy',
            'type' => 'text_xy_percent',
            'info' => esc_html__('Adjust default left and top margins for Tagline. (Default: X: 10% Y:0%)', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<span class="i-left" style="font-size:150%;">&harr;</span><small>' . esc_html__('Tagline Max Width', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'tagline_max_w',
            'type' => 'val_percent',
            'info' => esc_html__("Maximum width of Tagline in header area (Default: 90%)", 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . esc_html__('Hide Site Tagline', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'hide_site_tagline',
            'type' => 'select_hide',
            'info' => esc_html__('Hide Site Tagline (Uses "display:none;" : SEO friendly.)', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => esc_html__('Title/Tagline Area BG', 'weaver-xtreme' /*adm*/),
            'id' => 'title_tagline_bgcolor',
            'type' => 'ctext',
            'info' => esc_html__('BG Color for the Title, Tagline, Search, Logo/HTML and Mini-Menu area.', 'weaver-xtreme' /*adm*/),
        ),


        array(
            'name' => '<span class="i-left font-bold" style="font-size:120%;">&#x21cc;</span><small>' . esc_html__('Title/Tagline Padding', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'title_tagline_xy',
            'type' => 'text_tb',
            'info' => esc_html__('Add Top/Bottom Padding to the Site Title/Tagline block. This option is especially useful if the Header Image is a BG image. (Default: 0,0)', 'weaver-xtreme' /*adm*/),
        ),


        array(
            'name' => '<span class="i-left dashicons dashicons-editor-code"></span><small>' . esc_html__('Weaver Site Logo/HTML', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => '_site_logo',
            'type' => '+textarea',
            'info' => esc_html__('HTML for Site Title area. (example: &lt;img src="url" style="position:absolute;top:20px;left:20px;"&nbsp;/&gt; + Custom CSS: #site-logo{min-height:123px;} (This is not the WP Custom Logo!) (&#9733;Plus) (&diams;)', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . esc_html__('Hide Site Logo/HTML', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => '_hide_site_logo',
            'type' => '+select_hide',
            'info' => esc_html__('Hide Weaver Site Logo/HTML by device. (This is not the WP Custom Logo!) (&#9733;Plus) (&diams;)', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<span class="i-left">{ }</span> <small>' . esc_html__('Add Classes', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'site_title_add_class',
            'type' => '+widetext',
            'info' => '<em>' . wp_kses_post(__('Title/Tagline', 'weaver-xtreme' /*adm*/)) . '</em>' . wp_kses_post(__(': Space separated class names to add to this area (<em>Advanced option</em>) (&#9733;Plus)', 'weaver-xtreme' /*adm*/)),
        ),


        array('type' => 'submit'),


        array(
            'name' => esc_html__('The Header Mini-Menu', 'weaver-xtreme' /*adm*/),
            'id' => '-menu',
            'type' => 'subheader',
            'info' => esc_html__('Horizontal "Mini-Menu" displayed right-aligned of Site Tagline', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => esc_html__('Note:', 'weaver-xtreme' /*adm*/),
            'type' => 'note',
            'info' => esc_html__('The Header Mini-Menu options are on the Menu Tab.', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => esc_html__('Header Widget Area', 'weaver-xtreme' /*adm*/),
            'id' => 'header_sb',
            'type' => 'widget_area',
            'info' => esc_html__('Horizontal Header Widget Area', 'weaver-xtreme' /*adm*/),
        ),

        array('name' => esc_html__('Other Widget Area Options', 'weaver-xtreme'), 'type' => 'break'),

        array(
            'name' => '<small>' . esc_html__('Header Widget Area Position', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'header_sb_position',
            'type' => '+select_id',    //code
            'info' => esc_html__('Change where Header Widget Area is displayed. (Default: Top) (&#9733;Plus)', 'weaver-xtreme' /*adm*/),
            'value' => array(
                array('val' => 'top', 'desc' => esc_html__('Top of Header', 'weaver-xtreme' /*adm*/)),
                array('val' => 'before_header', 'desc' => esc_html__('Before Header Image', 'weaver-xtreme' /*adm*/)),
                array('val' => 'after_header', 'desc' => esc_html__('After Header Image', 'weaver-xtreme' /*adm*/)),
                array('val' => 'after_html', 'desc' => esc_html__('After HTML Block', 'weaver-xtreme' /*adm*/)),
                array('val' => 'after_menu', 'desc' => esc_html__('After Lower Menu', 'weaver-xtreme' /*adm*/)),
                array('val' => 'pre_header', 'desc' => esc_html__('Pre-#header &lt;div&gt;', 'weaver-xtreme' /*adm*/)),
                array('val' => 'post_header', 'desc' => esc_html__('Post-#header &lt;div&gt;', 'weaver-xtreme' /*adm*/)),
            ),
        ),

        array(
            'name' => '<span class="i-left dashicons dashicons-editor-kitchensink"></span>' . esc_html__('Fixed-Top Header Widget Area', 'weaver-xtreme' /*adm*/),
            'id' => 'header_sb_fixedtop',
            'type' => 'checkbox',
            'info' => wp_kses_post(__('Fix the Header Widget Area to top of page. If primary/secondary menus also fixed-top, header widget area will always be after secondary and before primary. Use the <em>Expand/Extend BG Attributes</em> on the "Full Width" tab to make a full width Header Widget Area.', 'weaver-xtreme' /*adm*/)),
        ),

        array('type' => 'submit'),

        array(
            'name' => esc_html__('Header HTML', 'weaver-xtreme' /*adm*/),
            'id' => 'header_html',
            'type' => 'widget_area',
            esc_html__('Header Widget Area', 'weaver-xtreme' /*adm*/),
            'info' => esc_html__('Add arbitrary HTML to Header Area (in &lt;div id="header-html"&gt;)', 'weaver-xtreme' /*adm*/),
        ),


        array(
            'name' => '<span class="i-left dashicons dashicons-editor-code"></span>' . esc_html__('Header HTML content', 'weaver-xtreme' /*adm*/),
            'id' => 'header_html_text',
            'type' => 'textarea',
            'placeholder' => esc_html__('Any HTML, including shortcodes', 'weaver-xtreme' /*adm*/),
            'info' => esc_html__('Add arbitrary HTML to Header Area (in &lt;div id="header-html"&gt;)', 'weaver-xtreme' /*adm*/),
            'val' => 4,
        ),

        array('type' => 'submit'),

        array(
            'name' => esc_html__('Note:', 'weaver-xtreme' /*adm*/),
            'type' => 'note',
            'info' => esc_html__('There are more standard WordPress Header options available on the Dashboard Appearance->Header panel.', 'weaver-xtreme' /*adm*/),
        ),
    );

    ?>
    <div class="options-intro">
        <?php _e('<strong>Header:</strong> Options affecting the Header Area at the top of your site.', 'weaver-xtreme' /*adm*/); ?>
        <br/>
        <div class="options-intro-menu"><a href="#header-area"><?php _e('Header Area', 'weaver-xtreme' /*adm*/); ?></a>
            |
            <a href="#header-image"><?php _e('Header Image', 'weaver-xtreme' /*adm*/); ?></a> |
            <a href="#header-video"><?php _e('Header Video', 'weaver-xtreme' /*adm*/); ?></a> |
            <a href="#site-title-tagline"><?php _e('Site Title/Tagline', 'weaver-xtreme' /*adm*/); ?></a> |
            <a href="#header-widget-area"><?php _e('Header Widget Area', 'weaver-xtreme' /*adm*/); ?></a>|
            <a href="#header-html"><?php _e('Header HTML', 'weaver-xtreme' /*adm*/); ?></a>
        </div>
    </div>
    <?php
    weaverx_form_show_options($opts);

    do_action('weaverxplus_admin', 'header_opts');
}

// ======================== Main Options > Menus ========================
function weaverx_mainopts_menus(): void
{


    $opts = array(
        array('type' => 'submit'),
        array(
            'name' => esc_html__('Menu &amp; Info Bars', 'weaver-xtreme' /*adm*/),
            'id' => '-menu',
            'type' => 'header',
            'info' => esc_html__('Options affecting site Menus and the Info Bar', 'weaver-xtreme' /*adm*/),
            'help' => 'help.html#MenuBar',
        ),


        ##### SmartMenu
        array(
            'name' => '<span class="i-left dashicons dashicons-menu"></span>' . esc_html__('Use SmartMenus', 'weaver-xtreme' /*adm*/),
            'id' => 'use_smartmenus',
            'type' => 'checkbox',
            'info' => wp_kses_post(__('Use <em>SmartMenus</em> rather than default Weaver Xtreme Menus. <em>SmartMenus</em> provide enhanced menu support, including auto-visibility, and transition effects. This option is recommended. There are additional <em>Smart Menu</em> options available on the <em>Appearance &rarr; +Xtreme Plus</em> menu.', 'weaver-xtreme' /*adm*/)),
        ),

        array(
            'name' => '<small>' . esc_html__('Menu Mobile/Desktop Switch Point', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'mobile_alt_switch',
            'type' => '+val_px',
            'info' => wp_kses_post(__('<em>SmartMenus Only:</em> Set when menu bars switch from desktop to mobile. (Default: 767px. Hint: use 768 to force mobile menu on iPad portrait.) (&#9733;Plus)', 'weaver-xtreme' /*adm*/)),
        ),

        array(
            'name' => esc_html__('Mega Menus:', 'weaver-xtreme' /*adm*/),
            'type' => 'note',
            'info' => esc_html__('Weaver Xtreme Plus allows you to define Mega Menu style dropdown menu items with arbitrary HTML content. (&#9733;Plus)', 'weaver-xtreme' /*adm*/),
        ),


        array(
            'name' => esc_html__('Primary Menu Bar', 'weaver-xtreme' /*adm*/),
            'id' => 'm_primary',
            'type' => 'menu_opts',
            'info' => esc_html__('Attributes for the Primary Menu Bar (Default Location: Bottom of Header)', 'weaver-xtreme' /*adm*/),
        ),

        array('type' => 'submit'),

        array(
            'name' => esc_html__('Secondary Menu Bar', 'weaver-xtreme' /*adm*/),
            'id' => 'm_secondary',
            'type' => 'menu_opts',
            'info' => esc_html__('Attributes for the Secondary Menu Bar (Default Location: Top of Header)', 'weaver-xtreme' /*adm*/),
        ),

        array('type' => 'submit'),


        array(
            'name' => esc_html__('Options: All Menus', 'weaver-xtreme' /*adm*/),
            'id' => '-forms',
            'type' => 'subheader_alt',
            'info' => esc_html__('Menu Bar enhancements and features', 'weaver-xtreme' /*adm*/),
        ),


        array(
            'name' => esc_html__('Current Page BG', 'weaver-xtreme' /*adm*/),
            'id' => 'menubar_curpage_bgcolor',
            'type' => 'ctext',
            'info' => esc_html__('BG Color for the currently displayed page and its ancestors.', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => esc_html__('Current Page Text', 'weaver-xtreme' /*adm*/),
            'id' => 'menubar_curpage_color',
            'type' => 'color',
            'info' => esc_html__('Color for the currently displayed page and its ancestors.', 'weaver-xtreme' /*adm*/),
        ),


        array(
            'name' => '<span class="i-left dashicons dashicons-editor-bold"></span><small>' . esc_html__('Bold Current Page', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'menubar_curpage_bold',
            'type' => 'checkbox',
            'info' => esc_html__('Bold Face Current Page and ancestors', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<span class="i-left dashicons dashicons-editor-italic"></span><small>' . esc_html__('Italic Current Page', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'menubar_curpage_em',
            'type' => 'checkbox',
            'info' => esc_html__('Italic Current Page and ancestors', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<small>' . esc_html__('Do Not Highlight Ancestors', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'menubar_curpage_noancestors',
            'type' => 'checkbox',
            'info' => esc_html__('Highlight Current Page only - do not also highlight ancestor items', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<small>' . esc_html__('Retain Menu Bar Hover BG', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'm_retain_hover',
            'type' => 'checkbox',
            'info' => esc_html__('Retain the menu bar hover BG color when sub-menus are opened.', 'weaver-xtreme' /*adm*/),
        ),


        array(
            'name' => '<small>' . esc_html__('Placeholder Hover Cursor', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'placeholder_cursor',
            'type' => 'select_id',    //code
            'info' => esc_html__('CSS cursor :hover attribute for placeholder menus (e.g., Custom Menus with URL==#). (Default: pointer)', 'weaver-xtreme' /*adm*/),
            'value' => array(
                array('val' => 'pointer', 'desc' => esc_html__('Pointer (indicates link)', 'weaver-xtreme' /*adm*/)),
                array('val' => 'context-menu', 'desc' => esc_html__('Context Menu available', 'weaver-xtreme' /*adm*/)),
                array('val' => 'text', 'desc' => esc_html__('Text', 'weaver-xtreme' /*adm*/)),
                array('val' => 'none', 'desc' => esc_html__('No pointer', 'weaver-xtreme' /*adm*/)),
                array('val' => 'not-allowed', 'desc' => esc_html__('Action not allowed', 'weaver-xtreme' /*adm*/)),
                array('val' => 'default', 'desc' => esc_html__('The default cursor', 'weaver-xtreme' /*adm*/)),
            ),
        ),


        array(
            'name' => '<small>' . esc_html__('Mobile Menu "Hamburger" Label', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'mobile_alt_label',
            'type' => 'widetext',
            'info' => esc_html__('Alternative label for the default mobile "Hamburger" icon. HTML allowed: &lt;span&gt; or &lt;img&gt; suggested.', 'weaver-xtreme' /*adm*/),
        ),


        array('type' => 'submit'),

        array(
            'name' => esc_html__('Header Mini-Menu', 'weaver-xtreme' /*adm*/),
            'id' => '-menu',
            'type' => 'subheader_alt',
            'info' => esc_html__('Horizontal "Mini-Menu" displayed right-aligned of Site Tagline', 'weaver-xtreme' /*adm*/),
        ),


        array(
            'name' => esc_html__('Mini-Menu', 'weaver-xtreme' /*adm*/),
            'id' => 'm_header_mini',
            'type' => 'titles_text',
            'info' => esc_html__('Color of Mini-Menu Link Items', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<small>' . esc_html__('Mini Menu Hover', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'm_header_mini_hover_color',
            'type' => 'ctext',
            'info' => esc_html__('Hover Color for Mini-Menu Links', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<span class="i-left dashicons dashicons-align-none"></span><small>' . esc_html__('Mini Menu Top Margin', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'm_header_mini_top_margin_dec',
            'type' => 'val_em',
            'info' => esc_html__('Top margin for Mini-Menu. Negative value moves it up. (Default: 0em)', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . esc_html__('Hide Mini Menu', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'm_header_mini_hide',
            'type' => 'select_hide',
            'info' => esc_html__('Hide Mini Menu', 'weaver-xtreme' /*adm*/),
        ),


        array('type' => 'submit'),


        array(
            'name' => esc_html__('Info Bar', 'weaver-xtreme' /*adm*/),
            'id' => 'infobar',
            'type' => 'widget_area',
            'info' => esc_html__('Info Bar : Breadcrumbs & Page Nav below primary menu', 'weaver-xtreme' /*adm*/),
        ),


        array(
            'name' => '<span class="i-left dashicons dashicons-visibility"></span>' . esc_html__('Hide Breadcrumbs', 'weaver-xtreme' /*adm*/),
            'id' => 'info_hide_breadcrumbs',
            'type' => 'checkbox',
            'info' => esc_html__('Do not display the Breadcrumbs', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<span class="i-left dashicons dashicons-visibility"></span>' . esc_html__('Hide Page Navigation', 'weaver-xtreme' /*adm*/),
            'id' => 'info_hide_pagenav',
            'type' => 'checkbox',
            'info' => esc_html__('Do not display the numbered Page navigation', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<span class="i-left dashicons dashicons-visibility"></span>' . esc_html__('Show Search box', 'weaver-xtreme' /*adm*/),
            'id' => 'info_search',
            'type' => 'checkbox',
            'info' => esc_html__('Include a Search box on the right', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<span class="i-left dashicons dashicons-visibility"></span>' . esc_html__('Show Log In', 'weaver-xtreme' /*adm*/),
            'id' => 'info_addlogin',
            'type' => 'checkbox',
            'info' => esc_html__('Include a simple Log In link on the right', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => esc_html__('Breadcrumb for Home', 'weaver-xtreme' /*adm*/),
            'id' => 'info_home_label',
            'type' => 'widetext', //code - option done in code
            'info' => esc_html__('This lets you change the breadcrumb label for your home page. (Default: Home)', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => esc_html__('Info Bar Links', 'weaver-xtreme' /*adm*/),
            'id' => 'ibarlink',
            'type' => 'link',
            'info' => esc_html__('Color for links in Info Bar (uses Standard Link colors if left blank)', 'weaver-xtreme' /*adm*/),
        ),
    );

    ?>
    <div class="options-intro">
        <?php _e('<strong>Menus:</strong> Options to control how your menus look.', 'weaver-xtreme' /*adm*/); ?><br/>
        <div class="options-intro-menu">
            <a href="#primary-menu-bar"><?php _e('Primary Menu Bar', 'weaver-xtreme' /*adm*/); ?></a> |
            <a href="#secondary-menu-bar"><?php _e('Secondary Menu Bar', 'weaver-xtreme' /*adm*/); ?></a> |
            <a href="#options-all-menus"><?php _e('Options: All Menus', 'weaver-xtreme' /*adm*/); ?></a> |
            <a href="#header-mini-menu"><?php _e('Header Mini-Menu', 'weaver-xtreme' /*adm*/); ?></a> |
            <a href="#info-bar"><?php _e('Info Bar', 'weaver-xtreme' /*adm*/); ?></a> |
            <a href="#extra-menu"><?php _e('Extra Menu (X-Plus)', 'weaver-xtreme' /*adm*/); ?></a>
        </div>
    </div>
    <?php

    $all_opts = apply_filters('weaverxplus_menu_inject', $opts);

    weaverx_form_show_options($all_opts);

}


// ======================== Main Options > Content Areas ========================
function weaverx_mainopts_content(): void
{
    $opts = array(
        array('type' => 'submit'),
        array(
            'name' => esc_html__('Content Areas', 'weaver-xtreme' /*adm*/),
            'id' => '-admin-page',
            'type' => 'header',
            'info' => esc_html__('Settings for the content areas (posts and pages)', 'weaver-xtreme' /*adm*/),
            'toggle' => 'content-areas',
            'help' => 'help.html#ContentAreas',
        ),

        array(
            'name' => esc_html__('Content Area', 'weaver-xtreme' /*adm*/),
            'id' => 'content',
            'type' => 'widget_area',
            'info' => esc_html__('Area properties for page and post content', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => esc_html__('Page Title', 'weaver-xtreme' /*adm*/),
            'id' => 'page_title',
            'type' => 'titles',
            'info' => esc_html__('Page titles, including pages, post single pages, and archive-like pages.', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<small>' . esc_html__('Bar under Title', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'page_title_underline_int',
            'type' => 'val_px',
            'info' => esc_html__('Enter size in px if you want a bar under page title. Leave blank or 0 for no bar.', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<small>' . esc_html__('Space Between Title and Content', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'space_after_title_dec',
            'type' => 'val_em',
            'info' => esc_html__('Space between Page or Post title and beginning of content (Default: 1.0em)', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => esc_html__('Archive Pages Title Text', 'weaver-xtreme' /*adm*/),
            'id' => 'archive_title',
            'type' => 'titles',
            'info' => esc_html__('Archive-like page titles: archives, categories, tags, searches.', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => esc_html__('Content Links', 'weaver-xtreme' /*adm*/),
            'id' => 'contentlink',
            'type' => 'link',
            'info' => esc_html__('Color for links in Content', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => esc_html__('Content Headings', 'weaver-xtreme' /*adm*/),
            'id' => 'content_h',
            'type' => '+titles',
            'info' => esc_html__('Headings (&lt;h1&gt;-&lt;h6&gt;) in page and post content (&#9733;Plus)', 'weaver-xtreme' /*adm*/),
        ),

        array('type' => 'submit'),

        array(
            'name' => esc_html__('Text', 'weaver-xtreme' /*adm*/),
            'id' => '-text',
            'type' => 'subheader_alt',
            'info' => esc_html__('Text related options', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<small>' . esc_html__('Space after paragraphs and lists', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'content_p_list_dec',
            'type' => 'val_em',
            'info' => esc_html__('Space after paragraphs and lists (Recommended: 1.5 em)', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<small>' . esc_html__('Page/Post Editor BG', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'editor_bgcolor',
            'type' => 'ctext',
            'info' => esc_html__('Alternative Background Color to use for Page/Post editor if you\'re using transparent or image backgrounds.', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<small>' . esc_html__('Input Area BG', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'input_bgcolor',
            'type' => 'ctext',
            'info' => esc_html__('Background color for text input (textareas) boxes.', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<small>' . esc_html__('Input Area Text', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'input_color',
            'type' => 'color',
            'info' => esc_html__('Text color for text input (textareas) boxes.', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<small>' . esc_html__('Auto Hyphenation', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'hyphenate',
            'type' => 'checkbox',
            'info' => esc_html__('Allow browsers to automatically hyphenate text for appearance.', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<span class="i-left" style=font-size:120%;">&nbsp;&#9783;</span>' . esc_html__('Columns', 'weaver-xtreme' /*adm*/),
            'id' => 'page_cols',
            'type' => 'select_id',    //code
            'info' => esc_html__('Automatically split all page content into columns using CSS column rules. Also can use Per Page option. (Always 1 column on IE&lt;=9.)', 'weaver-xtreme' /*adm*/),
            'value' => array(
                array('val' => '1', 'desc' => esc_html__('1 Column', 'weaver-xtreme' /*adm*/)),
                array('val' => '2', 'desc' => esc_html__('2 Columns', 'weaver-xtreme' /*adm*/)),
                array('val' => '3', 'desc' => esc_html__('3 Columns', 'weaver-xtreme' /*adm*/)),
                array('val' => '4', 'desc' => esc_html__('4 Columns', 'weaver-xtreme' /*adm*/)),
            ),
        ),


        array(
            'name' => esc_html__('Search Boxes', 'weaver-xtreme' /*adm*/),
            'id' => '-search',
            'type' => 'subheader_alt',
            'info' => esc_html__('Search box related options', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<small>' . esc_html__('Search Input BG', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'search_bgcolor',
            'type' => 'ctext',
            'info' => esc_html__('Background color for all search input boxes.', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<small>' . esc_html__('Search Input Text', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'search_color',
            'type' => 'color',
            'info' => esc_html__('Text color for all search input boxes.', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => esc_html__('Search Icon Color:', 'weaver-xtreme' /*adm*/),
            'info' => esc_html__('The Search Icon colored graphics used by previous versions of Weaver Xtreme have been discontinued. A text icon is now used. The color of the search icon is inherited from wrapping areas text color, including the header area and menu bar.', 'weaver-xtreme' /*adm*/),
            'type' => 'note',
        ),


        array('type' => 'submit'),
        array(
            'name' => esc_html__('Images', 'weaver-xtreme' /*adm*/),
            'id' => '-format-image',
            'type' => 'subheader_alt',
            'info' => esc_html__('Image related options', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<small>' . esc_html__('Image Border Color', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'media_lib_border_color',
            'type' => 'ctext',
            'info' => esc_html__('Border color for images in Container and Footer.', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<span class="i-left" style="font-size:150%;">&harr;</span><small>' . esc_html__('Image Border Width', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'media_lib_border_int',
            'type' => 'val_px',
            'info' => esc_html__('Border width for images in Container and Footer. (Leave blank or set to 0 for no image borders.)', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<span class="i-left dashicons dashicons-admin-page"></span><small>' . esc_html__('Show Image Shadows', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'show_img_shadows',
            'type' => 'checkbox',
            'info' => esc_html__('Add a shadow to images  in Container and Footer. Add CSS+ to Border Color for custom shadow.', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<small>' . esc_html__('Restrict Borders to Media Library', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'restrict_img_border',
            'type' => 'checkbox',
            'info' => esc_html__('For Container and Footer, restrict border and shadows to images from Media Library. Manually entered &lt;img&gt; HTML without Media Library classes will not have borders.', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<small>' . esc_html__('Caption text color', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'caption_color',
            'type' => 'ctext',
            'info' => esc_html__('Color of captions - e.g., below media images.', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => esc_html__('Featured Image - Pages', 'weaver-xtreme' /*adm*/),
            'id' => '-id',
            'type' => 'subheader_alt',
            'info' => esc_html__('Display of Page Featured Images', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<span class="i-left" style=font-size:120%;">&nbsp;&#10538;</span>' . esc_html__('Featured Image Location', 'weaver-xtreme' /*adm*/),
            'id' => 'page_fi_location',
            'type' => 'fi_location',
            'info' => esc_html__('Where to display Featured Image for Pages', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => esc_html__('Full Width FI BG Image:', 'weaver-xtreme' /*adm*/),
            'info' => wp_kses_post(__('To create full width Page BG images from the FI, check the <em>Container Area Extend BG Attributes</em> box on the <em>Full Width</em> tab.', 'weaver-xtreme' /*adm*/)),
            'type' => 'note',
        ),
        array(
            'name' => esc_html__('Parallax FI BG Image:', 'weaver-xtreme' /*adm*/),
            'info' => esc_html__('It will usually be more useful to use the Per Page FI option to specify Parallax BG images.', 'weaver-xtreme' /*adm*/),
            'type' => 'note',
        ),
        array(
            'name' => '<small>' . esc_html__('Page Content Height', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'page_min_height',
            'type' => '+val_px',
            'info' => esc_html__('Minimum Height Page Content with Parallax BG. (&#9733;Plus)', 'weaver-xtreme' /*adm*/),
        ),


        array(
            'name' => '<span class="i-left dashicons dashicons-editor-alignleft"></span><small>' . esc_html__('Featured Image Alignment', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'page_fi_align',
            'type' => 'fi_align',
            'info' => esc_html__('How to align the Featured Image', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . esc_html__('Hide Featured Image on Pages', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'page_fi_hide',
            'type' => 'select_hide',
            'info' => esc_html__('Where to hide Featured Images on Pages (Posts have their own setting.)', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<small>' . esc_html__('Page Featured Image Size', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'page_fi_size',
            'type' => 'select_id',
            'info' => esc_html__('Media Library Image Size for Featured Image on pages. (Header uses full size).', 'weaver-xtreme' /*adm*/),
            'value' => array(
                array('val' => 'thumbnail', 'desc' => esc_html__('Thumbnail', 'weaver-xtreme' /*adm*/)),
                array('val' => 'medium', 'desc' => esc_html__('Medium', 'weaver-xtreme' /*adm*/)),
                array('val' => 'large', 'desc' => esc_html__('Large', 'weaver-xtreme' /*adm*/)),
                array('val' => 'full', 'desc' => esc_html__('Full', 'weaver-xtreme' /*adm*/)),
            ),
        ),
        array(
            'name' => '<span class="i-left" style="font-size:150%;">&harr;</span><small>' . esc_html__('Featured Image Width, Pages', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'page_fi_width',
            'type' => '+val_percent',
            'info' => esc_html__('Width of Featured Image on Pages. Max Width in %, overrides FI Size selection. (&#9733;Plus)', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<small>' . esc_html__("Don't add link to FI", 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'page_fi_nolink',
            'type' => '+checkbox',
            'info' => esc_html__('Do not add link to Featured Image. (&#9733;Plus)', 'weaver-xtreme' /*adm*/),
        ),


        array(
            'name' => esc_html__('Lists - &lt;HR&gt; - Tables', 'weaver-xtreme' /*adm*/),
            'id' => '-list-view',
            'type' => 'subheader_alt',
            'info' => esc_html__('Other options related to content', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => esc_html__('Content List Bullet', 'weaver-xtreme' /*adm*/),
            'id' => 'contentlist_bullet',
            'type' => 'select_id',
            'info' => esc_html__('Bullet used for Unordered Lists in Content areas', 'weaver-xtreme' /*adm*/),
            'value' => array(
                array('val' => 'disc', 'desc' => esc_html__('Filled Disc', 'weaver-xtreme' /*adm*/)),
                array('val' => 'circle', 'desc' => esc_html__('Circle', 'weaver-xtreme' /*adm*/)),
                array('val' => 'square', 'desc' => esc_html__('Square', 'weaver-xtreme' /*adm*/)),
                array('val' => 'none', 'desc' => esc_html__('None', 'weaver-xtreme' /*adm*/)),
            ),
        ),

        array(
            'name' => esc_html__('&lt;HR&gt; color', 'weaver-xtreme' /*adm*/),
            'id' => 'hr_color',
            'type' => 'ctext',
            'info' => esc_html__('Color of horizontal (&lt;hr&gt;) lines in posts and pages.', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => esc_html__('Table Style', 'weaver-xtreme' /*adm*/),
            'id' => 'weaverx_tables',
            'type' => 'select_id',
            'info' => esc_html__('Style used for tables in content.', 'weaver-xtreme' /*adm*/),
            'value' => array(
                array('val' => 'default', 'desc' => esc_html__('Theme Default', 'weaver-xtreme' /*adm*/)),
                array('val' => 'bold', 'desc' => esc_html__('Bold Headings', 'weaver-xtreme' /*adm*/)),
                array('val' => 'noborders', 'desc' => esc_html__('No Borders', 'weaver-xtreme' /*adm*/)),
                array('val' => 'fullwidth', 'desc' => esc_html__('Wide', 'weaver-xtreme' /*adm*/)),
                array('val' => 'wide', 'desc' => esc_html__('Wide 2', 'weaver-xtreme' /*adm*/)),
                array('val' => 'plain', 'desc' => esc_html__('Minimal', 'weaver-xtreme' /*adm*/)),
            ),
        ),

        array(
            'name' => esc_html__('Comments', 'weaver-xtreme' /*adm*/),
            'id' => '-admin-comments',
            'type' => 'subheader',
            'info' => esc_html__('Settings for displaying comments', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => esc_html__('Comment Headings', 'weaver-xtreme' /*adm*/),
            'id' => 'comment_headings_color',
            'type' => 'ctext',
            'info' => esc_html__('Color for various headings in comment form', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => esc_html__('Comment Content BG', 'weaver-xtreme' /*adm*/),
            'id' => 'comment_content_bgcolor',
            'type' => 'ctext',
            'info' => esc_html__('BG Color of Comment Content area', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => esc_html__('Comment Submit Button BG', 'weaver-xtreme' /*adm*/),
            'id' => 'comment_submit_bgcolor',
            'type' => 'ctext',
            'info' => esc_html__('BG Color of "Post Comment" submit button', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<span class="i-left" style="font-size:200%;margin-left:4px;">&#x25a1;</span><small>' . esc_html__('Show Borders on Comments', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'show_comment_borders',
            'type' => 'checkbox',
            'info' => esc_html__('Show Borders around comment sections - improves visual look of comments.', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . esc_html__('Hide Old Comments When Closed', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'hide_old_comments',
            'type' => '+checkbox',
            'info' => esc_html__('Hide previous comments after closing comments for page or post. (Default: show old comments after closing.) (&#9733;Plus)', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<span class="i-left dashicons dashicons-visibility"></span>' . '<small>' . esc_html__('Show Allowed HTML', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'form_allowed_tags',
            'type' => '+checkbox',
            'info' => esc_html__('Show the allowed HTML tags below comment input box (&#9733;Plus)', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<span class="i-left dashicons dashicons-visibility"></span><span class="dashicons dashicons-admin-comments"></span>' .
                '<small>' . esc_html__('Hide Comment Title Icon', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'hide_comment_bubble',
            'type' => '+checkbox',
            'info' => esc_html__('Hide the comment icon before the Comments title (&#9733;Plus)', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . esc_html__('Hide Separator Above Comments', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'hide_comment_hr',
            'type' => '+checkbox',
            'info' => esc_html__('Hide the (&lt;hr&gt;) separator line above the Comments area (&#9733;Plus)', 'weaver-xtreme' /*adm*/),
        ),
    );

    ?>
    <div class="options-intro">
        <?php _e('<strong>Content Areas:</strong> Includes options common to both <em>Pages</em> and <em>Posts</em>. Options for <strong>Text</strong>,
<strong>Padding</strong>, <strong>Images</strong>, <strong>Lists &amp; Tables</strong>, and user <strong>Comments</strong>.', 'weaver-xtreme' /*adm*/); ?>
        <br/>
        <div class="options-intro-menu">
            <a href="#content-area"><?php _e('Content Area', 'weaver-xtreme' /*adm*/); ?></a> |
            <a href="#text"><?php _e('Text', 'weaver-xtreme' /*adm*/); ?></a> |
            <a href="#search-boxes"><?php _e('Search Boxes', 'weaver-xtreme' /*adm*/); ?></a> |
            <a href="#images"><?php _e('Images', 'weaver-xtreme' /*adm*/); ?></a> |
            <a href="#featured-image-pages"><?php _e('Featured Image - Pages', 'weaver-xtreme' /*adm*/); ?></a> |
            <a href="#lists-hr-tables"><?php _e('Lists - &lt;HR&gt; - Tables', 'weaver-xtreme' /*adm*/); ?></a> |
            <a href="#comments"><?php _e('Comments', 'weaver-xtreme' /*adm*/); ?></a>
        </div>
    </div>
    <?php
    weaverx_form_show_options($opts);
    do_action('weaverxplus_admin', 'content_areas');
    ?>
    <span style="color:green;"><b><?php _e('Hiding/Enabling Page and Post Comments', 'weaver-xtreme' /*adm*/); ?></b></span>
    <?php
    weaverx_help_link('help.html#LeavingComments', esc_html__('Help for Leaving Comments', 'weaver-xtreme' /*adm*/));
    ?>
    <p>
        <?php _e('Controlling "Reply/Leave a Comment" visibility for pages and posts is <strong>not</strong> a theme function.
It is controlled by WordPress settings.
Please click the ? just above to see the help file entry!', 'weaver-xtreme' /*adm*/); ?>
    </p>
    <?php
}

// ======================== Main Options > Post Specifics ========================
function weaverx_mainopts_posts(): void
{
    $opts = array(
        array('type' => 'submit'),
        array(
            'name' => esc_html__('Post Specifics', 'weaver-xtreme' /*adm*/),
            'id' => '-admin-post',
            'type' => 'header',
            'info' => esc_html__('Settings affecting Posts', 'weaver-xtreme' /*adm*/),
            'help' => 'help.html#PPSpecifics',
        ),

        array(
            'name' => esc_html__('Post Area', 'weaver-xtreme' /*adm*/),
            'id' => 'post',
            'type' => 'widget_area',
            'info' => esc_html__('Use these settings to override Content Area settings for Posts (blog entries).', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => esc_html__('Sticky Post BG', 'weaver-xtreme' /*adm*/),
            'id' => 'stickypost_bgcolor',
            'type' => 'ctext',
            'info' => esc_html__('BG color for sticky posts, author info. (Add {border:none;padding:0;} to CSS to make sticky posts same as regular posts.)', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<small>' . esc_html__('Reset Major Content Options', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'reset_content_opts',
            'type' => 'checkbox',
            'info' => esc_html__('Clear wrapping Content Area bg, borders, padding, and top/bottom margins for views with posts. Allows more flexible post settings.', 'weaver-xtreme' /*adm*/),
        ),


        array('type' => 'submit'),


        array(
            'name' => esc_html__('Post Title', 'weaver-xtreme' /*adm*/),
            'id' => '-text',
            'type' => 'subheader_alt',
            'info' => esc_html__('Options for the Post Title', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => esc_html__('Post Title', 'weaver-xtreme' /*adm*/),
            'id' => 'post_title',
            'type' => 'titles',
            'info' => esc_html__("Post title (Blog Views)", 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<small>' . esc_html__('Bar under Post Titles', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'post_title_underline_int',
            'type' => 'val_px',
            'info' => esc_html__('Enter size in px if you want a bar under page title. Leave blank or 0 for no bar.', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<small>' . esc_html__('Post Title Hover', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'post_title_hover_color',
            'type' => 'ctext',
            'info' => esc_html__('Color if you want the Post Title to show alternate color for hover', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<small>' . esc_html__('Space After Post Title', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'post_title_bottom_margin_dec',
            'type' => 'val_em',
            'info' => esc_html__('Space between Post Title and Post Info Line or content. (Default: 0.15em)', 'weaver-xtreme' /*adm*/),
        ),


        array(
            'name' => '<span class="i-left dashicons dashicons-admin-comments"></span><small>' . esc_html__('Show Comment Bubble', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'show_post_bubble',
            'type' => 'checkbox',
            'info' => esc_html__("Show comment bubble with link to comments on the post info line.", 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . wp_kses_post(__('Hide <em>Post Format</em> Icons', 'weaver-xtreme' /*adm*/)) . '</small>',
            'id' => 'hide_post_format_icon',
            'type' => '+checkbox',
            'info' => esc_html__('Hide the icons for posts with Post Format specified. (&#9733;Plus)', 'weaver-xtreme' /*adm*/),
        ),


        array(
            'name' => esc_html__('Post Layout', 'weaver-xtreme' /*adm*/),
            'id' => '-schedule',
            'type' => 'subheader_alt',
            'info' => esc_html__('Layout of Posts', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<span class="i-left" style=font-size:120%;">&nbsp;&#9783;</span>' . esc_html__('Post Content Columns', 'weaver-xtreme' /*adm*/),
            'id' => 'post_cols',
            'type' => 'select_id',    //code
            'info' => esc_html__('Automatically split all post content into columns for both blog and single page views. <em>This is post content only.</em> This is not the same as "Columns of Posts". (IE&lt;=9 will display 1 col.)', 'weaver-xtreme' /*adm*/),
            'value' => array(
                array('val' => '1', 'desc' => esc_html__('1 Column', 'weaver-xtreme' /*adm*/)),
                array('val' => '2', 'desc' => esc_html__('2 Columns', 'weaver-xtreme' /*adm*/)),
                array('val' => '3', 'desc' => esc_html__('3 Columns', 'weaver-xtreme' /*adm*/)),
                array('val' => '4', 'desc' => esc_html__('4 Columns', 'weaver-xtreme' /*adm*/)),
            ),
        ),

        array(
            'name' => '<span class="i-left" style=font-size:120%;">&nbsp;&#9783;</span>' . esc_html__('Columns of Posts', 'weaver-xtreme' /*adm*/),
            'id' => 'blog_cols',
            'type' => 'select_id',    //code
            'info' => esc_html__('Display posts on blog page with this many columns. (You should adjust "Display posts on blog page with this many columns" on Settings:Reading to be a multiple of this value.)', 'weaver-xtreme' /*adm*/),
            'value' => array(
                array('val' => '1', 'desc' => esc_html__('1 Column', 'weaver-xtreme' /*adm*/)),
                array('val' => '2', 'desc' => esc_html__('2 Columns', 'weaver-xtreme' /*adm*/)),
                array('val' => '3', 'desc' => esc_html__('3 Columns', 'weaver-xtreme' /*adm*/)),
            ),
        ),

        array(
            'name' => '<span class="i-left" style=font-size:120%;">&nbsp;&#9783;</span><small>' . esc_html__('Use Columns on Archive Pages', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'archive_cols',
            'type' => 'checkbox',    //code
            'info' => esc_html__('Display posts on archive-like pages using columns. (Archive, Author, Category, Tag)', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<small>' . esc_html__('First Post One Column', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'blog_first_one',
            'type' => 'checkbox',
            'info' => esc_html__('Always display the first post in one column.', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<small>' . esc_html__('Sticky Posts One Column', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'blog_sticky_one',
            'type' => 'checkbox',
            'info' => esc_html__("Display opening Sticky Posts in one column. If First Post One Column also checked, then first non-sticky post will be one column.", 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<span class="i-left" style=font-size:120%;">&nbsp;&#9783;</span><small>' . wp_kses_post(__('Use <em>Masonry</em> for Posts', 'weaver-xtreme' /*adm*/)) . '</small>',
            'id' => 'masonry_cols',
            'type' => 'select_id',    //code
            'info' => wp_kses_post(__('Use the <em>Masonry</em> blog layout option to show dynamically packed posts on blog and archive-like pages. Overrides "Columns of Posts" setting. <em>Not compatible with full width FI BG images.</em>', 'weaver-xtreme' /*adm*/)),
            'value' => array(
                array('val' => '0', 'desc' => ''),
                array('val' => '2', 'desc' => esc_html__('2 Columns', 'weaver-xtreme' /*adm*/)),
                array('val' => '3', 'desc' => esc_html__('3 Columns', 'weaver-xtreme' /*adm*/)),
                array('val' => '4', 'desc' => esc_html__('4 Columns', 'weaver-xtreme' /*adm*/)),
                array('val' => '5', 'desc' => esc_html__('5 Columns', 'weaver-xtreme' /*adm*/)),
            ),
        ),

        array(
            'name' => '<small>' . wp_kses_post(__('Compact <em>Post Format</em> Posts', 'weaver-xtreme' /*adm*/)) . '</small>',
            'id' => 'compact_post_formats',
            'type' => 'checkbox',
            'info' => wp_kses_post(__('Use compact layout for <em>Post Format</em> posts (Image, Gallery, Video, etc.). Useful for photo blogs and multi-column layouts. Looks great with <em>Masonry</em>.', 'weaver-xtreme' /*adm*/)),
        ),
        array(
            'name' => esc_html__('Photo Bloging', 'weaver-xtreme' /*adm*/),
            'info' => esc_html__('Read the Help entry for information on creating a Photo Blog page', 'weaver-xtreme' /*adm*/),
            'type' => 'note',
            'help' => 'help.html#PhotoBlog',
        ),


        array('type' => 'submit'),

        array(
            'name' => esc_html__('Excerpts / Full Posts', 'weaver-xtreme' /*adm*/),
            'id' => '-excerpt-view',
            'type' => 'subheader_alt',
            'info' => esc_html__('How to display posts in  Blog / Archive Views', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => esc_html__('Show Full Blog Posts', 'weaver-xtreme' /*adm*/),
            'id' => 'fullpost_blog',
            'type' => 'checkbox',
            'info' => esc_html__('Will display full blog post instead of excerpts on <em>blog pages</em>.', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<small>' . esc_html__('Full Post for Archives', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'fullpost_archive',
            'type' => 'checkbox',
            'info' => esc_html__('Display the full posts instead of excerpts on <em>special post pages</em>. (Archives, Categories, etc.) Does not override manually added &lt;--more--> breaks.', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<small>' . esc_html__('Full Post for Searches', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'fullpost_search',
            'type' => 'checkbox',
            'info' => esc_html__('Display the full posts instead of excerpts for Search results. Does not override manually added &lt;--more--> breaks.', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<small>' . wp_kses_post(__('Full text for 1st <em>"n"</em> Posts', 'weaver-xtreme' /*adm*/)) . '</small>',
            'id' => 'fullpost_first',
            'type' => 'val_num',
            'info' => esc_html__('Display the full post for the first "n" posts on Blog pages. Does not override manually added &lt;--more--> breaks.', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<small>' . esc_html__('Excerpt length', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'excerpt_length',
            'type' => 'val_num',
            'info' => esc_html__('Change post excerpt length. (Default: 40 words)', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<small>' . wp_kses_post(__('<em>Continue reading</em> Message', 'weaver-xtreme' /*adm*/)) . '</small>',
            'id' => 'excerpt_more_msg',
            'type' => 'widetext',
            'info' => wp_kses_post(__('Change default <em>Continue reading &rarr;</em> message for excerpts. Can include HTML (e.g., &lt;img>).', 'weaver-xtreme' /*adm*/)),
        ),
        array('type' => 'endheader'),


        array(
            'name' => esc_html__('Post Navigation', 'weaver-xtreme' /*adm*/),
            'id' => '-leftright',
            'type' => 'subheader_alt',
            'info' => esc_html__('Navigation for moving between posts', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => esc_html__('Blog Navigation Style', 'weaver-xtreme' /*adm*/),
            'id' => 'nav_style',
            'type' => 'select_id',
            'info' => esc_html__('Style of navigation links on blog pages: "Older/Newer posts", "Previous/Next Post", or by page numbers', 'weaver-xtreme' /*adm*/),
            'value' => array(
                array('val' => 'old_new', 'desc' => esc_html__('Older/Newer', 'weaver-xtreme' /*adm*/)),
                array('val' => 'prev_next', 'desc' => esc_html__('Previous/Next', 'weaver-xtreme' /*adm*/)),
                array('val' => 'paged_left', 'desc' => esc_html__('Paged - Left', 'weaver-xtreme' /*adm*/)),
                array('val' => 'paged_right', 'desc' => esc_html__('Paged - Right', 'weaver-xtreme' /*adm*/)),
            ),
        ),
        array(
            'name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . esc_html__('Hide Top Links', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'nav_hide_above',
            'type' => '+checkbox',
            'info' => esc_html__('Hide the blog navigation links at the top (&#9733;Plus)', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . esc_html__('Hide Bottom Links', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'nav_hide_below',
            'type' => '+checkbox',
            'info' => esc_html__('Hide the blog navigation links at the bottom (&#9733;Plus)', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . esc_html__('Show Top on First Page', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'nav_show_first',
            'type' => '+checkbox',
            'info' => esc_html__('Show navigation at top even on the first page (&#9733;Plus)', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => esc_html__('Single Page Navigation Style', 'weaver-xtreme' /*adm*/),
            'id' => 'single_nav_style',
            'type' => 'select_id',
            'info' => esc_html__('Style of navigation links on post Single pages: Previous/Next, by title, or none', 'weaver-xtreme' /*adm*/),
            'value' => array(
                array('val' => 'title', 'desc' => esc_html__('Post Titles', 'weaver-xtreme' /*adm*/)),
                array('val' => 'prev_next', 'desc' => esc_html__('Previous/Next', 'weaver-xtreme' /*adm*/)),
                array('val' => 'hide', 'desc' => esc_html__('None - no display', 'weaver-xtreme' /*adm*/)),
            ),
        ),
        array(
            'name' => '<small>' . esc_html__('Link to Same Categories', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'single_nav_link_cats',
            'type' => '+checkbox',
            'info' => esc_html__('Single Page navigation links point to posts with same categories. (&#9733;Plus)', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . esc_html__('Hide Top Links', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'single_nav_hide_above',
            'type' => '+checkbox',
            'info' => esc_html__('Hide the single page navigation links at the top (&#9733;Plus)', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . esc_html__('Hide Bottom Links', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'single_nav_hide_below',
            'type' => '+checkbox',
            'info' => esc_html__('Hide the single page navigation links at the bottom (&#9733;Plus)', 'weaver-xtreme' /*adm*/),
        ),

        array('type' => 'submit'),
        array(
            'name' => esc_html__('Post Meta Info Areas', 'weaver-xtreme' /*adm*/),
            'id' => '-info',
            'type' => 'subheader_alt',
            'info' => esc_html__('Top and Bottom Post Meta Information areas', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => esc_html__('Top Post Info', 'weaver-xtreme' /*adm*/),
            'id' => 'post_info_top',
            'type' => 'titles_text',
            'info' => esc_html__("Top Post info line", 'weaver-xtreme' /*adm*/),
        ),


        array(
            'name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . esc_html__('Hide top post info', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'post_info_hide_top',
            'type' => 'checkbox',    //code
            'info' => esc_html__('Hide entire top info line (posted on, by) of post.', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => esc_html__('Bottom Post Info', 'weaver-xtreme' /*adm*/),
            'id' => 'post_info_bottom',
            'type' => 'titles_text',
            'info' => esc_html__('The bottom post info line', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . esc_html__('Hide bottom post info', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'post_info_hide_bottom',
            'type' => 'checkbox',    //code
            'info' => esc_html__('Hide entire bottom info line (posted in, comments) of post.', 'weaver-xtreme' /*adm*/),
        ),


        array(
            'name' => '<span class="i-left dashicons dashicons-visibility"></span>' . esc_html__('Show Author Avatar', 'weaver-xtreme' /*adm*/),
            'id' => 'show_post_avatar',
            'type' => 'select_id',    //code
            'info' => esc_html__('Show author avatar on the post info line (also can be set per post with post editor)', 'weaver-xtreme' /*adm*/),
            'value' => array(
                array('val' => 'hide', 'desc' => esc_html__('Do Not Show', 'weaver-xtreme' /*adm*/)),
                array('val' => 'start', 'desc' => esc_html__('Start of Info Line', 'weaver-xtreme' /*adm*/)),
                array('val' => 'end', 'desc' => esc_html__('End of Info Line', 'weaver-xtreme' /*adm*/)),
            ),
        ),

        array(
            'name' => '<small>' . esc_html__('Avatar size', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'post_avatar_int',
            'type' => 'val_px',
            'info' => esc_html__('Size of Avatar in px. (Default: 28px)', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => esc_html__('Use Icons in Post Info', 'weaver-xtreme' /*adm*/),
            'id' => 'post_icons',
            'type' => 'select_id',
            'info' => esc_html__('Use Icons instead of Text descriptions in Post Meta Info', 'weaver-xtreme' /*adm*/),
            'value' => array(
                array('val' => 'text', 'desc' => esc_html__('Text Descriptions', 'weaver-xtreme' /*adm*/)),
                array('val' => 'fonticons', 'desc' => esc_html__('Font Icons', 'weaver-xtreme' /*adm*/)),
                array('val' => 'graphics', 'desc' => esc_html__('Graphic Icons', 'weaver-xtreme' /*adm*/)),
            ),
        ),
        array(
            'name' => '<small>' . esc_html__('Font Icons Color', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'post_icons_color',
            'type' => 'color',
            'info' => esc_html__('Color for Font Icons (Default: Post Info text color)', 'weaver-xtreme' /*adm*/),
        ),


        array(
            'name' => '<span style="color:red">' . esc_html__('Note:', 'weaver-xtreme' /*adm*/) . '</span>',
            'type' => 'note',
            'info' => esc_html__('Hiding any meta info item automatically uses Icons instead of text descriptions.', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . esc_html__('Hide Post Date', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'post_hide_date',
            'type' => 'checkbox',
            'info' => esc_html__('Hide the post date everywhere it is normally displayed.', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . esc_html__('Hide Post Author', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'post_hide_author',
            'type' => 'checkbox',
            'info' => esc_html__('Hide the post author everywhere it is normally displayed.', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . esc_html__('Hide Post Categories', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'post_hide_categories',
            'type' => 'checkbox',
            'info' => esc_html__('Hide the post categories wherever they are normally displayed.', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . esc_html__('Hide Post Tags', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'post_hide_tags',
            'type' => 'checkbox',
            'info' => esc_html__('Hide the post tags wherever they are normally displayed.', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . esc_html__('Hide Permalink', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'hide_permalink',
            'type' => 'checkbox',
            'info' => esc_html__('Hide the permalink.', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . esc_html__('Hide Category if Only One', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'hide_singleton_category',
            'type' => 'checkbox',
            'info' => esc_html__('If there is only one overall category defined (Uncategorized), don\'t show Category of post.', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . esc_html__('Hide Author for Single Author Site', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'post_hide_single_author',
            'type' => 'checkbox',
            'info' => esc_html__('Hide author information if site has only a single author.', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => esc_html__('Post Info Links', 'weaver-xtreme' /*adm*/),
            'id' => 'ilink',
            'type' => 'link',
            'info' => esc_html__('Links in post information top and bottom lines.', 'weaver-xtreme' /*adm*/),
        ),

        array('type' => 'submit'),


        array(
            'name' => esc_html__('Featured Image - Posts', 'weaver-xtreme' /*adm*/),
            'id' => '-id',
            'type' => 'subheader_alt',
            'info' => esc_html__('Display of Post Featured Images', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => esc_html__('Full Width FI BG Image:', 'weaver-xtreme' /*adm*/),
            'type' => 'note',
            'info' => wp_kses_post(__('To create full width Post BG images from the FI, check the <em>Post Area Extend BG Attributes</em> box at <em>Full Width</em> tab.', 'weaver-xtreme' /*adm*/)),
        ),

        array(
            'name' => '<small>' . esc_html__("Don't add link to FI", 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'post_fi_nolink',
            'type' => '+checkbox',
            'info' => esc_html__('Do not add link to Featured Image for any post layout. (&#9733;Plus)', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<span class="i-left" style=font-size:120%;">&nbsp;&#10538;</span>' . esc_html__('FI Location - Full Post', 'weaver-xtreme' /*adm*/),
            'id' => 'post_full_fi_location',
            'type' => 'fi_location_post',
            'info' => esc_html__('Where to display Featured Image for full blog posts.', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<small>' . esc_html__('Post Height - Blog View', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'post_blog_min_height',
            'type' => '+val_px',
            'info' => esc_html__('Minimum Height of Post, full or excerpt, with Parallax BG in blog views. (&#9733;Plus)', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<span class="i-left dashicons dashicons-editor-alignleft"></span><small>' . esc_html__('FI Alignment - Full post', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'post_full_fi_align',
            'type' => 'fi_align',
            'info' => esc_html__('Featured Image alignment', 'weaver-xtreme' /*adm*/),
        ),


        array(
            'name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . esc_html__('Hide FI - Full Posts', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'post_full_fi_hide',
            'type' => 'select_hide',
            'info' => esc_html__('Hide Featured Images on full blog posts.', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<small>' . esc_html__('FI Size - Full Posts', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'post_full_fi_size',
            'type' => 'select_id',
            'info' => esc_html__('Media Library Image Size for Featured Image on full posts.', 'weaver-xtreme' /*adm*/),
            'value' => array(
                array('val' => 'thumbnail', 'desc' => esc_html__('Thumbnail', 'weaver-xtreme' /*adm*/)),
                array('val' => 'medium', 'desc' => esc_html__('Medium', 'weaver-xtreme' /*adm*/)),
                array('val' => 'large', 'desc' => esc_html__('Large', 'weaver-xtreme' /*adm*/)),
                array('val' => 'full', 'desc' => esc_html__('Full', 'weaver-xtreme' /*adm*/)),
            ),
        ),
        array(
            'name' => '<span class="i-left" style="font-size:150%;">&harr;</span><small>' . esc_html__('FI Width, Full Posts', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'post_full_fi_width',
            'type' => '+val_percent',
            'info' => esc_html__('Width of Featured Image on Full Posts.  Max Width in %, overrides FI Size selection. (&#9733;Plus)', 'weaver-xtreme' /*adm*/),
        ),


        array(
            'name' => '<span class="i-left" style=font-size:120%;">&nbsp;&#10538;</span>' . esc_html__('FI Location - Excerpts', 'weaver-xtreme' /*adm*/),
            'id' => 'post_excerpt_fi_location',
            'type' => 'fi_location_post',
            'info' => esc_html__('Where to display Featured Image for posts displayed as excerpt.', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<span class="i-left dashicons dashicons-editor-alignleft"></span><small>' . esc_html__('FI Alignment - Excerpts', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'post_excerpt_fi_align',
            'type' => 'fi_align',
            'info' => esc_html__('How to align the Featured Image', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . esc_html__('Hide FI - Excerpts', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'post_excerpt_fi_hide',
            'type' => 'select_hide',
            'info' => esc_html__('Where to hide Featured Images on full blog posts.', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<small>FI Size - Excerpts</small>',
            'id' => 'post_excerpt_fi_size',
            'type' => 'select_id',
            'info' => esc_html__('Media Library Image Size for Featured Image on excerpts.', 'weaver-xtreme' /*adm*/),
            'value' => array(
                array('val' => 'thumbnail', 'desc' => esc_html__('Thumbnail', 'weaver-xtreme' /*adm*/)),
                array('val' => 'medium', 'desc' => esc_html__('Medium', 'weaver-xtreme' /*adm*/)),
                array('val' => 'large', 'desc' => esc_html__('Large', 'weaver-xtreme' /*adm*/)),
                array('val' => 'full', 'desc' => esc_html__('Full', 'weaver-xtreme' /*adm*/)),
            ),
        ),
        array(
            'name' => '<span class="i-left" style="font-size:150%;">&harr;</span><small>' . esc_html__('FI Width, Excerpts', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'post_excerpt_fi_width',
            'type' => '+val_percent',
            'info' => esc_html__('Width of Featured Image on excerpts.  Max Width in %, overrides FI Size selection. (&#9733;Plus)', 'weaver-xtreme' /*adm*/),
        ),


        array(
            'name' => '<span class="i-left" style=font-size:120%;">&nbsp;&#10538;</span>' . esc_html__('FI Location - Single Page', 'weaver-xtreme' /*adm*/),
            'id' => 'post_fi_location',
            'type' => 'fi_location',
            'info' => esc_html__('Where to display Featured Image for posts on single page view.', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<small>' . esc_html__('Post Height - Single Page', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'post_min_height',
            'type' => '+val_px',
            'info' => esc_html__('Minimum Height of Post with Parallax BG in Single Page view. (&#9733;Plus)', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => esc_html__('Parallax FI BG Image:', 'weaver-xtreme' /*adm*/),
            'info' => esc_html__('It will usually be more useful to use the Per Post FI option to specify Parallax BG images.', 'weaver-xtreme' /*adm*/),
            'type' => 'note',
        ),

        array(
            'name' => '<span class="i-left dashicons dashicons-editor-alignleft"></span><small>' . esc_html__('FI Alignment - Single Page', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'post_fi_align',
            'type' => 'fi_align',
            'info' => esc_html__('How to align the Featured Image on Single Page View.', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . esc_html__('Hide FI - Single Page', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'post_fi_hide',
            'type' => 'select_hide',
            'info' => esc_html__('Where to hide Featured Images on single page view.', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<small>' . esc_html__('FI Size - Single Posts', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'post_fi_size',
            'type' => 'select_id',
            'info' => esc_html__('Media Library Image Size for Featured Image on single page view.', 'weaver-xtreme' /*adm*/),
            'value' => array(
                array('val' => 'thumbnail', 'desc' => esc_html__('Thumbnail', 'weaver-xtreme' /*adm*/)),
                array('val' => 'medium', 'desc' => esc_html__('Medium', 'weaver-xtreme' /*adm*/)),
                array('val' => 'large', 'desc' => esc_html__('Large', 'weaver-xtreme' /*adm*/)),
                array('val' => 'full', 'desc' => esc_html__('Full', 'weaver-xtreme' /*adm*/)),
            ),
        ),
        array(
            'name' => '<span class="i-left" style="font-size:150%;">&harr;</span><small>' . esc_html__('FI Width, Single Page', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'post_fi_width',
            'type' => '+val_percent',
            'info' => esc_html__('Width of Featured Image on single page view. Max Width in %, overrides FI Size selection. (&#9733;Plus)', 'weaver-xtreme' /*adm*/),
        ),


        array('type' => 'submit'),


        array(
            'name' => esc_html__('More Post Related Options', 'weaver-xtreme' /*adm*/),
            'id' => '-forms',
            'type' => 'subheader_alt',
            'info' => esc_html__('Other options related to post display, including single pages.', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . wp_kses_post(__('Show <em>Comments are closed.</em>', 'weaver-xtreme' /*adm*/)) . '</small>',
            'id' => 'show_comments_closed',
            'type' => 'checkbox',
            'info' => esc_html__('If comments are off, and no comments have been made, show the <em>Comments are closed.</em> message.', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => esc_html__('Author Info BG', 'weaver-xtreme' /*adm*/),
            'id' => 'post_author_bgcolor',
            'type' => 'ctext',
            'info' => esc_html__('Background color used for Author Bio.', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . esc_html__('Hide Author Bio', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'hide_author_bio',
            'type' => 'checkbox',
            'info' => esc_html__('Hide display of author bio box on Author Archive and Single Post page views.', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<small>' . esc_html__('Allow comments for attachments', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'allow_attachment_comments',
            'type' => 'checkbox',
            'info' => esc_html__('Allow visitors to leave comments for attachments (usually full size media image - only if comments allowed).', 'weaver-xtreme' /*adm*/),
        ),
    );

    ?>
    <div class="options-intro">
        <?php _e('<strong>Post Specifics: </strong>
Options related to <strong>Posts</strong>, including <strong>Background</strong> color, <strong>Columns</strong> displayed
on blog pages, <strong>Title</strong> options, <strong>Navigation</strong> to earlier and later posts, the post
<strong>Info Lines</strong>, <strong>Excerpts</strong>, and <strong>Featured Image</strong> handling.', 'weaver-xtreme' /*adm*/); ?>
        <br/>
        <div class="options-intro-menu">
            <a href="#post-area"><?php _e('Post Area', 'weaver-xtreme' /*adm*/); ?></a> |
            <a href="#post-title"><?php _e('Post Title', 'weaver-xtreme' /*adm*/); ?></a> |
            <a href="#post-layout"><?php _e('Post Layout', 'weaver-xtreme' /*adm*/); ?></a> |
            <a href="#excerpts-full-posts"><?php _e('Excerpts / Full Posts', 'weaver-xtreme' /*adm*/); ?></a> |
            <a href="#post-navigation"><?php _e('Post Navigation', 'weaver-xtreme' /*adm*/); ?></a> |
            <a href="#post-meta-info-areas"><?php _e('Post Meta Info Areas', 'weaver-xtreme' /*adm*/); ?></a> |
            <a href="#featured-image-posts"><?php _e('Featured Image - Posts', 'weaver-xtreme' /*adm*/); ?></a> |
            <a href="#more-post-related-options"><?php _e('More Post Related Options', 'weaver-xtreme' /*adm*/); ?></a>
            |
            <a href="#custom-post-info-lines"><?php _e('Custom Post Info Lines', 'weaver-xtreme' /*adm*/); ?></a>
        </div>
    </div>
    <?php
    weaverx_form_show_options($opts);
    do_action('weaverxplus_admin', 'post_specifics');
    ?>
    <span style="color:green;"><b><?php _e('Hiding/Enabling Page and Post Comments', 'weaver-xtreme' /*adm*/); ?></b></span>
    <?php
    weaverx_help_link('help.html#LeavingComments', esc_html__('Help for Leaving Comments', 'weaver-xtreme' /*adm*/));
    ?>
    <p>
        <?php _e('Controlling "Reply/Leave a Comment" visibility for pages and posts is <strong>not</strong> a theme function.
It is controlled by WordPress settings.
Please click the ? just above to see the help file entry!
(Additional options for comment <em>styling</em> are found on the Content Areas tab.)', 'weaver-xtreme' /*adm*/); ?>
    </p>
    <?php
}


// ======================== Main Options > Footer ========================
function weaverx_mainopts_footer(): void
{
    $opts = array(
        array('type' => 'submit'),

        array(
            'name' => esc_html__('Footer Options', 'weaver-xtreme' /*adm*/),
            'id' => '-admin-generic',
            'type' => 'header',
            'info' => esc_html__('Settings for the footer', 'weaver-xtreme' /*adm*/),
            'help' => 'help.html#FooterOpt',
        ),


        array(
            'name' => esc_html__('Footer Area', 'weaver-xtreme' /*adm*/),
            'id' => 'footer',
            'type' => 'widget_area',
            'info' => esc_html__('Properties for the footer area.', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => esc_html__('Footer Links', 'weaver-xtreme' /*adm*/),
            'id' => 'footerlink',
            'type' => 'link',
            'info' => esc_html__('Color for links in Footer (Uses Standard Link colors if left blank).', 'weaver-xtreme' /*adm*/),
        ),

        array('name' => esc_html__('Footer Other options', 'weaver-xtreme'), 'type' => 'break'),

        array(
            'name' => esc_html__('Global Footer Area Replacement', 'weaver-xtreme'),
            'id' => 'pb_footer_replace_page_id',
            'type' => 'widetext',
            'info' => esc_html__('Provide any page or post ID to serve as global replacement for entire Footer area. This will override and replace most other settings in this section.', 'weaver-xtreme'),
        ),
        array(
            'name' => '<small>' . esc_html__('Page Builder Replacements', 'weaver-xtreme' /*adm*/) . '</small>',
            'type' => 'note',
            'info' => esc_html__('The Customizer interface has options to specify a Replacement Area from page builders.', 'weaver-xtreme' /*adm*/),
        ),

        array('type' => 'submit'),

        array(
            'name' => esc_html__('Footer Widget Area', 'weaver-xtreme' /*adm*/),
            'id' => 'footer_sb',
            'type' => 'widget_area_submit',
            'info' => esc_html__('Properties for the Footer Widget Area.', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => esc_html__('Footer HTML', 'weaver-xtreme' /*adm*/),
            'id' => 'footer_html',
            'type' => 'widget_area',
            'info' => esc_html__('Add arbitrary HTML to Footer Area (in &lt;div id=\"footer-html\"&gt;)', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<span class="i-left dashicons dashicons-editor-code"></span>' . esc_html__('Footer HTML content', 'weaver-xtreme' /*adm*/),
            'id' => 'footer_html_text',
            'type' => 'textarea',
            'placeholder' => esc_html__('Any HTML, including shortcodes.', 'weaver-xtreme' /*adm*/),
            'info' => esc_html__("Add arbitrary HTML", 'weaver-xtreme' /*adm*/),
            'val' => 4,
        ),
        array('type' => 'submit'),
    );

    ?>
    <div class="options-intro">
        <?php _e('<strong>Footer: </strong> 	Options affecting the <strong>Footer</strong> area, including <strong>Background</strong>
color, <strong>Borders</strong>, and the <strong>Copyright</strong> message.', 'weaver-xtreme' /*adm*/); ?>
        <br/>
        <div class="options-intro-menu">
            <a href="#footer-area"><?php _e('Footer Area', 'weaver-xtreme' /*adm*/); ?></a> |
            <a href="#footer-widget-area"><?php _e('Footer Widget Area', 'weaver-xtreme' /*adm*/); ?></a> |
            <a href="#footer-html"><?php _e('Footer HTML', 'weaver-xtreme' /*adm*/); ?></a> |
            <a href="#site-copyright"><?php _e('Site Copyright', 'weaver-xtreme' /*adm*/); ?></a>
        </div>
    </div>
    <?php
    weaverx_form_show_options($opts);
    do_action('weaverxplus_admin', 'footer_opts');
    ?>
    <a id="site-copyright"></a>
    <strong>&copy;</strong>&nbsp;<span
        style="color:blue;"><b><?php _e('Site Copyright', 'weaver-xtreme' /*adm*/); ?></b></span>
    <br/>
    <small>
        <?php _e('If you fill this in, the default copyright notice in the footer will be replaced with the text here.
It will not automatically update from year to year.
Use &amp;copy; to display &copy;.
You can use other HTML as well.
Use <span class="style4">&amp;nbsp;</span> to hide the copyright notice. &diams;', 'weaver-xtreme' /*adm*/); ?>
    </small>
    <br/>

    <span class="dashicons dashicons-editor-code"></span>
    <?php weaverx_textarea(weaverx_getopt('copyright'), 'copyright', 1, ' ', 'width:85%;'); ?>
    <br>
    <label><span
                class="dashicons dashicons-visibility"></span> <?php _e('Hide Powered By tag:', 'weaver-xtreme' /*adm*/); ?>
        <input type="checkbox" name="<?php weaverx_sapi_main_name('_hide_poweredby'); ?>"
               id="_hide_poweredby" <?php checked(weaverx_getopt_checked('_hide_poweredby')); ?> />
    </label>
    <small><?php _e('Check this to hide the "Proudly powered by" notice in the footer.', 'weaver-xtreme' /*adm*/); ?></small>
    <br/><br/>
    <?php _e('You can add other content to the Footer from the Advanced Options:HTML Insertion tab.', 'weaver-xtreme' /*adm*/); ?>
    <?php
}

// ======================== Main Options > Widget Areas ========================
function weaverx_mainopts_widgets(): void
{
    $opts = array(
        array('type' => 'submit'),
        array(
            'name' => esc_html__('Sidebar Options', 'weaver-xtreme' /*adm*/),
            'id' => '-screenoptions',
            'type' => 'header',
            'info' => esc_html__('Settings affecting main Sidebars and individual widgets', 'weaver-xtreme' /*adm*/),
            'help' => 'help.html#WidgetAreas',
        ),

        array(
            'name' => esc_html__('Individual Widgets', 'weaver-xtreme' /*adm*/),
            'id' => 'widget',
            'type' => 'widget_area',
            'info' => esc_html__('Properties for individual widgets (e.g., Text, Recent Posts, etc.)', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => esc_html__('Widget Title', 'weaver-xtreme' /*adm*/),
            'id' => 'widget_title',
            'type' => 'titles',
            'info' => esc_html__('Color for Widget Titles.', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => esc_html__('Bar under Widget Titles', 'weaver-xtreme' /*adm*/),
            'id' => 'widget_title_underline_int',
            'type' => 'val_px',
            'info' => esc_html__('Enter size in px if you want a bar under Widget Titles. Leave blank or 0 for no bar.', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => esc_html__('Widget List Bullet', 'weaver-xtreme' /*adm*/),
            'id' => 'widgetlist_bullet',
            'type' => 'select_id',
            'info' => esc_html__('Bullet used for Unordered Lists in Widget areas.', 'weaver-xtreme' /*adm*/),
            'value' => array(
                array('val' => 'disc', 'desc' => esc_html__('Filled Disc (default)', 'weaver-xtreme' /*adm*/)),
                array('val' => 'circle', 'desc' => esc_html__('Circle', 'weaver-xtreme' /*adm*/)),
                array('val' => 'square', 'desc' => esc_html__('Square', 'weaver-xtreme' /*adm*/)),
                array('val' => 'none', 'desc' => esc_html__('None', 'weaver-xtreme' /*adm*/)),
            ),
        ),

        array(
            'name' => esc_html__('Widget Links', 'weaver-xtreme' /*adm*/),
            'id' => 'wlink',
            'type' => 'link',
            'info' => esc_html__('Color for links in widgets (uses Standard Link colors if left blank).', 'weaver-xtreme' /*adm*/),
        ),

        array('type' => 'submit'),


        array(
            'name' => esc_html__('Primary Widget Area', 'weaver-xtreme' /*adm*/),
            'id' => 'primary',
            'type' => 'widget_area_submit',
            'info' => esc_html__('Properties for the Primary (Upper/Left) Sidebar Widget Area.', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => esc_html__('Secondary Widget Area', 'weaver-xtreme' /*adm*/),
            'id' => 'secondary',
            'type' => 'widget_area_submit',
            'info' => esc_html__('Properties for the Secondary (Lower/Right) Sidebar Widget Area.', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => esc_html__('Top Widget Areas', 'weaver-xtreme' /*adm*/),
            'id' => 'top',
            'type' => 'widget_area_submit',
            'info' => esc_html__('Properties for all Top Widget areas (Sitewide, Pages, Blog, Archive).', 'weaver-xtreme' /*adm*/),
        ),


        array(
            'name' => esc_html__('Bottom Widget Areas', 'weaver-xtreme' /*adm*/),
            'id' => 'bottom',
            'type' => 'widget_area',
            'info' => esc_html__('Properties for all Bottom Widget areas (Sitewide, Pages, Blog, Archive).', 'weaver-xtreme' /*adm*/),
        ),

    );

    weaverx_form_show_options($opts);
    ?>
    <hr/>
    <span style="color:blue;"><b>Define Per Page Extra Widget Areas</b></span>
    <?php
    weaverx_help_link('help.html#PPWidgets', 'Help for Per Page Widget Areas');
    ?>
    <br/>
    <small>
        <?php _e('You may define extra widget areas that can then be used in the <em>Per Page</em> settings, or in the <em>Weaver Xtreme Plus</em> [widget_area] shortcode.
Enter a list of one or more widget area names separated by commas.
Your names should include only letters, numbers, or underscores - no spaces or other special characters.
The widgets areas will then appear on the Appearance->Widgets menus.
They can be included on individual pages by adding the name you define here to the "Weaver Xtreme Options For This Page" box on the Edit Page screen. (&diams;)', 'weaver-xtreme' /*adm*/); ?>
    </small>
    <br/>
    <?php weaverx_textarea(weaverx_getopt('_perpagewidgets'), '_perpagewidgets', 1, ' ', $style = 'width:60%;', $class = 'wvrx-edit'); ?>
    <?php
    do_action('weaverxplus_admin', 'widget_areas');
}

// ======================== Main Options > Layout ========================
function weaverx_mainopts_layout(): void
{
    $opts = array(
        array('type' => 'submit'),
        array(
            'name' => esc_html__('Sidebar Layout', 'weaver-xtreme' /*adm*/),
            'id' => '-welcome-widgets-menus',
            'type' => 'header',
            'info' => esc_html__('Sidebar Layout for each type of page ("stack top" used for mobile view)', 'weaver-xtreme' /*adm*/),
            'help' => 'help.html#layout',
        ),

        array(
            'name' => esc_html__('Blog, Post, Page Default', 'weaver-xtreme' /*adm*/),
            'id' => 'layout_default',
            'type' => 'select_id',
            'info' => esc_html__('Select the default theme layout for blog, single post, attachments, and pages.', 'weaver-xtreme' /*adm*/),
            'value' => array(
                array('val' => 'right', 'desc' => esc_html__('Sidebars on Right', 'weaver-xtreme' /*adm*/)),
                array('val' => 'right-top', 'desc' => esc_html__('Sidebars on Right (stack top)', 'weaver-xtreme' /*adm*/)),
                array('val' => 'left', 'desc' => esc_html__(' Sidebars on Left', 'weaver-xtreme' /*adm*/)),
                array('val' => 'left-top', 'desc' => esc_html__(' Sidebars on Left (stack top)', 'weaver-xtreme' /*adm*/)),
                array('val' => 'split', 'desc' => esc_html__('Split - Sidebars on Right and Left', 'weaver-xtreme' /*adm*/)),
                array('val' => 'split-top', 'desc' => esc_html__('Split (stack top)', 'weaver-xtreme' /*adm*/)),
                array('val' => 'one-column', 'desc' => esc_html__('No sidebars, content only', 'weaver-xtreme' /*adm*/)),
            ),
        ),

        array(
            'name' => esc_html__('Archive-like Default', 'weaver-xtreme' /*adm*/),
            'id' => 'layout_default_archive',
            'type' => 'select_id',
            'info' => esc_html__('Select the default theme layout for all other pages - archives, search, etc.', 'weaver-xtreme' /*adm*/),
            'value' => array(
                array('val' => 'right', 'desc' => esc_html__('Sidebars on Right', 'weaver-xtreme' /*adm*/)),
                array('val' => 'right-top', 'desc' => esc_html__('Sidebars on Right (stack top)', 'weaver-xtreme' /*adm*/)),
                array('val' => 'left', 'desc' => esc_html__(' Sidebars on Left', 'weaver-xtreme' /*adm*/)),
                array('val' => 'left-top', 'desc' => esc_html__(' Sidebars on Left (stack top)', 'weaver-xtreme' /*adm*/)),
                array('val' => 'split', 'desc' => esc_html__('Split - Sidebars on Right and Left', 'weaver-xtreme' /*adm*/)),
                array('val' => 'split-top', 'desc' => esc_html__('Split (stack top)', 'weaver-xtreme' /*adm*/)),
                array('val' => 'one-column', 'desc' => esc_html__('No sidebars, content only', 'weaver-xtreme' /*adm*/)),
            ),
        ),

        array(
            'name' => esc_html__('Page', 'weaver-xtreme' /*adm*/),
            'id' => 'layout_page',
            'type' => 'select_layout',
            'info' => esc_html__('Layout for normal Pages on your site.', 'weaver-xtreme' /*adm*/),
            'value' => '',
        ),
        array(
            'name' => esc_html__('Blog', 'weaver-xtreme' /*adm*/),
            'id' => 'layout_blog',
            'type' => 'select_layout',
            'info' => esc_html__('Layout for main blog page. Includes "Page with Posts" Page templates.', 'weaver-xtreme' /*adm*/),
            'value' => '',
        ),
        array(
            'name' => esc_html__('Post Single Page', 'weaver-xtreme' /*adm*/),
            'id' => 'layout_single',
            'type' => 'select_layout',
            'info' => esc_html__('Layout for Posts displayed as a single page.', 'weaver-xtreme' /*adm*/),
            'value' => '',
        ),

        array(
            'name' => esc_html__('Attachments', 'weaver-xtreme' /*adm*/),
            'id' => 'layout_image',
            'type' => '+select_layout',
            'info' => esc_html__('Layout for attachment pages such as images. (&#9733;Plus)', 'weaver-xtreme' /*adm*/),
            'value' => '',
        ),

        array(
            'name' => esc_html__('Date Archive', 'weaver-xtreme' /*adm*/),
            'id' => 'layout_archive',
            'type' => '+select_layout',
            'info' => esc_html__('Layout for archive by date pages. (&#9733;Plus)', 'weaver-xtreme' /*adm*/),
            'value' => '',
        ),

        array(
            'name' => esc_html__('Category Archive', 'weaver-xtreme' /*adm*/),
            'id' => 'layout_category',
            'type' => '+select_layout',
            'info' => esc_html__('Layout for category archive pages. (&#9733;Plus)', 'weaver-xtreme' /*adm*/),
            'value' => '',
        ),
        array(
            'name' => esc_html__('Tags Archive', 'weaver-xtreme' /*adm*/),
            'id' => 'layout_tag',
            'type' => '+select_layout',
            'info' => esc_html__('Layout for tag archive pages. (&#9733;Plus)', 'weaver-xtreme' /*adm*/),
            'value' => '',
        ),

        array(
            'name' => wp_kses_post(__('Author Archive</small>', 'weaver-xtreme' /*adm*/)),
            'id' => 'layout_author',
            'type' => '+select_layout',
            'info' => esc_html__('Layout for author archive pages. (&#9733;Plus)', 'weaver-xtreme' /*adm*/),
            'value' => '',
        ),
        array(
            'name' => wp_kses_post(__('Search Results, 404</small>', 'weaver-xtreme' /*adm*/)),
            'id' => 'layout_search',
            'type' => '+select_layout',
            'info' => esc_html__('Layout for search results and 404 pages. (&#9733;Plus)', 'weaver-xtreme' /*adm*/),
            'value' => '',
        ),

        array(
            'name' => '<span class="i-left" style="font-size:120%;">&harr;</span><small>' . esc_html__('Left Sidebar Width', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'left_sb_width_int',
            'type' => 'val_percent',
            'info' => esc_html__('Width for Left Sidebar (Default: 25%)', 'weaver-xtreme' /*adm*/),
            'value' => '',
        ),
        array(
            'name' => '<span class="i-left" style="font-size:120%;">&harr;</span><small>' . esc_html__('Right Sidebar Width', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'right_sb_width_int',
            'type' => 'val_percent',
            'info' => esc_html__('Width for Right Sidebar (Default: 25%)', 'weaver-xtreme' /*adm*/),
            'value' => '',
        ),
        array(
            'name' => '<span class="i-left" style="font-size:120%;">&harr;</span><small>' . esc_html__('Split Left Sidebar Width', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'left_split_sb_width_int',
            'type' => 'val_percent',
            'info' => esc_html__('Width for Split Sidebar, Left Side (Default: 25%)', 'weaver-xtreme' /*adm*/),
            'value' => '',
        ),
        array(
            'name' => '<span class="i-left" style="font-size:120%;">&harr;</span><small>' . esc_html__('Split Right Sidebar Width', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'right_split_sb_width_int',
            'type' => 'val_percent',
            'info' => esc_html__('Width for Split Sidebar, Right Side (Default: 25%)', 'weaver-xtreme' /*adm*/),
            'value' => '',
        ),
        array(
            'name' => '<span class="i-left" style="font-size:120%;">&harr;</span> ' . esc_html__('Content Width:', 'weaver-xtreme' /*adm*/),
            'type' => 'note',
            'info' => esc_html__('The width of content area automatically determined by sidebar layout and width', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => esc_html__('Flow color to bottom', 'weaver-xtreme' /*adm*/),
            'id' => 'flow_color',
            'type' => '+checkbox',
            'info' => esc_html__('If checked, Content and Sidebar bg colors will flow to bottom of the Container (that is, equal heights). You must provide background colors for the Content and Sidebars or the default bg color will be used. (&#9733;Plus)', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => esc_html__('Alt Page Themes', 'weaver-xtreme' /*adm*/),
            'id' => '-editor-codex',
            'type' => 'header_area',
            'info' => esc_html__('&#9733; Weaver Xtreme Plus (V 3.1.1 or later) allows you to set Alternative Themes for the blog, single, and other archive-like pages.', 'weaver-xtreme' /*adm*/),
        ),


    );
    ?>
    <div class="options-intro">
        <strong>Sidebars &amp; Layout: </strong>
        <?php _e('Options affecting <strong>Sidebar Layout</strong> and the main <strong>Sidebar Areas</strong>.
This includes properties of individual <strong>Widgets</strong>, as well as properties of various <strong>Sidebars</strong>.', 'weaver-xtreme' /*adm*/); ?>
        <br/>
        <div class="options-intro-menu">
            <a href="#sidebar-layout"><?php _e('Sidebar Layout', 'weaver-xtreme' /*adm*/); ?></a> |
            <a href="#individual-widgets"><?php _e('Individual Widgets', 'weaver-xtreme' /*adm*/); ?></a> |
            <a href="#primary-widget-area"><?php _e('Primary Widget Area', 'weaver-xtreme' /*adm*/); ?></a> |
            <a href="#secondary-widget-area"><?php _e('Secondary Widget Area', 'weaver-xtreme' /*adm*/); ?></a> |
            <a href="#top-widget-areas"><?php _e('Top Widget Areas', 'weaver-xtreme' /*adm*/); ?></a> |
            <a href="#bottom-widget-areas"><?php _e('Bottom Widget Areas', 'weaver-xtreme' /*adm*/); ?></a>
        </div>
    </div>
    <?php

    weaverx_form_show_options($opts);
    do_action('weaverxplus_admin', 'layout');   // add new layout option?
}
