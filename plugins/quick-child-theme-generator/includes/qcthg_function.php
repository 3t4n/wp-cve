<?php
if(!class_exists('QCTHG_Main'))
{
    class QCTHG_Main
    {
        /**
         * Added Initialization hooks
         */
        public function qcthgInitialization()
        {
            register_activation_hook(QCTHG_SLUG_PATH, array($this, 'qcthgActivate'));
            register_deactivation_hook(QCTHG_SLUG_PATH, array($this, 'qcthgDeactivate'));
            register_uninstall_hook(QCTHG_SLUG_PATH, array($this, 'qcthgUninstall'));

            add_action('admin_menu', array($this, 'qcthgPluginMenu'));
            add_action('admin_init', array($this, 'qcthgRegisterAssets'));
            add_action('admin_enqueue_scripts', array($this, 'qcthgEnqueueAssets'));
            add_action('admin_post_qcthg_create_theme', array($this, 'qcthgCreateChildTheme'));
            add_action('admin_post_qcthg_create_template', array($this, 'qcthgCreateBlankTemplate'));
            if (isset($_COOKIE['qcthg_adminNotices']) && $_COOKIE['qcthg_adminNotices'] !== 0) {
                ob_start();
                add_action( 'admin_notices', array('QCTHG_Helper', 'adminNoticeError'));
            }
            if (isset($_COOKIE['qcthg_adminNoticesSuccess']) && $_COOKIE['qcthg_adminNoticesSuccess'] !== 0) {
                ob_start();
                add_action( 'admin_notices', array('QCTHG_Helper', 'adminNoticeSuccess'));
            }
        }

        /**
         * Added plugin menu
         */
        public function qcthgPluginMenu()
        {
            add_menu_page(
                'Quick Child Theme Generator',
                'Quick Child Theme',
                'manage_options',
                'quick-child-theme-generator',
                array($this, 'qcthgMenuPage'),
                'dashicons-groups',
                60
            );

            add_submenu_page(
                'quick-child-theme-generator',
                'Create Template',
                'Template',
                'manage_options',
                'quick-blank-template-generator',
                array($this, 'qcthgSubMenuTemplatePage'),
                1
            );

        }

        /**
         * Register all CSS/JS dependencies
         */
        public function qcthgRegisterAssets()
        {
            wp_register_style('qcthg-jquery-ui-css', QCTHG_URL.'assets/css/jquery-ui.css');
            wp_register_style('qcthg-custom-css', QCTHG_URL.'assets/css/qcthg_custom_style.css');

            wp_register_script('qcthg-custom-js', QCTHG_URL.'assets/js/qcthg_custom_script.js', array('jquery'), true, true);
        }

        /**
         * Enqueue all CSS/JS dependencies
         * @param  [string] $hook
         */
        public function qcthgEnqueueAssets($hook)
        {
            if($hook === 'toplevel_page_quick-child-theme-generator'
            || $hook === 'quick-child-theme_page_quick-blank-template-generator'
            || isset($_GET['page']) && $_GET['page'] === 'quick-child-theme-generator'
            || isset($_GET['page']) && $_GET['page'] === 'quick-blank-template-generator') {
                wp_enqueue_script('jquery');
                wp_enqueue_script('jquery-ui-core');
                wp_enqueue_script('jquery-ui-tabs');
                wp_enqueue_script('qcthg-custom-js');

                wp_enqueue_style('qcthg-jquery-ui-css');
                wp_enqueue_style('qcthg-custom-css');
            }
        }

        /**
         * Added main plugin page
         */
        public function qcthgMenuPage()
        {
            require_once(QCTHG_PATH.'/views/qcthg_menu.php');
        }

        /**
         * Added sub menu plugin page
         */
        public function qcthgSubMenuTemplatePage()
        {
            require_once(QCTHG_PATH.'/views/qcthg_template.php');
        }

        /**
         * Validate and create child theme
         */
        public function qcthgCreateChildTheme()
        {
            if(!empty($_POST)) {
				if(!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'qcthg_create_theme')) {
                    wp_die('Ooops!!! you are not auththorized user.');
				} else {
                    if(empty(trim($_POST['theme_template'])) || empty(trim($_POST['child_theme_name']))) {
                        $cookieValue = '<b>ERROR: </b> Missing parent or child theme name';
                        QCTHG_Helper::qcthgSetCookies('', $cookieValue);
                        wp_redirect(wp_get_referer());
                        exit;
                    }

                    $postedData = array();
                    $postedData['parent_theme_template'] = sanitize_text_field($_POST['theme_template']);
                    $postedData['theme_name'] = sanitize_text_field($_POST['child_theme_name']);
                    $postedData['theme_url'] = empty(trim($_POST['child_theme_url'])) ? site_url() : esc_url($_POST['child_theme_url']);
                    $postedData['theme_desc'] = empty(trim($_POST['child_theme_desc'])) ? 'Write here theme description.' : sanitize_text_field($_POST['child_theme_desc']);
                    $postedData['theme_author'] = empty(trim($_POST['child_theme_author'])) ? wp_get_current_user()->user_login : sanitize_text_field($_POST['child_theme_author']);
                    $postedData['author_url'] = empty(trim($_POST['child_theme_author_url'])) ? site_url() : esc_url($_POST['child_theme_author_url']);
                    $postedData['theme_version'] = empty(trim($_POST['child_theme_version'])) ? '1.0.0' : sanitize_text_field($_POST['child_theme_version']);

                    $themeGenOutput = $this->qcthgGenerateChildAssets($postedData);

                    if($themeGenOutput['screenshot'] !== 'success') {
                        $cookieValue = '<b>WARNING: </b> parent theme screenshot is not fetching in the child theme. But your child theme will be working fine so no worry about it.';
                        QCTHG_Helper::qcthgSetCookies('', $cookieValue);
                        wp_redirect(wp_get_referer());
                        exit;
                    }

                    wp_redirect(QCTHG_ADMIN_URL.'themes.php');
                    exit;
				}
			}
        }

        /**
         * Validate/Refined child theme data
         * @param  [array] $data
         */
        public function qcthgGenerateChildAssets($data)
        {
            $themeRoot = get_theme_root();
            $themeTitle = QCTHG_Helper::qcthgSanitizeText($data['theme_name']);
            $refThemeTitle = QCTHG_Helper::qcthgRefSanitizeText($themeTitle);
            $newChildThemePath = $themeRoot.'/'.$refThemeTitle;
            $parntThemePath = $themeRoot.'/'.$data['parent_theme_template'];

            if(empty($themeTitle)) {
                $cookieValue = '<b>ERROR: </b>Special characters are not allowed in the theme name! please try again.';
                QCTHG_Helper::qcthgSetCookies('', $cookieValue);
                wp_redirect(wp_get_referer());
                exit;
            }

            if(file_exists($newChildThemePath)) {
                $cookieValue = '<b>ERROR: </b>The child theme name <b> ( '.$refThemeTitle.' ) </b> directory already exists! Please check your themes directory <b>( '.$themeRoot.' )</b> or choose another name.';
                QCTHG_Helper::qcthgSetCookies('', $cookieValue);
                wp_redirect(wp_get_referer());
                exit;
            }

            $chkTheme = QCTHG_Helper::qcthgChkThemeName($themeTitle);

            if($chkTheme) {
                $cookieValue = '<b>ERROR: </b>The theme name <b>( '.$themeTitle.' )</b> already exists!';
                QCTHG_Helper::qcthgSetCookies('', $cookieValue);
                wp_redirect(wp_get_referer());
                exit;
            }

            if(wp_mkdir_p($newChildThemePath)) {

                $result['stylesheet'] = $this->qcthgCreateStylesheet($newChildThemePath, $data, $themeTitle);
                $result['functions_php'] = $this->qcthgCreateFunctionPhp($newChildThemePath, $refThemeTitle);
                $result['screenshot'] = $this->qcthgCopyScreenshot($parntThemePath, $newChildThemePath);
                return $result;

            } else {
                $cookieValue = '<b>ERROR: </b>Permissions denied!' .PHP_EOL. 'Unable to create '.$themeTitle.' directory. Please set the write permissions for following path: ' .PHP_EOL. $themeRoot;
                QCTHG_Helper::qcthgSetCookies('', $cookieValue);
                wp_redirect(wp_get_referer());
                exit;
            }
        }

        /**
         * Create style css file for child theme
         * @param  [string] $newChildThemePath
         * @param  [array] $data
         * @param  [string] $themeTitle
         * @return [string]
         */
        public function qcthgCreateStylesheet($newChildThemePath, $data, $themeTitle)
        {
            $cssContent = '/*' . PHP_EOL;
            $cssContent .= 'Theme Name:  ' . $themeTitle . PHP_EOL;
            $cssContent .= 'Theme URL:   ' . $data['theme_url'] . PHP_EOL;
            $cssContent .= 'Description: ' . $data['theme_desc'] . PHP_EOL;
            $cssContent .= 'Author:      ' . $data['theme_author'] . PHP_EOL;
            $cssContent .= 'Author URL:  ' . $data['author_url'] . PHP_EOL;
            $cssContent .= 'Template:    ' . $data['parent_theme_template'] . PHP_EOL;
            $cssContent .= 'Version:     ' . $data['theme_version'] . PHP_EOL;
            $cssContent .= '*/';

            if(file_put_contents($newChildThemePath.'/style.css', $cssContent, LOCK_EX)) {
                return 'success';
            } else {
                $cookieValue = '<b>ERROR: </b>Permissions denied!' .PHP_EOL. 'Unable to create <b>style.css</b>. Please set the write permissions for following path: ' .PHP_EOL. $newChildThemePath;
                QCTHG_Helper::qcthgSetCookies('', $cookieValue);
                wp_redirect(wp_get_referer());
                exit;
            }
        }

        /**
         * Create function php file for child theme
         * @param  [string] $newChildThemePath
         * @param  [string] $refThemeTitle
         * @return [string]
         */
        public function qcthgCreateFunctionPhp($newChildThemePath, $refThemeTitle)
        {
            $phpContent = "<?php" . PHP_EOL;
            $phpContent .= "add_action( 'wp_enqueue_scripts', '{$refThemeTitle}_enqueue_child_theme_styles', PHP_INT_MAX);" . PHP_EOL . PHP_EOL;
            $phpContent .= "function {$refThemeTitle}_enqueue_child_theme_styles() {" . PHP_EOL;
            $phpContent .= "    wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );" . PHP_EOL;
            $phpContent .= "    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri().'/style.css', array('parent-style') );" . PHP_EOL;
            $phpContent .= "}" . PHP_EOL . PHP_EOL;
            $phpContent .= "?>";

            if(file_put_contents($newChildThemePath.'/functions.php', $phpContent, LOCK_EX)) {
                return 'success';
            } else {
                $cookieValue = '<b>ERROR: </b>Permissions denied!' .PHP_EOL. 'Unable to create <b>function.php</b>. Please set the write permissions for following path: ' .PHP_EOL. $newChildThemePath;
                QCTHG_Helper::qcthgSetCookies('', $cookieValue);
                wp_redirect(wp_get_referer());
                exit;
            }
        }

        /**
         * Copy and paste parent theme screenshot to child theme
         * @param  [string] $parntThemePath
         * @param  [string] $newChildThemePath
         * @return [string, bool]
         */
        public function qcthgCopyScreenshot($parntThemePath, $newChildThemePath)
        {
            $screenshots = glob($parntThemePath.'/screenshot.{png,jpg,jpeg,gif}', GLOB_BRACE);

            if(!empty($screenshots[0])) {
                $screenshotName = basename($screenshots[0]);
                copy($parntThemePath.'/'.$screenshotName, $newChildThemePath.'/'.$screenshotName);
                return 'success';
            }
            return false;
        }

        /**
         * Plugin activate action
         */
        public function qcthgActivate()
        {
            $data = array(
                'siteurl' => get_bloginfo('url'),
                'email' => get_bloginfo('admin_email'),
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            );

            QCTHG_Helper::qcthgLogCall($data);
        }

        /**
         * Plugin deactivate action
         */
        public function qcthgDeactivate()
        {
            $data = array(
                'siteurl' => get_bloginfo('url'),
                'email' => get_bloginfo('admin_email'),
                'status' => 2,
                'updated_at' => date('Y-m-d H:i:s')
            );

            QCTHG_Helper::qcthgLogCall($data);
        }

        /**
         * Plugin uninstall action
         */
        public function qcthgUninstall()
        {
            $data = array(
                'siteurl' => get_bloginfo('url'),
                'email' => get_bloginfo('admin_email'),
                'status' => 3,
                'updated_at' => date('Y-m-d H:i:s')
            );

            QCTHG_Helper::qcthgLogCall($data);
        }

        /**
         * Validate and create custom template
         */
        public function qcthgCreateBlankTemplate()
        {
            if(!empty($_POST)) {
				if(!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'qcthg_create_template')) {
                    wp_die('Ooops!!! you are not auththorized user.');
				} else {
                    if(empty(trim($_POST['tmp_name']))) {
                        $cookieValue = '<b>ERROR: </b> Missing template name';
                        QCTHG_Helper::qcthgSetCookies('', $cookieValue);
                        wp_redirect(wp_get_referer());
                        exit;
                    }

                    $postedData = array();
                    $postedData['template_name'] = sanitize_text_field($_POST['tmp_name']);
                    $postedData['header_name'] = empty(trim($_POST['tmp_header_name'])) ? '' : sanitize_text_field($_POST['tmp_header_name']);
                    $postedData['footer_name'] = empty(trim($_POST['tmp_footer_name'])) ? '' : sanitize_text_field($_POST['tmp_footer_name']);

                    $this->qcthgGenerateTemplateAssets($postedData);

                    $cookieValue = '<b>SUCCESS: </b> Template successfully created';
                    QCTHG_Helper::qcthgSetCookies('qcthg_adminNoticesSuccess', $cookieValue);
                    wp_redirect(wp_get_referer());
                    exit;
				}
			}
        }

        /**
         * Validate/Refined custom template data
         * @param  [array] $data
         */
        public function qcthgGenerateTemplateAssets($data)
        {
            $themeRoot = get_theme_root();
            $childThemeDir = get_stylesheet_directory();
            $templateName = QCTHG_Helper::qcthgSanitizeText($data['template_name']);
            $refTemplateName = QCTHG_Helper::qcthgRefSanitizeText($templateName);
            $refTemplateNameLowerCase = strtolower($refTemplateName);
            $getActiveTheme = wp_get_theme();
            $parentThemeDirName = $getActiveTheme["Template"];
            $parentThemeDirPath = $themeRoot.'/'.$parentThemeDirName;
            $scanPath = scandir($parentThemeDirPath, 1);
            $scanChildPath = scandir($childThemeDir, 1);

            if(empty($templateName)) {
                $cookieValue = '<b>ERROR: </b>Special characters are not allowed in the template name! please try again.';
                QCTHG_Helper::qcthgSetCookies('', $cookieValue);
                wp_redirect(wp_get_referer());
                exit;
            }

            $chkTemplate = QCTHG_Helper::qcthgChkTemplateName($refTemplateNameLowerCase);

            if($chkTemplate) {
                $cookieValue = '<b>ERROR: </b>The template name <b>( template-'.$refTemplateNameLowerCase.'.php )</b> already exists in theme root directory!';
                QCTHG_Helper::qcthgSetCookies('', $cookieValue);
                wp_redirect(wp_get_referer());
                exit;
            }

            if(!empty($data['header_name'])) {
                $headerFileNm = $data['header_name'].'.php';
                $findHeader = QCTHG_Helper::findHeaderFooter($headerFileNm, $scanChildPath);

                if(!$findHeader) {
                    $cookieValue = '<b>ERROR: </b>Unable to find theme header! Please enter header name correctly';
                    QCTHG_Helper::qcthgSetCookies('', $cookieValue);
                    wp_redirect(wp_get_referer());
                    exit;
                }

            } else {
                $headerFileNm = 'header.php';
                /* check default header exists in child/parent theme */
                if(!QCTHG_Helper::findHeaderFooter('header.php', $scanPath) && !QCTHG_Helper::findHeaderFooter('header.php', $scanChildPath)) {
                    $cookieValue = '<b>ERROR: </b>Unable to find theme header';
                    QCTHG_Helper::qcthgSetCookies('', $cookieValue);
                    wp_redirect(wp_get_referer());
                    exit;
                }
            }

            if(!empty($data['footer_name'])) {
                $footerFileNm = $data['footer_name'].'.php';
                $findFooter = QCTHG_Helper::findHeaderFooter($footerFileNm, $scanChildPath);

                if(!$findFooter) {
                    $cookieValue = '<b>ERROR: </b>Unable to find theme footer! Please enter footer name correctly';
                    QCTHG_Helper::qcthgSetCookies('', $cookieValue);
                    wp_redirect(wp_get_referer());
                    exit;
                }

            } else {
                $footerFileNm = 'footer.php';
                /* check default footer exists in child/parent theme */
                if(!QCTHG_Helper::findHeaderFooter('footer.php', $scanChildPath) && !QCTHG_Helper::findHeaderFooter('footer.php', $scanPath)) {
                    $cookieValue = '<b>ERROR: </b>Unable to find theme footer';
                    QCTHG_Helper::qcthgSetCookies('', $cookieValue);
                    wp_redirect(wp_get_referer());
                    exit;
                }
            }

            $result['template'] = $this->qcthgCreateBlankTemplatePhp($childThemeDir, $refTemplateNameLowerCase, $templateName, $headerFileNm, $footerFileNm);
            return $result;
        }

        /**
         * Create blank template php file (with header/footer)
         * @param  [string] $childThemeDir
         * @param  [string] $refTemplateNameLowerCase
         * @param  [string] $templateName
         * @param  [string] $headerFileNm
         * @param  [string] $footerFileNm
         * @return [string]
         */
        public function qcthgCreateBlankTemplatePhp($childThemeDir, $refTemplateNameLowerCase, $templateName, $headerFileNm, $footerFileNm)
        {
            $headerFileNmEx = explode('.', $headerFileNm);
            $footerFileNmEx = explode('.', $footerFileNm);
            $headerNm = 'get_header();';
            $footerNm = 'get_footer();';

            if($headerFileNmEx[0] != 'header') {
                $headerExplode = explode('-', $headerFileNmEx[0]);
                $hedrNm = '';
                foreach ($headerExplode as $k => $v) {
                    if($k > 0) { $hedrNm .= $v; }
                }
                $headerNm = 'get_header("'.$hedrNm.'");';
            }

            if($footerFileNmEx[0] != 'footer') {
                $footerExplode = explode('-', $footerFileNmEx[0]);
                $fotrNm = '';
                foreach ($footerExplode as $k => $v) {
                    if($k > 0) { $fotrNm .= $v; }
                }
                $footerNm = 'get_footer("'.$fotrNm.'");';
            }

            $phpContent = "<?php" . PHP_EOL;
            $phpContent .= '/*' . PHP_EOL;
            $phpContent .= 'Template Name: ' . $templateName . PHP_EOL;
            $phpContent .= '*/' . PHP_EOL;
            $phpContent .= "{$headerNm}" . PHP_EOL;
            $phpContent .= "?>" . PHP_EOL . PHP_EOL;
            $phpContent .= "<!-- Place your custom page code here -->" . PHP_EOL . PHP_EOL;
            $phpContent .= "<?php" . PHP_EOL;
            $phpContent .= "{$footerNm}" . PHP_EOL;
            $phpContent .= "?>";

            if(file_put_contents($childThemeDir.'/template-'.$refTemplateNameLowerCase.'.php', $phpContent, LOCK_EX)) {
                return 'success';
            } else {
                $cookieValue = '<b>ERROR: </b>Permissions denied!' .PHP_EOL. 'Unable to create <b>template file</b>. Please set the write permissions for following path: ' .PHP_EOL. $childThemeDir;
                QCTHG_Helper::qcthgSetCookies('', $cookieValue);
                wp_redirect(wp_get_referer());
                exit;
            }
        }

    }

    $objMain = new QCTHG_Main();
}
