<?php
/*
 * Plugin Name: Remove "Powered by WordPress"
 * Version: 1.6.0
 * Plugin URI: https://webd.uk/product/support-us/
 * Description: Removes the WordPress credit on all default WordPress themes and inserts a widget area
 * Author: Webd Ltd
 * Author URI: https://webd.uk
 * Text Domain: remove-powered-by-wp
 */



if (!class_exists('remove_powered_by_wp_class')) {

	class remove_powered_by_wp_class {

        public static $version = '1.6.0';

        public static $rpbw_compatible_themes = array(
            'Inspiro' => 'inspiro',
            'Seedlet' => 'seedlet',
            'Newsup' => 'newsup',
            'Twenty Ten' => 'twentyten',
            'Twenty Eleven' => 'twentyeleven', 
            'Twenty Twelve' => 'twentytwelve', 
            'Twenty Thirteen' => 'twentythirteen', 
            'Twenty Fourteen' => 'twentyfourteen', 
            'Twenty Fifteen' => 'twentyfifteen', 
            'Twenty Sixteen' => 'twentysixteen',
            'Twenty Seventeen' => 'twentyseventeen',
            'Twenty Nineteen' => 'twentynineteen',
            'Twenty Twenty' => 'twentytwenty',
            'Twenty Twenty One' => 'twentytwentyone',
            'Snaps' => 'snaps',
            'Masonic' => 'masonic',
            'OnePress' => 'onepress',
            'GreenLeaf' => 'greenleaf',
            'Customizr' => 'customizr',
            'Solid Construction' => 'solid-construction',
            'Envo Shopper' => 'envo-shopper',
            'NewsCard' => 'newscard',
            'Travelbee' => 'travelbee',
            'Neve' => 'neve',
            'Tonal' => 'tonal',
            'MinimalistBlogger' => 'minimalistblogger'
        );

		function __construct() {

        	register_activation_hook(__FILE__, array($this, 'rpbw_activation'));
            add_action('customize_register', array($this, 'rpbw_customize_register'));
            add_action('wp_head' , array($this, 'rpbw_header_output'));
            add_action('widgets_init', array($this, 'rpbw_site_info_sidebar_init'), 11);

            if (is_admin()) {

                add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'rpbw_add_plugin_action_links'));
                add_action('admin_notices', 'rpbwCommon::admin_notices');
                add_action('wp_ajax_dismiss_rpbw_notice_handler', 'rpbwCommon::ajax_notice_handler');
                add_action('rpbw_admin_notice_donate', array($this, 'rpbw_admin_notice_upsell'));

            }

		}

		function rpbw_add_plugin_action_links($links) {

			$settings_links = rpbwCommon::plugin_action_links(add_query_arg('autofocus[section]', (get_template() == 'twentytwenty' ? 'options' : (get_template() == 'newsup' ? 'footer_options' : 'theme_options')), admin_url('customize.php')));

			return array_merge($settings_links, $links);

		}

        public static function rpbw_compatible_theme_installed() {

            $installed_themes = wp_get_themes();

            foreach (self::$rpbw_compatible_themes as $key => $value) {

                if (isset($installed_themes[$value]) && $installed_themes[$value]) {

                    return true;
                    break;

                }

            }

            return false;

        }

        function rpbw_customize_register($wp_customize) {

            foreach (array(
                'Twenty Seventeen' => 'twentyseventeen',
                'Twenty Nineteen' => 'twentynineteen',
                'Twenty Twenty' => 'twentytwenty',
                'Twenty Twenty One' => 'twentytwentyone'
            ) as $key => $value) {

                if (get_template() == $value && !class_exists('options_for_' . str_replace(' ', '_', strtolower($key)) . '_class')) {

                    $wp_customize->add_section('more_theme_options', array(
                        'title'     => __('More Theme Options', 'remove-powered-by-wp'),
                        'description'  => sprintf(__('Would you like even more options and features for your theme %s?', 'remove-powered-by-wp'), $key),
                        'priority'     => 0
                    ));

                    rpbwCommon::add_hidden_control($wp_customize, 'install_' . $value, 'more_theme_options', __('Options for ' . $key), 
                    
                    sprintf(wp_kses(__('<a href="%s" class="button">Install Options for %s Plugin</a>', 'remove-powered-by-wp'), array('a' => array('href' => array(), 'class' => array()))), esc_url(add_query_arg(array(
                            's' => $value . ' please our modification',
                            'tab' => 'search',
                            'type' => 'term'
                        ), admin_url('plugin-install.php'))), $key));

                }

            }

            if (!in_array(get_template(), array('solid-construction', 'newsup', 'twentyseventeen', 'twentytwenty'), true)) {

                $wp_customize->add_section('theme_options', array(
                    'title'    => __('Theme Options', 'remove-powered-by-wp'),
                    'priority' => 130
                ));

            } elseif (in_array(get_template(), array('solid-construction'), true)) {

                $wp_customize->add_section('theme_options', array(
                	'panel'    => 'solid_construction_theme_options',
                    'title'    => __('Footer Options', 'remove-powered-by-wp'),
                    'priority' => 0
                ));

            }

            $wp_customize->add_setting('remove_powered_by_wordpress', array(
                'default'       => false,
                'type'          => 'theme_mod',
                'transport'     => 'refresh',
                'sanitize_callback' => 'rpbwCommon::sanitize_boolean'
            ));

            if (in_array(get_template(), array('newscard', 'newsup', 'seedlet', 'twentynineteen', 'twentytwentyone', 'snaps'), true)) {

                $description = __('Removes the "Proudly powered by WordPress" text displayed in the website footer.', 'remove-powered-by-wp');

            } elseif (in_array(get_template(), array('twentytwenty', 'neve', 'minimalistblogger'))) {

                $description = __('Removes the "Powered by WordPress" text displayed in the website footer.', 'remove-powered-by-wp');

            } elseif (in_array(get_template(), array('inspiro', 'masonic', 'travelbee', 'tonal'))) {

                $description = __('Removes the "Powered by WordPress" text displayed in the website footer and replaces with the content of the "Site Info" widget area.', 'remove-powered-by-wp');

            } elseif (in_array(get_template(), array('customizr'))) {

                $description = __('Removes the "Powered by WP" text displayed in the website footer and replaces with the content of the "Site Info" widget area.', 'remove-powered-by-wp');

            } elseif (in_array(get_template(), array('solid-construction'))) {

                $description = __('Removes the "SOLID CONSTRUCTION BY" text displayed in the website footer.', 'remove-powered-by-wp');

            } elseif (in_array(get_template(), array('greenleaf'))) {

                $description = __('Removes the "Theme by" text displayed in the website footer and replaces with the content of the "Site Info" widget area.', 'remove-powered-by-wp');

            } else {

                $description = __('Removes the "Proudly powered by WordPress" text displayed in the website footer and replaces with the content of the "Site Info" widget area.', 'remove-powered-by-wp');

            }

            $wp_customize->add_control('remove_powered_by_wordpress', array(
                'label'         => __('Remove Powered by WordPress', 'remove-powered-by-wp'),
                'description'   => $description,
                'section'       => (get_template() == 'twentytwenty' ? 'options' : (get_template() == 'newsup' ? 'footer_options' : 'theme_options')),
                'settings'      => 'remove_powered_by_wordpress',
                'type'          => 'checkbox'
            ));

        }

        function rpbw_header_output() {

?>
<!--Customizer CSS--> 
<style type="text/css">
<?php

            if (get_theme_mod('remove_powered_by_wordpress')) {

                switch (get_template()) {

                    case 'newsup':

                        if (get_theme_mod('remove_powered_by_wordpress')) {

?>
.mg-footer-copyright .text-xs:first-child {
    display: none;
}
.mg-footer-copyright .col-md-6 {
    flex: 0 0 100%;
    max-width: 100%;
}
<?php

                        }

                        break;

                    case 'inspiro':

                        add_action('wp_footer', array($this, 'rpbw_get_site_info_sidebar'));
                        rpbwCommon::generate_css('.site-info .copyright', 'display', 'remove_powered_by_wordpress', '', '', 'none');

                        break;

                    case 'seedlet':

                        rpbwCommon::generate_css('.imprint', 'display', 'remove_powered_by_wordpress', '', '', 'none');

                        break;

                    case 'twentyten':

                        add_action('twentyten_credits', array($this, 'rpbw_get_site_info_sidebar'));
                        rpbwCommon::generate_css('#footer #site-generator>a', 'display', 'remove_powered_by_wordpress', '', '', 'none');

?>
#site-generator a {
    background-image: none;
    display: inline;
    padding-left: 0;
}
#site-generator p {
    margin: 0;
}
<?php

                        break;

                    case 'twentyeleven':

                        add_action('twentyeleven_credits', array($this, 'rpbw_get_site_info_sidebar'));

?>
#site-generator>span {
    display: none;
}
#site-generator>a:last-child {
    display: none;
}
#site-generator p {
    margin: 0;
}
<?php

                        break;

                    case 'twentytwelve':

                        add_action('twentytwelve_credits', array($this, 'rpbw_get_site_info_sidebar'));

