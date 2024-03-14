<?php
if ( isset($_SERVER['SCRIPT_FILENAME']) && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    exit(esc_html__('Please don\'t access this file directly.', 'WP2SL'));
}

if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Fields_Map_Table extends WP_List_Table {
    function __construct(){
        global $status, $page;
                
        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'Lead',     //singular name of the listed records
            'plural'    => 'Leads',    //plural name of the listed records
            'ajax'      => false       //does this table support ajax?
        ) );
    }

    /**
     * Display the default column content for a specific item and column name.
     *
     * @param array $item The item data.
     * @param string $column_name The column name.
     * @return string The column content.
     */
    function column_default($item, $column_name){
        switch($column_name){
            case 'field_name':
				if ($item['custom_field'] === "Y"){
					$Extra = '<div class="OEPL_Delete_Cust_Field_div"><img src ="'.OEPL_PLUGIN_URL.'image/delete-icon.png" class="OEPL_Delete_Cust_Field" title="Delete this custom field" pid='.$item['pid'].' /></div>';
				} else {
					$Extra = '';
				}
            	return $item['wp_meta_label'].$Extra;
			
			case 'custom_label':
				return '<input type="text" name="wp_meta_label[]" data-stored="'.$item['wp_custom_label'].'" value="'.$item['wp_custom_label'].'" maxlength="50" class ="OEPL_custom_label" /><a class="OEPL_small_button OEPL_save_custom_label" data-pid='.$item['pid'].' title="Save Custom Label"><i class="dashicons dashicons-yes-alt fa-2x"></i></a>';            
            case 'is_show':
				$isshow = '';
				if($item['is_show'] === 'Y')
					$isshow = "checked='checked'";
				return '<input type="checkbox" data-action="OEPL_Change_Status" data-pid='.$item['pid'].' class="OEPL_is_show OEPL_grid_status" value="1" '.$isshow.'>';
				
            case 'display_order':
				return '<input type="text" name="display_order[]" data-stored="'.$item['display_order'].'" value="'.$item['display_order'].'" maxlength="2" class ="OEPL_custom_order OEPLIntInput" /><a class="OEPL_small_button OEPL_save_custom_order" data-pid='.$item['pid'].' title="Save Custom Label"><span class="dashicons dashicons-yes-alt fa-2x"></span></a>';  
              
            case 'required':
				$isshow = '';
				if($item['required'] === 'Y')
					$isshow = "checked='checked'";
				return '<input type="checkbox" data-action="OEPL_Change_Required_Status" data-pid='.$item['pid'].' class="OEPL_required OEPL_grid_status" value="1" '.$isshow.'>';
				
			case 'hidden_field':
				if ($item['custom_field'] === "Y"){
					return '<img src ="'.OEPL_PLUGIN_URL.'image/NA_icon.png" title="Hidden feature is not applicable for this field" />';
				} else {
					$isshow = '';
                    $style = '';
                    if($item['hidden'] === 'Y'){
						$isshow = "checked='checked'";
                    } else {
                        $style = "display:none";
                    }

					return '<input type="checkbox" data-action="OEPL_Change_Hidden_Status" data-pid='.$item['pid'].' class="OEPL_is_hidden OEPL_grid_status" value="1" '.$isshow.'><input type="text" data-action="OEPL_Change_Hidden_Status_Val" data-pid='.$item['pid'].' class="OEPL_is_hidden OEPL_grid_status OEPL_hidden_value" value="'.$item['hidden_field_value'].'" style="'.$style.'">';	
				}
            default:
                return 'No Data'; //Show the whole array for troubleshooting purposes
        }
    }

    /**
     * Retrieve the list of columns for the table.
     *
     * @return array The list of columns.
     */
    function get_columns(){
        $columns = array(
            'field_name'	=> 'Field Name',
            'custom_label'	=> 'Custom Label',
            'display_order' => 'Display Order',
            'is_show'    	=> 'Show on Widget',
            'required'		=> 'Required',
            'hidden_field'	=> 'Hidden',
        );
        return $columns;
    }

    /**
     * Retrieve the list of sortable columns for the table.
     *
     * @return array The list of sortable columns.
     */
    function get_sortable_columns() {
        $sortable_columns = array(
            'field_name'	=> array('field_name',false),     
            'display_order'	=> array('display_order',true),		//true means it's already sorted
            'is_show'    	=> array('is_show',true),			//true means it's already sorted
        	'required'		=> array('required',true),
        	'hidden_field'	=> array('hidden',true),
		);
        return $sortable_columns;
    }

    /**
     * Retrieve the list of bulk actions for the table.
     *
     * @return array The list of bulk actions.
     */
    function get_bulk_actions() {
        $actions = array();
    	return $actions;
        $actions = array(
        	'display_on_widget'		=> 'Set on Widget',
            'remove_from_widget'	=> 'Remove from Widget',
            'update_display_order'	=> 'Update Display Order',
            'update_wp_meta_label'	=> 'Update Field Name',
            'make_field_required'	=> 'Set Required',
            'remove_required'		=> 'Unset Required',
            'make_hidden'			=> 'Set Hidden',
            'remove_hidden'			=> 'Unset Hidden',
        );
        return $actions;
    }
	
    /**
     * Display the search box HTML.
     *
     * @param string $text     The label text for the search box.
     * @param string $input_id The ID attribute for the search box input.
     */
	public function search_box( $text, $input_id ) { 
		
		wp_unslash($_POST);
		?>
	    <p class="search-box">
	      <label class="screen-reader-text" for="<?php echo $input_id ?>"><?php echo $text; ?>:</label>
          <input type="search" id="<?php echo $input_id ?>" name="LeadSearch" value="<?php if(isset($_POST['LeadSearch']))echo sanitize_text_field($_POST['LeadSearch']); ?>" />
	      <?php submit_button( $text, 'button', 'LeadSearchSubmit', false, array('id' => 'LeadSearchSubmit') ); ?>
	  	</p>
	<?php }
	
    /**
     * Display additional navigation elements above or below the table.
     *
     * @param string $item The current item.
     */
	function extra_tablenav($item){
		echo '<span class="subsubsub" >';
		echo "<a href=".admin_url('admin.php?page=mapping_table&is_show=Y')." 
			class='".((isset($_GET['is_show']) && $_GET['is_show'] === 'Y') ? "current":"")."'>".esc_html("Enabled on Widget")."</a>&nbsp;|&nbsp;";
		echo "<a href=".admin_url('admin.php?page=mapping_table&is_show=N')." class='".((isset($_GET['is_show']) && $_GET['is_show'] === 'N') ? "current":"")."'>".esc_html("Disabled on Widget")."</a>&nbsp;|&nbsp;";
		echo "<a href=".admin_url('admin.php?page=mapping_table&custom_field=Y')." class='".((isset($_GET['custom_field']) && $_GET['custom_field'] === 'Y') ? "current":"")."'>".esc_html("Custom Browse fields")."</a>&nbsp;|&nbsp;";
		echo "<a href=".admin_url('admin.php?page=mapping_table').">".esc_html("Reset")."</a>";
		echo '</span>';
	}
	
    /**
     * Process bulk actions performed on the table.
     */
    function process_bulk_action() {
    	global $wpdb;
		
		wp_unslash($_POST);
        $redirectFlag = FALSE;
        if( 'display_on_widget'===$this->current_action() && isset($_POST['Leads']) ) {
            foreach($_POST['Leads'] as $k=>$v) {
                $UpdateQuery = $wpdb->prepare("UPDATE ".OEPL_TBL_MAP_FIELDS." SET is_show='Y' WHERE pid='%d'", $v);
				$wpdb->query($UpdateQuery);
			}
			$redirectFlag = TRUE;	
		}
		else if('remove_from_widget' === $this->current_action() && isset($_POST['Leads'])) {
			foreach($_POST['Leads'] as $k=>$v) {
                $UpdateQuery = $wpdb->prepare("UPDATE ".OEPL_TBL_MAP_FIELDS." SET is_show='N' WHERE pid='%d'", $v);
				$wpdb->query($UpdateQuery);
			}
			$redirectFlag = TRUE;
		}
		else if ('update_display_order' === $this->current_action() && isset($_POST['display_order']) && isset($_POST['display_order_ID']) && isset($_POST['Leads'])) {
			$LeadsID = sanitize_text_field($_POST['Leads']);
			$DisplayOrder = sanitize_text_field($_POST['display_order']);
			$DisplayOrderID = sanitize_text_field($_POST['display_order_ID']);
			for($i = 0 ; $i<10 ; $i++) {
				if(in_array($DisplayOrderID[$i], $LeadsID)) {
                    $UpdateQuery = $wpdb->prepare("UPDATE ".OEPL_TBL_MAP_FIELDS." SET display_order='".$DisplayOrder[$i]."' WHERE pid='%d'", $DisplayOrderID[$i]);
					$wpdb->query($UpdateQuery);
				}
			}
			$redirectFlag = TRUE;
		}
		else if('update_wp_meta_label' === $this->current_action() && isset($_POST['wp_meta_label']) && isset($_POST['display_order_ID']) && isset($_POST['Leads']) ) {
			$LeadsID = sanitize_text_field($_POST['Leads']);
			$LabelsArray = sanitize_text_field($_POST['wp_meta_label']);
			$DisplayOrderID = sanitize_text_field($_POST['display_order_ID']);
			for($i = 0 ; $i<10 ; $i++) {
				if(in_array($DisplayOrderID[$i], $LeadsID)) {
                    $UpdateQuery = $wpdb->prepare("UPDATE ".OEPL_TBL_MAP_FIELDS." SET wp_custom_label='".$LabelsArray[$i]."' WHERE pid='%d'", $DisplayOrderID[$i]);
					$wpdb->query($UpdateQuery);
				}
			}
			$redirectFlag = TRUE;
		}
		else if('make_field_required' === $this->current_action() && isset($_POST['Leads']) ) {
			$LeadsID = sanitize_text_field($_POST['Leads']);
			foreach($LeadsID as $k=>$v) {
                $UpdateQuery = $wpdb->prepare("UPDATE ".OEPL_TBL_MAP_FIELDS." SET required='Y' WHERE pid='%d'", $v);
				$wpdb->query($UpdateQuery);
			}
			$redirectFlag = TRUE;
		}
		else if('remove_required' === $this->current_action() && isset($_POST['Leads']) ) {
			$LeadsID = sanitize_text_field($_POST['Leads']);
			foreach($LeadsID as $k=>$v) {
                $UpdateQuery = $wpdb->prepare("UPDATE ".OEPL_TBL_MAP_FIELDS." SET required='N' WHERE pid='%d'", $v);
				$wpdb->query($UpdateQuery);
			}
			$redirectFlag = TRUE;
		}
		else if('make_hidden' === $this->current_action() && isset($_POST['Leads']) ) {
			$LeadsID = sanitize_text_field($_POST['Leads']);
			foreach($LeadsID as $k=>$v) {
                $UpdateQuery = $wpdb->prepare("UPDATE ".OEPL_TBL_MAP_FIELDS." SET hidden='Y' WHERE pid='%d'", $v);
				$wpdb->query($UpdateQuery);
			}
			$redirectFlag = TRUE;
		}
		else if('remove_hidden' === $this->current_action() && isset($_POST['Leads']) ) {
			$LeadsID = sanitize_text_field($_POST['Leads']);
			foreach($LeadsID as $k=>$v) {
                $UpdateQuery = $wpdb->prepare("UPDATE ".OEPL_TBL_MAP_FIELDS." SET hidden='N' WHERE pid='%d'", $v);
				$wpdb->query($UpdateQuery);
			}
			$redirectFlag = TRUE;
		}
		
		if($redirectFlag === TRUE && isset($_GET['orderby']) && isset($_GET['order']) && isset($_GET['paged']) && isset($_GET['is_show']) && isset($_GET['custom_field'])) {
			if($_GET['orderby']) $orderby = '&orderby='.sanitize_text_field($_GET['orderby']); 
				else $orderby = '';
			if($_GET['order']) $order = '&order='.sanitize_text_field($_GET['order']);
				else $order = '';	
			if($_GET['paged']) $paged = '&paged='.sanitize_text_field($_GET['paged']);
				else $paged = '';
			if($_GET['is_show']) $is_show = '&is_show='.sanitize_text_field($_GET['is_show']);
				else $is_show = '';
			if($_GET['custom_field'] && $_GET['custom_field'] === 'Y') $is_show = '&custom_field='.sanitize_text_field($_GET['custom_field']);
				else $custom_field = '';
			
			$url = admin_url('admin.php?page=mapping_table'.sanitize_text_field($orderby).sanitize_text_field($order).sanitize_text_field($is_show).sanitize_text_field($paged).sanitize_text_field($custom_field));
			wp_redirect($url);
			exit;
		}
    }

    /**
     * Prepare the items for the table.
     */
    function prepare_items() {
        global $wpdb;
		
		wp_unslash($_POST);
        $per_page = 10;
        $columns = $this->get_columns();
        $hidden = array();
        $query = 'SELECT `pid`, `module`, `custom_field`, `wp_meta_label`, `wp_custom_label`, `is_show`, `display_order`, `required`, `hidden`, `hidden_field_value` FROM '.OEPL_TBL_MAP_FIELDS.' WHERE 1';
		
		if(isset($_POST['LeadSearch']) && !empty($_POST['LeadSearch'])) {
			$where = ' AND wp_meta_label LIKE "%'.sanitize_text_field($_POST['LeadSearch']).'%"';
			$query .= $where;
		}
		if(isset($_GET['is_show']) && !empty($_GET['is_show'])) {
			$where = ' AND is_show = "'.sanitize_text_field($_GET['is_show']).'"';
			$query .= $where;
		}
		if(isset($_GET['custom_field']) && !empty($_GET['custom_field']) && $_GET['custom_field'] === 'Y') {
			$where = " AND custom_field ='".sanitize_text_field($_GET['custom_field'])."'";
			$query .= $where;
		}
		$orderby 	= !empty($_GET["orderby"]) 	? sanitize_text_field($_GET["orderby"]): 'ASC';
		$order 		= !empty($_GET["order"]) 	? sanitize_text_field($_GET["order"]):'';
		if(!empty($orderby) & !empty($order)) {
			$query .=' ORDER BY '.$orderby.' '.$order;
		} else {
			$query .= ' ORDER BY is_show ASC,display_order ASC';
		}
        $data = $wpdb->get_results($query,ARRAY_A);      
        
		$sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->process_bulk_action();
		
        $current_page = $this->get_pagenum();
        $total_items = count($data);
        $data = array_slice($data,(($current_page-1)*$per_page),$per_page);
        $this->items = $data;
        $this->set_pagination_args( array(
            'total_items' => $total_items,                  //WE have to calculate the total number of items
            'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
            'total_pages' => ceil($total_items/$per_page),  //WE have to calculate the total number of pages
        ) );
    }
}