<?php 
namespace Adminz\Helper;
use PhpParser\Node\Stmt\Foreach_;

class ADMINZ_Helper_Flatsome_Ux_Builder{
	public $post_type;
	public $template_block_id;
	public $taxonomy;
	public $tax_template_block_id;

	function __construct() {}

	function init(){}

	function post_type_content_support(){

		if(!$this->post_type) {
			return;
		}

		add_action( 'init', function (){
			if ( function_exists( 'add_ux_builder_post_type' ) ) {
				add_ux_builder_post_type( $this->post_type );
			}
		} );
	}

	function taxonomy_layout_support(){
		if(!$this->taxonomy) {
			return;
		}

		$this->taxonomy_build_adminbar_link();
		$this->taxonomy_build_template();
		$this->term_taxonomy_meta_box();
	}

	function post_type_layout_support(){
		if(!$this->post_type) {
			return;
		}

		$this->post_type_build_adminbar_link();
		$this->post_type_build_template();
		$this->post_meta_box();
	}

	function taxonomy_build_adminbar_link(){
		if ( !$this->taxonomy ) {
			return;
		}

		add_action( 'wp_before_admin_bar_render', function () {
			global $wp_admin_bar;
			if ( !is_archive() ) {
				return;
			}

			$queried_object = get_queried_object();
			if(!isset($queried_object->taxonomy) or $queried_object->taxonomy !==$this->taxonomy){
				return;
			}

			$tax_template_block_id = false;
			$adminz_flatsome   = get_option( 'adminz_flatsome', [] );
			if ( isset( $adminz_flatsome['taxonomy_layout_support'][$queried_object->taxonomy] ) ) {
					$tax_template_block_id = $adminz_flatsome['taxonomy_layout_support'][$queried_object->taxonomy];
					$tax_template_block_id = \Adminz\Helper\ADMINZ_Helper_Flatsome_Ux_Builder::taxonomy_get_block_id( $tax_template_block_id );

					if($tax_template_block_id){
						$wp_admin_bar->add_menu( array(
							'parent' => 'edit',
							'id'     => 'edit_uxbuilder',
							'title'  => 'Edit with UX Builder',
							'href'   => ux_builder_edit_url( $tax_template_block_id ),
						) );
					}
					
			}

		} );

	}

	function post_type_build_adminbar_link(){
		add_action( 'wp_before_admin_bar_render', function(){
			global $wp_admin_bar;
			global $post;
			global $wpdb;
			if ( ! is_page() && ! is_single() ) {return; }
			if ( ! current_user_can( 'edit_post', $post->ID ) ) {return; }
			if(get_post_type() != $this->post_type) return;

			$wp_admin_bar->add_menu( array(
				'parent' => 'edit',
				'id'     => 'edit_uxbuilder',
				'title'  => 'Edit with UX Builder',
				'href'   => ux_builder_edit_url( $post->ID ),
			) );
			

			
			$wp_admin_bar->add_menu( array(
				'parent' => 'edit',
				'id'     => 'edit_uxbuilder_product_layout',
				'title'  => 'Edit '.$this->post_type.' layout with UX Builder',
				'href'   => ux_builder_edit_url( $post->ID, self::post_type_get_block_id($this->template_block_id) ),
			) );
		});
	}

	function taxonomy_build_template(){
		if ( !$this->taxonomy ){
			return;
		}

		add_filter('taxonomy_template', function($template, $type, $templates){
			$queried_object = get_queried_object();
			if(isset($queried_object->taxonomy) and $queried_object->taxonomy == $this->taxonomy){
				/**
				 * BUG: Nếu ko tìm được template_id thì trắng layout
				 * Cần phải check trước khi vào template
				 * Gán tạm nó vào $GLOBALS
				 * Thực hiện kiểm tra template_id có hợp lệ hay không
				 * 
				 */
				$template_block_id = false;
				$adminz_flatsome   = get_option( 'adminz_flatsome', [] );
				if ( isset( $adminz_flatsome['taxonomy_layout_support'][$queried_object->taxonomy] ) ) {
					$template_block_id = $adminz_flatsome['taxonomy_layout_support'][$queried_object->taxonomy];
					$template_block_id = \Adminz\Helper\ADMINZ_Helper_Flatsome_Ux_Builder::taxonomy_get_block_id( $template_block_id );
					if ( $template_block_id ) {
						$GLOBALS[ 'adminz_template_block_id_for_term_taxonomy_id_' . $queried_object->term_taxonomy_id] = $template_block_id;
						$template = ADMINZ_DIR . "inc/file/flatsome_taxonomy_template.php";
					}
				}
			}
			return $template;
		},10,3);

	}