?>
.site-info>span {
    display: none;
}
.site-info>a:last-child {
    display: none;
}
<?php

                        break;

                    case 'twentythirteen':

                        add_action('twentythirteen_credits', array($this, 'rpbw_get_site_info_sidebar'));

?>
.site-info>span {
    display: none;
}
.site-info>a:last-child {
    display: none;
}
.site-info p {
    margin: 0;
}
<?php

                        break;

                    case 'twentyfourteen':

                        add_action('twentyfourteen_credits', array($this, 'rpbw_get_site_info_sidebar'));

?>
.site-info>span {
    display: none;
}
.site-info>a:last-child {
    display: none;
}
.site-info p {
    margin: 0;
}
<?php

                        break;

                    case 'twentyfifteen':
                        add_action('twentyfifteen_credits', array($this, 'rpbw_get_site_info_sidebar'));
?>
.site-info>span {
    display: none;
}
.site-info>a:last-child {
    display: none;
}
<?php

                        break;

                    case 'twentysixteen':

                        add_action('twentysixteen_credits', array($this, 'rpbw_get_site_info_sidebar'));

?>
.site-footer span[role=separator] {
    display: none;
}
.site-info>a:last-child {
    display: none;
}
.site-footer .site-title:after {
    display: none;
}
<?php

                        break;

                    case 'twentyseventeen':

                        add_action('get_template_part_template-parts/footer/site', array($this, 'rpbw_get_site_info_sidebar'));

