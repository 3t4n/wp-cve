<?php
namespace SirvElementorWidget\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class SirvWidget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'SIRV';
	}


	public function get_title() {
		return __( 'Sirv gallery', 'sirv' );
	}


	public function get_icon() {
		//return 'fa fa-picture-o';
		//return 'eicon-image-box';
		return 'eicon-image';
	}


	public function get_categories() {
		return [ 'general' ];
	}


	public function get_keywords(){
		return ['sirv', 'gallery'];
	}


	protected function register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', 'sirv' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'sirv-gallery',
			[
				'label' => __( 'Sirv add media', 'sirv' ),
				'type' => 'sirvcontrol',
				'dynamic' => [
					'active' => true,
				],
				'description' => "You're adding Sirv gallery. Choose your image(s) or spin(s):"
			]
		);

		$this->add_control(
			'sirv-data-string',
			[
				'label' => __( 'View', 'sirv' ),
				'type' => \Elementor\Controls_Manager::HIDDEN,
				'default' => '',
			]
		);

		$this->end_controls_section();

	}


	protected function render() {

		$settings = $this->get_settings_for_display();

		$shData = json_decode($settings['sirv-data-string'], true);


		echo '<div>';

		if(\Elementor\Plugin::$instance->editor->is_edit_mode()){
			echo '<div class="sirv-elementor-click-overlay" style="position: absolute;top: 0;left: 0;bottom: 100%;right: 100%;width: 100%;height: 100%;z-index: 1000000;"></div>';

			if(empty($settings['sirv-data-string'])){
				echo '<div class="sirv-empty-widget"><img class="sirv-empty-widget__img" src="'. SIRV_PLUGIN_SUBDIR_URL_PATH .'assets/logo.svg" /><div class="sirv-empty-widget__text">Sirv gallery</div></div>';
			}
		}

		if(!empty($shData['shortcode']['id'])) echo do_shortcode('[sirv-gallery id="'. $shData['shortcode']['id'] . '"]');

		if(!empty($shData['images'])){
			echo $this->render_sirv_imgs($shData);
		}

		echo '</div>';

		//if(empty($settings['sirv-data-string'])) echo "<br>Hidden field is empty<br>"; else echo "<br>Hidden string: " . $settings['sirv-data-string'] . "<br>";

	}


	protected function render_sirv_imgs($data){
		$placehodler_grey = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAAAAAA6fptVAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAAKSURBVAgdY3gPAADxAPAXl1qaAAAAAElFTkSuQmCC";
		$placeholder_grey_params = '?q=1&w=10&colorize.color=efefef';

		$isResponsive = (Boolean) $data['images']['full']['isResponsive'];
		$isLazyLoading = (Boolean) $data['images']['full']['isLazyLoading'];
		$width = $data['images']['full']['width'];
		$align = $data['images']['full']['align'];
		$linkType = isset($data['images']['full']['linkType']) ? $data['images']['full']['linkType'] : 'none';
		$customLink = isset($data['images']['full']['customLink']) ? $data['images']['full']['customLink'] : '';
		$isBlankWindow = isset($data['images']['full']['isBlankWindow']) ? (bool) $data['images']['full']['isBlankWindow'] : false;
		$isAltCaption = (Boolean) $data['images']['full']['isAltCaption'];

		$sirvClass = $isResponsive ? 'Sirv' : '';

		//backward compatibility for old isLinkToBigImage
		$isLinkToBigImage = isset($data['images']['full']['linkToBigImage']) ? (bool) $data['images']['full']['linkToBigImage'] : false;
		if($isLinkToBigImage) $linkType == 'large';

		$this->add_render_attribute('figure', [
			'class' => ['sirv-flx', 'sirv-img-container', $align]
		]);

		if($width){
			//$style = $isResponsive ? "max-width: {$width}px;" : "width: {$width}px;";
			$style = "width: {$width}px;";
			$this->add_render_attribute('figure', 'style', $style);
		}

		if($isResponsive){

			if(!$isLazyLoading) $this->add_render_attribute('sirv_img', 'data-options', 'autostart: created;');
		}

		$this->add_render_attribute('figure__img', [
				'class' => [$sirvClass, 'sirv-img-container__img']
		]);


		$images = '';
		foreach ($data['images']['full']['imagesData'] as $imageData) {
			$fcaption = '';
			if($imageData['caption']){
				$this->add_render_attribute('figure__img', 'alt', $imageData['caption']);
				if($isAltCaption){
					$fcaption = '<figcaption class="sirv-img-container__cap">'. $imageData['caption'] .'</figcaption>';
				}
			}


				if($isResponsive){
					if ($imageData['img_width'] !== '0') {
						$this->add_render_attribute('figure__img', 'width', $imageData['img_width']);
					}
					if($imageData['img_height'] !== '0'){
					$this->add_render_attribute('figure__img', 'height', $imageData['img_height']);
					}
				}else{
					$img_size = $this->calcImgSize($imageData['img_width'], $imageData['img_height'], $width);
					if ($imageData['img_width'] !== '0') {
						$this->add_render_attribute('figure__img', 'width', $img_size['width']);
					}
					if ($imageData['img_height'] !== '0') {
						$this->add_render_attribute('figure__img', 'height', $img_size['height']);
					}
				}

			//$srcAttr = $isResponsive ? 'src="' . $placehodler_grey .'"' : 'src="' . $imageData['modUrl'] . '"';
			$srcAttr = $isResponsive ? 'src="' . $imageData['origUrl'] . $placeholder_grey_params .'"' : 'src="' . $imageData['modUrl'] . '"';
			$dataSrcAttr = $isResponsive ? ' data-src="' . $imageData['modUrl'] . '"' : '';

			$imgTag = '<img '. $this->get_render_attribute_string( 'figure__img' ) . ' ' . $srcAttr . $dataSrcAttr .'>';
			$build = '<figure '. $this->get_render_attribute_string( 'figure' ) .'>';
			if($linkType !== 'none'){
				$linkTo = $linkType == 'url' ? $customLink : $imageData['origUrl'];
				$blankAttr =  $isBlankWindow ? ' target="_blank" ' : '';

				$build .= '<a class="sirv-img-container__link"'. $blankAttr .'href="'. $linkTo .'">' . $imgTag . '</a>';
			}else{
				$build .= $imgTag;
			}
			$build .= $fcaption .'</figure>' . PHP_EOL;

			$images .= $build;

		}


		return $images;

	}


	protected function calcImgSize($orig_width, $orig_height, $width){
		$size = array('width' => $orig_width, 'height' => $orig_height);

			if($width){
					$size['width'] = $width;
					$size['height'] = floor( (int)$width * $this->calcProportion($orig_width, $orig_height) );
			}

    return $size;
	}


	protected function calcProportion($width, $height){
		return (int)$height / (int)$width;
	}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.1.0
	 *
	 * @access protected
	 */
	/*protected function _content_template() {

	}*/

}
