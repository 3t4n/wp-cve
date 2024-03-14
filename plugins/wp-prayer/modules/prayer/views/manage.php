<?php
if ( class_exists( 'WP_List_Table_Helper1' ) and ! class_exists( 'WPE_Prayer_Table' ) ) {
	class WPE_Prayer_Table extends WP_List_Table_Helper1 {
		public function __construct($tableinfo) {
			parent::__construct( $tableinfo );
		}
		/**
		* Slug for the view page.
		* @var string
		*/
		var $admin_view_page_name;
				/**
		* Column where to display actions.
		* @param  array  $item        Record.
		* @param  string $column_name Column name.
		* @return string              Column output.
		*/
		function column_default( $item, $column_name ) {
			
			// Return Default values from db except current timestamp field. If currenttimestamp_field is encountered return formatted value.
			$offset = get_option('gmt_offset');
			if ( ! empty( $this->currenttimestamp_field ) and $column_name == $this->currenttimestamp_field ) {
				$return = date_i18n(get_option('date_format'),strtotime( $item->$column_name )+$offset*3600 ).' '.date_i18n(get_option('time_format'),strtotime( $item->$column_name )+$offset*3600 );
			} else if ( $column_name == $this->col_showing_links ) {
				$actions = array();
				foreach ( $this->actions as $action ) {
					$action_slug = sanitize_title( $action );
					$action_label1 =  $action ;
					if($action_label1 == "delete"){
					$action_label = __('delete',WPE_TEXT_DOMAIN);
					} elseif($action_label1 == "edit"){
					$action_label= __('edit',WPE_TEXT_DOMAIN);
					} elseif($action_label1 == "prayers"){	
					$action_label = __('prayers',WPE_TEXT_DOMAIN);
					}
					else{
					$action_label = $action_label1;
					}					
					if ( 'delete' == $action_slug ) {
					$actions[ $action_slug ] = sprintf( '<a href="?page=%s&doaction=%s&'.$this->primary_col.'=%s">'.$action_label.'</a>',$this->admin_listing_page_name,$action_slug,$item->{$this->primary_col} );
					}else if ( 'edit' == $action_slug ) {
					$actions[ $action_slug ] = sprintf( '<a href="?page=%s&doaction=%s&'.$this->primary_col.'=%s">'.$action_label.'</a>',$this->admin_add_page_name,$action_slug,$item->{$this->primary_col} );
					}else if ( 'prayers' == $action_slug ) {
					$actions[ $action_slug ] = sprintf( '<a href="?page=%s&doaction=%s&'.$this->primary_col.'=%s">'.$action_label.'</a>',$this->admin_view_page_name,$action_slug,$item->{$this->primary_col} );
					}else { $actions[ $action_slug ] = sprintf( '<a href="?page=%s&doaction=%s&'.$this->primary_col.'=%s">'.$action_label.'</a>',$this->admin_listing_page_name,$action_slug,$item->{$this->primary_col} ); }
					}
				if($item->{$this->col_showing_links}!= '')
				return sprintf( '%1$s %2$s', $item->{$this->col_showing_links}, $this->row_actions( $actions ) );
					else
					return sprintf( '%1$s %2$s', $item->prayer_messages, $this->row_actions( $actions ) );
				} else {
					$return = $item->$column_name;
				}
				return $return;
			}

			/**
			* Output for Author  column.
			* @param array $item Prayer Row.
			*/
			public function column_prayer_author($item) {
				if($item->prayer_author_name != '')
				return $item->prayer_author_name;
				else{
					$user_info = get_userdata($item->prayer_author);
					return $user_info->display_name;
				}
			}


			/**
			* Output for Prayer count
			*/
			public function column_prayer_count($item) {
				global $wpdb;
				if($item->request_type === 'praise_report'){
					return 0;
				}
				else {
					global $prayer_id;

					$prayers = $wpdb->get_results( 'SELECT prayer_id, COUNT(*) AS \'total_prayer\' FROM ' . $wpdb->prefix . 'prayer_users GROUP BY prayer_id;', ARRAY_A );
					foreach($prayers as $prayer) {
						if($prayer['prayer_id'] == $item->prayer_id) {
							return $prayer['total_prayer'];
						}
					}
					return 0;
				}

			}

			/**
			* Output for Status column.
			* @param array $item Map Row.
			*/
			public function column_prayer_status($item) {
				if($item->prayer_status == 'pending' || $item->prayer_status == 'disapproved' || $item->prayer_status == 'private')
			$actions[ 'do_approve' ] = sprintf( '<a href="?page=%s&doaction=%s&'.$this->primary_col.'=%s">'.__('Approve',WPE_TEXT_DOMAIN).'</a>',$this->admin_listing_page_name,'do_approve',$item->{$this->primary_col} );
				if($item->prayer_status == 'pending' || $item->prayer_status == 'approved' || $item->prayer_status == 'private')
			$actions[ 'disapprove' ] = sprintf( '<a href="?page=%s&doaction=%s&'.$this->primary_col.'=%s">'.__('Disapprove',WPE_TEXT_DOMAIN).'</a>',$this->admin_listing_page_name,'disapprove',$item->{$this->primary_col} );
				if($item->prayer_status == 'pending' || $item->prayer_status == 'disapproved' || $item->prayer_status == 'approved')
			$actions[ 'private' ] = sprintf( '<a href="?page=%s&doaction=%s&'.$this->primary_col.'=%s">'.__('private',WPE_TEXT_DOMAIN).'</a>',$this->admin_listing_page_name,'private',$item->{$this->primary_col} );
            $status = $item->prayer_status;	
			if($status == "pending"){
			$status1 = __('pending',WPE_TEXT_DOMAIN);
			} elseif($status == "private"){
			$status1 = __('private',WPE_TEXT_DOMAIN);
			} elseif($status == "approved"){	
			$status1 = __('Approved',WPE_TEXT_DOMAIN);
			} elseif($status == "disapproved"){	
			$status1 = __('Disapproved',WPE_TEXT_DOMAIN);
			}
				
				return sprintf( '%1$s %2$s', ucwords( $status1 ), $this->row_actions( $actions ));
			}

			/**
			* Output for Request Type column.
			* @param array $item Map Row.
			*/
			public function column_request_type($item) {
				if($item->request_type == 'prayer_request')
				return __( 'Prayer Request',WPE_TEXT_DOMAIN );
				elseif($item->request_type == 'praise_report')
				return __( 'Praise Report',WPE_TEXT_DOMAIN );
			}
			/**
			* Approve action.
			*/
			public function do_approve(){
               
                $id = intval( sanitize_text_field( $_GET[ $this->primary_col ] ) );
				$modelFactory = new FactoryModelWPE();
				$prayer_obj = $modelFactory->create_object( 'prayer' );
				$res = $prayer_obj->change_prayer_status($id,'approved');
				$this->prepare_items();
				$this->response['success'] = __( ' '.ucwords( $this->singular_label ).' '.__('Approved',WPE_TEXT_DOMAIN), $this->textdomain );
				$this->listing();
			}
			/**
			* Disaaprove action.
			*/
			public function disapprove(){
				$id = intval( sanitize_text_field( $_GET[ $this->primary_col ] ) );
				$modelFactory = new FactoryModelWPE();
				$prayer_obj = $modelFactory->create_object( 'prayer' );
				$res = $prayer_obj -> change_prayer_status($id,'disapproved');
				$this->prepare_items();
				$this->response['success'] = __( ' '.ucwords( $this->singular_label ).' '.__('Disapproved',WPE_TEXT_DOMAIN), $this->textdomain );
				$this->listing();
			}
			/**
			* Private action.
			*/
			public function private(){
				$id = intval( sanitize_text_field( $_GET[ $this->primary_col ] ) );
				$modelFactory = new FactoryModelWPE();
				$prayer_obj = $modelFactory->create_object( 'prayer' );
				$res = $prayer_obj -> change_prayer_status($id,'private');
				$this->prepare_items();
				$this->response['success'] = __( ' '.ucwords( $this->singular_label ).' '.__('private',WPE_TEXT_DOMAIN), $this->textdomain );
				$this->listing();
			}			
            /**
			* Perform bulk action.
			*/
			function process_bulk_action(){
				parent::process_bulk_action( );
				$ids = $this->get_user_selected_records();
				if ( 'approve' === $this->current_action() and ! empty( $ids ) ) {
					$modelFactory = new FactoryModelWPE();
					$prayer_obj = $modelFactory->create_object( 'prayer' );
					$idArray = explode(",",$ids);
					foreach ($idArray as $key => $value) {
						$res = $prayer_obj -> change_prayer_status($value,'approved');
					}
				$this->response['success'] = (strpos( $ids, ',' ) !== false) ?  __('Approved', $this->textdomain ) : __('Approved', $this->textdomain );
					
				}
				elseif ( 'disapprove' === $this->current_action() and ! empty( $ids ) ) {
					$modelFactory = new FactoryModelWPE();
					$prayer_obj = $modelFactory->create_object( 'prayer' );
					$idArray = explode(",",$ids);
					foreach ($idArray as $key => $value) {
						$res = $prayer_obj -> change_prayer_status($value,'disapproved');
					}
				$this->response['success'] = (strpos( $ids, ',' ) !== false) ?  __('Disapproved', $this->textdomain ) : __('Disapproved', $this->textdomain );
				
				}elseif ( 'private' === $this->current_action() and ! empty( $ids ) ) {
					$modelFactory = new FactoryModelWPE();
					$prayer_obj = $modelFactory->create_object( 'prayer' );
					$idArray = explode(",",$ids);
					foreach ($idArray as $key => $value) {
						$res = $prayer_obj -> change_prayer_status($value,'private');
					}
				$this->response['success'] = (strpos( $ids, ',' ) !== false) ?  __('private', $this->textdomain ) : __('Private', $this->textdomain );
				
				}
			}

		}
		// Minimal Configuration :)
		global $wpdb;
		$settings = unserialize(get_option('_wpe_prayer_engine_settings'));
		$columns   = array(
		'prayer_messages' 		=> __('Pray',WPE_TEXT_DOMAIN),
		'prayer_author' 		=> __('Name',WPE_TEXT_DOMAIN),
		'prayer_author_email' 		=> __('Email',WPE_TEXT_DOMAIN),
		'prayer_status' 		=> __('Status',WPE_TEXT_DOMAIN),
		'request_type' 			=> __('Type',WPE_TEXT_DOMAIN),
		'prayer_time' 			=> __('Date',WPE_TEXT_DOMAIN),
		'prayer_title' 			=> __('IP address',WPE_TEXT_DOMAIN),
		);
		$sortable  = array('prayer_messages','prayer_title','prayer_author','prayer_time','prayer_author_email','request_type','prayer_status','prayer_category','prayer_country');

	if(isset($settings['wpe_hide_prayer_count']) && $settings['wpe_hide_prayer_count'] =='false'){$columns['prayer_count'] = 'Count';}
    if(isset($settings['wpe_autoemail']) && $settings['wpe_autoemail']=='true'){$columns['prayer_lastname'] = 'Notify';}
    if(isset($settings['wpe_category']) && $settings['wpe_category'] =='true'){$columns['prayer_category'] = 'Category';}
	if(isset($settings['wpe_country']) && $settings['wpe_country']=='true'){$columns['prayer_country'] = 'Country';}
    
		$tableinfo = array(
		'table' => WPE_TBL_PRAYER,
		'textdomain' => WPE_TEXT_DOMAIN,
		'singular_label' => __('Prayer Request',WPE_TEXT_DOMAIN),
		'plural_label' => __('Prayer Request',WPE_TEXT_DOMAIN),
		'admin_listing_page_name' => 'wpe_manage_prayer',
		'admin_add_page_name' => 'wpe_form_prayer',
		'primary_col' => 'prayer_id',
		'columns' => $columns,
		'sortable' => $sortable,
		'per_page' => 20,
		'actions' => array( 'edit','delete'),
		'col_showing_links' => 'prayer_messages',
		'currenttimestamp_field' => 'prayer_time',
		'admin_view_page_name' => 'wpe_manage_prayers_performed',
		'bulk_actions' => array('approve'=>__('Approve',WPE_TEXT_DOMAIN),'disapprove'=>__('Disapprove',WPE_TEXT_DOMAIN),'private'=>__('private',WPE_TEXT_DOMAIN)),
		);
		return new WPE_Prayer_Table( $tableinfo );
	}
