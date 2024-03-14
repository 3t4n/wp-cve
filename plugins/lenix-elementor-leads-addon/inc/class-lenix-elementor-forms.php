<?php
class Lenix_Register_Elementor_Forms {
	
	public $forms;
	
	private function get_froms(){
		
        if(!is_null($this->forms)){
        	return $this->forms;
        }
      
      	$this->forms = array();
      
		global $wpdb;
		$sql_query = "SELECT *  FROM `{$wpdb->prefix}postmeta`
		WHERE `meta_key` LIKE '_elementor_data'
		AND `meta_value` LIKE '%\"widgetType\":\"form\"%'
		AND `post_id` IN (
			SELECT `id` FROM `{$wpdb->prefix}posts`
			WHERE `post_status` IN ('publish','draft','private')
		)";

		$results = $wpdb->get_results($sql_query);
		
		if (!count($results)){
			return $this->get_froms();
		}
		
		foreach($results as $result){
			$post_id = $result->post_id;
			$data = $result->meta_value;
			$json = json_decode($data,true);
			if($json){
				foreach($json as $j){
					$this->find_form_element($j,$post_id);
				}
			}
		}
      	return $this->get_froms();
	}
	
	private function find_form_element($element_data,$post_id) {
	
		if(!$element_data['elType']){
			return;
		}
		
		if ( 'widget' === $element_data['elType'] && 'form' === $element_data['widgetType'] ) {
	
			$this->forms[] = array(
				'post_id' => $post_id,
				'form_data' => $element_data,
			);
		}

		if ( ! empty( $element_data['elements'] ) ) {
			foreach ( $element_data['elements'] as $element ) {
				$this->find_form_element( $element,$post_id );
			}
		}
		
	}
	
	public function get_form_data($form_id,$post_id = false){
		
		if(!empty($this->get_froms())){
			foreach($this->get_froms() as $form){
				if($form['post_id'] == $post_id && $form_id == $form['form_data']['id']){
					return $form;
				}
				if(!$post_id && $form_id == $form['form_data']['id']){
					return $form;
				}
			}
		}
		
		return false;
		
	}
	
