<?php

namespace WPPayForm\App\Modules\PDF\Manager;

use WPPayForm\App\Services\Protector;
use WPPayForm\App\Services\AccessControl;
use WPPayForm\App\Services\PlaceholderParser;
use WPPayForm\App\Models\Meta;
use WPPayForm\App\Models\Form;
use WPPayForm\App\Models\Submission;
use WPPayForm\App\Services\FormPlaceholders;
use WPPayForm\Framework\Support\Arr;
use FluentPdf\Classes\Controller\AvailableOptions;
use FluentPdf\Classes\Controller\FontDownloader;
use FluentPdf\Classes\PdfBuilder;
use FluentPdf\Classes\Controller\Activator;

class WPPayFormPdfBuilder extends PdfBuilder
{
    protected $optionKey = '_fluent_pdf_settings';

    public function __construct()
    {
        $this->registerHooks();
    }

    protected function registerHooks()
    {
        // Global settings register
        add_filter('wppayform_global_settings_components', [$this, 'globalSettingMenu']);
        // add_filter('wppayform/admin_app_vars', [$this, 'formSettingsMenu']);

        add_action('wp_ajax_wppayform_pdf_admin_ajax_actions', [$this, 'ajaxRoutes']);

        add_filter('wppayform_single_entry_widgets', array($this, 'pushPdfButtons'), 10, 2);

        add_filter('wppayform_email_attachments', array($this, 'maybePushToEmail'), 10, 5);

        add_action('wppayform_addons_page_render_fluent_pdf_settings', array($this, 'renderGlobalPage'));

        // if (!function_exists('wpFluentForm')) {
        //     add_action('admin_notices', function () {
        //         if (!get_option($this->optionKey) && AccessControl::hasTopLevelMenuPermission())
        //             echo '<div class="notice notice-warning"><p>Fluent PDF require to download fonts. Please <a href="' . admin_url('admin.php?page=wppayform.php#/integrations/pdf') . '">click here</a> to download and configure the settings</p></div>';
        //     });
        // }

        // add_filter('wpf_pdf_body_parse', function($content, $entryId, $formData, $form){

        //     if(!defined('WPPAYFORMPRO')){
        //         return $content;
        //     }
        //     $processor = new \FluentFormPro\classes\ConditionalContent();
        //     return $processor::initiate($content, $entryId, $formData, $form);
        // }, 10, 4);



        add_filter('wppayform/all_shortcodes', [$this, 'pushShortCode'], 10, 2);
        add_filter('wppayform/all_placeholders', [$this, 'pushPlaceHolders'],10,2);

        add_filter(
            'wppayform_shortcode_parser_callback_pdf.download_link', 
            [$this, 'createLink'],
            10, 
            2
        );

        add_filter(
            'wppayform_shortcode_parser_callback_pdf.download_link.public', 
            [$this, 'createPublicLink'],
            10,
            2
        );

        add_action('wp_ajax_wppayform_pdf_download', [$this, 'download']);
        add_action('wp_ajax_wppayform_pdf_download_public', [$this, 'downloadPublic']);
        add_action('wp_ajax_nopriv_wppayform_pdf_download_public', [$this, 'downloadPublic']);
    }

    public function globalSettingMenu($setting)
    {
        $assetUrl = WPPAYFORM_URL . 'assets/';
        $setting["pdf_settings"] = [
            "hash" => "pdf_settings",
            "title" => __("PDF Settings", 'wp-payment-form'),
            "svg"   => '<img  src="' . $assetUrl . '/images/form/pdf.svg"/>',
        ];

        return $setting;
    }

    public function formSettingsMenu($settingsMenus)
    {
        // require_once FLUENT_PDF_PATH . '/Classes/Controller/FontDownloader.php';
        // $downloadable_font_files = (new \FluentPdf\Classes\Controller\FontDownloader())->getDownloadableFonts();
        // if ($downloadable_font_files){
        //     return $settingsMenus;
        // }

        // $settingsMenus['pdf'] = [
        //     'title' => __('PDF Feeds', 'wp-payment-form'),
        //     'slug' => 'pdf-feeds',
        //     'hash' => 'pdf',
        //     'route' => '/pdf-feeds',
        //     'svg' => '/images/form/pdf.svg'
        // ];

        // return $settingsMenus;
    }

