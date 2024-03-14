<?php
class MyWorks_WC_Xero_Sync_Wc_Data_List extends MyWorks_WC_Xero_Sync_Core {
	
	# Dashboard
	public function get_dashboard_status_data(){
		global $wpdb;
		$data = array();
		
		$x_customer_count = (int) $wpdb->get_var("SELECT COUNT(*) FROM ".$this->gdtn('customers'));
		$x_product_count = (int) $wpdb->get_var("SELECT COUNT(*) FROM ".$this->gdtn('products'));
		
		$data['xero_initial_data_loaded'] = ($x_customer_count || $x_product_count)?true:false;
		
		$data['default_settings_saved'] = (!empty($this->get_option('mw_wc_xero_sync_default_xero_product')))?true:false;
		
		# WooCommerce data count
		$data['wc_total_customers'] = $this->count_wc_customers();
		$data['wc_total_products'] = $this->count_wc_products();
		$data['wc_total_variations'] = $this->count_wc_variations();
		
		# Mapping count
		$data['customer_mapped'] = (int) $wpdb->get_var("SELECT COUNT(*) FROM ".$this->gdtn('map_customers')." WHERE `X_C_ID` !='' ");
		
		$data['product_mapped'] = (int) $wpdb->get_var("SELECT COUNT(*) FROM ".$this->gdtn('map_products')." WHERE `X_P_ID` !='' ");
		
		$data['variation_mapped'] = (int) $wpdb->get_var("SELECT COUNT(*) FROM ".$this->gdtn('map_variations')." WHERE `X_P_ID` !='' ");
		
		$data['payment_gateways_mapped'] = (int) $wpdb->get_var("SELECT COUNT(*) FROM ".$this->gdtn('map_payment_method')." WHERE `X_ACC_ID` !='' ");
		
		$basic_mapping_done = ($data['customer_mapped'] && $data['product_mapped'] && $data['payment_gateways_mapped'])?true:false;
		$data['basic_mapping_done'] = $basic_mapping_done;
		
		return $data;
	}
	
	# Get wc product type by ID
	public function get_product_type_by_id($product_id){
		$pt = '';
		$product_id = (int) $product_id;
		if($product_id>0){
			#return (string) get_product_type($product_id);
			
			global $wpdb;
			$pt_q = "
			SELECT DISTINCT(p.ID), terms.name as wc_pt
			FROM ".$wpdb->posts." p			
			INNER JOIN {$wpdb->term_relationships} AS term_relationships ON p.ID = term_relationships.object_id
			INNER JOIN {$wpdb->term_taxonomy} AS term_taxonomy ON term_relationships.term_taxonomy_id = term_taxonomy.term_taxonomy_id
			INNER JOIN {$wpdb->terms} AS terms ON term_taxonomy.term_id = terms.term_id
			WHERE p.post_type =  'product'
			AND p.ID = %d
			AND term_taxonomy.taxonomy = 'product_type'
			";
			$pt_q = $wpdb->prepare($pt_q,$product_id);
			$pt_row = $this->get_row($pt_q);
			if(is_array($pt_row) && count($pt_row)){
				$pt = $pt_row['wc_pt'];
			}
			
			if(empty($pt)){
				$_product = wc_get_product( $product_id );
				if(is_object($_product) && !empty($_product)){
					if($_product->is_type( 'simple' )){
						$pt = 'simple';
					}elseif($_product->is_type( 'grouped' )){
						$pt = 'grouped';
					}elseif($_product->is_type( 'external' )){
						$pt = 'external';
					}elseif($_product->is_type( 'variable' )){
						$pt = 'variable';
					}elseif($_product->is_type( 'bundle' )){
						$pt = 'bundle';
					}
				}
			}
		}
		return $pt;
	}
	
	public function get_wc_active_payment_gateways(){
		$wc_apg_l = array();
		$pgl = WC()->payment_gateways()->payment_gateways;
		if(is_array($pgl) && count($pgl)){
			foreach($pgl as $key=>$value){
				if($value->enabled=='yes'){
					$wc_apg_l[$value->id] = $value->title;
				}		
			}
		}
		return $wc_apg_l;
	}
	
