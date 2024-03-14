<?php
if ( ! defined( 'ABSPATH' ) ) { exit;}
if( !class_exists( 'Ni_WooCommerce_Cost_Of_Goods_Quick_Edit' ) ) { 
include_once("ni-cog-function.php"); 
	class Ni_WooCommerce_Cost_Of_Goods_Quick_Edit  extends Ni_COG_Function{
			var $ni_constant = array();  
		 var $ni_cost_goods ='_ni_cost_goods';
		 public function __construct($ni_constant = array()){
			$this->ni_constant = $ni_constant; 
			
			$ni_cog_meta_key = $this->get_cog_setting_by_key("ni_cog_meta_key",'_ni_cost_goods');
			if(empty($ni_cog_meta_key)){
			 	$ni_cog_meta_key = '_ni_cost_goods';	 
		 	}
			$this->ni_cost_goods = $ni_cog_meta_key;	
			
		   add_action( 'quick_edit_custom_box',  array(&$this,'quick_edit_add_nicog_field' ), 10000, 2 );	
		   add_action( 'save_post',  			 array(&$this,'save_quick_edit_ni_cog_field' ) );	 
		   add_filter( 'post_row_actions',  	 array(&$this,'expand_quick_edit_nicog_field_link' ), 10, 2  );	 	
		   add_action( 'admin_footer',  		 array(&$this,'quick_edit_nicog_field_javascript') );		
		   
		   
		   /*Bulk Edit Using WooCommerce Hook*/
		   add_action( 'woocommerce_product_bulk_edit_end', array(&$this,'woocommerce_product_bulk_edit_end') );
		   add_action( 'save_post',  array(&$this,'save_quick_edit_bulk_ni_cog_field' ) );	 
		   		
		}
		
		function woocommerce_product_bulk_edit_end(){
			?>
             <div class="inline-edit-group">
                <label class="alignleft">
                    <span class="title"><?php _e( 'Cost Of Goods', 'wooreportcog' ); ?> </span>
                    <span class="input-text-wrap">
                        <select class="change_regular_ni_cost_goods change_to" name="change_regular_ni_cost_goods">
                            <?php
                            $options = array(
                                ''  => __( '— No change —', 'wooreportcog' ),
                                '1' => __( 'Change to:', 'wooreportcog' )
                            );
                            foreach ( $options as $key => $value ) {
                                echo '<option value="' . esc_attr( $key ) . '">' . $value . '</option>';
                            }
                            ?>
                        </select>
                    </span>
                </label>
                <label class="change-input">
                    <input type="text" name="_bulk_ni_cost_goods" class="text bulk_ni_cost_goods sale_price" placeholder="<?php printf( esc_attr__( 'Enter Cost Of Goods (%s)', 'wooreportcog' ), get_woocommerce_currency_symbol() ); ?>" value="" />
                </label>
            </div>
            <?php
		}
		
		function save_quick_edit_bulk_ni_cog_field( $post_id = '') {
			
			if(!isset($_REQUEST['_bulk_ni_cost_goods'])){
				return $post_id;
			}
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return $post_id;
			}
			
			if(!isset($_REQUEST['post_type'])){
				return $post_id;
			}
			
			if('product' != $_REQUEST['post_type']){
				return $post_id;
			}
			
			if (!current_user_can( 'edit_post', $post_id)){
				return $post_id;
			}
			 
			$product = wc_get_product($post_id);
			
			if(!$product){
				return $post_id;
			}
			
			$nicog_product_type = $product->get_type();
			
			if ($nicog_product_type=='simple'){
				if(isset($_REQUEST['change_regular_ni_cost_goods'])){
					if($_REQUEST['change_regular_ni_cost_goods'] == 1){
						$data = isset($_REQUEST['_bulk_ni_cost_goods']) ? $_REQUEST['_bulk_ni_cost_goods'] : 0;
						if(is_array($ni_cost_goods)){
							error_log("Bulk Cost of Goods Array(0003)".$data);
							return false;
						}
						//update_post_meta( $post_id, '_ni_cost_goods', $data );	
						$this->update_cost_price($post_id,$data);
					}
				}
			}
			return $post_id;
			
		}
		
				
		function quick_edit_add_nicog_field( $column_name, $post_type ) {
			if ( 'ni_cost_goods' != $column_name ) {
				return;
			}
			?>
			<fieldset class="inline-edit-col-left _nicog_fieldset">
				<div class="inline-edit-col wp-clearfix">
				<label class="alignleft">
					<span class=""><?php esc_html_e( 'Cost Of Goods', '' ); ?></span>
					<span class="input-text-wrap alignright">
						<input type="text" name="_ni_cost_goods" id="_ni_cost_goods" class="text wc_input_price regular_price _ni_cost_goods_qedit" value="">
					</span>
				</label>
				</div>
			</fieldset>
			<?php
		}
		
				
		
		
		
		function save_quick_edit_ni_cog_field( $post_id = '') {
			
			if(!isset($_REQUEST[$this->ni_cost_goods])){
				return $post_id;
			}
			
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return $post_id;
			}
			
			if(!isset($_REQUEST['post_type'])){
				return $post_id;
			}
			
			if('product' != $_REQUEST['post_type']){
				return $post_id;
			}
			
			if (!current_user_can( 'edit_post', $post_id)){
				return $post_id;
			}
			 
			$product = wc_get_product($post_id);
			
			if(!$product){
				return $post_id;
			}
			
			$nicog_product_type = $product->get_type();
			
			if ($nicog_product_type=='simple'){
				if(isset($_REQUEST[$this->ni_cost_goods])){
					$data = isset($_REQUEST[$this->ni_cost_goods]) ? $_REQUEST[$this->ni_cost_goods] : 0;
					if(is_array($this->ni_cost_goods)){
						error_log("Cost of Goods Array(0004):- ".$this->ni_cost_goods);
						return false;
					}
					//update_post_meta( $post_id, '_ni_cost_goods', $data );	
					$this->update_cost_price($post_id,$data);
				}				
			}
			return $post_id;
			
		}
		
		
		function expand_quick_edit_nicog_field_link( $actions = '', $post = null ) {
			global $current_screen;

			if ( 'product' != $post->post_type ) {
				return $actions;
			}
			
			
			$product = wc_get_product( $post->ID);
			
			if(!$product){
				$post->ID;
			}
			
			$nicog_product_type=$product->get_type();				 
			 
			$data                               = $this->get_cost_price( $post->ID );
			$data                               = empty( $data ) ? '' : $data;
			$actions['inline hide-if-no-js']    = '<a href="#" class="editinline" title="';
			$actions['inline hide-if-no-js']    .= esc_attr( 'Edit this item inline' ) . '"';
			$actions['inline hide-if-no-js']    .= " onclick=\"get_nicog_field_value('{$data}','{$nicog_product_type}')\" >";
			$actions['inline hide-if-no-js']    .= 'Quick Edit';
			$actions['inline hide-if-no-js']    .= '</a>';

			return $actions;
		}
		function quick_edit_nicog_field_javascript() {
			global $current_screen;

			if ( 'product' != $current_screen->post_type ) {
				return;
			}
		?>
			<script type="text/javascript">
				function get_nicog_field_value( fieldValue, nicog_product_type ) {
					inlineEditPost.revert();
					if (nicog_product_type=='simple'){
						jQuery('._nicog_fieldset').show();
						jQuery('._ni_cost_goods_qedit').val(fieldValue);
						
					}else{
						
						jQuery('._nicog_fieldset').hide();
					}
				}
			</script>
		<?php
		}
		
	}
}