    public function ajaxRoutes()
    {
        $maps = [
            'get_global_settings' => 'getGlobalSettingsAjax',
            'save_global_settings' => 'saveGlobalSettings',
            'get_feeds' => 'getFeedsAjax',
            'feed_lists' => 'getFeedListAjax',
            'create_feed' => 'createFeedAjax',
            'get_feed' => 'getFeedAjax',
            'save_feed' => 'saveFeedAjax',
            'delete_feed' => 'deleteFeedAjax',
            'download_pdf' => 'getPdf',
            'downloadFonts' => 'downloadFonts'
        ];

        $route = sanitize_text_field($_REQUEST['route']);

        AccessControl::hasTopLevelMenuPermission();

        if (isset($maps[$route])) {
            $this->{$maps[$route]}();
        }
    }

    public function getGlobalSettings()
    {
        $defaults = [
            'paper_size' => 'A4',
            'orientation' => 'P',
            'font' => 'default',
            'font_size' => '14',
            'font_color' => '#323232',
            'accent_color' => '#989797',
            'heading_color' => '#000000',
            'language_direction' => 'ltr'
        ];

        $option = get_option($this->optionKey);
        wp_send_json_success([
            'settings' => wp_parse_args($option, $defaults),
            'fields' => $this->getGlobalFields()
        ]);
    }


    public function saveGlobalSettings()
    {
        $settings = wp_unslash($_REQUEST['settings']);
        update_option($this->optionKey, $settings);
        wp_send_json_success([
            'message' => __('Settings successfully updated', 'wp-payment-form')
        ], 200);
    }

    public function getFeedsAjax($request)
    {
        $formId = intval($request->form_id);

        $feeds = $this->getFeeds($formId);

        $shortCodes = FormPlaceholders::getAllShortCodes($formId);
        $placeholders = FormPlaceholders::getAllPlaceholders($formId);

        wp_send_json_success([
            'pdf_feeds' => $feeds,
            'templates' => $this->getAvailableTemplates($formId),
            'editor_shortcodes' => $shortCodes,
            'placeholders' => $placeholders
        ], 200);
    }

    public function getFeedList()
    {
        $formId = intval($_REQUEST['form_id']);

        $feeds = $this->getFeeds($formId);

        $formattedFeeds = [];
        foreach ($feeds as $feed) {
            $formattedFeeds[] = [
                'label' => $feed['name'],
                'id' => $feed['id']
            ];
        }

        wp_send_json_success([
            'pdf_feeds' => $formattedFeeds
        ], 200);
    }

    private function globalSettings()
    {
        $defaults = [
            'paper_size' => 'A4',
            'orientation' => 'P',
            'font' => 'default',
            'font_size' => '14',
            'font_color' => '#323232',
            'accent_color' => '#989797',
            'heading_color' => '#000000',
            'language_direction' => 'ltr'
        ];

        $option = get_option($this->optionKey);
        if (!$option || !is_array($option)) {
            return $defaults;
        }

        return wp_parse_args($option, $defaults);

    }

    public function createFeed($request)
    {
        $formId = intval($request->form_id);
        $templateName = sanitize_text_field($request->template);
        $templates = $this->getAvailableTemplates($formId);

        if (!isset($templates[$templateName]) || !$formId) {
            wp_send_json_error([
                'message' => __('Sorry! No template found!', 'wp-payment-form')
            ], 423);
        }

        $template = $templates[$templateName];

        $class = $template['class'];
        if (!class_exists($class)) {
            wp_send_json_error([
                'message' => __('Sorry! No template Class found!!', 'wp-payment-form')
            ], 423);
        }
        $instance = new $class();
        $defaultSettings = $instance->getDefaultSettings($formId);

        $data = [
            'name' => $template['name'],
            'template_key' => $templateName,
            'settings' => $defaultSettings,
            'appearance' => $this->globalSettings()
        ];

        $insertMeta = Meta::create([
            'meta_key' => '_pdf_feeds',
            'form_id' => $formId,
            'meta_value' => wp_json_encode($data)
        ]);

        wp_send_json_success([
            'feed_id' => $insertMeta->id,
            'message' => __('Feed has been created, edit the feed now', 'wp-payment-form')
        ], 200);
    }

