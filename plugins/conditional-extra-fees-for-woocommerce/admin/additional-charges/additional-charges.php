<?php

class pisol_cefw_additional_charges_form{
    function __construct(){
        add_action('pi_cefw_extra_form_fields', array($this, 'mainForm'), 10, 1);
        add_filter('pi_cefw_extra_charge_form_data',array($this, 'formData'), 10, 1);
        add_filter('pi_cefw_extra_charge_clone_form_data',array($this, 'cloneData'), 10, 2);
        add_action('pisol_cefw_save_extra_charge', array($this, 'saveForm'),10,1);

        add_action('wp_ajax_pi_cefw_extra_charge_dynamic_value_product', array($this, 'search_product'));

		add_action('wp_ajax_pi_cefw_extra_charge_dynamic_value_category', array($this, 'search_category'));
    }

    function mainForm($data){
        include 'template/additional-charges.php';
    }

    function formData($data){
        $action_value = filter_input( INPUT_GET, 'action' );
        $id_value     = filter_input( INPUT_GET, 'id' );
        if ( isset( $action_value ) && 'edit' === $action_value ) {
            $data['pi_enable_additional_charges'] = get_post_meta( $data['post_id'], 'pi_enable_additional_charges', true );
            $data['pi_enable_additional_charges']       = isset($data['pi_enable_additional_charges']) && 'on' === $data['pi_enable_additional_charges'] ? 'checked' : '';
        }else{
            $data['pi_enable_additional_charges']               = '';
        }
        return $data;
    }

	function cloneData($data, $post_id){
        
        $data['pi_enable_additional_charges'] = get_post_meta( $post_id, 'pi_enable_additional_charges', true );
       
        return $data;
    }

    function saveForm($post_id){
        if ( isset( $_POST['pi_enable_additional_charges'] ) ) {
            update_post_meta( $post_id, 'pi_enable_additional_charges', "on" );
        } else {
            update_post_meta( $post_id, 'pi_enable_additional_charges', "off");
        }
    }

    static function tabName($name, $slug, $active = ""){
        echo "<a href=\"javascript:void(0)\" class=\"bg-secondary p-2 d-block text-center text-light additional-charges-tab border-bottom {$active} \" id=\"add-charges-tab-{$slug}\" data-target=\"#add-charges-tab-content-{$slug}\">{$name}</a>";
    }

    static function additionalChargesEnabled($post_id){
        $add_charges_enabled = get_post_meta( $post_id, 'pi_enable_additional_charges', true );

        return $add_charges_enabled == 'on' ? true : false;
    }

    static function sumOfCharges($name, $data){
        $val = isset($data[$name]) ? $data[$name] : '';
        ?>
        <select name="<?php echo $name; ?>" class="form-control">
            <option value="all" <?php selected($val, 'all'); ?>>Sum of all matched charges</option>
            <option value="largest" <?php selected($val, 'largest'); ?>>Largest of the matched charges</option>
            <option value="smallest" <?php selected($val, 'smallest'); ?>>Smallest of the matched charges</option>
        </select>
        <?php
    }

	static function productOption($product_ids){
		if(empty($product_ids)) return ;

		$html = '';
		if(!is_array($product_ids)){
			$product_ids = array($product_ids);
		}
		foreach($product_ids as $product_id){
			
			$title = str_replace("&#8211;",">",get_the_title( $product_id ));
			$html .= sprintf('<option value="%s">%s</option>', esc_attr($product_id), esc_html($title).' (#'.$product_id.')');
			
		}
		return $html;
	}

	static function categoryOption($category_ids){
		if(empty($category_ids)) return ;

		$html = '';
		if(!is_array($category_ids)){
			$category_ids = array($category_ids);
		}
		foreach($category_ids as $category_id){

			$cat = get_term($category_id, 'product_cat');
			
			if(is_wp_error($cat) || empty($cat)) continue;
			
			$html .= sprintf('<option value="%s">%s</option>', esc_attr($category_id), esc_html($cat->name).' (#'.$category_id.')');
			
		}
		return $html;
	}

    public function search_product( $x = '', $post_types = array( 'product' ) ) {
		$cap = Pi_cefw_Menu::getCapability();
		if ( ! current_user_can( $cap ) ) {
			return;
		}

        ob_start();
        
        if(!isset($_GET['keyword'])) die;

		$keyword = isset($_GET['keyword']) ? sanitize_text_field($_GET['keyword']) : "";

		if ( empty( $keyword ) ) {
			die();
		}
		$arg            = array(
			'post_status'    => 'publish',
			'post_type'      => $post_types,
			'posts_per_page' => 50,
			's'              => $keyword

		);
		$the_query      = new WP_Query( $arg );
		$found_products = array();
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$prd = wc_get_product( get_the_ID() );
				$cat_ids  = wp_get_post_terms( get_the_ID(), 'product_cat', array( 'fields' => 'ids' ) );

				/* remove grouped product or external product */
				if($prd->is_type('grouped') || $prd->is_type('external')){
					continue;
				}
				

				$product_id    = get_the_ID();
				$product_title = get_the_title().' (#'.$product_id.')';
				$the_product   = new WC_Product( $product_id );
				if ( ! $the_product->is_in_stock() ) {
					$product_title .= ' (Out of stock)';
				}
				$product          = array( 'id' => $product_id, 'text' => $product_title );
				$found_products[] = $product;

				if ( $prd->has_child() && $prd->is_type( 'variable' ) ) {
					$product_children = $prd->get_children();
					if ( count( $product_children ) ) {
						foreach ( $product_children as $product_child ) {
							if ( self::wc_version_check() ) {
								$product = array(
									'id'   => $product_child,
									'text' => str_replace("&#8211;",">",get_the_title( $product_child )).' (#'.$product_child.')'
								);

							} else {
								$child_wc  = wc_get_product( $product_child );
								$get_atts  = $child_wc->get_variation_attributes();
								$attr_name = array_values( $get_atts )[0];
								$product   = array(
									'id'   => $product_child,
									'text' => get_the_title() . ' - ' . $attr_name
								);

							}
							$found_products[] = $product;
						}

					}
				} 			
			}
        }
		wp_send_json( $found_products );
		die;
    }

	public function search_category() {
		$cap = Pi_cefw_Menu::getCapability();
		if ( ! current_user_can( $cap ) ) {
			return;
		}

		ob_start();

		$keyword = filter_input( INPUT_GET, 'keyword' );

		if ( empty( $keyword ) ) {
			die();
		}
		$categories = get_terms(
			array(
				'taxonomy' => 'product_cat',
				'orderby'  => 'name',
				'order'    => 'ASC',
				'search'   => $keyword,
				'number'   => 100
			)
		);
		$items      = array();
		if ( count( $categories ) ) {
			foreach ( $categories as $category ) {
				$item    = array(
					'id'   => $category->term_id,
					'text' => $category->name
				);
				$items[] = $item;
			}
		}
		wp_send_json( $items );
		die;
    }

    
    static function wc_version_check( $version = '3.0' ) {
            if ( class_exists( 'WooCommerce' ) ) {
                global $woocommerce;
                if ( version_compare( $woocommerce->version, $version, ">=" ) ) {
                    return true;
                }
            }
            return false;
    }
    
}
new pisol_cefw_additional_charges_form();