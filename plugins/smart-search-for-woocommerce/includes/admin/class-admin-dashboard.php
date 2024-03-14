<?php
/**
 * Searchanise Admin dashboard
 *
 * @package Searchanise/AdminDashboard
 */

namespace Searchanise\SmartWoocommerceSearch;

defined( 'ABSPATH' ) || exit;

/**
 * Admin dashboard class
 */
class Admin_Dashboard {

	const DEFAULT_PERIOD = 'Y';
	const KEY_PERIOD = 'se-dashboard-period';
	const KEY_LANGUAGE = 'se-dashboard-language';
	const KEY_CHECKBOX = 'se-dashboard-select-';
	const MAX_SEARCHES_STRINGS = 5;
	const MAX_TEXT_SEARCHES_LENGTH = 40;

	/**
	 * Lang code
	 *
	 * @var string
	 */
	private $lang_code = '';

	/**
	 * Init analytics scripts. Called in wp_dashboard_setup
	 */
	public static function init() {
		if ( ! Api::get_instance()->is_show_analytics_on_dashboard() ) {
			return;
		}

		$se_dashboard_js_path = SE_BASE_DIR . '/assets/js/se-dashboard.js';
		$se_dashboard_css_path = SE_BASE_DIR . '/assets/css/se-dashboard.css';

		$dashboard = new self();
		wp_enqueue_script( 'google-charts', 'https://www.gstatic.com/charts/loader.js', array(), SE_PLUGIN_VERSION, false );
		wp_enqueue_script( 'jquery-cookie', plugins_url( SE_BASE_DIR . '/assets/js/jquery.cookie.js' ), array( 'jquery' ), SE_PLUGIN_VERSION, false );
		wp_register_script( 'se-dashboard', plugins_url( $se_dashboard_js_path ), array( 'jquery', 'google-charts', 'jquery-cookie' ), SE_PLUGIN_VERSION, true );
		wp_register_style( 'se-dashboard', plugins_url( $se_dashboard_css_path ), array(), SE_PLUGIN_VERSION, false );

		wp_add_dashboard_widget( 'se_analytics', __( 'Smart Search Analytics by <span class="se-logo">Searchanise</span>', 'woocommerce-searchanise' ), array( $dashboard, 'analytics_handler' ) );
	}

	/**
	 * Display analytics dashboard
	 */
	public function analytics_handler() {
		$this->lang_code = Api::get_instance()->get_locale();
		$se_dashboard_link = get_admin_url( null, '/admin.php?page=searchanise' );
		$period_selector_html = $this->render_periods_selector();
		$language_selector = $this->render_language_selector();
		$checkbox_states = $this->get_checkbox_states();
		$translations = $this->get_translations();
		$allowed_html = array(
			'div' => array(
				'class' => array(),
				'id' => array(),
			),
			'h2' => array(),
			'h3' => array(),
			'p' => array(),
			'ul' => array(
				'class' => array(),
			),
			'span' => array(
				'class' => array(),
			),
			'li' => array(
				'class' => array(),
			),
			'select' => array(
				'name' => array(),
				'id' => array(),
			),
			'option' => array(
				'value' => array(),
				'selected' => array(),
			),
			'input' => array(
				'type' => array(),
				'id' => array(),
				'name' => array(),
				'value' => array(),
				'checked' => array(),
			),
			'label' => array(
				'for' => array(),
			),
			'a' => array(
				'href' => array(),
				'class' => array(),
			),
		);

		$dashboard_options = array(
			'host'                   => is_ssl() ? str_replace( 'http://', 'https://', SE_SERVICE_URL ) : SE_SERVICE_URL,
			'url_path'               => '/getanalytics/woocommerce',
			'search_queries_limit'   => self::MAX_SEARCHES_STRINGS,
			'max_search_text_length' => self::MAX_TEXT_SEARCHES_LENGTH,
			'txt'                    => $translations,
			'engines'                => $this->get_dashboard_engines(),
			'chart_language'         => Api::get_instance()->get_iso_lang_name( $this->lang_code ),
		);

		wp_localize_script( 'se-dashboard', 'SeDashboardOptions', $dashboard_options );
		wp_enqueue_style( 'se-dashboard' );
		wp_enqueue_script( 'se-dashboard' );

		$dashboard_html = <<<HTML
	<div class="se-language-select">
		{$language_selector}
	</div>
	<div class="se-dashboard-container">
		<div id="se-chart-error" class="se-hidden">
			<div class="se-error-contentainer">
				<div class="se-error-content">
					<h2>{$translations['chart_error_title']}</h2>
					<p>{$translations['chart_error']}</p>
				</div>
			</div>
		</div>
		<ul class="se-dashboard">
			<li class="se-analytics-select-wrapper">
				<div class="se-date-select">
					{$period_selector_html}
				</div>
				<div class="se-analytics-select">
					<ul class="se-analytics-select-list">
						<li><input type="checkbox" id="elm-total-searches" name="se_query[]" value="search_data" {$checkbox_states['search_data']} /><label for="elm-total-searches">{$translations['total_searches']}</label></li>
						<li><input type="checkbox" id="elm-category-clicks" name="se_query[]" value="categories_clicks" {$checkbox_states['categories_clicks']} /><label for="elm-category-clicks">{$translations['category_clicks']}</label></li>
						<li><input type="checkbox" id="elm-product-clicks" name="se_query[]" value="product_clicks" {$checkbox_states['product_clicks']} /><label for="elm-product-clicks">{$translations['product_clicks']}</label></li>
						<li><input type="checkbox" id="elm-suggestion-clicks" name="se_query[]" value="suggestions_clicks" {$checkbox_states['suggestions_clicks']} /><label for="elm-suggestion-clicks">{$translations['suggestion_clicks']}</label></li>
					</ul>
				</div>
				<div class="se-clear"></div>
			</li>
			<li class="se-graphs se-loading">
				<div id="se-chart"></div>
			</li>
			<li class="se-search-results-wrapper">
				<div class="se-top-search-queries">
					<h3>{$translations['top_search_queries']}</h3>
					<span class="se-no-results">{$translations['no_results']}</span>
					<div class="se-results-content"></div>
				</div>
				<div class="se-top-search-no-result-queries">
					<h3>{$translations['top_search_queries_no_results']}</h3>
					<span class="se-no-results">{$translations['no_results']}</span>
					<div class="se-results-content"></div>
				</div>
				<div class="se-clear"></div>
			</li>
		</ul>
	</div>
	<div class="se-go-dashboard">
		<a href="{$se_dashboard_link}" class="button">{$translations['go_dashboard']}</a>
	</div>
HTML;

		echo wp_kses( $dashboard_html, $allowed_html );
	}

