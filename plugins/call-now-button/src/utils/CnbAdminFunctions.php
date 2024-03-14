<?php

namespace cnb\utils;

// don't load directly
use cnb\admin\action\CnbActionType;

defined( 'ABSPATH' ) || die( '-1' );

class CnbAdminFunctions {
    /**
     * Get the active tab name (?tab=<name>)
     *
     * @return string
     */
    function get_active_tab_name() {
        $cnb_utils = new CnbUtils();

        return $cnb_utils->get_query_val( 'tab', 'basic_options' );
    }

    /**
     * Returns the CSS class used for active tabs
     *
     * @param $tab_name string name of tab to check
     *
     * @return string
     */
    function is_active_tab( $tab_name ) {
        $active_tab = $this->get_active_tab_name();

        return $active_tab === $tab_name ? 'nav-tab-active' : '';
    }

    /**
     * Return an array of all ButtonTypes
     *
     * @return string[] array of ButtonTypes to their nice names
     */
    function cnb_get_button_types() {
        return array(
            'SINGLE' => 'Single button',
            'FULL'   => 'Buttonbar',
            'MULTI'  => 'Multibutton',
	        'DOTS'   => 'Dots',
        );
    }

    /**
     * Return an array of all ActionTypes
     *
     * Note(s):
     * - This is NOT in alphabetical order, but rather in order of
     *   what feels more likely to be chosen
     *
     * @return CnbActionType[] array of ActionType to their nice names
     */
    function cnb_get_action_types() {
        $all_types = array(
            'PHONE'    => new CnbActionType('PHONE', '💬 Phone', ['STARTER', 'PRO', 'FREE']),
            'EMAIL'    => new CnbActionType('EMAIL', '💬 Email', ['STARTER', 'PRO', 'FREE']),
            'SMS'      => new CnbActionType('SMS', '💬 SMS/Text', ['STARTER', 'PRO', 'FREE']),
            'WHATSAPP' => new CnbActionType('WHATSAPP', '💬 WhatsApp', ['STARTER', 'PRO', 'FREE']),
            'FACEBOOK' => new CnbActionType('FACEBOOK', '💬 Messenger', ['STARTER', 'PRO', 'FREE']),
            'SIGNAL'   => new CnbActionType('SIGNAL', '💬 Signal', ['STARTER', 'PRO', 'FREE']),
            'TELEGRAM' => new CnbActionType('TELEGRAM', '💬 Telegram', ['STARTER', 'PRO', 'FREE']),
            'ANCHOR'   => new CnbActionType('ANCHOR', '⏬ Scroll to  point', ['STARTER', 'PRO', 'FREE']),
            'LINK'     => new CnbActionType('LINK', '🔗 Link', ['STARTER', 'PRO', 'FREE']),
            'MAP'      => new CnbActionType('MAP', '📍 Location', ['STARTER', 'PRO', 'FREE']),
            'TALLY'    => new CnbActionType('TALLY', '🔌 Tally form window', ['PRO', 'FREE']),
            'IFRAME'   => new CnbActionType('IFRAME', '🔌 Content window', ['PRO']),
            'INTERCOM' => new CnbActionType('INTERCOM', '🔌 Intercom chat', ['PRO']),
            'SKYPE'    => new CnbActionType('SKYPE', '💬 Skype', ['STARTER', 'PRO', 'FREE']),
            'ZALO'     => new CnbActionType('ZALO', '💬 Zalo', ['STARTER', 'PRO', 'FREE']),
            'VIBER'    => new CnbActionType('VIBER', '💬 Viber', ['STARTER', 'PRO', 'FREE']),
            'LINE'     => new CnbActionType('LINE', '💬 Line', ['STARTER', 'PRO', 'FREE']),
            'WECHAT'   => new CnbActionType('WECHAT', '💬 WeChat', ['STARTER', 'PRO', 'FREE']),
            'CHAT'     => new CnbActionType('CHAT', '💬 Live chat', ['PRO']),
        );

		return apply_filters('cnb_get_action_types', $all_types);
    }

	function get_display_modes() {
		return array(
			'MOBILE_ONLY' => 'Mobile only',
			'DESKTOP_ONLY' => 'Desktop only',
			'ALWAYS' => 'All screens'
		);
	}

    function cnb_get_condition_filter_types() {
        return array(
            'INCLUDE' => 'Display the button',
            'EXCLUDE' => 'Hide the button',
        );
    }

    function cnb_get_condition_types() {
        return array(
            'URL' => [
                'name' => 'Page URL',
                'proOnly' => false
                ],
            'GEO' => [
                'name' => 'Visitor location',
                'proOnly' => true
                ]
        );
    }

