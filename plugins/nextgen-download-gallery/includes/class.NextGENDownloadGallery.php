<?php

if (!defined('ABSPATH')) {
	exit;
}

class NextGENDownloadGallery {

	protected $taglist = false;				// for recording taglist when building gallery for nggtags_ext shortcode

	/**
	* static method for getting the instance of this singleton object
	* @return self
	*/
	public static function getInstance() {
		static $instance = null;

		if ($instance === null) {
			$instance = new self();
		}

		return $instance;
	}

	/**
	* hide constructor
	*/
	private function __construct() {}

	/**
	* initialise plugin
	*/
	public function pluginStart() {
		add_action('init', 'ngg_download_gallery_load_text_domain');
		add_action('wp_enqueue_scripts', [$this, 'enqueueScripts']);

		add_action('admin_init', [$this, 'adminInit']);
		add_action('admin_menu', [$this, 'adminMenu'], 11);
		add_filter('plugin_row_meta', [$this, 'addPluginDetailsLinks'], 10, 2);

		// custom shortcodes
		add_shortcode('nggtags_ext', [$this, 'shortcodeTags']);

		// register "AJAX" actions (not really AJAX, just a cheap way into WordPress from the front end)
		add_action('wp_ajax_ngg-download-gallery-zip', [$this, 'nggDownloadZip']);
		add_action('wp_ajax_nopriv_ngg-download-gallery-zip', [$this, 'nggDownloadZip']);

		// register POST actions, for compatibility with custom templates created from v1.4.0
		// NB: see this support post for why this isn't the "official" way to grab a ZIP file:
		// @link https://wordpress.org/support/topic/only-administrator-can-download
		add_action('admin_post_ngg-download-gallery-zip', [$this, 'nggDownloadZip']);
		add_action('admin_post_nopriv_ngg-download-gallery-zip', [$this, 'nggDownloadZip']);

		// NextGEN Gallery integration
		add_filter('ngg_render_template', [$this, 'nggRenderTemplate'], 10, 2);

		// work-arounds for NGG2
		if (defined('NEXTGEN_GALLERY_PLUGIN_VERSION')) {
			add_filter('query_vars', [$this, 'addNgg2QueryVars']);

			// pick up tags when ngg_images uses tags
			add_filter('ngg_gallery_object', [$this, 'ngg2GalleryObject']);

			// tell NGG2 to look in plugin for legacy templates
			add_filter('ngg_legacy_template_directories', [$this, 'ngg2LegacyTemplateFolders']);
		}
	}

	/**
	* add back some missing query vars that NextGEN Gallery 2.0 has dropped
	* @param array $query_vars
	* @return array
	*/
	public function addNgg2QueryVars($query_vars) {
		$query_vars[] = 'gallerytag';

		return $query_vars;
	}

	/**
	* enqueue any scripts we need
	*/
	public function enqueueScripts() {
		$min = SCRIPT_DEBUG ? '' : '.min';
		$ver = SCRIPT_DEBUG ? time() : NGG_DLGALL_PLUGIN_VERSION;

		$options = $this->getOptions();

		wp_register_script('nextgen-download-gallery-form', plugins_url("static/js/download-form$min.js", NGG_DLGALL_PLUGIN_FILE), ['jquery'], $ver, true);
		wp_localize_script('nextgen-download-gallery-form', 'ngg_dlgallery', [
			'canDownloadAll'	=> !empty($options['enable_all']),
			'canSelectAll'		=> !empty($options['select_all']),
			'alertNoImages'		=> __('Please select one or more images to download', 'nextgen-download-gallery'),
		]);

		wp_enqueue_style('nextgen-download-gallery', plugins_url('static/css/style.css', NGG_DLGALL_PLUGIN_FILE), [], $ver);

		// FIXME: should be able to enqueue on demand! Find some way to tie script dependencies to NGG2 transient cached galleries
		if (defined('NEXTGEN_GALLERY_PLUGIN_VERSION')) {
			wp_enqueue_script('nextgen-download-gallery-form');
		}
	}

