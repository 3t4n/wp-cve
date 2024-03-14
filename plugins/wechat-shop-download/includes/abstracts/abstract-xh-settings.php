<?php
if (! defined ( 'ABSPATH' ))
	exit (); // Exit if accessed directly
	
/**
 * Abstract Settings API Class
 *
 * Admin Settings API used by login.
 *
 * @since 1.0.0
 * @author ranj
 */
abstract class Abstract_WShop_Settings {
	/**
	 * The plugin ID.
	 * Used for option names.
	 * 
	 * @var string
	 */
	public $plugin_id = 'wshop_';
	
	/**
	 * Method ID.
	 * 
	 * @var string
	 */
	public $id = '';

	/**
	 * Method title.
	 * 
	 * @var string
	 */
	public $title = '';
	
	/**
	 * menu title
	 * @var string
	 */
	public $menu_title='';
	
	/**
	 * Method description.
	 * 
	 * @var string
	 */
	public $description = '';
	
	/**
	 * 'yes' if the method is enabled.
	 * 
	 * @var boolean
	 */
	public $enabled;
	
	/**
	 * Setting values.
	 * 
	 * @var array
	 */
	public $settings = array ();
	
	/**
	 * Form option fields.
	 * 
	 * @var array
	 */
	public $form_fields = array ();
	
	/**
	 * Validation errors.
	 * 
	 * @var array
	 */
	public $errors = array ();
	
	/**
	 * Sanitized fields after validation.
	 * 
	 * @var array
	 */
	public $sanitized_fields = array ();
	
	public function admin_form_start(){
    	if( isset($_POST['xunhuweb-form-'.$this->id])){
	        if(current_user_can('manage_options')&& wp_verify_nonce( $_POST['xunhuweb-form-'.$this->id], plugin_basename( __FILE__ ) ) ){
	           $this->process_admin_options(); 
	        }else{
	            $this->errors[]=WShop_Error::err_code(701)->errmsg;
	            $this->display_errors();
	        }
	    }
	    ?><form method="post" id="mainform" action="" enctype="multipart/form-data"><?php
	    wp_nonce_field( plugin_basename( __FILE__ ), 'xunhuweb-form-'.$this->id);
	}
	
	/**
	 * Admin Options.
	 *
	 * Setup the gateway settings screen.
	 * Override this in your gateway.
	 *
	 * @since 1.0.0
	 */
	public function admin_options() {	
	    $title = $this->title;
	    if(!empty($this->menu_title)){
	        $title = $this->menu_title;
	    }
    	      ?>
    	    <style type="text/css">
                .form-table tr{display:block;}
            </style>
            <h3><?php echo ( ! empty( $title ) ) ? $title : __( 'Settings') ; ?></h3>
            <?php echo ( ! empty( $this->description ) ) ? wpautop( $this->description ) : ''; ?>
            
            <?php do_action('wshop_admin_options_header_'.$this->id)?>
            
            <input type="hidden" name="action" value="<?php print esc_attr($this->id)?>"/>
            <table class="form-table">
    			<?php $this->generate_settings_html(); ?>
    		</table>
    		<?php
	}

	public function post_options() {
	   $title = isset($this->menu_title)&&!empty($this->menu_title)?$this->menu_title:$this->title;
	      ?>
	    <style type="text/css">
            .form-table tr{display:block;}
        </style>
        <h3><?php echo  ! empty( $title)  ?$title: __( 'Settings') ; ?></h3>
        <?php echo ( ! empty( $this->description ) ) ? wpautop( $this->description ) : ''; ?>
        
        <?php do_action('xh_social_admin_options_header_'.$this->id)?>
        
        <input type="hidden" name="action" value="<?php print esc_attr($this->id)?>"/>
        <table class="form-table">
			<?php $this->generate_settings_html(); ?>
		</table>
		<?php
	}
	
	public function admin_form_end(){
	   ?><p class="submit">
			<input type="submit" value="<?php print __('Save Changes')?>" class="button-primary" />
		 </p>
		</form><?php
	}
	