    private function getFeeds($formId)
    {
        $feeds = Meta::where('form_id', $formId)
            ->where('meta_key', '_pdf_feeds')
            ->get();

        $formattedFeeds = [];
        foreach ($feeds as $feed) {
            $settings = json_decode($feed->meta_value, true);
            $settings['id'] = $feed->id;
            $formattedFeeds[] = $settings;
        }

        return $formattedFeeds;
    }

    public function getFeed($request)
    {  
        $formId = intval($_REQUEST['form_id']);

        $feedId = intval($_REQUEST['feed_id']);

        $feed = Meta::where('id', $feedId)
            ->where('meta_key', '_pdf_feeds')
            ->first();

        $settings = json_decode($feed->meta_value, true);
        $templateName = Arr::get($settings, 'template_key');

        $templates = $this->getAvailableTemplates($formId);

        if (!isset($templates[$templateName]) || !$formId) {
            wp_send_json_error([
                'message' => __('Sorry! No template found!', 'wp-payment-form')
            ], 423);
        }

        $template = $templates[$templateName];

        $class = $template['class'];
        if (!class_exists($class)) {
            wp_send_json_error([
                'message' => __('Sorry! No template Class found!', 'wp-payment-form')
            ], 423);
        }
        $instance = new $class();

        $globalFields = $this->getGlobalFields();

        $globalFields[] = [
            'key' => 'watermark_image',
            'label' => 'Water Mark Image',
            'type' => 'image_widget'
        ];

        $globalFields[] = [
            'key' => 'watermark_text',
            'label' => 'Water Mark Text',
            'type' => 'text',
            'placeholder' => 'Watermark text',
            'tips' => 'Water mark text will be set only if watermark image is not set',
        ];

        $globalFields[] = [
            'key' => 'watermark_opacity',
            'label' => 'Water Mark Opacity',
            'type' => 'number',
            'inline_tip' => 'Value should be between 1 to 100'
        ];
        $globalFields[] = [
            'key' => 'watermark_img_behind',
            'label' => 'Water Mark Position',
            'type' => 'checkbox',
            'inline_tip' => 'Set as background'
        ];

        $globalFields[] = [
            'key' => 'security_pass',
            'label' => 'PDF Password',
            'type' => 'text',
            'inline_tip' => 'If you want to set password please give on otherwise leave it empty'
        ];

        $settingsFields = $instance->getSettingsFields();

        $settingsFields[] = [
            'key' => 'allow_download',
            'label' => 'Allow Download',
            'tips' => 'Allow this feed to be downloaded on form submission. Only logged in users will be able to download.',
            'type' => 'radio_choice',
            'options' => [
                true => 'Yes',
                false => 'No'
            ]
        ];

        $settingsFields[] = [
            'key' => 'shortcode',
            'label' => 'Shortcode',
            'tips' => 'Use this shortcode on submission message to generate PDF link.',
            'type' => 'text',
            'readonly' => true
        ];

        $settings['settings']['shortcode'] = '{pdf.download_link.' . $feedId. '}';

        wp_send_json_success([
            'feed' => $settings,
            'settings_fields' => $settingsFields,
            'appearance_fields' => $globalFields
        ], 200);
    }

    public function saveFeed($request)
    {
        $formId = intval($request->form_id);
        $feedId = intval($request->feed_id);
        $feed = wp_unslash($request->feed);

        if (empty($feed['name'])) {
            wp_send_json_error([
                'message' => __('Feed name is required', 'wp-payment-form')
            ], 423);
        }

        Meta::where('id', $feedId)
            ->update([
                'meta_value' => wp_json_encode($feed)
            ]);

        wp_send_json_success([
            'message' => __('Settings successfully updated', 'wp-payment-form')
        ], 200);
    }