	public function get_wc_tax_rate_dropdown($wc_tax_rates,$selected='',$skip_rate_id='',$skip_rate_class='None'){
		echo '<option value=""></option>';
		if(is_array($wc_tax_rates) && count($wc_tax_rates)){
			foreach($wc_tax_rates as $rates){
				if($skip_rate_id!=$rates['tax_rate_id'] && $skip_rate_class!=$rates['tax_rate_class']){
					echo '<option  data-tax_rate_country="'.esc_attr($rates['tax_rate_country']).'"  data-tax_rate_state="'.esc_attr($rates['tax_rate_state']).'"  data-tax_rate="'.esc_attr($rates['tax_rate']).'"  data-tax_rate_name="'.esc_attr($rates['tax_rate_name']).'"  data-tax_rate_priority="'.esc_attr($rates['tax_rate_priority']).'"  data-tax_rate_compound="'.esc_attr($rates['tax_rate_compound']).'"  data-tax_rate_shipping="'.esc_attr($rates['tax_rate_shipping']).'" data-tax_rate_order="'.esc_attr($rates['tax_rate_order']).'" data-tax_rate_class="'.esc_attr($rates['tax_rate_class']).'" data-tax_rate_city="'.esc_attr($rates['location_code']).'" value="'.esc_attr($rates['tax_rate_id']).'">'.$this->escape($rates['tax_rate_name']).'</option>';
				}
			}
		}		
	}
	
	public function get_wc_tax_rate_id_array($wc_tax_rates){
		$tx_rate_arr = array();
		if(is_array($wc_tax_rates) && count($wc_tax_rates)){
			foreach($wc_tax_rates as $rates){
				$tx_rate_arr[$rates['tax_rate_id']] = $rates;
			}
		}
		return $tx_rate_arr;
	}
	
	public function get_wc_tax_rates_a_lc_add($wc_tax_rates_a){
		$tx_rate_arr = array();
		if(is_array($wc_tax_rates_a) && count($wc_tax_rates_a)){
			global $wpdb;
			$wtr_lt = $wpdb->prefix.'woocommerce_tax_rate_locations';
			foreach($wc_tax_rates_a as $k => $rates){
				$tax_rate_id  = (int) $rates['tax_rate_id'];
				$lc_a = $this->get_row(" SELECT `location_code` FROM {$wtr_lt} WHERE `tax_rate_id` = {$tax_rate_id} AND location_type = 'city' LIMIT 0,1");
				$location_code = '';
				if(is_array($lc_a) && !empty($lc_a)){
					$location_code = $lc_a['location_code'];
				}
				
				$rates['location_code'] = $location_code;
				$tx_rate_arr[$k] = $rates;
			}
		}
		return $tx_rate_arr;
	}
	
	# Customer List Function - Map / Push / Count
	public function count_wc_customers($search_txt='',$cl_role_search='',$cl_um_srch='') {
		return (int) $this->get_wc_customers(true,false,'',$search_txt,$cl_role_search,$cl_um_srch);
	}
	
