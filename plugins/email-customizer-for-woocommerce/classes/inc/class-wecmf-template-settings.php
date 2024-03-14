<?php
/**
 * Woo Email Customizer
 *
 * @author    ThemeHiGH
 * @category  Admin
 */

if(!defined('ABSPATH')){ exit; }

if(!class_exists('WECMF_Template_Settings')):
class WECMF_Template_Settings extends WECMF_Builder_Settings {
	/**
	 * Main instance of the class
	 *
	 * @access   protected
	 * @var      $_instance    
	 */
	protected static $_instance = null;

	/**
	 * Array of WooCommerce email statuses
	 *
	 * @access   private
	 * @var      $template_status    
	 */
	private $template_status = array();

	/**
	 * Array of messages to show on completion of email mapping actions
	 *
	 * @access   private
	 * @var      $map_msgs    
	 */
	private $map_msgs = array();
	
	/**
	 * Manages the email mapping form submission result
	 *
	 * @access   private
	 * @var      $result   boolean result of form submission
	 */
	private $result = 'initialized';

	/**
	 * Array of field properties for email mapping form
	 *
	 * @access   private
	 * @var      $map_fields    form fields
	 */
	private $map_fields = array();

	/** Decalre variables Explicitly */
	private $template_map;

	public function __construct() {
		parent::__construct('template_settings', '');
		$this->init_constants();
	}
	
