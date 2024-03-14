<?php
/**
 * @package         FirePlugins Framework
 * @version         1.1.94
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FPFramework\Admin\Library;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class Library
{
	use Favorites;
	use Templates;
	
	/**
	 * Library item info popup.
	 * 
	 * @var  string
	 */
	private $info_modal_id = 'fpf-library-item-info-popup';

	/**
	 * Library preview popup.
	 * 
	 * @var  string
	 */
	private $preview_modal_id = 'fpf-library-preview-popup';

	/**
	 * The library settings
	 * 
	 * @var   array
	 */
	protected $library_settings = [];

	public function __construct($library_settings = [])
	{
		$this->library_settings = $library_settings;

		$this->favorites_init();
		$this->templates_init();
	}
	
	public function init()
	{
		$this->prepare();

		// Enqueue media
		add_action('admin_enqueue_scripts', [$this, 'register_media']);

		$this->customizePopups();
		$this->addPopups();
	}

	/**
	 * Prepares the Library.
	 * 
	 * @return  void
	 */
	private function prepare()
	{
		$this->library_settings['preview_url'] = FPF_TEMPLATES_SITE_URL . '?template_preview_id=TEMPLATE_ID&plugin=' . $this->library_settings['plugin'];
	}
	
	/**
	 * Setup ajax requests
	 * 
	 * @return  void
	 * 
	 * @deprecated 1.1.0  Used only for backwards compatibility
	 */
	public function setupAjax()
	{
		$this->templates_init();
	}

	/**
	 * Customize popups sections.
	 * 
	 * @return  void
	 */
	private function customizePopups()
	{
		// Main Templates Library Popup
		add_action('fpframework/modal/' . $this->library_settings['id'] . '/header/prepend_actions', [$this, 'templates_library_set_modal_actions']);
		add_action('fpframework/modal/' . $this->library_settings['id'] . '/header/middle', [$this, 'templates_library_set_modal_header_middle']);

		// Templates Library Preview
		add_action('fpframework/modal/' . $this->preview_modal_id . '/header/before_title', [$this, 'library_preview_before_title']);
		add_action('fpframework/modal/' . $this->preview_modal_id . '/header/middle', [$this, 'library_preview_header_middle']);
	}

	/**
	 * Add required popups to the page.
	 * 
	 * @return  void
	 */
	private function addPopups()
	{
		add_action('admin_footer', [$this, 'add_library_popup']);
		add_action('admin_footer', [$this, 'add_library_preview_template_popup']);
		add_action('admin_footer', [$this, 'add_library_item_info_popup']);
	}

	/**
	 * Adds admin media
	 * 
	 * @return  void
	 */
	public function register_media()
	{
		// Templates Library CSS
		wp_enqueue_style(
			'fpf-templates-library',
			FPF_MEDIA_URL . 'admin/css/fpf_templates_library.css',
			[],
			FPF_VERSION,
			false
		);

		// Templates Library JS
		wp_enqueue_script(
			'fpf-templates-library',
			FPF_MEDIA_URL . 'admin/js/fpf_templates_library.js',
			[],
			FPF_VERSION,
			true
		);

		$data = [
			'plugin_name' => $this->library_settings['plugin_name'],
			'pro' => fpframework()->_('FPF_PRO'),
			'lite' => fpframework()->_('FPF_LITE'),
			'wordpress' => fpframework()->_('FPF_WORDPRESS'),
			'install_plugin' => fpframework()->_('FPF_INSTALL_PLUGIN'),
			'activate_plugin' => fpframework()->_('FPF_ACTIVATE_PLUGIN'),
			'update_plugin' => fpframework()->_('FPF_UPDATE_PLUGIN'),
			'license_key' => fpframework()->_('FPF_LICENSE_KEY'),
			'license' => isset($this->library_settings['license_key']) ? $this->library_settings['license_key'] : ''
		];
		wp_localize_script('fpf-templates-library', 'fpf_templates_library_js_object', $data);
	}

	/**
	 * Adds the popup at the footer of the page. Appears when you click the "New" / "Add New" button.
	 * 
	 * @return  void
	 */
	public function add_library_popup()
	{
		$payload = [
			'id' => $this->library_settings['id'],
			'title' => $this->library_settings['title'],
			'class' => ['fpf-templates-library'],
			'overlay_click' => false,
			'content' => fpframework()->renderer->admin->render('library/tmpl', $this->library_settings, true),
			'width' => 70,
			'height' => 100
		];
		
        \FPFramework\Helpers\HTML::renderModal($payload);
	}

	/**
	 * Adds the popup at that allows us to preview a template.
	 * 
	 * @return  void
	 */
	public function add_library_preview_template_popup()
	{
		$payload = [
			'id' => $this->preview_modal_id,
			'class' => ['fpf-templates-library-popup-preview'],
			'overlay_click' => false,
			'content' => fpframework()->renderer->admin->render('library/preview', [], true),
			'width' => 100,
			'height' => 100
		];
		
        \FPFramework\Helpers\HTML::renderModal($payload);
	}

	/**
	 * Adds the popup that displays the info for each template.
	 * 
	 * @return  void
	 */
	public function add_library_item_info_popup()
	{
		$popup_payload = [
			'category_label' => isset($this->library_settings['main_category_label']) ? $this->library_settings['main_category_label'] : '',
			'plugin_name' => $this->library_settings['plugin_name']
		];
		
		$payload = [
			'id' => $this->info_modal_id,
			'title' => 'Template Title Goes Here',
			'class' => ['fpf-templates-library-item-info'],
			'content' => fpframework()->renderer->admin->render('library/info_popup', $popup_payload, true),
		'width' => '600px'
		];
		
        \FPFramework\Helpers\HTML::renderModal($payload);
	}

	/**
	 * Renders any other actions in the modal header.
	 * 
	 * @return  void
	 */
	public function templates_library_set_modal_actions()
	{
		?>
		<li>
			<a href="<?PHP echo esc_url($this->library_settings['create_new_template_link']); ?>" title="<?php esc_attr_e(fpframework()->_('FPF_START_FROM_SCRATCH')); ?>">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<circle cx="12" cy="12" r="8" stroke="currentColor"/>
					<line x1="11.9277" y1="8.5" x2="11.9277" y2="15.5" stroke="currentColor" stroke-linecap="round"/>
					<line x1="15.5" y1="11.9285" x2="8.5" y2="11.9285" stroke="currentColor" stroke-linecap="round"/>
				</svg>
			</a>
		</li>
		<li>
			<a href="#" class="fpf-templates-refresh-btn" title="<?php esc_attr_e(fpframework()->_('FPF_REFRESH_TEMPLATES')); ?>">
				<svg class="checkmark" width="18" height="18" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
					<circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none" />
					<path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" stroke-width="5" />
				</svg>
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M20 12C20 7.58172 16.4183 4 12 4C7.58172 4 4 7.58172 4 12C4 16.4183 7.58172 20 12 20C14.2879 20 16.3514 19.0396 17.8095 17.5" stroke="currentColor" stroke-linecap="round"/>
					<path class="tip" d="M22.25 9.99999L20 12.25L17.75 9.99999" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
			</a>
		</li>
		<li>
			<a href="<?php echo esc_url(FPF_SUPPORT_URL . '?topic=Custom%20Development&plugin=' . $this->library_settings['plugin_name']); ?>" title="<?php esc_attr_e(fpframework()->_('FPF_REQUEST_TEMPLATE')); ?>" target="_blank">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M8.8 16H9.3C9.3 15.7239 9.07614 15.5 8.8 15.5V16ZM8.8 20H8.3C8.3 20.1905 8.40823 20.3644 8.57912 20.4486C8.75002 20.5327 8.95387 20.5125 9.10486 20.3963L8.8 20ZM13.7304 16.2074L14.0353 16.6037L13.7304 16.2074ZM5 4.5H19V3.5H5V4.5ZM19.5 5V15H20.5V5H19.5ZM4.5 15V5H3.5V15H4.5ZM8.8 15.5H5V16.5H8.8V15.5ZM9.3 20V16H8.3V20H9.3ZM19 15.5H14.3401V16.5H19V15.5ZM13.4256 15.8111L8.49514 19.6037L9.10486 20.3963L14.0353 16.6037L13.4256 15.8111ZM3.5 15C3.5 15.8284 4.17157 16.5 5 16.5V15.5C4.72386 15.5 4.5 15.2761 4.5 15H3.5ZM19.5 15C19.5 15.2761 19.2761 15.5 19 15.5V16.5C19.8284 16.5 20.5 15.8284 20.5 15H19.5ZM14.3401 15.5C14.0093 15.5 13.6878 15.6094 13.4256 15.8111L14.0353 16.6037C14.1227 16.5365 14.2299 16.5 14.3401 16.5V15.5ZM19 4.5C19.2761 4.5 19.5 4.72386 19.5 5H20.5C20.5 4.17157 19.8284 3.5 19 3.5V4.5ZM5 3.5C4.17157 3.5 3.5 4.17157 3.5 5H4.5C4.5 4.72386 4.72386 4.5 5 4.5V3.5Z" fill="currentColor"/>
				</svg>
			</a>
		</li>
		<li>
			<a href="#" class="fpf-templates-library-toggle-fullscreen">
				<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M9 2H14V7" stroke="currentColor" stroke-width="1"/>
					<path d="M7 14L2 14L2 9" stroke="currentColor" stroke-width="1"/>
				</svg>
				<svg class="on-fullscreen" width="16" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M14 5L9 5L9 3.97232e-08" stroke="currentColor" stroke-width="1"/>
					<path d="M0 9H5L5 14" stroke="currentColor" stroke-width="1"/>
				</svg>
			</a>
		</li>
		<?php
	}

	/**
	 * Renders actions in the middle of the header.
	 * 
	 * @return  void
	 */
	public function templates_library_set_modal_header_middle()
	{
		if (!isset($this->library_settings['plugin_license_type']))
		{
			return;
		}
		
		if ($this->library_settings['plugin_license_type'] === 'lite')
		{
			?>
			<a href="#" class="fpf-button outline red fpf-modal-opener" data-fpf-modal-item="<?php esc_attr_e(fpframework()->_('FPF_PRO_TEMPLATES')); ?>" data-fpf-modal="#fpfUpgradeToPro" data-fpf-plugin="<?php esc_attr_e($this->library_settings['plugin_name']); ?>">
				<svg class="icon" width="16" height="14" viewBox="0 0 16 14" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M7.5 10C7.5 10.2761 7.72386 10.5 8 10.5C8.27614 10.5 8.5 10.2761 8.5 10L7.5 10ZM8.35355 3.64645C8.15829 3.45118 7.84171 3.45118 7.64645 3.64645L4.46447 6.82843C4.2692 7.02369 4.2692 7.34027 4.46447 7.53553C4.65973 7.7308 4.97631 7.7308 5.17157 7.53553L8 4.70711L10.8284 7.53553C11.0237 7.7308 11.3403 7.7308 11.5355 7.53553C11.7308 7.34027 11.7308 7.02369 11.5355 6.82843L8.35355 3.64645ZM8.5 10L8.5 4L7.5 4L7.5 10L8.5 10Z" fill="currentColor"/>
					<path d="M14 7C14 10.3137 11.3137 13 8 13C4.68629 13 2 10.3137 2 7C2 3.68629 4.68629 1 8 1C11.3137 1 14 3.68629 14 7Z" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
				<?php esc_html_e(fpframework()->_('FPF_UPGRADE_TO_PRO')); ?>
			</a>
			<?php
		}
	}

	/**
	 * Adds back and refresh buttons to the Preview Library Item popup.
	 * 
	 * @return  void
	 */
	public function library_preview_before_title()
	{
		?>
		<a href="#" class="fpf-modal-close fpf-templates-library-preview-go-back">
			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M16 4L8 12L16 20" stroke="currentColor" stroke-linecap="round"/>
			</svg>
			<div class="text"><?php echo fpframework()->_('FPF_BACK'); ?></div>
		</a>
		<a href="#" class="fpf-templates-library-refresh-demo">
			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M20 12C20 7.58172 16.4183 4 12 4C7.58172 4 4 7.58172 4 12C4 16.4183 7.58172 20 12 20C14.2879 20 16.3514 19.0396 17.8095 17.5" stroke="currentColor" stroke-linecap="round"/>
				<path d="M22.25 9.99999L20 12.25L17.75 9.99999" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
			</svg>
		</a>
		<?php
	}

	/**
	 * Adds responsive devices to Preview Library Item popup.
	 * 
	 * @return  void
	 */
	public function library_preview_header_middle()
	{
		?>
		<div class="fpf-templates-library-preview-responsive-devices">
			<svg class="fpf-templates-library-preview-responsive-device active" data-device="desktop" width="35" height="24" viewBox="0 0 35 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<rect x="1" y="1" width="33" height="19" rx="2" stroke="currentColor" stroke-width="2"/>
				<path d="M16 21.5V21C16 20.4477 16.4477 20 17 20H19C19.5523 20 20 20.4477 20 21V21.5C20 22.0523 20.4477 22.5 21 22.5H23.25C23.6642 22.5 24 22.8358 24 23.25C24 23.6642 23.6642 24 23.25 24H12.75C12.3358 24 12 23.6642 12 23.25C12 22.8358 12.3358 22.5 12.75 22.5H15C15.5523 22.5 16 22.0523 16 21.5Z" fill="currentColor"/>
			</svg>
			<svg class="fpf-templates-library-preview-responsive-device" data-device="tablet" width="19" height="24" viewBox="0 0 19 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<rect x="1" y="1" width="17" height="22" rx="2" stroke="currentColor" stroke-width="2"/>
				<circle cx="9.5" cy="19.5" r="1.5" fill="currentColor"/>
			</svg>
			<svg class="fpf-templates-library-preview-responsive-device" data-device="mobile" width="15" height="24" viewBox="0 0 15 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<rect x="1" y="1" width="13" height="22" rx="2" stroke="currentColor" stroke-width="2"/>
				<line x1="5" y1="2" x2="10" y2="2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
			</svg>
		</div>
		<?php
	}

    /**
     * Return templates folder path
     *  
     * @return  string
     */
    protected function getTemplatesPath()
    {
		return \FPFramework\Helpers\WPHelper::getPluginUploadsDirectory($this->library_settings['plugin'], 'templates');
    }
}