<?php

namespace SGI\Fitvids\Admin;

use const SGI\Fitvids\BASENAME;

class Settings
{

    /**
     * Plugin options
     * 
     * @since 3.0
     */
    private $opts;

    public function __construct()
    {

        $this->opts = RSFitvids()->getOpts();

        add_filter('plugin_action_links_' . BASENAME, [&$this, 'addSettingsLink']);

        add_action('admin_menu', [&$this, 'addSettingsMenu']);
        add_action('admin_init', [&$this, 'registerSettings']);

    }

    /**
     * Function that adds settings link to the plugin page
     * 
     * @param  array $links Existing plugin links
     * @return array        Merged array with our link
     * 
     * @since 1.0
     */
    public function addSettingsLink($links)
    {

        $links[] = sprintf(
            '<a href="%s">%s</a>',
            admin_url('options-general.php?page=sgi-fitvids'),
            __('Settings','responsive-videos-fitvids')
        );

        return $links;

    }

    /**
     * Adds our submenu to WordPress settings menu
     * 
     * @return void
     * 
     * @since 2.0
     */
    public function addSettingsMenu()
    {

        add_submenu_page(
            'options-general.php',
            __('Responsive Videos', 'responsive-videos-fitvids'),
            __('Responsive Videos', 'responsive-videos-fitvids'),
            'manage_options',
            'sgi-fitvids',
            [&$this, 'settingsPage']
        );

    }

    /**
     * Displays the settings form on plugin settings page
     * 
     * @return void
     * 
     * @since 2.0
     */
    public function settingsPage()
    {

        echo '<div class="wrap">';

        printf (
            '<h1>%s</h1>',
            __('Responsive Videos Settings','responsive-videos-fitvids')
        );

        echo '<form method="POST" action="options.php">';
        settings_fields('sgi_fitvids_settings');
        do_settings_sections('sgi-fitvids');
        submit_button();
        echo "</form>";
        echo '</div>';

    }

    /**
     * Registers plugin settings
     * 
     * @return void
     * 
     * @since 1.0
     */
    public function registerSettings()
    {

        register_setting(
            'sgi_fitvids_settings',
            'sgi_fitvids_opts',
            [&$this, 'sanitizeOpts']
        );

        add_settings_section(
            'sgi_fitvids_core',
            __('Core settings','responsive-videos-fitvids'),
            [&$this, 'coreSectionDisplay'],
            'sgi-fitvids'
        );

        add_settings_field(
            'sgi_fitvids_autoconfig',
            __('Autoconfigure', 'responsive-videos-fitvids'),
            [&$this, 'autoconfigDisplay'],
            'sgi-fitvids',
            'sgi_fitvids_core',
            $this->opts['core']['autoconfig']
        );

        add_settings_field(
            'sgi_fitvids_selector',
            __('CSS Selector', 'responsive-videos-fitvids'),
            [&$this, 'cssSelectorDisplay'],
            'sgi-fitvids',
            'sgi_fitvids_core',
            $this->opts['core']['selector']
        );

        add_settings_section(
            'sgi_fitvids_activation',
            __('Activation settings','responsive-videos-fitvids'),
            [&$this, 'activationDisplay'],
            'sgi-fitvids'
        );
        add_settings_field(
            'sgi_fitvids_active_on',
            __('Active on', 'responsive-videos-fitvids'),
            [&$this, 'activeOnDisplay'],
            'sgi-fitvids',
            'sgi_fitvids_activation',
            $this->opts['active']
        );

    }

    /**
     * Function that displays the core section heading information
     * @author Sibin Grasic
     * @since 2.0
     */
    public function coreSectionDisplay()
    {

        printf(
            '<p>%s</p>',
            __(
                'Core plugin settings control main functionality of the plugin',
                'responsive-videos-fitvids'
            )
        );

    }

