<?php

class DVMC_Manychat extends ET_Builder_Module {

	public $slug       = 'dvmc_manychat';
	public $vb_support = 'on';

	protected $module_credits = array(
		'module_uri' => 'https://www.simb.co/',
		'author'     => 'SimBco',
		'author_uri' => 'https://www.simb.co/',
	);

	public function init() {
		$this->name = esc_html__( 'Manychat', 'dvmc-divi-manychat' );
		$this->advanced_fields = false;
	}

	public function get_fields() {
		$widgetList = self::getWidgetList();

		return array(
			'mc_widget_id' => array(
				'label'           => esc_html__( 'Widget', 'dvmc-divi-manychat' ),
				'type'            => 'select',
				'options' => $widgetList,
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'This is the manychat widget you wish to insert', 'dvmc-divi-manychat' ),
				'toggle_slug'     => 'main_content',
				'computed_affects' => array(
					'__mc_widget_label'
				),
			),
			'__mc_widget_label' => array(
				'type'                => 'computed',
				'computed_callback'   => array( 'DVMC_Manychat', 'getWidgetLabel' ),
				'computed_depends_on' => array(
					'mc_widget_id',
				),
			),
		);
	}

	public function render( $attrs, $content = null, $render_slug ) {
		$widgetLabel = self::getWidgetLabel(['mc_widget_id' => $this->props['mc_widget_id']]);
		return sprintf( '<div class="mcwidget-embed" data-widget-id="%1$s">Manychat widget: %2$s</div>', $this->props['mc_widget_id'], $widgetLabel );
	}

	private static function getWidgetList()
	{
		$cachedWidgetList = get_transient('wpmc_widget_list');
		if ($cachedWidgetList !== false) {
			return $cachedWidgetList;
		}
		$returnWidgets = [];
		$pageId = get_option('wpmc_fb_page_id', null);
		if ($pageId) {
			$url = "https://widget.manychat.com/{$pageId}.js";
			$result = wp_remote_retrieve_body( wp_remote_get( $url ) );
			if ($result) {
				preg_match('/(?s:window.mcwidget\s*=\s*({.*})\s*;)/U', $result, $matches);
				$mcWidgetsJson = $matches[1];
				if ($mcWidgetsJson) {
					$mcWidgets = json_decode($mcWidgetsJson, true);
					if (array_key_exists('widgets', $mcWidgets) && is_array($mcWidgets['widgets'])){
						$widgets = $mcWidgets['widgets'];
						foreach ($widgets as $currentWidget) {
							if ($currentWidget['widget_type'] !== 'checkbox'){
								$returnWidgets[$currentWidget['widget_id']] = $currentWidget['name'];
							}
						}
					}
				}
				
			}
			
		}
		set_transient('wpmc_widget_list', $returnWidgets, 5 * MINUTE_IN_SECONDS);
		
		return $returnWidgets;
	}

	public static function getWidgetLabel($atts)
	{
		$widgetId = $atts['mc_widget_id'];
		$widgetList = self::getWidgetList();
		if (array_key_exists($widgetId, $widgetList)){
			return $widgetList[$widgetId];
		} else {
			return '';
		}
	}
}

new DVMC_Manychat;