	public function display_forms_in_admin_panel(){
		if(empty($this->get_froms())){
		    echo "<h1>".__( 'No forms found yet', 'elementor-leads' )."</h1>";
				echo "<p>".__( 'To view forms, create at least one form', 'elementor-leads' )."</p>";
			return;
		}
      

      
      
		echo "<table class='wp-list-table widefat fixed striped'>";
		
		$tabs = array(
			'form-name' => __('Form Name','elementor-leads'),
			'form-location' => __('Form Location','elementor-leads'),
			'form-type' => __('Form Type','elementor-leads'),
			'leads-count' => __('Leads Count','elementor-leads'),
			'email-recipes' => __('Email Recipients','elementor-leads'),
			'actions' => __('Actions','elementor-leads'),
		);
		echo "<tr>";
		foreach($tabs as $key => $label){
			$style = $key == 'actions' ? ' style="width:250px"' : false;
			$style = $key == 'leads-count' ? ' style="width:80px"' : $style;
			echo "<th$style>$label</th>";
		}
		echo "</tr>";

		foreach($this->get_froms() as $form){
		
			$form_name = isset($form['form_data']['settings']['form_name']) ? $form['form_data']['settings']['form_name'] : __('No Form Name');
			$post_id = isset($form['post_id']) ? $form['post_id'] : 0;
			$element_id = isset($form['form_data']['id']) ? $form['form_data']['id'] : 0;
			

			
			
			$page_name = get_the_title($post_id);
			
			// fix elementor 2.1
			$form_slugs = array($element_id);
			$post_ids = array($post_id);
			
			if($included_posts = get_post_meta($post_id,'_elementor_global_widget_included_posts',true)){
				$post_ids = array_keys($included_posts);
				foreach($post_ids as $included_post_id ){
					$elementor_data = get_post_meta($included_post_id,'_elementor_data',true);
					$elementor_data_json = json_decode($elementor_data,true);

					$slug = recursive_get_forms_slugs($elementor_data_json,$post_id);
					if($slug){
						$form_slugs = array_merge($form_slugs,$slug);
					}
				}
				$post_ids[] = intval($post_id);
			}
			
		
			$args = array(
				'post_type'              => 'elementor_lead',
				'posts_per_page'         => '-1',
			);
			
			if($element_id){
				$args['elementor_form'] = $element_id;
			}
			
			if($post_id){
				$args['elementor_form_post_id'] = $post_id;
			}
			
			global $leads_query;;
			$leads_query = true;
			
			$query = new WP_Query( $args );
			$count_leads = $query->post_count;
			wp_reset_postdata();
			
			$leads_query = false;

            $email_recipes = isset($form['form_data']['settings']['email_to'])? explode(',',$form['form_data']['settings']['email_to']):"";
			$email_recipes = $email_recipes != "" ? implode('<br>',$email_recipes) : "";
			
			$is_global = get_post_meta($post_id,'_elementor_template_widget_type',true);
			$is_global = $is_global && 'form' === $is_global;
			$form_type = $is_global ? __('Global','elementor-leads').' '.'('.count($post_ids).')' : __('Single','elementor-leads');
			
			echo "<tr>";
				$link = admin_url()."edit.php?post_type=elementor_lead&elementor_form={$element_id}&elementor_form_post_id={$post_id}";
				echo "<td><a href='$link'><b>$form_name</b></a></td>";
				echo "<td>";
				$post_id_location = $is_global && $post_ids ? $post_ids[0] : $post_id;
				echo "<a target='_blank' href='".get_permalink($post_id_location)."'>".get_the_title($post_id_location).($is_global ? ' ('.get_the_title($post_id).')' : false)."</a></td>";
				echo "<td>".$form_type."</td>";
				echo "<td>$count_leads</td>";
				echo "<td>$email_recipes</td>";
				echo "<td>";
					if($count_leads):
						
						/*echo "<a class='export button' href='$link&lenix_elementor_leads_export' target='_blank'>".
						__( 'Export leads to csv', 'elementor-leads' )
						."</a>";*/
						
						echo "<form>";
							echo "<input type='hidden' name='post_type' value='elementor_lead'>";
							echo "<input type='hidden' name='lenix_elementor_leads_export' value='1'>";
							echo "<input type='hidden' name='elementor_form' value='{$element_id}'>";
							echo "<input type='hidden' name='elementor_form_post_id' value='{$post_id}'>";
							echo "<div class='define-dates' style='display: none;'>";
								echo "<table>";
									echo "<tr>";
										echo "<td><label>".__( 'From', 'elementor-leads' )."</label></td>";
										echo "<td><input type='date' name='from_date'></td>";
									echo "</tr>";
									echo "<tr>";
										echo "<td><label>".__( 'To', 'elementor-leads' )."</label></td>";
										echo "<td><input type='date' name='to_date'></td>";
									echo "</tr>";
								echo "</table>";
							echo "</div>";
							
							echo "<a class='button show-define-dates'>".
						__( 'Define Dates', 'elementor-leads' )
						."</a>";
							
							echo "<button type='submit' class='export button button-primary'>".
						__( 'Export leads to csv', 'elementor-leads' )
						."</button>";
							
						echo "</form>";
						
					endif;
				echo "</td>";
			echo "</tr>";
		}
		
		echo "</table>";
		
		echo "<script>";
			echo "
			jQuery(document).on('click','.show-define-dates',function(){
				jQuery(this).closest('form').find('.define-dates').slideToggle('fast');
			});";
		echo "</script>";
		
	}
	public function elementor_leads_meta_box_add() {
		
		add_meta_box( 'FormFields', __('Form Fields','elementor-leads'), array($this,'elementor_leads_fields_meta_box'), 'elementor_lead', 'normal', 'high' );

	}
	
	public function elementor_leads_fields_meta_box( $post ) {

		wp_nonce_field( 'elementor_leads_meta_box_nonce', 'elementor_leads_meta_box_nonce' );
		
		$form_slug = get_post_meta($post->ID,'form_slug',true);
		$form_post_id = get_post_meta($post->ID,'post_id',true);
		$json_lead_data = get_post_meta($post->ID,'lead_data',true);
		$lead_data = json_decode($json_lead_data,true);

		?>
		
		<table class="lenix-leads-fields">
		
			<?php if( !empty($lead_data) ): ?>
		
				<?php foreach($lead_data as $field => $data): ?>
					<tr>
						<td width="30%"><?php echo $data['title'] ? $data['title'] : $data['type']; ?></td>
						<td width="70%">
							<?php
							if( isset($data['value']) ){
								display_value_by_type($data);
							}
							?>
						</td>
					</tr>
				<?php endforeach; ?>
			
			<?php endif; ?>
			
		</table>
		
		<style>
		.lenix-leads-fields {
			width: 100%;
		}
		.lenix-leads-fields td {
			padding: 5px;
			border: 1px solid #ddd;
		}
		.lenix-leads-fields td > * {
			width: 100%;
		}
		</style>
		<?php
		
	}
	