	/**
	* extend the nggtags shortcode to permit template specification
	* @param array $attrs
	* @param string $content
	* @return string
	*/
	public function shortcodeTags($attrs, $content = '') {
		$template = isset($attrs['template']) ? $attrs['template'] : '';
		$images = isset($attrs['images']) ? $attrs['images'] : false;

		if (!empty($attrs['album'])) {
			$out = $this->nggShowAlbumTags($attrs['album'], $template, $images);
		}
		else if (!empty($attrs['gallery'])) {
			$out = $this->nggShowGalleryTags($attrs['gallery'], $template, $images);
		}

		return $out;
	}

	/**
	* nggShowGalleryTags() - create a gallery based on the tags
	* copyright (c) Photocrati Media 2012, modified to permit a template specification
	* @param string $taglist list of tags as csv
	* @param string $template the template to use, if any
	* @param int $images how many images per page, defaults to all
	* @return string
	*/
	protected function nggShowGalleryTags($taglist, $template, $images = false) {

		// $_GET from wp_query
		$pid    = get_query_var('pid');
		$pageid = (int) get_query_var('pageid');

		// record taglist and set filter to override gallery title with taglist
		$this->taglist = $taglist;
		add_filter('ngg_gallery_object', [$this, 'nggGalleryObjectTagged']);

		// NextGEN Gallery 2 can show gallery of tags with template
		if (defined('NEXTGEN_GALLERY_PLUGIN_VERSION')) {
			$params = [
				'display_type'	=> 'photocrati-nextgen_basic_thumbnails',
				'tag_ids'		=> $taglist,
				'template'		=> $template,
			];
			$registry = \C_Component_Registry::get_instance();
			$renderer = $registry->get_utility('I_Displayed_Gallery_Renderer');
			$out = $renderer->display_images($params);
		}

		// build list of tagged images in NextCellent Gallery
		else {
			// get the related images
			$picturelist = \nggTags::find_images_for_tags($taglist , 'ASC');

			// look for ImageBrowser if we have a $_GET('pid')
			if ( $pageid === get_the_ID() || !is_home() ) {
				if (!empty( $pid ))  {
					$out = nggCreateImageBrowser($picturelist, $template);
					return $out;
				}
			}

			// nothing to see, move along...
			if ( empty($picturelist) ) {
				return;
			}

			// show gallery
			if ( is_array($picturelist) ) {
				// process gallery using selected template
				$out = nggCreateGallery($picturelist, false, $template, $images);
			}
		}

		// remove filter for gallery title
		remove_filter('ngg_gallery_object', [$this, 'nggGalleryObjectTagged']);

		$out = apply_filters('ngg_show_gallery_tags_content', $out, $taglist);
		return $out;
	}

	/**
	* nggShowAlbumTags() - create a gallery based on the tags
	* copyright (c) Photocrati Media 2012, modified to permit a template specification
	* @param string $taglist list of tags as csv
	* @param string $template the template to use, if any
	* @param int $images how many images per page, defaults to all
	* @return string
	*/
	protected function nggShowAlbumTags($taglist, $template, $images = false) {
		global $nggRewrite;

		// NextGEN Gallery 2.0[.7] defines class nggRewrite but doesn't instantiate it
		if (class_exists('nggRewrite') && !isset($nggRewrite)) {
			$nggRewrite = new \nggRewrite();
		}

		// $_GET from wp_query
		$tag            = get_query_var('gallerytag');
		$pageid         = (int) get_query_var('pageid');

		// look for gallerytag variable
		if ( $pageid === get_the_ID() || !is_home() )  {
			if (!empty( $tag ))  {
				$slug = esc_attr( $tag );
				$term = get_term_by('name', $slug, 'ngg_tag');
				$tagname = $term->name;
				$out  = sprintf('<div id="albumnav"><span><a href="%1$s" title="%2$s">%2$s</a> | %3$s</span></div>',
							esc_url(get_permalink()), __('Overview', 'nextgen-download-gallery'), esc_html($tagname));
				$out .=  $this->nggShowGalleryTags($slug, $template, $images);
				return $out;
			}
		}

		// get now the related images
		$picturelist = \nggTags::get_album_images($taglist);

		// nothing to see, move along...
		if ( empty($picturelist) ) {
			return;
		}

		// re-structure the object that we can use the standard template
		foreach ($picturelist as $key => $picture) {
			$picturelist[$key]->previewpic  = $picture->pid;
			$picturelist[$key]->previewname = $picture->filename;
			$picturelist[$key]->previewurl  = site_url("/{$picture->path}/thumbs/thumbs_{$picture->filename}");
			$picturelist[$key]->counter     = $picture->count;
			$picturelist[$key]->title       = $picture->name;
			$picturelist[$key]->pagelink    = $nggRewrite->get_permalink(['gallerytag' => $picture->slug]);
		}

		// TODO: Add pagination later
		$navigation = '<div class="ngg-clear"></div>';

		// create the output
		$out = \nggGallery::capture('album-compact', [
			'album'			=> 0,
			'galleries'		=> $picturelist,
			'pagination'	=> $navigation,
		]);

		$out = apply_filters('ngg_show_album_tags_content', $out, $taglist);

		return $out;
	}

