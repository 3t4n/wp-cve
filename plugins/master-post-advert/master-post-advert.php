<?php
/*
 * Plugin Name: Master Post Advert
 * Plugin URI:  http://www.bbproject.net/moje-projekty/inne/master-post-advert
 * Description: Display advertising between the introduction and post content.
 * Author:      BBPROJECT.NET
 * Author URI:  http://www.bbproject.net
 * Version:     1.0.2
 */

// -----------------------------------------------------------------------------

/**
 * Master Post Advert
 *
 * @since 1.0
 */
class MasterPostAdvert
{

	// -------------------------------------------------------------------------

	protected $name       = 'master_post_advert';
	protected $plugin_dir = '';
	protected $options;

	// -------------------------------------------------------------------------

	/**
	 * Pobranie ustawien
	 *
	 * @since 1.0
	 *
	 * @return array
	 */
	protected function getOptions()
	{

		if (!isset($this->options)) {

			$defaults = array(
				'align' => 'center',
				'title' => '',
				'code'  => ''
			);
			$this->options = get_option($this->name);

			if (!is_array($this->options)) {
				$this->options = array();
			}
			foreach ($defaults as $name => $value) {
				if (!isset($this->options[$name])) {
					$this->options[$name] = $value;
				}
			}

		}

		return $this->options;

	}

	// -------------------------------------------------------------------------

	/**
	 * Konstruktor
	 *
	 * @since 1.0
	 */
	public function __construct()
	{

		$this->plugin_dir = plugin_dir_path(__FILE__);
		load_plugin_textdomain($this->name, false, dirname(plugin_basename(__FILE__)).'/languages');

		if (is_admin()) {
			add_action('admin_menu', array($this, 'actionAdminMenu'));
		} else {
			add_filter('the_content_more_link', array($this, 'filterTheContentMoreLink'));
			add_filter('the_content', array($this, 'filterTheContent'));
		}

	}

	// -------------------------------------------------------------------------

	/**
	 * Zdarzenie admin_menu
	 *
	 * @since 1.0
	 */
	public function actionAdminMenu()
	{
		add_action('admin_init', array($this, 'actionAdminInit'));
		add_options_page(__('Master Post Advert Settings', $this->name), 'Master Post Advert', 'install_plugins', basename(__FILE__), array($this, 'callbackOptions'));
	}

	// -------------------------------------------------------------------------

	/**
	 * Zdarzenie admin_init
	 *
	 * @since 1.0
	 */
	public function actionAdminInit()
	{
		register_setting($this->name.'_options', $this->name, array($this, 'callbackValidate'));
	}

	// -------------------------------------------------------------------------

	/**
	 * Formularz ustawien
	 *
	 * @since 1.0
	 */
	public function callbackOptions()
	{
		$options = $this->getOptions();
		include $this->plugin_dir.'options.php';
	}

	// -------------------------------------------------------------------------

	/**
	 * Walidacja danych formularza ustawien
	 *
	 * @since 1.0
	 *
	 * @param  array $data
	 * @return array
	 */
	public function callbackValidate($data)
	{
		$data['align'] = trim(strtolower($data['align']));
		$data['title'] = trim($data['title']);
		$data['code']  = trim($data['code']);
		return $data;
	}

	// -------------------------------------------------------------------------

	/**
	 * Usuniecie anchora linka "Czytaj wiecej"
	 *
	 * @since 1.0.2
	 *
	 * @param  string $more_link_html
	 * @return string
	 */
	public function filterTheContentMoreLink($more_link_html)
	{
		return preg_replace('/#more-[0-9]+/i', '', $more_link_html);
	}

	// -------------------------------------------------------------------------

	/**
	 * Parsowanie tresci
	 *
	 * @since 1.0
	 *
	 * @param  string $content
	 * @return string
	 */
	public function filterTheContent($content)
	{

		// Nie jest postem
		if (!is_single()) {
			return $content;
		}

		return preg_replace_callback(
			'#(?P<open><[a-z]+[^>]*>)? *(?P<more><span id="more-[0-9]+"></span>) *(?P<close></[a-z]+>)?()#i',
			array($this, 'filterTheContentCallback'),
			$content
		);

	}

	// -------------------------------------------------------------------------

	/**
	 * Funkcja pomocnicza dla parsowania tresci
	 *
	 * @since 1.0
	 *
	 * @param  array  $m
	 * @return string
	 */
	protected function filterTheContentCallback($m)
	{

		$options = $this->getOptions();
		if (!$options['code']) {
			return $m[0];
		}

		$title = $options['title'] ? '<div class="master-post-advert-title">'.$options['title'].'</div>' : '';
		$ad = <<<EOA
<div class="master-post-advert" style="text-align: {$options['align']}; margin: 25px 0; overflow: hidden;">
	<div style="text-align: left; display: inline-block; max-width: 100%;">
		{$title}
		<div class="master-post-advert-ad">{$options['code']}</div>
	</div>
</div>
EOA;

		if ($m['open'] && $m['close']) {
			return "{$ad}\n{$m[0]}";
		} else if ($m['open']) {
			return "{$ad}\n{$m['open']}{$m['more']}";
		} else if ($m['close']) {
			return "{$m['more']}{$m['close']}\n{$ad}";
		} else {
			return "\n{$ad}\n{$m['more']}";
		}

	}

}

// -----------------------------------------------------------------------------

function master_post_advert_init() {
	new MasterPostAdvert();
}
add_action('init', 'master_post_advert_init');