    /**
     * Function that displays the autoconfig checkbox setting
     * @param boolean $autoconfig 
     * @return void
     * @author Sibin Grasic
     * @since 2.0
     */
    public function autoconfigDisplay($autoconfig)
    {

        printf(
            '<label for="">
                <input type="checkbox" class="sgi-autoconfig" name="sgi_fitvids_opts[core][autoconfig]" %s> %s
            </label>
            <p class="description">%s</p>
            ',
            checked(true, $autoconfig, false),
            __('Autoconfigure plugin','responsive-videos-fitvids'),
            __('Checking this box will automatically configure the plugin on your website. If some of your videos are not responsive, please disable and use manual configuration options','responsive-videos-fitvids')
        );

        echo '
            <script type="text/javascript">
            (function($){

                $(document).ready(function(){

                    $(".sgi-autoconfig").change(function(){

                        if (this.checked) {

                            $(".sgi-selector").prop("disabled",true);
                            $(".sgi-selector-desc strong").show();

                        } else {

                            $(".sgi-selector").prop("disabled",false);
                            $(".sgi-selector-desc strong").hide();

                        }

                    });

                });

            }) (jQuery);
            </script>
        ';

    }

    /**
     * Displays the input box for the CSS selestor
     * 
     * @param string $selector 
     * 
     * @since 2.0
     */
    public function cssSelectorDisplay($selector)
    {

        $style    = 'display:none';
        $disabled = '';
        
        if ($this->opts['core']['autoconfig']) :
            $style    = '';
            $disabled = 'disabled';
        endif;

        if ($selector == '') :
            $selector = '.entry-content';
        endif;

        $desc = sprintf(
            '%s<br><strong style="%s">%s</strong>',
            __('CSS selector for targeting areas with embedded videos','responsive-videos-fitvids'),
            $style,
            __('This option has been disabled because you have enabled auto configuration','responsive-videos-fitvids')
        );

        printf(
            '<input type="text" class="regular-text sgi-selector" name="sgi_fitvids_opts[core][selector]" value="%s" %s>
            <p class="sgi-selector-desc description">%s</p>
            ',
            $selector,
            $disabled,
            $desc            
        );

    }

    /**
     * Displays the activation section heading information
     * 
     * @since 2.0
     */
    public function activationDisplay()
    {

        printf(
            '<p>%s</p>',
            __(
                'Activation settings allow you to enable/disable fitvids script on particular pages of your website',
                'responsive-videos-fitvids'
            )
        );

    }

    /**
     * Function that displays the checkboxes for activation flags
     * @param array $active Array holding activation options
     * @return void
     * @author Sibin Grasic
     * @since 2.0
     */
    public function activeOnDisplay($active)
    {

        printf(
            '<label for="sgi_fitvids_opts[active][post]">
                <input type="checkbox" name="sgi_fitvids_opts[active][post]" %s> %s
            </label>
            <p class="description">%s</p>',
            checked(true, $active['post'], false),
            __('Single Post','responsive-videos-fitvids'),
            __('Activate on single post - is_single()','responsive-videos-fitvids')
        );

        printf(
            '<label for="sgi_fitvids_opts[active][page]">
                <input type="checkbox" name="sgi_fitvids_opts[active][page]" %s> %s
            </label>
            <p class="description">%s</p>',
            checked(true, $active['page'], false),
            __('Single Page','responsive-videos-fitvids'),
            __('Activate on single page - is_page()','responsive-videos-fitvids')
        );

        printf(
            '<label for="sgi_fitvids_opts[active][fp]">
                <input type="checkbox" name="sgi_fitvids_opts[active][fp]" %s> %s
            </label>
            <p class="description">%s</p>',
            checked(true, $active['fp'], false),
            __('Front Page','responsive-videos-fitvids'),
            __('Activate on Front Page - is_front_page()','responsive-videos-fitvids')
        );

        printf(
            '<label for="sgi_fitvids_opts[active][arch]">
                <input type="checkbox" name="sgi_fitvids_opts[active][arch]" %s> %s
            </label>
            <p class="description">%s</p>',
            checked(true, $active['arch'], false),
            __('Archive Pages','responsive-videos-fitvids'),
            __('Activate on Archive Pages','responsive-videos-fitvids')
        );

    }

    /**
     * Function that "sanitizes" options
     *
     * @param array  $opts Plugin options
     * @return array       Sanitizedoptions array
     * 
     * @since 1.0
     */
    public function sanitizeOpts($opts)
    {

        if ($opts['core']['autoconfig'] == 'on') :

            $opts['core']['autoconfig'] = true;

        else :

            $opts['core']['autoconfig'] = false;

        endif;

        foreach ($opts['active'] as $key => $value):

            if ($value == 'on'):
                $opts['active'][$key] = true;
            else:
                $opts['active'][$key] = false;
            endif;

        endforeach;

        //var_dump($opts);die;

        return $opts;
    }


}