	/**
	* override gallery title with taglist
	* @param stdClass $gallery
	* @return stdClass
	*/
	public function nggGalleryObjectTagged($gallery) {
		if ($this->taglist) {
			$gallery->title					= $this->getTitleFromTaglist($this->taglist);
			$gallery->nggDownloadTaglist	= $this->taglist;
		}

		return $gallery;
	}

	/**
	* confect gallery title from taglist
	* @param string $taglist;
	* @return string
	*/
	public function getTitleFromTaglist($taglist) {
		/* translators: gallery title when it has been created from image tags */
		$title = sprintf(__('tagged: %s', 'nextgen-download-gallery'), $taglist);

		return apply_filters('ngg_dlgallery_tags_gallery_title', $title, $taglist);
	}

	/**
	* handle NextGEN Gallery 2 tag galleries
	* @param stdClass $gallery
	* @return stdClass
	*/
	public function ngg2GalleryObject($gallery) {
		if ($gallery->displayed_gallery->source === 'tags') {
			$this->taglist = implode(',', $gallery->displayed_gallery->container_ids);
			$gallery = $this->nggGalleryObjectTagged($gallery);
		}
		else {
			$this->taglist = '';
		}

		return $gallery;
	}

	/**
	* add this plugin's template folder to NextGEN Gallery 2's list of legacy template folders
	* @param array $folders
	* @return array
	*/
	public function ngg2LegacyTemplateFolders($folders) {
		$folders[__('NextGEN Download Gallery', 'nextgen-download-gallery')] = NGG_DLGALL_PLUGIN_ROOT . 'templates';

		return $folders;
	}

	/**
	* tell NextGEN about our custom template
	* @param string $custom_template the path to the custom template file (or false if not known to us)
	* @param string $template_name name of custom template sought
	* @return string
	*/
	public function nggRenderTemplate($custom_template, $template_name) {
		$default = 'gallery-download';

		// detect complete filepath to default template, correct to simple template name
		$defaultPath = NGG_DLGALL_PLUGIN_ROOT . "templates/$default.php";
		if ($template_name === $defaultPath || $template_name === "gallery-$defaultPath") {
			$template_name = $default;
		}

		if ($template_name === $default) {
			// see if theme has customised this template
			$custom_template = locate_template("nggallery/$template_name.php");
			if (!$custom_template) {
				// no custom template so set to the default
				$custom_template = NGG_DLGALL_PLUGIN_ROOT . "templates/$template_name.php";
			}
		}

		if (basename($custom_template) === "$default.php") {
			wp_enqueue_style('nextgen-download-gallery');
			wp_enqueue_script('nextgen-download-gallery-form');
		}

		return $custom_template;
	}

