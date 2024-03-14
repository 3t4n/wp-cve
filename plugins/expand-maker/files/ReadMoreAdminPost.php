<?php
Class ReadMoreAdminPost {

	public function __construct() {

		$this->actions();
	}

	public function actions() {

		add_action('admin_post_save_data', array($this, 'expmSaveData'));
		add_action('admin_post_save_new_data', array($this, 'newSavedData'));
		add_action('admin_post_delete_readmore', array($this, 'expmDeleteData'));
		add_action('admin_post_read_more_clone', array($this, 'cloneReadMore'));
		add_action('admin_post_yrmSaveSettings', array($this, 'saveSettings'));

		add_action('admin_post_yrm_fr_save_data', array($this, 'FarSaveData'));
		add_filter('yrmSavedData', array($this, 'filterSaveData'));
	}

	public function FarSaveData() {
		global $wpdb;
		if(isset($_POST)) {
			// Check for CSRF
			check_admin_referer('read_more_save');
		}

		$data = ReadMore::parseDataFromPost($_POST);
		$data = apply_filters('yrmSavedData', $data);
		ReadMore::create($data);
	}

	public function saveSettings() {
		if(
			!isset($_POST[YRM_ADMIN_POST_NONCE])
			|| !wp_verify_nonce($_POST[YRM_ADMIN_POST_NONCE], 'YRM_ADMIN_POST_NONCE')
		) {
			_e('Sorry, your nonce did not verify.', YRM_LANG);die();
		}

		$options = array(
			'yrm-delete-data',
			'yrm-hid-find-and-replace-menu',
			'yrm-hid-accordion-menu',
			'yrm-user-roles',
			'yrm-hide-media-buttons',
			'yrm-hide-google-fonts'
		);

		foreach ($options as $option) {
			$current = '';
			if (!empty($_POST[$option])) {
				$current = $_POST[$option];
			}
			update_option($option, $current);
		}

		wp_redirect(admin_url().'admin.php?page=rmmore-settings&saved=1');
	}

	public function cloneReadMore() {

		$id = (int)$_GET['id'];
		$dataObj = new ReadMoreData();
		$dataObj->setId($id);
		$savedData = $dataObj->getSavedOptions();
		global $wpdb;

		$data = array(
			'type' => $savedData['type'],
			'expm-title' => $savedData['expm-title'].'(clone)',
			'button-width' => $savedData['button-width'],
			'button-height' => $savedData['button-height'],
			'animation-duration' => $savedData['animation-duration'],
			'options' => json_encode($savedData)
		);

		$format = array(
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
		);

		$wpdb->insert($wpdb->prefix.'expm_maker', $data, $format);
		wp_redirect(admin_url()."admin.php?page=readMore");
	}

	public function expmSanitizeData($optionName, $secondArg = false) {

		if(isset($_POST[$optionName])) {
			return sanitize_text_field($_POST[$optionName]);
		}
		return '';
	}

	public function expmDeleteData() {
		
		global $wpdb;
		$id = absint($_GET['readMoreId']);
		$wpdb->delete($wpdb->prefix.'expm_maker', array('id'=>$id), array('%d'));
		do_action('YrmDeleteReadMore', $id);
		wp_redirect(admin_url()."admin.php?page=ExpMaker");
	}

	public function addToPosts($posts, $postId, $options) {
		global $wpdb;

		$format = array(
			'%d',
			'%d',
			'%s',
		);
		$wpdb->delete($wpdb->prefix.'expm_maker_pages', array('button_id'=>$postId), array('%d'));
		if(!empty($posts)) {
			foreach ($posts as $post) {
				$wpdb->delete($wpdb->prefix.'expm_maker_pages', array('post_id'=>$post), array('%d'));
				$data = array(
					'post_id' => $post,
					'button_id' => $postId,
					'options' => json_encode($options)
				);

				$wpdb->insert($wpdb->prefix.'expm_maker_pages', $data, $format);
			}
		}
		
	}

	public function expmSaveData() {

		global $wpdb;
		if(isset($_POST)) {
			// Check for CSRF
			check_admin_referer('read_more_save');
		}
		$options = array(
			'font-size'=> $this->expmSanitizeData('font-size'),
			'yrm-btn-hover-animate' => $_POST['yrm-btn-hover-animate']
		);
		$options['scroll-to-initial-position'] = $this->expmSanitizeData('scroll-to-initial-position', true);
		$options['yrm-dimension-mode'] = $this->expmSanitizeData('yrm-dimension-mode');
		$options['yrm-button-padding-top'] = $this->expmSanitizeData('yrm-button-padding-top');
		$options['yrm-button-padding-right'] = $this->expmSanitizeData('yrm-button-padding-right');
		$options['yrm-button-padding-bottom'] = $this->expmSanitizeData('yrm-button-padding-bottom');
		$options['yrm-button-padding-left'] = $this->expmSanitizeData('yrm-button-padding-left');
		$options['show-content-gradient'] = $this->expmSanitizeData('show-content-gradient');
		$options['show-content-gradient-height'] = $this->expmSanitizeData('show-content-gradient-height');
		$options['show-content-gradient-position'] = $this->expmSanitizeData('show-content-gradient-position');
		$options['yrm-more-button-custom-class'] = $this->expmSanitizeData('yrm-more-button-custom-class');
		$options['yrm-less-button-custom-class'] = $this->expmSanitizeData('yrm-less-button-custom-class');
		$options['yrm-custom-css'] = $this->expmSanitizeData('yrm-custom-css');
		$options['yrm-editor-js'] = $this->expmSanitizeData('yrm-editor-js', 1);
		$options['hide-button-after-click'] = $this->expmSanitizeData('hide-button-after-click', true);
		$options['hide-button-blog-post'] = $this->expmSanitizeData('hide-button-blog-post', true);
		$options['default-show-hidden-content'] = $this->expmSanitizeData('default-show-hidden-content', true);
		$options['link-button-url'] = $this->expmSanitizeData('link-button-url');
		$options['link-button-new-tab'] = $this->expmSanitizeData('link-button-new-tab', true);
		$options['yrm-button-opacity'] = $this->expmSanitizeData('yrm-button-opacity');
		$options['yrm-cursor'] = $this->expmSanitizeData('yrm-cursor');
		$options['enable-tooltip'] = $this->expmSanitizeData('enable-tooltip', true);
		$options['enable-tooltip-text'] = $this->expmSanitizeData('enable-tooltip-text');
		$options['yrm-enable-decoration'] = $this->expmSanitizeData('yrm-enable-decoration', true);
		$options['yrm-decoration-type'] = $this->expmSanitizeData('yrm-decoration-type');
		$options['yrm-decoration-style'] = $this->expmSanitizeData('yrm-decoration-style');
		$options['yrm-decoration-color'] = $this->expmSanitizeData('yrm-decoration-color');

		if(YRM_PKG > YRM_FREE_PKG) {
			$options['yrm-hidden-data-load-mode'] = $this->expmSanitizeData('yrm-hidden-data-load-mode');
			$options['load-data-delay'] = $this->expmSanitizeData('load-data-delay');
			$options['load-data-after-action'] = $this->expmSanitizeData('load-data-after-action', true);
			$options['link-button-confirm'] = $this->expmSanitizeData('link-button-confirm', true);
			$options['link-button-confirm-text'] = $this->expmSanitizeData('link-button-confirm-text');
			$options['auto-open'] = $this->expmSanitizeData('auto-open', true);
			$options['auto-open-delay'] = $this->expmSanitizeData('auto-open-delay');
			$options['auto-close'] = $this->expmSanitizeData('auto-close', true);
			$options['auto-close-delay'] = $this->expmSanitizeData('auto-close-delay');
			$options['hidden-content-bg-image'] = $this->expmSanitizeData('hidden-content-bg-image', true);
			$options['hidden-content-bg-img-size'] = $this->expmSanitizeData('hidden-content-bg-img-size');
			$options['hidden-content-bg-repeat'] = $this->expmSanitizeData('hidden-content-bg-repeat');
			$options['hidden-bg-image-url'] = $this->expmSanitizeData('hidden-bg-image-url');
			$options['btn-background-color'] = $this->expmSanitizeData('btn-background-color');
			$options['btn-text-color'] = $this->expmSanitizeData('btn-text-color');
			$options['btn-border-radius'] = $this->expmSanitizeData('btn-border-radius');
			$options['horizontal'] = $this->expmSanitizeData('horizontal');
			$options['vertical'] = $this->expmSanitizeData('vertical');
			$options['expander-font-family'] = $this->expmSanitizeData('expander-font-family');
			$options['btn-custom-font-family'] = $this->expmSanitizeData('btn-custom-font-family');
			$options['show-only-devices'] = $this->expmSanitizeData('show-only-devices');
			$options['hidden-content-font-size-enable'] = $this->expmSanitizeData('hidden-content-font-size-enable');
			$options['hidden-content-font-size'] = $this->expmSanitizeData('hidden-content-font-size');
			$options['hide-content'] = $this->expmSanitizeData('hide-content');
			$options['yrm-selected-devices'] = @$_POST['yrm-selected-devices'];
			$options['hover-effect'] = $this->expmSanitizeData('hover-effect');
			$options['button-border'] = $this->expmSanitizeData('button-border');
			$options['button-border-width'] = $this->expmSanitizeData('button-border-width');
			$options['button-border-color'] = $this->expmSanitizeData('button-border-color');
			$options['button-box-shadow'] = $this->expmSanitizeData('button-box-shadow');
			$options['button-box-shadow-horizontal-length'] = $this->expmSanitizeData('button-box-shadow-horizontal-length');
			$options['button-box-shadow-vertical-length'] = $this->expmSanitizeData('button-box-shadow-vertical-length');
			$options['button-box-spread-radius'] = $this->expmSanitizeData('button-box-spread-radius');
			$options['button-box-blur-radius'] = $this->expmSanitizeData('button-box-blur-radius');
			$options['button-box-shadow-color'] = $this->expmSanitizeData('button-box-shadow-color');
			$options['btn-hover-text-color'] = $this->expmSanitizeData('btn-hover-text-color');
			$options['btn-hover-bg-color'] = $this->expmSanitizeData('btn-hover-bg-color');
			$options['hidden-content-bg-color'] = $this->expmSanitizeData('hidden-content-bg-color');
			$options['hidden-inner-width'] = $this->expmSanitizeData('hidden-inner-width');
			$options['hidden-content-font-family'] = $this->expmSanitizeData('hidden-content-font-family');
			$options['hidden-content-align'] = $this->expmSanitizeData('hidden-content-align');
			$options['yrm-hidden-content-line-height'] = $this->expmSanitizeData('yrm-hidden-content-line-height');
			$options['yrm-hidden-content-line-height-size'] = $this->expmSanitizeData('yrm-hidden-content-line-height-size');
			$options['hidden-content-custom-font-family'] = $this->expmSanitizeData('hidden-content-custom-font-family');
			$options['hidden-content-text-color'] = $this->expmSanitizeData('hidden-content-text-color');
			$options['hidden-content-padding'] = $this->expmSanitizeData('hidden-content-padding');
			$selectedPosts = @$_POST['yrm-selected-post'];
			$options['yrm-selected-post'] = $selectedPosts;
			$options['button-for-post'] = $this->expmSanitizeData('button-for-post');
			$options['yrm-button-for-post'] = $this->expmSanitizeData('yrm-button-for-post');
			$options['hide-after-word-count'] = $this->expmSanitizeData('hide-after-word-count');

			$options['show-content-gradient-color'] = $this->expmSanitizeData('show-content-gradient-color');
			$options['enable-button-icon'] = $this->expmSanitizeData('enable-button-icon');
			$options['arrow-icon-width'] = $this->expmSanitizeData('arrow-icon-width');
			$options['arrow-icon-height'] = $this->expmSanitizeData('arrow-icon-height');
			$options['arrow-icon-alignment'] = $this->expmSanitizeData('arrow-icon-alignment');
			$options['yrm-button-icon'] = $this->expmSanitizeData('yrm-button-icon');


			$pagesOptions = array(
				'yrm-button-for-post' => $options['yrm-button-for-post'],
				'button-for-post' => $options['button-for-post'],
				'hide-after-word-count' => $options['hide-after-word-count']
			);
		}

		$options['more-button-title'] = $this->expmSanitizeData('more-button-title');
		$options['more-title'] = $this->expmSanitizeData('more-title');
		$options['less-button-title'] = $this->expmSanitizeData('less-button-title');
		$options['less-title'] = $this->expmSanitizeData('less-title');
		$options['add-button-next-content'] = $this->expmSanitizeData('add-button-next-content');
		
		$options['yrm-btn-font-weight'] = $this->expmSanitizeData('yrm-btn-font-weight');
		$options['yrm-animate-easings'] = apply_filters('yrm-save-easings', $this->expmSanitizeData('yrm-animate-easings'));
		$options = apply_filters('yrmSaveOptions', $options, $this);

		$options = json_encode($options);
		$id = $this->expmSanitizeData('read-more-id');
		$title = $this->expmSanitizeData('expm-title');
		$type = $this->expmSanitizeData('read-more-type');
		$width = $this->expmSanitizeData('button-width');
		$height = $this->expmSanitizeData('button-height');
		$duration = $this->expmSanitizeData('animation-duration');

		$data = array(
			'type' => $type,
			'expm-title' => $title,
			'button-width' => $width,
			'button-height' => $height,
			'animation-duration' => $duration,
			'options' => $options
		);
	
		$format = array(
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
		);
		if(!$id) {
			$wpdb->insert($wpdb->prefix.'expm_maker', $data, $format);
			$readMoreId = $wpdb->insert_id;
		}
		else {
			$data['id'] = $id;
			$wpdb->update($wpdb->prefix.'expm_maker', $data, array('id'=>$id), $format, array('%d'));
			$readMoreId = $id;
		}
		if(YRM_PKG > YRM_FREE_PKG) {
			$this->addToPosts($selectedPosts,$readMoreId,$pagesOptions);
		}

		wp_redirect(admin_url()."admin.php?page=button&readMoreId=".$readMoreId."&yrm_type=".$type."&saved=1");
	}

	public function newSavedData() {
		global $wpdb;
		if(isset($_POST)) {
			// Check for CSRF
			check_admin_referer('read_more_types_save');
		}

		global $YRM_TYPES;
		$postData = $_POST;
		$postData = stripslashes_deep($postData);

		if (!empty($postData['yrm-type'])) {
			$type = $postData['yrm-type'];
			if(file_exists($YRM_TYPES[$type])) {
				$className = ucfirst($type).'TypeReadMore';
				require_once($YRM_TYPES[$type].$className.'.php');
				$dataObj = new ReadMoreData();
				$dataObj->setId($postData['yrm-post-id']);

				$obj = new $className($dataObj);
				if($obj instanceof ReadMoreTypes) {
					$obj->create($postData);
				}
			}
		}
	}

	public function filterSaveData($form) {
		$form = stripslashes_deep($form);
		return $form;
	}
}

$readMoreAdminPost = new ReadMoreAdminPost();