<?php
namespace Adminz\Admin\AdminzElementor;
use Adminz\Admin\Adminz;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class ADMINZ_Category extends Widget_Base {
	
	public function get_name() {
		return 'adminz-category';
	}
	
	public function get_title() {
		return __( 'Category', 'administrator-z' );
	}
	
	public function get_icon() {
		return 'eicon-select';
	}
	public function get_keywords() {
		return [ Adminz::get_adminz_menu_title(), 'taxonomy', 'category' ];
	}
	public function get_categories() {
		return [ Adminz::get_adminz_slug() ];
	}
	
	protected function _register_controls() {

		$this->start_controls_section(
			'section_title',
			[
				'label' => __( 'Category', 'administrator-z' ),
			]
		);

		$post_types = get_post_types( ['public'=>true], 'objects' );
		$arr_post_types = array();
		foreach ($post_types as $key => $post_type) {
			$arr_post_types[$post_type->name] = $post_type->labels->singular_name;
		}

		$this->add_control(
			'post_type',
			[
				'label' => __( 'Select Post Type', 'administrator-z' ),
				'type' => Controls_Manager::SELECT,
				'options' => $arr_post_types,
				'default' => $this->get_default($arr_post_types,'post'),
			]
		);

		foreach ($post_types as $key => $post_type) {			
			$taxonomy_objects = get_object_taxonomies( $post_type->name, 'objects' );
			$arr_taxonomy = array();
			if(!empty($taxonomy_objects)){
				foreach ($taxonomy_objects as $taxonomy) {
					$arr_taxonomy[$taxonomy->name] = $taxonomy->label; 
				}
			}
			$this->add_control(
				'taxonomy_'.$post_type->name,
				[
					'label' => __( 'Filter by category', 'administrator-z' ),
					'type' => Controls_Manager::SELECT,
					'options' => $arr_taxonomy,
					'default' => $this->get_default($arr_taxonomy,''),
					'condition'=> ['post_type'=>$post_type->name],
				]
			);
		}
		$this->add_control(
			'title_size',
			[
				'label' => __( 'Title HTML Tag', 'administrator-z' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				],
				'default' => 'h3',
				'separator' => 'before',
			]
		);
		$this->add_control(
			'title_text',
			[
				'label'=> __( 'Title', 'administrator-z' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Empty', 'administrator-z' ),

			]
		);
		$this->add_control(
			'toggled',
			[
				'label' => __( 'Toggled items', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'your-plugin' ),
				'label_off' => __( 'No', 'your-plugin' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$this->end_controls_section();
	}
	private function get_default($arr,$default){
		if(empty($arr)){
			return $default;
		}else {
			foreach ($arr as $key => $value) {
				return $key;
			}
		}
		return ;
	}
	
	protected function render() {

        $settings = $this->get_settings_for_display();        
        $title_text =  $settings['title_text'];
		$taxonomy = $settings['taxonomy_'.$settings['post_type']];
		$rand = 'adminz_cat'.wp_rand();
		$html = '<div class="'.$rand.' elementor-element elementor-widget elementor-widget-toggle" data-element_type="widget" data-widget_type="toggle.default">
		   <div class="elementor-widget-container">
		      <div class="elementor-toggle" role="tablist">';

		// title text 
		$this->add_render_attribute( 'title_text', 'class', 'adminz-elementor-category' );

		if(empty($settings['title_text'])){			
			$title_text = get_taxonomy( $taxonomy )->labels->singular_name;
		}
		$html .= sprintf( '<%1$s %2$s>%3$s</%1$s>', $settings['title_size'], $this->get_render_attribute_string( 'title_text' ), $title_text );


		// children box
		$categories  = get_terms($taxonomy, array('hide_empty' => false));
		$categoryHierarchy = array();

		if(is_array($categories) and !empty($categories)){
			$this->sort_terms_hierarchically($categories, $categoryHierarchy);
			$html .=$this->call_children_html($categoryHierarchy);
			
		}
		// end children box
		$html .= '</div></div></div>';
		?>
		<style type="text/css">
			.<?php echo esc_attr($rand) ?> *{}
			.<?php echo esc_attr($rand) ?> .elementor-toggle-icon{cursor: pointer; display: inline;}


			.<?php echo esc_attr($rand) ?> .elementor-tab-contentz{
				display: none;
				padding-left: 1em;
			}
			.<?php echo esc_attr($rand) ?> .elementor-toggle-icon-opened,
			.<?php echo esc_attr($rand) ?> .elementor-tab-titlea.active .elementor-toggle-icon-closed{
				display: none;
			}
			.<?php echo esc_attr($rand) ?> .elementor-tab-titlea.active .elementor-toggle-icon-opened{
				display: inline;
			}
			.<?php echo esc_attr($rand) ?> .elementor-tab-titlea.active+.elementor-tab-contentz{
				display: block;
			}
		</style>
		<script type="text/javascript">
			jQuery(document).ready(function($){
				$(".<?php echo esc_attr($rand) ?> .elementor-toggle-icon").on("click",function(){
					$(this).parent(".elementor-tab-titlea").toggleClass('active');
				});
			})

		</script>
		<?php
		echo apply_filters('the_content',$html);
	}
	private function sort_terms_hierarchically(Array &$cats, Array &$into, $parentId = 0){
 		foreach ($cats as $i => $cat) {
	        if ($cat->parent == $parentId) {
	        	unset($cat->filter);	        	
	        	unset($cat->term_group);
	        	unset($cat->description);
	        	$cat->ancestors = count(get_ancestors( $cat->term_id, $cat->category,"taxonomy")); 
	        	//unset($cat->taxonomy);
	            $into[$cat->term_id] = $cat;
	            unset($cats[$i]);
	        }
	    }

	    foreach ($into as $topCat) {
	        $topCat->children = array();
	        $this->sort_terms_hierarchically($cats, $topCat->children, $topCat->term_id);
	    }	    
		
	}
	function call_children_html($categoryHierarchy){
		$settings = $this->get_settings_for_display();
		$active = $settings['toggled'] =="yes" ? "active" : ""; 
		$cssdisplay = $settings['toggled'] =="yes" ? "display: block;" : ""; 
		if(!is_array($categoryHierarchy)) return;
		if(empty($categoryHierarchy)) return;
		ob_start();
		foreach ($categoryHierarchy as $cat) {
			if (!empty($cat->children)) {
				?>
				<div class="elementor-toggle-item">
					<div class="elementor-tab-titlea <?php echo ($active); ?>" data-tab="<?php echo ($cat->slug) ; ?>"> <span class="elementor-toggle-icon elementor-toggle-icon-closed"> <?php echo Adminz::get_icon_html('chevron-down',['width'=> "1em"]); ?> </span> <span class="elementor-toggle-icon elementor-toggle-icon-opened"> <?php echo Adminz::get_icon_html('chevron-up',['width'=> "1em"]); ?> </span> <a class="term_link" href="<?php echo get_term_link($cat);?>"><?php echo ($cat->name);?></a> 
					</div>
					<div id="elementor-tab-content-<?php echo ($cat->slug) ; ?>" class="elementor-tab-contentz elementor-clearfix elementor-<?php echo ($active); ?>" data-tab="<?php echo ($cat->slug) ; ?>"  style="<?php echo ($cssdisplay); ?>">
						<!-- content tab -->
						<?php 
						echo ($this->call_children_html($cat->children)); 
						// ko children va la item cuoi cung cua mang
						if (empty($cat->children) and $key == key(array_slice($categoryHierarchy, -1, 1, true))){ 
							// bao nhieu cha thi đóng bấy nhiêu div
							for ($i=1; $i <=$cat->ancestors ; $i++) { 
								$html .="</div>";
							}
						}
					 	?>
					</div>
				</div>
				<?php
			}else{
				?>
				<div class="elementor-toggle-item">
					<div class="elementor-tab-titlea">
						<a class="term_link" href="<?php echo get_term_link($cat);?>"><?php echo ($cat->name);?></a>
					</div>
				</div>
				<?php
			}
		}
		
		return ob_get_clean();
	}
/*	protected function _content_template() {

    }*/
	
	
}