	/**
	 * Initialise Settings Form Fields.
	 *
	 * Add an array of fields to be displayed
	 * on the gateway's settings screen.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function init_form_fields() {
	    $this->form_fields=array();
	}
	
	/**
	 * Get the form fields after they are initialized.
	 * 
	 * @since 1.0.0
	 * @return array of options
	 */
	public function get_form_fields() {
		return apply_filters ( 'wshop_settings_api_form_fields_' . $this->id, $this->form_fields );
	}
	/**
	 * 获取页面下拉选项
	 * @return array
	 * @since 1.0.0
	 */
	public function get_page_options(){
	    global $wpdb;
	    $pages =$wpdb->get_results(
	        "select ID,post_title
	        from {$wpdb->posts}
	        where post_type='page'
	              and post_status='publish';");
	    $options = array(
	        '0'=>__('Select...',WSHOP)
	    );
	    if($pages){
	        foreach ($pages as $page){
	            $options[$page->ID]=$page->post_title;
	        }
	    }
	
	    return $options;
	}
	
	
	/**
	 * Admin Panel Options Processing.
	 * - Saves the options to the DB.
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function process_admin_options() {
		$this->validate_settings_fields ();
		do_action('wshop_process_admin_options_validate_'.$this->id);
		if (count ( $this->errors ) > 0) {
			$this->display_errors ();
			return false;
		} else {
		    wp_cache_delete($this->plugin_id . $this->id . '_settings', 'options');
			update_option ( $this->plugin_id . $this->id . '_settings', $this->sanitized_fields,true);
			$this->init_settings ();
			$this->display_success();
			return true;
		}
	}
	
	/**
	 * update_option function
	 * 更新单个配置项
	 * @param string $key
	 * @param mixed $val
	 * @since 1.0.0
	 */
	public function update_option($key,$val){
	    $options = get_option($this->plugin_id . $this->id . '_settings',array());
	    if(!$options||!is_array($options)){
	        $options =array();
	    }
	    
	    $options[$key]=$val;
	    
	    wp_cache_delete($this->plugin_id . $this->id . '_settings', 'options');
	    update_option ( $this->plugin_id . $this->id . '_settings', $options ,true);
	    $this->init_settings ();
	    return true;
	}
	
	/**
	 * update_option function
	 * 更新单个配置项
	 * @param string $key
	 * @param mixed $val
	 * @since 1.0.5
	 */
	public function update_option_array(array $settings){
	    $options = get_option($this->plugin_id . $this->id . '_settings',array());
	    if(!$options||!is_array($options)){
	        $options =array();
	    }
	     
	    foreach ($settings as $key=>$val){
	        $options[$key]=$val;
	    }
	   
	    wp_cache_delete($this->plugin_id . $this->id . '_settings', 'options');
	    update_option ( $this->plugin_id . $this->id . '_settings', $options ,true);
	    $this->init_settings ();
	    return true;
	}

	/**
	 * get_option function.
	 *
	 * Gets and option from the settings API, using defaults if necessary to prevent undefined notices.
	 *
	 * @param string $key
	 * @param mixed $empty_value
	 * @return mixed The value specified for the option or a default value for the option.
	 */
	public function get_option($key, $empty_value = null) {
	    if (empty ( $this->settings )) {
	        $this->init_settings ();
	    }
	
	    // Get option default if unset.
	    if (! isset ( $this->settings [$key] )) {
	        $form_fields = $this->get_form_fields ();
	        $this->settings [$key] = isset ( $form_fields [$key] ['default'] ) ? $form_fields [$key] ['default'] : '';
	    }
	
	    if (! is_null ( $empty_value ) && empty ( $this->settings [$key] ) && '' === $this->settings [$key]) {
	        $this->settings [$key] = $empty_value;
	    }
	
	    return $this->settings [$key];
	}
	
	/**
	 * Display admin success messages.
	 *
	 * @since 1.0.0
	 */
	public function display_success(){
	    ?>
	    <div id="message" class="success notice notice-success is-dismissible">
   		<p><?php echo __('Data saved successfully!',WSHOP);?></p>
   		<button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php print __('Ignore')?></span></button>
   		</div>
	    <?php 
	}
		
	/**
	 * Display admin error messages.
	 *
	 * @since 1.0.0
	 */
	public function display_errors() {
	    if(count( $this->errors)==0){
	        return;
	    }
		?>
		<div id="message" class="error notice notice-error is-dismissible">
		<p>
		<?php 
		 foreach ($this->errors as $error){
		     echo $error;
		 }
		?>
		</p><button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php print __('Ignore')?></span></button></div>
		<?php 
	}
	/**
	 *
	 * @param array $pairs
	 * @param array $atts
	 * @param string $shortcode
	 * @return array[]|unknown[]
	 * @since 1.0.7
	 */
	function include_atts( $pairs, $atts, $shortcode = '' ) {
	    $atts = (array)$atts;
	    $out = array();
	    foreach ($pairs as $name => $default) {
	        if ( array_key_exists($name, $atts) ){
	            $out[$name] = $atts[$name];
	        }else{
	            $out[$name] = $default;
	        }
	    }
	
	    return $out;
	}
	

	public function get_role_options(){
	    $editable_roles = array_reverse( get_editable_roles() );
	   
	    $results = array();
	    foreach ( $editable_roles as $role => $details ) {
	        if($role=='all'||$role=='none'){continue;}
	        $name = translate_user_role($details['name'] );
	        $results[$role] =$name;
	    }
	
	    return $results;
	}
	
	public function get_post_type_options(){
	    global $wp_post_types;
	    $types = array();
	
	    if(!$wp_post_types){
	        return $types;
	    }
	
	    foreach ($wp_post_types as $key=>$type){
	        if(in_array($key, array('attachment'))){continue;}
	
	        if($type->show_ui&&$type->public&&!(isset($type->wshop_ignore)&&$type->wshop_ignore)){
	            $types[$key]=isset($type->label)&& !empty($type->label)?"{$type->label}({$key})":$key;
	        }
	    }
	    return apply_filters('wshop_post_types', $types);
	}
	
	/**
	 * Initialise Gateway Settings.
	 *
	 * Store all settings in a single database entry
	 * and make sure the $settings array is either the default
	 * or the settings stored in the database.
	 *
	 * @since 1.0.0
	 * @uses get_option(), add_option()
	 */
	public function init_settings() {
		
		// Load form_field settings.
		$this->settings = get_option ( $this->plugin_id . $this->id . '_settings', null );
		
		if (! $this->settings || ! is_array ( $this->settings )) {
			
			$this->settings = array ();
			
			// If there are no settings defined, load defaults.
			$form_fields = $this->get_form_fields ();
			if ($form_fields) {
				
				foreach ( $form_fields as $k => $v ) {
					$this->settings [$k] = isset ( $v ['default'] ) ? $v ['default'] : '';
				}
			}
		}
		
		if (! empty ( $this->settings ) && is_array ( $this->settings )) {
			$this->settings = array_map ( array (
					$this,
					'format_settings' 
			), $this->settings );
			$this->enabled = isset ( $this->settings ['enabled'] ) && $this->settings ['enabled'] == 'yes' ? 'yes' : 'no';
		}
	}
	
	
	/**
	 * Prefix key for settings.
	 *
	 * @param mixed $key        	
	 * @return string
	 */
	public function get_field_key($key) {
		return $this->plugin_id . $this->id . '_' . $key;
	}
	
	/**
	 * Decode values for settings.
	 *
	 * @param mixed $value        	
	 * @return array
	 */
	public function format_settings($value) {
		return is_array ( $value ) ? $value : $value;
	}
	
	/**
	 * Generate Settings HTML.
	 *
	 * Generate the HTML for the fields on the "settings" screen.
	 *
	 * @param array $form_fields
	 *        	(default: array())
	 * @since 1.0.0
	 * @uses method_exists()
	 * @return string the html for the settings
	 */
	public function generate_settings_html($form_fields = false) {
		if ($form_fields===false) {
			$form_fields = $this->get_form_fields ();
		}
		
		$html = '';
		foreach ( $form_fields as $k => $v ) {
			
			if (! isset ( $v ['type'] ) || ($v ['type'] == '')) {
				$v ['type'] = 'text'; // Default to "text" field type.
			}
			
			if (method_exists ( $this, 'generate_' . $v ['type'] . '_html' )) {
				$html .= $this->{'generate_' . $v ['type'] . '_html'} ( $k, $v );
			} else {
				$html .= $this->{'generate_text_html'} ( $k, $v );
			}
		}
		
		echo $html;
	}
	
	/**
	 * htmlspecialchars
	 * 
	 * @since 1.0.0
	 * @param string $var
	 * @return string
	 */
	function wc_sanitize_tooltip($var) {
		return htmlspecialchars ( wp_kses ( html_entity_decode ( $var ), array (
				'br' => array (),
				'em' => array (),
				'strong' => array (),
				'small' => array (),
				'span' => array (),
				'ul' => array (),
				'li' => array (),
				'ol' => array (),
				'p' => array () 
		) ) );
	}
	
	/**
	 * Generate help tips
	 * 
	 * @since 1.0.0
	 * @param string $tip
	 * @param bool $allow_html
	 * @return string
	 */
	function wc_help_tip($tip, $allow_html = false) {
		if ($allow_html) {
			// $tip = $this->wc_sanitize_tooltip( $tip );
		} else {
			$tip = esc_attr ( $tip );
		}
		
		return '<span class="help-tip" data-tip="' . $tip . '"></span>';
	}
	
	/**
	 * Get HTML for tooltips.
	 *
	 * @since 1.0.0
	 * @param array $data        	
	 * @return string
	 */
	public function get_tooltip_html($data) {
	    if (isset($data ['desc_tip'])&&$data ['desc_tip'] === true) {
			$tip = $data ['description'];
		} elseif (! empty ( $data ['desc_tip'] )) {
			$tip = $data ['desc_tip'];
		} else {
			$tip = '';
		}
		
		return $tip ? $this->wc_help_tip ( $tip, true ) : '';
	}
	
	/**
	 * Get HTML for descriptions.
	 *
	 * @since 1.0.0
	 * @param array $data        	
	 * @return string
	 */
	public function get_description_html($data) {
		if ($data ['desc_tip'] === true) {
			$description = '';
		} elseif (! empty ( $data ['desc_tip'] )) {
			$description = $data ['description'];
		} elseif (! empty ( $data ['description'] )) {
			$description = $data ['description'];
		} else {
			$description = '';
		}
		
		return $description ? '<p class="description">' . $description . '</p>' . "\n" : '';
	}
	
	/**
	 * Get custom attributes.
	 *
	 *@since 1.0.0
	 * @param array $data        	
	 * @return string
	 */
	public function get_custom_attribute_html($data) {
		$custom_attributes = array ();
		
		if (! empty ( $data ['custom_attributes'] ) && is_array ( $data ['custom_attributes'] )) {
			
			foreach ( $data ['custom_attributes'] as $attribute => $attribute_value ) {
				$custom_attributes [] = esc_attr ( $attribute ) . '="' . esc_attr ( $attribute_value ) . '"';
			}
		}
		
		return implode ( ' ', $custom_attributes );
	}
	
	/**
	 * Generate Select HTML.
	 *
	 * @param mixed $key
	 * @param mixed $data
	 * @since 1.0.0
	 * @return string
	 */
	public function generate_section_html($key, $data) {
	
	    $field = $this->get_field_key ( $key );
	    $defaults = array (
	        'title' => '',
	        'disabled' => false,
	        'class' => '',
	        'css' => 'min-width:400px;',
	        'placeholder' => '',
	        'type' => 'text',
	        'desc_tip' => false,
	        'description' => '',
	        'custom_attributes' => array (),
	        'scripts'=>'',
	        'options' => array ()
	    );
	
	    if(isset($data['func'])&&$data['func']){
	        $data['options'] = call_user_func($data['options']);
	    }
	
	    $data = wp_parse_args ( $data, $defaults );
	
	    ob_start ();
    	    ?>
            <tr valign="top" class="<?php echo isset($data['tr_css'])?$data['tr_css']:''; ?>" data-type="section" data-key="<?php echo esc_attr($key)?>">
            	<th scope="row" class="titledesc"><label
            		for="<?php echo esc_attr( $field ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></label>
            					<?php echo $this->get_tooltip_html( $data ); ?>
            				</th>
            	<td class="forminp">
            		<fieldset>
            			<legend class="screen-reader-text">
            				<span><?php echo wp_kses_post( $data['title'] ); ?></span>
            			</legend>
            			<select class="select <?php echo esc_attr( $data['class'] ); ?>" name="<?php echo esc_attr( $field ); ?>" id="<?php echo esc_attr( $field ); ?>" style="<?php echo esc_attr( $data['css'] ); ?>" <?php disabled( $data['disabled'], true ); ?> <?php echo $this->get_custom_attribute_html( $data ); ?>>
    					<?php foreach ( (array) $data['options'] as $option_key => $option_value ) : ?>
    						<option value="<?php echo esc_attr( $option_key ); ?>"
    			           <?php selected( $option_key, esc_attr( $this->get_option( $key ) ) ); ?>><?php echo esc_attr( $option_value ); ?></option>
    					<?php endforeach; ?>
    				</select>
    				<?php echo $this->get_description_html( $data ); ?>
    			</fieldset>
    				<script type="text/javascript">
    				(function($){
    					window.on_<?php echo esc_attr( $key ); ?>_change=function(){
    						<?php foreach ( $data['options'] as $k=>$val){
    						    ?>
    						    $('.section-<?php echo esc_attr($k)?>.section-<?php echo esc_attr($key)?>').css('display','none');
    						    <?php 
    						}?>
    						
    						$('.section-<?php echo esc_attr($key)?>.section-'+$('#<?php echo esc_attr( $field ); ?>').val()).css('display','block');
    					}
    					
    					$(function(){
    						window.on_<?php echo esc_attr($key ); ?>_change();
    						if(window.after_onload){window.after_onload();}
    					});
    					
    					$('#<?php echo esc_attr( $field ); ?>').change(function(){
    						window.on_<?php echo esc_attr(  $key ); ?>_change();
    					});
    					
    				})(jQuery);
    			</script>
            	</td>
            </tr>
            <?php
		
		return ob_get_clean ();
	}
	
	public function generate_tabs_html($key, $data) {
	    $field = $this->get_field_key ( $key );
	    $defaults = array (
	        'title' => '',
	        'class' => '',
	        'css' => '',
	        'desc_tip' => false,
	        'custom_attributes' => array (),
	        'options' => array ()
	    );
	
	    if(isset($data['func'])&&$data['func']){
	        $data['options'] = call_user_func($data['options']);
	    }
	
	    $data = wp_parse_args ( $data, $defaults );
	
	    ob_start ();
	    ?>
    	    <tr class="<?php echo isset($data['tr_css'])?$data['tr_css']:''; ?>">
        	    <td colspan="2" style="display:block;margin:0;padding:0;">
            	    <hr/>
                   <h4 class="nav-tab-wrapper woo-nav-tab-wrapper">
                   <?php 
                    foreach ($data['options'] as $k=>$val){
                        ?><a href="javascript:void(0);" class="nav-tab nav-tab-<?php echo esc_attr($field); ?>" data-key="<?php echo esc_attr($k)?>" style="font-size:8px;line-height:18px;"><?php echo $val;?></a><?php 
                    }
                   ?>
                   </h4>
                   
                   <script type="text/javascript">
                		(function($){
                    		var $tabs =$('.nav-tab-<?php echo esc_attr($field); ?>');
                    		if( $tabs.length>0){
                       		 	$tabs.click(function(){
                    				<?php foreach ( $data['options'] as $k=>$val){
                    				    ?>
                    				    $('.tab-<?php echo esc_attr($key); ?>.tab-<?php echo esc_attr($k)?>').css('display','none');
                    				    <?php 
                    				}?>
                    				
                    				$('.nav-tab-<?php echo esc_attr($field); ?>.nav-tab-active').removeClass('nav-tab-active');
                    				$(this).addClass('nav-tab-active');
    								var sections = [];
                    				$('.tab-<?php echo esc_attr($key); ?>.tab-'+$(this).attr('data-key')).each(function(){
    									var data_type = $(this).attr('data-type');
    									if(data_type=='section'){
    										sections.push('window.on_'+$(this).attr('data-key')+'_change()');
    									}
    
    									$(this).css('display','block');
                        			});
    
    								for(var i=0;i<sections.length;i++){
    									eval(sections[i]);
    								}
                        		});
    
                       	        window.after_onload=function(){
                       	        	$tabs.first().click();
                           	    };
                          		$(function(){
                          			$tabs.first().click();
                              	});
                    		}
                		})(jQuery);
                	</script>
        	    </td>
    	    </tr>
           
            <?php
		
		return ob_get_clean ();
	}
		
	/**
	 * Generate Text Input HTML.
	 *
	 * @param mixed $key        	
	 * @param mixed $data        	
	 * @since 1.0.0
	 * @return string
	 */
	public function generate_text_html($key, $data) {
		$field = $this->get_field_key ( $key );
		$defaults = array (
				'title' => '',
				'disabled' => false,
				'class' => '',
				'css' => 'min-width:400px;',
				'placeholder' => '',
				'type' => 'text',
				'desc_tip' => false,
				'description' => '',
				'custom_attributes' => array () 
		);
		
		$data = wp_parse_args ( $data, $defaults );
		
		ob_start ();
		?>
        <tr valign="top" class="<?php echo isset($data['tr_css'])?$data['tr_css']:''; ?>">
        	<th scope="row" class="titledesc">
        		<label for="<?php echo esc_attr( $field ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></label>
				<?php echo $this->get_tooltip_html( $data ); ?>
			</th>
        	<td class="forminp">
        		<fieldset>
        			<legend class="screen-reader-text">
        				<span><?php echo wp_kses_post( $data['title'] ); ?></span>
        			</legend>
        			<input class="input-text regular-input <?php echo esc_attr( $data['class'] ); ?>" type="<?php echo esc_attr( $data['type'] ); ?>" name="<?php echo esc_attr( $field ); ?>" id="<?php echo esc_attr( $field ); ?>" style="<?php echo esc_attr( $data['css'] ); ?>" value="<?php echo $data['type']!='password'? esc_attr( $this->get_option( $key ) ):null; ?>" placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>" <?php disabled( $data['disabled'], true ); ?> <?php echo $this->get_custom_attribute_html( $data ); ?> />
					<?php echo $this->get_description_html( $data ); ?>
				</fieldset>
        	</td>
        </tr>
        <?php
		
		return ob_get_clean ();
	}
	 
	/**
	 * Generate Text Input HTML.
	 *
	 * @param mixed $key
	 * @param mixed $data
	 * @since 1.0.0
	 * @return string
	 */
	public function generate_hidden_html($key, $data) {
	    $field = $this->get_field_key ( $key );
	    $defaults = array (
	        'title' => '',
	        'disabled' => false,
	        'class' => '',
	        'css' => 'min-width:400px;',
	        'placeholder' => '',
	        'type' => 'hidden',
	        'desc_tip' => false,
	        'description' => '',
	        'custom_attributes' => array ()
	    );
	
	    $data = wp_parse_args ( $data, $defaults );
	
	    ob_start ();
	        ?>
	        <tr style="display:none;">
	        	<td>
	        		<input class="input-text regular-input <?php echo esc_attr( $data['class'] ); ?>" type="<?php echo esc_attr( $data['type'] ); ?>" name="<?php echo esc_attr( $field ); ?>" id="<?php echo esc_attr( $field ); ?>" style="<?php echo esc_attr( $data['css'] ); ?>" value="<?php echo $data['type']!='password'? esc_attr( $this->get_option( $key ) ):null; ?>" placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>" <?php disabled( $data['disabled'], true ); ?> <?php echo $this->get_custom_attribute_html( $data ); ?> />
	        	</td>
	        </tr>
	        <?php
		
		return ob_get_clean ();
	}
	
	/**
	 * Generate Price Input HTML.
	 *
	 * @param mixed $key        	
	 * @param mixed $data        	
	 * @since 1.0.0
	 * @return string
	 */
	public function generate_price_html($key, $data) {
		$field = $this->get_field_key ( $key );
		$defaults = array (
				'title' => '',
				'disabled' => false,
				'class' => '',
				'css' => '',
				'placeholder' => '',
				'type' => 'text',
				'desc_tip' => false,
				'description' => '',
				'custom_attributes' => array () 
		);
		
		$data = wp_parse_args ( $data, $defaults );
		
		ob_start ();
		?>
            <tr valign="top" class="<?php echo isset($data['tr_css'])?$data['tr_css']:''; ?>">
            	<th scope="row" class="titledesc">
            		<label for="<?php echo esc_attr( $field ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></label>
					<?php echo $this->get_tooltip_html( $data ); ?>
				</th>
            	<td class="forminp">
            		<fieldset>
            			<legend class="screen-reader-text">
            				<span><?php echo wp_kses_post( $data['title'] ); ?></span>
            			</legend>
            			<input class="wc_input_price input-text regular-input <?php echo esc_attr( $data['class'] ); ?>" type="text" name="<?php echo esc_attr( $field ); ?>" id="<?php echo esc_attr( $field ); ?>" style="<?php echo esc_attr( $data['css'] ); ?>" value="<?php echo esc_attr( ( $this->get_option( $key ) ) ); ?>" placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>" <?php disabled( $data['disabled'], true ); ?> <?php echo $this->get_custom_attribute_html( $data ); ?> />
						<?php echo $this->get_description_html( $data ); ?>
					</fieldset>
            	</td>
            </tr>
            <?php
		
		return ob_get_clean ();
	}
	
	
	/**
	 * Generate Decimal Input HTML.
	 *
	 * @param mixed $key        	
	 * @param mixed $data        	
	 * @since 1.0.0
	 * @return string
	 */
	public function generate_decimal_html($key, $data) {
		$field = $this->get_field_key ( $key );
		$defaults = array (
				'title' => '',
				'disabled' => false,
				'class' => '',
				'css' => '',
				'placeholder' => '',
				'type' => 'text',
				'desc_tip' => false,
				'description' => '',
				'custom_attributes' => array () 
		);
		
		$data = wp_parse_args ( $data, $defaults );
		
		ob_start ();
		?>
        <tr valign="top" class="<?php echo isset($data['tr_css'])?$data['tr_css']:''; ?>">
        	<th scope="row" class="titledesc">
        		<label for="<?php echo esc_attr( $field ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></label>
        		<?php echo $this->get_tooltip_html( $data ); ?>
        	</th>
        	<td class="forminp">
        		<fieldset>
        			<legend class="screen-reader-text">
        				<span><?php echo wp_kses_post( $data['title'] ); ?></span>
        			</legend>
        			<input class="wc_input_decimal input-text regular-input <?php echo esc_attr( $data['class'] ); ?>" type="text" name="<?php echo esc_attr( $field ); ?>" id="<?php echo esc_attr( $field ); ?>" style="<?php echo esc_attr( $data['css'] ); ?>" value="<?php echo esc_attr( ( $this->get_option( $key ) ) ); ?>" placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>" <?php disabled( $data['disabled'], true ); ?> <?php echo $this->get_custom_attribute_html( $data ); ?> />
        			<?php echo $this->get_description_html( $data ); ?>
        		</fieldset>
        	</td>
        </tr>
        <?php
		return ob_get_clean ();
	}
	
	public function generate_datetime_html($key, $data) {
	    $field = $this->get_field_key ( $key );
	    $defaults = array (
	        'title' => '',
	        'disabled' => false,
	        'class' => '',
	        'css' => '',
	        'format'=>'Y-m-d H:i',
	        'data_format'=>'date',//date|time
	        'format_js'=>'yyyy-MM-dd HH:mm',
	        'placeholder' => '',
	        'type' => 'text',
	        'default'=>null,
	        'desc_tip' => false,
	        'description' => '',
	        'custom_attributes' => array ()
	    );
	
	    $data = wp_parse_args ( $data, $defaults );
	
		$val = $this->get_option( $key,$data['default'] );
		if($val){
			$val =date($data['format'],is_numeric($val)?$val:strtotime($val) );
		}
		
	    ob_start ();
	    ?>
	        <tr valign="top" class="<?php echo isset($data['tr_css'])?$data['tr_css']:''; ?>">
	        	<th scope="row" class="titledesc">
	        		<label for="<?php echo esc_attr( $field ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></label>
	        		<?php echo $this->get_tooltip_html( $data ); ?>
	        	</th>
	        	<td class="forminp">
	        		<fieldset>
	        			<legend class="screen-reader-text">
	        				<span><?php echo wp_kses_post( $data['title'] ); ?></span>
	        			</legend>
	        			<input class="wc_input_decimal input-text regular-input <?php echo esc_attr( $data['class'] ); ?>" type="text" name="<?php echo esc_attr( $field ); ?>" id="<?php echo esc_attr( $field ); ?>" style="<?php echo esc_attr( $data['css'] ); ?>" value="<?php echo esc_attr( $val ); ?>" placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>" <?php disabled( $data['disabled'], true ); ?> <?php echo $this->get_custom_attribute_html( $data ); ?> />
	        			<script type="text/javascript">
								jQuery(function($){
									$("#<?php echo esc_attr( $field ); ?>").focus(function() {
                              			WdatePicker({
                              				dateFmt: '<?php echo $data['format_js']?>'
                              			});
                              		});
								});
	        			</script>
	        			<?php echo $this->get_description_html( $data ); ?>
	        		</fieldset>
	        	</td>
	        </tr>
	        <?php
			
		return ob_get_clean ();
	}
	
	public function generate_image_html($key, $data) {
	    $field = $this->get_field_key ( $key );
	    $defaults = array (
	        'title' => '',
	        'disabled' => false,
	        'class' => '',
	        'css' => '',
	        'placeholder' => '',
	        'type' => 'text',
	        'desc_tip' => false,
	        'description' => '',
	        'custom_attributes' => array (),
	        'validate'=>function($key,$api){
    	       $field = $api->get_field_key($key);
    	       $data = isset($_POST[$field])?stripslashes($_POST[$field]):null;
	            return $data;
	        }
	    );
	
	    $data = wp_parse_args ( $data, $defaults );
	
	    ob_start ();
	    ?>
	        <tr valign="top" class="<?php echo isset($data['tr_css'])?$data['tr_css']:''; ?>">
	        	<th scope="row" class="titledesc">
	        		<label for="<?php echo esc_attr( $field ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></label>
	        		<?php echo $this->get_tooltip_html( $data ); ?>
	        	</th>
	        	<td class="forminp">
	        		<fieldset>
	        			<legend class="screen-reader-text">
	        				<span><?php echo wp_kses_post( $data['title'] ); ?></span>
	        			</legend>
	        			
						<img id="<?php echo esc_attr( $field ); ?>-img" style="max-width:100px;max-height:100px;" src="<?php echo esc_attr(  $this->get_option( $key ) ); ?>">
						<input type="hidden" name="<?php echo esc_attr( $field ); ?>" id="<?php echo esc_attr( $field ); ?>" value="<?php echo esc_attr(  $this->get_option( $key ) ); ?>" />
						<input type="button" class="button" id="btn-<?php echo esc_attr( $field ); ?>-upload-img" value="<?php echo __('Upload Image',WSHOP)?>" />
						<a href="javascript:void(0);" style="margin-left:5px;" id="btn-<?php echo esc_attr( $field ); ?>-remove"><?php echo __('Remove',WSHOP)?></a>
						<script type="text/javascript">
						(function($){
							$('#btn-<?php echo esc_attr( $field ); ?>-upload-img').click(function() {  
								var send_attachment_bkp = wp.media.editor.send.attachment;
							    wp.media.editor.send.attachment = function(props, attachment) {
								    
							        $('#<?php echo esc_attr( $field ); ?>').val(attachment.url);
							        $('#<?php echo esc_attr( $field ); ?>-img').attr('src',attachment.url);
							        wp.media.editor.send.attachment = send_attachment_bkp;
							    }
							    wp.media.editor.open();
							    return false;    
						    });   
						    $('#btn-<?php echo esc_attr( $field ); ?>-remove').click(function(){
								if(confirm('<?php echo __('Are you sure?',WSHOP)?>')){
									$('#<?php echo esc_attr( $field ); ?>').val('');
									$('#<?php echo esc_attr( $field ); ?>-img').attr('src','');
								}
							});
						})(jQuery);
						</script>
	        			
	        			<?php echo $this->get_description_html( $data ); ?>
	        		</fieldset>
	        	</td>
	        </tr>
	        <?php
		return ob_get_clean ();
	}
	
	/*public function generate_img_html($key, $data){
	    $data = wp_parse_args ( $data, $defaults );
	    
	    $singles = explode('x', $data['size']);
	    $img_size=array(
	        'width'=>0,
	        'height'=>0
	    );
	    switch (count($singles)){
	        case 1:
	            $img_size=array(
	            'width'=>absint($singles[0]),
	            'height'=>absint($singles[0]),
	            );
	            break;
	        case 2:
	            $img_size=array(
	            'width'=>absint($singles[0]),
	            'height'=>absint($singles[1]),
	            );
	            break;
	    }
	    
	    $default_val=array();
	    if(!empty($default)){
	        $default_val = WShop::instance()->generate_request_params(array(
	            'url'=>$data['default']
	        ));
	    }
	     
	    if(!defined('xh_webuploader')){
	        define('xh_webuploader', 'xh_webuploader');
	        ?>
	        <style type="text/css">
	        .webuploader-container {
	        	position: relative;
	        }
	        .webuploader-element-invisible {
	        	position: absolute !important;
	        	clip: rect(1px 1px 1px 1px); 
	            clip: rect(1px,1px,1px,1px);
	        }
	        
	        .webuploader-pick {
	        	position: relative;
	        	display: inline-block;
	        	cursor: pointer;
	        	background: #337ab7;
	        	padding: 4px 12px;
	        	color: #fff;
	        	text-align: center;
	        	border-radius: 3px;
	        	overflow: hidden;
	        	margin-top:5px;
	        	
	        }
	        .webuploader-pick-hover {
	        	background: #00a2d4;
	        }
	        
	        .webuploader-pick-disable {
	        	opacity: 0.6;
	        	pointer-events:none;
	        }
	        </style>
	    	<script type="text/javascript" src="<?php echo WSHOP_URL?>/assets/webuploader-0.1.7-alpha/webuploader.js"></script>
	        <?php 
	    }
	    
	    $input_id = $field;
	    $input_name = $field;
	    $required = $data['required'];
	    $description = $data['description'];
	    $label = $data['title'];
	    $default = $data['default'];
	    ?>
		<div class="xh-form-group" id="form-group-<?php echo $input_id?>">
            <label class="<?php echo $required?"required":""?>"><?php echo $label;?></label>
            <div>
            	<img class="thumbnail" id="image-<?php print $input_id;?>" src="<?php echo esc_url($default)?>" style="max-width:80px;max-height:80px;"/>
            	<div id="image-<?php print $input_id;?>-picker"><?php echo __('Upload image',WSHOP)?></div>
            	 
                <span class="help-block"><?php echo $description?></span>
                <input type="hidden" value="<?php echo esc_attr(json_encode($default_val))?>" name="<?php echo $input_name;?>" id="<?php echo $input_id;?>"/>
            </div>
        </div>
        <script type="text/javascript">
        	(function($){
        		if ( !WebUploader.Uploader.support() ) {
        	       return;
        	    }
        
        		var <?php print $input_id;?>_config ={
        			swf:'<?php echo WSHOP_URL.'/assets/webuploader-0.1.5/Uploader.swf'?>',
        			server:'<?php echo WSHOP::instance()->ajax_url(array('action'=>"xh_uc_upload_img",'w'=>$img_size['width'],'h'=>$img_size['height']),true,true)?>',
        			dnd:'#form-group-<?php echo $input_id?>',
        			paste:document.body,
        			pick:'#image-<?php print $input_id;?>-picker',
        			accept:{
        		        title: 'Images',
        		       // extensions: 'gif,jpg,jpeg,bmp,png',
        		       // mimeTypes: 'image/gif,image/jpg,image/jpeg,image/bmp,image/png'
        		    },
        		    auto :true,
        		    fileNumLimit:1			    
        		};
        
        		     //do something
        		var <?php print $input_id;?>_uploader = WebUploader.create(<?php print $input_id;?>_config);
        		
        		// 文件上传失败，显示上传出错。
        		<?php print $input_id;?>_uploader.on( 'uploadStart', function( file ) {
        			$('#image-<?php print $input_id;?>-picker .webuploader-pick').text('<?php echo __('Uploading...',WSHOP)?>');
        		});
        		// 文件上传失败，显示上传出错。
        		<?php print $input_id;?>_uploader.on( 'uploadError', function( file ,reason) {
        			<?php print $input_id;?>_uploader.reset();
        			alert('<?php echo WSHOP_Error::err_code(500)->errmsg?>');
        		});
        
        		// 完成上传完了，成功或者失败，先删除进度条。
        		<?php print $input_id;?>_uploader.on( 'uploadComplete', function( file ) {
        			$('#image-<?php print $input_id;?>-picker .webuploader-pick').text('<?php echo __('Upload image',WSHOP)?>');
        		});
        		
        		<?php print $input_id;?>_uploader.on( 'uploadSuccess', function( file ,response) {
        			<?php print $input_id;?>_uploader.reset();
        			
        			if(!response||typeof response.errcode=='undefined'){
        				alert('<?php echo WSHOP_Error::error_unknow()->errmsg?>');
        				return;
        			}
        			
        			if(response.errcode!=0){
        				alert(response.errmsg);
        				return;
        			}
        
        			$('#<?php echo $input_id;?>').val(JSON.stringify(response.data));
        			$('#image-<?php print $input_id;?>').attr('src',response.data.url);
        			
        		});
        		
        	})(jQuery);
        </script>
        <?php
	}*/
	
	public function generate_attachment_html($key, $data) {
	    $field = $this->get_field_key ( $key );
	    $defaults = array (
	        'title' => '',
	        'disabled' => false,
	        'class' => '',
	        'css' => '',
	        'placeholder' => '',
	        'type' => 'text',
	        'desc_tip' => false,
	        'description' => '',
	        'custom_attributes' => array ()
	    );
	
	    $data = wp_parse_args ( $data, $defaults );
	
	    ob_start ();
	        ?>
	        <tr valign="top" class="<?php echo isset($data['tr_css'])?$data['tr_css']:''; ?>">
	        	<th scope="row" class="titledesc">
	        		<label for="<?php echo esc_attr( $field ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></label>
	        		<?php echo $this->get_tooltip_html( $data ); ?>
	        	</th>
	        	<td class="forminp">
	        		<fieldset>
	        			<legend class="screen-reader-text">
	        				<span><?php echo wp_kses_post( $data['title'] ); ?></span>
	        			</legend>
	        			<?php $img =wp_get_attachment_image_src($this->get_option( $key ) );?>
						<img id="<?php echo esc_attr( $field ); ?>-img" style="max-width:100px;max-height:100px;" src="<?php echo esc_attr( $img&&count($img)>0?$img[0]:""); ?>">
						<input type="hidden" name="<?php echo esc_attr( $field ); ?>" id="<?php echo esc_attr( $field ); ?>" value="<?php echo esc_attr(  $this->get_option( $key ) ); ?>" />
						<input type="button" class="button" id="btn-<?php echo esc_attr( $field ); ?>-upload-img" value="<?php echo __('Upload Image',WSHOP)?>" />
						<a href="javascript:void(0);" style="margin-left:5px;" id="btn-<?php echo esc_attr( $field ); ?>-remove"><?php echo __('Remove',WSHOP)?></a>
						<script type="text/javascript">
						(function($){
							$('#btn-<?php echo esc_attr( $field ); ?>-upload-img').click(function() {  
								var send_attachment_bkp = wp.media.editor.send.attachment;
							    wp.media.editor.send.attachment = function(props, attachment) {
								    
							        $('#<?php echo esc_attr( $field ); ?>').val(attachment.id);
							        $('#<?php echo esc_attr( $field ); ?>-img').attr('src',attachment.url);
							        wp.media.editor.send.attachment = send_attachment_bkp;
							    }
							    wp.media.editor.open();
							    return false;    
						    });   
						    $('#btn-<?php echo esc_attr( $field ); ?>-remove').click(function(){
								if(confirm('<?php echo __('Are you sure?',WSHOP)?>')){
									$('#<?php echo esc_attr( $field ); ?>').val('');
									$('#<?php echo esc_attr( $field ); ?>-img').attr('src','');
								}
							});
						})(jQuery);
						</script>
	        			
	        			<?php echo $this->get_description_html( $data ); ?>
	        		</fieldset>
	        	</td>
	        </tr>
	        <?php
			
		return ob_get_clean ();
	}
	
	/**
	 * Generate Password Input HTML.
	 *
	 * @param mixed $key        	
	 * @param mixed $data        	
	 * @since 1.0.0
	 * @return string
	 */
	public function generate_password_html($key, $data) {
		$data ['type'] = 'password';
		return $this->generate_text_html ( $key, $data );
	}
	
	/**
	 * Generate Color Picker Input HTML.
	 *
	 * @param mixed $key        	
	 * @param mixed $data        	
	 * @since 1.0.0
	 * @return string
	 */
	public function generate_color_html($key, $data) {
		$field = $this->get_field_key ( $key );
		$defaults = array (
				'title' => '',
				'disabled' => false,
				'class' => '',
				'css' => '',
				'placeholder' => '',
				'desc_tip' => false,
				'description' => '',
				'custom_attributes' => array () 
		);
		
		$data = wp_parse_args ( $data, $defaults );
		
		ob_start ();
		?>
        <tr valign="top" class="<?php echo isset($data['tr_css'])?$data['tr_css']:''; ?>">
        	<th scope="row" class="titledesc">
        		<label for="<?php echo esc_attr( $field ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></label>
				<?php echo $this->get_tooltip_html( $data ); ?>
			</th>
        	<td class="forminp">
        		<fieldset>
        			<legend class="screen-reader-text">
        				<span><?php echo wp_kses_post( $data['title'] ); ?></span>
        			</legend>
        			<span class="colorpickpreview" style="background:<?php echo esc_attr( $this->get_option( $key ) ); ?>;"></span>
        			<input class="colorpick <?php echo esc_attr( $data['class'] ); ?>" type="text" name="<?php echo esc_attr( $field ); ?>" id="<?php echo esc_attr( $field ); ?>" style="<?php echo esc_attr( $data['css'] ); ?>" value="<?php echo esc_attr( $this->get_option( $key ) ); ?>" placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>" <?php disabled( $data['disabled'], true ); ?> <?php echo $this->get_custom_attribute_html( $data ); ?> />
        			<div id="colorPickerDiv_<?php echo esc_attr( $field ); ?>" class="colorpickdiv" style="z-index: 100; background: #eee; border: 1px solid #ccc; position: absolute; display: none;"></div>
					<?php echo $this->get_description_html( $data ); ?>
				</fieldset>
        	</td>
        </tr>
        <?php
		
		return ob_get_clean ();
	}
	
	/**
	 * Generate Textarea HTML.
	 *
	 * @param mixed $key        	
	 * @param mixed $data        	
	 * @since 1.0.0
	 * @return string
	 */
	public function generate_textarea_html($key, $data) {
		$field = $this->get_field_key ( $key );
		$defaults = array (
				'title' => '',
				'disabled' => false,
				'class' => '',
				'css' => 'min-width:400px;',
				'placeholder' => '',
				'type' => 'text',
				'desc_tip' => false,
				'description' => '',
				'custom_attributes' => array () 
		);
		
		$data = wp_parse_args ( $data, $defaults );
		
		ob_start ();
		?>
            <tr valign="top" class="<?php echo isset($data['tr_css'])?$data['tr_css']:''; ?>">
            	<th scope="row" class="titledesc">
            	<label for="<?php echo esc_attr( $field ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></label>
					<?php echo $this->get_tooltip_html( $data ); ?>
				</th>
            	<td class="forminp">
            		<fieldset>
            			<legend class="screen-reader-text">
            				<span><?php echo wp_kses_post( $data['title'] ); ?></span>
            			</legend>
            			<textarea rows="3" cols="20" class="input-text wide-input <?php echo esc_attr( $data['class'] ); ?>"  name="<?php echo esc_attr( $field ); ?>" id="<?php echo esc_attr( $field ); ?>" style="<?php echo esc_attr( $data['css'] ); ?>" placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>" <?php disabled( $data['disabled'], true ); ?> <?php echo $this->get_custom_attribute_html( $data ); ?>><?php echo esc_textarea( $this->get_option( $key ) ); ?></textarea>
						<?php echo $this->get_description_html( $data ); ?>
					</fieldset>
            	</td>
            </tr>
            <?php
		
		return ob_get_clean ();
	}
	
	/**
	 * Generate Checkbox HTML.
	 *
	 * @param mixed $key        	
	 * @param mixed $data        	
	 * @since 1.0.0
	 * @return string
	 */
	public function generate_checkbox_html($key, $data) {
		$field = $this->get_field_key ( $key );
		$defaults = array (
				'title' => '',
				'label' => '',
				'disabled' => false,
				'class' => '',
				'css' => '',
				'type' => 'text',
				'desc_tip' => false,
				'description' => '',
				'custom_attributes' => array () 
		);
		
		$data = wp_parse_args ( $data, $defaults );
		
		ob_start ();
		?>
        <tr valign="top" class="<?php echo isset($data['tr_css'])?$data['tr_css']:''; ?>">
        	<th scope="row" class="titledesc"><label
        		for="<?php echo esc_attr( $field ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></label>
        					<?php echo $this->get_tooltip_html( $data ); ?>
        				</th>
        	<td class="forminp">
        		<fieldset>
        			<legend class="screen-reader-text">
        				<span><?php echo wp_kses_post( $data['title'] ); ?></span>
        			</legend>
        			<label for="<?php echo esc_attr( $field ); ?>"> <input <?php disabled( $data['disabled'], true ); ?> class="<?php echo esc_attr( $data['class'] ); ?>" type="checkbox" name="<?php echo esc_attr( $field ); ?>" id="<?php echo esc_attr( $field ); ?>" style="<?php echo esc_attr( $data['css'] ); ?>" value="1" <?php checked( $this->get_option( $key ), 'yes' ); ?> <?php echo $this->get_custom_attribute_html( $data ); ?> /> <?php echo wp_kses_post( $data['label'] ); ?></label><br />
        						<?php echo $this->get_description_html( $data ); ?>
        					</fieldset>
        	</td>
        </tr>
        <?php
		
		return ob_get_clean ();
	}
	
	/**
	 * Generate Select HTML.
	 *
	 * @param mixed $key        	
	 * @param mixed $data        	
	 * @since 1.0.0
	 * @return string
	 */
	public function generate_select_html($key, $data) {

		$field = $this->get_field_key ( $key );
		$defaults = array (
				'title' => '',
				'disabled' => false,
				'class' => '',
				'css' => 'min-width:400px;',
				'placeholder' => '',
				'type' => 'text',
				'desc_tip' => false,
		        'default'=>null,
				'description' => '',
				'custom_attributes' => array (),
				'options' => array () 
		);
		
		$data = wp_parse_args ( $data, $defaults );
		
		if(!isset($data['post_type'])||!$data['post_type']){
		
    		if(isset($data['func'])&&$data['func']){
    		    $data['options'] = call_user_func($data['options']);
    		}
    		
    		ob_start ();
    		?>
            <tr valign="top" class="<?php echo isset($data['tr_css'])?$data['tr_css']:''; ?>">
            	<th scope="row" class="titledesc"><label for="<?php echo esc_attr( $field ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></label>
					<?php echo $this->get_tooltip_html( $data ); ?>
				</th>
            	<td class="forminp">
            		<fieldset>
            			<legend class="screen-reader-text">
            				<span><?php echo wp_kses_post( $data['title'] ); ?></span>
            			</legend>
            			<select class="select <?php echo esc_attr( $data['class'] ); ?>" name="<?php echo esc_attr( $field ); ?>" id="<?php echo esc_attr( $field ); ?>" style="<?php echo esc_attr( $data['css'] ); ?>" <?php disabled( $data['disabled'], true ); ?> <?php echo $this->get_custom_attribute_html( $data ); ?>>
							<?php foreach ( (array) $data['options'] as $option_key => $option_value ) : ?>
								<option value="<?php echo esc_attr( $option_key ); ?>"
					           <?php selected( $option_key, esc_attr( $this->get_option( $key ) ) ); ?>><?php echo esc_attr( $option_value ); ?></option>
							<?php endforeach; ?>
						</select>
						<?php echo $this->get_description_html( $data ); ?>
					</fieldset>
            	</td>
            </tr>
            <?php
    		return ob_get_clean ();
		}else{
		    if(!is_string($data['post_type'])&&is_callable($data['post_type'])){
		        $data['post_type'] = call_user_func($data['post_type']);
		    }
		    
		    ob_start ();
		    $multiple = isset($data['multiple'])&&$data['multiple']?1:0;
		    $field_key = $field;
		    if($multiple){
		        $field .="[]";
		    }
		    ?>
                <tr valign="top" class="<?php echo isset($data['tr_css'])?$data['tr_css']:''; ?>">
                	<th scope="row" class="titledesc"><label for="<?php echo esc_attr( $field ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></label>
    					<?php echo $this->get_tooltip_html( $data ); ?>
    				</th>
                	<td class="forminp">
                		<fieldset>
                			<legend class="screen-reader-text">
                				<span><?php echo wp_kses_post( $data['title'] ); ?></span>
                			</legend>
                		    <?php 
                		    $custom_params = isset($data['custom_params'])?$data['custom_params']:null;
                		    $custom_params_method = isset($data['custom_params_method'])?$data['custom_params_method']:null;
                		    if(!$custom_params&&$custom_params_method){
                		        $custom_params=call_user_func($custom_params_method);
                		    }
                		    ?>
                			<select class="wshop-search" data-multiple="<?php echo $multiple?>" data-custom_params="<?php echo $custom_params?esc_attr(json_encode($custom_params)):null; ?>" data-type='<?php echo $data['post_type'];?>' name="<?php echo esc_attr( $field ); ?>" id="<?php echo esc_attr( $field_key ); ?>" data-sortable="true" data-placeholder="<?php echo __( 'Search by ID/title...', WSHOP); ?>" data-allow_clear="true">
                    			<?php 
                    			$vals=array();
                    			if(isset($data['get_values'])){
                    			    $posts = call_user_func($data['get_values'], $data);
                    			    if($posts){
                    			        foreach ($posts as $p){
                    			            if(in_array($data['post_type'], array('customer','wp_user'))){
                    			                $vals[]=$p->ID;
                    			                ?>
                                			    <option value="<?php echo $p->ID;?>">
                                			    	<?php echo $p->user_login; ?>(<?php echo $p->user_email;?>)
                                			    </option>
                                			    <?php 
                    			            }else{
                    			                $vals[]=$p->ID;
                    			                ?>
                                			    <option value="<?php echo $p->ID;?>">
                                			    	<?php echo $p->post_title; ?>
                                			    </option>
                                			    <?php 
                    			            }
                    			        }
                    			    }
                    			}else{
                        			$value = $this->get_option($key,$data['default']);
                        			if($value&&is_array($value)){
                        			    foreach ($value as $value1) {
                        			        if(in_array($data['post_type'], array('customer','wp_user'))){
                        			            $p = $value?get_user_by('ID',$value1):null;
                        			            if($p){
                        			                $vals[]=$value1;
                        			                ?>
                                    			    <option value="<?php echo $value1;?>">
                                    			    	<?php echo $p->user_login; ?>(<?php echo $p->user_email;?>)
                                    			    </option>
                                    			    <?php 
                                    			}
                        			        }else{
                        			            $p = $value?get_post($value1):null;
                        			            if($p){
                        			                $vals[]=$value1;
                        			                ?>
                                    			    <option value="<?php echo $value1;?>">
                                    			    	<?php echo $p->post_title; ?>
                                    			    </option>
                                    			    <?php 
                                    			}
                        			        }
                            			    
                        			    }
                        			}else{
                        			    if(in_array($data['post_type'], array('customer','wp_user'))){
                        			        $p = $value?get_user_by('ID',$value):null;
                        			        if($p){
                        			            $vals[]=$value;
                        			            ?>
                                			    <option value="<?php echo $value;?>">
                                			    	<?php echo $p->user_login; ?>(<?php echo $p->user_email;?>)
                                			    </option>
                                			    <?php 
                                			}
                        			    }else{
                        			        $p = $value?get_post($value):null;
                        			        if($p){
                        			            $vals[]=$value;
                        			            ?>
                                			    <option value="<?php echo $value;?>">
                                			    	<?php echo $p->post_title; ?>
                                			    </option>
                                			    <?php 
                                			}
                        			    }
                        			    
                        			}
                    			}
                    			?>
                    		</select>
                    		<script type="text/javascript">
                        		(function($){
    								$(document).bind('wshop-on-select2-inited',function(){
    									$("#<?php echo esc_attr( $field_key ); ?>").val(<?php echo json_encode($vals)?>).trigger('change');
    								});
    								
    							})(jQuery);
                    		</script>
    						<?php echo $this->get_description_html( $data ); ?>
    					</fieldset>
                	</td>
                </tr>
                <?php
        		return ob_get_clean ();
		}
	}
	
	/**
	 * Generate Multiselect HTML.
	 *
	 * @param mixed $key        	
	 * @param mixed $data        	
	 * @since 1.0.0
	 * @return string
	 */
	public function generate_multiselect_html($key, $data) {
		$field = $this->get_field_key ( $key );
		$defaults = array (
				'title' => '',
				'disabled' => false,
				'class' => '',
				'css' => 'min-width:400px;',
				'placeholder' => '',
				'type' => 'text',
				'desc_tip' => false,
				'default'=>array(),
				'description' => '',
				'custom_attributes' => array (),
				'options' => array () 
		);
		if(isset($data['func'])&&$data['func']){
		    $data['options'] = call_user_func($data['options']);
		}
		$data = wp_parse_args ( $data, $defaults );
		$value = ( array ) $this->get_option ( $key, isset($data['default'])&&is_array($data['default'])? $data['default']:array () );
		ob_start ();
		?>
        <tr valign="top" class="<?php echo isset($data['tr_css'])?$data['tr_css']:''; ?>">
        	<th scope="row" class="titledesc"><label
        		for="<?php echo esc_attr( $field ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></label>
        					<?php echo $this->get_tooltip_html( $data ); ?>
        				</th>
        	<td class="forminp">
        		<fieldset>
        			<legend class="screen-reader-text">
        				<span><?php echo wp_kses_post( $data['title'] ); ?></span>
        			</legend>
        			
        			<ul style="list-style: none; <?php echo esc_attr( $data['css'] ); ?>"  class="<?php echo esc_attr( $data['class'] ); ?>" id="<?php echo esc_attr( $field ); ?>" >
        				
        				<?php foreach ( (array) $data['options'] as $option_key => $option_value ) : ?>
        					<li>
        					<label><input name="<?php echo esc_attr( $field ); ?>[]" type="checkbox"  <?php disabled( $data['disabled'], true ); ?> <?php echo $this->get_custom_attribute_html( $data ); ?> value="<?php echo esc_attr( $option_key ); ?>"
        						<?php checked( in_array( $option_key, $value ), true ); ?>> <?php echo esc_attr( $option_value ); ?></label>
        					</li>
        				<?php endforeach; ?>
        							
        			</ul>
        			
        			<?php echo $this->get_description_html( $data ); ?>
        		</fieldset>
        	</td>
        </tr>
        <?php
		
		return ob_get_clean ();
	}

	/**
	 * Generate subtitle HTML.
	 *
	 * @param mixed $key
	 * @param mixed $data
	 * @since 1.0.0
	 * @return string
	 */
	public function generate_subtitle_html($key, $data) {
	    $field = $this->get_field_key ( $key );
	    $defaults = array (
	        'title' => '',
	        'class' => ''
	    );
	
	    $data = wp_parse_args ( $data, $defaults );
	
	    ob_start ();
	    ?>
	    <tr class="<?php echo isset($data['tr_css'])?$data['tr_css']:''; ?>">
    	    <td colspan="2" style="display:block;margin:0;padding:0;">

    	    <?php 
    	       if(!isset($data['dividing'])||$data['dividing']){
    	           ?>
    	           <hr/>
    	           <?php 
    	       }
    	    ?>
    	    
    	    <h2><?php echo wp_kses_post( isset($data['title'])?$data['title']:null ); ?> </h2>
        	<p class="description"><?php echo wp_kses_post( isset($data['description'])?$data['description']:null ); ?></p>

	    </td>
	    </tr>
		<?php
		
		return ob_get_clean ();
	}
	
	public function generate_custom_html($key, $data) {
	    $field = $this->get_field_key ( $key );
	    $defaults = array (
	        'func' => null
	    );
	
	    ob_start ();
	    if(isset($data['func'])&&$data['func']){
	        call_user_func_array($data['func'],array(
	            $key,
	            $this,
	            $data
	        ));
	    }
		
		return ob_get_clean ();
	}
	
	/**
	 * Validate the data on the "Settings" form.
	 *
	 * @since 1.0.0
	 * @param array $form_fields
	 *        	(default: array())
	 */
	public function validate_settings_fields($form_fields = array()) {
		if (empty ( $form_fields )) {
			$form_fields = $this->get_form_fields ();
		}
		
		$this->sanitized_fields = array ();
		
		foreach ( $form_fields as $key => $field ) {
		    if(isset($field['ignore'])){
		        continue;
		    }
		    
			// Default to "text" field type.
			$type = empty ( $field ['type'] ) ? 'text' : $field ['type'];
			
			// Look for a validate_FIELDID_field method for special handling
			if(isset($field['validate'])){
			    $func =$field['validate'];
			    $field = call_user_func_array($func,array( $key ,$this));
			}
			elseif (method_exists ( $this, 'validate_' . $key . '_field' )) {
				$field = $this->{'validate_' . $key . '_field'} ( $key );
				
				// Exclude certain types from saving
			} elseif (in_array ( $type, array (
					'title' 
			) )) {
				continue;
				
				// Look for a validate_FIELDTYPE_field method
			} elseif (method_exists ( $this, 'validate_' . $type . '_field' )) {
				$field = $this->{'validate_' . $type . '_field'} ( $key );
				// Fallback to text
			} else {
				$field = $this->validate_text_field ( $key );
			}
			
			$this->sanitized_fields [$key] = $field;
		}
	}
	
	/**
	 * Validate Text Field.
	 *
	 * Make sure the data is escaped correctly, etc.
	 *
	 * @since 1.0.0
	 * @param mixed $key        	
	 * @return string
	 */
	public function validate_text_field($key) {
		$text = $this->get_option ( $key );
		$field = $this->get_field_key ( $key );
		
		if (isset ( $_POST [$field] )) {
		    $settings = $this->form_fields[$key];
		    if(isset($settings['ignore_kses_post'])&&$settings['ignore_kses_post']){
	            $text = !is_array( $_POST [$field])? ( trim ( stripslashes ( $_POST [$field] ) ) ): $_POST [$field];
	        }else{
	            $text =!is_array( $_POST [$field])? wp_kses_post ( trim ( stripslashes ( $_POST [$field] ) ) ): $_POST [$field];
	        }
			
		}
		
		return $text;
	}
	
	public function validate_select_field($key) {
	    $text = $this->get_option ( $key );
	    $field = $this->get_field_key ( $key );
	
	    if (isset ( $_POST [$field] )) {
	        $settings = $this->form_fields[$key];
	        if(isset($settings['ignore_kses_post'])&&$settings['ignore_kses_post']){
	            $text = !is_array( $_POST [$field])? ( trim ( stripslashes ( $_POST [$field] ) ) ): $_POST [$field];
	        }else{
	            $text =!is_array( $_POST [$field])? wp_kses_post ( trim ( stripslashes ( $_POST [$field] ) ) ): $_POST [$field];
	        }
	        	
	    }
	
	    return $text;
	}
	
	/**
	 * Validate Price Field.
	 *
	 * Make sure the data is escaped correctly, etc.
	 *
	 * @since 1.0.0
	 * @param mixed $key        	
	 * @return string
	 */
	public function validate_price_field($key) {
		$text = $this->get_option ( $key );
		$field = $this->get_field_key ( $key );
		
		if (isset ( $_POST [$field] )) {
			
			if ($_POST [$field] !== '') {
				$text =  ( trim ( stripslashes ( $_POST [$field] ) ) );
			} else {
				$text = '';
			}
		}
		
		return $text;
	}
	
	/**
	 * Validate Decimal Field.
	 *
	 * Make sure the data is escaped correctly, etc.
	 * @since 1.0.0
	 * @param mixed $key        	
	 * @return string
	 */
	public function validate_decimal_field($key) {
		$text = $this->get_option ( $key );
		$field = $this->get_field_key ( $key );
		
		if (isset ( $_POST [$field] )) {
			
			if ($_POST [$field] !== '') {
				$text =  ( trim ( stripslashes ( $_POST [$field] ) ) );
			} else {
				$text = '';
			}
		}
		
		return $text;
	}
	
	public function validate_datetime_field($key) {
	    $fields =$this->get_form_fields ();
	    
	    $data = $fields[$key];
	    $defaults = array (
	        'title' => '',
	        'disabled' => false,
	        'class' => '',
	        'css' => '',
	        'format'=>'Y-m-d H:i',
	        'data_format'=>'date',//date|time
	        'format_js'=>'yyyy-MM-dd HH:mm',
	        'placeholder' => '',
	        'type' => 'text',
	        'desc_tip' => false,
	        'description' => '',
	        'custom_attributes' => array ()
	    );
	    
	    $data = wp_parse_args ( $data, $defaults );
	    
	    $text = $this->get_option ( $key );
	    $field = $this->get_field_key ( $key );
	
	    if (isset ( $_POST [$field] )) {
	        	
	        if ($_POST [$field] !== '') {
	            if($data['data_format']=='date'){
	               $text =  date($data['format'],strtotime( trim ( stripslashes ( $_POST [$field] ) ))) ;
	            }else{
	                $text =  strtotime( trim ( stripslashes ( $_POST [$field] ) )) ;
	            }
	        } else {
	            $text = '';
	        }
	    }
	
	    return $text;
	}
	
	/**
	 * Validate Password Field.
	 *
	 * Make sure the data is escaped correctly, etc.
	 *
	 * @param mixed $key        	
	 * @since 1.0.0
	 * @return string
	 */
	public function validate_password_field($key) {
		$field = $this->get_field_key ( $key );
		$value = wp_kses_post ( trim ( stripslashes ( $_POST [$field] ) ) );
		return $value;
	}
	
	/**
	 * Validate Textarea Field.
	 *
	 * Make sure the data is escaped correctly, etc.
	 *
	 * @param mixed $key        	
	 * @since 1.0.0
	 * @return string
	 */
	public function validate_textarea_field($key) {
		$text = $this->get_option ( $key );
		$field = $this->get_field_key ( $key );
		
		if (isset ( $_POST [$field] )) {
			
		    $settings = $this->form_fields[$key];
		    if(isset($settings['ignore_kses_post'])&&$settings['ignore_kses_post']){
		        $text =   trim ( stripslashes ( $_POST [$field]  ));
	    }else{
		        $text = wp_kses ( trim ( stripslashes ( $_POST [$field] ) ), array_merge ( array (
					'iframe' => array (
							'src' => true,
							'style' => true,
							'id' => true,
							'class' => true 
					) 
			), wp_kses_allowed_html ( 'post' ) ) );
		    }
		    
			
		}
		
		return $text;
	}
	
	/**
	 * Validate Checkbox Field.
	 *
	 * If not set, return "no", otherwise return "yes".
	 *
	 * @param mixed $key        	
	 * @since 1.0.0
	 * @return string
	 */
	public function validate_checkbox_field($key) {
		$status = 'no';
		$field = $this->get_field_key ( $key );
		
		if (isset ( $_POST [$field] ) && (1 == $_POST [$field])) {
			$status = 'yes';
		}
		
		return $status;
	}
	
	public function validate_number_field($key) {
	    $field = $this->get_field_key ( $key );
		$value = trim ( stripslashes ( $_POST [$field] ) );
		if($value===''){
		    return null;
		}
		
		return intval($value);
	}
	
	/**
	 * Validate Multiselect Field.
	 *
	 * Make sure the data is escaped correctly, etc.
	 *
	 * @param mixed $key        	
	 * @since 1.0.0
	 * @return string
	 */
	public function validate_multiselect_field($key) {
		$field = $this->get_field_key ( $key );
		
		if (isset ( $_POST [$field] )) {
			$value = array_map ( 'stripslashes', ( array ) $_POST [$field] );
		} else {
			$value = '';
		}
		
		return $value;
	}
}