	public function get_wc_customers($is_count=false,$list_page=false,$limit='',$search_txt='',$cl_role_search='',$cl_um_srch='') {
		global $wpdb;
		
		/* User role search start */
		$roles = ''; # Leave blank for all roles
			
		$cl_role_search = $this->sanitize($cl_role_search);
		if(!empty($cl_role_search)){
			$roles = $cl_role_search;
		}
		
		if(!is_array($roles) && !empty($roles)){
			$roles = array_map('trim',explode( ",", $roles ));
		}
		
		/* User role search end */
		
		$ext_join = '';
		$ext_whr = '';
		
		if(is_array($roles) && !empty($roles)){
			$ext_whr .= ' AND     (';
			$i = 1;
			foreach ( $roles as $role ) {
				$ext_whr .= ' ' . $wpdb->usermeta . '.meta_value    LIKE    \'%"' . $role . '"%\' ';
				if ( $i < count( $roles ) ) $ext_whr .= ' OR ';
				$i++;
			}
			$ext_whr .= ' ) ';
		}		
		
		/* Customer search start */
		$search_txt = $this->sanitize($search_txt);
		if($search_txt!=''){
			$mv_w = $wpdb->prepare("meta_value LIKE '%%%s%%'",$search_txt);
			$cs_gcq = "SELECT GROUP_CONCAT(DISTINCT(user_id)) AS c_ids FROM {$wpdb->usermeta} WHERE {$mv_w} AND meta_key IN('billing_company','first_name','last_name')";
			
			$st_a = explode(' ',$search_txt);
			if(is_array($st_a) && count($st_a) > 1){
				$cs_gcq = "
				SELECT GROUP_CONCAT(DISTINCT(um.user_id)) as c_ids
				FROM {$wpdb->usermeta} um 
				INNER JOIN {$wpdb->usermeta} um_f ON (um.user_id = um_f.user_id AND um_f.meta_key = 'first_name') 
				INNER JOIN {$wpdb->usermeta} um_l ON (um.user_id = um_l.user_id AND um_l.meta_key = 'last_name') 
				WHERE (um.meta_value LIKE '%%%s%%' AND um.meta_key = 'billing_company')
				OR um_f.meta_value LIKE '%%%s%%'
				OR um_l.meta_value LIKE '%%%s%%'
				OR CONCAT(um_f.meta_value,' ', um_l.meta_value) LIKE '%%%s%%' ";
				$cs_gcq = $wpdb->prepare($cs_gcq,$search_txt,$search_txt,$search_txt,$search_txt);
			}
			
			$s_c_ids = $wpdb->get_var($cs_gcq);
			$c_id_w = (!empty($s_c_ids))?" OR ".$wpdb->users.".ID IN ({$s_c_ids})":'';
			
			$ext_whr .= $wpdb->prepare(" AND (".$wpdb->users.".display_name LIKE '%%%s%%' OR ".$wpdb->users.".user_email LIKE '%%%s%%' OR ".$wpdb->users.".ID = %s {$c_id_w} ) ", $search_txt,$search_txt,$search_txt);			
		}
		
		/* Customer search end */
		
		# Main Query
		if($is_count){
			$sql = '
				SELECT  COUNT(DISTINCT(' . $wpdb->users . '.ID))
				FROM        ' . $wpdb->users . ' INNER JOIN ' . $wpdb->usermeta . '
				ON          ' . $wpdb->users . '.ID = ' . $wpdb->usermeta . '.user_id
				'.$ext_join.'
				WHERE       ' . $wpdb->usermeta . '.meta_key        =       \'' . $wpdb->prefix . 'capabilities\'				
			';
			$sql .= $ext_whr;
		}else{
			$sql = '
				SELECT  DISTINCT(' . $wpdb->users . '.ID) , ' . $wpdb->users . '.display_name, ' . $wpdb->users . '.user_email
				FROM        ' . $wpdb->users . ' INNER JOIN ' . $wpdb->usermeta . '
				ON          ' . $wpdb->users . '.ID = ' . $wpdb->usermeta . '.user_id
				'.$ext_join.'
				WHERE       ' . $wpdb->usermeta . '.meta_key        =       \'' . $wpdb->prefix . 'capabilities\'				
			';
			$sql .= $ext_whr;
			
			$orderby = $wpdb->users.'.display_name ASC';
			$sql .= ' ORDER BY  '.$orderby;
			
			if($limit!=''){
				$sql .= ' LIMIT  '.$limit;
			}
		}		
		
		#echo $sql;
		if($is_count){
			return $wpdb->get_var($sql);
		}else{
			$r_data = array();
			$q_data =  $this->get_data($sql);
			
			#echo $wpdb->num_rows;
			#$this->_p($q_data);
			
			if(is_array($q_data) && !empty($q_data)){
				foreach($q_data as $rd){
					$cu_tmp_arr = array();
					$cu_tmp_arr['ID'] = $rd['ID'];
					$cu_tmp_arr['display_name'] = $rd['display_name'];
					$cu_tmp_arr['user_email'] = $rd['user_email'];
					
					$c_meta = get_user_meta($rd['ID']);		
					$cu_tmp_arr['first_name'] = (is_array($c_meta) && isset($c_meta['first_name'][0]))?$c_meta['first_name'][0]:'';
					$cu_tmp_arr['last_name'] = (is_array($c_meta) && isset($c_meta['last_name'][0]))?$c_meta['last_name'][0]:'';
					
					$cu_tmp_arr['billing_company'] = (is_array($c_meta) && isset($c_meta['billing_company'][0]))?$c_meta['billing_company'][0]:'';
					
					$X_ContactID = '';$X_Name = '';$X_EmailAddress = '';
					
					$mt = $this->gdtn('map_customers');
					$mq = $wpdb->prepare("SELECT X_C_ID FROM {$mt} WHERE W_C_ID = %d AND X_C_ID != ''",$rd['ID']);
					$md =  $this->get_row($mq);
					if(is_array($md) && !empty($md) && !empty($md['X_C_ID'])){
						$X_C_ID = $md['X_C_ID'];
						$xct = $this->gdtn('customers');
						$xcq = $wpdb->prepare("SELECT Name, EmailAddress FROM {$xct} WHERE ContactID = %s",$X_C_ID);
						$xcd =  $this->get_row($xcq);
						if(is_array($xcd) && !empty($xcd)){
							$X_ContactID = $X_C_ID;
							$X_Name = $xcd['Name'];
							$X_EmailAddress = $xcd['EmailAddress'];
						}
					}
					
					$cu_tmp_arr['X_ContactID'] = $X_ContactID;
					$cu_tmp_arr['X_Name'] = $X_Name;
					$cu_tmp_arr['X_EmailAddress'] = $X_EmailAddress;
					
					$r_data[] = $cu_tmp_arr;
				}
			}
			
			unset($q_data);
			#$this->_p($r_data);
			return $r_data;	
		}		
	}
	
