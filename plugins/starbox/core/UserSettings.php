<?php defined('ABSPATH') || die('Cheatin\' uh?'); ?>
<?php

/**
 * Affiliate settings
 */
class ABH_Core_UserSettings extends ABH_Classes_BlockController {

    public $user;
    public $author = array();
    public $themes = array();

    public function init($user = null) {
        $this->user = $user;

        if (isset($this->user->ID))
            $this->author = ABH_Classes_Tools::getOption('abh_author' . $this->user->ID);

        $default = array(
            'abh_use' => 1,
            'abh_nofollow_social' => 1,
            'abh_noopener_social' => 0,
            'abh_noreferrer_social' => 0,
            // --
            'abh_title' => "",
            'abh_company' => "",
            'abh_company_url' => "",
            'abh_extra_description' => "",
            // --
            'abh_socialtext' => "",
            'abh_twitter' => "",
            'abh_facebook' => "",
            'abh_google' => "",
            'abh_linkedin' => "",
            'abh_instagram' => "",
            'abh_flickr' => "",
            'abh_pinterest' => "",
            'abh_tumblr' => "",
            'abh_youtube' => "",
            'abh_vimeo' => "",
            'abh_klout' => "",
            'abh_gravatar' => "",
            'abh_theme' => "default",
            'abh_position' => "default",
        );

        if (!isset($this->author) || empty($this->author)) {
            $this->author = $default;
        }else{
            $this->author = @array_merge($default,$this->author);

        }

        $this->themes = @array_merge(array('default'), ABH_Classes_Tools::getOption('abh_themes'));

        parent::init();
    }

    public function action() {
        switch (ABH_CLasses_Tools::getValue('action')) {
            //Update the user data in User Profile
            case 'update':
            case 'createuser':
                $user_id = (int)ABH_CLasses_Tools::getValue('user_id');

                if(!current_user_can('edit_users') && get_current_user_id() <> $user_id){
                    return;
                }

                //Get the default settings
                $settings = ABH_Classes_Tools::getOption('abh_author' . $user_id);

                $settings['abh_use'] = (int)ABH_CLasses_Tools::getValue('abh_use');
                $settings['abh_nofollow_social'] = (int)ABH_CLasses_Tools::getValue('abh_nofollow_social');
                $settings['abh_noopener_social'] = (int)ABH_CLasses_Tools::getValue('abh_noopener_social');
                $settings['abh_noreferrer_social'] = (int)ABH_CLasses_Tools::getValue('abh_noreferrer_social');

                $settings['abh_title'] = ABH_CLasses_Tools::getValue('abh_title');
                $settings['abh_company'] = ABH_CLasses_Tools::getValue('abh_company');
                $settings['abh_company_url'] = ABH_CLasses_Tools::getValue('abh_company_url');
                if (isset($_POST['abh_extra_description'])) {
                    $allowed_html = array(
                        'a' => array('href' => array(), 'target' => array(), 'title' => array(), 'style' => array(), 'class' => array()),
                        'img' => array('src' => array(), 'alt' => array(), 'style' => array(), 'class' => array()),
                        'br' => array(), 'p' => array(),
                        'em' => array(), 'b' => array(), 'strong' => array(),
                        'h2' => array(), 'h3' => array(), 'h4' => array(), 'h5' => array(), 'h6' => array(),
                    );

                    $settings['abh_extra_description'] = wp_unslash(trim(wp_kses($_POST['abh_extra_description'], $allowed_html)));
                }
                // --

                $settings['abh_socialtext'] = ABH_CLasses_Tools::getValue('abh_socialtext');
                $settings['abh_twitter'] = ABH_CLasses_Tools::getValue('abh_twitter');
                $settings['abh_facebook'] = ABH_CLasses_Tools::getValue('abh_facebook');
                $settings['abh_google'] = ABH_CLasses_Tools::getValue('abh_google');
                $settings['abh_linkedin'] = ABH_CLasses_Tools::getValue('abh_linkedin');
                $settings['abh_instagram'] = ABH_CLasses_Tools::getValue('abh_instagram');
                $settings['abh_flickr'] = ABH_CLasses_Tools::getValue('abh_flickr');
                $settings['abh_pinterest'] = ABH_CLasses_Tools::getValue('abh_pinterest');
                $settings['abh_tumblr'] = ABH_CLasses_Tools::getValue('abh_tumblr');
                $settings['abh_youtube'] = ABH_CLasses_Tools::getValue('abh_youtube');
                $settings['abh_vimeo'] = ABH_CLasses_Tools::getValue('abh_vimeo');

                // --
                $settings['abh_theme'] = ABH_CLasses_Tools::getValue('abh_theme');
                $settings['abh_position'] = ABH_CLasses_Tools::getValue('abh_position');

                /* if there is an icon to upload */
                if (isset($_FILES['abh_gravatar']['name']) && $_FILES['abh_gravatar']['name'] <> '') {
                    $return = $this->model->addImage($_FILES['abh_gravatar']);
                    if ($return['name'] <> '')
                        $settings['abh_gravatar'] = $return['name'];
                    if ($return['message'] <> '')
                        define('ABH_MESSAGE_FAVICON', $return['message']);
                }
                ////////////////////////////////////

                if (ABH_CLasses_Tools::getValue('abh_resetgravatar') == 1) {
                    $settings['abh_gravatar'] = '';
                }

                ABH_Classes_Tools::saveOptions('abh_author' . $user_id, $settings);

                ABH_Classes_Tools::emptyCache();

                ABH_Classes_Tools::checkErrorSettings();
                /* Force call of error display */
                ABH_Classes_ObjController::getController('ABH_Classes_Error')->hookNotices();

                break;

            case 'abh_get_box':
                $user_id = (int)ABH_CLasses_Tools::getValue('user_id');
                $theme = ABH_CLasses_Tools::getValue('abh_theme');

                if(!current_user_can('edit_users') && get_current_user_id() <> $user_id){
                    return;
                }

                ABH_CLasses_Tools::setOption('abh_titlefontsize', ABH_CLasses_Tools::getValue('abh_titlefontsize', 'default'));
                ABH_CLasses_Tools::setOption('abh_descfontsize', ABH_CLasses_Tools::getValue('abh_descfontsize', 'default'));

                if ($theme == 'default')
                    $theme = ABH_Classes_Tools::getOption('abh_theme');

                $str = '';
                $str .= '<script type="text/javascript" src="' . _ABH_ALL_THEMES_URL_ . $theme . '/js/frontend.js?ver=' . ABH_VERSION . '"></script>';
                $str .= '<link rel="stylesheet"  href="' . _ABH_ALL_THEMES_URL_ . $theme . '/css/frontend.css?ver=' . ABH_VERSION . '" type="text/css" media="all" />';
                $str .= ABH_Classes_ObjController::getController('ABH_Controllers_Frontend')->showBox($user_id);

                ABH_Classes_Tools::setHeader('json');
                echo json_encode(array('box' => $str));
                exit();
        }
    }

}