	public static function instance() {
		if(is_null(self::$_instance)){
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	public function init_constants(){
			
		$this->template_status =array(
			'0'=>'admin-new-order',
			'1'=>'admin-cancelled-order',
			'2'=>'admin-failed-order',
			'3'=>'customer-completed-order',
			'4'=>'customer-on-hold-order',
			'5'=>'customer-processing-order',
			'6'=>'customer-refunded-order',
			'7'=>'customer-partially-refunded-order',
			'8'=>'customer-invoice',
			'9'=>'customer-note',
			'10'=>'customer-reset-password',
			'11'=>'customer-new-account',
		);
		$this->map_msgs = array(
			true	=> array(
				'msg' 	=> 	array(
					'save'				=>	'Settings Saved',
					'reset'				=>	'Template Settings Successfully Reset',
					'reset-template'	=>	'Template Reset Successfully',
				),
				'class'		=>	'thwecmf-save-success',
			),
			false	=> array(
				'msg' 	=> 	array(
					'save'				=>	'Your changes were not saved due to an error (or you made none!)',
					'reset'				=>	'Reset not done due to an error (or nothing to reset!)',
					'reset-template'	=>	'An error occured while reseting the template ( or nothing to reset )',
				),
				'class'		=>	'thwecmf-save-error',
			),
		);

		$this->map_fields = array(
			'template-list'		=> array(
				'type'=>'select', 'name'=>'template-list[]', 'label'=>'', 'value'=>'','class'=>'thwecmf-paragraph','options'=>'',
			)
		);
	}

	/**
	 * Get the url of the templates page
	 *
	 * @param string $tab tab url parameter
	 * @param string $section section url parameter
	 * @return string url of the template page
	 */
	public function get_admin_url($submenu = false){
		if( $submenu === "premium" ){
			$url = 'admin.php?page=thwecmf_premium_features';
		}else{
			$url = 'admin.php?page=thwecmf_email_customizer';
		}
		return admin_url($url);
	}

	/**
     * Prepare template settings to be saved
     *
	 * @return  string $settings settings to be saved
     */
	private function prepare_settings(){
		$settings = WECMF_Utils::thwecmf_get_template_settings();
		$template_map = isset( $settings[WECMF_Utils::SETTINGS_KEY_TEMPLATE_MAP] ) ? $settings[WECMF_Utils::SETTINGS_KEY_TEMPLATE_MAP] : array();
		$file_ext = 'php';
		foreach ($_POST['i_template-list'] as $key => $value) {
			$template_map[$this->template_status[sanitize_text_field( $key )]] = sanitize_text_field($value);
		}
		$settings[WECMF_Utils::SETTINGS_KEY_TEMPLATE_MAP] = $template_map;
		return $settings;
	}

	/**
     * Save the template mapping
     *
	 * @return  string message to be displayed after saving
     */
	private function save_settings(){
		$result = false;
		if( !isset($_POST['i_template-list']) || !isset( $_POST['thwecmf_template_map'] ) || !wp_verify_nonce( $_POST['thwecmf_template_map'], 'template_map_action' ) || !WECMF_Utils::is_user_capable() ){
			wp_die( '<div class="wecm-wp-die-message">Action failed. Could not verify nonce.</div>' );
		}
		$temp_data = array();
   		$settings = $this->prepare_settings();
   		$result = WECMF_Utils::thwecmf_save_template_settings($settings);
		return $result;
	}

	/**
     * Get the path of the template
     *
	 * @param  string $template name of template file
	 * @param  boolean $backup choose between custom/default template
	 * @return string $file path of the desired template file
     */
	private function get_template_path( $template, $backup=false ){
		$file = $template.'.php';
		if( $backup ){
			$file = esc_url( TH_WECMF_T_PATH ).$file;
		}else{
			$file = esc_url( THWECMF_CUSTOM_T_PATH ).$file;
		}
		return $file;
	}

	/**
     * Reset the template to default
     *
	 * @return string message to be displayed based on the action
     */
	private function reset_template(){
		$result = false;
		$file_reset = false;
		$template = isset( $_POST['i_template_name'] ) ? sanitize_text_field( $_POST['i_template_name'] ) : false;
		if( $template ){
			if( !wp_verify_nonce( $_POST['thwecmf_edit_template_'.$template], 'thwecmf_edit_template' ) || !WECMF_Utils::is_user_capable() ){
				wp_die( '<div class="wecm-wp-die-message">Action failed. Could not verify nonce.</div>' );
			}
			$result = WECMF_Utils::thwecmf_reset_templates( $template );
			$edited_template = $this->get_template_path( $template );
			$backup_template = $this->get_template_path( $template, true );
			if( file_exists( $backup_template ) ){
				$file_reset = copy( $backup_template,$edited_template );
			}
		}
		return $result || $file_reset;
	}

	/**
     * Reset the template map settings
     *
	 * @return string message to be displayed based on the action
     */
	private function reset_settings(){
		$result = false;

		if( !isset( $_POST['thwecmf_template_map'] ) || !wp_verify_nonce( $_POST['thwecmf_template_map'], 'template_map_action' ) || !WECMF_Utils::is_user_capable() ){
			wp_die( '<div class="wecm-wp-die-message">Action failed. Could not verify nonce.</div>' );
		}else{
			$result = $this->reset_to_default();
		}
		return $result;
	}

	/**
     * Reset the template map settings to default
     *
	 * @return boolean settings reset or not
     */
	public function reset_to_default() {
		$settings = WECMF_Utils::thwecmf_reset_template_map();
		$result = WECMF_Utils::thwecmf_save_template_settings($settings);
		return $result;
	}

	/**
     * Render the page content and manage page actions
     *
     */
	public function render_page($page){
		?>
		<div id="thwecmf_template_mapping" class="thwecmf-plain-background">
			<?php
			$this->render_notifications();
			$this->init_field_form_props();
			$this->render_heading($page);
			if($page === "thwecmf_email_mapping"){
				$this->render_map_template_form();
			}else{
				$this->manage_templates();
			}
			?>
		</div>
		<?php
    }

    /**
     * Load the template mapping data
     *
     */
    public function init_field_form_props(){
		$this->template_map = WECMF_Utils::thwecmf_get_template_map();
	}

	private function get_template_label( $label ){
		if( strlen($label) > 0 && strlen($label) > 17 ){
			return substr($label, 0, 16)." ....";
		}
		return $label;
	}

	public function manage_templates(){
		?>
		<div class="thwecmf-template-custom-wrapper thwecmf-template-wrapper">
		    <div class="thwecmf-templates thwecmf-custom-templates">
				<div class="thwecmf-template-preview-wrapper">
					<?php
					foreach (WECMF_Utils::email_statuses() as $key => $label) {
						$key = str_replace('-', '_', $key);
						$url = $key === "customer_partially_refunded_order" ? "customer_refunded_order" : $key;
						?>
		    			<div class="thwecmf-template-box">
		    				<form name="thwecmf_edit_template_form_<?php echo $key; ?>" action="" method="POST">
		    					<?php
		    					if ( function_exists('wp_nonce_field') ){
									wp_nonce_field( 'thwecmf_edit_template', 'thwecmf_edit_template_'.$key );
		    					}
		    					?>
		    					<input type="hidden" name="i_template_type" value="">
								<input type="hidden" name="i_template_name" value="<?php echo esc_attr($key); ?>">
			    				<div class="thwecmf-template-image" style='background-image: url(<?php echo esc_url(TH_WECMF_ASSETS_URL."images/{$url}.svg"); ?>) ;'>
								</div>
			    				<div class="thwecmf-template-name">
			    					<p class="thwecmf-label" title="<?php echo esc_attr( $label ); ?>"><?php echo esc_html( $this->get_template_label( $label ) ); ?></p>
			    				</div>
		    					<div class="template-manage-menu">
		    						<div class="template-manage-menu-item">
		    							<button type="submit" class="thwecmf-template-action-links" formaction="<?php $this->get_admin_url(); ?>" name="i_edit_template">
		    								<img src="<?php echo TH_WECMF_ASSETS_URL ?>images/template-edit.svg" class="template-edit-icon">
		    							</button>
		    							<button type="submit" class="thwecmf-template-action-links thwecmf-reset-link" name="reset_template">
				    						<img src="<?php echo TH_WECMF_ASSETS_URL ?>images/template-reset.svg">
										</button>
									</div>
		    					</div>
			    			</form>
		    			</div>
		    		<?php } ?>
		    	</div>
			</div>
		</div>
	    	<?php
	}

	public function render_notifications(){
		$result = "load";
		$action = "";
		if( isset( $_POST['save_settings'] ) ){
			$result = $this->save_settings();
			$action = "save";
		
		}else if( isset( $_POST['reset_settings'] ) ){
			$result = $this->reset_settings();
			$action = "reset";

		}else if( isset( $_POST['reset_template'] ) ){
			$result = $this->reset_template();
		}

		if( $result === "load" ){
			return;
		}
		
		if( $result ){
			$result = "success";
			$icons = "dashicons-yes";
			$message = $action === "save" ? "Settings Saved" : "Template Settings Successfully Reset";
		}else{
			$result = "error";
			$icons = "dashicons-no-alt";
			$message = $action === "save" ? "Your changes were not saved due to an error (or you made none!)" : "Reset not done due to an error (or nothing to reset!)";
		}
		?>
		<div id="thwecmf_validations" class="thwecmf-template-validation">
			<div class="validation-wrapper thwecmf-<?php echo $result; ?>">
        		<span class="dashicons <?php echo $icons; ?>"></span>
		        <div class="validation-messages">
		            <p class="thwecmf-label"><?php echo $result; ?></p>
		            <p class="thwecmf-label-light"><?php echo $message; ?></p>
		        </div>
		    </div>
        </div>
        <script>
        	jQuery(function($) {
        		setTimeout(function() { $("#thwecmf_validations").remove(); }, 2000);
        	});
        </script>
        <?php
	}

	public function render_heading($page=false){
		?>
		<div class="thwecmf-mapping-title">
			<h1 class="thwecmf-main-heading"><?php echo $page === "thwecmf_email_mapping" ? "Email Mapping" : "Templates";?></h1>
			<?php if( $page === "thwecmf_email_customizer" ){

				echo '<a class="btn thwecmf-view-premium" href="'.esc_url( $this->get_admin_url("premium") ).'"><image src="'.TH_WECMF_ASSETS_URL.'images/premium.svg">Premium</a>';
			} ?>
		</div>
		<?php
	}

	/**
     * Render the template mapping form
     *
     */
	private function render_map_template_form(){
		?>
		<form name="template_map_form" action="" method="POST">
			<?php
			if ( function_exists('wp_nonce_field') ){
				wp_nonce_field( 'template_map_action', 'thwecmf_template_map' );
	    	}
	    	?>
			<table id="thwecmf_template_map">
				<tbody>
					<?php foreach (WECMF_Utils::email_statuses() as $key => $email) {
						echo '<tr>';
						$this->mapping_row_template( $email, $key );
						$this->mapping_row_choose_template( $key, $email );
						echo '</tr>';
					}
					?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="3">
							<button class="btn btn-primary thwecmf-mapping-button" name="save_settings" type="submit">Save</button>
							<button class="btn thwecmf-mapping-button" name="reset_settings" type="submit" onclick="return confirm('Are you sure you want to reset to default settings? all your changes will be deleted.');">Reset</button>
						</td>
						<td></td>
					</tr>
				</tfoot>
			</table>
		</form>
    	<?php
    }

    public function get_template_icon_url($template){
		return TH_WECMF_ASSETS_URL.'images/woo.svg';
	}

	public function get_template_created_date(){
		return 'December 31, 2020';
	}

    public function mapping_row_template( $template, $key ){
		$url = $this->get_template_icon_url( $template );
		$date = $this->get_template_created_date( $template );
		?>
		<td class="thwecmf-mapping-column-template">
			<input type="hidden" name="i_email-id[]" value="<?php echo $key; ?>">
			<div class="thwecmf-template-information">
				<div class="thwecmf-template-icon thwecmf-inline thwecmf-template-info">
					<img src="<?php echo $url; ?>">
				</div>
				<div class="thwecmf-template-label thwecmf-inline thwecmf-template-info">
					<div class="thwecmf-label"><?php echo $template; ?></div>
				</div>
			</div>
		</td>
		<?php
	}

	public function mapping_row_choose_template( $email, $label ){
		?>
		<td class="thwecmf-mapping-column-map">
			<label class="thwecmf-paragraph thwecmf-block thwecmf-label-light">Choose from saved templates</label>
			<?php $this->render_map_field_template($this->map_fields['template-list'], $email, $label); ?>
		</td>
		<?php
	}

	public function get_options($email,$label){
		$status = str_replace('-', '_', $email);
		$options = array(
			'' => 'Default Template',
			$status => $label
		);
		return $options;
	}

	public function render_map_field_template( $field, $email, $label ){
		$options = $this->get_options( $email, $label );
		$name = isset( $field['name'] ) ? $field['name'] : '';
		$class = isset( $field['class'] ) ? $field['class'] : '';
		$template = isset( $this->template_map[$email] ) ? $this->template_map[$email] : "";

		echo '<select name="i_'.$name.'" class="'.$class.'">';
		foreach ($options as $key => $value) {
			$selected = $template === $key ? "selected" : "";
			echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
		}
		echo '</select>';
	}
	
}
endif;