    public function deleteFeed($request)
    {
        $feedId = intval($request->feed_id);
        Meta::where('id', $feedId)
            ->where('meta_key', '_pdf_feeds')
            ->delete();

        wp_send_json_success([
            'message' => __('Feed successfully deleted', 'wp-payment-form')
        ], 200);
    }

    /*
    * @return key => [ path, name]
    * To register a new template this filter must hook for path mapping
    * filter: fluentform_pdf_template_map
    */
    public function getAvailableTemplates($formId)
    {
        $templates = [
            "general" => [
                'name' => 'General',
                'class' => '\WPPayForm\App\Modules\PDF\Templates\GeneralTemplate',
                'key' => 'general',
                'preview' => FLUENT_PDF_URL . 'assets/images/basic_template.png'
            ],
            // "custom" => [
            //     'name' => 'PDF Builder',
            //     'class' => '\WPPayForm\App\Modules\PDF\Templates\CustomTemplate',
            //     'key' => 'custom',
            //     'preview' => FLUENTFORM_PDF_URL . 'assets/images/basic_template.png'
            // ]
        ];

        if (Form::hasPaymentFields($formId)) {
            $templates['invoice'] = [
                'name' => 'Invoice',
                'class' => '\WPPayForm\App\Modules\PDF\Templates\InvoiceTemplate',
                'key' => 'invoice',
                'preview' => FLUENT_PDF_URL . 'assets/images/tabular.png'
            ];
        }
        
        return apply_filters('wppayform/pdf_templates', $templates, $formId);
    }


    /*
    * @return [ key name]
    * global pdf setting fields
    */
    public function getGlobalFields()
    {
        return [
            [
                'key' => 'paper_size',
                'label' => 'Paper size',
                'type' => 'dropdown',
                'tips' => 'All available templates are shown here, select a default template',
                'options' => AvailableOptions::getPaperSizes()
            ],
            [
                'key' => 'orientation',
                'label' => 'Orientation',
                'type' => 'dropdown',
                'options' => AvailableOptions::getOrientations()
            ],
            [
                'key' => 'font_family',
                'label' => 'Font Family',
                'type' => 'dropdown-group',
                'placeholder' => 'Select Font',
                'options' => AvailableOptions::getInstalledFonts()
            ],
            [
                'key' => 'font_size',
                'label' => 'Font size',
                'type' => 'number'
            ],
            [
                'key' => 'font_color',
                'label' => 'Font color',
                'type' => 'color_picker'
            ],
            [
                'key' => 'heading_color',
                'label' => 'Heading color',
                'tips' => 'The Color Form Headings',
                'type' => 'color_picker'
            ],
            [
                'key' => 'accent_color',
                'label' => 'Accent color',
                'tips' => 'The accent color is used for the borders, breaks etc.',
                'type' => 'color_picker'
            ],
            [
                'key' => 'language_direction',
                'label' => 'Language Direction',
                'tips' => 'Script like Arabic and Hebrew are written right to left. For Arabic/Hebrew please select RTL',
                'type' => 'radio_choice',
                'options' => [
                    'ltr' => 'LTR',
                    'rtl' => 'RTL'
                ]
            ]
        ];
    }

    public function pushPdfButtons($widgets, $data)
    {
        $fontManager = new FontDownloader();
        $downloadableFiles = $fontManager->getDownloadableFonts();
        if ($downloadableFiles) {
            return $widgets;
        }

        $formId = $data['submission']->form_id;
        $feeds = $this->getFeeds($formId);
        if (!$feeds) {
            return $widgets;
        }
        $widgetData = [
            'title' => __('PDF Downloads', 'wp-payment-form'),
            'type' => 'html_content'
        ];

        $wppayform_admin_nonce = wp_create_nonce('wppayform_admin_nonce');

        $contents = '<ul class="ff_list_items">';
        foreach ($feeds as $feed) {
            $contents .= '<li><a href="' . admin_url('admin-ajax.php?action=wppayform_pdf_admin_ajax_actions&wppayform_admin_nonce='.$wppayform_admin_nonce.'&route=download_pdf&submission_id=' . $data['submission']->id . '&id=' . $feed['id']) . '" target="_blank"><span style="font-size: 12px;" class="dashicons dashicons-arrow-down-alt"></span>' . $feed['name'] . '</a></li>';
        }
        $contents .= '</ul>';
        $widgetData['content'] = $contents;

        $widgets['pdf_feeds'] = $widgetData;
        return $widgets;
    }