	public function elementor_leads_columns_head($defaults) {
	
		if( !isset($_GET['post_type']) || $_GET['post_type'] != 'elementor_lead'){
			return $defaults;
		}
		
		if(isset($_GET['elementor_form']) && isset($_GET['elementor_form_post_id'])):
			
			$form_slug = $_GET['elementor_form'];
			$form = $this->get_form_data($_GET['elementor_form'],$_GET['elementor_form_post_id']);
			$limit = apply_filters('lenix_elementor_leads_limit_admin_fields_cols',100);
			
			foreach($form['form_data']['settings']['form_fields'] as $field):
				if(!$limit){
					continue;
				}
				$limit--;
				$field_label = get_field_label_by_type($field);
			
				$field_id = isset($field['custom_id']) && $field['custom_id'] ? $field['custom_id'] : $field['_id'];
				$defaults[$field_id] = $field_label;
			endforeach;
		
		else:
			$defaults['form_data'] = __('Form Data','elementor-leads');
		endif;
		
		return $defaults;
	}
	 
	public function elementor_leads_columns_content($column_name, $post_ID) {
		
		$screen = get_current_screen();
		if ( 'elementor_lead' != $screen->post_type ){
			return;
		}
		
		$form_slug = isset($_GET['elementor_form']) ? $_GET['elementor_form'] : 0;	

		$lead_fields = get_post_meta($post_ID,'lead_data',true);
		$lead_fields = $lead_fields ? json_decode($lead_fields,true) : false;
		$is_form_data_column = $column_name == 'form_data';
		
		if(!empty($lead_fields)){
			
			echo $is_form_data_column ? '<table class="wp-list-table widefat fixed striped">' : false;
			
			foreach($lead_fields as $field => $data):				

				if ($column_name == $field || $is_form_data_column ) {
					echo  $is_form_data_column ? '<tr><th>'.($data['title'] ? $data['title'] : __('No Label','elementor-leads')).'</th><td>' : false;
					display_value_by_type($data);
					echo $is_form_data_column ? '</td></tr>' : false;
				}
				
			endforeach;
			
			echo $is_form_data_column ? '</table>' : false;
			
		} else {
			
			$form = $this->get_form_data($_GET['elementor_form'],$_GET['elementor_form_post_id']);
			$form_settings = $form['form_data']['settings']['form_fields'];
			foreach($form_settings as $field){
				$field_id = isset($field['custom_id']) && $field['custom_id'] ? $field['custom_id'] : $field['_id'];
				if ($column_name == $field_id) {
					$data['type'] = $field['field_type'];
					$val = get_post_meta($post_ID,$column_name,true);
					$data['value'] = $val ? $val : '-';
					display_value_by_type($data);
				}
			}
			
		}
		
		
		
	}
	
	public function show_list_of_elementor_forms() {
		$screen = get_current_screen();

		if ( 'elementor_lead' != $screen->post_type ){
			return;
		}
		
     	 
      
      	?>
		<div class="notice" style="padding: 0;border:0;">
          <?php
			
			$this->display_forms_in_admin_panel();
			?>
		</div>
	<?php          
		echo "<style>.search-box{display:none;}</style>"; 
	}
	

	public function remove_date_drop(){

		$screen = get_current_screen();

		if ( 'elementor_lead' == $screen->post_type ){
			add_filter('months_dropdown_results', '__return_empty_array');
          
		}
	}
	