	# Product List Function - Map / Push / Count
	public function count_wc_products($is_inventory=false,$search_txt='',$stock_status='',$product_cat_search=0,$p_type='',$product_um_srch=''){
		return (int) $this->get_wc_products(true,'',$is_inventory,$search_txt,$stock_status,$product_cat_search,$p_type,$product_um_srch);
	}
	
	public function get_wc_products($is_count=false,$limit='',$is_inventory=false,$search_txt='',$stock_status='',$product_cat_search=0,$p_type='',$product_um_srch=''){
		global $wpdb;
		
		$ext_join = '';
		$ext_sql = '';
		
		/*Inventory product type*/
		if($is_inventory){
			$ext_join.= " INNER JOIN ".$wpdb->postmeta." pm8 ON ( pm8.post_id = p.ID	AND pm8.meta_key =  '_manage_stock') ";
			$ext_sql.= " AND pm8.meta_value='yes' ";
		}
		
		/*Product search by SKU, name*/
		$search_txt = $this->sanitize($search_txt);
		if($search_txt!=''){
			$ext_join.= "LEFT JOIN ".$wpdb->postmeta." pm1 ON ( pm1.post_id = p.ID AND pm1.meta_key =  '_sku' ) ";
			$ext_sql.=" AND ( p.ID = %d OR p.post_title LIKE '%%%s%%' OR pm1.meta_value LIKE '%%%s%%' ) ";
			$ext_sql = $wpdb->prepare($ext_sql,$search_txt,$search_txt,$search_txt);
		}
		
		/*Stock status search*/
		$stock_status = $this->sanitize($stock_status);
		if($stock_status!=''){
			$ext_join.= " INNER JOIN ".$wpdb->postmeta." pm7 ON ( pm7.post_id = p.ID AND pm7.meta_key =  '_stock_status' ) ";
			$ext_sql.= " AND pm7.meta_value='{$stock_status}' ";
			
		}
		
		/*Product category search*/
		$product_cat_search = (int) $product_cat_search;
		if($product_cat_search>0){
			$ext_join.= " 
			JOIN   {$wpdb->term_relationships} TR ON p.ID=TR.object_id 
			JOIN   {$wpdb->term_taxonomy} T ON TR.term_taxonomy_id=T.term_taxonomy_id
			JOIN  {$wpdb->terms} TS ON T.term_id = TS.term_id
			";
			$ext_sql.= " AND  T.taxonomy = 'product_cat' AND T.term_id = {$product_cat_search} ";
		}
		
		/*Product type search*/
		$p_type = $this->sanitize($p_type);
		if($p_type!='' && $p_type != 'all'){
			$pt_jt = 'INNER';
			$ext_join.= "
				{$pt_jt} JOIN {$wpdb->term_relationships} AS term_relationships ON p.ID = term_relationships.object_id
				{$pt_jt} JOIN {$wpdb->term_taxonomy} AS term_taxonomy ON term_relationships.term_taxonomy_id = term_taxonomy.term_taxonomy_id
				{$pt_jt} JOIN {$wpdb->terms} AS terms ON term_taxonomy.term_id = terms.term_id
			";
			if($p_type=='simple'){
				$ext_sql.= " AND term_taxonomy.taxonomy = 'product_type' AND (terms.slug = '{$p_type}' OR terms.slug = '' OR terms.slug IS NULL)";				
			}else{
				$ext_sql.= " AND term_taxonomy.taxonomy = 'product_type' AND terms.slug = '{$p_type}'";
			}			
		}
		
		/*Product map, un-mapped search*/
		$product_um_srch = $this->sanitize($product_um_srch);
		if($product_um_srch == 'only_m'){
			$ext_join.= " 
			INNER JOIN " . $this->gdtn('map_products') . " pmap ON (p.ID = pmap.W_P_ID AND pmap.X_P_ID != '')
			";
		}
		
		if($product_um_srch == 'only_um'){
			$ext_sql.= " AND p.ID NOT IN(SELECT W_P_ID FROM " . $this->gdtn('map_products') . " WHERE X_P_ID != '')";
		}
		
		/*Hide variable parent product*/
		if($this->option_checked('mw_wc_qbo_desk_hide_vpp_fmp_pages') && empty($p_type)){
			$ext_sql.= " AND p.ID NOT IN(SELECT post_parent FROM {$wpdb->posts} WHERE post_type = 'product_variation' AND post_parent>0) ";
		}
		
		# Main Query
		if($is_count){
			$sql = "SELECT COUNT(DISTINCT(p.ID)) ";
		}else{
			$sql = "SELECT DISTINCT(p.ID), p.post_title AS name ";
		}
		
		$sql.= "FROM ".$wpdb->posts." p
			{$ext_join}
			WHERE p.post_type =  'product'
			AND p.post_status NOT IN('trash','auto-draft','inherit')
			{$ext_sql}
		";
		
		if(!$is_count){
			$orderby = 'p.post_title ASC';
			$sql .= ' ORDER BY  '.$orderby;
			
			$limit = $this->sanitize($limit);
			if($limit!=''){
				$sql .= ' LIMIT  '.$limit;
			}
		}
		
		#echo $sql;
		if($is_count){
			return $wpdb->get_var($sql);
		}else{
			$r_data = array();
			$q_data =  $this->get_data($sql);
			
			if(is_array($q_data) && !empty($q_data)){
				foreach($q_data as $rd){
					$pd_tmp_arr = array();
					$pd_tmp_arr['ID'] = $rd['ID'];
					$pd_tmp_arr['name'] = $this->sanitize($rd['name']);
					
					$p_meta = get_post_meta($rd['ID']);
					$pd_tmp_arr['sku'] = (is_array($p_meta) && isset($p_meta['_sku'][0]))?$p_meta['_sku'][0]:'';
					$pd_tmp_arr['regular_price'] = (is_array($p_meta) && isset($p_meta['_regular_price'][0]))?$p_meta['_regular_price'][0]:'';
					$pd_tmp_arr['sale_price'] = (is_array($p_meta) && isset($p_meta['_sale_price'][0]))?$p_meta['_sale_price'][0]:'';
					$pd_tmp_arr['price'] = (is_array($p_meta) && isset($p_meta['_price'][0]))?$p_meta['_price'][0]:'';
					$pd_tmp_arr['stock'] = (is_array($p_meta) && isset($p_meta['_stock'][0]))?$p_meta['_stock'][0]:'';
					$pd_tmp_arr['backorders'] = (is_array($p_meta) && isset($p_meta['_backorders'][0]))?$p_meta['_backorders'][0]:'';
					$pd_tmp_arr['stock_status'] = (is_array($p_meta) && isset($p_meta['_stock_status'][0]))?$p_meta['_stock_status'][0]:'';
					$pd_tmp_arr['manage_stock'] = (is_array($p_meta) && isset($p_meta['_manage_stock'][0]))?$p_meta['_manage_stock'][0]:'';
					$pd_tmp_arr['total_sales'] = (is_array($p_meta) && isset($p_meta['total_sales'][0]))?$p_meta['total_sales'][0]:'';				
					
					$pd_tmp_arr['wc_product_type'] = $this->get_product_type_by_id($rd['ID']);
					
					#Xero Data
					$X_ItemID = '';$X_Name = '';$X_Code = '';$X_IsTrackedAsInventory=0;
					
					$mt = $this->gdtn('map_products');
					$mq = $wpdb->prepare("SELECT X_P_ID FROM {$mt} WHERE W_P_ID = %d AND X_P_ID != ''",$rd['ID']);
					$md =  $this->get_row($mq);
					if(is_array($md) && !empty($md) && !empty($md['X_P_ID'])){
						$X_P_ID = $md['X_P_ID'];
						$xpt = $this->gdtn('products');
						$xcq = $wpdb->prepare("SELECT Name, Code, IsTrackedAsInventory FROM {$xpt} WHERE ItemID = %s",$X_P_ID);
						$xcd =  $this->get_row($xcq);
						if(is_array($xcd) && !empty($xcd)){
							$X_ItemID = $X_P_ID;
							$X_Name = $xcd['Name'];
							$X_Code = $xcd['Code'];
							$X_IsTrackedAsInventory = $xcd['IsTrackedAsInventory'];
						}
					}
					
					$pd_tmp_arr['X_ItemID'] = $X_ItemID;
					$pd_tmp_arr['X_Name'] = $X_Name;
					$pd_tmp_arr['X_Code'] = $X_Code;
					$pd_tmp_arr['X_IsTrackedAsInventory'] = $X_IsTrackedAsInventory;
					
					$r_data[] = $pd_tmp_arr;
				}
			}
			
			unset($q_data);
			#$this->_p($r_data);
			return $r_data;
		}
	}
	
