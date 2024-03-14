<?php

/**
 * Copyright (c) 2024 iControlWP <support@icontrolwp.com>
 * All rights reserved.
 *
 * "iControlWP" is distributed under the GNU General Public License, Version 2,
 * June 1991. Copyright (C) 1989, 1991 Free Software Foundation, Inc., 51 Franklin
 * St, Fifth Floor, Boston, MA 02110, USA
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
 * ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */
class ICWP_APP_Plugin_Controller extends ICWP_APP_Foundation {

	/**
	 * @var stdClass
	 */
	private static $oControllerOptions;

	/**
	 * @var ICWP_APP_Plugin_Controller
	 */
	public static $oInstance;

	/**
	 * @var string
	 */
	private static $sRootFile;

	/**
	 * @var bool
	 */
	protected $bRebuildOptions;

	/**
	 * @var bool
	 */
	protected $bForceOffState;

	/**
	 * @var bool
	 */
	protected $bResetPlugin;

	/**
	 * @var string
	 */
	private $sPluginUrl;

	/**
	 * @var string
	 */
	private $sPluginBaseFile;

	/**
	 * @var array
	 */
	private $aRequirementsMessages;

	/**
	 * @var string
	 */
	protected static $sSessionId;

	/**
	 * @var string
	 */
	protected static $sRequestId;

	/**
	 * @var string
	 */
	private $sConfigOptionsHashWhenLoaded;

	/**
	 * @var bool
	 */
	protected $bMeetsBasePermissions = false;

	private $modules = [];

	/**
	 * @param $rootFile
	 * @return ICWP_APP_Plugin_Controller
	 */
	public static function GetInstance( $rootFile ) {
		if ( !isset( self::$oInstance ) ) {
			try {
				self::$oInstance = new self( $rootFile );
			}
			catch ( \Exception $e ) {
				return null;
			}
		}
		return self::$oInstance;
	}

	/**
	 * @param string $rootFile
	 * @throws \Exception
	 */
	private function __construct( $rootFile ) {
		self::$sRootFile = $rootFile;
		$this->checkMinimumRequirements();
		add_action( 'plugins_loaded', [ $this, 'onWpPluginsLoaded' ], 0 );
	}

	/**
	 * @return array
	 * @throws \Exception
	 */
	private function readPluginSpecification() {
		$spec = [];
		$contents = $this->loadDP()->readFileContentsUsingInclude( $this->getPathPluginSpec() );
		if ( !empty( $contents ) ) {
			$spec = \json_decode( $contents, true );
			if ( empty( $spec ) ) {
				throw new \Exception( 'Could not json_decode the plugin spec configuration.' );
			}
		}
		return $spec;
	}

	/**
	 * @param bool $checkOnlyFrontEnd
	 * @throws \Exception
	 */
	private function checkMinimumRequirements( $checkOnlyFrontEnd = true ) {
		if ( $checkOnlyFrontEnd && !is_admin() ) {
			return;
		}

		$meetsRequirements = true;
		$reqsMessages = $this->getRequirementsMessages();

		$sMinimumPhp = $this->getPluginSpec_Requirement( 'php' );
		if ( !empty( $sMinimumPhp ) ) {
			if ( \version_compare( \phpversion(), $sMinimumPhp, '<' ) ) {
				$reqsMessages[] = sprintf( 'PHP does not meet minimum version. Your version: %s.  Required Version: %s.', \PHP_VERSION, $sMinimumPhp );
				$meetsRequirements = false;
			}
		}

		$minimumWp = $this->getPluginSpec_Requirement( 'wordpress' );
		if ( !empty( $minimumWp ) ) {
			$WPversion = $this->loadWP()->getWordpressVersion();
			if ( \version_compare( $WPversion, $minimumWp, '<' ) ) {
				$reqsMessages[] = sprintf( 'WordPress does not meet minimum version. Your version: %s.  Required Version: %s.', $WPversion, $minimumWp );
				$meetsRequirements = false;
			}
		}

		if ( !$meetsRequirements ) {
			$this->aRequirementsMessages = $reqsMessages;
			add_action( 'admin_menu', [ $this, 'adminNoticeDoesNotMeetRequirements' ] );
			add_action( 'network_admin_notices', [ $this, 'adminNoticeDoesNotMeetRequirements' ] );
			throw new \Exception( 'Plugin does not meet minimum requirements' );
		}
	}

	public function adminNoticeDoesNotMeetRequirements() {
		$aMessages = $this->getRequirementsMessages();
		if ( !empty( $aMessages ) && is_array( $aMessages ) ) {
			$aDisplayData = [
				'strings' => [
					'requirements'     => $aMessages,
					'summary_title'    => sprintf( 'Web Hosting requirements for Plugin "%s" are not met and you should deactivate the plugin.', $this->getHumanName() ),
					'more_information' => 'Click here for more information on requirements'
				],
				'hrefs'   => [
					'more_information' => sprintf( 'https://wordpress.org/plugins/%s/faq', $this->getTextDomain() )
				]
			];

			$this->loadRenderer( $this->getPath_Templates() )
				 ->setTemplate( 'notices/does-not-meet-requirements' )
				 ->setRenderVars( $aDisplayData )
				 ->display();
		}
	}

	/**
	 * @return array
	 */
	protected function getRequirementsMessages() {
		if ( !isset( $this->aRequirementsMessages ) ) {
			$this->aRequirementsMessages = [];
		}
		return $this->aRequirementsMessages;
	}

	/**
	 * Registers the plugins activation, deactivate and uninstall hooks.
	 */
	protected function registerActivationHooks() {
		register_deactivation_hook( $this->getRootFile(), [ $this, 'onWpDeactivatePlugin' ] );
	}