	function post_type_build_template(){
		if ( !$this->post_type ) {
			return;
		}

		add_filter('single_template', function ($template, $type, $templates) {
		    if (is_single() && get_post_type() == $this->post_type) {
				/**
				 * BUG: Nếu ko tìm được template_id thì trắng layout
				 * Cần phải check trước khi vào template
				 * Gán tạm nó vào $GLOBALS
				 * Thực hiện kiểm tra template_id có hợp lệ hay không
				 * 
				 */
				$template_block_id = false;
				$adminz_flatsome = get_option('adminz_flatsome', []);		
				if(isset($adminz_flatsome['post_type_template'][get_post_type()])){
					$template_block_id = $adminz_flatsome['post_type_template'][get_post_type()];
					$template_block_id = \Adminz\Helper\ADMINZ_Helper_Flatsome_Ux_Builder::post_type_get_block_id($template_block_id);
					if($template_block_id){
						$GLOBALS['adminz_template_block_id_for_'.get_the_ID()] = $template_block_id;
						$template = ADMINZ_DIR."inc/file/flatsome_post_type_template.php";
					}			
				}
		    }
		    return $template;
		},10,3);

	}

	function save_custom_taxonomy_metabox( $term_id ) {
		if ( isset( $_POST['tax_template_block_id'] ) ) {
			$custom_value = sanitize_text_field( $_POST['tax_template_block_id'] );
			update_term_meta( $term_id, 'tax_template_block_id', $custom_value );
		}
	}

	function term_taxonomy_meta_box(){
		if(!$this->taxonomy){
			return;
		}
		
		add_action( "{$this->taxonomy}_add_form_fields", function(){
			ob_start();
			?>
			<div class="form-field term-parent-wrap">
				<label for="parent">Layout block ID</label>
				<select name="tax_template_block_id" id="parent" class="postform" aria-describedby="parent-description">
					<option value="">Inherit </option>
					<?php
						$block_posts = get_posts( array( 'post_type' => 'blocks', 'posts_per_page' => -1 ) );
						foreach ( $block_posts as $block_post ) {
							echo '<option value="block_id_' . $block_post->ID . '">' . $block_post->post_title . '</option>';
						}
					?>
				</select>
				<p id="parent-description">Uxbuilder Layout Support - Taxonomy</p>
			</div>
			<?php
			echo ob_get_clean();
		} );

		add_action( "{$this->taxonomy}_edit_form_fields", function($term){
			ob_start();			
			?>
			<tr class="form-field term-parent-wrap">
				<th scope="row">
					<label for="parent">Layout block ID</label>
				</th>
				<td>
					<select name="tax_template_block_id" id="parent" class="postform" aria-describedby="parent-description">
						<option value="">Inherit</option>
						<?php
							$block_posts = get_posts( array( 'post_type' => 'blocks', 'posts_per_page' => -1 ) );
							foreach ( $block_posts as $block_post ) {
								$selected = "";
								if(get_term_meta( $term->term_id, 'tax_template_block_id' , true) == "block_id_".$block_post->ID){
									$selected = "selected";
								}
								echo '<option '.$selected.' value="block_id_' . $block_post->ID . '">' . $block_post->post_title . '</option>';
							}
						?>
					</select>
					<p id="parent-description">Uxbuilder Layout Support - Taxonomy</p>
				</td>
			</tr>
			<?php
			echo ob_get_clean();
		} );

		add_action( 'edited_term', [ $this, 'save_custom_taxonomy_metabox' ] );
		add_action( 'create_term', [ $this, 'save_custom_taxonomy_metabox' ] );
	}