    public function getPdfConfig($settings, $default)
    {
        return [
            'mode' => 'utf-8',
            'format' => Arr::get($settings, 'paper_size', Arr::get($default, 'paper_size')),
            'orientation' => Arr::get($settings, 'orientation', Arr::get($default, 'orientation')),
            // 'debug' => true //uncomment this debug on development
        ];
    }

    /*
    * when download button will press
    * Pdf rendering will control from here
    */
    public function getPdf()
    {
        $feedId = intval($_REQUEST['id']);
        $submissionId = intval($_REQUEST['submission_id']);
        $feed = Meta::where('id', $feedId)
            ->where('meta_key', '_pdf_feeds')
            ->first();


        $settings = json_decode($feed->meta_value, true);

        $settings['id'] = $feed->id;

        $templateName = Arr::get($settings, 'template_key');

        $templates = $this->getAvailableTemplates($feed->form_id);

        if (!isset($templates[$templateName])) {
            die('Sorry! No template found');
        }

        $template = $templates[$templateName];

        $class = $template['class'];
        if (!class_exists($class)) {
            die('Sorry! No template class found');
        }

        $instance = new $class();

        $instance->viewPDF($submissionId, $settings);
    }

    public function maybePushToEmail($emailAttachments, $notification, $submission, $formId, $entry)
    {
        $fontManager = new FontDownloader();
        $downloadableFiles = $fontManager->getDownloadableFonts();
        if ($downloadableFiles) {
            return $emailAttachments;
        }

        if (!Arr::get($notification, 'pdf_attachments')) {
            return $emailAttachments;
        }

        $pdfFeedIds = Arr::get($notification, 'pdf_attachments');

        $feeds = Meta::whereIn('id', $pdfFeedIds)
            ->where('meta_key', '_pdf_feeds')
            ->where('form_id', $formId)
            ->get();

        $templates = $this->getAvailableTemplates($formId);

        foreach ($feeds as $feed) {
            $settings = json_decode($feed->meta_value, true);
            $settings['id'] = $feed->id;
            $templateName = Arr::get($settings, 'template_key');

            if (!isset($templates[$templateName])) {
                continue;
            }
            $template = $templates[$templateName];
            $class = $template['class'];
            if (!class_exists($class)) {
                continue;
            }
            $instance = new $class();

            // we have to compute the file name to make it unique
            $fileName = $settings['name'] . '_' . $entry->id . '_' . $feed->id;

            //parse shortcodes in file name
            $fileName = PlaceholderParser::parse( $fileName,  $entry->id, $submission);
            $fileName = sanitize_title($fileName, 'pdf-file', 'display');

            if(is_multisite()) {
                $fileName .= '_'.get_current_blog_id();
            }

            $file = $instance->outputPDF($entry->id, $settings, $fileName, false);
            if ($file) {
                $emailAttachments[] = $file;
            }
        }


        return $emailAttachments;
    }


