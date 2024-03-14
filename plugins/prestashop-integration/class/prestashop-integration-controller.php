<?php
/*  Copyright 2010-2023  François Pons  (email : fpons@aytechnet.fr)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

global $prestashop_integration;
if ( $prestashop_integration->psabspath != '' && file_exists( $prestashop_integration->psabspath . 'config/config.inc.php' )
                                              && ( file_exists( $prestashop_integration->psabspath . 'classes/FrontController.php' ) ||
                                                   file_exists( $prestashop_integration->psabspath . 'classes/controller/FrontController.php' ) ) ) {
	require_once( $prestashop_integration->psabspath . 'config/config.inc.php' );

	class PrestaShopIntegration_FrontController extends FrontController {
		//public $php_self = 'wordpress';

		public function __destruct()
		{
			// make sure Cookie are saved
			if ( isset( $this->context) && isset( $this->context->cookie) )
				$this->context->cookie->write();
		}

		public function init()
		{
			$this->page_name = 'wordpress';
			parent::init();

			if ( isset( $this->context) ) {
				if ( version_compare(_PS_VERSION_, '1.5.4', '>=') )
					$this->setMedia();
				if ( $this->checkAccess() ) {
					$this->postProcess();
					$this->initHeader();
					if ( $this->viewAccess() )
						$this->initContent();
					else
						$this->errors[] = Tools::displayError('Access denied.');
					$this->initFooter();
				}
				if (version_compare(_PS_VERSION_, '1.7', '>')) {
					$this->context->smarty->assign(array(
							'layout' => $this->getLayout(),
							'stylesheets' => $this->getStylesheets(),
							'javascript' => $this->getJavascript(),
							'js_custom_vars' => Media::getJsDef(),
							'notifications' => $this->prepareNotifications(),
							));
				}
			}
		}

		public function displayHeader($display = true)
		{
			global $prestashop_integration;

			/* HACK to inform PrestaShop being under control of WordPress...
			   ...and to avoid bug of modules not displayed on PrestaShop home being not displayed on blog too */
			$_SERVER['QUERY_STRING'] = str_replace( "index.php", "wordpress.php", $_SERVER['QUERY_STRING'] );
			$_SERVER['PHP_SELF'] = str_replace( "index.php", "wordpress.php", $_SERVER['PHP_SELF'] );

			$smarty = isset( $this->context) ? $this->context->smarty : self::$smarty;

			if ( isset( $this->context) ) {
				Tools::safePostVars();

				// assign css_files and js_files at the very last time
				if ((Configuration::get('PS_CSS_THEME_CACHE') || Configuration::get('PS_JS_THEME_CACHE')) && is_writable(_PS_THEME_DIR_.'cache'))
				{
					// CSS compressor management
					if (Configuration::get('PS_CSS_THEME_CACHE'))
						$this->css_files = Media::cccCSS($this->css_files);
					//JS compressor management
					if (Configuration::get('PS_JS_THEME_CACHE'))
						$this->js_files = Media::cccJs($this->js_files);
				}

				$smarty->assign('css_files', $this->css_files);
				$smarty->assign('js_files', array_unique($this->js_files));
				$smarty->assign(array(
					'errors' => $this->errors,
					'display_header' => $this->display_header,	
					'display_footer' => $this->display_footer,
				));

				// Don't use live edit if on mobile device
				if ($this->context->getMobileDevice() == false && Tools::isSubmit('live_edit'))
					$smarty->assign('live_edit', $this->getLiveEditFooter());

				$layout = $this->getLayout();
				if ($layout)
				{
					if ($this->template)
						$smarty->assign('template', $smarty->fetch($this->template));

					if (method_exists('Media', 'getJsDef')) {
						$dom_available = extension_loaded('dom') ? true : false;

						$smarty->assign(array(
							'js_def' => Media::getJsDef(),
							'js_inline' => $dom_available ? Media::getInlineScript() : array(),
							));

						$javascript = $this->context->smarty->fetch(_PS_ALL_THEMES_DIR_.'javascript.tpl');
					}
				}
				/* FIXME : Do not call directly the following else the template page will be outputed:
				   $this->smartyOutputContent($layout); */
			} else {
				global $css_files, $js_files;
				global $cookie;

				$current_id_lang = $cookie->id_lang;
				$cookie->id_lang = $prestashop_integration->psLang();
				if ( $current_id_lang && $current_id_lang != $cookie->id_lang )
					$cookie->write();

				if (Validate::isLoadedObject($ps_language = new Language((int)$cookie->id_lang)))
					self::$smarty->assign( 'lang_iso', $ps_language->iso_code );

				$smarty->assign( array(
					'prestashop_integration' => $prestashop_integration,
					'time' => time(),
					'img_update_time' => Configuration::get('PS_IMG_UPDATE_TIME'),
					'static_token' => Tools::getToken(false),
					'token' => Tools::getToken(),
					'logo_image_width' => Configuration::get('SHOP_LOGO_WIDTH'),
					'logo_image_height' => Configuration::get('SHOP_LOGO_HEIGHT'),
					'priceDisplayPrecision' => _PS_PRICE_DISPLAY_PRECISION_,
					'content_only' => (int)Tools::getValue('content_only')
				) );

				$smarty->assign( array(
					'HOOK_HEADER' => Module::hookExec('header'),
					'HOOK_TOP' => Module::hookExec('top'),
					'HOOK_LEFT_COLUMN' => Module::hookExec('leftColumn'),
					'HOOK_RIGHT_COLUMN' => Module::hookExec('rightColumn', array('cart' => self::$cart)),
					'HOOK_FOOTER' => Module::hookExec('footer')
				) );

				if ((Configuration::get('PS_CSS_THEME_CACHE') OR Configuration::get('PS_JS_THEME_CACHE')) AND is_writable(_PS_THEME_DIR_.'cache'))
				{
					if ($prestashop_integration->css_import) {
						// CSS compressor management
						if (Configuration::get('PS_CSS_THEME_CACHE'))
							Tools::cccCss();
					}

					if ($prestashop_integration->js_import) {
						//JS compressor management
						if (Configuration::get('PS_JS_THEME_CACHE'))
							Tools::cccJs();
					}
				}
				$smarty->assign('css_files', $css_files);
				$smarty->assign('js_files', array_unique($js_files));
			}

			if ($prestashop_integration->favicon_import) {
				$favicon_url = _PS_IMG_.Configuration::get('PS_FAVICON');
				if (isset($favicon_url) && !empty($favicon_url))
					$favicon_url = $favicon_url;
				else
					$favicon_url = _PS_IMG_.'favicon.ico';
				$favicon_url .= '?'.$prestashop_integration->getTemplateVars( 'img_update_time' );
?>
<link rel="icon" type="image/vnd.microsoft.icon" href="<?php echo $favicon_url; ?>" />
<link rel="shortcut icon" type="image/x-icon" href="<?php echo $favicon_url; ?>" />
<?php
			}