	# Variation List Function - Map / Push / Count
	public function count_wc_variations($is_inventory=false,$search_txt='',$stock_status='',$product_um_srch=''){
		return (int) $this->get_wc_variations(true,'',$is_inventory,$search_txt,$stock_status,$product_um_srch);
	}
	
	public function get_wc_variations($is_count=false,$limit='',$is_inventory=false,$search_txt='',$stock_status='',$variation_um_srch=''){
		global $wpdb;
		
		$ext_join = '';
		$ext_sql = '';
		
		/*Inventory variation type*/
		if($is_inventory){
			$ext_join.= " INNER JOIN ".$wpdb->postmeta." pm8 ON ( pm8.post_id = p.ID	AND pm8.meta_key =  '_manage_stock') ";
			$ext_sql.= " AND pm8.meta_value='yes' ";
		}
		
		/*Variation search by SKU, name*/
		$search_txt = $this->sanitize($search_txt);
		if($search_txt!=''){
			$ext_join.= "LEFT JOIN ".$wpdb->postmeta." pm1 ON ( pm1.post_id = p.ID AND pm1.meta_key =  '_sku' ) ";
			$ext_sql.=" AND ( p.ID = %d OR p.post_title LIKE '%%%s%%' OR pm1.meta_value LIKE '%%%s%%' ) ";
			$ext_sql = $wpdb->prepare($ext_sql,$search_txt,$search_txt,$search_txt);
		}
		
		/*Stock status search*/
		$stock_status = $this->sanitize($stock_status);
		if($stock_status!=''){
			$ext_join.= " INNER JOIN ".$wpdb->postmeta." pm7 ON ( pm7.post_id = p.ID AND pm7.meta_key =  '_stock_status' ) ";
			$ext_sql.= " AND pm7.meta_value='{$stock_status}' ";
			
		}
		
		/*Variation map, un-mapped search*/
		$variation_um_srch = $this->sanitize($variation_um_srch);
		if($variation_um_srch == 'only_m'){
			$ext_join.= " 
			INNER JOIN " . $this->gdtn('map_variations') . " pmap ON (p.ID = pmap.W_V_ID AND pmap.X_P_ID != '')
			";
		}
		
		if($variation_um_srch == 'only_um'){
			$ext_sql.= " AND p.ID NOT IN(SELECT W_V_ID FROM " . $this->gdtn('map_variations') . " WHERE X_P_ID != '')";
		}
		
		# Main Query
		if($is_count){
			$sql = "SELECT COUNT(DISTINCT(p.ID)) ";
		}else{
			$sql = "SELECT DISTINCT(p.ID), p.post_title AS name, p.post_parent as parent_id, p.post_name ";
		}
		
		$sql.= "FROM ".$wpdb->posts." p
			{$ext_join}
			WHERE p.post_type =  'product_variation'
			AND p.post_status NOT IN('trash','auto-draft','inherit')
			{$ext_sql}
		";
		
		if(!$is_count){
			$orderby = 'p.post_title ASC';
			$sql .= ' ORDER BY  '.$orderby;
			
			$limit = $this->sanitize($limit);
			if($limit!=''){
				$sql .= ' LIMIT  '.$limit;
			}
		}
		
		#echo $sql;
		if($is_count){
			return $wpdb->get_var($sql);
		}else{
			$r_data = array();
			$q_data =  $this->get_data($sql);
			
			if(is_array($q_data) && !empty($q_data)){
				foreach($q_data as $rd){
					$pd_tmp_arr = array();
					$pd_tmp_arr['ID'] = $rd['ID'];
					$pd_tmp_arr['name'] = $this->sanitize($rd['name']);
					
					$pd_tmp_arr['post_name'] = $rd['post_name'];
					$pd_tmp_arr['parent_id'] = $rd['parent_id'];
					
					$p_meta = get_post_meta($rd['ID']);
					$pd_tmp_arr['sku'] = (is_array($p_meta) && isset($p_meta['_sku'][0]))?$p_meta['_sku'][0]:'';
					$pd_tmp_arr['regular_price'] = (is_array($p_meta) && isset($p_meta['_regular_price'][0]))?$p_meta['_regular_price'][0]:'';
					$pd_tmp_arr['sale_price'] = (is_array($p_meta) && isset($p_meta['_sale_price'][0]))?$p_meta['_sale_price'][0]:'';
					$pd_tmp_arr['price'] = (is_array($p_meta) && isset($p_meta['_price'][0]))?$p_meta['_price'][0]:'';
					$pd_tmp_arr['stock'] = (is_array($p_meta) && isset($p_meta['_stock'][0]))?$p_meta['_stock'][0]:'';
					$pd_tmp_arr['backorders'] = (is_array($p_meta) && isset($p_meta['_backorders'][0]))?$p_meta['_backorders'][0]:'';
					$pd_tmp_arr['stock_status'] = (is_array($p_meta) && isset($p_meta['_stock_status'][0]))?$p_meta['_stock_status'][0]:'';
					$pd_tmp_arr['manage_stock'] = (is_array($p_meta) && isset($p_meta['_manage_stock'][0]))?$p_meta['_manage_stock'][0]:'';
					$pd_tmp_arr['total_sales'] = (is_array($p_meta) && isset($p_meta['total_sales'][0]))?$p_meta['total_sales'][0]:'';
					
					#Attributes
					$attribute_names = '';
					$attribute_names_arr = array();
					
					$attribute_values = '';
					$attribute_values_arr = array();
					
					if(is_array($p_meta) && count($p_meta)){
						foreach($p_meta as $pm_k => $pm_v){
							if($this->start_with($pm_k,'attribute_')){
								$attribute_names_arr[] = $pm_k;
								$attribute_values_arr[] = (isset($pm_v[0]))?$pm_v[0]:'';
							}
						}
					}
					
					if(count($attribute_names_arr) && count($attribute_values_arr)){
						$attribute_names = implode(',',$attribute_names_arr);
						$attribute_values = implode(',',$attribute_values_arr);
					}
					
					$pd_tmp_arr['attribute_names'] = $attribute_names;
					$pd_tmp_arr['attribute_values'] = $attribute_values;
					
					$parent_name = '';
					if($rd['parent_id']>0){
						$parent_id = (int) $rd['parent_id'];
						$parent_name = $this->get_field_by_val($wpdb->posts,'post_title','ID',$parent_id);
					}
					
					$pd_tmp_arr['parent_name'] = $this->sanitize($parent_name);
					
					#Xero Data
					$X_ItemID = '';$X_Name = '';$X_Code = '';$X_IsTrackedAsInventory=0;
					
					$mt = $this->gdtn('map_variations');
					$mq = $wpdb->prepare("SELECT X_P_ID FROM {$mt} WHERE W_V_ID = %d AND X_P_ID != ''",$rd['ID']);
					$md =  $this->get_row($mq);
					if(is_array($md) && !empty($md) && !empty($md['X_P_ID'])){
						$X_P_ID = $md['X_P_ID'];
						$xpt = $this->gdtn('products');
						$xcq = $wpdb->prepare("SELECT Name, Code, IsTrackedAsInventory FROM {$xpt} WHERE ItemID = %s",$X_P_ID);
						$xcd =  $this->get_row($xcq);
						if(is_array($xcd) && !empty($xcd)){
							$X_ItemID = $X_P_ID;
							$X_Name = $xcd['Name'];
							$X_Code = $xcd['Code'];
							$X_IsTrackedAsInventory = $xcd['IsTrackedAsInventory'];
						}
					}
					
					$pd_tmp_arr['X_ItemID'] = $X_ItemID;
					$pd_tmp_arr['X_Name'] = $X_Name;
					$pd_tmp_arr['X_Code'] = $X_Code;
					$pd_tmp_arr['X_IsTrackedAsInventory'] = $X_IsTrackedAsInventory;
					
					$r_data[] = $pd_tmp_arr;
				}
			}
			
			unset($q_data);
			#$this->_p($r_data);
			return $r_data;
		}
	}
	