    /**
     * These apply to URL only
     * @return array[]
     */
    function cnb_get_condition_match_types_url() {
        return array(
            'SIMPLE'    => [
                'name' => 'Page path starts with',
                'plans' => ['STARTER', 'PRO', 'FREE']
            ],
            'EXACT'     => [
                'name' => 'Page URL is',
                'plans' => ['STARTER', 'PRO', 'FREE']
            ],
            'SUBSTRING' => [
                'name' => 'Page URL contains',
                'plans' => ['STARTER', 'PRO', 'FREE']
            ],
            'REGEX'     => [
                'name' => 'Page URL matches RegEx',
                'plans' => ['PRO', 'FREE']
            ]
        );
    }

    /**
     * These apply to GEO only
     *
     * @return string[]
     */
    function cnb_get_condition_match_types_geo() {
        return array(
            'COUNTRY_CODE'    => 'Country code is',
        );
    }

    /**
     * @param array $original Array of "daysOfWeek", index 0 == Monday, values should be strings and contain "true"
     * in order to be evaulated correctly.
     *
     * @return array cleaned up array with proper booleans for the days.
     */
    function cnb_create_days_of_week_array( $original ) {
        // If original does not exist, leave it as it is
        if ( ! is_array( $original ) ) {
            return $original;
        }

        // Default everything is NOT selected, then we enable only those days that are passed in via $original
        $result = array( false, false, false, false, false, false, false );
        foreach ( $result as $day_of_week_index => $day_of_week ) {
            $day_of_week_is_enabled       = isset( $original[ $day_of_week_index ] ) && ( $original[ $day_of_week_index ] === 'true' || $original[ $day_of_week_index ] === true );
            $result[ $day_of_week_index ] = $day_of_week_is_enabled;
        }

        return $result;
    }

    /**
     * <p>Echo the promobox.</p>
     * <p>The CTA block is optional and displays only when there's a link provided or $cta_button_text = 'none'.</p>
     * <p>Defaut CTA text is "Let's go". Default <code>$icon</code> is flag (value should be a dashicon name)</p>
     *
     * <p><strong>NOTE: $body and $cta_pretext are NOT escaped and are assumed to be pre-escaped (or contain no User input)</strong></p>
     *
     * @param $color string
     * @param $headline string Assumed to be pre-escaped HTML (or static HTML), so this will not be (re)escaped
     * @param $body string Assumed to be pre-escaped HTML, so this will not be (re)escaped
     * @param $icon string
     * @param $cta_pretext string Assumed to be pre-escaped HTML, so this will not be (re)escaped
     * @param $cta_button_text string
     * @param $cta_button_link string URL
     * @param $cta_footer_notice
     *
     * @return void It <code>echo</code>s html output of the promobox
     */
    function cnb_promobox( $color, $headline, $body, $icon = 'flag', $cta_pretext = null, $cta_button_text = 'Let\'s go', $cta_button_link = null, $cta_footer_notice = null ) {
        echo '
        <div id="cnb_upgrade_box" class="cnb-promobox cnb-promobox-' . esc_attr( $color ) . '">
            <div class="cnb-promobox-header cnb-promobox-header-' . esc_attr( $color ) . '">
                <span class="dashicons dashicons-' . esc_attr( $icon ) . '"></span>
                <h2 class="hndle">' .
             // phpcs:ignore WordPress.Security
              $headline
             . '</h2>
            </div>
            <div class="inside">
                <div class="cnb-promobox-copy">
                    <div class="cnb_promobox_item">' .
             // phpcs:ignore WordPress.Security
             $body
             . '</div>
                    <div class="clear"></div>';
        if ( ! is_null( $cta_button_link ) || $cta_button_text == 'none' ) {
            echo '
                    <div class="cnb-promobox-action">
                        <div class="cnb-promobox-action-left">' .
                 // phpcs:ignore WordPress.Security
                 $cta_pretext
                 . '</div>';
            if ( $cta_button_text != 'none' && $cta_button_link != 'disabled' ) {
                echo '
                        <div class="cnb-promobox-action-right">
                            <a class="button button-primary button-large" style="user-select: none;" href="' . esc_url( $cta_button_link ) . '">' . esc_html( $cta_button_text ) . '</a>
                        </div>';
            } elseif ( $cta_button_link == 'disabled' ) {
                echo '
                        <div class="cnb-promobox-action-right">
                            <button class="button button-primary button-large" disabled>' . esc_html( $cta_button_text ) . '</a>
                        </div>';
            }
            echo '
                        <div class="clear"></div>';
            if ( ! is_null( $cta_footer_notice ) ) {
                echo '<div class="nonessential" style="padding-top: 5px;">' . esc_html( $cta_footer_notice ) . '</div>';
            }
            echo '
                    </div>
                    ';
        }
        echo '
                </div>
            </div>
        </div>
    ';
    }

    /**
     *
     * Returns the url for the Upgrade to cloud page
     *
     * @return string upgrade page url
     */
    function cnb_legacy_upgrade_page() {
        $url = admin_url( 'admin.php' );

        return add_query_arg( 'page', 'call-now-button-upgrade', $url );
    }
}