	/**
	* @deprecated generate link for downloading everything; templates should now call getDownloadAllId()
	* @param object $gallery
	* @return string|false
	*/
	public static function getDownloadAllUrl($gallery) {
		$args = [
			'action'		=> 'ngg-download-gallery-zip',
			'all-id'		=> urlencode(self::getDownloadAllId($gallery)),
			'download-all'	=> 1,
		];

		$url = add_query_arg($args, admin_url('admin-ajax.php'));

		return $url;
	}

	/**
	* get list of expected NGG2 gallery query fields
	* @return array
	*/
	protected function getNgg2QueryFields() {
		return ['ID', 'container_ids', 'exclusions', 'excluded_container_ids', 'entity_ids', 'returns', 'source'];
	}

	/**
	* generate link for downloading everything, if configured
	* @param object $gallery
	* @return string|false
	*/
	public static function getDownloadAllId($gallery) {
		if (defined('NEXTGEN_GALLERY_PLUGIN_VERSION')) {
			$self = self::getInstance();

			// build query for NextGEN Gallery 2 virtual gallery
			$params = $gallery->displayed_gallery->get_entity();
			$query = [];
			foreach ($self->getNgg2QueryFields() as $field) {
				if (!empty($params->$field)) {
					$query[$field] = $params->$field;
				}
			}

			// encode to prevent accidental corruption
			$json = json_encode($query);
			$id = base64_encode($json);
		}
		else {
			// legacy plugin
			if (empty($gallery->nggDownloadTaglist)) {
				// just a gallery
				$id = $gallery->ID;
			}
			else {
				// virtual gallery from tags
				$id = md5($gallery->nggDownloadTaglist);
			}
		}

		return $id;
	}

	/**
	* build download title from NextGEN 2 displayed gallery properties, e.g. selected galleries, taglist, etc.
	* @param C_Displayed_Gallery $displayed_gallery
	* @return string
	*/
	protected function getNgg2DownloadTitle($displayed_gallery) {
		switch ($displayed_gallery->source) {

			case 'galleries':
				$titles = [];
				foreach ($displayed_gallery->container_ids as $gallery_id) {
					$gallery = nggdb::find_gallery($gallery_id);
					$titles[] = $gallery->title;
				}
				$title = implode(',', $titles);
				break;

			case 'tags':
				$taglist = implode(',', $displayed_gallery->container_ids);
				$title = $this->getTitleFromTaglist($taglist);
				break;

			default:
				// allow title to be confected
				// TODO: extend this function to pick up other NGG2 gallery sources
				$title = '';
				break;

		}

		// restrict length to 250 characters
		$title = substr($title, 0, 250);

		return $title;
	}

