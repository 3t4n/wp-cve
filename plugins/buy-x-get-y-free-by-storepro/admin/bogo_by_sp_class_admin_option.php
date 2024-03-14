<?php

class bogo_by_sp_class_admin_option{

    public $plugin_name;

    private $settings = array();

    private $active_tab;

    private $bogo_tab = 'default';

    private $bogo_settings = 'bogo-by-storepro';
    
    

    function __construct($plugin_name){
        $this->plugin_name = $plugin_name;
        $this->free_product = $this->getSavedProductArray();
        $this->free_product_2 = $this->getSavedProduct2Array();
        $this->free_product_3 = $this->getSavedProduct3Array();
        $this->buy_product = $this->getBuyProductArray();
        $this->buy_product_2 = $this->getBuyProduct2Array();
        $this->buy_product_3 = $this->getBuyProduct3Array();

        $this->settings = array(
           
			array('field'=>'title', 'label'=>__("Buy One Get One Free"), 'type'=>"setting_category"),
            array('field'=>'sp_bogo_disable_global', 'label'=>__('Enable','bogo-by-sp'),'type'=>'switch', 'default'=>0,   'desc'=>__('Enable rule globally','bogo-by-sp')),
			array('field'=>'sp_bogo_enable_cart_message', 'label'=>__('Cart Message','bogo-by-sp'),'type'=>'switch', 'default'=>0,   'desc'=>__('Enable cart message','bogo-by-sp')),
			array('field'=>'sp_bogo_cart_message_content', 'label'=>__('Custom Cart Message ','bogo-by-sp'),'type'=>'textarea', 'default'=>"", 'desc'=>__('(Add the content you want to display as cart message)','bogo-by-sp')),

			array('field'=>'sp_bogo_product_buy', 'label'=>__('Select Your First Main Product','bogo-by-sp'),'type'=>'select', 'default'=>"",  'desc'=>__('(This is the product along with you offer a free product)','bogo-by-sp'), 'value'=>$this->buy_product),			
			array('field'=>'sp_bogo_product_buy_quantity', 'label'=>__('Quantity Of The First Main Product','bogo-by-sp'),'type'=>'number', 'default'=>1, 'desc'=>__('(Select the minimum Quantity of Main Product to be Purchased)','bogo-by-sp'),'min'=>1, 'step'=>1),
			array('field'=>'sp_bogo_product_free', 'label'=>__('Select The Promotional Product','bogo-by-sp'),'type'=>'select', 'default'=>"", 'desc'=>__('(This is the product given as free)','bogo-by-sp'), 'value'=>$this->free_product),
			array('field'=>'sp_bogo_product_free_quantity', 'label'=>__('Quantity Of The Promotional Product','bogo-by-sp'),'type'=>'number', 'default'=>1, 'desc'=>__('(Select the Quantity of free products to be given)','bogo-by-sp'), 'min'=>1, 'step'=>1),



            array('field'=>'sp_bogo_product_2_buy', 'label'=>__('Select Your Second Main Product','bogo-by-sp'),'type'=>'select', 'default'=>"",  'desc'=>__('(This is the product along with you offer a free product)','bogo-by-sp'), 'value'=>$this->buy_product_2),			
			array('field'=>'sp_bogo_product_2_buy_quantity', 'label'=>__('Quantity Of The Second Main Product','bogo-by-sp'),'type'=>'number', 'default'=>1, 'desc'=>__('(Select the minimum Quantity of the Second Main Product to be Purchased)','bogo-by-sp'),'min'=>1, 'step'=>1),
			array('field'=>'sp_bogo_product_2_free', 'label'=>__('Select The Promotional Product','bogo-by-sp'),'type'=>'select', 'default'=>"", 'desc'=>__('(This is the product given as free)','bogo-by-sp'), 'value'=>$this->free_product_2),
			array('field'=>'sp_bogo_product_2_free_quantity', 'label'=>__('Quantity Of The Promotional Product','bogo-by-sp'),'type'=>'number', 'default'=>1, 'desc'=>__('(Select the Quantity of free products to be given)','bogo-by-sp'), 'min'=>1, 'step'=>1),

            array('field'=>'sp_bogo_product_3_buy', 'label'=>__('Select Your Third Main Product','bogo-by-sp'),'type'=>'select', 'default'=>"",  'desc'=>__('(This is the product along with you offer a free product)','bogo-by-sp'), 'value'=>$this->buy_product_3),			
			array('field'=>'sp_bogo_product_3_buy_quantity', 'label'=>__('Quantity Of The Third Main Product','bogo-by-sp'),'type'=>'number', 'default'=>1, 'desc'=>__('(Select the minimum Quantity of the Third Main Product to be Purchased)','bogo-by-sp'),'min'=>1, 'step'=>1),
			array('field'=>'sp_bogo_product_3_free', 'label'=>__('Select The Promotional Product','bogo-by-sp'),'type'=>'select', 'default'=>"", 'desc'=>__('(This is the product given as free)','bogo-by-sp'), 'value'=>$this->free_product_3),
			array('field'=>'sp_bogo_product_3_free_quantity', 'label'=>__('Quantity Of The Promotional Product','bogo-by-sp'),'type'=>'number', 'default'=>1, 'desc'=>__('(Select the Quantity of free products to be given)','bogo-by-sp'), 'min'=>1, 'step'=>1),


            array('field'=>'sp_bogo_product_free_icon', 'label'=>__('Text to be displayed on the promotional product ','bogo-by-sp'),'type'=>'text', 'default'=>"Get it for Free!! ", 'value' => "Get it for Free!!",   'desc'=>__('(Enter the text to be displayed eg: FREE, leave it blank to remove the label) ','bogo-by-sp')),
            array('field'=>'sp_bogo_product_free_text_single', 'label'=>__('Text to be displayed on the product page of the promotional item ','bogo-by-sp'),'type'=>'text', 'default'=>"This item comes for free ", 'value' =>"This item comes for free ", 'desc'=>__('(Enter the text to be displayed eg: This item comes for free (Quantity - x ) with Product X (Minimum Quantity - y ) )','bogo-by-sp')),        
            
           
        );
        
        $this->tab = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
        $this->active_tab = $this->tab != "" ? $this->tab : 'default';

        if($this->bogo_tab == $this->active_tab){
            add_action($this->plugin_name.'_tab_content', array($this,'tab_content'));
        }


        add_action($this->plugin_name.'_tab', array($this,'tab'),1);

       
        $this->register_settings();

       
    }