	/**
	 * Generate language selector html code
	 *
	 * @param bool $output If true, selector content will be displayed otherwise return.
	 *
	 * @return string
	 */
	public function render_language_selector( $output = false ) {
		$html = '';
		$engines_data = $this->get_dashboard_engines();
		$current_language = $this->get_current_language();
		$allowed_html = array(
			'div' => array(
				'class' => array(),
				'id' => array(),
			),
			'h3' => array(),
			'select' => array(
				'name' => array(),
				'id' => array(),
			),
			'option' => array(
				'value' => array(),
				'selected' => array(),
			),
			'input' => array(
				'type' => array(),
				'id' => array(),
				'name' => array(),
				'value' => array(),
				'checked' => array(),
			),
		);

		if ( count( $engines_data ) > 1 ) {
			$html = '<div class="se-language-select-value">';
			$html .= '<select name="se_language" id="se-language">';
			foreach ( $engines_data as $e ) {
				$selected = $e['lang_code'] == $current_language ? ' selected="selected"' : '';
				$html .= "<option value=\"{$e['lang_code']}\"{$selected}>{$e['language_name']}</option>";
			}
			$html .= '</select></div>';
			$html .= '<div class="se-language-select-title"><h3>Language</h3></div>';
		} elseif ( count( $engines_data ) == 1 ) {
			$e = reset( $engines_data );
			$html .= "<input type=\"hidden\" name=\"se_language\" id=\"se-language\" value = \"{$e['lang_code']}\" />";
		}

		if ( $output ) {
			echo wp_kses( $html, $allowed_html );
		}

		return $html;
	}

	/**
	 * Generate period selector html
	 *
	 * @param bool $output If true, selector content will be displayed otherwise return.
	 *
	 * @return mixed
	 */
	public function render_periods_selector( $output = false ) {
		$selected_period = $this->get_current_period();
		$available_periods = $this->get_available_periods();
		$allowed_html = array(
			'select' => array(
				'name' => array(),
				'id' => array(),
			),
			'option' => array(
				'value' => array(),
				'selected' => array(),
			),
		);

		$html = '<select name="se_time_period" id="se-time-period">';

		foreach ( $available_periods as $period => $name ) {
			$selected = $period == $selected_period ? ' selected="selected"' : '';
			$html .= "<option value=\"{$period}\"{$selected}>{$name}</option>";
		}
		$html .= '</select>';

		if ( $output ) {
			echo wp_kses( $html, $allowed_html );
			return true;
		} else {
			return $html;
		}
	}

