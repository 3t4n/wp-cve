<?php

class Eprolo_Pod_Options_Manager {

	public function getOptionNamePrefix() {
		return get_class( $this ) . '_';
	}

	public function getOptionMetaData() {
		return array();
	}

	/**
	 * Array of string name of options
	 */
	public function getOptionNames() {
		return array_keys( $this->getOptionMetaData() );
	}

	/**
	 * Override this method to initialize options to default values and save to the database with add_option
	 */
	protected function initOptions() {
	}

	public function addOption( $optionName, $value ) {
		$prefixedOptionName = $this->prefix( $optionName ); // how it is stored in DB
		return add_option( $prefixedOptionName, $value );
	}

	/**
	 * Just returns the class name. Override this method to return something more readable
	 */
	public function getPluginDisplayName() {
		return get_class( $this );
	}

	/**
	 * Get the prefixed version input $name suitable for storing in WP options
	 * Idempotent: if $optionName is already prefixed, it is not prefixed again, it is returned without change
	 */
	public function prefix( $name ) {
		$optionNamePrefix = $this->getOptionNamePrefix();
		if ( strpos( $name, $optionNamePrefix ) === 0 ) {
			return $name; // already prefixed
		}
		return $optionNamePrefix . $name;
	}

	/**
	 * Remove the prefix from the input $name.
	 * Idempotent: If no prefix found, just returns what was input.
	 */
	public function &unPrefix( $name ) {
		$optionNamePrefix = $this->getOptionNamePrefix();
		if ( strpos( $name, $optionNamePrefix ) === 0 ) {
			$val = substr( $name, strlen( $optionNamePrefix ) );
			return $val;
		}
		return $name;
	}

	/**
	 * A wrapper function delegating to WP get_option() but it prefixes the input $optionName
	 * To enforce "scoping" the options in the WP options table thereby avoiding name conflicts
	 * if option is not set.
	 */
	public function getOption( $optionName, $default = null ) {
		$retVal = get_option( $optionName );
		if ( ! $retVal && $default ) {
			$retVal = $default;
		}
		return $retVal;
	}

	public function get_eprolo_version() {
		return $this->getOption( 'eprolo_pod__version' );
	}

	/**
	 * A wrapper function delegating to WP delete_option() but it prefixes the input $optionName
	 * To enforce "scoping" the options in the WP options table thereby avoiding name conflicts
	 */
	public function deleteOption( $optionName ) {
		return delete_option( $optionName );
	}



	public function getRoleOption( $optionName ) {
		$roleAllowed = $this->getOption( $optionName );
		if ( ! $roleAllowed || '' == $roleAllowed ) {
			$roleAllowed = 'Administrator';
		}
		return $roleAllowed;
	}


	/**
	 * Retrieve the url of the plugin
	 */
	public function getUrl() {
		return \plugin_dir_url( __FILE__ );
	}

	public function updateOption( $optionName, $value ) {
		return update_option( $optionName, $value );
	}



	public function registerSettings() {
		$settingsGroup = get_class( $this ) . '-settings-group';
		$optionMetaData = $this->getOptionMetaData();
		foreach ( $optionMetaData as $aOptionKey => $aOptionMeta ) {
			register_setting( $settingsGroup, $aOptionMeta );
		}
	}
	public function curlPost( $url ) {
		$argc = array( 'sslverify' => false );
		$response = wp_remote_get( EPROLO_POD_ORIGIN . $url, $argc );
		$body = wp_remote_retrieve_body( $response );
		return $body;
	}


