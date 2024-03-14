<?php
 /*
   Rule CPT rows are stored.  At rule store/update
   time, a master rule option array is (re)created, to allow speedier access to rule information at
   product/cart processing time.
 */
class VTMAM_Rules_UI { 
	
	public function __construct(){       
    global $post, $vtmam_info;
    add_action( 'add_meta_boxes_vtmam-rule', array(&$this, 'vtmam_remove_meta_boxes') );   
    add_action( 'add_meta_boxes_vtmam-rule', array(&$this, 'vtmam_add_metaboxes') );
    add_action( "admin_enqueue_scripts",     array(&$this, 'vtmam_enqueue_script'));  
   
    //all in one seo fix
    add_action( 'add_meta_boxes_vtmam-rule', array($this, 'vtmam_remove_all_in_one_seo_aiosp') ); 
    
    //AJAX actions
    //add_action( 'wp_ajax_vtmam_ajax_load_variations', array($this, 'vtmam_ajax_load_variations') );                    //v2.0.0a free version doesn't need this
    //add_action( 'wp_ajax_noprov_vtmam_ajax_load_variations', array($this, 'vtmam_ajax_load_variations') );             //v2.0.0a free version doesn't need this
	}
 
  //v2.0.0a recode function.  
  public function vtmam_enqueue_script() {
    global $post_type;
    if ( 'vtmam-rule' != $post_type ) {
                //error_log( print_r(  'exit 001', true ) );    
        return;
    }


        //****************
        //v2.0.0a NEW STUFF end
        //****************   
    
        //v2.0.0a following moved down to here     
        //wp_register_style ('vtmam-admin-style', VTMAM_URL.'/admin/css/vtmam-admin-style-v002.css' );  //v2.0.0a NEW CSS IN FILE
        wp_register_style ('vtmam-admin-style', VTMAM_URL.'/admin/css/vtmam-admin-style-' .VTMAM_ADMIN_CSS_FILE_VERSION. '.css' );  //v2.0.0a NEW CSS =for all= IN FILE
        
        wp_enqueue_style  ('vtmam-admin-style');
        wp_register_script('vtmam-admin-script', VTMAM_URL.'/admin/js/vtmam-admin-script.js' );  
        //wp_enqueue_script ('vtmam-admin-script');
        wp_enqueue_script ('vtmam-admin-script', array('jquery'), false, true);   //v2.0.0a - added jquery as dependancy
        
        /*       //v2.0.0a free version doesn't need this
        //AJAX resources
        // see http://wp.smashingmagazine.com/2011/10/18/how-to-use-ajax-in-wordpress/
        //     http://wpmu.org/how-to-use-ajax-with-php-on-your-wp-site-without-a-plugin/
        wp_register_script( "vtmam_variations_script", plugin_dir_url( __FILE__ ).'/admin/js/vtmam-variations-script.js', array('jquery') );
        //  "variationsInAjax"  used in URL jquery statement: "url : variationsInAjax.ajaxurl"
        wp_localize_script( 'vtmam_variations_script', 'variationsInAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 'location' => 'post', 'manual' => 'false' ));        
        wp_enqueue_script( 'vtmam_variations_script' );
        */
        //error_log( print_r(  'AFTER REGISTERS =', true ) );
    
    return;
  }    
  