	/**
	 * Returns period selector variants
	 *
	 * @return array
	 */
	public function get_available_periods() {
		return array(
			'W'   => __( 'This week', 'woocommerce-searchanise' ),
			'LW'  => __( 'Last week', 'woocommerce-searchanise' ),
			'M'   => __( 'This month', 'woocommerce-searchanise' ),
			'LM'  => __( 'Last month', 'woocommerce-searchanise' ),
			'Y'   => __( 'This year', 'woocommerce-searchanise' ),
			'LY'  => __( 'Last year', 'woocommerce-searchanise' ),
		);
	}

	/**
	 * Returns current selected language
	 *
	 * @return string
	 */
	public function get_current_language() {
		$engines_data = $this->get_dashboard_engines();

		if ( ! empty( $_SESSION[ self::KEY_LANGUAGE ] ) ) {
			$lang_code = sanitize_key( $_SESSION[ self::KEY_LANGUAGE ] );
		} elseif ( ! empty( $_COOKIE[ self::KEY_LANGUAGE ] ) ) {
			$lang_code = sanitize_key( $_COOKIE[ self::KEY_LANGUAGE ] );
		}

		if ( ! empty( $lang_code ) && key_exists( $lang_code, $engines_data ) ) {
			return $lang_code;
		} else {
			return $this->lang_code;
		}
	}

	/**
	 * Retun current selected period
	 *
	 * @return string
	 */
	public function get_current_period() {
		$period = self::DEFAULT_PERIOD;
		$available_periods = $this->get_available_periods();

		if ( ! empty( $_SESSION[ self::KEY_PERIOD ] ) ) {
			$period = sanitize_key( $_SESSION[ self::KEY_PERIOD ] );
		} elseif ( ! empty( $_COOKIE[ self::KEY_PERIOD ] ) ) {
			$period = sanitize_key( $_COOKIE[ self::KEY_PERIOD ] );
		}

		$period = key_exists( $period, $available_periods ) ? $period : self::DEFAULT_PERIOD;

		return $period;
	}

	/**
	 * Returns checkbox states
	 */
	public function get_checkbox_states() {
		$states = array();
		$names = array( 'search_data', 'categories_clicks', 'product_clicks', 'suggestions_clicks' );

		foreach ( $names as $name ) {
			$key = self::KEY_CHECKBOX . $name;
			$value = 'true';

			if ( ! empty( $_SESSION[ $key ] ) ) {
				$value = sanitize_key( $_SESSION[ $key ] );
			} elseif ( ! empty( $_COOKIE[ $key ] ) ) {
				$value = sanitize_key( $_COOKIE[ $key ] );
			}

			$states[ $name ] = 'true' == $value ? 'checked="checked"' : '';
		}

		return $states;
	}

	/**
	 * Returns translations
	 *
	 * @return array
	 */
	public function get_translations() {
		return array(
			'date'                          => __( 'Date', 'woocommerce-searchanise' ),
			'total_searches'                => __( 'Total searches', 'woocommerce-searchanise' ),
			'product_clicks'                => __( 'Product Clicks', 'woocommerce-searchanise' ),
			'category_clicks'               => __( 'Category Clicks', 'woocommerce-searchanise' ),
			'suggestion_clicks'             => __( 'Suggestion Clicks', 'woocommerce-searchanise' ),
			'go_dashboard'                  => __( 'Go to Dashboard', 'woocommerce-searchanise' ),
			'no_results'                    => __( 'Sorry, nothing to report', 'woocommerce-searchanise' ),
			'top_search_queries'            => __( 'Top search queries', 'woocommerce-searchanise' ),
			'top_search_queries_no_results' => __( 'Top search with no results', 'woocommerce-searchanise' ),
			'chart_error_title'             => __( 'Something went wrong', 'woocommerce-searchanise' ),
			/* translators: %s: support email */
			'chart_error'                   => sprintf( __( 'We couldn’t get the data, please try to check it later or contact <a href="mailto:%s" target="blank">Searchanise support</a>', 'woocommerce-searchanise' ), SE_SUPPORT_EMAIL ),
		);
	}

	/**
	 * Returns engines available for dashboard statistic
	 *
	 * @return array
	 */
	public function get_dashboard_engines() {
		static $engines = array();

		if ( empty( $engines ) ) {
			$engines = Api::get_instance()->get_engines();
		}

		return $engines;
	}
}