	# Order List Function - Map / Push / Count
	public function count_wc_order_list($search_txt='',$date_from='',$date_to='',$status=''){
		return (int) $this->get_wc_order_list(true,'',$search_txt,$date_from,$date_to,$status);
	}
	
	public function get_wc_order_list($is_count=false,$limit='',$search_txt='',$date_from='',$date_to='',$status=''){
		global $wpdb;
		
		$ext_sql = '';
		$ext_join = '';
		
		#$onc_mf = $this->get_woo_ord_number_key_field();
		$onc_mf = '';
		
		$ldl = false;
		if($ldl){
			$wp_date_time_c = $this->now();
			$ld = 30;
			$l_days_dt = date('Y-m-d H:i:s', strtotime('-'.$ld.' days', strtotime($wp_date_time_c)));
			$ext_sql = " AND p.post_date BETWEEN '{$l_days_dt}' AND '{$wp_date_time_c}' ";
		}
		
		# Search
		$search_txt = $this->sanitize($search_txt);
		
		if($search_txt!=''){
			$ext_join .="
			LEFT JOIN ".$wpdb->postmeta." pm1
			ON ( pm1.post_id = p.ID AND pm1.meta_key =  '_billing_first_name' )
			LEFT JOIN ".$wpdb->postmeta." pm2
			ON ( pm2.post_id = p.ID AND pm2.meta_key =  '_billing_last_name' )
			LEFT JOIN ".$wpdb->postmeta." pm7
			ON ( pm7.post_id = p.ID AND pm7.meta_key =  '_billing_company' )
			";
			
			if(!empty($onc_mf)){
				$ext_join .="
				LEFT JOIN ".$wpdb->postmeta." pm10
				ON ( pm10.post_id = p.ID AND pm10.meta_key =  '{$onc_mf}' )
				";
			}
			
			if(!empty($onc_mf)){
				$ext_sql .=$wpdb->prepare(" AND ( pm1.meta_value LIKE '%%%s%%' OR pm2.meta_value LIKE '%%%s%%' OR pm7.meta_value LIKE '%%%s%%' OR CONCAT(pm1.meta_value,' ', pm2.meta_value) LIKE '%%%s%%'  OR p.ID = %s OR pm10.meta_value = %s ) ",$search_txt,$search_txt,$search_txt,$search_txt,$search_txt,$search_txt);
			}else{
				$ext_sql .=$wpdb->prepare(" AND ( pm1.meta_value LIKE '%%%s%%' OR pm2.meta_value LIKE '%%%s%%' OR pm7.meta_value LIKE '%%%s%%' OR CONCAT(pm1.meta_value,' ', pm2.meta_value) LIKE '%%%s%%' OR p.ID = %s ) ",$search_txt,$search_txt,$search_txt,$search_txt,$search_txt);
			}
			
		}
		
		/*Order status search*/
		$status = $this->sanitize($status);
		if($status!=''){
			$ext_sql .=$wpdb->prepare(" AND p.post_status = %s",$status);
		}
		
		/*Order date search*/
		$date_from = $this->sanitize($date_from);
		if($date_from!=''){
			$ext_sql .=" AND p.post_date>='".$date_from." 00:00:00'";
		}

		$date_to = $this->sanitize($date_to);
		if($date_to!=''){
			$ext_sql .=" AND p.post_date<='".$date_to." 23:59:59'";
		}
		
		# Main Query
		if($is_count){
			$sql = "SELECT COUNT(DISTINCT(p.ID)) ";
		}else{
			$sql = "SELECT DISTINCT(p.ID), p.post_status, p.post_date ";
		}
		
		$sql .= "FROM {$wpdb->prefix}posts as p
			{$ext_join}
			WHERE
			p.post_type = 'shop_order'
			AND p.post_status NOT IN('trash','auto-draft','inherit')
			{$ext_sql}
		";
		
		if(!$is_count){
			$orderby = 'p.post_date DESC';
			$sql .= ' ORDER BY  '.$orderby;
			
			$limit = $this->sanitize($limit);
			if($limit!=''){
				$sql .= ' LIMIT  '.$limit;
			}
		}
		
		#echo $sql;
		if($is_count){
			return $wpdb->get_var($sql);
		}else{
			$r_data = array();
			$q_data =  $this->get_data($sql);
			
			if(is_array($q_data) && !empty($q_data)){
				foreach($q_data as $rd){
					$od_tmp_arr = array();					
					$od_tmp_arr['ID'] = $rd['ID'];
					$od_tmp_arr['post_status'] = $rd['post_status'];
					$od_tmp_arr['post_date'] = $rd['post_date'];
					
					$o_meta = get_post_meta($rd['ID']);
					if(!is_array($o_meta)){
						$o_meta = array();
					}
					
					$od_tmp_arr['billing_first_name'] = (isset($o_meta['_billing_first_name'][0]))?$o_meta['_billing_first_name'][0]:'';
					$od_tmp_arr['billing_last_name'] = (isset($o_meta['_billing_last_name'][0]))?$o_meta['_billing_last_name'][0]:'';
					$od_tmp_arr['billing_company'] = (isset($o_meta['_billing_company'][0]))?$o_meta['_billing_company'][0]:'';
					
					$od_tmp_arr['order_total'] = (isset($o_meta['_order_total'][0]))?$o_meta['_order_total'][0]:'';
					$od_tmp_arr['order_key'] = (isset($o_meta['_order_key'][0]))?$o_meta['_order_key'][0]:'';
					$od_tmp_arr['customer_user'] = (isset($o_meta['_customer_user'][0]))?$o_meta['_customer_user'][0]:'';
					$od_tmp_arr['order_currency'] = (isset($o_meta['_order_currency'][0]))?$o_meta['_order_currency'][0]:'';				
					$od_tmp_arr['payment_method'] = (isset($o_meta['_payment_method'][0]))?$o_meta['_payment_method'][0]:'';
					$od_tmp_arr['payment_method_title'] = (isset($o_meta['_payment_method_title'][0]))?$o_meta['_payment_method_title'][0]:'';
					
					# Order Number					
					if(!empty($onc_mf)){
						$od_tmp_arr[$onc_mf] = (isset($o_meta[$onc_mf][0]))?$o_meta[$onc_mf][0]:'';
					}					
					
					$r_data[] = $od_tmp_arr;
				}
			}
			
			unset($q_data);
			#$this->_p($r_data);
			return $r_data;	
		}
	}
	
	# End
}