  public function vtmam_remove_meta_boxes() {
     if(!current_user_can('administrator')) {  
      	remove_meta_box( 'revisionsdiv', 'post', 'normal' ); // Revisions meta box
        remove_meta_box( 'commentsdiv', 'vtmam-rule', 'normal' ); // Comments meta box
      	remove_meta_box( 'authordiv', 'vtmam-rule', 'normal' ); // Author meta box
      	remove_meta_box( 'slugdiv', 'vtmam-rule', 'normal' );	// Slug meta box        	
      	remove_meta_box( 'postexcerpt', 'vtmam-rule', 'normal' ); // Excerpt meta box
      	remove_meta_box( 'formatdiv', 'vtmam-rule', 'normal' ); // Post format meta box
      	remove_meta_box( 'trackbacksdiv', 'vtmam-rule', 'normal' ); // Trackbacks meta box
      	remove_meta_box( 'postcustom', 'vtmam-rule', 'normal' ); // Custom fields meta box
      	remove_meta_box( 'commentstatusdiv', 'vtmam-rule', 'normal' ); // Comment status meta box
      	remove_meta_box( 'postimagediv', 'vtmam-rule', 'side' ); // Featured image meta box
      	remove_meta_box( 'pageparentdiv', 'vtmam-rule', 'side' ); // Page attributes meta box
        remove_meta_box( 'categorydiv', 'vtmam-rule', 'side' ); // Category meta box
        remove_meta_box( 'tagsdiv-post_tag', 'vtmam-rule', 'side' ); // Post tags meta box
        remove_meta_box( 'tagsdiv-vtmam_rule_category', 'vtmam-rule', 'side' ); // vtmam_rule_category tags  
        remove_meta_box('relateddiv', 'vtmam-rule', 'side');                  
      } 
 
  }
        
        
  public  function vtmam_add_metaboxes() {
      global $post, $vtmam_info, $vtmam_rule, $vtmam_rules_set;        

      $found_rule = false;                            
      if ($post->ID > ' ' ) {
        $post_id =  $post->ID;
        $vtmam_rules_set = vtmam_get_rules_set(); //v2.0.0
        $sizeof_rules_set = is_array($vtmam_rules_set) ? sizeof($vtmam_rules_set) : 0; //v2.0.0
        for($i=0; $i < $sizeof_rules_set; $i++) {  //v2.0.0
           if ($vtmam_rules_set[$i]->post_id == $post_id) {
              $vtmam_rule = $vtmam_rules_set[$i];  //load vtmam-rule               
              $found_rule = true;
              $found_rule_index = $i; 
              $i =  $sizeof_rules_set;
           }
        }
      } 

      if (!$found_rule) {
        //initialize rule
        $vtmam_rule = new VTMAM_Rule;  
         //fill in standard default values not already supplied
        $selected = 's';
        $vtmam_rule->inpop[1]['user_input'] = $selected; //’use selection groups’ by default
        $vtmam_rule->specChoice_in[0]['user_input'] = $selected;
        $vtmam_rule->amtSelected[0]['user_input'] = $selected; 
        $vtmam_rule->role_and_or_in[1]['user_input'] = $selected;  // 'or'
        $vtmam_rule->minandmaxSelected[0]['user_input'] = $selected; //minimum or maximum rule
        $vtmam_rule->maxRule_typeSelected[0]['user_input'] = $selected; // 'cart'
      }
                  
      $sizeof_rule_error_message = is_array($vtmam_rule->rule_error_message) ? sizeof($vtmam_rule->rule_error_message) : 0; //v2.0.0
      //if ( sizeof($vtmam_rule->rule_error_message ) > 0 ) {    //these error messages are from the last upd action attempt, coming from vtmam-rules-update.php
      if ( $sizeof_rule_error_message > 0 ) {
           add_meta_box('vtmam-errmsg', __('Update Error Messages :: The rule is not active until these are resolved ::', 'vtmam'), array(&$this, 'vtmam_error_messages'), 'vtmam-rule', 'normal', 'high');
      }
          
      add_meta_box('vtmam-rule-minandmax', __('Rule Evaluation Type', 'vtmam'), array(&$this, 'vtmam_rule_minandmax'), 'vtmam-rule', 'normal', 'high');
      add_meta_box('vtmam-max-rule-type', __('Min/Max Rule Type Selection', 'vtmam'), array(&$this, 'vtmam_max_rule_type'), 'vtmam-rule', 'normal', 'high');   //v2.0.0
      add_meta_box('vtmam-pop-in-select', __('Cart Search Criteria', 'vtmam'), array(&$this, 'vtmam_pop_in_select'), 'vtmam-rule', 'normal', 'high');                      
      add_meta_box('vtmam-pop-in-specifics', __('Rule Application Method', 'vtmam'), array(&$this, 'vtmam_pop_in_specifics'), 'vtmam-rule', 'normal', 'high');
      add_meta_box('vtmam-rule-amount', __('Quantity or Price Min or Max Amount', 'vtmam'), array(&$this, 'vtmam_rule_amount'), 'vtmam-rule', 'normal', 'high');
      add_meta_box('vtmam-rule-repeating-groups', __('Minimum Purchase Repeating Groups', 'vtmam'), array(&$this, 'vtmam_rule_repeating_groups'), 'vtmam-rule', 'normal', 'default');  //v1.07.9
      add_meta_box('vtmam-rule-custom-message', __('Custom Message', 'vtmam'), array(&$this, 'vtmam_rule_custom_message'), 'vtmam-rule', 'normal', 'default');  //v1.07      
      add_meta_box('vtmam-rule-id', __('Min or Max Purchase Rule ID', 'vtmam'), array(&$this, 'vtmam_rule_id'), 'vtmam-rule', 'side', 'low'); //low = below Publish box
      add_meta_box('vtmam-rule-resources', __('Resources', 'vtmam'), array(&$this, 'vtmam_rule_resources'), 'vtmam-rule', 'side', 'low'); //low = below Publish box 
            
      //add help tab to this screen... 
      $content = '<br><a  href="' . VTMAM_DOCUMENTATION_PATH_PRO_BY_PARENT . '"  title="Access Plugin Documentation">Access Plugin Documentation</a>';
      $screen = get_current_screen();
      $screen->add_help_tab( array( 
         'id' => 'vtmam-help',            //unique id for the tab
         'title' => 'Min and Max Purchase Help',      //unique visible title for the tab
         'content' => $content  //actual help text
        ) ); 
			
			//no session write needed, not accessed in free version
        
     return;   
  }                   
   
                                                    
  public function vtmam_error_messages() {     
      global $post, $vtmam_rule;
      echo "<div class='alert-message alert-danger'>" ;       
      $sizeof_rule_error_message = is_array($vtmam_rule->rule_error_message) ? sizeof($vtmam_rule->rule_error_message) : 0; //v2.0.0
      for($i=0; $i < $sizeof_rule_error_message; $i++) {   //v2.0.0                                      
            echo '<div class="vtmam-error"><p>'; 
            echo $vtmam_rule->rule_error_message[$i];
            echo '</p></div>';            
      } //end for loop
          
      echo "</div>";    
      if( $post->post_status == 'publish') { //if post status not = pending, make it so  
          $post_id = $post->ID;
          global $wpdb;
          $wpdb->update( $wpdb->posts, array( 'post_status' => 'pending' ), array( 'ID' => $post_id ) );
      } 

  }  
   
      
   public    function vtmam_pop_in_select( ) {
       global $post, $vtmam_info, $vtmam_rule; $vtmam_rules_set;
       $checked = 'checked="checked"'; 
       $vtmamNonce = wp_create_nonce("vtmam-rule-nonce"); //nonce verified in vt-minandmax-purchase.php
       
       $disabled = 'disabled="disabled"' ;       
       ?>
         
        <style type="text/css">
           /*Free version*/
           #cartChoice,
           #cartChoice-label,
           #varChoice,
           #varChoice-label,
           #singleChoice,
           #singleChoice-label,
           #prodcat-in,
           #prodcat-in h3,
           .and-or,
           #rulecat-in,
           #rulecat-in h3,
           #andChoice-label, 
           #cartSelected,
           #cartSelected-label,
           #lifetimeSelected,
           #lifetimeSelected-label,        
           {color:#aaa;}  /*grey out unavailable choices*/
           #wpsc_product_category-adder,
           #vtmam_rule_category-adder {
            display:none;
           }
           #vtmam-pop-in-cntl {margin-bottom:15px;}
            /*v1.06 begin*/
           .pro-anchor {
              border: 1px solid #CCCCCC;
              clear: both;
              color: #000000;
              float: left;
              font-size: 14px;
              margin-bottom: 10px;
              margin-left: 2%;
              margin-top: 20px;
              padding: 5px 10px;
              text-decoration: none;
              width: auto;        
           }
           #inpopDescrip-more-help {color: #0074A2 !important;font-size: 15px;} 
           /*v1.06 end*/                     
        </style>
                   
        <input type="hidden" id="vtmam_nonce" name="vtmam_nonce" value="<?php echo $vtmamNonce; ?>" />
        
        <input type="hidden" id="fullMsg" name="fullMsg" value="<?php echo $vtmam_info['default_full_msg'];?>" />  <?php //v1.07  ?>
         
        <div class="column1" id="inpopDescrip">
            <h4> <?php _e('Choose how to look at the Candidate Population', 'vtmam') ?></h4>
            <p> <?php _e('Min and Max Amount rules will only look at the contents of the cart at checkout.
            Min and Max Amount rules define a candidate group within the cart. The Free version of the plugin
            applies only to logged-in user membership status.', 'vtmam') ?>           
            </p>
            <?php //v1.06 msg moved below ?>
        </div>

        
        <div class="column2" id="inpopChoice">       
          <h3><?php _e('Select Search Type', 'vtmam')?></h3>
          <div id="inpopRadio">
          <?php
           $sizeof_rule_inpop = is_array($vtmam_rule->inpop) ? sizeof($vtmam_rule->inpop) : 0; //v2.0.0
           for($i=0; $i < $sizeof_rule_inpop; $i++) {  //v2.0.0
           ?>                 
              
              <input id="<?php echo $vtmam_rule->inpop[$i]['id']; ?>" class="<?php echo $vtmam_rule->inpop[$i]['class']; ?>" type="<?php echo $vtmam_rule->inpop[$i]['type']; ?>" name="<?php echo $vtmam_rule->inpop[$i]['name']; ?>" value="<?php echo $vtmam_rule->inpop[$i]['value']; ?>" <?php if ( $vtmam_rule->inpop[$i]['user_input'] > ' ' ) { echo $checked; } else { echo $disabled; } ?> /><span id="<?php echo $vtmam_rule->inpop[$i]['id'] . '-label'; ?>"> <?php echo $vtmam_rule->inpop[$i]['label']; ?></span><br />

           <?php } ?>                 
          </div>

          <span class="" id="singleChoice-span">                                  
            <span id="inpop-singleProdID-label"><?php _e('&nbsp; Enter Product ID Number', 'vtmam')?></span><br />                    
             <input id="<?php echo $vtmam_rule->inpop_singleProdID['id']; ?>" class="<?php echo $vtmam_rule->inpop_singleProdID['class']; ?>" type="<?php echo $vtmam_rule->inpop_singleProdID['type']; ?>" name="<?php echo $vtmam_rule->inpop_singleProdID['name']; ?>" value="<?php echo $vtmam_rule->inpop_singleProdID['value']; ?>">
             <br /> 
            <?php if ($vtmam_rule->inpop_singleProdID['value'] > ' ' ) { ?>           
                <span id="inpop-singleProdID-name-label"><?php _e('&nbsp; Product Name', 'vtmam')?></span><br /> 
                <span id="inpop-singleProdID-name" ><?php echo $vtmam_rule->inpop_singleProdID_name; ?></span><br />
            <?php } ?>                                         
          </span>
          
        </div>
         
        <div class="column3 inpopExplanation" id="cartChoice-chosen">
            <h4><?php _e('Apply to all products in the cart', 'vtmam')?><span> - <?php _e('explained', 'vtmam')?></span></h4>
            <p><?php _e('No threshhold group is chosen, and the initial rule logic applies to all products
            to be found in the cart.', 'vtmam')?>              
            </p>
        </div>
        <div class="column3 inpopExplanation" id="groupChoice-chosen">
            <h4><?php _e('Use Selection Groups', 'vtmam')?><span> - <?php _e('explained', 'vtmam')?></span></h4> 
            <p><?php _e('Using selection groups, you can specify the initial focus of the rule, focusing on some products found in the cart.  
            A selection group can be considered a threshhold, which when reached the other
            aspects of the rule is applied.  For example, if you specify category Auto Parts, then
            if products in categories other than Auto Parts are in the cart, the rule would not apply to them.', 'vtmam')?>           
            </p>
          </div>
        <div class="column3 inpopExplanation" id="varChoice-chosen">
            <h4><?php _e('Single Product with Variations', 'vtmam')?><span> - <?php _e('explained', 'vtmam')?></span></h4>
            <p><?php _e('Apply rule to the variations for a single product found in the cart, whose ID is supplied in the "Product ID" box.  Enter the Product ID and hit the "Product and Variations" button (The product ID can be found in the URL
            of the product during a product edit session).  Select any/all of the variations belonging to the product.', 'vtmam')?>           
            </p>
        </div>  
        <div class="column3 inpopExplanation" id="singleChoice-chosen">
            <h4><?php _e('Single Product Only', 'vtmam')?><span> - <?php _e('explained', 'vtmam')?></span></h4>
            <p><?php _e('Only apply rule to a single product found in the cart, whose ID is supplied in the "Product ID" box.  The product ID can be found in the URL
            of the product during a product edit session.', 'vtmam')?>  
            <br /> <br /> 
            <?php _e('For example, in the product edit session url:', 'vtmam')?> 
            <br /><br />  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <?php _e('http://www.xxxx.com/wp-admin/post.php?post=872&action=edit', 'vtmam')?> 
            <br /><br />
            <?php _e('The product id is in the "post=872" portion of the address, and hence the number is 872. You would enter 
            that number in the box to the left labeled "Enter Product ID Number".', 'vtmam')?>
            <br /><br />
            <?php _e('NB: If **single** is chosen, the value of "All" is applied to the Rule Application Method, regardless of what is chosen below.', 'vtmam')?> 
           </p>
        </div>        
         
        <div id="inpop-varProdID-cntl">            
          <a id="inpop-varProdID-more" class="help-anchor" href="javascript:void(0);">Single Product with Variations - <span id="pop-in-more-help">More Info</span></a>
         <p id="inpop-varProdID-descrip" class="help-text"><?php _e('When "Single Product with Variations" is chosen, at least one variation must be selected.
         <br><br> NB - PLEASE NOTE: If the product variation structure is changed in any way, you MUST return to the matching rule and reselect your variation choices.
         <br><br>Multiple rules may be created to apply to individual variations, or groups of variations within a product.           
         <br><br>Please be sure to prevent any rule overlap when applied to a given
            product-variation combination.  An overlap example: if there is a category-level rule covering an entire product, and an individual rule applying to any of the product variations.
         <br><br>Rule overlap in variation rules is Not removed by the rule processing engine, it must be prevented here.'   , 'vtmam')?>             
         </p> 
          <div id="inpopVarBox">
              <h3>Single Product with Variations</h3>
              <div id="inpopVarProduct">
                <span id="inpop-varProdID-label"><?php _e('&nbsp; Enter Product ID Number', 'vtmam')?></span><br />                    
                 <input id="<?php echo $vtmam_rule->inpop_varProdID['id']; ?>" class="<?php echo $vtmam_rule->inpop_varProdID['class']; ?>" type="<?php echo $vtmam_rule->inpop_varProdID['type']; ?>" name="<?php echo $vtmam_rule->inpop_varProdID['name']; ?>" value="<?php echo $vtmam_rule->inpop_varProdID['value']; ?>">
                 <br />                            
              </div>
              <div id="inpopVarButton">
                 <?php
                    $product_ID = $vtmam_rule->inpop_varProdID['value']; 
                    $product_variation_IDs = vtmam_get_variations_list($product_ID);
                    /* ************************************************
                    **   Get Variations Button for Rule screen
                    *     ==>>> get the product id from $_REQUEST['varProdID'];  in the receiving ajax routine. 
                    ************************************************ */                     
                 ?>
                                                        
                 <div class="inpopVar-loading-animation">
										<img title="Loading" alt="Loading" src="<?php echo VTMAM_URL;?>/admin/images/indicator.gif" />
										<?php _e('Getting Variations ...', 'vtmam'); ?>
								 </div>
                 
                 
                 <a id="ajaxVariationIn" href="javascript:void(0);">
                    <?php if ($product_ID > ' ') {   ?>
                      <?php _e('Refresh Variations', 'vtmam')?>                      
                    <?php } else {   ?>
                      <?php _e('Get Variations', 'vtmam')?> 
                    <?php } ?>
                  </a>
                 
              </div>
          </div>
          <div id="variations-in">
          <?php              
           if ($product_variation_IDs) { //if product still has variations, expose them here
           ?>
              <h3><?php _e('Product Variations', 'vtmam')?></h3>                  
            <?php
              //********************************
              $this->vtmam_post_category_meta_box($post, array( 'args' => array( 'taxonomy' => 'variations', 'tax_class' => 'var-in', 'checked_list' => $vtmam_rule->var_in_checked, 'product_ID' => $product_ID, 'product_variation_IDs' => $product_variation_IDs )));
              // ********************************
            }                               
          ?>
           </div>  <?php //end variations-in ?>
        </div>  <?php //end inpopVarProdID ?>       

        
        <?php //v1.06 moved here, changed msg?>
        <a id="" class="pro-anchor" target="_blank"  href="<?php echo VTMAM_PURCHASE_PRO_VERSION_BY_PARENT ; ?>">( Greyed-out Options are available in the <span id="inpopDescrip-more-help">Pro Version</span> &nbsp;)</a>
          
          
       <div class="<?php //echo $groupPop_vis ?> " id="vtmam-pop-in-cntl">                                                  
         <a id="pop-in-more" class="help-anchor" href="javascript:void(0);">Selection Groups - <span id="pop-in-more-help">More Info</span></a>
         <p id="pop-in-descrip" class="help-text"><?php _e("Role/Membership is used within Wordpress to control access and capabilities, when a role is given to a user.  
         Wordpress assigns certain roles by default such as Subscriber for new users or Administrator for the site's owner. Roles can also be used to associate a user 
         with a pricing level.  Use a role management plugin like http://wordpress.org/extend/plugins/user-role-editor/ to establish custom roles, which you can give 
         to a user or class of users.  Then you can associate that role with a Min and Max Purchase Rule.  So when the user logs into your site, their Role interacts with the appropriate Rule.
         <br><br>
         In the Pro version, you may use an existing category to identify the group of products to which you wish to apply the rule.  
         If you'd rather, use a Min and Max Purchase Category to identify products - this avoids disturbing the store categories. Just add a Min and Max Purchase Category, go to the product screen,
         and add the product to the correct Min and Max purchase category.  (On your product add/update screen, the Mininimum purchase 
         category metabox is just below the default product category box.)  You can also apply the rule using User Membership or Roles  
         as a solo selection, or you can use any combination of all three.  
         <br><br>
         Please take note of the relationship choice 'and/or'
         when using roles.  The default is 'or', while choosing 'and' requires that both a role and a category be selected, before a rule
         can be published.", 'vtmam')?>
         </p> 
    
        <div id="prodcat-in">
          <h3><?php _e('Product Categories', 'vtmam')?></h3>
          
          <?php
          // ********************************
          $this->vtmam_post_category_meta_box($post, array( 'args' => array( 'taxonomy' => $vtmam_info['parent_plugin_taxonomy'], 'tax_class' => 'prodcat-in', 'checked_list' => $vtmam_rule->prodcat_in_checked)));
          // ********************************
          ?>
        
        </div>  <?php //end prodcat-in ?>
        <h4 class="and-or"><?php _e('Or', 'vtmam') //('And / Or', 'vtmam')?></h4>
        <div id="rulecat-in">
          <h3><?php _e('Min and Max Purchase Categories', 'vtmam')?></h3>
          
          <?php
          // ********************************
          $this->vtmam_post_category_meta_box($post, array( 'args' => array( 'taxonomy' => $vtmam_info['rulecat_taxonomy'], 'tax_class' => 'rulecat-in', 'checked_list' => $vtmam_rule->rulecat_in_checked )));
          // ********************************
          ?> 
                         
        </div>  <?php //end rulecat-in ?>
        
        
        <div id="and-or-role-div">
          <?php
           $checked = 'checked="checked"'; 
           $sizeof_role_and_or_in = is_array($vtmam_rule->role_and_or_in) ? sizeof($vtmam_rule->role_and_or_in) : 0; //v2.0.0
           //for($i=0; $i < sizeof($vtmam_rule->role_and_or_in); $i++) { 
           for($i=0; $i < $sizeof_role_and_or_in; $i++) {   //v2.0.0 
           ?>                               
              <input id="<?php echo $vtmam_rule->role_and_or_in[$i]['id']; ?>" class="<?php echo $vtmam_rule->role_and_or_in[$i]['class']; ?>" type="<?php echo $vtmam_rule->role_and_or_in[$i]['type']; ?>" name="<?php echo $vtmam_rule->role_and_or_in[$i]['name']; ?>" value="<?php echo $vtmam_rule->role_and_or_in[$i]['value']; ?>" <?php if ( $vtmam_rule->role_and_or_in[$i]['user_input'] > ' ' ) { echo $checked; } else { echo $disabled; }?>    /><span id="<?php echo $vtmam_rule->role_and_or_in[$i]['id'] . '-label'; ?>"> <?php echo $vtmam_rule->role_and_or_in[$i]['label']; ?></span><br /> 
           <?php } 
           //if neither 'and' nor 'or' selected, select 'or'
         /*  if ( (!$vtmam_rule->role_and_or_in[0]['user_input'] == 's') && (!$vtmam_rule->role_and_or_in[1]['user_input'] == 's') )   {
               $vtmam_rule->role_and_or_in[1]['user_input'] = 's';
           }   */
                      
           ?>                 
          </div>
        
        
        <div id="role-in">
          <h3><?php _e('Membership List by Role', 'vtmam')?></h3>
          
          <?php
          // ********************************
          $this->vtmam_post_category_meta_box($post, array( 'args' => array( 'taxonomy' => 'roles', 'tax_class' => 'role-in', 'checked_list' => $vtmam_rule->role_in_checked  )));
          // ********************************
          ?>
        </div>
        <div class="back-to-top">
            <a title="Back to Top" href="#wpbody"><?php _e('Back to Top', 'vtmam')?><span class="back-to-top-arrow">&nbsp;&uarr;</span></a>
        </div>
      </div> <?php //end vtmam-pop-in-cntl ?>

   <?php   
}
      
  
    public    function vtmam_pop_in_specifics( ) {                     
       global $post, $vtmam_info, $vtmam_rule; $vtmam_rules_set;
       $checked = 'checked="checked"';      
  ?>
        
       <div class="column1" id="specDescrip">
          <h4><?php _e('How is the Rule applied to the search results?', 'vtmam')?></h4>
          <p><?php _e("Once we've figured out the population we're working on (cart only or specified groups),
          how do we apply the rule?  Do we look at each product individually and apply the rule to
          each product we find?  Or do we look at the population as a group, and apply the rule to the
          group as a tabulated whole?  Or do we apply the rule to any we find, and limit the application 
          of the rule to a certain number of products?", 'vtmam')?>           
          </p>
       </div>
       <div class="column2" id="specChoice">
          <h3><?php _e('Select Rule Application Method', 'vtmam')?></h3>
          <div id="specRadio">
            <span id="Choice-input-span">
                <?php
               $sizeof_specChoice_in = is_array($vtmam_rule->specChoice_in) ? sizeof($vtmam_rule->specChoice_in) : 0; //v2.0.0
               for($i=0; $i < $sizeof_specChoice_in; $i++) {   //v2.0.0
               ?>                 

                  <input id="<?php echo $vtmam_rule->specChoice_in[$i]['id']; ?>" class="<?php echo $vtmam_rule->specChoice_in[$i]['class']; ?>" type="<?php echo $vtmam_rule->specChoice_in[$i]['type']; ?>" name="<?php echo $vtmam_rule->specChoice_in[$i]['name']; ?>" value="<?php echo $vtmam_rule->specChoice_in[$i]['value']; ?>" <?php if ( $vtmam_rule->specChoice_in[$i]['user_input'] > ' ' ) { echo $checked; } ?> /><?php echo $vtmam_rule->specChoice_in[$i]['label']; ?><br />

               <?php
                }
               ?>  
            </span>
            <span class="" id="anyChoice-span">
                <span><?php _e('*Any* applies to a *required*', 'vtmam')?></span><br />
                 <?php _e('Maximum of:', 'vtmam')?>                      
                 <input id="<?php echo $vtmam_rule->anyChoice_max['id']; ?>" class="<?php echo $vtmam_rule->anyChoice_max['class']; ?>" type="<?php echo $vtmam_rule->anyChoice_max['type']; ?>" name="<?php echo $vtmam_rule->anyChoice_max['name']; ?>" value="<?php echo $vtmam_rule->anyChoice_max['value']; ?>">
                 <?php _e('Products', 'vtmam')?>
            </span>           
          </div>                
       </div>                                                
       <div class="column3 specExplanation" id="allChoice-chosen">
          <h4><?php _e('Treat the Selected Group as a Single Entity', 'vtmam')?><span> - <?php _e('explained', 'vtmam')?></span></h4>
          <p><?php _e("Using *All* as your method, you choose to look at all the products from your cart search results.  That means we add
          all the quantities and/or price across all relevant products in the cart, to test against the rule's requirements.", 'vtmam')?>           
          </p>
       </div>
       <div class="column3 specExplanation" id="eachChoice-chosen">
          <h4><?php _e('Each in the Selected Group', 'vtmam')?><span> - <?php _e('explained', 'vtmam')?></span></h4>
          <p><?php _e("Using *Each* as your method, we apply the rule to each product from your cart search results.
          So if any of these products fail to meet the rule's requirements, the cart as a whole receives an error message.", 'vtmam')?>           
          </p>
       </div>
       <div class="column3 specExplanation" id="anyChoice-chosen">
          <h4><?php _e('Apply the rule to any Individual Product in the Cart', 'vtmam')?><span> - <?php _e('explained', 'vtmam')?></span></h4>
          <p><?php _e("Using *Any*, we can apply the rule to any product in the cart from your cart search results, similar to *Each*.  However, there is a
          maximum number of products to which the rule is applied. The product group is checked to see if any of the group fail to reach the min and max amount
          threshhold.  If so, the error will be applied to products in the cart based on cart order, up to the maximum limit supplied.", 'vtmam')?>
          <br /> <br /> 
          <?php _e('For example, the rule might be something like:', 'vtmam')?>
          <br /> <br />
          <?php _e('&nbsp;&nbsp;"You may buy a min or max of $10 for each of any of 2 products from this group."', 'vtmam')?>              
          </p>               
       </div> 
       <div class="back-to-top">
            <a title="Back to Top" href="#wpbody"><?php _e('Back to Top', 'vtmam')?><span class="back-to-top-arrow">&nbsp;&uarr;</span></a>
       </div>
      <?php
  }  
      
                                                                            
    public    function vtmam_rule_amount( ) {
        global $post, $vtmam_info, $vtmam_rule, $vtmam_rules_set;
        $checked = 'checked="checked"';           
          ?>
        <div class="column1" id="amtDescrip">
            <h4><?php _e('What are the Rule Amount Options?', 'vtmam')?></h4>
          <p><?php _e('Min and Max Purchase Rules can be applied to the quantity or the price of the products from 
          your cart search results.', 'vtmam')?>        
          </p>
      </div>
      <div class="column2" id="amtChoice">
          <h3><?php _e('Select Rule Amount Option', 'vtmam')?></h3>
          <div id="amtRadio">
            <span id="amt-selected">
             <?php
             $sizeof_amtSelected = is_array($vtmam_rule->amtSelected) ? sizeof($vtmam_rule->amtSelected) : 0; //v2.0.0
             for($i=0; $i < $sizeof_amtSelected; $i++) {    //v2.0.0
             //for($i=0; $i < sizeof($vtmam_rule->amtSelected); $i++) {  //v2.0.0 
             ?>                 

                <input id="<?php echo $vtmam_rule->amtSelected[$i]['id']; ?>" class="<?php echo $vtmam_rule->amtSelected[$i]['class']; ?>" type="<?php echo $vtmam_rule->amtSelected[$i]['type']; ?>" name="<?php echo $vtmam_rule->amtSelected[$i]['name']; ?>" value="<?php echo $vtmam_rule->amtSelected[$i]['value']; ?>" <?php if ( $vtmam_rule->amtSelected[$i]['user_input'] > ' ' ) { echo $checked; } ?> /><?php echo $vtmam_rule->amtSelected[$i]['label']; ?><br />

             <?php
              }
             ?>
            </span>
            <span id="amtChoice-span">
                 <?php _e('Min or Max Amount:', 'vtmam')?>
                 <input id="<?php echo $vtmam_rule->minandmax_amt['id']; ?>" class="<?php echo $vtmam_rule->minandmax_amt['class']; ?>" type="<?php echo $vtmam_rule->minandmax_amt['type']; ?>" name="<?php echo $vtmam_rule->minandmax_amt['name']; ?>" value="<?php echo $vtmam_rule->minandmax_amt['value']; ?>">
            </span>
          </div>                
       </div>
      <div class="column3 amtExplanation" id="qtyChoice-chosen">
          <h4><?php _e('Apply to Quantity', 'vtmam')?><span> - <?php _e('explained', 'vtmam')?></span></h4>
          <p><?php _e('With Quantity chosen, we total up the units amount across indivual products, candidate groups in the cart, or the cart
          in total.  Then we compare that total against the minandmax amount for the rule.', 'vtmam')?>        
          </p>
       </div>
       <div class="column3 amtExplanation" id="amtChoice-chosen">
          <h4><?php _e('Apply to Price', 'vtmam')?><span> - <?php _e('explained', 'vtmam')?></span></h4>
          <p><?php _e('With Price chosen, we total up the price across indivual products, candidate groups in the cart, or the cart
          in total.  Then we compare that total against the minandmax amount for the rule.', 'vtmam')?>            
          </p>
       </div>
       <div class="back-to-top">
            <a title="Back to Top" href="#wpbody"><?php _e('Back to Top', 'vtmam')?><span class="back-to-top-arrow">&nbsp;&uarr;</span></a>
       </div>
      <?php
  }       
  
    
                                                                             
    public    function vtmam_rule_minandmax( ) {
        global $post, $vtmam_info, $vtmam_rule, $vtmam_rules_set;
        $checked = 'checked="checked"';           
          ?>
        <div class="column1" id="minandmaxDescrip">                                               
            <h4><?php _e('Minimum or Maximum Evaluation Type', 'vtmam')?></h4>
          <p><?php _e('Rule Evaluation Type can be applied either in minimum or maximum mode.', 'vtmam')?>        
          </p>
      </div>
      <div class="column2" id="minandmaxChoice">
          <h3><?php _e('Rule Evaluation Type', 'vtmam')?></h3>
          <div id="minandmaxRadio">
            <span id="minandmax-selected">
             <?php
             $sizeof_minandmaxSelected = is_array($vtmam_rule->minandmaxSelected) ? sizeof($vtmam_rule->minandmaxSelected) : 0; //v2.0.0
             for($i=0; $i < $sizeof_minandmaxSelected; $i++) {   //v2.0.0
             //for($i=0; $i < sizeof($vtmam_rule->minandmaxSelected); $i++) {   //v2.0.0 
             ?>                 

                <input id="<?php echo $vtmam_rule->minandmaxSelected[$i]['id']; ?>" class="<?php echo $vtmam_rule->minandmaxSelected[$i]['class']; ?>" type="<?php echo $vtmam_rule->minandmaxSelected[$i]['type']; ?>" name="<?php echo $vtmam_rule->minandmaxSelected[$i]['name']; ?>" value="<?php echo $vtmam_rule->minandmaxSelected[$i]['value']; ?>" <?php if ( $vtmam_rule->minandmaxSelected[$i]['user_input'] > ' ' ) { echo $checked; } ?> /><?php echo $vtmam_rule->minandmaxSelected[$i]['label']; ?><br />

             <?php
              }
             ?>
            </span>
          </div>                
       </div>
      <div class="column3 minandmaxExplanation" id="minChoice-chosen">
          <h4><?php _e('Minimum Purchase Rule Evaluation Type', 'vtmam')?><span> - <?php _e('explained', 'vtmam')?></span></h4>
          <p><?php _e('With Minimum Purchase chosen, the amount/price amount must meet or exceed the min/max amount.', 'vtmam')?>        
          </p>
       </div>
       <div class="column3 minandmaxExplanation" id="maxChoice-chosen">
          <h4><?php _e('Maximum Purchase Rule Evaluation Type', 'vtmam')?><span> - <?php _e('explained', 'vtmam')?></span></h4>
          <p><?php _e('With Maximum Purchase chosen, the amount/price amount must not exceed the min/max amount.', 'vtmam')?>            
          </p>
       </div>

      <?php
  } 
                                                                               
    public    function vtmam_max_rule_type( ) {
        global $post, $vtmam_info, $vtmam_rule, $vtmam_rules_set;
        $checked = 'checked="checked"';                
        $disabled = 'disabled="disabled"' ; 
                   
          ?>
        <div class="column1" id="maxRule-typeDescrip">
            <h4><?php _e('Min/Max Rule Type', 'vtmam')?></h4>       <?php //v2.0.0  ?>
          <p><?php _e('Maximum Purchase rules can set a limit for the current cart only, or set a customer purchase lifetime limit.', 'vtmam')?>        
          </p>
      </div>
      <div class="column2" id="maxRule-typeChoice">
          <h3><?php _e('Select Min/Max Rule Type', 'vtmam')?></h3>         <?php //v2.0.0  ?> 
          <div id="maxRule-typeRadio">
            <span id="maxRule-typeSelected">
             <?php
             $sizeof_maxRule_typeSelected = is_array($vtmam_rule->maxRule_typeSelected) ? sizeof($vtmam_rule->maxRule_typeSelected) : 0; //v2.0.0
             for($i=0; $i < $sizeof_maxRule_typeSelected; $i++) {  //v2.0.0
             //for($i=0; $i < sizeof($vtmam_rule->maxRule_typeSelected); $i++) {  //v2.0.0 
             ?>                                                                                                                                                                                                                                                                                                                                          

                <input id="<?php echo $vtmam_rule->maxRule_typeSelected[$i]['id']; ?>" class="<?php echo $vtmam_rule->maxRule_typeSelected[$i]['class']; ?>" type="<?php echo $vtmam_rule->maxRule_typeSelected[$i]['type']; ?>" name="<?php echo $vtmam_rule->maxRule_typeSelected[$i]['name']; ?>" value="<?php echo $vtmam_rule->maxRule_typeSelected[$i]['value']; ?>" <?php if ( $vtmam_rule->maxRule_typeSelected[$i]['user_input'] > ' ' ) { echo $checked;  } else { echo $disabled; } ?> /><?php echo $vtmam_rule->maxRule_typeSelected[$i]['label']; ?><br />

             <?php
              }
             ?>
            </span>     
          </div>                
       </div>
      <div class="column3 maxRule-typeExplanation" id="cartTypeChoice-chosen">
          <h4><?php _e('Cart Only ', 'vtmam')?><span> - <?php _e('explained', 'vtmam')?></span></h4>
          <p><?php _e('The rule applies only to the current cart contents, no history actions taken.', 'vtmam')?>        
          </p>
       </div>
       <div class="column3 maxRule-typeExplanation" id="lifetimeTypeChoice-chosen">
          <h4><?php _e('Lifetime Purchases ', 'vtmam')?><span> - <?php _e('explained', 'vtmam')?></span></h4>
          <p><?php _e('This selection applies the  rule to customer Lifetime cumulative purchase limits.  For each Rule/Purchaser/rule-affected cart contents, 
          cumulative information is stored on the database.', 'vtmam')?>            
          </p>
       </div>

       <div  id="maxrule-type-help-div">
       <a id="maxrule-type-help-more" class="help-anchor" href="javascript:void(0);">Important Documentation for Lifetime Purchases - <span id="maxrule-type-help">More Info</span></a>
         <p id="maxrule-type-help-descrip" class="help-text"><?php _e('
          Lifetime limit rules track all relevant customer purchases, and check if total lifetime sales for this customer exceed the rule limit.
          <br><br>Lifetime purchase rules create customer Lifetime cumulative purchase limits. For each Rule/Purchaser/rule-affected cart contents, cumulative information is stored on the database
          , and this historical information is used as part of the lifetime limit computations. If a customer has previously purchased products covered by this rule, that information is 
          used to see if the cumulative total of historical purchases and current purchases exceeds the rule limit.
          <br><br>For Lifetime limit functionality, 
          ', 'vtmam')?>
          <a  href=" <?php echo VTMAM_PURCHASE_PRO_VERSION_BY_PARENT ; ?> "  title="Access Plugin Documentation"> Upgrade to Min and Max Purchase Pro</a>  
          <?php _e('
          <br><br>NB => Do not create a cart limit rule and a lifetime limit rule for the same product population.  Do just the lifetime rule, otherwise a duplicate error msg will result.
          <br><br>ALSO, be very aware of TWO danger points in the system.  First, once history has been stored for rule lifetime purchases, you must be extremely careful in changing the
          rule criteria, for any rule changes could invalidate history already stored.  Second, the system works off of the default formset for checkout purchaser name information.  
          If you choose a custom formset, naming MUST follow the default checkout formset precisely, AND the new formset number must be entered in the options screen at the appropriate option.
          ', 'vtmam')?>            
         </p>
      </div>
        
      <?php
  } 
   
   //v1.07.9 NEW FUNCTION 
   //repeating groups by count or $ value                                                                        
    public    function vtmam_rule_repeating_groups() {
        global $post, $vtmam_info, $vtmam_rule, $vtmam_rules_set;                   
          ?>
        <div class="rule_message clear-left" id="repeating-groups-area">
           <span class="newColumn1" id=repeating-groups-label-area>
              <h3><?php _e('Repeating Groups Quantity', 'vtmam')?></h3>
              <span id='repeating-groups-optional'>(optional)</span>
              <span class="clear-left" id='repeating-groups-comment'>(rule purchases must be in groups of X count)</span>
           </span>   
            <textarea name="repeating-groups" type="text" class="msg-text" id="repeating-groups"><?php echo $vtmam_rule->repeatingGroups; ?></textarea> 
        </div>

      <?php
  }  
  //v1.07.9 end
     
   //V1.07 New
   //Custom Message overriding default messaging                                                                        
    public    function vtmam_rule_custom_message() {
        global $post, $vtmam_info, $vtmam_rule, $vtmam_rules_set;                   
         
          ?>
        <div class="rule_message clear-left" id="cust-msg-text-area">
           <span class="newColumn1" id=cust-msg-text-label-area>
              <h3><?php _e('Custom Message Text', 'vtmam')?></h3>
              <span id='cust-msg-optional'>(optional)</span>
              <span class="clear-left" id='cust-msg-comment'>(overrides default message)</span>
           </span>   
            <textarea name="cust-msg-text" type="text" class="msg-text newColumn2" id="cust-msg-text" cols="50" rows="2"><?php if ($vtmam_rule->custMsg_text) {echo stripslashes($vtmam_rule->custMsg_text); }  //v2.0.0a ?></textarea>          
       </div>

      <?php
  }  
  //v1.07 end
              
                                                                            
    public    function vtmam_rule_id( ) {
        global $post;           
        echo '<span id="vtmam-rule-postid">' . $post->ID . '</span>';
  } 
  
    public    function vtmam_rule_resources ( ) {          
        echo '<span id="vtmam-rr-text">Read documentation, learn the functions and find some tips & tricks.</span><br>';
        echo '<a id="vtmam-rr-doc"  href="' . VTMAM_DOCUMENTATION_PATH_PRO_BY_PARENT . '"  title="Access Plugin Documentation">Plugin Documentation</a>';
        echo '<span id="vtmam-rr-box">';
        echo '<span id="vtmam-rr-created">by VarkTech.com</span>';
        echo '<a id="vtmam-rr-vote"  href="' . VTMAM_DOWNLOAD_FREE_VERSION_BY_PARENT . '"  title="Vote for the Plugin">Vote</a>';
        echo '</span>'; //end rr-box
  }   
      
/*
source: http://www.ilovecolors.com.ar/avoid-hierarchical-taxonomies-to-loose-hierarchy/ 
==> pasted from wp-admin/includes/meta-boxes.php -> post_categories_meta_box()
**  plugin with same code in http://scribu.net/wordpress/category-checklist-tree 
*/
       
  public  function vtmam_post_category_meta_box( $post, $box ) {
      $defaults = array('taxonomy' => 'category');
      if ( !isset($box['args']) || !is_array($box['args']) )
          $args = array();
      else
          $args = $box['args'];
      extract( wp_parse_args($args, $defaults), EXTR_SKIP );
      $tax = get_taxonomy($taxonomy);
   
   //vark => removed the divs with the tabs for 'all' and 'most popular'
      ?>
      <div id="taxonomy-<?php echo $taxonomy; ?>" class="categorydiv">
   
          <div id="<?php echo $taxonomy; ?>-pop" class="tabs-panel" style="display: none;">
              <ul id="<?php echo $taxonomy; ?>checklist-pop" class="categorychecklist form-no-clear" >
                    <?php $popular_ids = vtmam_popular_terms_checklist($taxonomy); //wp_popular_terms_checklist($taxonomy); replaced, //v2.0.0 ?>
              </ul>
          </div>
   
          <div id="<?php echo $taxonomy; ?>-all" class="tabs-panel">
              <?php
              $name = ( $taxonomy == 'category' ) ? 'post_category' : 'tax_input[' .  $tax_class . ']';     //vark replaced $taxonomy with $tax_class
              echo "<input type='hidden' name='{$name}[]' value='0' />"; // Allows for an empty term set to be sent. 0 is an invalid Term ID and will be ignored by empty() checks.
              ?>
              <ul id="<?php echo $taxonomy; ?>checklist" class="list:<?php echo $taxonomy?> categorychecklist form-no-clear">
      <?php    

            switch( $taxonomy ) {
              case 'roles': 
                  $vtmam_checkbox_classes = new VTMAM_Checkbox_classes; 
                  $vtmam_checkbox_classes->vtmam_fill_roles_checklist($tax_class, $checked_list);
                break;
              case 'variations':                  
                  vtmam_fill_variations_checklist($tax_class, $product_ID, $product_variation_IDs, $checked_list);  //v2.0.0 null possible $checked_list must be last                           
                break;
              default:  //product category or vtmam category...
                  $this->vtmam_build_checkbox_contents ($taxonomy, $tax_class, $checked_list);                             
                break;
            }
            
      ?>  
              </ul>
          </div>
          
      <?php //wp-hidden-children div removed, no longer functions as/of WP3.5 ?>
      
      </div>
      <?php
}

    //remove conflict with all-in-one seo pack!!  
    //  from http://wordpress.stackexchange.com/questions/55088/disable-all-in-one-seo-pack-for-some-custom-post-types
    function vtmam_remove_all_in_one_seo_aiosp() {
        $cpts = array( 'vtmam-rule' );
        foreach( $cpts as $cpt ) {
            remove_meta_box( 'aiosp', $cpt, 'advanced' );
        }
    }


    
  /*
    *  taxonomy (r) - registered name of taxonomy
    *  tax_class (r) - name options => 'prodcat-in' 'prodcat-out' 'rulecat-in' 'rulecat-out'
    *             refers to product taxonomy on the candidate or action categories,
    *                       rulecat taxonomy on the candidate or action categories
    *                         :: as there are only these 4, they are unique   
    *  checked_list (o) - selection list from previous iteration of rule selection                              
    *                          
   */

  public function vtmam_build_checkbox_contents ($taxonomy, $tax_class, $checked_list = NULL) {
        global $wpdb, $vtmam_info;         
        $sql = "SELECT terms.`term_id`, terms.`name`  FROM `" . $wpdb->prefix . "terms` as terms, `" . $wpdb->prefix . "term_taxonomy` as term_taxonomy WHERE terms.`term_id` = term_taxonomy.`term_id` AND term_taxonomy.`taxonomy` = '" . $taxonomy . "' ORDER BY terms.`term_id` ASC";                         
		    $categories = $wpdb->get_results($sql,ARRAY_A) ;

        foreach ($categories as $category) {
            $output  = '<li id='.$taxonomy.'-'.$category['term_id'].'>' ;
            $output  .= '<label class="selectit">' ;
            $output  .= '<input id="'.$tax_class.'_'.$taxonomy.'-'.$category['term_id'].' " ';
            $output  .= 'type="checkbox" name="tax-input-' .  $tax_class . '[]" ';
            $output  .= 'value="'.$category['term_id'].'" ';
            if ($checked_list) {
                if (in_array($category['term_id'], $checked_list)) {   //if cat_id is in previously checked_list  
                   $output  .= 'checked="checked"';
                }
            }
            if ( ($taxonomy == $vtmam_info['parent_plugin_taxonomy']) || ($taxonomy == $vtmam_info['rulecat_taxonomy']) )           {       
                  $output  .= ' disabled="disabled"';
            }
            $output  .= '>'; //end input statement
            $output  .= '&nbsp;' . $category['name'];
            $output  .= '</label>';            
            $output  .= '</li>';
              echo $output ;
         }
         return;
    }



      
} //end class