	function post_meta_box(){		
		if(!$this->post_type){
			return;
		}
		$string = $this->template_block_id;
		$taxonomy = str_replace("taxonomy_", "", $string);
		if($taxonomy){
			// Thêm select box vào trang thêm mới term của taxonomy "book_type"
			add_action($taxonomy.'_add_form_fields', function () use($taxonomy) {
			    echo '<div class="form-field">';
			    echo '<label for="'.$taxonomy.'_block_id">UXBlock Template: '.$this->post_type.'</label>';
			    echo '<select id="'.$taxonomy.'_block_id" name="'.$taxonomy.'_block_id">';
			    echo '<option value="0">--</option>';

			    // Lấy danh sách bài viết từ post type 'block'
			    $block_posts = get_posts(array('post_type' => 'blocks', 'posts_per_page' => -1));

			    foreach ($block_posts as $block_post) {
			        echo '<option value="' . $block_post->ID . '">' . $block_post->post_title . '</option>';
			    }

			    echo '</select>';
			    echo '</div>';
			});

			add_action('created_'.$taxonomy, function ($term_id) use($taxonomy) {
			    if (isset($_POST[$taxonomy.'_block_id'])) {
			        update_term_meta($term_id, $taxonomy.'_block_id', $_POST[$taxonomy.'_block_id']);
			    }
			});



			// Thêm select box vào trang chỉnh sửa term của taxonomy "book_type"
			add_action($taxonomy.'_edit_form_fields', function ($term) use($taxonomy){
			    $selected_block_id = get_term_meta($term->term_id, $taxonomy.'_block_id', true);

			    // Lấy danh sách bài viết từ post type 'block'
			    $block_posts = get_posts(array('post_type' => 'blocks', 'posts_per_page' => -1));

			    echo '<tr class="form-field">';
			    echo '<th scope="row" valign="top"><label for="'.$taxonomy.'_block_id">UXBlock Template: '.$this->post_type.':</label></th>';
			    echo '<td>';
			    echo '<select id="'.$taxonomy.'_block_id" name="'.$taxonomy.'_block_id">';
			    echo '<option value="0">--</option>';

			    foreach ($block_posts as $block_post) {
			        echo '<option value="' . $block_post->ID . '"';
			        if ($selected_block_id == $block_post->ID) {
			            echo ' selected';
			        }
			        echo '>' . $block_post->post_title . '</option>';
			    }

			    echo '</select>';
			    echo '</td>';
			    echo '</tr>';
			});

			// Lưu giá trị khi cập nhật term của taxonomy "book_type"
			add_action('edited_'.$taxonomy, function ($term_id) use($taxonomy) {
			    if (isset($_POST[$taxonomy.'_block_id'])) {
			        update_term_meta($term_id, $taxonomy.'_block_id', $_POST[$taxonomy.'_block_id']);
			    }
			});
		}

		// edit post type
		add_action('add_meta_boxes', function () use ($taxonomy) {
            add_meta_box(
                'book_template_metabox',
                'UXBlock Template',
                function ($post) use ($taxonomy) {
                    $selected_block_id = get_post_meta($post->ID, 'template_block_id', true);

                    // Retrieve the list of "block" posts
                    $block_posts = get_posts(array('post_type' => 'blocks', 'posts_per_page' => -1));

                    echo '<p><label for="template_block_id">UXBlock Template</label><p>';
                    echo '<p><select id="template_block_id" name="template_block_id">';
                    echo '<option value="0">--</option>';

                    foreach ($block_posts as $block_post) {
                        echo '<option value="' . $block_post->ID . '"';
                        if ($selected_block_id == $block_post->ID) {
                            echo ' selected';
                        }
                        echo '>' . $block_post->post_title . '</option>';
                    }

                    echo '</select><p>';
                    echo '<p><small>Enabled by Administrator Z. Goto Tool/ Administratorz/ Flatsome/ Uxbuilder Layout Support</small><p>';
                },
                'book', // Custom post type
                'side',  // Where to display the metabox (e.g., 'normal', 'side', 'advanced')
                'default' // Priority
            );
        });

        // save metadata
        add_action('save_post', function ($post_id) use ($taxonomy) {
            if (get_post_type($post_id) == $this->post_type && current_user_can('edit_post', $post_id)) {
                if (isset($_POST['template_block_id'])) {
                    $selected_block_id = sanitize_text_field($_POST['template_block_id']);
                    update_post_meta($post_id, 'template_block_id', $selected_block_id);
                }
            }
        });
	}

	public static function taxonomy_get_block_id($template_block_id){
		$queried_object = get_queried_object();

		// ghi đè giá trị
		if($a = get_term_meta( $queried_object->term_id, 'tax_template_block_id', true)){
			$template_block_id = $a;
		}

		$a = str_replace('block_id_','',$template_block_id);
		if($a){
			return $a;
		}
		return false;
	}

	// lấy block id theo setting từ options
	public static function post_type_get_block_id($template_block_id){
		$return = (int)$template_block_id;		
		global $post; 

		// update_post_meta($post_id, 'template_block_id', $selected_block_id);
		if($meta = get_post_meta($post->ID,'template_block_id',true)){
			$return = $meta;
			return $return;
		}		

		// $_value = "taxonomy_".$_tax;
		if(strpos($template_block_id, "taxonomy_") === 0){
			$taxonomy = str_replace("taxonomy_", "", $template_block_id);			
			$_terms = wp_get_post_terms( $post->ID, $taxonomy );
			$_terms = array_reverse($_terms);
			if(!empty($_terms) and is_array($_terms)){
			    foreach ($_terms as $kt => $term) {
			    	//update_term_meta($term_id, $taxonomy.'_block_id', $_POST[$taxonomy.'_block_id']);				    	
		        	if($_block_id = get_term_meta($term->term_id, $taxonomy.'_block_id', true)){
		        		$return = $_block_id;
		        		return $return;
		        	}
			    }
			}
		}

		// $_value = "block_id_".$block_id;
		if(strpos($template_block_id, "block_id_") === 0){
			$return = str_replace("block_id_", "", $template_block_id);
			return $return;
		}

		



		return $return;
	}
}