	public function onWpDeactivatePlugin() {
		$oFS = $this->loadFS();

		$sTmpDir = $this->getPath_PluginCache();
		if ( $oFS->isDir( $sTmpDir ) ) {
			$oFS->deleteDir( $sTmpDir );
		}

		if ( current_user_can( $this->getBasePermissions() ) && apply_filters( $this->doPluginPrefix( 'delete_on_deactivate' ), false ) ) {
			do_action( $this->doPluginPrefix( 'delete_plugin' ) );
			$this->deletePluginControllerOptions();
		}
	}

	public function onWpPluginsLoaded() {
		$this->doLoadTextDomain();
		$this->doRegisterHooks();
	}

	protected function doRegisterHooks() {
		$this->registerActivationHooks();

		add_action( 'init', [ $this, 'onWpInit' ] );
		add_action( 'admin_init', [ $this, 'onWpAdminInit' ] );
		add_action( 'wp_loaded', [ $this, 'onWpLoaded' ] );

		add_action( 'admin_menu', [ $this, 'onWpAdminMenu' ] );
		add_action( 'network_admin_menu', [ $this, 'onWpAdminMenu' ] );

		add_filter( 'all_plugins', [ $this, 'filter_hidePluginFromTableList' ] );
		add_filter( 'all_plugins', [ $this, 'doPluginLabels' ] );
		add_filter( 'plugin_action_links_'.$this->getPluginBaseFile(), [ $this, 'onWpPluginActionLinks' ], 50 );
		add_filter( 'plugin_row_meta', [ $this, 'onPluginRowMeta' ], 50, 2 );
		add_filter( 'site_transient_update_plugins', [ $this, 'filter_hidePluginUpdatesFromUI' ] );
		add_action( 'in_plugin_update_message-'.$this->getPluginBaseFile(), [ $this, 'onWpPluginUpdateMessage' ] );

		add_filter( 'auto_update_plugin', [ $this, 'onWpAutoUpdate' ], 500, 2 );
		add_filter( 'set_site_transient_update_plugins', [ $this, 'setUpdateFirstDetectedAt' ] );

		add_action( 'shutdown', [ $this, 'onWpShutdown' ] );
		add_action( 'wp_logout', [ $this, 'onWpLogout' ] );

		// outsource the collection of admin notices
		if ( is_admin() ) {
			$this->loadAdminNoticesProcessor()->setActionPrefix( $this->doPluginPrefix() );
		}
	}

	public function onWpAdminInit() {
		if ( $this->getPluginSpec_Property( 'show_dashboard_widget' ) === true ) {
			add_action( 'wp_dashboard_setup', [ $this, 'onWpDashboardSetup' ] );
		}
		add_action( 'admin_enqueue_scripts', [ $this, 'onWpEnqueueAdminCss' ], 100 );
		add_action( 'admin_enqueue_scripts', [ $this, 'onWpEnqueueAdminJs' ], 99 );
	}

	public function onWpLoaded() {
		if ( $this->getIsValidAdminArea() ) {
			$this->doPluginFormSubmit();
		}
	}

	public function onWpInit() {
		add_action( 'wp_enqueue_scripts', [ $this, 'onWpEnqueueFrontendCss' ], 99 );
		$this->bMeetsBasePermissions = current_user_can( $this->getBasePermissions() );
	}

	public function onWpAdminMenu() {
		if ( $this->getIsValidAdminArea() ) {
			$this->createPluginMenu();
		}
	}

	public function onWpDashboardSetup() {
		if ( $this->getIsValidAdminArea() ) {
			wp_add_dashboard_widget(
				$this->doPluginPrefix( 'dashboard_widget' ),
				apply_filters( $this->doPluginPrefix( 'dashboard_widget_title' ), $this->getHumanName() ),
				[ $this, 'displayDashboardWidget' ]
			);
		}
	}

	public function displayDashboardWidget() {
		$aContent = apply_filters( $this->doPluginPrefix( 'dashboard_widget_content' ), [] );
		echo implode( '', $aContent );
	}

	/**
	 * v5.4.1: Nasty looping bug in here where this function was called within the 'user_has_cap' filter
	 * so we removed the "current_user_can()" or any such sub-call within this function
	 * @return bool
	 */
	public function getHasPermissionToManage() {
		if ( apply_filters( $this->doPluginPrefix( 'bypass_permission_to_manage' ), false ) ) {
			return true;
		}
		return ( $this->getMeetsBasePermissions() && apply_filters( $this->doPluginPrefix( 'has_permission_to_manage' ), true ) );
	}

	/**
	 * Must be simple and cannot contain anything that would call filter "user_has_cap", e.g. current_user_can()
	 * @return bool
	 */
	public function getMeetsBasePermissions() {
		return $this->bMeetsBasePermissions;
	}

	public function getHasPermissionToView() {
		return $this->getHasPermissionToManage(); // TODO: separate view vs manage
	}

	/**
	 * @return bool
	 */
	protected function createPluginMenu() {

		$bHideMenu = apply_filters( $this->doPluginPrefix( 'filter_hidePluginMenu' ), !$this->getPluginSpec_Menu( 'show' ) );
		if ( $bHideMenu ) {
			return true;
		}

		if ( $this->getPluginSpec_Menu( 'top_level' ) ) {

			$aPluginLabels = $this->getPluginLabels();

			$sMenuTitle = $this->getPluginSpec_Menu( 'title' );
			if ( is_null( $sMenuTitle ) ) {
				$sMenuTitle = $this->getHumanName();
			}

			$sMenuIcon = $this->getPluginUrl_Image( $this->getPluginSpec_Menu( 'icon_image' ) );
			$sIconUrl = empty( $aPluginLabels[ 'icon_url_16x16' ] ) ? $sMenuIcon : $aPluginLabels[ 'icon_url_16x16' ];

			$sFullParentMenuId = $this->getPluginPrefix();
			add_menu_page(
				$this->getHumanName(),
				$sMenuTitle,
				$this->getBasePermissions(),
				$sFullParentMenuId,
				[ $this, $this->getPluginSpec_Menu( 'callback' ) ],
				$sIconUrl
			);

			if ( $this->getPluginSpec_Menu( 'has_submenu' ) ) {

				$aPluginMenuItems = apply_filters( $this->doPluginPrefix( 'filter_plugin_submenu_items' ), [] );
				if ( !empty( $aPluginMenuItems ) ) {
					foreach ( $aPluginMenuItems as $sMenuTitle => $aMenu ) {
						list( $sMenuItemText, $sMenuItemId, $aMenuCallBack ) = $aMenu;
						add_submenu_page(
							$sFullParentMenuId,
							$sMenuTitle,
							$sMenuItemText,
							$this->getBasePermissions(),
							$sMenuItemId,
							$aMenuCallBack
						);
					}
				}
			}

			if ( $this->getPluginSpec_Menu( 'do_submenu_fix' ) ) {
				$this->fixSubmenu();
			}
		}
		return true;
	}

