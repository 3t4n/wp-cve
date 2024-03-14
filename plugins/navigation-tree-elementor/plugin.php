<?php
namespace naviTreeElementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Main Plugin Class
 *
 * @since 1.0.0
 */
class ElementorNavigationTree {

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function __construct() {
		$this->naviTreeElementor_add_actions();
	}

	/**
	 * Add Actions
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function naviTreeElementor_add_actions() {

		add_action( 'elementor/editor/after_enqueue_scripts', function() {
			wp_register_script( 'ent-js', plugins_url( '/assets/js/ent.js', __FILE__ ), [ 'jquery' ], false, true );
			wp_enqueue_script('ent-js');
		} );		

		add_action( 'elementor/editor/after_enqueue_styles', function() {
			wp_register_style( 'ent-css', plugins_url( '/assets/css/ent.css', __FILE__ ) );
			wp_enqueue_style('ent-css');
		} );

		add_action( 'elementor/editor/footer', [ $this, 'naviTreeElementor_wrapper' ] );
		add_action('wp_ajax_naviTreeElementor_update',array($this,'naviTreeElementor_build_navigation_tree'));
	}

	public function naviTreeElementor_label($elType, $elID){
		if($elID){
			echo '<span class="fx-element-label">'. esc_html($elID) .'</span>';
		} else {
			echo '<span class="fx-element-label">' . esc_html($elType) . '</span>';
		}
	}

	public function naviTreeElementor_build_navigation_tree() {
		global $wpdb;

		$id = sanitize_text_field( $_POST['current_page'] );

		$dbPrefix = $wpdb->get_blog_prefix();
		$flexiblePostMeta = $dbPrefix . 'postmeta';
		
		$activeElements = $wpdb->get_results("SELECT * FROM $flexiblePostMeta WHERE post_id = $id AND meta_key = '_elementor_data'", ARRAY_A);

		$data = json_decode($activeElements[0]['meta_value']);

		/*
		echo '<ul class="bullet-list-round">';
			foreach($data as $elements){ 
				echo '<li data-id="'. $elements->id .'" class="fx-editor-trigger">' . $this->normalOrCustomName($elements->elType, $elements->settings->_element_id) . '</li>'; 
			}
		echo '</ul>';
		*/

		

		$array[] = '';

			echo '<ul class="bullet-list-round">';
			foreach($data as $elements){
				echo '<li data-id="'. esc_attr( $elements->id ) .'" class="fx-editor-trigger">';
						$this->naviTreeElementor_label($elements->elType, $elements->settings->_element_id);
				if(!empty($elements->elements)){
					echo '<ul class="bullet-list-round">';

					foreach($elements->elements as $firstLayerElement){

						echo '<li data-id="'. esc_attr( $firstLayerElement->id ) .'" class="fx-editor-trigger">';

								$this->naviTreeElementor_label($firstLayerElement->elType, $firstLayerElement->settings->_element_id);					
						
						if(!empty($firstLayerElement->elements)){
							echo '<ul class="bullet-list-round">';
							foreach($firstLayerElement->elements as $secondLayerElement){
								echo '<li data-id="'. esc_attr( $secondLayerElement->id ) .'" class="fx-editor-trigger">';
									if($secondLayerElement->widgetType){
										$this->naviTreeElementor_label($secondLayerElement->widgetType, $secondLayerElement->settings->_element_id);	
									} else {
										$this->naviTreeElementor_label($secondLayerElement->elType, $secondLayerElement->settings->_element_id);	
									}

									if(!empty($secondLayerElement->elements)){
										echo '<ul class="bullet-list-round">';
										foreach($secondLayerElement->elements as $thirdLayerElement){
											echo '<li data-id="'. esc_attr( $thirdLayerElement->id ) .'" class="fx-editor-trigger">';
												if($thirdLayerElement->widgetType){
													$this->naviTreeElementor_label($thirdLayerElement->widgetType, $thirdLayerElement->settings->_element_id);
												} else {
													$this->naviTreeElementor_label($thirdLayerElement->elType, $thirdLayerElement->settings->_element_id);
												}

												if(!empty($thirdLayerElement->elements)){
													echo '<ul class="bullet-list-round">';
													foreach($thirdLayerElement->elements as $fourthLayerElement){
														echo '<li data-id="'. esc_attr( $fourthLayerElement->id ) .'" class="fx-editor-trigger">';
															if($fourthLayerElement->widgetType){
																$this->naviTreeElementor_label($fourthLayerElement->widgetType, $fourthLayerElement->settings->_element_id);
															} else {
																$this->naviTreeElementor_label($fourthLayerElement->elType, $fourthLayerElement->settings->_element_id);
															}
														echo '</li>'; // fourthLayer element
													}
													echo '</ul>'; // fourthLayer
												}
											echo '</li>'; // thirdLayer element
										}
										echo '</ul>'; // thirdLayer
									}
								echo '</li>'; // secondLayer element
							}
							echo '</ul>'; // secondLayer
						}
						echo '</li>'; // firstLayer element
					}
					echo '</ul>'; // firstLayer

					
				}

				// var_dump($array[0]['elements']);
			
			}
			echo '</ul>'; // base sections
			
			
			wp_die();
	}

	public function naviTreeElementor_wrapper() {
		global $post;

		echo '<div id="fx-el-navtree" class="closed" data-ent-post-id="'. esc_attr( $post->ID ) .'" data-ent-base-url="'. site_url() .'">
				<i aria-hidden="true" class="eicon-chevron-left active"></i>
				<i aria-hidden="true" class="eicon-chevron-right"></i>
				<div class="refresh"><i class="fa fa-refresh" aria-hidden="true"></i></div>
				<div class="inner"></div>
				</div>';
	}
}

new ElementorNavigationTree();
