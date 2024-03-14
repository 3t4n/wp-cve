<?php

class BPD_IconListItem extends ET_Builder_Module {

	public $slug       = 'bpd_icon_list_item';
	public $vb_support = 'on';
	public $type = 'child';
	public $child_title_var = 'title';
	public $child_title_fallback_var = 'subtitle';

	protected $module_credits = array(
		'module_uri' => 'https://webtechstreet.com',
		'author'     => 'WebTechStreet',
		'author_uri' => 'https://webtechstreet.com',
	);

	public function init() {
		$this->name = esc_html__( 'Icon List Item', 'bpd-booster-pack-divi' );

		$this->advanced_setting_title_text = esc_html__( 'Icon Item', 'bpd-booster-pack-divi' );
		$this->settings_text = esc_html__( 'Icon Item Settings', 'bpd-booster-pack-divi' );
		$this->settings_modal_toggles = array(
			'general'  => array(
				'toggles' => array(
					'main_content' => esc_html__( 'Icon Item', 'bpd-booster-pack-divi' ),
				),
			),
		);
	}

	function get_fields() {
		return array(
			'title' => array(
				'label'           => esc_html__( 'Title', 'bpd-booster-pack-divi' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Text entered here will appear as title.', 'bpd-booster-pack-divi' ),
				'toggle_slug'     => 'main_content',
			),
			'l_icon' => array(
				'label'               => esc_html__( 'Icon', 'bpd-booster-pack-divi' ),
				'type'                => 'et_font_icon_select',
				'renderer'            => 'et_pb_get_font_icon_list',
				'renderer_with_field' => true,
				'toggle_slug'     => 'main_content',
			),
			'icon_href' => array(
				'label'               => esc_html__( 'Link', 'bpd-booster-pack-divi' ),
				'type'                => 'text',
				'toggle_slug'  			  => 'main_content',
			),
		);
	}

	public function get_advanced_fields_config() {
 		$advanced_fields = array();

		$advanced_fields = false;
		return $advanced_fields;
	}
	function render_prop( $value = '', $field_name = '', $field_type = '', $render_slug = '') {
 			$order_class = self::get_module_order_class( $render_slug );
 			$output      = '';

 			switch ( $field_type ) {
 				case 'select_fonticon':
 				$output = sprintf(
 					'<span class="bpd-icon-list-icon">%1$s</span>',
 					esc_attr( et_pb_process_font_icon( $value ))
 				);
 				break;
 			}
 			return $output;
 		}

		function render( $attrs, $content = null, $render_slug ) {
			$title = $this->props['title'];
			$iconhref = $this->props['icon_href'];
			$ahref="";
			if(''!== $iconhref)
			{
					$ahref ='href='.$iconhref;
			}

			$output = sprintf(
				'<a %3$s class="bpd-icon-list-item" style="text-decoration:none;">%1$s <span class="bpd-icon-list-text">%2$s</span></a>',
				$this->render_prop( esc_html($this->props['l_icon']), 'l_icon', 'select_fonticon', $render_slug ),esc_html($title),esc_html($ahref)
			);
			return $output;
		}

}

new BPD_IconListItem;