?>
.site-info:last-child a:last-child {
    display: none;
}
.site-info:last-child span {
    display: none;
}
.site-info p {
    margin: 0;
}
<?php

                        break;

                    case 'twentynineteen':

                        add_action('wp_footer', array($this, 'rpbw_remove_site_info_comma'));

?>
.site-info>.imprint {
    display: none;
}
.site-name {
    margin-right: 1rem;
}
<?php

                        break;

                    case 'twentytwenty':

?>
.powered-by-wordpress {
    display: none;
}
<?php

                        break;

                    case 'twentytwentyone':

?>
.powered-by {
    display: none;
}
<?php

                        break;

                    case 'snaps':

?>
.site-info {
    display: none;
}
<?php

                        break;

                    case 'masonic':

                        add_action('wp_footer', array($this, 'rpbw_replace_masonic_copyright'));

                        break;

                    case 'onepress':

                        remove_action('onepress_footer_site_info', 'onepress_footer_site_info');
                        add_action('onepress_footer_site_info', 'remove_powered_by_wp_class::onepress_footer_site_info');

                        break;

                    case 'customizr':

                        add_action('wp_footer', array($this, 'rpbw_replace_customizr_copyright'));
                        add_filter('tc_wp_powered', '__return_empty_string');

                        break;

                    case 'greenleaf':

                        add_action('wp_footer', array($this, 'rpbw_replace_greenleaf_theme_by'));

                        break;

                    case 'solid-construction':

                        add_action('wp_footer', array($this, 'rpbw_replace_solid_construction_by'));

                        break;

                    case 'envo-shopper':

                        remove_action('envo_shopper_generate_footer', 'envo_shopper_generate_construct_footer', 20);

                        add_action('envo_shopper_generate_footer', 'remove_powered_by_wp_class::envo_shopper_generate_footer', 20);

                        break;

                    case 'newscard':

?>
.site-info .copyright .theme-link::after, .site-info .copyright .author-link, .site-info .copyright .wp-link {
    display: none;
}
<?php

                        break;

                    case 'travelbee':

                        remove_action('travelbee_footer', 'travelbee_footer_bottom', 40);
                        add_action('travelbee_footer', array($this, 'rpbw_travelbee_footer_bottom'), 40);

                        break;

                    case 'neve':

                        global $wp_filter;

                    	if (isset($wp_filter['neve_after_slot_component']->callbacks)) {

                            foreach ($wp_filter['neve_after_slot_component']->callbacks as $callback_key => $callback_value) {

                                foreach ($callback_value as $callback_array_key => $callback__array_value) {

                                    if ('add_footer_component' === $callback__array_value['function'][1]) {

                                        unset($wp_filter['neve_after_slot_component']->callbacks[$callback_key]);

                                    }

                                }

                            }

                    		if (!$wp_filter['neve_after_slot_component']->callbacks) {

                    			unset($wp_filter['neve_after_slot_component']);

		                    }

                    	}

                        break;

                    case 'tonal':

                        add_action('wp_footer', array($this, 'rpbw_get_site_info_sidebar'));

                        break;

                    case 'minimalistblogger':