    function getSavedProductArray(){
        $free_product_id = get_option('sp_bogo_product_free',"");
       
        if( empty($free_product_id )) return array();

        $product_title = get_the_title($free_product_id);
      

        $product = array( $free_product_id => $product_title,);

        return $product;
    }    

    //Product 2
    function getSavedProduct2Array(){
        $free_product_2_id = get_option('sp_bogo_product_2_free',"");
       
        if( empty($free_product_2_id )) return array();

        $product_2_title = get_the_title($free_product_2_id);
      

        $product_2 = array( $free_product_2_id => $product_2_title,);

        return $product_2;
    }    
    //Product 3
    function getSavedProduct3Array(){
        $free_product_3_id = get_option('sp_bogo_product_3_free',"");
       
        if( empty($free_product_3_id )) return array();

        $product_3_title = get_the_title($free_product_3_id);
      

        $product_3 = array( $free_product_3_id => $product_3_title,);

        return $product_3;
    }    
	
	function getBuyProductArray(){
        $buy_product_id = get_option('sp_bogo_product_buy',"");

        if( empty($buy_product_id)) return array();

        $product_title = get_the_title($buy_product_id);
        

        $buy_product = array( $buy_product_id => $product_title);

        return $buy_product;
    }

    //Product 2
    function getBuyProduct2Array(){
        $buy_product_2_id = get_option('sp_bogo_product_2_buy',"");

        if( empty($buy_product_2_id)) return array();

        $product_2_title = get_the_title($buy_product_2_id);
        

        $buy_product_2 = array( $buy_product_2_id => $product_2_title);

        return $buy_product_2;
    }

    //Product 3
    function getBuyProduct3Array(){
        $buy_product_3_id = get_option('sp_bogo_product_3_buy',"");

        if( empty($buy_product_3_id)) return array();

        $product_3_title = get_the_title($buy_product_3_id);
        

        $buy_product_3= array( $buy_product_3_id => $product_3_title);

        return $buy_product_3;
    }

    
    function delete_settings(){
        foreach($this->settings as $setting){
            delete_option( $setting['field'] );
        }
    }

    function register_settings(){   

        foreach($this->settings as $setting){
            register_setting( $this->bogo_settings, $setting['field']);
        }
    
    }

    function tab_content(){
        
       ?>

	<div class="tab_container">
			<input id="tab1" type="radio" name="tabs" checked>
			<label for="tab1" class="new"><span>Settings</span></label>
			<h2 class="sp_head">Buy X Get Y Free<br>
			<!-- <span style="color: #F7931E;font-size: 13px;float:right;">By <img  src="<?php echo plugin_dir_url( __FILE__ ); ?>img/Sp_bogo.png"></span> --> </h2>

			<section id="content1" class="tab-content">
				  <form method="post" action="options.php">
				<?php settings_fields( $this->bogo_settings ); ?>
				<?php
					foreach($this->settings as $setting){
						new bogo_by_sp_form($setting, $this->bogo_settings);
					}
				?>
				<input type="submit" class="btn btn-primary spsave" value="Save Option" />
				</form>
				<p class="link">
			  Powered by: <a href="https://storepro.io/" style="color: #ee6443;">StorePro</a>
			</p> 
			</section>
			
	</div>  
	<?php
		}	
	}

new bogo_by_sp_class_admin_option($this->plugin_name);