	/**
	 * Creates HTML for the Administration page to set options for this plugin.
	 * Override this method to create a customized page.
	 */
	public function settingsPage() {
		$aplugin = new Eprolo_Pod_Options_Manager();
		wp_enqueue_script( 'startup', $aplugin->getUrl() . 'js/startup.js', array( 'jquery' ), $aplugin->get_eprolo_version(), true );
		wp_localize_script( 'startup', 'ajax_startup', array( 'ajaxUrl' => admin_url( 'admin-ajax.php' ) ) );
		wp_enqueue_script( 'bootstrap', $aplugin->getUrl() . 'js/bootstrap.min.js', array( 'jquery' ), $aplugin->get_eprolo_version(), true );
		wp_enqueue_style( 'bootstrapCss', $aplugin->getUrl() . 'css/bootstrap.min.css', '', $aplugin->get_eprolo_version(), 'all' );
		wp_enqueue_style( 'custom', $aplugin->getUrl() . 'css/main.css', '', $aplugin->get_eprolo_version(), 'all' );
		$eprolo_pod_store_token = $aplugin->getOption( 'eprolo_pod_store_token' );
		$eprolo_pod_connected   = $aplugin->getOption( 'eprolo_pod_connected' );
		$eprolo_pod_shop_url = $aplugin->getOption( 'eprolo_pod_shop_url' );
		$eprolo_pod_user_id = $aplugin->getOption( 'eprolo_pod_user_id' );
		$url = $aplugin->getUrl();

		?>
		
			<input type="hidden" id="eprolo_pod_store_token" value="<?php echo esc_attr( $eprolo_pod_store_token ); ?>" />
			<input type="hidden" id="eprolo_pod_connected" value="<?php echo esc_attr( $eprolo_pod_connected ); ?>" />
			<input type="hidden" id="eprolo_pod_shop_url" value="<?php echo esc_attr( $eprolo_pod_shop_url ); ?>" />
			<input type="hidden" id="eprolo_pod_user_id" value="<?php echo esc_attr( $eprolo_pod_user_id ); ?>" />
			<input type="hidden" id="eprolo_pod_file_url" value="<?php echo esc_attr( $url ); ?>" />
			<?php wp_nonce_field( 'eprolo_pod_form_action', 'eprolo_pod_nonce_field' ); ?>
			<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist" style="padding-top:30px">
			<img  width="200px" src="https://inkedjoy.com/_nuxt/img/logo.7f566fa.svg" >
			</ul>
			
			<ul class="nav nav-pills mb-3" id="Go_to_EPROLO_POD_DIV" role="tablist" style="padding-top:10px;display: none;">
				<p id="Go_to_EPROL_tip" style="width: 100%;">Your store has been successfully connected to your Inkedjoy account.
				<br>
				<a style="margin-top:10px;margin-bottom:20px" href="https://www.eprolo.com/project/fix-errors-when-installing-eprolo-to-woocommerce-store/" target="_blank">How to fix errors when installing Inkedjoy to WooCommerce store?</a>
				</p>
				<li class="nav-item">
						<a class="nav-link active" id="go_to_url" href="<?php echo esc_attr( EPROLO_POD_ORIGIN ); ?>prod-api/woocommerce/WAuthToken.html?token=<?php echo esc_attr( $eprolo_pod_store_token ); ?>&domain=<?php echo esc_attr( $eprolo_pod_shop_url ); ?>  " target="_blank" aria-selected="true">Go to Inkedjoy</a>
				</li>
			</ul>
			
			<ul class="nav nav-pills mb-3"  id="POD_DisconnectIV"  role="tablist" style="padding-top:20px">
				<li class="nav-item">
					<a class="nav-link active"  href="javascript:void(0);"  onClick="eprolo_pod_disconnect()" aria-selected="true">Disconnect from  Inkedjoy</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="javascript:void(0);"   onClick="eprolo_pod_reflsh()">Refresh</a>
				</li>
			</ul> 
			
			
			<div  id="eprolo_pod_connect_keyDIV" style="display: none;" >
				<ul class="nav nav-pills mb-3"  role="tablist" style="padding-top:10px;margin-top:30px;">
				<li class="nav-item">
						<a class="nav-link active"   id="Connect_to_EPROLO_POD"  href=""  target="_blank"  >Connect to Inkedjoy POD</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="javascript:void(0);"   onClick="eprolo_pod_reflsh()">Refresh</a>
				</li>
				</ul>
			
				<ul class="nav nav-pills mb-3"  role="tablist" style="padding-top:10px;margin-top:30px;" >
					<div style="width: 100%;">Before connect to Inkedjoy POD, the following conditions must be met:</div>
					<div style="width: 100%;">(1) WooCommerce plugin has been installed and activated;</div>
					<div style="width: 100%;">(2) Set permalinks to anything other than "Plain" in Settings > Permalinks;</div>
					<div style="width: 100%;">(3) Your website must be an SSL connection.</div><br>
				<a style="margin-top:10px;margin-bottom:20px" href="https://www.eprolo.com/project/install-inkedjoy-app-to-woocommerce-store/" target="_blank">How to install Inkedjoy POD APP to your Woocommerce store?</a>
				<br>
					<!-- <p style="width: 100%;">Already have an Inkedjoy account? Please enter Auth key to link the store.</p>
				<li class="nav-item" >
					<input type="text" style="padding: 5px; margin-right: 20px; width: 500px;" id="eprolo_pod_connect_key" value=""  />
				</li>
				<li class="nav-item">
						<a class="nav-link active"  href="javascript:void(0);"   onClick="eprolo_pod_connect_key()">Connect with Auth key</a>
				</li> -->
				</ul>
			</div>
		<?php
		$this->ad_tempalte();

	}