?>
<?php			if ($javascript)
				echo $javascript;
			else {
?>
<script type="text/javascript">
	var baseDir = '<?php echo $prestashop_integration->getTemplateVars( 'content_dir' ); ?>';
<?php if ( $prestashop_integration->getTemplateVars( 'base_uri' ) ) { ?>
	var baseUri = '<?php echo $prestashop_integration->getTemplateVars( 'base_uri' ); ?>';
<?php } ?>
	var static_token = '<?php echo $prestashop_integration->getTemplateVars( 'static_token' ); ?>';
	var token = '<?php echo $prestashop_integration->getTemplateVars( 'token' ); ?>';
	var priceDisplayPrecision = <?php echo $prestashop_integration->getTemplateVars( 'priceDisplayPrecision' )*$prestashop_integration->getTemplateVars( 'currency' )->decimals; ?>;
	var priceDisplayMethod = <?php echo $prestashop_integration->getTemplateVars( 'priceDisplay' ); ?>;
	var roundMode = <?php echo $prestashop_integration->getTemplateVars( 'roundMode' ); ?>;
</script>
<?php
				if ($prestashop_integration->js_import) {
					foreach ( $prestashop_integration->getTemplateVars( 'js_files' ) as $js_uri )
						echo '<script type="text/javascript" src="'.$js_uri.'"></script>'."\n";
				}
			}
			if ($prestashop_integration->css_import) {
				foreach ( $prestashop_integration->getTemplateVars( 'css_files' ) as $css_uri => $media )
					echo '<link href="'.$css_uri.'" rel="stylesheet" type="text/css" media="'.$media.'" />'."\n";
			}

			if ($prestashop_integration->js_import || $prestashop_integration->css_import)
				echo $prestashop_integration->getTemplateVars( 'HOOK_HEADER' );
                }
	}

	class PrestaShopIntegration_IndexController extends IndexController {
		protected function canonicalRedirection($canonical_url = '')
		{
			return;
		}
	}
}

