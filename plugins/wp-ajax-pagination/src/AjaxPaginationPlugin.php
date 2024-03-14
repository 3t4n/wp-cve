<?php namespace AjaxPagination;

use Premmerce\SDK\V2\FileManager\FileManager;
use AjaxPagination\Admin\Admin;
use AjaxPagination\Admin\Settings;
use AjaxPagination\Frontend\Frontend;
use AjaxPagination\Admin\Customizer;

/**
 * Class AjaxPaginationPlugin
 *
 * @package AjaxPagination
 */
class AjaxPaginationPlugin {

	/**
	 * @var FileManager
	 */
	private $fileManager;

	/**
	 * AjaxPaginationPlugin constructor.
	 *
     * @param string $mainFile
	 */
    public function __construct($mainFile) {
        $this->fileManager = new FileManager($mainFile);

        add_action('plugins_loaded', [ $this, 'loadTextDomain' ]);

	}

	/**
	 * Run plugin part
	 */
	public function run() {
		if ( is_admin() ) {
			new Admin( $this->fileManager );
		} else {
			new Frontend( $this->fileManager );
		}
            new Customizer($this->fileManager);

	}

    /**
     * Load plugin translations
     */
    public function loadTextDomain()
    {
        $name = $this->fileManager->getPluginName();
        load_plugin_textdomain('wp-ajax-pagination', false, $name . '/languages/');
    }

	/**
	 * Fired when the plugin is activated
	 */
	public function activate() {
		// TODO: Implement activate() method.

        $options = get_option( Settings::OPTIONS );
        $options['paginationType'] = 'ajax';
        $options['postsSelector'] = array(Settings::POSTS_SELECTOR);
        $options['navigationSelector'] = array(Settings::NAVIGATION_SELECTOR);
        $options['jsCode'] = Settings::JS_CODE;
        $options['pagingUrl'] = 1;
        update_option(Settings::OPTIONS, $options);

	}

	/**
	 * Fired when the plugin is deactivated
	 */
	public function deactivate() {
		// TODO: Implement deactivate() method.


	}

	/**
	 * Fired during plugin uninstall
	 */
	public static function uninstall() {
		// TODO: Implement uninstall() method.

        delete_option(Settings::OPTIONS);
	}
}