<?php



/**
 * Aarambha Demo Sites Core.
 *
 * This class handles few things.
 * 1. Downloads the required demo files.
 * 2. Imports Content
 * 3. Imports Customizer Informations.
 * 4. Imports Widgets.
 * 5. Sets up pages.
 * 6. Finalizes the Import Process.
 *
 * @since 1.0.0
 */
if (!defined('WPINC')) {
    exit;    // Exit if accessed directly.
}

/**
 * Class:: Aarambha_DS_Core.
 *
 * Content Importer.
 */
class Aarambha_DS_Core
{
    /**
     * Single class instance.
     *
     * @since 1.0.0
     *
     * @var object
     */
    private static $instance = null;

    /**
     * Records the time.
     *
     * @since 1.0.0
     *
     * @var object
     */
    private $microtime;

    /**
     * Stores the request instance to prevent failure of nonce validation
     * @provide the complete request object for AJAX.
     */
    protected $request = [];

    /**
     * Ensures only one instance of this class is available.
     *

     *
     * @version 1.0.0
     *
     * @since 1.0.0
     *
     * @return object Aarambha_DS_Core
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * A dummy constructor to prevent this class from being loaded more than once.
     *
     * @see Aarambha_DS_Core::getInstance()
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    private function __construct()
    {
        /* We do nothing here! */
    }

    /**
     * You cannot clone this class.
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function __clone()
    {
        _doing_it_wrong(
            __FUNCTION__,
            esc_html__('Cheatin&#8217; huh?', 'aarambha-demo-sites'),
            '1.0.0'
        );
    }

    /**
     * You cannot unserialize instance of this class.
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function __wakeup()
    {
        _doing_it_wrong(
            __FUNCTION__,
            esc_html__('Cheatin&#8217; huh?', 'aarambha-demo-sites'),
            '1.0.0'
        );
    }

    /**
     * Inject the importer where required.
     */
    private function injectImporter()
    {
        if ( ! defined( 'WP_LOAD_IMPORTERS' ) ) {
			define( 'WP_LOAD_IMPORTERS', true );
		}

        // Load Importer API.
		require_once ABSPATH . 'wp-admin/includes/import.php';

		if ( ! class_exists( 'WP_Importer' ) ) {
			$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';

			if ( file_exists( $class_wp_importer ) ) {
				require $class_wp_importer;
			}
		}

		// Include WXR Importer.
		require dirname( __FILE__ ) . '/wordpress-importer/class-wxr-importer.php';
    }

    /**
     * Prepares the import.
     *
     * @param array Demo
     *
     * @return bool
     */
    public function prepare($demo)
    {
        $slug = $demo['slug'];

        $dir = $this->createDir($slug);

        $files = $this->download($demo, $dir);

        return $files;
    }

    /**
     * Creates the directory.
     * 
     * @param string $slug.
     */
    public function createDir($slug)
    {
        $dir = aarambha_ds_get_custom_uploads_dir();
        $path  = "{$dir}/$slug";

        if (!file_exists($path)) {
            wp_mkdir_p(trailingslashit($path));
        }

        return $path;
    }

    /**
     * Downloads the file.
     * 
     * @param array $files  List of files to be downloaded.
     * @param string $dir   Download path for the files.
     * 
     * @return array Files downloaded & written.
     */
    public function download($demo, $dir)
    {
        $name = $demo['slug'];

        $args = [];
        $args['theme'] = (isset($demo['theme'])) ? $demo['theme'] : 'neostore';
        $args['demo'] = $name;

        if (!function_exists('download_url')) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }

        $files = [];

        if (isset($demo['files'])) {

            foreach ($demo['files'] as $key => $file) {

                if (empty($file)) {
                    continue;
                }

                if (is_array($file)) {
                    foreach ($file as $filename) {
                        $name = $filename['file'];

                        $file_name = "{$dir}/{$name}";

                        $args['file'] = $name;
                        $url = Aarambha_DS()->api()->downloadUrl($args, $dir);

                        // Download the file.
                        $download = download_url($url);

                        // Get the file content.
                        $content = @file_get_contents($download);

                        $file_handle = @fopen($file_name, 'w'); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged, WordPress.WP.AlternativeFunctions.file_system_read_fopen

                        if ($file_handle) {
                            $files[$key] = $file;

                            fwrite($file_handle, $content); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_fwrite

                            fclose($file_handle); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_fclose
                        }
                    }
                } else {
                    $file_name = "{$dir}/$file";

                    // Check if file exists.
                    if (file_exists($file_name)) {
                        $files[$key] = $file;
                        continue;
                    }

                    $args['file'] = $file;
                    $url = Aarambha_DS()->api()->downloadUrl($args, $dir);

                    // Download the file.
                    $download = download_url($url);


                    // Get the file content.
                    $content = @file_get_contents($download);

                    $file_handle = @fopen($file_name, 'w'); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged, WordPress.WP.AlternativeFunctions.file_system_read_fopen

                    if ($file_handle) {
                        $files[$key] = $file;

                        fwrite($file_handle, $content); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_fwrite

                        fclose($file_handle); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_fclose
                    }
                }
            }
        }

        return $files;
    }

    /**
     * Begin the import process.
     * 
     * @return bool
     */
    public function content($file, $req = [])
    {

        $this->injectImporter();

        if (!empty($req)) {
            $this->request = $req;
        }

        $wp_import                    = new Aarambha_DS_WXR_Importer();
		$wp_import->fetch_attachments = true;

        try {
            ob_start();
			$result = $wp_import->import( $file );
			ob_end_clean();

            if (is_wp_error($result)) {
                return ['action' => 'terminate', 'message' => $result->get_error_message()];
            } else {
                // next action to trigger
                $response = [
                    'message'    => esc_html__('Importing Options', 'aarambha-demo-sites'),
                    'action'     => 'import-customize',
                ];

                return $response;
            }
        } catch (Exception $e) {
            return ['action'  => 'terminate', 'message' =>  $e->getMessage()];
        }
        return true;
    }

    public function customizer($import_file)
    {
        // Include WXR Importer.
        require dirname( __FILE__ ) . '/customize/class-aarambha-ds-customize-importer.php';

        $results = Aarambha_DS_Customize_Importer::import( $import_file );

        return true;
    }

    /**
     * Import the widget.
     * 
     * @return bool
     */
    public function widgets($import_file)
    {
        // Include WXR Importer.
        require dirname( __FILE__ ) . '/class-aarambha-ds-widget-importer.php';

        $results = Aarambha_DS_Widget_Importer::import( $import_file );

        return true;
    }

    /**
     * Import the slider datas.
     * 
     * @return bool.
     */
    public function slider($file)
    {
        // Smart Slider plugin is inactive.
        if (!class_exists('SmartSlider3')) {
            return true;
        }

        SmartSlider3::import($file);

        return true;
    }

    /**
     * Setup pages.
     * 
     * @return bool.
     */
    public function setupPages($pages)
    {


        return true;
    }

    /**
     * Setup menu navigation.
     * 
     * @return bool.
     */
    public function setupNavigation($navigations)
    {
        $locations = get_theme_mod('nav_menu_locations');

        foreach ($navigations as $key => $value) {
            $menu = get_term_by('name', $value, 'nav_menu');

            if (isset($menu->term_id)) {
                $locations[$key] = $menu->term_id;
            }
        }

        set_theme_mod('nav_menu_locations', $locations);

        return true;
    }
}