?>
.footer-info-right {
    display: none;
}
<?php

                        break;

                }

            }

?>
</style> 
<!--/Customizer CSS-->
<?php

        }

        function rpbw_site_info_sidebar_init() {

            if (!in_array(get_template(), array('twentynineteen', 'twentytwenty', 'twentytwentyone', 'newsup', 'seedlet'), true)) {

            	register_sidebar( array(
            		'name'          => __('Site Info', 'remove-powered-by-wp'),
            		'id'            => 'site-info',
            		'description'   => __('Add widgets here to appear in your footer site info.', 'remove-powered-by-wp'),
    		        'before_widget' => '',
            		'after_widget'  => '',
            		'before_title'  => '<h2 class="widget-title">',
            		'after_title'   => '</h2>',
            	) );

            }

        }

        function rpbw_get_site_info_sidebar() {

            if (is_active_sidebar('site-info')) {

                switch (get_template()) {

                    case 'twentyten':
                    case 'twentyeleven':
                    case 'twentytwelve':
                    case 'twentythirteen':
                    case 'twentyfourteen':
                    case 'twentyfifteen':
                    case 'twentysixteen':
                        dynamic_sidebar('site-info');
                        break;

                    case 'inspiro':
                        echo('<div id="site-info">');
                        dynamic_sidebar('site-info');
                        echo('</div>');

?>
<script type="text/javascript">
    (function() {
        document.getElementsByClassName('copyright')[0].innerHTML = '';
        while (document.getElementById('site-info').childNodes.length > 0) {
            document.getElementsByClassName('copyright')[0].appendChild(document.getElementById('site-info').childNodes[0]);
        }
        document.getElementsByClassName('copyright')[0].style.display = 'block';
        document.getElementById('site-info').remove();
    })();
</script>
<?php

                        break;

                    case 'twentyseventeen':
                        echo('<div class="site-info">');
                        dynamic_sidebar('site-info');
                        echo('</div>');
                        break;

                    case 'tonal':
                        echo('<div class="site-info" id="site-info">');
                        dynamic_sidebar('site-info');
                        echo('</div>');

?>
<script type="text/javascript">
    (function() {
        document.getElementsByClassName('site-info')[0].remove();
        document.getElementById('colophon').appendChild(document.getElementById('site-info'));
    })();
</script>
<?php

                        break;

                }

            } else {

                switch (get_template()) {

                    case 'tonal':

?>
<script type="text/javascript">
    (function() {
        document.getElementsByClassName('site-info')[0].remove();
    })();
</script>
<?php

                        break;

                }

            }

        }

        function rpbw_remove_site_info_comma() {

?>
<script type="text/javascript">
    (function() {
        document.getElementsByClassName('site-info')[0].innerHTML = document.getElementsByClassName('site-info')[0].innerHTML.split('</a>,\n\t\t\t\t\t\t').join('</a>');
    })();
</script>
<?php

        }

        function rpbw_activation() {

            set_theme_mod('remove_powered_by_wordpress', true);

        }

        function rpbw_admin_notice_upsell() {

            foreach (array(
                'Twenty Seventeen' => 'twentyseventeen',
                'Twenty Nineteen' => 'twentynineteen',
                'Twenty Twenty' => 'twentytwenty',
                'Twenty Twenty One' => 'twentytwentyone'
            ) as $key => $value) {

                if (get_template() == $value) {

                    echo '<p>';
                    printf(
                        __('You are using %s theme so you should try %s plugin which has loads more options and features!', 'remove-powered-by-wp'),
                        '<strong>' . $key . '</strong>',
                        '<strong><a href="' . add_query_arg(array(
                            's' => $value . ' please our modification',
                            'tab' => 'search',
                            'type' => 'term'
                        ), admin_url('plugin-install.php')) . '" title="' . __('Options for ' . $key, 'remove-powered-by-wp') . '">' . __('Options for ' . $key, 'remove-powered-by-wp') . '</a></strong>'
                    );
                    echo '</p>';

                }

            }

        }

        public function rpbw_replace_masonic_copyright() {

            if (is_active_sidebar('site-info')) {

                echo('<div class="site-info">');
                dynamic_sidebar('site-info');
                echo('</div>');

            }

?>
<script type="text/javascript">
    (function() {
        var copyright = document.getElementsByClassName('copyright')[0],
            copyrightHeader = document.getElementsByClassName('copyright-header')[0],
            copyrightYear = document.getElementsByClassName('copyright-year')[0]
            siteInfo = document.getElementsByClassName('site-info');
        copyright.textContent = '';
        copyright.append(copyrightHeader);
        copyright.append(copyrightYear);
        if (siteInfo.length) {
            copyright.append(siteInfo[0]);
        }
    })();
</script>
<?php

        }

        public function rpbw_travelbee_footer_bottom() {

?>
    <div class="footer-b">
		<div class="container">
            <div class="footer-bottom-t">
                <?php 
                travelbee_footer_navigation();
                travelbee_social_links(); ?>
            </div><?php

            if (is_active_sidebar('site-info')) {

                echo('<div class="site-info">');
                dynamic_sidebar('site-info');
                echo('</div>');

            }

?>
		</div>
	</div>
<?php

        }

        public function rpbw_replace_customizr_copyright() {

            if (is_active_sidebar('site-info')) {

                echo('<div class="site-info">');
                dynamic_sidebar('site-info');
                echo('</div>');

            }

?>
<script type="text/javascript">
    (function() {
        var credits = document.getElementsByClassName('czr-credits')[0],
            siteInfo = document.getElementsByClassName('site-info');
        credits.textContent = '';
        if (siteInfo.length) {
			siteInfo[0].classList.add('czr-credits');
			credits.replaceWith(siteInfo[0]);
        }
    })();
</script>
<?php

        }

        public function rpbw_replace_greenleaf_theme_by() {

            if (is_active_sidebar('site-info')) {

                echo('<div class="site-info">');
                dynamic_sidebar('site-info');
                echo('</div>');

            }

?>
<script type="text/javascript">
    (function() {
        var themeBy = document.getElementsByClassName('fright')[0],
            siteInfo = document.getElementsByClassName('site-info');
        themeBy.textContent = '';
        if (siteInfo.length) {
            themeBy.append(siteInfo[0]);
        }
    })();
</script>
<?php

        }

        public function rpbw_replace_solid_construction_by() {

            if (is_active_sidebar('site-info')) {

                echo('<div id="site-info">');
                dynamic_sidebar('site-info');
                echo('</div>');

            }

?>
<script type="text/javascript">
    (function() {
        var solidConstructionBy = document.getElementById('footer-left-content'),
            siteInfo = document.getElementById('site-info');
        solidConstructionBy.textContent = '';
        if ('innerHTML' in siteInfo) {
            solidConstructionBy.innerHTML = siteInfo.innerHTML;
        } else {
			solidConstructionBy.remove();
        }
    })();
</script>
<?php

        }

        public static function envo_shopper_generate_footer() {

            if (is_active_sidebar('site-info')) {

        ?>
        <footer id="colophon" class="footer-credits container-fluid">
            <div class="container">    
                <div class="footer-credits-text text-center">
                    <?php

                dynamic_sidebar('site-info');

                    ?>
                </div>
            </div>	
        </footer>
        <?php

            }

        }

        public static function onepress_footer_site_info() {

            if (is_active_sidebar('site-info')) {

                dynamic_sidebar('site-info');

            }

        }

	}

    if (!class_exists('rpbwCommon')) {

        require_once(dirname(__FILE__) . '/includes/class-rpbw-common.php');

    }

    if (rpbwCommon::is_theme_being_used(remove_powered_by_wp_class::$rpbw_compatible_themes)) {

	    $remove_powered_by_wp_object = new remove_powered_by_wp_class();

    } else {

        if (is_admin() && !remove_powered_by_wp_class::rpbw_compatible_theme_installed()) {

            add_action('admin_notices', 'rpbw_wrong_theme_notice');

        }

    }

    function rpbw_wrong_theme_notice() {

?>
<div class="notice notice-error">
<p><strong><?php esc_html_e('Remove "Powered by WordPress" Plugin Error', 'remove-powered-by-wp'); ?></strong><br />
<?php

        printf(
            __('This plugin requires one of the compatible themes to be active or live previewed in order to function. Your theme "%s" is not compatible. Please install and activate or live preview one of these themes (or a child theme thereof):', 'remove-powered-by-wp'),
            get_template()
        );

        $theme_list = array();

        foreach (remove_powered_by_wp_class::$rpbw_compatible_themes as $key => $value) {

            $theme_list[] = '<a href="' . add_query_arg('search', $value, admin_url('theme-install.php')) . '" title="' .  __($key, 'remove-powered-by-wp') . '">' .  __($key, 'remove-powered-by-wp') . '</a>';

        }

        echo ' ' . implode(', ', $theme_list) . '.';

?></p>
</div>
<?php

    }

}

?>