	protected function fixSubmenu() {
		global $submenu;
		$sFullParentMenuId = $this->getPluginPrefix();
		if ( isset( $submenu[ $sFullParentMenuId ] ) ) {
			unset( $submenu[ $sFullParentMenuId ][ 0 ] );
		}
	}

	/**
	 * Displaying all views now goes through this central function and we work out
	 * what to display based on the name of current hook/filter being processed.
	 */
	public function onDisplayTopMenu() {
	}

	/**
	 * @param array  $meta
	 * @param string $pluginFile
	 * @return array
	 */
	public function onPluginRowMeta( $meta, $pluginFile ) {
		if ( $pluginFile == $this->getPluginBaseFile() ) {
			$template = '<strong><a href="%s" target="_blank">%s</a></strong>';
			foreach ( $this->getPluginSpec_PluginMeta() as $linkData ) {
				$link = sprintf( $template, $linkData[ 'href' ], $linkData[ 'name' ] );
				$meta[] = $link;
			}
		}
		return $meta;
	}

	/**
	 * @param array $actionLinks
	 * @return array
	 */
	public function onWpPluginActionLinks( $actionLinks ) {

		if ( $this->getIsValidAdminArea() ) {

			$linksToAdd = $this->getPluginSpec_ActionLinks( 'add' );
			if ( \is_array( $linksToAdd ) ) {

				$template = '<a href="%s" target="%s">%s</a>';
				foreach ( $linksToAdd as $link ) {
					if ( empty( $link[ 'name' ] ) || ( empty( $link[ 'url_method_name' ] ) && empty( $link[ 'href' ] ) ) ) {
						continue;
					}

					if ( !empty( $link[ 'url_method_name' ] ) ) {
						$method = $link[ 'url_method_name' ];
						if ( \method_exists( $this, $method ) ) {
							$settingsLink = sprintf( $template, $this->{$method}(), "_top", $link[ 'name' ] );
							\array_unshift( $actionLinks, $settingsLink );
						}
					}
					elseif ( !empty( $link[ 'href' ] ) ) {
						$settingsLink = sprintf( $template, $link[ 'href' ], "_blank", $link[ 'name' ] );
						\array_unshift( $actionLinks, $settingsLink );
					}
				}
			}
		}
		return $actionLinks;
	}

	public function onWpEnqueueFrontendCss() {
		$frontend = $this->getPluginSpec_Include( 'frontend' );
		if ( !empty( $frontend[ 'css' ] ) && is_array( $frontend[ 'css' ] ) ) {
			foreach ( $frontend[ 'css' ] as $asset ) {
				$uniq = $this->doPluginPrefix( $asset );
				wp_register_style( $uniq, $this->getPluginUrl_Css( $asset.'.css' ), ( empty( $dependent ) ? false : $dependent ), $this->getVersion() );
				wp_enqueue_style( $uniq );
				$dependent = $uniq;
			}
		}
	}

	public function onWpEnqueueAdminJs() {
		$includeTypes = \array_filter( [
			'admin'        => $this->getIsValidAdminArea(),
			'plugin_admin' => $this->getIsPage_PluginAdmin(),
		] );

		foreach ( \array_keys( $includeTypes ) as $type ) {
			$aAdminJs = $this->getPluginSpec_Include( $type );
			if ( !empty( $aAdminJs[ 'js' ] ) && \is_array( $aAdminJs[ 'js' ] ) ) {
				$sDependent = false;
				foreach ( $aAdminJs[ 'js' ] as $asset ) {
					$sUrl = $this->getPluginUrl_Js( $asset.'.js' );
					if ( !empty( $sUrl ) ) {
						$sUnique = $this->doPluginPrefix( $asset );
						wp_register_script( $sUnique, $sUrl, $sDependent, $this->getVersion().rand() );
						wp_enqueue_script( $sUnique );
						$sDependent = $sUnique;
					}
				}
			}
		}
	}

	public function onWpEnqueueAdminCss() {
		$includeTypes = \array_filter( [
			'admin'        => $this->getIsValidAdminArea(),
			'plugin_admin' => $this->getIsPage_PluginAdmin(),
		] );

		foreach ( \array_keys( $includeTypes ) as $type ) {
			$css = $this->getPluginSpec_Include( $type );
			if ( !empty( $css[ 'css' ] ) && \is_array( $css[ 'css' ] ) ) {
				$dependent = false;
				foreach ( $css[ 'css' ] as $asset ) {
					$sUrl = $this->getPluginUrl_Css( $asset.'.css' );
					if ( !empty( $sUrl ) ) {
						$sUnique = $this->doPluginPrefix( $asset );
						wp_register_style( $sUnique, $sUrl, $dependent, $this->getVersion().rand() );
						wp_enqueue_style( $sUnique );
						$dependent = $sUnique;
					}
				}
			}
		}
	}

