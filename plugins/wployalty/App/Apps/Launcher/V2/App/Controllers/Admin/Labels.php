<?php
/**
 * @author      Wployalty (Ilaiyaraja)
 * @license     http://www.gnu.org/licenses/gpl-3.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wll\V2\App\Controllers\Admin;

use Wll\V2\App\Controllers\Base;
use Wll\V2\App\Controllers\Member;
use Wll\V2\App\Helpers\Settings;
use Wlr\App\Helpers\EarnCampaign;
use Wlr\App\Helpers\Woocommerce;

defined('ABSPATH') or die();

class Labels extends Base
{
    /**
     * Getting local data
     * @return void
     */
    public function getLauncherLocalData()
    {
        $response = array(
            'success' => false,
            'data' => array()
        );
        if (!$this->isLauncherSecurityValid('local_data')) {
            $response['data']['message'] = __('Security check failed', 'wp-loyalty-rules');
            wp_send_json($response);
        }
        $is_pro = EarnCampaign::getInstance()->isPro();
        $short_code_lists = self::$settings->getShortCodeList();
        $short_codes = array();
        foreach ($short_code_lists as $key => $short_code_list) {
            $short_codes[] = array(
                "value" => $short_code_list,
                "label" => $key,
            );
        }
        $localize = array(
            'is_pro' => $is_pro,
            'common' => array(
                'back_to_apps_url' => admin_url('admin.php?' . http_build_query(array('page' => WLR_PLUGIN_SLUG))) . '#/apps',
            ),
            'plugin_name' => WLL_PLUGIN_NAME,
            'version' => 'v' . WLL_PLUGIN_VERSION,
            'short_code_lists' => $short_codes,
            'render_admin_page_nonce' => Woocommerce::create_nonce('render_page_nonce'),
            'common_nonce' => Woocommerce::create_nonce('common_nonce'),
            'design_nonce' => Woocommerce::create_nonce('wll_design_settings'),
            'content_nonce' => Woocommerce::create_nonce('wll_content_settings'),
            'launcher_nonce' => Woocommerce::create_nonce('wll_launcher_settings'),
            'settings_nonce' => Woocommerce::create_nonce('wll_launcher_settings'),
        );
        $localize = apply_filters('wll_launcher_local_data', $localize);
        $response['success'] = true;
        $response['data'] = $localize;
        wp_send_json($response);
    }

    /**
     * Getting app labels
     * @return void
     */
    public function getLauncherLabels()
    {
        $response = array(
            'success' => false,
            'data' => array()
        );
        if (!$this->isLauncherSecurityValid('common_nonce')) {
            $response['data']['message'] = __('Security check failed', 'wp-loyalty-rules');
            wp_send_json($response);
        }
        $color_list = array(
            array(
                'label' => __('Primary', 'wp-loyalty-rules'),
                'value' => 'primary'
            ),
            array(
                'label' => __('Secondary', 'wp-loyalty-rules'),
                'value' => 'secondary'
            )
        );
        $text_color_list = array(
            array(
                'label' => __('White', 'wp-loyalty-rules'),
                'value' => 'white'
            ),
            array(
                'label' => __('Black', 'wp-loyalty-rules'),
                'value' => 'black'
            )
        );
        $label_data = array(
            'common' => $this->getCommonLabels(),
            'color_list' => $color_list,
            'text_color_list' => $text_color_list,
            'short_codes' => __('Shortcodes', 'wp-loyalty-rules'),
            'design' => $this->getLauncherDesignLabels(),
            'guest' => $this->getLauncherGuestLabels(),
            'member' => $this->getLauncherMemberLabels(),
            'popup_button' => $this->getLauncherContentLabels(),
            'shortcodes' => $this->getShortCodeWithLabels(),
            'social_share_list' => $this->getSocialShareList(),
        );
        $response['success'] = true;
        $response['data'] = $label_data;
        wp_send_json($response);
    }

    protected function getSocialShareList()
    {
        $member = new Member();
        $user = $member->getUserDetails();
        $base_controller = new Base();
        $base_helper = new \Wlr\App\Helpers\Base();
        $referral_url = $base_helper->getReferralUrl('dummy');
        $social_share_list = !empty($referral_url) && !empty($user) && is_object($user) && (isset($user->user_email) && !empty($user->user_email)) ? $base_controller->getSocialIconList($user->user_email, $referral_url) : self::$settings->getDummySocialShareList();
        return array(
            'content' => array(
                'member' => array(
                    'referrals' => array(
                        'social_share_list' => !empty($social_share_list) ? $social_share_list : array(),
                    ),
                )
            )
        );
    }

    protected function getShortCodeWithLabels()
    {
        $short_code_list = Settings::shortCodesWithLabels();
        $guest_short_code = $member_short_code = $referral_short_code = array();
        if (isset($short_code_list['common']) && !empty($short_code_list['common'])
            && isset($short_code_list['guest']) && !empty($short_code_list['guest'])) {
            $guest_short_code = array_merge($short_code_list['common'], $short_code_list['guest']);
        }
        if (isset($short_code_list['common']) && !empty($short_code_list['common'])
            && isset($short_code_list['member']) && !empty($short_code_list['member'])) {
            $member_short_code = array_merge($short_code_list['common'], $short_code_list['member']);
        }
        if (isset($short_code_list['common']) && !empty($short_code_list['common'])
            && isset($short_code_list['referral']) && !empty($short_code_list['referral'])) {
            $referral_short_code = array_merge($short_code_list['common'], $short_code_list['referral']);
        }
        return array(
            'content' => array(
                'guest' => array(
                    'welcome' => array(
                        'shortcodes' => $guest_short_code,
                    ),
                ),
                'member' => array(
                    'banner' => array(
                        'shortcodes' => $member_short_code,
                    ),
                    'referrals' => array(
                        'shortcodes' => $referral_short_code,
                    ),
                ),
            ),
        );
    }

    protected function getCommonLabels()
    {
        return array(
            'plugin_name' => WLL_PLUGIN_NAME,
            'version' => 'v' . WLL_PLUGIN_VERSION,
            'save' => __('Save Changes', 'wp-loyalty-rules'),
            'upgrade_text' => __('Upgrade to Pro', 'wp-loyalty-rules'),
            'buy_pro_url' => 'https://wployalty.net/pricing/?utm_campaign=wployalty-link&utm_medium=pro_url&utm_source=pricing',
            'launcher_power_by_url' => 'https://wployalty.net/?utm_campaign=wployalty-link&utm_medium=launcher&utm_source=powered_by',
            'reset' => __('Reset', 'wp-loyalty-rules'),
            'back' => __('Back', 'wp-loyalty-rules'),
            'back_to_apps' => __('Back to WPLoyalty', 'wp-loyalty-rules'),
            'edit_styles' => __('Edit Styles', 'wp-loyalty-rules'),
            'design' => __('Design', 'wp-loyalty-rules'),
            'content' => __('Content', 'wp-loyalty-rules'),
            'launcher' => __('Launcher', 'wp-loyalty-rules'),
            'default' => __('Default', 'wp-loyalty-rules'),
            'upload_icon' => __('Upload icon', 'wp-loyalty-rules'),
            'icon' => __('Icon', 'wp-loyalty-rules'),
            'icon_buttons' => array(
                'restore' => __('Restore Default', 'wp-loyalty-rules'),
                'browse' => __('Browse Image', 'wp-loyalty-rules'),
            ),
            'background' => __('Background', 'wp-loyalty-rules'),
            'text' => __('Text', 'wp-loyalty-rules'),
            'texts' => __('Texts', 'wp-loyalty-rules'),
            'link' => __('Link', 'wp-loyalty-rules'),
            'color' => __('Color', 'wp-loyalty-rules'),
            'colors' => __('Colors', 'wp-loyalty-rules'),
            'buttons' => __('Buttons', 'wp-loyalty-rules'),
            'title' => __('Title', 'wp-loyalty-rules'),
            'description' => __('Description', 'wp-loyalty-rules'),
            'visibility' => __('Visibility', 'wp-loyalty-rules'),
            'show' => __('Show', 'wp-loyalty-rules'),
            'none' => __('Do not show', 'wp-loyalty-rules'),
            'restore_default' => __('Restore Default', 'wp-loyalty-rules'),
            'browse_image' => __('Browse Image', 'wp-loyalty-rules'),
            'left' => __('Left', 'wp-loyalty-rules'),
            'right' => __('Right', 'wp-loyalty-rules'),
            'mobile_only' => __('Mobile Only', 'wp-loyalty-rules'),
            'desktop_only' => __('Desktop Only', 'wp-loyalty-rules'),
            'mobile_and_desktop' => __('Mobile and Desktop', 'wp-loyalty-rules'),
            'display_none' => __('Do not show', 'wp-loyalty-rules'),
            'image_description' => __('Choose an image to preview.', 'wp-loyalty-rules'),
            'logo_image' => __('Your logo', 'wp-loyalty-rules'),
            'font_family' => __('Font Family', 'wp-loyalty-rules'),
            'white' => __('White', 'wp-loyalty-rules'),
            'black' => __('Black', 'wp-loyalty-rules'),
            'primary' => __('Primary', 'wp-loyalty-rules'),
            'secondary' => __('Secondary', 'wp-loyalty-rules'),
            'back_to_loyalty' => __('Back to WPLoyalty', 'wp-loyalty-rules'),
            'reset_message' => __('Reset Successfully', 'wp-loyalty-rules'),
            'theme_color' => __('Color', 'wp-loyalty-rules'),
            'no_result_found' => __('No results found!', 'wp-loyalty-rules'),
            'toggle' => array(
                'activate' => __('click to activate', 'wp-loyalty-rules'),
                'deactivate' => __('click to de-activate', 'wp-loyalty-rules'),
            ),
            'visibility_list' => array(
                array(
                    'label' => __('Show', 'wp-loyalty-rules'),
                    'value' => 'show'
                ),
                array(
                    'label' => __('None', 'wp-loyalty-rules'),
                    'value' => 'none'
                ),
            ),
            'banner' => __('Banner', 'wp-loyalty-rules'),
            'enabled' => __('Enabled', 'wp-loyalty-rules'),
            'disabled' => __('Disabled', 'wp-loyalty-rules'),
            'welcome' => __('Welcome', 'wp-loyalty-rules'),
            'referrals' => __('Referrals', 'wp-loyalty-rules'),
            'points' => __('Earn & Redeem', 'wp-loyalty-rules'),
            'earn' => __('Earn', 'wp-loyalty-rules'),
            'redeem' => __('Redeem', 'wp-loyalty-rules'),
            'level_name' => __('Level A', 'wp-loyalty-rules'),
            'ok_text' => __('Yes, Reset', 'wp-loyalty-rules'),
            'cancel_text' => __('Cancel', 'wp-loyalty-rules'),
            'confirm_title' => __('Reset Settings?', 'wp-loyalty-rules'),
            'confirm_description' => __('Are you sure want to reset this settings?', 'wp-loyalty-rules'),
            'dummy_preview_message' => __('This preview uses dummy records.', 'wp-loyalty-rules'),
            'powered_by' => __('Powered by', 'wp-loyalty-rules'),
            'wpl_loyalty_text' => __('WPLoyalty', 'wp-loyalty-rules'),
            'rewards_title' => __('My Rewards', 'wp-loyalty-rules'),
            'coupons_title' => __('My Coupons', 'wp-loyalty-rules'),
            'apply_button_text' => __('Apply', 'wp-loyalty-rules'),
            'show_launcher_condition_text' => __('Show widget on specific locations based on conditions', 'wp-loyalty-rules'),
            'add_condition_text' => __('Add Condition', 'wp-loyalty-rules'),
            'url_text' => __("URL's", 'wp-loyalty-rules'),
            'home_text' => __("Home page", 'wp-loyalty-rules'),
            'contains_text' => __("Contains", 'wp-loyalty-rules'),
            'do_not_contains_text' => __("Do not contains", 'wp-loyalty-rules'),
            'match_all' => __("Match All", 'wp-loyalty-rules'),
            'match_any' => __("Match Any", 'wp-loyalty-rules'),
            'conditions_text' => __("Conditions", 'wp-loyalty-rules'),
            'delete_text' => __("delete", 'wp-loyalty-rules'),

            'rewards_tab' => array(
                'reward_opportunity' => __('Reward Opportunities', 'wp-loyalty-rules'),
                'my_rewards' => __('My Rewards', 'wp-loyalty-rules'),
            ),
        );
    }

    protected function getLauncherDesignLabels()
    {
        return array(
            'logo' => array(
                'title' => __('Logo', 'wp-loyalty-rules'),
                'visibility' => __('VISIBILITY', 'wp-loyalty-rules'),
                'image' => array(
                    'description' => __('Choose your logo from the media library', 'wp-loyalty-rules'),
                ),
            ),
            'colors' => array(
                'title' => __('Colors', 'wp-loyalty-rules'),
                'theme_title' => __('THEME', 'wp-loyalty-rules'),
                'theme_color' => __('Theme Color', 'wp-loyalty-rules'),
                'banner' => __('BANNER', 'wp-loyalty-rules'),
                'buttons' => __('BUTTONS', 'wp-loyalty-rules'),
                'links' => __('LINKS', 'wp-loyalty-rules'),
                'icons' => __('ICONS', 'wp-loyalty-rules'),
                'theme' => array(
                    'primary' => __('Primary', 'wp-loyalty-rules'),
                    'secondary' => __('Secondary', 'wp-loyalty-rules'),
                ),
            ),
            'placement' => array(
                'title' => __('Placement', 'wp-loyalty-rules'),
                'position' => array(
                    'title' => __('Position', 'wp-loyalty-rules'),
                    'options' => array(
                        array(
                            'label' => __('Left', 'wp-loyalty-rules'),
                            'value' => 'left',
                        ),
                        array(
                            'label' => __('Right', 'wp-loyalty-rules'),
                            'value' => 'right',
                        ),
                    ),
                ),
                'spacing' => array(
                    'title' => __('Spacing', 'wp-loyalty-rules'),
                    'description' => __('The position of the panel and launcher relative to the customer\'s window. Only applies to desktop mode.', 'wp-loyalty-rules'),
                    'side_space' => __('Side spacing', 'wp-loyalty-rules'),
                    'bottom_space' => __('Bottom spacing', 'wp-loyalty-rules'),
                ),
            ),
            'branding' => __('Branding', 'wp-loyalty-rules'),
        );
    }

    protected function getLauncherGuestLabels()
    {
        return array(
            'title' => __('Guest', 'wp-loyalty-rules'),
            'welcome' => array(
                'texts' => array(
                    'have_account' => __('Have an account?', 'wp-loyalty-rules'),
                    'sign_in' => __('Sign in', 'wp-loyalty-rules'),
                ),
                'buttons' => array(
                    'create_account' => __('Create account', 'wp-loyalty-rules'),
                ),
            ),
        );
    }

    protected function getLauncherMemberLabels()
    {
        return array(
            'title' => __('Member', 'wp-loyalty-rules'),
            'banner' => array(
                'levels' => __('LEVELS', 'wp-loyalty-rules'),
                'points' => __('Points', 'wp-loyalty-rules'),
                'point_description' => __('Point description', 'wp-loyalty-rules'),
                'shortcodes' => __('Shortcodes', 'wp-loyalty-rules'),
            ),
        );
    }

    protected function getLauncherContentLabels()
    {
        return array(
            'title' => __('Launcher', 'wp-loyalty-rules'),
            'edit_launcher' => __('Edit Launcher', 'wp-loyalty-rules'),
            'appearance_text' => __('APPEARANCE', 'wp-loyalty-rules'),
            'icon_with_text' => __('Icon with text', 'wp-loyalty-rules'),
            'icon_only' => __('Icon only', 'wp-loyalty-rules'),
            'text_only' => __('Text only', 'wp-loyalty-rules'),
            'icon_only_on_mobile' => __('Icon only on mobile', 'wp-loyalty-rules'),

            'appearance' => array(
                'visibility_list' => array(
                    array(
                        'label' => __('Icon with text', 'wp-loyalty-rules'),
                        'value' => 'icon_with_text'
                    ),
                    array(
                        'label' => __('Icon only', 'wp-loyalty-rules'),
                        'value' => 'icon_only'
                    ),
                    array(
                        'label' => __('Text only', 'wp-loyalty-rules'),
                        'value' => 'text_only'
                    ),
                ),
            ),
            'view_option' => __('View option', 'wp-loyalty-rules'),
            'view_options' => array(
                array(
                    'label' => __('Both mobile and desktop', 'wp-loyalty-rules'),
                    'value' => 'both_mobile_desktop'
                ),
                array(
                    'label' => __('Mobile only', 'wp-loyalty-rules'),
                    'value' => 'mobile_only'
                ),
                array(
                    'label' => __('Desktop only', 'wp-loyalty-rules'),
                    'value' => 'desktop_only'
                ),
            ),
            'font' => __('Font', 'wp-loyalty-rules'),
            'font_families' => $this->getFontListLabels(),
        );
    }

    protected function getFontListLabels()
    {
        return apply_filters('wll_font_list', array(
            array(
                'label' => __('Inherit', 'wp-loyalty-rules'),
                'value' => 'inherit'
            ),
            array(
                'label' => __('Helvetica Neue', 'wp-loyalty-rules'),
                'value' => 'helvetica'
            ),
            array(
                'label' => __('Arial', 'wp-loyalty-rules'),
                'value' => 'arial'
            ),
            array(
                'label' => __('Courier New', 'wp-loyalty-rules'),
                'value' => 'courier'
            ),
            array(
                'label' => __('Impact', 'wp-loyalty-rules'),
                'value' => 'impact'
            ),
        ));
    }
}