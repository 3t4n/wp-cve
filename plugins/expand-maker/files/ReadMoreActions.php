<?php
use yrm\Updates;

CLass ReadMoreActions {

	public function __construct() {

		add_action('init', array($this, 'yrmInit'));
		add_action('admin_enqueue_scripts', array(new ReadMoreStyles(),'registerStyles'));
		add_action('wp_head', array($this, 'readMoreWpHead'));
		add_action('the_content', array($this, 'findAndReplace'));
		if(YRM_PKG > YRM_FREE_PKG) {
			add_action('the_content', array($this, 'postContentFiler'));
		}
		add_action('yrm-easings', array($this, 'easingsFilter'));
		add_action('yrm-save-easings', array($this, 'saveEasings'));
		add_action('admin_head', array($this, 'adminHead'));
	}

	public function yrmInit() {
		$this->revieNotice();
		$ajaxObj = new RadMoreAjax();
		$ajaxObj->init();
		
		if(YRM_PKG > YRM_FREE_PKG) {
			new Updates();
		}
	}

	private function revieNotice() {
		add_action('admin_notices', array($this, 'showReviewNotice'));
		add_action('network_admin_notices', array($this, 'showReviewNotice'));
		add_action('user_admin_notices', array($this, 'showReviewNotice'));
	}

	public function showReviewNotice() {
		echo new YrmShowReviewNotice();
	}

	public function saveEasings($savedData) {

        if (YRM_PKG == YRM_FREE_PKG && $savedData != 'linear' && $savedData != 'swing') {
            $savedData = 'swing';
        }

        return $savedData;
    }
	public function easingsFilter($easings) {

        if (empty($easings)) {
            return $easings;
        }

        foreach ($easings as $key => $value) {

            if(YRM_PKG == YRM_FREE_PKG && $key != 'linear' && $key != 'swing') {
                $easings[$key] = $value.' (PRO) ';
            }
        }

        return $easings;
    }

	public function readMoreWpHead() {

		echo '<script>readMoreArgs = []</script>';
		echo YrmConfig::readMoreHeaderScript();
	}

	public function postContentFiler($cotent) {
		global $post;
		global $wpdb;
		if (empty($post)) {
			return $cotent;
		}
		$postId = $post->ID;

		$getSavedSql = $wpdb->prepare("SELECT * FROM ".sanitize_text_field($wpdb->prefix)."expm_maker_pages WHERE post_id = %d", $postId);
		$result = $wpdb->get_row($getSavedSql, ARRAY_A);
		
		if(empty($result)) {
			$result = $this->allowDoFilter();
		}
		if(!empty($result)) {
			return ReadMoreContentManager::doFilterContent($cotent, $post, $result);
		}

		return $cotent;
	}

	private function allowDoFilter() {
		global $wpdb;
		$result = array();

		$allReadMores = $wpdb->get_results("SELECT id, options FROM ".sanitize_text_field($wpdb->prefix)."expm_maker", ARRAY_A);
		foreach ($allReadMores as $readMore) {
			$options = json_decode($readMore['options'], true);
			
			if(empty($options)) {
				continue;
			}

			if(!empty($options['yrm-button-for-post']) && $options['yrm-button-for-post'] == 'forALlPosts') {
				$result['options'] = $readMore['options'];
				$result['button_id'] = $readMore['id'];
				break;
			}
		}

		return $result;
	}

	public function findAndReplace($content) {
		require_once(YRM_ADMIN_TYPE_CLASSES.'FarTypeReadMore.php');

		return FarTypeReadMore::filterContent($content);
	}

	public function adminHead()
	{
		$subsExtensionAvailable = apply_filters('yrmSubsExtensionAvailable', 0);
		$allowedTag = ReadMoreAdminHelper::getAllowedTags();
		$str = '';
		if (!$subsExtensionAvailable) {
			ob_start();
			?>
				<script>
					jQuery(document).ready(function() {
						jQuery('[href*="<?php echo YRM_SUBSCRIBERS_PAGE;?>"]').attr("href", "<?php echo YRM_PRO_URL; ?>").attr('target', '_blank');
					});
				</script>
			<?php
			$str = ob_get_contents();
			ob_end_clean();
		}

		echo wp_kses($str, $allowedTag);
	}
}