	/**
	 * Displays a message in the plugins listing when a plugin has an update available.
	 */
	public function onWpPluginUpdateMessage() {
		$default = sprintf( 'Upgrade Now To Get The Latest Available %s Features.', $this->getHumanName() );
		$msg = apply_filters( $this->doPluginPrefix( 'plugin_update_message' ), $default );
		if ( empty( $msg ) ) {
			$msg = '';
		}
		else {
			$msg = sprintf( '<div class="%s plugin_update_message">%s</div>', $this->getPluginPrefix(), $msg );
		}
		echo $msg;
	}

	/**
	 * This will hook into the saving of plugin update information and if there is an update for this plugin, it'll add
	 * a data stamp to state when the update was first detected.
	 * @param \stdClass $updateData
	 * @return \stdClass
	 */
	public function setUpdateFirstDetectedAt( $updateData ) {

		if ( !empty( $updateData ) && !empty( $updateData->response ) && isset( $updateData->response[ $this->getPluginBaseFile() ] ) ) {
			// i.e. there's an update available
			$newVersion = $this->loadWP()->getPluginUpdateNewVersion( $this->getPluginBaseFile() );
			if ( !empty( $newVersion ) ) {
				$conOpts = $this->getPluginControllerOptions();
				if ( !isset( $conOpts->update_first_detected ) || ( count( $conOpts->update_first_detected ) > 3 ) ) {
					$conOpts->update_first_detected = [];
				}
				if ( !isset( $conOpts->update_first_detected[ $newVersion ] ) ) {
					$conOpts->update_first_detected[ $newVersion ] = $this->loadDP()->time();
				}

				// a bit of cleanup to remove the old-style entries which would gather foreva-eva
				foreach ( $conOpts as $key => $data ) {
					if ( \strpos( $key, 'update_first_detected_' ) !== false ) {
						unset( $conOpts->{$key} );
					}
				}
			}
		}

		return $updateData;
	}

	/**
	 * This is a filter method designed to say whether WordPress plugin upgrades should be permitted,
	 * based on the plugin settings.
	 * @param bool          $doUpdate
	 * @param string|object $mItem
	 * @return bool
	 */
	public function onWpAutoUpdate( $doUpdate, $mItem ) {
		$WP = $this->loadWP();

		// The item in question is this plugin...
		if ( $WP->getFileFromAutomaticUpdateItem( $mItem ) === $this->getPluginBaseFile() ) {
			$sAutoupdateSpec = $this->getPluginSpec_Property( 'autoupdate' );

			$oConOptions = $this->getPluginControllerOptions();

			if ( !$WP->getIsRunningAutomaticUpdates() && $sAutoupdateSpec == 'confidence' ) {
				$sAutoupdateSpec = 'yes'; // so that we appear to be automatically updating
			}

			switch ( $sAutoupdateSpec ) {

				case 'yes' :
					$doUpdate = true;
					break;

				case 'block' :
					$doUpdate = false;
					break;

				case 'confidence' :
					$doUpdate = false;
					$newVersion = $WP->getPluginUpdateNewVersion( $this->getPluginBaseFile() );
					if ( !empty( $newVersion ) ) {
						$firstDetected = isset( $oConOptions->update_first_detected[ $newVersion ] ) ? $oConOptions->update_first_detected[ $newVersion ] : 0;
						$updateAvailableFor = $this->loadDP()->time() - $firstDetected;
						$doUpdate = ( $firstDetected > 0 && ( $updateAvailableFor > DAY_IN_SECONDS ) );
					}
					break;

				case 'pass' :
				default:
					break;
			}
		}
		return $doUpdate;
	}

	/**
	 * @param array $plugins
	 * @return array
	 */
	public function doPluginLabels( $plugins ) {
		$labels = $this->getPluginLabels();
		if ( empty( $labels ) ) {
			return $plugins;
		}

		$file = $this->getPluginBaseFile();
		// For this plugin, overwrite any specified settings
		if ( \array_key_exists( $file, $plugins ) ) {
			foreach ( $labels as $sLabelKey => $sLabel ) {
				$plugins[ $file ][ $sLabelKey ] = $sLabel;
			}
		}

		return $plugins;
	}

	/**
	 * @return array
	 */
	public function getPluginLabels() {
		return apply_filters( $this->doPluginPrefix( 'plugin_labels' ), $this->getPluginSpec_Labels() );
	}

	/**
	 * Hooked to 'shutdown'
	 */
	public function onWpShutdown() {
		do_action( $this->doPluginPrefix( 'pre_plugin_shutdown' ) );
		do_action( $this->doPluginPrefix( 'plugin_shutdown' ) );
		$this->saveCurrentPluginControllerOptions();
		$this->deleteFlags();
	}

	public function onWpLogout() {
		if ( $this->hasSessionId() ) {
			$this->clearSession();
		}
	}

	protected function deleteFlags() {
		$oFS = $this->loadFS();
		if ( $oFS->exists( $this->getPath_Flags( 'rebuild' ) ) ) {
			$oFS->deleteFile( $this->getPath_Flags( 'rebuild' ) );
		}
		if ( $this->getIsResetPlugin() ) {
			$oFS->deleteFile( $this->getPath_Flags( 'reset' ) );
		}
	}

	/**
	 * Added to a WordPress filter ('all_plugins') which will remove this particular plugin from the
	 * list of all plugins based on the "plugin file" name.
	 * @param array $aPlugins
	 * @return array
	 */
	public function filter_hidePluginFromTableList( $aPlugins ) {

		$bHide = apply_filters( $this->doPluginPrefix( 'hide_plugin' ), false );
		if ( !$bHide ) {
			return $aPlugins;
		}

		$sPluginBaseFileName = $this->getPluginBaseFile();
		if ( isset( $aPlugins[ $sPluginBaseFileName ] ) ) {
			unset( $aPlugins[ $sPluginBaseFileName ] );
		}
		return $aPlugins;
	}