	/**
	* POST action for downloading a bunch of NextGEN gallery images as a ZIP archive
	*/
	public function nggDownloadZip() {
		global $nggdb;

		// pick up gallery ID and array of image IDs from AJAX request
		$images  = isset($_REQUEST['pid']) && is_array($_REQUEST['pid']) ? array_map('intval', $_REQUEST['pid']) : false;
		$gallery = isset($_REQUEST['gallery']) ? trim(wp_unslash($_REQUEST['gallery'])) : '';
		$tags    = isset($_REQUEST['all-tags']) ? trim(wp_unslash($_REQUEST['all-tags'])) : '';
		$allID   = !empty($_REQUEST['download-all']) && !empty($_REQUEST['all-id']) ? wp_unslash($_REQUEST['all-id']) : false;

		// sanity check
		if (!is_object($nggdb)) {
			wp_die(__('NextGEN Download Gallery requires NextGEN Gallery or NextCellent Gallery', 'nextgen-download-gallery'));
		}

		// check for GET request bundling images into a comma-separated string
		if (empty($images) && !empty($_GET['pids'])) {
			$images = explode('~', trim(wp_unslash($_GET['pids'])));
			$images = array_map('intval', $images);
		}

		// check for Chrome on iOS and maybe kludge for it
		$this->maybeKludgeChromeIOS($images, $gallery, $tags, $allID);

		// check for request to download everything
		if ($allID) {
			if (defined('NEXTGEN_GALLERY_PLUGIN_VERSION')) {
				// decode NGG2 encoded query for virtual gallery
				$json = base64_decode($allID);
				$query = $json ? json_decode($json, true) : false;

				// reduce query to permitted fields
				$query = $query ? array_intersect_key($query, array_flip($this->getNgg2QueryFields())) : false;

				if (!empty($query)) {
					$mapper = \C_Displayed_Gallery_Mapper::get_instance();
					$factory = \C_Component_Factory::get_instance();
					$displayed_gallery = $factory->create('displayed_gallery', $query, $mapper);

					$entities = $displayed_gallery->get_entities(false, false, true);

					$images = [];
					foreach ($entities as $image) {
						$images[] = $image->pid;
					}

					if (empty($gallery)) {
						$gallery = $this->getNgg2DownloadTitle($displayed_gallery);
					}
				}
			}
			else {
				$images = $nggdb->get_ids_from_gallery($allID);
			}
		}
		elseif ($tags) {
			$picturelist = \nggTags::find_images_for_tags($tags, 'ASC');
			$images = [];
			foreach ($picturelist as $image) {
				$images[] = $image->pid;
			}
		}

		// if no gallery name, confect one
		if (empty($gallery)) {
			$gallery = md5(implode(',', $images));
		}

		if (is_array($images) && count($images) > 0) {
			// phpcs:disable Generic.PHP.NoSilencedErrors.Discouraged

			// allow a long script run for pulling together lots of images
			@set_time_limit(HOUR_IN_SECONDS);

			// stop/clear any output buffering
			while (ob_get_level()) {
				ob_end_clean();
			}

			// turn off compression on the server
			if (function_exists('apache_setenv'))
				@apache_setenv('no-gzip', 1);
			@ini_set('zlib.output_compression', 'Off');

			if (!class_exists('PclZip')) {
				require ABSPATH . 'wp-admin/includes/class-pclzip.php';
			}

			$filename = tempnam(get_temp_dir(), 'zip');
			$zip = new PclZip($filename);
			$files = [];

			foreach ($images as $image) {
				$image = $nggdb->find_image($image);
				if ($image) {
					$files[] = apply_filters('ngg_dlgallery_image_path', $image->imagePath, $image, $gallery);
				}
			}

			if (count($files) > 0) {
				// allow other plugins / themes to preprocess files added to the zip archive
				$preAddCallback = apply_filters('ngg_dlgallery_zip_pre_add', '__return_true');

				// create the Zip archive, without paths or compression (images are generally already compressed)
				$properties = $zip->create($files, PCLZIP_OPT_REMOVE_ALL_PATH, PCLZIP_OPT_NO_COMPRESSION, PCLZIP_CB_PRE_ADD, $preAddCallback);
				if (!is_array($properties)) {
					wp_die($zip->errorInfo(true));
				}
				unset($zip);

				// send the Zip archive to the browser
				$zipName = apply_filters('ngg_dlgallery_zip_filename', sanitize_file_name(strtr($gallery, ',', '-')) . '.zip', $gallery);
				do_action('ngg_dlgallery_zip_before_send', $zipName, $filename, $images);
				header_remove();
				nocache_headers();
				header('Content-Description: File Transfer');
				header('Content-Type: application/zip');
				header("Content-Disposition: attachment; filename=\"$zipName\"");
				header('Content-Transfer-Encoding: binary');
				header('Content-Length: ' . filesize($filename));

				$chunksize = 1024 * 1024;
				$file = @fopen($filename, 'rb');
				if ($file !== false) {
					while (!feof($file)) {
						echo @fread($file, $chunksize);
						flush();
					}
					fclose($file);

					// check for bug in some old PHP versions, close a second time!
					if (is_resource($file)) {
						@fclose($file);
					}
				}

				do_action('ngg_dlgallery_zip_after_send', $zipName, $filename, $images);

				// delete the temporary file
				@unlink($filename);

				exit;
			}

			// phpcs:enable

			// no files, report to user
			wp_die(__('No files found to download', 'nextgen-download-gallery'));
		}

		wp_die(__('No images selected for download', 'nextgen-download-gallery'));
	}