	public function ad_tempalte() {
		?>
			<div class="ep-body">
				<div class="ep-logo-container"><div class="ep-logo"></div></div>
				<p class="ep-desc1">Free Dropshipping Platform</p>
				<p class="ep-desc2">Provide One-stop Solution for Dropshipping Store Owners</p>
				<div class="ep-list">
					<div class="ep-row" style="margin-left: -12px;margin-right: -12px;">
						<div class="ep-col" style="padding-left: 12px;padding-right: 12px;">
							<div class="ep-ad-box">
								<div class="ep-row is-middle" style="margin-left: -10px;margin-right: -10px;">
									<div class="ep-col ep-col-auto" style="padding-left: 10px;padding-right: 10px;">
										<div class="ep-eprolo-logo"></div>
									</div>
									<div class="ep-col ep-col-100" style="padding-left: 10px;padding-right: 10px;">
										<p class="title">EPROLO</p>
										<p class="title-desc">Dropshipping Partner & Sourcing Agent in China</p>
									</div>
								</div>
							<!-- <div class="ep-row is-middle ep-ad-other" style="margin-left: -10px;margin-right: -10px;">
									<div class="ep-col ep-col-auto ep-other-logo" style="padding-left: 10px;padding-right: 10px;">
										<div class="ep-woo-logo"></div>
									</div>
									<div class="ep-col ep-col-100" style="padding-left: 10px;padding-right: 10px;">
										<p class="text"><a class="ep-link" href="https://wordpress.org/plugins/eprolo-dropshipping/" target="_blank">View plug-in details</a></p>
									</div>
								</div>  --> 
								<div class="ep-row is-middle ep-ad-other" style="margin-left: -10px;margin-right: -10px;">
									<div class="ep-col ep-col-auto ep-other-logo" style="padding-left: 10px;padding-right: 10px;">
										<div class="ep-woo-logo"></div>
									</div>
									<div class="ep-col ep-col-100" style="padding-left: 10px;padding-right: 10px;">
										<p class="text"><a class="ep-link" href="https://wordpress.org/support/plugin/eprolo-dropshipping/reviews/" target="_blank">Leaving EPROLO APP a Postive Review</a></p>
									</div>
								</div>
								<div class="ep-row is-middle ep-ad-other" style="margin-left: -10px;margin-right: -10px;">
									<div class="ep-col ep-col-auto ep-other-logo" style="padding-left: 10px;padding-right: 10px;">
										<div class="ep-i-logo"></div>
									</div>
									<div class="ep-col ep-col-100" style="padding-left: 10px;padding-right: 10px;">
										<p class="text"><a class="ep-link" href="https://www.eprolo.com/" target="_blank">Go to Eprolo official website</a></p>
									</div>
								</div>
							</div>
						</div>
						<div class="ep-col" style="padding-left: 12px;padding-right: 12px;">
							<div class="ep-ad-box">
								<div class="ep-row is-middle" style="margin-left: -10px;margin-right: -10px;">
									<div class="ep-col ep-col-auto" style="padding-left: 10px;padding-right: 10px;">
										<div class="ep-eprolo-pod-logo"></div>
									</div>
									<div class="ep-col ep-col-100" style="padding-left: 10px;padding-right: 10px;">
										<p class="title">Inkedjoy POD</p>
										<p class="title-desc">Skyrocket Business Value with Competitive Price</p>
									</div>
								</div>
							<!-- 	<div class="ep-row is-middle ep-ad-other" style="margin-left: -10px;margin-right: -10px;">
									<div class="ep-col ep-col-auto ep-other-logo" style="padding-left: 10px;padding-right: 10px;">
										<div class="ep-woo-logo"></div>
									</div>
									<div class="ep-col ep-col-100" style="padding-left: 10px;padding-right: 10px;">
										<p class="text"><a class="ep-link" href="" target="_blank">View plug-in details</a></p>
									</div>
								</div>  -->
								<div class="ep-row is-middle ep-ad-other" style="margin-left: -10px;margin-right: -10px;">
									<div class="ep-col ep-col-auto ep-other-logo" style="padding-left: 10px;padding-right: 10px;">
										<div class="ep-woo-logo"></div>
									</div>
									<div class="ep-col ep-col-100" style="padding-left: 10px;padding-right: 10px;">
										<p class="text"><a class="ep-link" href="https://wordpress.org/support/plugin/eprolo-pod-dropshipping/reviews/" target="_blank">Leaving Inkedjoy APP a Postive Review</a></p>
									</div>
								</div>
								<div class="ep-row is-middle ep-ad-other" style="margin-left: -10px;margin-right: -10px;">
									<div class="ep-col ep-col-auto ep-other-logo" style="padding-left: 10px;padding-right: 10px;">
										<div class="ep-i-logo"></div>
									</div>
									<div class="ep-col ep-col-100" style="padding-left: 10px;padding-right: 10px;">
										<p class="text"><a class="ep-link" href="https://inkedjoy.com/" target="_blank">Go to Inkedjoy official website</a></p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php
	}
}
