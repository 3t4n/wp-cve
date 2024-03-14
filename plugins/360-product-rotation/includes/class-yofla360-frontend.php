<?php

if (!defined("ABSPATH")) {
	exit;
}

/**
 * Class YoFLA360Frontend
 *
 * Utilities to manipulate wordpress frontend
 *
 */
class YoFLA360Frontend
{

	/** @var YoFLA360Frontend The single instance of the class */
	protected static $_instance = null;

	/**
	 * Main YoFLA360Frontend Instance
	 *
	 * Ensures only one instance of YoFLA360Frontend is loaded or can be loaded.
	 *
	 * @static
	 * @return YoFLA360Frontend Main instance
	 */
	public static function instance()
	{
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct()
	{
	}


	/**
	 * Returns iframe embed code, to insert a 360 view into a page
	 *
	 * @param $viewData
	 * @param string $elementId
	 * @return bool|string
	 */
	public function get_embed_iframe_html($viewData, $elementId = '')
	{

		$iframe_url = $viewData->get_iframe_url();

		$name = '3drt-' . $viewData->id;

		if (empty($elementId)) {
			$elementId = $name;
		}

		if ($iframe_url) {
			//if height is not set, do not provide height parameter
			$height = ($viewData->height) ? 'height="' . $viewData->heightNum . '"' : '';

			$html = '<iframe
            name="' . $name . '"
            id="' . $elementId . '"
            width="' . $viewData->widthNum . '"
            ' . $height . '
            src="' . $iframe_url . '"
            marginheight="0"
            marginwidth="0"
            scrolling="no"
            class="yofla_360_iframe"
            allowfullscreen
            style="' . $viewData->iframe_styles . '"
            >';
			$html .= '</iframe>';

			// auto-height
			if ($viewData->autoHeight) {
				$html = '
				<iframe
					class="yofla_360_iframe auto-aspect-ratio auto-aspect-ratio__modify-height-'.$viewData->heightNum.' auto-aspect-ratio__' . $viewData->widthNum . 'x' . $viewData->heightNum . '"
					name="' . $name . '"
					id="' . $elementId . '"
					width="' . $viewData->widthNum . '"
					height="' . $viewData->heightNum . '"
					src="' . $iframe_url . '"
					marginheight="0"
					marginwidth="0"
					scrolling="no"
					allowfullscreen
					style="' . $viewData->iframe_styles . ';"
					>';
				$html .= '</iframe>';
			}


		} else {
			YoFLA360()->add_error('Error constructing iframe url.');
			$html = YoFLA360()->get_errors();
		}

		return $html;
	}

	/**
	 * Outputs the div embed code for integrating a 360 view into a webpage
	 *
	 * @param $viewData YoFLA360Viewdata
	 * @return string
	 */
	public function get_embed_div_element_html($viewData)
	{
		$template_string = '<div id=\'{id}\' class="rotate-tool-instance" style="{styles}" data-rotate-tool=\'{"path":"{path}",
            "id":"{id}",
            "configFile":"{config_file}",
            "themeUrl":"{theme_url}",
            "gaData":{"isEnabled":"{ga_enabled}",
                "trackingId":"{ga_tracking_id}",
                "label":"{ga_label}",
            "category":"{ga_category}"
        } }\'> </div>';

		$configFile = $viewData->config_url;
		$path = $viewData->get_product_url();

		if (strpos($configFile, 'http') === 0) {
			$path = "";
		}

		$values = array(
			'{id}' => $viewData->id,
			'{width}' => $viewData->width,
			'{height}' => $viewData->height,
			'{styles}' => $viewData->get_styles(),
			'{path}' => $path,
			'{ga_enabled}' => ($viewData->ga_enabled) ? 'true' : 'false',
			'{ga_tracking_id}' => $viewData->ga_tracking_id,
			'{ga_label}' => $viewData->get_ga_label(),
			'{ga_category}' => $viewData->ga_category,
			'{config_file}' => $configFile,
			'{theme_url}' => $viewData->theme_url,
		);

		$new_html = strtr($template_string, $values);

		return $new_html;
	}

	/**
	 * Outputs the div embed code for integrating a 360 view into a webpage (next360 version)
	 *
	 * @param $viewData YoFLA360Viewdata
	 * @return string
	 */
	public function get_embed_div_element_html_next360($viewData)
	{
		//die("url is: ".$viewData->get_product_url());

		$template_string = '<div class="rotate-tool-instance" style="{styles}" data-yofla360=\'{"path":"{path}",
            "id":"{id}",
            "gaData":{"isEnabled":"{ga_enabled}",
                "trackingId":"{ga_tracking_id}",
                "label":"{ga_label}",
            "category":"{ga_category}"
        } }\'> </div>';

		$productUrl = YoFLA360()->Utils()->get_product_url($viewData);

		$values = array(
			'{id}' => $viewData->id,
			'{width}' => $viewData->width,
			'{height}' => $viewData->height,
			'{styles}' => $viewData->get_styles(),
			'{path}' => $productUrl,
			'{ga_enabled}' => ($viewData->ga_enabled) ? 'true' : 'false',
			'{ga_tracking_id}' => $viewData->ga_tracking_id,
			'{ga_label}' => $viewData->get_ga_label(),
			'{ga_category}' => $viewData->ga_category,
		);


		$new_html = strtr($template_string, $values);

		return $new_html;
	}

	/**
	 * Wraps the provided string in html comments with information about hte plugin
	 *
	 * @param $html_code
	 * @return string
	 */
	public function format_plugin_output($html_code)
	{
		//start html output
		$html_code_start = "\n" . '<!-- 360 Product Rotation Plugin v.' . YOFLA_360_VERSION_NUM . ' by www.yofla.com  Begin -->' . "\n";
		$html_code_end = "\n" . '<!-- 360 Product Rotation Plugin v.' . YOFLA_360_VERSION_NUM . ' by www.yofla.com  End -->' . "\n";
		$output = $html_code_start . $html_code . $html_code_end;
		return $output;
	}


	/**
	 * Adds html code to error message that is returned to page
	 *
	 * @param $msg
	 * @return string
	 */
	public function format_error($msg)
	{
		$str = '<div><span style="color: red">360 Plugin Error: <br>' . PHP_EOL . $msg . PHP_EOL . '</span></div>';
		return $str;
	}

}//class
