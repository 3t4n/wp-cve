<?php

namespace FernleafSystems\Wordpress\Plugin\iControlWP;

/**
 * Copyright (c) 2023 iControlWP <support@icontrolwp.com>
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
class Controller extends \ICWP_APP_Foundation {

	/**
	 * @var \stdClass
	 */
	private static $conOpts;

	/**
	 * @var Controller
	 */
	public static $oInstance;

	/**
	 * @var string
	 */
	private static $sRootFile;

	/**
	 * @var bool
	 */
	protected $rebuildOpts;

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
	private $cfgHash;

	/**
	 * @var bool
	 */
	protected $bMeetsBasePermissions = false;

	private $modules = [];

	/**
	 * @return Controller
	 */
	public static function GetInstance( string $rootFile ) {
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
	 * @throws \Exception
	 */
	private function __construct( string $rootFile ) {
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

		$minPHP = $this->getPluginSpec_Requirement( 'php' );
		if ( !empty( $minPHP ) ) {
			if ( \version_compare( \phpversion(), $minPHP, '<' ) ) {
				$reqsMessages[] = sprintf( 'PHP does not meet minimum version. Your version: %s.  Required Version: %s.', \PHP_VERSION, $minPHP );
				$meetsRequirements = false;
			}
		}

		$minWP = $this->getPluginSpec_Requirement( 'wordpress' );
		if ( !empty( $minWP ) ) {
			$WPversion = $this->loadWP()->getWordpressVersion();
			if ( \version_compare( $WPversion, $minWP, '<' ) ) {
				$reqsMessages[] = sprintf( 'WordPress does not meet minimum version. Your version: %s.  Required Version: %s.', $WPversion, $minWP );
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
		$msg = $this->getRequirementsMessages();
		if ( !empty( $msg ) && \is_array( $msg ) ) {
			$this->loadRenderer( $this->getPath_Templates() )
				 ->setTemplate( 'notices/does-not-meet-requirements' )
				 ->setRenderVars( [
					 'strings' => [
						 'requirements'     => $msg,
						 'summary_title'    => sprintf( 'Web Hosting requirements for Plugin "%s" are not met and you should deactivate the plugin.', $this->getHumanName() ),
						 'more_information' => 'Click here for more information on requirements'
					 ],
					 'hrefs'   => [
						 'more_information' => sprintf( 'https://wordpress.org/plugins/%s/faq', $this->getTextDomain() )
					 ]
				 ] )
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
		add_action( 'in_plugin_update_message-'.$this->getPluginBaseFile(), [ $this, 'onWpPluginUpdateMessage' ] );

		add_filter( 'auto_update_plugin', [ $this, 'onWpAutoUpdate' ], 500, 2 );
		add_filter( 'set_site_transient_update_plugins', [ $this, 'setUpdateFirstDetectedAt' ] );

		add_action( 'shutdown', [ $this, 'onWpShutdown' ] );

		// outsource the collection of admin notices
		if ( is_admin() ) {
			$this->loadAdminNoticesProcessor()->setActionPrefix( $this->doPluginPrefix() );
		}
	}

	public function onWpAdminInit() {
		add_action( 'admin_enqueue_scripts', [ $this, 'onWpEnqueueAdmin' ], 100 );
	}

	public function onWpEnqueueAdmin( $hook = '' ) {
		if ( \strpos( (string)$hook, 'icontrolwp_page_icwp' ) === 0 ) {
			$this->enqueueAdminCss();
			$this->enqueueAdminJs();
		}
	}

	public function onWpLoaded() {
		if ( $this->getIsValidAdminArea() ) {
			$this->doPluginFormSubmit();
		}
	}

	public function onWpInit() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueueFrontendCss' ], 99 );
		$this->bMeetsBasePermissions = current_user_can( $this->getBasePermissions() );
	}

	public function onWpAdminMenu() {
		if ( $this->getIsValidAdminArea() ) {
			$this->createPluginMenu();
		}
	}

	/**
	 * v5.4.1: Nasty looping bug in here where this function was called within the 'user_has_cap' filter
	 * so we removed the "current_user_can()" or any such sub-call within this function
	 */
	public function getHasPermissionToManage() :bool {
		if ( apply_filters( $this->doPluginPrefix( 'bypass_permission_to_manage' ), false ) ) {
			return true;
		}
		return $this->getMeetsBasePermissions()
			   && apply_filters( $this->doPluginPrefix( 'has_permission_to_manage' ), true );
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

	protected function createPluginMenu() {

		if ( apply_filters( $this->doPluginPrefix( 'filter_hidePluginMenu' ), !$this->getPluginSpec_Menu( 'show' ) ) ) {
			return true;
		}

		if ( $this->getPluginSpec_Menu( 'top_level' ) ) {

			$labels = $this->getPluginLabels();

			$title = $this->getPluginSpec_Menu( 'title' );
			if ( \is_null( $title ) ) {
				$title = $this->getHumanName();
			}

			$icon = $this->getPluginUrl_Image( $this->getPluginSpec_Menu( 'icon_image' ) );
			$iconURL = empty( $labels[ 'icon_url_16x16' ] ) ? $icon : $labels[ 'icon_url_16x16' ];

			$fullMenuID = $this->getPluginPrefix();
			add_menu_page(
				$this->getHumanName(),
				$title,
				$this->getBasePermissions(),
				$fullMenuID,
				[ $this, $this->getPluginSpec_Menu( 'callback' ) ],
				$iconURL
			);

			if ( $this->getPluginSpec_Menu( 'has_submenu' ) ) {

				$itesm = apply_filters( $this->doPluginPrefix( 'filter_plugin_submenu_items' ), [] );
				if ( !empty( $itesm ) ) {
					foreach ( $itesm as $title => $menuItem ) {
						list( $text, $itemID, $itemCallback ) = $menuItem;
						add_submenu_page(
							$fullMenuID,
							$title,
							$text,
							$this->getBasePermissions(),
							$itemID,
							$itemCallback
						);
					}
				}
			}

			if ( $this->getPluginSpec_Menu( 'do_submenu_fix' ) ) {
				$this->fixSubmenu();
			}
		}
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

	public function enqueueFrontendCss() {
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

	public function enqueueAdminJs() {
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

	public function enqueueAdminCss() {
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
				$conOpts = $this->conOpts();
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

			$conOpts = $this->conOpts();

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
						$firstDetected = $conOpts->update_first_detected[ $newVersion ] ?? 0;
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
			foreach ( $labels as $key => $sLabel ) {
				$plugins[ $file ][ $key ] = $sLabel;
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

	protected function deleteFlags() {
		$FS = $this->loadFS();
		if ( $FS->exists( $this->getPath_Flags( 'rebuild' ) ) ) {
			$FS->deleteFile( $this->getPath_Flags( 'rebuild' ) );
		}
		if ( $this->getIsResetPlugin() ) {
			$FS->deleteFile( $this->getPath_Flags( 'reset' ) );
		}
	}

	/**
	 * Added to a WordPress filter ('all_plugins') which will remove this particular plugin from the
	 * list of all plugins based on the "plugin file" name.
	 * @param array $plugins
	 * @return array
	 */
	public function filter_hidePluginFromTableList( $plugins ) {
		if ( apply_filters( $this->doPluginPrefix( 'hide_plugin' ), false ) ) {
			$basename = $this->getPluginBaseFile();
			if ( isset( $plugins[ $basename ] ) ) {
				unset( $plugins[ $basename ] );
			}
		}
		return $plugins;
	}

	protected function doLoadTextDomain() {
		return load_plugin_textdomain(
			$this->getTextDomain(),
			false,
			plugin_basename( $this->getPath_Languages() )
		);
	}

	protected function doPluginFormSubmit() {
		if ( !$this->getIsPluginFormSubmit() ) {
			return false;
		}

		// do all the plugin feature/options saving
		do_action( $this->doPluginPrefix( 'form_submit' ) );

		if ( $this->getIsPage_PluginAdmin() ) {
			$this->loadWP()->doRedirect( $this->loadWP()->getUrl_CurrentAdminPage() );
		}
	}

	/**
	 * @param string $suffix
	 * @param string $glue
	 */
	public function doPluginPrefix( $suffix = '', $glue = '-' ) :string {
		$prefix = $this->getPluginPrefix( $glue );

		if ( $suffix == $prefix || \strpos( $suffix, $prefix.$glue ) === 0 ) { //it already has the full prefix
			return $suffix;
		}

		return sprintf( '%s%s%s', $prefix, empty( $suffix ) ? '' : $glue, empty( $suffix ) ? '' : $suffix );
	}

	/**
	 * @param string $suffix
	 */
	public function doPluginOptionPrefix( $suffix = '' ) :string {
		return $this->doPluginPrefix( $suffix, '_' );
	}

	/**
	 * @param string $sKey
	 * @return mixed|null
	 */
	protected function getPluginSpec_ActionLinks( $sKey ) {
		return $this->conOpts()->plugin_spec[ 'action_links' ][ $sKey ] ?? null;
	}

	/**
	 * @param string $sKey
	 * @return mixed|null
	 */
	protected function getPluginSpec_Include( $sKey ) {
		return $this->conOpts()->plugin_spec[ 'includes' ][ $sKey ] ?? null;
	}

	/**
	 * @param string $key
	 * @return array|string
	 */
	protected function getPluginSpec_Labels( $key = '' ) {
		$labels = $this->conOpts()->plugin_spec[ 'labels' ] ?? [];
		//Prep the icon urls
		if ( !empty( $labels[ 'icon_url_16x16' ] ) ) {
			$labels[ 'icon_url_16x16' ] = $this->getPluginUrl_Image( $labels[ 'icon_url_16x16' ] );
		}
		if ( !empty( $labels[ 'icon_url_32x32' ] ) ) {
			$labels[ 'icon_url_32x32' ] = $this->getPluginUrl_Image( $labels[ 'icon_url_32x32' ] );
		}

		if ( empty( $key ) ) {
			return $labels;
		}

		return $this->conOpts()->plugin_spec[ 'labels' ][ $key ] ?? null;
	}

	/**
	 * @param string $key
	 * @return mixed|null
	 */
	protected function getPluginSpec_Menu( $key ) {
		return $this->conOpts()->plugin_spec[ 'menu' ][ $key ] ?? null;
	}

	/**
	 * @param string $key
	 * @return mixed|null
	 */
	protected function getPluginSpec_Path( $key ) {
		return $this->conOpts()->plugin_spec[ 'paths' ][ $key ] ?? null;
	}

	/**
	 * @param string $key
	 * @return mixed|null
	 */
	protected function getPluginSpec_Property( $key ) {
		return $this->conOpts()->plugin_spec[ 'properties' ][ $key ] ?? null;
	}

	/**
	 * @return array
	 */
	protected function getPluginSpec_PluginMeta() {
		$conOpts = $this->conOpts();
		return ( isset( $conOpts->plugin_spec[ 'plugin_meta' ] ) && is_array( $conOpts->plugin_spec[ 'plugin_meta' ] ) ) ? $conOpts->plugin_spec[ 'plugin_meta' ] : [];
	}

	/**
	 * @param string $sKey
	 * @return mixed|null
	 */
	protected function getPluginSpec_Requirement( $sKey ) {
		return $this->conOpts()->plugin_spec[ 'requirements' ][ $sKey ] ?? null;
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
	public function getIsValidAdminArea( $checkUserPermissions = true ) :bool {
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

	public function getOptionStoragePrefix() :string {
		return $this->getPluginPrefix( '_' ).'_';
	}

	/**
	 * @param $glue
	 */
	public function getPluginPrefix( $glue = '-' ) :string {
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

	public function getIsPage_PluginAdmin() :bool {
		return \strpos( $this->loadWP()->getCurrentWpAdminPage(), $this->getPluginPrefix() ) === 0;
	}

	public function getIsPage_PluginMainDashboard() :bool {
		return $this->loadWP()->getCurrentWpAdminPage() == $this->getPluginPrefix();
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

	public function getIsRebuildOptionsFromFile() :bool {
		if ( isset( $this->rebuildOpts ) ) {
			return $this->rebuildOpts;
		}

		// The first choice is to look for the file hash. If it's "always" empty, it means we could never
		// hash the file in the first place so it's not ever effectively used and it falls back to the rebuild file
		$conOptions = $this->conOpts();
		$specPath = $this->getPathPluginSpec();
		$currentHash = @\md5_file( $specPath );
		$modTime = $this->loadFS()->getModifiedTime( $specPath );

		$this->rebuildOpts = true;

		if ( isset( $conOptions->hash ) && is_string( $conOptions->hash ) && ( $conOptions->hash == $currentHash ) ) {
			$this->rebuildOpts = false;
		}
		elseif ( isset( $conOptions->mod_time ) && ( $modTime < $conOptions->mod_time ) ) {
			$this->rebuildOpts = false;
		}

		$conOptions->hash = $currentHash;
		$conOptions->mod_time = $modTime;
		return $this->rebuildOpts;
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

	public function isLegacy() :bool {
		return \version_compare( \PHP_VERSION, '7.0', '<' );
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
		return trailingslashit( \dirname( $this->getRootFile() ) );
	}

	public function getRootFile() :string {
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

	protected function conOpts() :\stdClass {
		if ( !isset( self::$conOpts ) ) {

			self::$conOpts = $this->loadWP()->getOption( $this->getPluginControllerOptionsKey() );
			if ( !is_object( self::$conOpts ) ) {
				self::$conOpts = new \stdClass();
			}

			// Used at the time of saving during WP Shutdown to determine whether saving is necessary. TODO: Extend to plugin options
			if ( empty( $this->cfgHash ) ) {
				$this->cfgHash = \md5( \serialize( self::$conOpts ) );
			}

			if ( $this->getIsRebuildOptionsFromFile() ) {
				self::$conOpts->plugin_spec = $this->readPluginSpecification();
			}
		}
		return self::$conOpts;
	}

	protected function deletePluginControllerOptions() {
		$this->setPluginControllerOptions( false );
		$this->saveCurrentPluginControllerOptions();
	}

	protected function saveCurrentPluginControllerOptions() {
		$options = $this->conOpts();
		if ( $this->cfgHash != \md5( \serialize( $options ) ) ) {
			add_filter( $this->doPluginPrefix( 'bypass_permission_to_manage' ), '__return_true' );
			$this->loadWP()->updateOption( $this->getPluginControllerOptionsKey(), $options );
			remove_filter( $this->doPluginPrefix( 'bypass_permission_to_manage' ), '__return_true' );
		}
	}

	/**
	 * This should always be used to modify or delete the options as it works within the Admin Access Permission system.
	 * @param \stdClass|bool $oOptions
	 * @return $this
	 */
	protected function setPluginControllerOptions( $oOptions ) {
		self::$conOpts = $oOptions;
		return $this;
	}

	private function getPluginControllerOptionsKey() :string {
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
	 * @return \ICWP_APP_FeatureHandler_Plugin
	 */
	public function loadCorePluginFeatureHandler() {
		return $this->loadFeatureHandler( [
			'slug'          => 'plugin',
			'load_priority' => 10
		] );
	}

	public function loadAllFeatures() :bool {
		foreach ( $this->loadCorePluginFeatureHandler()->getActivePluginFeatures() as $modProperties ) {
			$this->loadFeatureHandler( $modProperties );
		}
		return true;
	}

	/**
	 * @return \ICWP_APP_FeatureHandler_Base|mixed
	 */
	public function loadFeatureHandler( array $properties ) {
		$slug = $properties[ 'slug' ];
		if ( !isset( $this->modules[ $slug ] ) ) {
			$className = $this->modMap()[ $slug ];
			$this->modules[ $slug ] = new $className( $this, $properties );
		}
		return $this->modules[ $slug ];
	}

	private function modMap() :array {
		return [
			'plugin'           => \ICWP_APP_FeatureHandler_Plugin::class,
			'autoupdates'      => \ICWP_APP_FeatureHandler_Autoupdates::class,
			'compatibility'    => \ICWP_APP_FeatureHandler_Compatibility::class,
			'google_analytics' => \ICWP_APP_FeatureHandler_GoogleAnalytics::class,
			'security'         => \ICWP_APP_FeatureHandler_Security::class,
			'whitelabel'       => \ICWP_APP_FeatureHandler_Whitelabel::class,
		];
	}
}