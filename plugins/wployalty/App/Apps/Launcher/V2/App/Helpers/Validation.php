<?php
/**
 * @author      Wployalty (Ilaiyaraja)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wll\V2\App\Helpers;

use Valitron\Validator;

defined('ABSPATH') or die();

class Validation
{
    static function validateNotAllowedGuestShortcode($field, $value, array $params, array $fields)
    {
        $status = true;
        if (empty($value)) return true;
        $not_allowed_shortcodes = array(
            '{wlr_user_name}', '{wlr_user_points}', '{wlr_point_label}',
            '{wlr_referral_advocate_point}',
            '{wlr_referral_advocate_point_percentage}',
            '{wlr_referral_advocate_reward}',
            '{wlr_referral_friend_point}',
            '{wlr_referral_friend_point_percentage}',
            '{wlr_referral_friend_reward}');
        foreach ($not_allowed_shortcodes as $shortcode) {
            if ('' === $shortcode || false !== strpos($value, $shortcode)) {
                $status = false;
                break;
            }
        }
        return $status;
    }

    static function validateNotAllowedMemberShortcode($field, $value, array $params, array $fields)
    {
        $status = true;
        if (empty($value)) return true;
        $not_allowed_shortcodes = array('{wlr_signup_url}', '{wlr_signin_url}');
        foreach ($not_allowed_shortcodes as $shortcode) {
            if ('' === $shortcode || false !== strpos($value, $shortcode)) {
                $status = false;
                break;
            }
        }
        return $status;
    }

    public function validateInputAlpha($input)
    {
        return preg_replace('/[^A-Za-z0-9_\-]/', '', $input);
    }

    function validateDesignTab($post)
    {
        $validator = new Validator($post);
        $settings_labels = array(
            'design.logo.is_show', 'design.logo.image', 'design.colors.theme.primary',
            'design.colors.theme.secondary', 'design.colors.banner.background', 'design.colors.banner.text',
            'design.colors.buttons.background', 'design.colors.buttons.text', 'design.colors.links', 'design.colors.icons',
            'design.colors.launcher.background', 'design.colors.launcher.text', 'design.branding.is_show',
        );
        $labels = array();
        $this_field = __("This field", "wp-loyalty-rules");
        foreach ($settings_labels as $settings_label) {
            $labels[$settings_label] = $this_field;
        }
        $validator->labels($labels);
        $validator->stopOnFirstFail(false);
        Validator::addRule('sanitizeText', array(\Wlr\App\Helpers\Validation::class, 'validateSanitizeText'), __('Invalid characters', 'wp-loyalty-rules'));
        $required_fields = $sanitize_text = array();
        if (isset($post['design']) && !empty($post['design']) && is_array($post['design'])) {
            foreach ($post['design'] as $key => $value) {
                switch ($key) {
                    case 'logo':
                    case 'branding':
                        $required_fields[] = 'design.' . $key . '.is_show';
                        $sanitize_text[] = 'design.' . $key . '.is_show';
                        break;
                    case 'colors':
                        $sanitize_text[] = 'design.' . $key . '.theme.background';
                        $sanitize_text[] = 'design.' . $key . '.theme.text';
                        $sanitize_text[] = 'design.' . $key . '.banner.background';
                        $sanitize_text[] = 'design.' . $key . '.banner.text';
                        $sanitize_text[] = 'design.' . $key . '.buttons.background';
                        $sanitize_text[] = 'design.' . $key . '.buttons.text';
                        $sanitize_text[] = 'design.' . $key . '.links';
                        $sanitize_text[] = 'design.' . $key . '.icons';
                        $sanitize_text[] = 'design.' . $key . '.launcher.background';
                        $sanitize_text[] = 'design.' . $key . '.launcher.text';
                        break;
                }
            }
        }
        $validator->rule('required', $required_fields)->message(__('{field} is required', 'wp-loyalty-rules'));
        $validator->rule('sanitizeText', $sanitize_text);
        return $validator->validate() ? true : $validator->errors();
    }

    function validateContentTab($post)
    {
        $validator = new Validator($post);
        $settings_labels = array(
            //guest
            'content.guest.welcome.texts.title', 'content.guest.welcome.texts.description', 'content.guest.welcome.texts.have_account',
            'content.guest.welcome.texts.sign_in', 'content.guest.welcome.texts.sign_in_url', 'content.guest.welcome.button.text',
            'content.guest.welcome.button.url', 'content.guest.welcome.icon.image', 'content.guest.points.earn.title', 'content.guest.points.earn.icon.image',
            'content.guest.points.redeem.title', 'content.guest.points.redeem.icon.image', 'content.guest.referrals.title', 'content.guest.referrals.description',
            //member
            'content.member.banner.texts.welcome', 'content.member.banner.texts.points', 'content.member.banner.texts.points_label',
            'content.member.banner.texts.points_content', 'content.member.banner.texts.points_text', 'content.member.banner.levels.is_show',
            'content.member.banner.points.is_show', 'content.member.points.earn.title', 'content.member.points.earn.icon.image',
            'content.member.points.redeem.title', 'content.member.points.redeem.icon.image',
            'content.member.referrals.title', 'content.member.referrals.description',
        );
        $labels = array();
        $this_field = __("This field", "wp-loyalty-rules");
        foreach ($settings_labels as $settings_label) {
            $labels[$settings_label] = $this_field;
        }
        $validator->labels($labels);
        $validator->stopOnFirstFail(false);
        Validator::addRule('cleanHtml', array(\Wlr\App\Helpers\Validation::class, 'validateCleanHtml'), __('Invalid characters', 'wp-loyalty-rules'));
        Validator::addRule('number', array(\Wlr\App\Helpers\Validation::class, 'validateNumber'), __('must contain only numbers 0-9', 'wp-loyalty-rules'));
        Validator::addRule('sanitizeText', array(\Wlr\App\Helpers\Validation::class, 'validateSanitizeText'), __('Invalid characters', 'wp-loyalty-rules'));
        Validator::addRule('validateGuestShortcodes', array(__CLASS__, 'validateNotAllowedGuestShortcode'), __('has invalid shortcodes.', 'wp-loyalty-rules'));
        Validator::addRule('validateMemberShortcodes', array(__CLASS__, 'validateNotAllowedMemberShortcode'), __('has invalid shortcodes.', 'wp-loyalty-rules'));
        $sanitize_text = $required_and_clean = $clean_html = $guest_shortcode = $member_shortcode = array();
        if (isset($post['content']) && is_array($post['content']) && isset($post['content']['guest']) && !empty($post['content']['guest']) && is_array($post['content']['guest'])) {
            foreach ($post['content']['guest'] as $key => $value) {
                switch ($key) {
                    case 'welcome':
                        $required_and_clean[] = 'content.guest.' . $key . '.texts.title';
                        $guest_shortcode[] = 'content.guest.' . $key . '.texts.title';
                        $required_and_clean[] = 'content.guest.' . $key . '.texts.description';
                        $guest_shortcode[] = 'content.guest.' . $key . '.texts.description';
                        $required_and_clean[] = 'content.guest.' . $key . '.texts.have_account';
                        $guest_shortcode[] = 'content.guest.' . $key . '.texts.have_account';
                        $required_and_clean[] = 'content.guest.' . $key . '.texts.sign_in';
                        $guest_shortcode[] = 'content.guest.' . $key . '.texts.sign_in';
                        $guest_shortcode[] = 'content.guest.' . $key . '.texts.sign_in_url';
                        $required_and_clean[] = 'content.guest.' . $key . '.texts.sign_in_url';
                        $required_and_clean[] = 'content.guest.' . $key . '.button.text';
                        $guest_shortcode[] = 'content.guest.' . $key . '.button.text';
                        $guest_shortcode[] = 'content.guest.' . $key . '.button.url';
                        $required_and_clean[] = 'content.guest.' . $key . '.button.url';
                        break;
                    case 'points':
                        $required_and_clean[] = 'content.guest.' . $key . '.earn.title';
                        $guest_shortcode[] = 'content.guest.' . $key . '.earn.title';
                        $required_and_clean[] = 'content.guest.' . $key . '.redeem.title';
                        $guest_shortcode[] = 'content.guest.' . $key . '.redeem.title';
                        break;
                    case 'referrals':
                        $required_and_clean[] = 'content.guest.' . $key . '.title';
                        $guest_shortcode[] = 'content.guest.' . $key . '.title';
                        $required_and_clean[] = 'content.guest.' . $key . '.description';
                        $guest_shortcode[] = 'content.guest.' . $key . '.description';
                        break;
                }
            }
        }
        if (isset($post['content']) && is_array($post['content']) && isset($post['content']['member']) && !empty($post['content']['member']) && is_array($post['content']['member'])) {
            foreach ($post['content']['member'] as $key => $value) {
                switch ($key) {
                    case 'banner':
                        $sanitize_text[] = 'content.member.' . $key . '.levels.is_show';
                        $sanitize_text[] = 'content.member.' . $key . '.points.is_show';
                        $clean_html[] = 'content.member.' . $key . '.texts.welcome';
                        $member_shortcode[] = 'content.member.' . $key . '.texts.welcome';
                        $required_and_clean[] = 'content.member.' . $key . '.texts.welcome';
                        $member_shortcode[] = 'content.member.' . $key . '.texts.points_label';
                        $required_and_clean[] = 'content.member.' . $key . '.texts.points_label';
                        break;
                    case 'points':
                        $required_and_clean[] = 'content.member.' . $key . '.earn.title';
                        $member_shortcode[] = 'content.member.' . $key . '.earn.title';
                        $required_and_clean[] = 'content.member.' . $key . '.redeem.title';
                        $member_shortcode[] = 'content.member.' . $key . '.redeem.title';
                        break;
                    case 'referrals':
                        $required_and_clean[] = 'content.member.' . $key . '.title';
                        $member_shortcode[] = 'content.member.' . $key . '.title';
                        $required_and_clean[] = 'content.member.' . $key . '.description';
                        $member_shortcode[] = 'content.member.' . $key . '.description';
                        break;
                }
            }
        }
        $validator->rule('required', $required_and_clean)->message(__('{field} is required', 'wp-loyalty-rules'));
        $validator->rule('cleanHtml', $required_and_clean);
        $validator->rule('cleanHtml', $clean_html);
        $validator->rule('sanitizeText', $sanitize_text);
        $validator->rule('validateGuestShortcodes', $guest_shortcode);
        $validator->rule('validateMemberShortcodes', $member_shortcode);
        return $validator->validate() ? true : $validator->errors();
    }

    function validateLauncherTab($post)
    {
        $validator = new Validator($post);
        $settings_labels = array(
            'launcher.appearance.selected', 'launcher.appearance.text',
            'launcher.view_option', 'launcher.font_family', 'launcher.placement.position',
            'launcher.placement.side_spacing', 'launcher.placement.bottom_spacing', 'launcher.show_conditions'
        );
        $labels = array();
        $this_field = __("This field", "wp-loyalty-rules");
        foreach ($settings_labels as $settings_label) {
            $labels[$settings_label] = $this_field;
        }
        $validator->labels($labels);
        $validator->stopOnFirstFail(false);
        Validator::addRule('cleanHtml', array(\Wlr\App\Helpers\Validation::class, 'validateCleanHtml'), __('Invalid characters', 'wp-loyalty-rules'));
        Validator::addRule('number', array(\Wlr\App\Helpers\Validation::class, 'validateNumber'), __('must contain only numbers 0-9', 'wp-loyalty-rules'));
        Validator::addRule('sanitizeText', array(\Wlr\App\Helpers\Validation::class, 'validateSanitizeText'), __('Invalid characters', 'wp-loyalty-rules'));
        Validator::addRule('validateGuestShortcodes', array(__CLASS__, 'validateNotAllowedGuestShortcode'), __('has invalid shortcodes.', 'wp-loyalty-rules'));
        Validator::addRule('validateMemberShortcodes', array(__CLASS__, 'validateNotAllowedMemberShortcode'), __('has invalid shortcodes.', 'wp-loyalty-rules'));
        Validator::addRule('validateUrlContains', array(__CLASS__, 'validateUrlContains'), __('has invalid shortcodes.', 'wp-loyalty-rules'));
        Validator::addRule('isEmpty', array(\Wlr\App\Helpers\Validation::class, 'validateIsEmpty'), __('is empty', 'wp-loyalty-rules'));
        $required_fields = $clean_html = $sanitize_text = $shortcode_check = array();
        if (isset($post['launcher']) && !empty($post['launcher']) && is_array($post['launcher'])) {
            foreach ($post['launcher'] as $key => $value) {
                switch ($key) {
                    case 'appearance':
                        $required_fields[] = 'launcher.' . $key . '.selected';
                        $clean_html[] = 'launcher.' . $key . '.text';
                        $shortcode_check[] = 'launcher.' . $key . '.text';
                        if (is_array($value) && isset($value['selected']) && in_array($value['selected'], array("icon_with_text", "text_only"))) $required_fields[] = 'launcher.' . $key . '.text';
                        break;
                    case 'view_option':
                    case 'font_family':
                        $required_fields[] = 'launcher.' . $key;
                        $clean_html[] = 'launcher.' . $key;
                        $sanitize_text[] = 'launcher.' . $key;
                        break;
                    case 'placement':
                        $required_fields[] = 'launcher.' . $key . '.position';
                        $sanitize_text[] = 'launcher.' . $key . '.position';
                        $required_fields[] = 'launcher.' . $key . '.side_spacing';
                        $required_fields[] = 'launcher.' . $key . '.bottom_spacing';
                        break;
                }
            }
        }
        if (isset($post['launcher']) && is_array($post['launcher']) && isset($post['launcher']['show_conditions'])
            && !empty($post['launcher']['show_conditions']) && is_array($post['launcher']['show_conditions'])) {
            $condition_label_text = $condition_label = $condition_clean = $empty_check = array();
            $condition_label_fields = array(
                'launcher.show_conditions.home_page.operator.value',
                'launcher.show_conditions.home_page.url_path',
                'launcher.show_conditions.contains.operator.value',
                'launcher.show_conditions.contains.url_path',
                'launcher.show_conditions.do_not_contains.operator.value',
                'launcher.show_conditions.do_not_contains.url_path',
            );
            foreach ($condition_label_fields as $label) {
                $condition_label_text[$label] = $this_field;
            }
            foreach ($post['launcher']['show_conditions'] as $key => $condition) {
                $type = is_array($condition) && isset($condition['operator']) && is_array($condition['operator']) &&
                isset($condition['operator']['value']) && !empty($condition['operator']['value']) ? $condition['operator']['value'] : '';
                switch ($type) {
                    case 'home_page':
                        $required_fields[] = 'launcher.show_conditions.' . $key . '.operator.value';
                        $condition_clean[] = 'launcher.show_conditions.' . $key . '.operator.value';
                        $condition_label['launcher.show_conditions.' . $key . '.operator.value'] = $condition_label_text['launcher.show_conditions.' . $type . '.operator.value'];
                        break;
                    case 'contains':
                    case 'do_not_contains':
                        $required_fields[] = 'launcher.show_conditions.' . $key . '.operator.value';
                        $required_fields[] = 'launcher.show_conditions.' . $key . '.url_path';
                        $condition_clean[] = 'launcher.show_conditions.' . $key . '.url_path';
                        $empty_check[] = 'launcher.show_conditions.' . $key . '.url_path';
                        $condition_label['launcher.show_conditions.' . $key . '.operator.value'] = $condition_label_text['launcher.show_conditions.' . $type . '.operator.value'];
                        $condition_label['launcher.show_conditions.' . $key . '.url_path'] = $condition_label_text['launcher.show_conditions.' . $type . '.url_path'];
                        break;
                }
            }
            $validator->labels($condition_label);
            $validator->rule('isEmpty', $empty_check)->message(__('{field} has empty value', 'wp-loyalty-rules'));
            $validator->rule('cleanHtml', $condition_clean)->message(__('{field} has invalid characters', 'wp-loyalty-rules'));
            $validator->rule('validateUrlContains', $condition_clean)->message(__('{field} has invalid characters', 'wp-loyalty-rules'));
        }
        $validator->rule('required', $required_fields)->message(__('{field} is required', 'wp-loyalty-rules'));
        $validator->rule('cleanHtml', $clean_html);
        $validator->rule('sanitizeText', $sanitize_text);
        $validator->rule('validateGuestShortcodes', $shortcode_check);
        $validator->rule('validateMemberShortcodes', $shortcode_check);

        return $validator->validate() ? true : $validator->errors();
    }

    static function validateUrlContains($field, $value, array $params, array $fields)
    {
        if (filter_var($value, FILTER_VALIDATE_URL) !== false) {
            return false; // Full URL is not allowed
        }
        $pattern = apply_filters('wll_launcher_visibility_pages_url_pattern', '/^[a-zA-Z0-9_.\/#=\-:@?&]+$/');
        if (!preg_match($pattern, $value)) {
            return false; // Does not match the pattern
        }
        return true;
    }
}