    public function renderGlobalPage()
    {
        wp_enqueue_script('fluent_pdf_admin', FLUENT_PDF_URL . 'assets/js/admin.js', ['jquery'], FLUENT_PDF_VERSION, true);
        $fontManager = new FontDownloader();
        $downloadableFiles = $fontManager->getDownloadableFonts();

        wp_localize_script('fluent_pdf_admin', 'fluent_pdf_admin', [
            'ajaxUrl' => admin_url('admin-ajax.php')
        ]);

        $statuses = [];
        $globalSettingsUrl = '#';
        if (!$downloadableFiles) {
            $statuses = $this->getSystemStatuses();
            $globalSettingsUrl = admin_url('admin.php?page=fluent_forms_settings#pdf_settings');

            if (!get_option($this->optionKey)) {
                update_option($this->optionKey, $this->getGlobalSettings(), 'no');
            }
        }

        include FLUENT_PDF_PATH . '/assets/views/admin_screen.php';

        // wp_enqueue_script('fluentform_pdf_admin', FLUENTFORM_PDF_URL . 'assets/js/admin.js', ['jquery'], FLUENTFORM_PDF_VERSION, true);
        // $fontManager = new FontDownloader();
        // $downloadableFiles = $fontManager->getDownloadableFonts();

        // wp_localize_script('fluentform_pdf_admin', 'fluentform_pdf_admin', [
        //     'ajaxUrl' => admin_url('admin-ajax.php')
        // ]);


        // $statuses = [];
        // $globalSettingsUrl = '#';
        // if (!$downloadableFiles) {
        //     $statuses = $this->getSystemStatuses();
        //     $globalSettingsUrl = admin_url('admin.php?page=wppayform_settings#pdf_settings');

        //     if (!get_option($this->optionKey)) {
        //         update_option($this->optionKey, $this->globalSettings(), 'no');
        //     }
        // }

        // return wp_send_json([
        //     'statuses' => $statuses,
        //     'downloadableFiles' => $downloadableFiles,
        //     'globalSettingsUrl' => $globalSettingsUrl
        // ], 200);
    }

    public function downloadFonts($request)
    {
        Activator::maybeCreateFolderStructure();

        $fontManager = new FontDownloader();
        $downloadableFiles = $fontManager->getDownloadableFonts(3);

        $downloadedFiles = [];
        foreach ($downloadableFiles as $downloadableFile) {
            $fontName = $downloadableFile['name'];
            $res = $fontManager->download($fontName);
            $downloadedFiles[] = $fontName;
            if (is_wp_error($res)) {
                wp_send_json_error([
                    'message' => 'Font Download failed. Please reload and try again'
                ], 423);
            }
        }

        wp_send_json_success([
            'downloaded_files' => $downloadedFiles
        ], 200);
    }

    private function getSystemStatuses()
    {
        $mbString = extension_loaded('mbstring');
        $mbRegex = extension_loaded('mbstring') && function_exists('mb_regex_encoding');
        $gd = extension_loaded('gd');
        $dom = extension_loaded('dom') || class_exists('DOMDocument');
        $libXml = extension_loaded('libxml');
        $extensions = [
            'mbstring' => [
                'status' => $mbString,
                'label' => ($mbString) ? 'MBString is enabled' : 'The PHP Extension MB String could not be detected. Contact your web hosting provider to fix.'
            ],
            'mb_regex_encoding' => [
                'status' => $mbRegex,
                'label' => ($mbRegex) ? 'MBString Regex is enabled' : 'The PHP Extension MB String does not have MB Regex enabled. Contact your web hosting provider to fix.'
            ],
            'gd' => [
                'status' => $gd,
                'label' => ($gd) ? 'GD Library is enabled' : 'The PHP Extension GD Image Library could not be detected. Contact your web hosting provider to fix.'
            ],
            'dom' => [
                'status' => $dom,
                'label' => ($dom) ? 'PHP Dom is enabled' : 'The PHP DOM Extension was not found. Contact your web hosting provider to fix.'
            ],
            'libXml' => [
                'status' => $libXml,
                'label' => ($libXml) ? 'LibXml is OK' : 'The PHP Extension libxml could not be detected. Contact your web hosting provider to fix'
            ]
        ];

        $overAllStatus = $mbString && $mbRegex && $gd && $dom && $libXml;

        return [
            'status' => $overAllStatus,
            'extensions' => $extensions
        ];
    }