	/**
	* if POST request came from Google Chrome on iOS, try to work around it by redirecting to a GET request
	* @param array $images
	* @param string $gallery
	* @param string $tags
	* @param string $allID
	*/
	protected function maybeKludgeChromeIOS($images, $gallery, $tags, $allID) {
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			return;
		}

		// @link https://developer.chrome.com/multidevice/user-agent
		if (!isset($_SERVER['HTTP_USER_AGENT']) || strpos($_SERVER['HTTP_USER_AGENT'], 'CriOS/') === false) {
			return;
		}

		$request = [
			'action'		=> 'ngg-download-gallery-zip',
			'gallery'		=> rawurlencode($gallery),
		];

		if ($allID) {
			$request['download-all'] = 1;
			$request['all-id'] = rawurlencode($allID);
		}
		elseif (!empty($tags)) {
			$request['all-tags'] = rawurlencode($tags);
		}
		elseif (is_array($images) && count($images) > 0) {
			$request['pids'] = implode('~', $images);
		}

		$url = add_query_arg($request, admin_url('admin-ajax.php'));

		header("Location: $url");
		exit;
	}

	/**
	* initialise settings for admin
	*/
	public function adminInit() {
		add_settings_section(NGG_DLGALL_OPTIONS, false, false, NGG_DLGALL_OPTIONS);
		register_setting(NGG_DLGALL_OPTIONS, NGG_DLGALL_OPTIONS, [$this, 'settingsValidate']);
	}

	/**
	* action hook for adding plugin details links
	*/
	public function addPluginDetailsLinks($links, $file) {
		if ($file === NGG_DLGALL_PLUGIN_NAME) {
			$links[] = sprintf('<a href="https://wordpress.org/support/plugin/nextgen-download-gallery" rel="noopener" target="_blank">%s</a>', _x('Get help', 'plugin details links', 'nextgen-download-gallery'));
			$links[] = sprintf('<a href="https://wordpress.org/plugins/nextgen-download-gallery/" rel="noopener" target="_blank">%s</a>', _x('Rating', 'plugin details links', 'nextgen-download-gallery'));
			$links[] = sprintf('<a href="https://translate.wordpress.org/projects/wp-plugins/nextgen-download-gallery" rel="noopener" target="_blank">%s</a>', _x('Translate', 'plugin details links', 'nextgen-download-gallery'));
			$links[] = sprintf('<a href="https://shop.webaware.com.au/donations/?donation_for=NextGEN+Download+Gallery" rel="noopener" target="_blank">%s</a>', _x('Donate', 'plugin details links', 'nextgen-download-gallery'));
		}

		return $links;
	}

	/**
	* admin menu items
	*/
	public function adminMenu() {
		if (!defined('NGGFOLDER')) {
			return;
		}

		add_submenu_page(NGGFOLDER, 'Download Gallery', 'Download Gallery', 'manage_options', 'ngg-dlgallery', [$this, 'settingsPage']);
	}

	/**
	* settings admin
	*/
	public function settingsPage() {
		$options = $this->getOptions();

		if (!isset($options['enable_all'])) {
			$options['enable_all'] = 0;
		}

		require NGG_DLGALL_PLUGIN_ROOT . 'views/settings-form.php';
	}

	/**
	* validate settings on save
	* @param array $input
	* @return array
	*/
	public function settingsValidate($input) {
		$output = [];

		$output['enable_all'] = empty($input['enable_all']) ? 0 : 1;
		$output['select_all'] = empty($input['select_all']) ? 0 : 1;

		return $output;
	}

	/**
	* get plugin options, setting defaults if values are missing
	* @return array
	*/
	protected function getOptions() {
		return wp_parse_args((array) get_option(NGG_DLGALL_OPTIONS, []), [
			'enable_all'		=> 1,
			'select_all'		=> 1,
		]);
	}

}
