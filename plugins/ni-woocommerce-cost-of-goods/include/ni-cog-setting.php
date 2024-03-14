<?php
if ( ! defined( 'ABSPATH' ) ) { exit;}
if( !class_exists( 'Ni_COG_Setting' ) ) {
	include_once("ni-cog-function.php"); 
	class Ni_COG_Setting extends Ni_COG_Function{
		public function __construct(){
		}
		function page_init(){
		 $input_type="text";
		 $input_type="hidden";
		 
		//print_r(get_option("niwoocog_setting"));
		 
		 $ni_cog_meta_key = $this->get_cog_setting_by_key("ni_cog_meta_key",'_ni_cost_goods');
		 $enable_profit_percentage = $this->get_cog_setting_by_key("enable_profit_percentage",'no');
		 $enable_net_profit = $this->get_cog_setting_by_key("enable_net_profit",'no');
		 $enable_net_profit_margin = $this->get_cog_setting_by_key("enable_net_profit_margin",'no');
		 $enable_product_cost = $this->get_cog_setting_by_key("enable_product_cost",'no');
		 if(empty( $ni_cog_meta_key)){
			 $ni_cog_meta_key = '_ni_cost_goods';	 
		 }
		 ?>
         <div class="container-fluid">
         	<div id='niwoocog'>
            	<div class="row">
                	<div class="col">
                    	 <div class="card " style="max-width:50%">
                         <div class="card-header bd-indigo-400">
                             <?php esc_html_e( 'Cost Of Goods Settings', 'wooreportcog' )?> 
                          </div>
                          <form name="frm_cog_setting" id="frm_cog_setting">
                          	<div class="card-body">
                           	<div class="row">
                                    <div class="col-6">
                                        <label for="order_days">
                                            <?php  esc_html_e("Ni Cost Of Goods Meta key",'wooreportcog'); ?>
                                        </label>
                                    </div>
                                    <div class="col-6">
                                       <input type="text" id="ni_cog_meta_key" name="ni_cog_meta_key" value="<?php echo $ni_cog_meta_key; ?>"  class="form-control" />
                                       <span>  <?php  esc_html_e(" Ni Cost Of Goods Key",'wooreportcog'); ?> : _ni_cost_goods</span>
                                    </div>
                                </div>
                               <div class="row">
                                    	<div  class="col"  style="padding:10px;"></div>
                                    </div>  
                            <div class="row">
                                  
                                    <div class="col-6">
                                        <label for="order_country">
                                            <?php  esc_html_e("Enable product cost columns",'wooreportcog'); ?>
                                        </label>
                                    </div>
                                    <div class="col-6">
                                       <input type="checkbox" name="enable_product_cost"  class="form-control" id="enable_product_cost" <?php echo ($enable_product_cost==='yes')?'checked':''; ?> />
                                    </div>
                               </div>
                             <div class="row">
                                    	<div  class="col"  style="padding:10px;"></div>
                                    </div>
                            <div class="row">
                                  
                                    <div class="col-6">
                                        <label for="order_country">
                                            <?php  esc_html_e("Enable net profit margin (%) columns",'wooreportcog'); ?>
                                        </label>
                                    </div>
                                    <div class="col-6">
                                      <input type="checkbox" name="enable_net_profit_margin"  class="form-control" id="enable_net_profit_margin"  <?php echo ($enable_net_profit_margin==='yes')?'checked':''; ?> />
                                    </div>
                               </div>
                              <div class="row">
                                    	<div  class="col"  style="padding:10px;"></div>
                                    </div>  
                            <div class="row">
                                  
                                    <div class="col-6">
                                        <label for="order_country">
                                            <?php  esc_html_e("Enable net profit columns",'wooreportcog'); ?>
                                        </label>
                                    </div>
                                    <div class="col-6">
                                    <input type="checkbox" name="enable_net_profit"  class="form-control" id="enable_net_profit"  <?php echo ($enable_net_profit==='yes')?'checked':''; ?> />
                                    </div>
                               </div>
                              <div class="row">
                                    	<div  class="col"  style="padding:10px;"></div>
                                    </div>  
                            <div class="row">
                                  
                                    <div class="col-6">
                                        <label for="order_country">
                                            <?php  esc_html_e("Enable profit percentage (%) columns",'wooreportcog'); ?>
                                        </label>
                                    </div>
                                    <div class="col-6">
                                   	<input type="checkbox"  class="form-control" name="enable_profit_percentage" id="enable_profit_percentage"  <?php echo ($enable_profit_percentage==='yes')?'checked':''; ?>  />
                                    </div>
                               </div>
                               
                               <div class="row">
                                    	<div  class="col"  style="padding:10px;"></div>
                                    </div> 
                              <div class="row">
                                  
                                    <div class="col">
                                       <input type="submit" value="<?php esc_html_e( 'Save', 'wooreportcog' )?>" class="btn bd-blue-500  mb-2"  />
                                    </div>
                                  
                               </div>       
                                   
                          </div>
                          <input type="<?php echo  $input_type; ?>" name="sub_action" value="ni_cog_setting">
             		<input type="<?php echo  $input_type; ?>" name="action" value="ni_cog_action">
                          </form>
                          
                        </div>
                    </div>
                </div>
                
                <div class="row">
                	<div class="col">
                    	  <div class="ajax_cog_content"></div>
                    </div>
                </div>
            </div> 
         </div>
        <?php
		}
		function page_ajax(){
			$niwoocog_setting = array();
			$niwoocog_setting["ni_cog_meta_key"] = sanitize_text_field( isset($_REQUEST["ni_cog_meta_key"])?$_REQUEST["ni_cog_meta_key"]:"_ni_cost_goods");
			$niwoocog_setting["enable_profit_percentage"] = sanitize_text_field( isset($_REQUEST["enable_profit_percentage"])?'yes':"no");
			$niwoocog_setting["enable_net_profit"] = sanitize_text_field( isset($_REQUEST["enable_net_profit"])?'yes':"no");
			$niwoocog_setting["enable_net_profit_margin"] = sanitize_text_field( isset($_REQUEST["enable_net_profit_margin"])?'yes':"no");
			$niwoocog_setting["enable_product_cost"] = sanitize_text_field( isset($_REQUEST["enable_product_cost"])?'yes':"no");
			//print_r($niwoocog_setting);
			
			update_option("niwoocog_setting", $niwoocog_setting );
			esc_html_e( "Settings saved successfully.",'wooreportcog');
			die;
			wp_die();	
		}
	}
}
?>