	/**
	 * Added to the WordPress filter ('site_transient_update_plugins') in order to remove visibility of updates
	 * from the WordPress Admin UI.
	 * In order to ensure that WordPress still checks for plugin updates it will not remove this plugin from
	 * the list of plugins if DOING_CRON is set to true.
	 * @param StdClass $oPlugins
	 * @return StdClass
	 * @uses $this->fHeadless if the plugin is headless, it is hidden
	 */
	public function filter_hidePluginUpdatesFromUI( $oPlugins ) {

		if ( $this->loadWP()->getIsCron() ) {
			return $oPlugins;
		}
		if ( !apply_filters( $this->doPluginPrefix( 'hide_plugin_updates' ), false ) ) {
			return $oPlugins;
		}
		if ( isset( $oPlugins->response[ $this->getPluginBaseFile() ] ) ) {
			unset( $oPlugins->response[ $this->getPluginBaseFile() ] );
		}
		return $oPlugins;
	}

	protected function doLoadTextDomain() {
		return load_plugin_textdomain(
			$this->getTextDomain(),
			false,
			plugin_basename( $this->getPath_Languages() )
		);
	}

	/**
	 * @return bool
	 */
	protected function doPluginFormSubmit() {
		if ( !$this->getIsPluginFormSubmit() ) {
			return false;
		}

		// do all the plugin feature/options saving
		do_action( $this->doPluginPrefix( 'form_submit' ) );

		if ( $this->getIsPage_PluginAdmin() ) {
			$oWp = $this->loadWP();
			$oWp->doRedirect( $oWp->getUrl_CurrentAdminPage() );
		}
		return true;
	}

	/**
	 * @param string $sSuffix
	 * @param string $sGlue
	 * @return string
	 */
	public function doPluginPrefix( $sSuffix = '', $sGlue = '-' ) {
		$sPrefix = $this->getPluginPrefix( $sGlue );

		if ( $sSuffix == $sPrefix || strpos( $sSuffix, $sPrefix.$sGlue ) === 0 ) { //it already has the full prefix
			return $sSuffix;
		}

		return sprintf( '%s%s%s', $sPrefix, empty( $sSuffix ) ? '' : $sGlue, empty( $sSuffix ) ? '' : $sSuffix );
	}

	/**
	 * @param string $sSuffix
	 * @return string
	 */
	public function doPluginOptionPrefix( $sSuffix = '' ) {
		return $this->doPluginPrefix( $sSuffix, '_' );
	}

	/**
	 * @param string $sKey
	 * @return mixed|null
	 */
	protected function getPluginSpec_ActionLinks( $sKey ) {
		$oConOpts = $this->getPluginControllerOptions();
		return isset( $oConOpts->plugin_spec[ 'action_links' ][ $sKey ] ) ? $oConOpts->plugin_spec[ 'action_links' ][ $sKey ] : null;
	}

	/**
	 * @param string $sKey
	 * @return mixed|null
	 */
	protected function getPluginSpec_Include( $sKey ) {
		$oConOpts = $this->getPluginControllerOptions();
		return isset( $oConOpts->plugin_spec[ 'includes' ][ $sKey ] ) ? $oConOpts->plugin_spec[ 'includes' ][ $sKey ] : null;
	}

	/**
	 * @param string $sKey
	 * @return array|string
	 */
	protected function getPluginSpec_Labels( $sKey = '' ) {
		$oConOpts = $this->getPluginControllerOptions();
		$aLabels = isset( $oConOpts->plugin_spec[ 'labels' ] ) ? $oConOpts->plugin_spec[ 'labels' ] : [];
		//Prep the icon urls
		if ( !empty( $aLabels[ 'icon_url_16x16' ] ) ) {
			$aLabels[ 'icon_url_16x16' ] = $this->getPluginUrl_Image( $aLabels[ 'icon_url_16x16' ] );
		}
		if ( !empty( $aLabels[ 'icon_url_32x32' ] ) ) {
			$aLabels[ 'icon_url_32x32' ] = $this->getPluginUrl_Image( $aLabels[ 'icon_url_32x32' ] );
		}

		if ( empty( $sKey ) ) {
			return $aLabels;
		}

		return isset( $oConOpts->plugin_spec[ 'labels' ][ $sKey ] ) ? $oConOpts->plugin_spec[ 'labels' ][ $sKey ] : null;
	}

	/**
	 * @param string $sKey
	 * @return mixed|null
	 */
	protected function getPluginSpec_Menu( $sKey ) {
		$oConOptions = $this->getPluginControllerOptions();
		return isset( $oConOptions->plugin_spec[ 'menu' ][ $sKey ] ) ? $oConOptions->plugin_spec[ 'menu' ][ $sKey ] : null;
	}

	/**
	 * @param string $key
	 * @return mixed|null
	 */
	protected function getPluginSpec_Path( $key ) {
		$conOpts = $this->getPluginControllerOptions();
		return isset( $conOpts->plugin_spec[ 'paths' ][ $key ] ) ? $conOpts->plugin_spec[ 'paths' ][ $key ] : null;
	}

	/**
	 * @param string $key
	 * @return mixed|null
	 */
	protected function getPluginSpec_Property( $key ) {
		$conOpts = $this->getPluginControllerOptions();
		return isset( $conOpts->plugin_spec[ 'properties' ][ $key ] ) ? $conOpts->plugin_spec[ 'properties' ][ $key ] : null;
	}

	/**
	 * @return array
	 */
	protected function getPluginSpec_PluginMeta() {
		$conOpts = $this->getPluginControllerOptions();
		return ( isset( $conOpts->plugin_spec[ 'plugin_meta' ] ) && is_array( $conOpts->plugin_spec[ 'plugin_meta' ] ) ) ? $conOpts->plugin_spec[ 'plugin_meta' ] : [];
	}

