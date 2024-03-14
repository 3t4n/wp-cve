<?php

namespace IfSo\Services\PluginSettingsService;

if (!class_exists('PluginSettingsService')) {

class PluginSettingsService {

	const PAGES_VISITED_OPTION =
		'ifso_settings_page_pages_visited_option';
	const REMOVE_PLUGIN_DATA_OPTION =
		'ifso_settings_page_remove_data_on_uninstall_option';
	const APPLY_THE_CONTENT_FILTER_OPTION =
		'ifso_settings_page_apply_the_content_filter_option';
	const REMOVE_AUTO_P_TAG_OPTION =
		'ifso_settings_page_remove_auto_p_tag_option';
	const ALLOW_FRAGMENTED_CACHE =
		'ifso_settings_page_allow_fragmented_cache_option';
	const REMOVE_COOKIE =
		'ifso_settings_pages_remove_visits_cookie_option';
	const ALLOW_SHORTCODES =
		'ifso_settings_pages_allow_shortcodes_option';
	const DISABLE_CACHE =
		'ifso_settings_pages_disable_cache_option';
    const AJAX_ANALYTICS =
        'ifso_settings_pages_analytics_with_ajax_option';
    const DISABLE_ANALYTICS =
        'ifso_settings_pages_analytics_enabled_option';
    const USER_GROUP_LIMIT =
        'ifso_settings_pages_user_group_limit_option';
    const GROUPS_COOKIE_LIFESPAN =
        'ifso_settings_pages_groups_cookie_lifespan';
    const RENDER_TRIGGERS_VIA_AJAX =
        'ifso_settings_page_render_triggers_via_ajax_option';
    const PREVENT_NOCACHE_HEADERS =
        'ifso_settings_prevent_nocache_headers_option';
    const FORCE_DO_SHORTCODE =
        'ifso_settings_force_do_shortcode_option';
    const DISABLE_SESSIONS =
        'ifso_settings_disable_sessions_optionm';
    const SCHEDULE_INTERVAL =
        'ifso_settings_schedule_interval_option';
    const TRIGGERS_VISITED_ON =
        'ifso_settings_triggers_visited_on_option';
    const TRIGGERS_VISITED_NUMBER =
        'ifso_settings_triggers_visited_number_option';
    const AJAX_LOADER_ANIMATION_TYPE =
        'ifso_settings_ajax_loader_animation_type_option';
    const TINYMCE_FORCE_WRAPPER =
        'ifso_settings_tmce_force_wrapper_option';
    const ENABLE_VISIT_COUNT =
        'ifso_settings_enable_visit_count_option';

	private static $instance;

	public $pagesVisitedOption;
	public $removePluginDataOption;
	public $applyTheContentFilterOption;
	public $removeAutoPTagOption;
	public $allowFragmentedCacheOption;
	public $removePageVisitsCookie;
	public $allowShortcodesInTitle;
	public $disableCache;
    public $ajaxAnalytics;
    public $disableAnalytics;
    public $userGroupLimit;
    public $groupsCookieLifespan;
    public $renderTriggersViaAjax;
    public $preventNocacheHeaders;
    public $forceDoShortcode;
    public $disableSessions;
    public $scheduleInterval;
    public $triggersVisitedOn;
    public $triggersVisitedNumber;
    public $ajaxLoaderAnimationType;
    public $tmceForceWrapper;
    public $enableVisitCount;
    public $extraOptions;

	private function __construct() {
		$this->pagesVisitedOption = 
			$this->create_pages_visited_option();
		$this->removePluginDataOption = 
			$this->create_remove_plugin_data_option();
		$this->applyTheContentFilterOption = 
			$this->create_apply_the_content_filter_option();
		$this->removeAutoPTagOption = 
			$this->create_remove_auto_p_tag_option();
		$this->allowFragmentedCacheOption = 
			$this->create_allow_fragmented_cache_option();
		$this->removePageVisitsCookie = 
			$this->remove_visits_cookie_option();
		$this->allowShortcodesInTitle = 
			$this->create_allow_shortcodes_option();
		$this->disableCache = 
			$this->create_disable_cache_option();
        $this->ajaxAnalytics =
            $this->create_ajax_analytics_option();
        $this->disableAnalytics =
            $this->create_disable_analytics_option();
        $this->userGroupLimit =
            $this->create_user_group_limit_option();
        $this->groupsCookieLifespan =
            $this->create_groups_cookie_lifespan_option();
        $this->renderTriggersViaAjax =
            $this->create_render_triggers_via_ajax_option();
        $this->preventNocacheHeaders =
            $this->create_prevent_nocache_option();
        $this->forceDoShortcode =
            $this->create_force_do_shortcode_option();
        $this->disableSessions =
            $this->create_disable_sessions_option();
        $this->scheduleInterval =
            $this->create_schedule_interval_option();
        $this->triggersVisitedOn =
            $this->create_triggers_visited_on_option();
        $this->triggersVisitedNumber =
            $this->create_triggers_visited_number_option();
        $this->ajaxLoaderAnimationType =
            $this->create_ajax_loader_type_option();
        $this->tmceForceWrapper =
            $this->create_tmce_force_wrapper_option();
        $this->enableVisitCount =
            $this->create_enable_visit_count_option();

        add_action('plugins_loaded',function (){
            $this->extraOptions = apply_filters("ifso_extra_settings_options",new \StdClass());
        },PHP_INT_MAX);
	}

	private function remove_visits_cookie_option() {
		$default = true;
		$postName = 'ifso_settings_pages_remove_visits_cookie';
		$option = new IfSoSettingsYesNoOption(
				self::REMOVE_COOKIE,
				$default,
				$postName
			);
		return $option;	
	}

	private function create_allow_fragmented_cache_option() {
		$default = false;
		$postName = 'ifso_settings_pages_allow_fragmented_cache';
		$option = new IfSoSettingsYesNoOption(
				self::ALLOW_FRAGMENTED_CACHE,
				$default,
				$postName
			);
		return $option;	
	}

	private function create_remove_auto_p_tag_option() {
		$default = true;
		$postName = 'ifso_settings_pages_remove_auto_p_tag';
		$option = new IfSoSettingsYesNoOption(
				self::REMOVE_AUTO_P_TAG_OPTION,
				$default,
				$postName
			);
		return $option;	
	}

	private function create_apply_the_content_filter_option() {
		$default = true;
		$postName = 'ifso_settings_pages_apply_the_content_filter';
		$option = new IfSoSettingsYesNoOption(
				self::APPLY_THE_CONTENT_FILTER_OPTION,
				$default,
				$postName
			);
		return $option;	
	}

	private function create_remove_plugin_data_option() {
		$default = false;
		$postName = 'ifso_settings_pages_remove_data_uninstall';
		$option = new IfSoSettingsYesNoOption(
				self::REMOVE_PLUGIN_DATA_OPTION,
				$default,
				$postName
			);
		return $option;
	}

	private function create_pages_visited_option() {
		$default = array(
			'duration_type' => 'weeks',
			'duration_value' => 2
		);
		$option = new IfSoSettingsPagesVisitedOption(
				self::PAGES_VISITED_OPTION,
				$default
			);
		return $option;
	}

	private function create_allow_shortcodes_option() {
		$default = false;
		$postName = 'ifso_settings_pages_allow_shortcodes';
		$option = new IfSoSettingsYesNoOption(
				self::ALLOW_SHORTCODES,
				$default,
				$postName
			);
		return $option;	
	}

	private function create_disable_cache_option() {
		$default = false;
		$postName = 'ifso_settings_pages_disable_cache';
		$option = new IfSoSettingsYesNoOption(
				self::DISABLE_CACHE,
				$default,
				$postName
			);
		return $option;	
	}

    private function create_disable_analytics_option() {
        $default = false;
        $postName = 'ifso_settings_pages_analytics_disabled';
        $option = new IfSoSettingsYesNoOption(
            self::DISABLE_ANALYTICS,
            $default,
            $postName
        );
        return $option;
    }

    private function create_ajax_analytics_option() {
        $default = true;
        $postName = 'ifso_settings_pages_analytics_with_ajax';
        $option = new IfSoSettingsYesNoOption(
            self::AJAX_ANALYTICS,
            $default,
            $postName
        );
        return $option;
    }

    private function create_user_group_limit_option(){
        $default = 5;
        $postName = 'ifso_settings_pages_user_group_limit';
        $option = new IfSoSettingsNumberOption(
            self::USER_GROUP_LIMIT,
            $default,
            $postName
        );
        return $option;
    }

    private function create_groups_cookie_lifespan_option(){
        $default = 365;
        $postName = 'ifso_settings_pages_groups_cookie_lifespan';
        $option = new IfSoSettingsNumberOption(
            self::GROUPS_COOKIE_LIFESPAN,
            $default,
            $postName
        );
        return $option;
    }

    private function create_render_triggers_via_ajax_option(){
        $default = false;
        $postName = 'ifso_settings_page_render_triggers_via_ajax';
        $option = new IfSoSettingsYesNoOption(
            self::RENDER_TRIGGERS_VIA_AJAX,
            $default,
            $postName
        );
        return $option;
    }

    private function create_prevent_nocache_option(){
        $default = true;
        $postName ='ifso_settings_prevent_nocache_headers';
        $option = new IfSoSettingsYesNoOption(
            self::PREVENT_NOCACHE_HEADERS,
            $default,
            $postName
        );
        return $option;
    }

    private function create_force_do_shortcode_option(){
        $default = true;
        $postName ='ifso_settings_force_do_shortcode';
        $option = new IfSoSettingsYesNoOption(
            self::FORCE_DO_SHORTCODE,
            $default,
            $postName
        );
        return $option;
    }

    private function create_disable_sessions_option(){
	    $default = false;
	    $postName = 'ifso_settings_disable_sessions';
	    $option = new IfSoSettingsYesNoOption(
	        self::DISABLE_SESSIONS,
            $default,
            $postName
        );
	    return $option;
    }

    private function create_schedule_interval_option(){
	    $default = 60;
	    $postName = 'ifso_settings_schedule_interval';
        $option = new IfSoSettingsNumberOption(
            self::SCHEDULE_INTERVAL,
            $default,
            $postName
        );
        return $option;
    }

    private function create_triggers_visited_on_option(){
	    $default = false;
	    $postName = 'ifso_settings_triggers_visited_on';
        $option = new IfSoSettingsYesNoOption(
            self::TRIGGERS_VISITED_ON,
            $default,
            $postName
        );
        return $option;
    }

    private function create_triggers_visited_number_option(){
        $default = 100;
        $postName = 'ifso_settings_triggers_visited_number';
        $option = new IfSoSettingsNumberOption(
            self::TRIGGERS_VISITED_NUMBER,
            $default,
            $postName
        );
        return $option;
    }

    private function create_ajax_loader_type_option(){
	    $default = 0;
	    $postName = 'ifso_settings_ajax_loader_animation_type';
        $option = new IfSoSettingsStringOption(
            self::AJAX_LOADER_ANIMATION_TYPE,
            $default,
            $postName
        );
        return $option;
    }

    private function create_tmce_force_wrapper_option(){
	    $default = false;
	    $postName = 'ifso_settings_tmce_force_wrapper';
        $option = new IfSoSettingsYesNoOption(
            self::TINYMCE_FORCE_WRAPPER,
            $default,
            $postName
        );
        return $option;
    }

    private function create_enable_visit_count_option(){
	    $default  = true;
	    $postName =  "ifso_settings_enable_visit_count";
        $option = new IfSoSettingsYesNoOption(
            self::ENABLE_VISIT_COUNT,
            $default,
            $postName
        );
        return  $option;
    }

	public static function get_instance() {
		if ( NULL == self::$instance )
			self::$instance = new PluginSettingsService();

		return self::$instance;
	}

	public function settings_page_update() {
		if( isset( $_POST['ifso_settings_page_update'] ) ) {
		 	if( ! check_admin_referer(
		 			'ifso_settings_nonce',
		 			'ifso_settings_nonce' ) )
				return;

			$this->pagesVisitedOption->apply($_POST);
			$this->removePluginDataOption->apply($_POST);
			$this->applyTheContentFilterOption->apply($_POST);
			$this->removeAutoPTagOption->apply($_POST);
			$this->allowFragmentedCacheOption->apply($_POST);
			$this->removePageVisitsCookie->apply($_POST);
			$this->allowShortcodesInTitle->apply($_POST);
			$this->disableCache->apply($_POST);
			$this->ajaxAnalytics->apply($_POST);
            $this->disableAnalytics->apply($_POST);
            $this->userGroupLimit->apply($_POST);
            $this->groupsCookieLifespan->apply($_POST);
            $this->renderTriggersViaAjax->apply($_POST);
            $this->preventNocacheHeaders->apply($_POST);
            $this->forceDoShortcode->apply($_POST);
            $this->disableSessions->apply($_POST);
            $this->scheduleInterval->apply($_POST);
            $this->triggersVisitedOn->apply($_POST);
            $this->triggersVisitedNumber->apply($_POST);
            $this->ajaxLoaderAnimationType->apply($_POST);
            $this->tmceForceWrapper->apply($_POST);
            $this->enableVisitCount->apply($_POST);

            foreach ($this->extraOptions as $extension){
                foreach($extension as $option){
                    $option->apply($_POST);
                }
            }

            do_action('ifso_settings_page_update');

			$this->redirect_to_settings_page();
		}
	}

	private function redirect_to_settings_page() {
		$redirect = admin_url( 'admin.php?page=' . EDD_IFSO_PLUGIN_SETTINGS_PAGE );
		wp_redirect( $redirect );
		exit();
	}
}

abstract class IfSoSettingsOptionBase {
	private $optionName;
	private $optionDefault;

	public function __construct($optionName, $optionDefault) {
		$this->optionName = $optionName;
		$this->optionDefault = $optionDefault;
	}

	public function get() {
		if ( $this->isset_cached_value() )
			return $this->get_cached_value();

		$option = get_option( $this->optionName,
						  	  $this->optionDefault );

		try {
			$this->cache_value($option);
		} catch (InvalidArgumentException $e) {
			$this->cache_value($this->optionDefault);
		}

		return $this->get_cached_value();
	}

	public function set( $optionValue ) {
		update_option( $this->optionName, $optionValue );
		$this->cache_value( $optionValue );
	}

	abstract public function apply($post);
	abstract public function validate($optionValue);
	abstract protected function isset_cached_value();
	abstract protected function get_cached_value();
	abstract protected function cache_value($optionValue);
}

class IfSoSettingsPagesVisitedOption
	extends IfSoSettingsOptionBase {

	private $cachedValue;

	public function apply( $post ) {
		$postName_Type = 'ifso_settings_pages_visited_type';
		$postName_Value = 'ifso_settings_pages_visited_value';

		if ( ! isset( $post[$postName_Type] ) )
			return false;
		else if ( ! isset( $post[$postName_Value] ))
			return false;

		$type = $post[$postName_Type];
		$value = $post[$postName_Value];
		$option = array(
				'duration_type' => $type,
				'duration_value' => $value
			);

		if ( ! $this->validate($option) )
			return false;

		$this->set($option);
	}

	public function validate( $optionValue ) {
		$durationValue = $optionValue['duration_value'];
		if ( ! is_numeric( $durationValue ) )
			return false;

		$durationType = $optionValue['duration_type'];
		$availableDurationTypes = array(
				'minutes',
				'hours',
				'days',
				'weeks',
				'months');
		if ( ! in_array( $durationType,
						 $availableDurationTypes ) )
			return false;

		return true;
	}

	protected function isset_cached_value() {
		return isset( $this->cachedValue );
	}

	protected function get_cached_value() {
		return $this->cachedValue;
	}

	protected function cache_value( $optionValue ) {
		$this->cachedValue = 
			new IfSoPagesVisitedOptionData($optionValue);
	}
}

class IfSoSettingsYesNoOption extends IfSoSettingsOptionBase {

	protected $cachedValue;
	protected $postName;

	public function __construct($optionName,
								$optionDefault,
								$postName) {
		parent::__construct($optionName, $optionDefault);
		$this->postName = $postName;
	}

	public function apply( $post ) {
		$value = isset( $post[$this->postName] ) ? 1 : 0;

		if ( ! $this->validate($value) )
			return false;

		$this->set($value);
	}

	public function validate( $optionValue ) {
		return $optionValue == 1 || $optionValue == 0;
	}

	protected function isset_cached_value() {
		return isset( $this->cachedValue );
	}

	protected function get_cached_value() {
		return $this->cachedValue;
	}

	protected function cache_value( $optionValue ) {
		$this->cachedValue =  $optionValue;
	}
}

    class IfSoSettingsNumberOption extends IfSoSettingsOptionBase {

        protected $cachedValue;
        protected $postName;

        public function __construct($optionName,
                                    $optionDefault,
                                    $postName) {
            parent::__construct($optionName, $optionDefault);
            $this->postName = $postName;
        }

        public function apply( $post ) {
            if(!isset($post[$this->postName]))
                return false;

            $value = $post[$this->postName];

            if ( ! $this->validate($value) )
                return false;

            $this->set($value);
        }

        public function validate( $optionValue ) {
            return is_numeric($optionValue);
        }

        protected function isset_cached_value() {
            return isset( $this->cachedValue );
        }

        protected function get_cached_value() {
            return $this->cachedValue;
        }

        protected function cache_value( $optionValue ) {
            $this->cachedValue =  $optionValue;
        }
    }

    class IfSoSettingsStringOption extends IfSoSettingsNumberOption{
        public function apply($post){
            $post = wp_unslash($post);
            parent::apply($post);
        }

        public function validate( $optionValue ) {
            return is_string($optionValue);
        }

    }

class IfSoPagesVisitedOptionData {

	private $durationType;
	private $durationValue;

	public function __construct($options) {
		$this->durationType
			= $this->safe_extrap_option($options, 'duration_type');
		$this->durationValue
			= $this->safe_extrap_option($options, 'duration_value');
	}

	/*
	 * Helper method that extracts the given $option from $arr
	 */
	private function safe_extrap_option($options, $option) {
		if ( key_exists( $option, $options ) )
			return $options[$option];
		else
			throw new InvalidArgumentException($option);
	}

	/*
	 * Returns hours/days/weeks/months
	 */
	public function get_duration_type(){
		return $this->durationType;
	}

	public function get_duration_value(){
		return $this->durationValue;
	}
}

}