	public function export_elementor_leads_to_csv(){
		
        if(!is_admin() || !( current_user_can('editor') || current_user_can('administrator') ) || !isset($_GET['lenix_elementor_leads_export']) || !isset($_GET['elementor_form']) || !isset($_GET['elementor_form_post_id']) ){
                return;
        }
            
		$form_slug = sanitize_key($_GET['elementor_form']);
		$form_post_id = sanitize_key($_GET['elementor_form_post_id']);
		
		if($form_slug && $form_post_id){
			
			$form = $this->get_form_data($form_slug,$form_post_id);
			$form_name = $form['form_data']['settings']['form_name'];
			
			lenix_download_send_headers("export-$form_name-" . date("d-m-Y") . ".csv");
			
			$csv_data[0][] = __('Date','elementor-leads');
			$csv_data[0][] = __('Time','elementor-leads');
			
			
			foreach($form['form_data']['settings']['form_fields'] as $field):
				$csv_data[0][] = html_entity_decode($field['field_label'], ENT_QUOTES, "utf-8");
			endforeach;
			
			$form_slugs = array($form_slug);
			$post_ids = array($form_post_id);
			
			if($included_posts = get_post_meta($form_post_id,'_elementor_global_widget_included_posts',true)){
				$post_ids = array_keys($included_posts);
				foreach($post_ids as $included_post_id ){
					$elementor_data = get_post_meta($included_post_id,'_elementor_data',true);
					$elementor_data_json = json_decode($elementor_data,true);

					$slug = recursive_get_forms_slugs($elementor_data_json,$form_post_id);
					if($slug){
						$form_slugs = array_merge($form_slugs,$slug);
					}
				}
				$post_ids[] = $form_post_id;
			}
			
			$args = array(
				'post_type'              => 'elementor_lead',
				'posts_per_page'         => '-1',
			);
			
			if($form_slug){
				$args['elementor_form'] = $form_slug;
			}
			
			if($form_post_id){
				$args['elementor_form_post_id'] = $form_post_id;
			}
			
			$from_date = lenix_get_query_field('from_date');
			$to_date = lenix_get_query_field('to_date');
			
			if($from_date || $to_date){
				
				$dates = array();
				
				if($from_date){
					$ymd = explode('-',$from_date);
					$dates['after'] = array(
						'year' => $ymd[0],
						'month' => $ymd[1],
						'day' => $ymd[2],
					);
				}
				
				if($to_date){
					$ymd = explode('-',$to_date);
					$dates['before'] = array(
						'year' => $ymd[0],
						'month' => $ymd[1],
						'day' => $ymd[2],
					);
				}
				
				$dates['inclusive'] = true;
				$args['date_query'] = array($dates);
				
			}

			
			global $leads_query;
			$leads_query = true;
			$query = new WP_Query( $args );
			$leads_query = false;

			// The Loop
			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();
					$values = array();
					
					$post_time = get_post_time('U', true);
					$values[] = date('d-m-Y',$post_time);
					$values[] = date('h:i A',$post_time);
					
					$json_lead_data = get_post_meta(get_the_ID(),'lead_data',true);
					$lead_data = json_decode($json_lead_data,true);
					
					foreach($form['form_data']['settings']['form_fields'] as $field):
						
						if(isset($field['type']) && $field['type'] == 'html'){
							continue;
						}
						
						$field_id = isset($field['custom_id']) && $field['custom_id'] ? $field['custom_id'] : $field['_id'];

                        if($lead_data){

                            if(isset($lead_data[$field_id])){

                                $values[] = $lead_data[$field_id]['value'];

                            }

                        }
					endforeach;
					
					$csv_data[] = $values;
				}
			} else {
				echo __( 'No leads found yet', 'elementor-leads' );
			}

			// Restore original Post Data
			wp_reset_postdata();
			
			if(!empty($csv_data)){
				echo lenix_array_to_csv($csv_data);
			}
			
			die();
		}
	}
	
	public function filter_form_leads($query){

		if( !is_admin() || !$query->get('post_type') || $query->get('post_type') != 'elementor_lead'){
			return;
		}

		$form_slug = isset($_GET['elementor_form']) ? $_GET['elementor_form'] : false;
		if($query->get('elementor_form')){
			$form_slug = $query->get('elementor_form');
		}
	
		$post_id = isset($_GET['elementor_form_post_id']) ? $_GET['elementor_form_post_id'] : false;
		if($query->get('elementor_form_post_id')){
			$post_id = $query->get('elementor_form_post_id');
		}
		
		if(!$form_slug || !$post_id){
			return;
		}
		
		if($is_global = get_post_meta($post_id,'_elementor_template_widget_type',true)){
			$included_posts = get_post_meta($post_id,'_elementor_global_widget_included_posts',true);
			if($included_posts){				
				$post_id = array_keys($included_posts);
			}
		}
		
		$meta_query = array(
			'relation' => 'AND'
		);
		
		if(!is_array($post_id)){
			$meta_query[] = array(
				'key'     => 'form_slug',
				'value'   => $form_slug,
				'compare' => '=',
			);
		}
		
		$meta_query[] = array(
			'key'     => 'post_id',
			'value'   => $post_id,
			'compare' => is_array($post_id) ? 'IN' : '=',
		);
		
		$query->set(
			'meta_query',
			$meta_query
		);
		
	}
	
	public function __construct(){
	
		add_action('lenix_elementor_leads_admin_options_page_section',array($this,'display_forms_in_admin_panel'));
		add_action('add_meta_boxes', array($this,'elementor_leads_meta_box_add') );
		add_filter('manage_posts_columns', array($this,'elementor_leads_columns_head'));
		add_action('manage_posts_custom_column', array($this,'elementor_leads_columns_content'), 10, 2);
		add_filter('views_edit-elementor_lead','__return_empty_array');
		add_action('admin_notices',array($this,'show_list_of_elementor_forms'));
		add_action('admin_head', array($this,'remove_date_drop'));
		add_action('init',array($this,'export_elementor_leads_to_csv'));		
		add_action('pre_get_posts',array($this,'filter_form_leads'));		
		
	}
	
}
new Lenix_Register_Elementor_Forms();