	/**
	 * @param string $sKey
	 * @return mixed|null
	 */
	protected function getPluginSpec_Requirement( $sKey ) {
		$conOpts = $this->getPluginControllerOptions();
		return isset( $conOpts->plugin_spec[ 'requirements' ][ $sKey ] ) ? $conOpts->plugin_spec[ 'requirements' ][ $sKey ] : null;
	}

	/**
	 * @return string
	 */
	public function getBasePermissions() {
		return $this->getPluginSpec_Property( 'base_permissions' );
	}

	/**
	 * @param bool $checkUserPermissions
	 * @return bool
	 */
	public function getIsValidAdminArea( $checkUserPermissions = true ) {
		if ( $checkUserPermissions && did_action( 'init' ) && !current_user_can( $this->getBasePermissions() ) ) {
			return false;
		}

		$WP = $this->loadWP();
		if ( !$WP->isMultisite() && is_admin() ) {
			return true;
		}
		elseif ( $WP->isMultisite() && $this->getIsWpmsNetworkAdminOnly() && is_network_admin() ) {
			return true;
		}
		return false;
	}

	/**
	 * @return string
	 */
	public function getOptionStoragePrefix() {
		return $this->getPluginPrefix( '_' ).'_';
	}

	/**
	 * @param $glue
	 * @return string
	 */
	public function getPluginPrefix( $glue = '-' ) {
		return sprintf( '%s%s%s', $this->getParentSlug(), $glue, $this->getPluginSlug() );
	}

	/**
	 * Default is to take the 'Name' from the labels section but can override with "human_name" from property section.
	 * @return string
	 */
	public function getHumanName() {
		$labels = $this->getPluginLabels();
		return empty( $labels[ 'Name' ] ) ? $this->getPluginSpec_Property( 'human_name' ) : $labels[ 'Name' ];
	}

	/**
	 * @return string
	 */
	public function getIsLoggingEnabled() {
		return $this->getPluginSpec_Property( 'logging_enabled' );
	}

	/**
	 * @return bool
	 */
	public function getIsPage_PluginAdmin() {
		return ( strpos( $this->loadWP()->getCurrentWpAdminPage(), $this->getPluginPrefix() ) === 0 );
	}

	/**
	 * @return bool
	 */
	public function getIsPage_PluginMainDashboard() {
		return ( $this->loadWP()->getCurrentWpAdminPage() == $this->getPluginPrefix() );
	}