    public function pushShortCode($shortCodes, $formID)
    {
        $feeds = Meta::where('form_id', $formID)
                ->where('meta_key', '_pdf_feeds')
                ->get();

        $feedShortCodes = [
            '{pdf.download_link}' => 'Submission PDF link'
        ];

        foreach ($feeds as $feed) {
            $feedSettings = json_decode($feed->meta_value);
            $key = '{pdf.download_link.' . $feed->id . '}';
            $feedShortCodes[$key] = $feedSettings->name . ' feed PDF link';
        }

        $shortCodes[] = [
            'title' => __('PDF', 'wp-payment-form'),
            'shortcodes' => $feedShortCodes
        ];

        return $shortCodes;
    }

    public function pushPlaceHolders($shortCodes, $formID)
    {
        $feeds = Meta::where('form_id', $formID)
                ->where('meta_key', '_pdf_feeds')
                ->get();

        $feedShortCodes = array(
            'download_link' => array(
                'id' => 'download_link',
                'tag' => '{pdf.download_link}',
                'label' => __('Submission PDF link', 'wp-payment-form')
            ),
        );


        foreach ($feeds as $feed) {
            $feedSettings = json_decode($feed->meta_value);

            $id = 'pdf_download_link';

            $tag = '{pdf.download_link.' . $feed->id . '}';
            $label = $feedSettings->name . ' feed PDF link';

            $feedShortCodes[$label] = array(
                'id' => $id,
                'tag' => $tag,
                'label' => $label
            );
        }

        $shortCodes['pdf'] = [
            'title' => __('PDF', 'wp-payment-form'),
            'placeholders' => $feedShortCodes
        ];

        return $shortCodes;
    }

    /**
     * @var string $shortCode
     * @var \FluentForm\App\Services\FormBuilder\ShortCodeParser $parser
     */
    public function createLink($shortCode, $parser)
    {
        $form = $parser->getForm();
        $entry = $parser->getEntry();

        // Currently we are assuming there is only one PDF Feed.
        // Hence the PDF Download Link will always be the first one.

        $feed = Meta::where('form_id', $form->id)
                    ->where('meta_key', '_pdf_feeds')
                    ->first();

        if ($feed) {
            $feedSettings = json_decode($feed->value, true);

            if (Arr::get($feedSettings, 'settings.allow_download')) {

                $nonce = wp_create_nonce('wpapyform_admin_nonce');

                $url = admin_url('admin-ajax.php?action=wppayform_pdf_download&wpapyform_admin_nonce=' . $nonce . '&submission_id=' . $entry->id . '&id=' . $feed->id);

                return $url;
            }
        }
    }

    public function download()
    {
        if (!is_user_logged_in()) {
            $message = __('Sorry! You have to login first.', 'wp-payment-form');

            wp_send_json_error([
                'message' => $message
            ], 422);
        }

        $hasPermission = AccessControl::hasTopLevelMenuPermission();

        if (!$hasPermission) {
            $submissionId = intval($_REQUEST['submission_id']);
            $submissionModel = new Submission();
            $submission = $submissionModel->getSubmission($submissionId);

            if (!$submission) {
                $message = __("You don't have permission to download the PDF.", 'wp-payment-form');

                wp_send_json_error([
                    'message' => $message
                ], 422);
            }
        }

        return $this->getPdf();
    }

    public function createPublicLink($shortCode, $entry)
    {
        $feedID = str_replace('download_link.', '', $shortCode);
        if ($feedID) {    
            $feed = Meta::where('id', $feedID)->first();
            if ($feed) {
                $hashedEntryID = base64_encode(Protector::encrypt($entry->id));
                $hashedFeedID = base64_encode(Protector::encrypt($feedID));
                return admin_url('admin-ajax.php?action=wppayform_pdf_download_public&submission_id=' . $hashedEntryID . '&id=' . $hashedFeedID);
            }
        }
    }

    public function downloadPublic()
    {
        $feedId = intval(Protector::decrypt(base64_decode($_REQUEST['id'])));
        $submissionId = intval(Protector::decrypt(base64_decode($_REQUEST['submission_id'])));

        $_REQUEST['id'] = $feedId;
        $_REQUEST['submission_id'] = $submissionId;

        return $this->getPdf();
    }
}