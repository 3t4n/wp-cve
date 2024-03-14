<?php

if (!defined('WEBTOTEM_INIT') || WEBTOTEM_INIT !== true) {
	if (!headers_sent()) {
		header('HTTP/1.1 403 Forbidden');
	}
	die("Protected By WebTotem!");
}

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use \Twig\Loader\FilesystemLoader;
use \Twig\Environment;

/**
 * Read, parse and handle everything related with the templates.
 */
class WebTotemTemplate {

	protected $loader;
	protected $page_nonce;
	protected $images_path;
  protected $menu_url;
  protected $domain;

	function __construct() {
        if (class_exists('\Twig\Loader\FilesystemLoader')) {
            $this->loader = new FilesystemLoader( WEBTOTEM_PLUGIN_PATH . '/includes/templates/');
        }
		$this->page_nonce = wp_create_nonce('wtotem_page_nonce');
		$this->images_path = WebTotem::getImagePath('');
    $this->menu_url = WebTotem::adminURL('admin.php?page=wtotem');
    $this->domain = WEBTOTEM_SITE_DOMAIN;
	}

	/**
	 * Rendering a template using twig and filling in data.
	 *
	 * @param string $template
	 * @param array $variables
	 *
	 * @return bool|string
	 * @throws LoaderError
	 * @throws RuntimeError
	 * @throws SyntaxError
	 */
	public function twigRender( $template, $variables = []) {

		$twig = new Environment($this->loader);

		if(!file_exists(WEBTOTEM_PLUGIN_PATH . '/includes/templates/' . $template)) {
			WebTotemOption::setNotification('error', __('There is no template: ', 'wtotem') . $template);
			return FALSE;
		}

		// Default values of some variables
		$variables['images_path'] = $this->images_path;
		$variables['days'] = (isset($variables['days'])) ? $variables['days'] : 7;
		$variables['page_nonce'] = $this->page_nonce;
    $variables['menu_url'] = $this->menu_url;
    $variables['domain'] = $this->domain;

		if( WebTotem::isMultiSite() ){
			$variables['is_multisite'] = WebTotem::isMultiSite();
			$variables['is_super_admin'] = is_super_admin();
			$variables['hid'] = (WebTotemRequest::get('hid')) ? '&hid=' . WebTotemRequest::get('hid') : '';
		}

		$twig->addFilter(new \Twig\TwigFilter('trans', array( $this, 'translate' )));
		$twig->addFunction(new \Twig\TwigFunction('plural', array( $this, 'plural' )));
		$twig->addFilter(new \Twig\TwigFilter('t', array( $this, 'translate' )));

		return $twig->render($template, $variables);
	}

	/**
	 * Page rendering based on array data.
	 *
	 * @param $params
	 *
	 * @return bool|string
	 * @throws LoaderError
	 * @throws RuntimeError
	 * @throws SyntaxError
	 */
	public function arrayRender($params) {

		$render = '';
		if(is_array($params)){

			if(array_key_exists('template', $params)){
				$template = $params['template'] . '.html.twig';
				$variables = (isset($params['variables'])) ? $params['variables'] : [];

				$render = $this->twigRender($template, $variables) ?: '';
			} else {
				foreach ($params as $param){
					$template = $param['template'] . '.html.twig';
					$variables = (isset($param['variables'])) ? $param['variables'] : [];

					$render .= $this->twigRender($template, $variables) ?: '';
				}
			}

		}

		return $render;
	}

	/**
	 * Generate a page based on a basic template and content.
	 *
	 * @param $page_content
	 *
	 * @return bool|string
	 * @throws LoaderError
	 * @throws RuntimeError
	 * @throws SyntaxError
	 */
	public function baseTemplate($page_content) {

		if(WebTotemRequest::get('hid')){
			WebTotemOption::setSessionOptions(['host_id' => WebTotemRequest::get('hid')]);
		}

		$variables['menu_url'] = WebTotem::adminURL('admin.php?page=wtotem');

		$page = str_replace(['wtotem', '_'], '', WebTotemRequest::get('page'));
		$page = $page ?: 'dashboard';
		$variables['is_active'][$page] = 'wtotem_nav__link_active';
		$variables['page'] = $page;

    if($page != 'activation'){
      $user_feedback = WebTotemAPI::getFeedback();
      $variables['user_feedback'] = isset($user_feedback['score']) && (bool)$user_feedback['score'];
    }

		$variables['theme_mode'] = WebTotem::getThemeMode();
		$variables['notifications'] = WebTotem::getNotifications();
		$variables['current_year'] = date('Y');
    $variables['content'] = $page_content;

		return $this->twigRender('layout.html.twig', $variables);
	}


	/**
	 * String translation.
	 *
	 * @param $string
	 * @param array $params
	 *
	 * @return string
	 */
	public static function translate($string, array $params = []) {

		global $locale;

		$string = ('en_US' !== $locale) ? translate($string, 'wtotem') : $string;

		if($params){
			foreach ($params as $key => $value){
				$string = str_replace($key, $value, $string);
			}
		}

		return (string) $string;
	}

	/**
	 * @param array $params [single, plural, number]
	 *
	 * usage example
	 * {{ plural({'single' : '%s month', 'plural' : '%s months', 'number' : 1}) }}
	 *
	 * @return string
	 */
	public static function plural( array $params): string {

		$string = _n( $params['single'], $params['plural'], $params['number'],'wtotem' );
		$string = str_replace('%s', $params['number'], $string);

		return (string) $string;
	}

    /**
     * Get HTML without Twig
     *
     * @return string|bool
     */
    public function getHtml($template) {
        $templatePath = WEBTOTEM_PLUGIN_PATH . '/includes/templates/' . $template . '.html.twig';
        if(!file_exists($templatePath)) {
            return FALSE;
        }

        ob_start();
        include $templatePath;
        return ob_get_clean();
    }

}