	/**
	 * @return bool
	 */
	protected function getIsPluginFormSubmit() {
		if ( empty( $_POST ) && empty( $_GET ) ) {
			return false;
		}

		$formSubmit = [
			$this->doPluginOptionPrefix( 'plugin_form_submit' ),
			'icwp_link_action'
		];

		foreach ( $formSubmit as $option ) {
			if ( !\is_null( $this->loadDP()->FetchRequest( $option ) ) ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * @return bool
	 * @throws \Exception
	 */
	public function getIsRebuildOptionsFromFile() {
		if ( isset( $this->bRebuildOptions ) ) {
			return $this->bRebuildOptions;
		}

		// The first choice is to look for the file hash. If it's "always" empty, it means we could never
		// hash the file in the first place so it's not ever effectively used and it falls back to the rebuild file
		$conOptions = $this->getPluginControllerOptions();
		$specPath = $this->getPathPluginSpec();
		$currentHash = @\md5_file( $specPath );
		$modTime = $this->loadFS()->getModifiedTime( $specPath );

		$this->bRebuildOptions = true;

		if ( isset( $conOptions->hash ) && is_string( $conOptions->hash ) && ( $conOptions->hash == $currentHash ) ) {
			$this->bRebuildOptions = false;
		}
		elseif ( isset( $conOptions->mod_time ) && ( $modTime < $conOptions->mod_time ) ) {
			$this->bRebuildOptions = false;
		}

		$conOptions->hash = $currentHash;
		$conOptions->mod_time = $modTime;
		return $this->bRebuildOptions;
	}

	/**
	 * @return bool
	 */
	public function getIsResetPlugin() {
		if ( !isset( $this->bResetPlugin ) ) {
			$bExists = $this->loadFS()->isFile( $this->getPath_Flags( 'reset' ) );
			$this->bResetPlugin = (bool)$bExists;
		}
		return $this->bResetPlugin;
	}

	/**
	 * @return bool
	 */
	public function getIsWpmsNetworkAdminOnly() {
		return $this->getPluginSpec_Property( 'wpms_network_admin_only' );
	}

	/**
	 * @return string
	 */
	public function getParentSlug() {
		return $this->getPluginSpec_Property( 'slug_parent' );
	}

	/**
	 * This is the path to the main plugin file relative to the WordPress plugins directory.
	 * @return string
	 */
	public function getPluginBaseFile() {
		if ( !isset( $this->sPluginBaseFile ) ) {
			$this->sPluginBaseFile = plugin_basename( $this->getRootFile() );
		}
		return $this->sPluginBaseFile;
	}

	/**
	 * @return string
	 */
	public function getPluginSlug() {
		return $this->getPluginSpec_Property( 'slug_plugin' );
	}

	/**
	 * @param string $sPath
	 * @return string
	 */
	public function getPluginUrl( $sPath = '' ) {
		if ( empty( $this->sPluginUrl ) ) {
			$this->sPluginUrl = plugins_url( '/', $this->getRootFile() );
		}
		return add_query_arg( [ 'ver' => $this->getVersion() ], $this->sPluginUrl.$sPath );
	}

	/**
	 * @param string $sAsset
	 * @return string
	 */
	public function getPluginUrl_Asset( $sAsset ) {
		if ( $this->loadFS()->exists( $this->getPath_Assets( $sAsset ) ) ) {
			return $this->getPluginUrl( $this->getPluginSpec_Path( 'assets' ).'/'.$sAsset );
		}
		return '';
	}

	/**
	 * @param string $sAsset
	 * @return string
	 */
	public function getPluginUrl_Css( $sAsset ) {
		return $this->getPluginUrl_Asset( 'css/'.$sAsset );
	}

	/**
	 * @param string $sAsset
	 * @return string
	 */
	public function getPluginUrl_Image( $sAsset ) {
		return $this->getPluginUrl_Asset( 'images/'.$sAsset );
	}

	/**
	 * @param string $sAsset
	 * @return string
	 */
	public function getPluginUrl_Js( $sAsset ) {
		return $this->getPluginUrl_Asset( 'js/'.$sAsset );
	}

	/**
	 * @return string
	 * @throws \Exception
	 */
	public function getPluginUrl_AdminMainPage() {
		return $this->loadCorePluginFeatureHandler()->getFeatureAdminPageUrl();
	}

	/**
	 * @param string $asset
	 * @return string
	 */
	public function getPath_Assets( $asset = '' ) {
		return trailingslashit( path_join( $this->getRootDir(), $this->getPluginSpec_Path( 'assets' ) ) ).$asset;
	}

	/**
	 * @param string $sFlag
	 * @return string
	 */
	public function getPath_Flags( $sFlag = '' ) {
		return $this->getRootDir().$this->getPluginSpec_Path( 'flags' ).DIRECTORY_SEPARATOR.$sFlag;
	}

	/**
	 * @param string $sFile
	 * @return string|null
	 */
	public function getPath_Temp( $sFile = '' ) {
		$sPath = null;
		$sTmpDir = path_join( $this->getRootDir(), $this->getPluginSpec_Path( 'temp' ) );
		if ( $this->loadFS()->mkdir( $sTmpDir ) ) {
			if ( empty( $sFile ) ) {
				$sPath = trailingslashit( $sTmpDir );
			}
			else {
				$sPath = path_join( $sTmpDir, $sFile );
			}
		}
		return $sPath;
	}

	/**
	 * @param string $sAsset
	 * @return string
	 */
	public function getPath_AssetCss( $sAsset = '' ) {
		return $this->getPath_Assets( 'css/'.$sAsset );
	}

	/**
	 * @param string $sAsset
	 * @return string
	 */
	public function getPath_AssetJs( $sAsset = '' ) {
		return $this->getPath_Assets( 'js/'.$sAsset );
	}

	/**
	 * @param string $sAsset
	 * @return string
	 */
	public function getPath_AssetImage( $sAsset = '' ) {
		return $this->getPath_Assets( 'images/'.$sAsset );
	}

	/**
	 * @return string
	 */
	public function getPath_Languages() {
		return trailingslashit( path_join( $this->getRootDir(), $this->getPluginSpec_Path( 'languages' ) ) );
	}

	/**
	 * @return string
	 */
	public function getPath_PluginCache() {
		return path_join( WP_CONTENT_DIR, $this->getPluginSpec_Path( 'cache' ) );
	}

	/**
	 * get the root directory for the plugin source with the trailing slash
	 * @return string
	 */
	public function getPath_Source() {
		return $this->isLegacy() ? $this->getPath_SourceLegacy() : $this->getPath_SourceCurrent();
	}

	/**
	 * get the root directory for the plugin with the trailing slash
	 * @return string
	 */
	public function getPath_SourceCurrent() {
		return trailingslashit( path_join( $this->getRootDir(), $this->getPluginSpec_Path( 'source' ) ) );
	}

	/**
	 * get the root directory for the plugin with the trailing slash
	 * @return string
	 */
	public function getPath_SourceLegacy() {
		return trailingslashit( path_join( $this->getRootDir(), $this->getPluginSpec_Path( 'source-legacy' ) ) );
	}

	/**
	 * @return bool
	 */
	public function isLegacy() {
		return version_compare( PHP_VERSION, '7.0', '<' );
	}

	/**
	 * Get the directory for the plugin source files with the trailing slash
	 * @param string $sSourceFile
	 * @return string
	 */
	public function getPath_SourceFile( $sSourceFile = '' ) {
		return $this->getPath_Source().$sSourceFile;
	}

	/**
	 * Get the path to a library source file
	 * @param string $sLibFile
	 * @return string
	 */
	public function getPath_LibFile( $sLibFile = '' ) {
		return $this->getPath_Source().'lib/'.$sLibFile;
	}

	/**
	 * @return string
	 */
	public function getPath_Templates() {
		return trailingslashit( path_join( $this->getRootDir(), $this->getPluginSpec_Path( 'templates' ) ) );
	}

	/**
	 * @param string $sTemplate
	 * @return string
	 */
	public function getPath_TemplatesFile( $sTemplate ) {
		return path_join( $this->getPath_Templates(), $sTemplate );
	}

	/**
	 * @return string
	 */
	private function getPathPluginSpec() {
		return path_join( $this->getRootDir(), 'plugin-spec.php' );
	}

	/**
	 * Get the root directory for the plugin with the trailing slash
	 * @return string
	 */
	public function getRootDir() {
		return trailingslashit( dirname( $this->getRootFile() ) );
	}

	/**
	 * @return string
	 */
	public function getRootFile() {
		if ( !isset( self::$sRootFile ) ) {
			self::$sRootFile = __FILE__;
		}
		return self::$sRootFile;
	}

	/**
	 * @return string
	 */
	public function getTextDomain() {
		return $this->getPluginSpec_Property( 'text_domain' );
	}

	/**
	 * @return string
	 */
	public function getVersion() {
		return $this->getPluginSpec_Property( 'version' );
	}

	/**
	 * @return \stdClass
	 */
	protected function getPluginControllerOptions() {
		if ( !isset( self::$oControllerOptions ) ) {

			self::$oControllerOptions = $this->loadWP()->getOption( $this->getPluginControllerOptionsKey() );
			if ( !is_object( self::$oControllerOptions ) ) {
				self::$oControllerOptions = new \stdClass();
			}

			// Used at the time of saving during WP Shutdown to determine whether saving is necessary. TODO: Extend to plugin options
			if ( empty( $this->sConfigOptionsHashWhenLoaded ) ) {
				$this->sConfigOptionsHashWhenLoaded = \md5( \serialize( self::$oControllerOptions ) );
			}

			if ( $this->getIsRebuildOptionsFromFile() ) {
				self::$oControllerOptions->plugin_spec = $this->readPluginSpecification();
			}
		}
		return self::$oControllerOptions;
	}

	protected function deletePluginControllerOptions() {
		$this->setPluginControllerOptions( false );
		$this->saveCurrentPluginControllerOptions();
	}

	protected function saveCurrentPluginControllerOptions() {
		$options = $this->getPluginControllerOptions();
		if ( $this->sConfigOptionsHashWhenLoaded != \md5( \serialize( $options ) ) ) {
			add_filter( $this->doPluginPrefix( 'bypass_permission_to_manage' ), '__return_true' );
			$this->loadWP()->updateOption( $this->getPluginControllerOptionsKey(), $options );
			remove_filter( $this->doPluginPrefix( 'bypass_permission_to_manage' ), '__return_true' );
		}
	}

	/**
	 * This should always be used to modify or delete the options as it works within the Admin Access Permission system.
	 * @param stdClass|bool $oOptions
	 * @return $this
	 */
	protected function setPluginControllerOptions( $oOptions ) {
		self::$oControllerOptions = $oOptions;
		return $this;
	}

	/**
	 * @return string
	 */
	private function getPluginControllerOptionsKey() {
		return \strtolower( \get_class( $this ) );
	}

	/**
	 * @param string $path
	 * @return mixed
	 */
	public function loadLib( $path ) {
		return include( $this->getPath_LibFile( $path ) );
	}

	public function deactivateSelf() {
		if ( $this->getIsValidAdminArea() && function_exists( 'deactivate_plugins' ) ) {
			deactivate_plugins( $this->getPluginBaseFile() );
		}
	}

	public function clearSession() {
		$this->loadDP()->setDeleteCookie( $this->getPluginPrefix() );
		self::$sSessionId = null;
	}

	/**
	 * Returns true if you're overriding OFF.  We don't do override ON any more (as of 3.5.1)
	 */
	public function getIfOverrideOff() {
		if ( !isset( $this->bForceOffState ) ) {
			$this->bForceOffState = $this->loadFS()->fileExistsInDir( 'forceOff', $this->getRootDir(), false );
		}
		return $this->bForceOffState;
	}

	/**
	 * @param bool $setIfNeeded
	 * @return string
	 */
	public function getSessionId( $setIfNeeded = true ) {
		if ( empty( self::$sSessionId ) ) {
			self::$sSessionId = $this->loadDP()->FetchCookie( $this->getPluginPrefix(), '' );
			if ( empty( self::$sSessionId ) && $setIfNeeded ) {
				self::$sSessionId = md5( uniqid( $this->getPluginPrefix() ) );
				$this->setSessionCookie();
			}
		}
		return self::$sSessionId;
	}

	/**
	 * @return bool
	 */
	public function hasSessionId() {
		return !empty( $this->getSessionId( false ) );
	}

	protected function setSessionCookie() {
		$oWp = $this->loadWP();
		$this->loadDP()->setCookie(
			$this->getPluginPrefix(),
			$this->getSessionId(),
			$this->loadDP()->time() + \DAY_IN_SECONDS*30,
			$oWp->getCookiePath(),
			$oWp->getCookieDomain()
		);
	}

	/**
	 * @return \ICWP_APP_FeatureHandler_Plugin
	 */
	public function loadCorePluginFeatureHandler() {
		return $this->loadFeatureHandler( [
			'slug'          => 'plugin',
			'load_priority' => 10
		] );
	}

	/**
	 * @return bool
	 */
	public function loadAllFeatures() {
		$success = true;
		foreach ( $this->loadCorePluginFeatureHandler()->getActivePluginFeatures() as $slug => $modProperties ) {
			try {
				$this->loadFeatureHandler( $modProperties );
			}
			catch ( Exception $e ) {
				$this->loadWP()->wpDie( $e->getMessage() );
				$success = false;
			}
		}
		return $success;
	}

	/**
	 * @return \ICWP_APP_FeatureHandler_Base|mixed
	 */
	public function loadFeatureHandler( array $properties ) {

		$slug = $properties[ 'slug' ];
		if ( \property_exists( $this, 'modules' ) && isset( $this->modules[ $slug ] ) ) {
			return $this->modules[ $slug ];
		}

		$featureName = \str_replace( ' ', '', \ucwords( \str_replace( '_', ' ', $slug ) ) );
		$optionsVarName = sprintf( 'oFeatureHandler%s', $featureName ); // e.g. oFeatureHandlerPlugin

		if ( isset( $this->{$optionsVarName} ) ) {
			return $this->{$optionsVarName};
		}

		// e.g. features/firewall.php
		require_once( $this->getPath_SourceFile( sprintf( 'features/%s.php', $slug ) ) );

		$className = sprintf( '%s_%s_FeatureHandler_%s',
			\strtoupper( $this->getParentSlug() ),
			\strtoupper( $this->getPluginSlug() ),
			$featureName
		); // e.g. ICWP_APP_FeatureHandler_Plugin

		if ( !\class_exists( $className, false ) ) {
			throw new \Exception( 'class does not exist for slug: '.$slug );
		}

		$mod = new $className( $this, $properties );

		\property_exists( $this, 'modules' ) ? ( $this->modules[ $slug ] = $mod ) : ( $this->{$optionsVarName} = $mod );

		return $mod;
	}
}