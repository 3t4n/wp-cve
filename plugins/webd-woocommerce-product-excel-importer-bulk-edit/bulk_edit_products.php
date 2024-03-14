<?php

class WebdWoocommerceBProducts{

	public $id;
	public $title;
	public $description;
	public $excerpt;
	public $category;
	public $tag;	
	public $sku;
	public $categories;
	public $tags;
	public $regular_price;
	public $sale_price;
	public $stock;
	public $stock_status;	
	public $length;
	public $width;
	public $height;	
	public $regular_price_from;
	public $regular_price_to;
	public $sale_price_from;
	public $sale_price_to;
	public $keyword;
	public $sale_price_selector;
	public $price_selector;
	public $term;
	
	

	public function vocabularySelect(){
				$taxonomy_objects = get_object_taxonomies( 'product', 'objects' );	
				print "<form id='selectTaxonomy' action= ".admin_url( 'admin.php?page=webd-woocommerce-product-excel-importer-bulk-edit&tab=search-edit' )." >
				<select name='vocabularySelect' id='vocabularySelect'>";
				print "<option value='' >".esc_html__("Select..","webd_bulk_edit")."</option>";
				foreach( $taxonomy_objects as $voc){
					if($voc->name !='product_type'){ ?>
						<option value='<?php print esc_attr($voc->name);?> '><?php print esc_attr($voc->name) ;?></option>
					<?php
					}
				}
				print "<select>";
	}
	
	public function editProductsDisplay(){
	?>
		<h2><?php esc_html_e("EDIT SIMPLE PRODUCTS","webd_bulk_edit");?></h2>	
		<?php
			$query = new WP_Query( array(
				'post_type' => 'product',				
				'posts_per_page' => '1',								
				) );
		if($query ->have_posts()){	

		?>
				<p>
					<span class='toggler button button-primary  '>
						<?php esc_html_e("Filter Products","webd_bulk_edit");?> <i class='fa fa-filter '></i>
					</span>  
					<span class='togglerMassive button button-secondary warning btn btn-danger '>
						<?php esc_html_e("Massive Update","webd_bulk_edit");?> <i class='fa fa-edit '></i>
					</span>
				</p>
				
				<form id='selectTaxonomy' action= "<?php echo admin_url( 'admin.php?page=webd-woocommerce-product-excel-importer-bulk-edit&tab=search-edit' ); ?>" >
					<table class='wp-list-table widefat fixed table table-bordered'>	
						<tr>
							<td><?php esc_html_e("Choose Taxonomy","webd_bulk_edit");?></td>
							<td><?php  $this->vocabularySelect() ; ?></td>
						</tr>
					</table>
				</form>
				
				<form name='editProductDisplay' id='editProductDisplay' method='post' action= "<?php echo admin_url( 'admin.php?page=webd-woocommerce-product-excel-importer-bulk-edit&tab=search-edit' ); ?>" >
					<table class='wp-list-table widefat fixed table table-bordered'>	
						<tr class='vocabularySelect'>
						
						<?php if( isset( $_REQUEST['vocabularySelect'] ) ){	?>		
							<td><?php esc_html_e("Tax Terms","webd_bulk_edit");?></td>
							<td>
							<input type='hidden' name ='vocabularySelect' value='<?php print sanitize_text_field($_REQUEST['vocabularySelect']); ?>' />
							<?php 
							$terms = get_terms( sanitize_text_field($_REQUEST['vocabularySelect'], array(
								'hide_empty' => true,
							) ));					
							if(count($terms) <1 ){
								print "<p class='warning rightToLeft'>No terms on products for ".sanitize_text_field($_REQUEST['vocabularySelect'])."</p>";	
							}else{
								print "<select id='taxTerm' name='taxTerm'>";
								print "<option value='' >".esc_html__("Choose Terms..","webd_bulk_edit")."</option>";
								foreach($terms as $term){
									print "<option value='".$term->term_id."' >".sanitize_text_field($term->name)."</option>";
								}
								print "</select>";					
							}
							print "</td>";
						} ?></tr>

							<tr>
								<td><?php esc_html_e("Keywords","webd_bulk_edit");?></td>
								<td><input type='text' name='keyword' id='keyword' placeholder='Search term'/></td>
							</tr>
							<tr>
								<td><?php esc_html_e("SKU","webd_bulk_edit");?></td>
								<td><input type='text' name='sku' id='sku' placeholder='Search by SKU'/></td>
							</tr>
							<tr>
								<td><?php esc_html_e("Regular Price","webd_bulk_edit");?></td><td><input type='number' name='regular_price' id='regular_price' placeholder='Regular Price'/></td>
								<td><?php esc_html_e("Regular Price Selector","webd_bulk_edit");?></td>
							<td>
								<select name='price_selector' id='price_selector'>
									<option value=">">></option>
									<option value=">=">>=</option>
									<option value="<="><=</option>
									<option value="<"><</option>
									<option value="==">==</option>
									<option value="!=">!=</option>
								</select>
							</td>
							</tr>
							<tr>
								<td><?php esc_html_e("Sale Price","webd_bulk_edit");?></td>
								<td><input type='number' name='sale_price' id='sale_price' placeholder='Sale Price'/></td>								
								<td><?php esc_html_e("Sale Price Selector","webd_bulk_edit");?></td>
								<td>
									<select name='sale_price_selector' id='sale_price_selector' >
									<option value=">">></option>
									<option value=">=">>=</option>
									<option value="<="><=</option>
									<option value="<"><</option>
									<option value="==">==</option>
									<option value="!=">!=</option>
									</select>	
								</td>
							</tr>
							<tr>
								<td><?php esc_html_e("Products Per Page","webd_bulk_edit");?> </td><td><input type='number' min="1" max="100" style='width:100%;' required name='posts_per_page' id='posts_per_page' placeholder='100 max' value='100'/></td><td></td><td></td>
							</tr>
							
							<tr>
								<td><?php esc_html_e("Offset Products","webd_bulk_edit");?></td><td><input type='number' name='offset' min="1" max="100" style='width:100%;' id='offset' placeholder='Start from..'/></td>
								<td></td><td></td>
							</tr>
							
						<tr>
						
						<?php $taxonomy_objects = get_object_taxonomies( 'product', 'objects' ); ?>
					</table>
					<table class='wp-list-table widefat fixed table table-bordered'>
					<legend>
						<h2><?php esc_html_e("TAXONOMIES TO SHOW","webd_bulk_edit");?></h2>
					</legend>
					<?php

					print "<tr>";
					$cols = array();
					$checked = 'checked';
					foreach( $taxonomy_objects as $voc){
						if($voc->name =='product_cat' || $voc->name =='product_tag' || $voc->name =='product_type' ){
							print "<td> 
							<input type='checkbox' class='fieldsToShow' ".$checked." name='toShow".esc_attr($voc->name)."' value='1'/>
							<label for='".str_replace('_',' ',esc_attr($voc->name))."'>". str_replace('_',' ',esc_attr($voc->name)). "</label>
							</td>";
							array_push($cols,esc_attr($voc->name));							
						}else{
							print "<td> 
							<input type='checkbox' class='fieldsToShow warning proOnly' name='".esc_attr($voc->name)."' disabled />
							<label for='".str_replace('_',' ',esc_attr($voc->name))."' class='proOnly'>". str_replace('_',' ',esc_attr($voc->name)). "</label>
							</td>";	
							array_push($cols,esc_attr($voc->name));							
						}
					}
					print "</tr>
					</table>";	
					?>
					<table class='wp-list-table widefat fixed table table-bordered'>
						<legend><h2><?php esc_html_e("FIELDS TO SHOW","webd_bulk_edit");?></h2></legend>
						<?php $cols = array("title","description",'_sku','_regular_price','_sale_price','_weight','_stock','_stock_status','_width','_length','_height');	
						print "<tr>";
						
						foreach( $cols as $col){	
								print "<td>
									<input type='checkbox' class='fieldsToShow' ".$checked." name='toShow".$col."' value='1'/>
									<label for='".$col."'>". $col. "</label>
								</td>";	
						}
						print "</tr>
					</table>";	?>				
								
						<input type='hidden' name='searchProductList' value='1'  />
						<?php 
						wp_nonce_field('searchProductList'); 
						submit_button( esc_html__("Search","webd_bulk_edit"),'primary','Search'); 
						?>		
				</form>
			<?php
			$this->updateMassiveForm();		
			?><div class='result'><?php $this->editProducts(); ?></div><?php
			
		}else print "<p class='warning'>".esc_html__("No products found","webd_bulk_edit").".</p>";		

	
	}
	
	
	public function editProducts(){
		
		if($_SERVER['REQUEST_METHOD'] == 'POST' && current_user_can('administrator') && $_REQUEST['searchProductList'] ){
			
			check_admin_referer( 'searchProductList' );
			check_ajax_referer( 'searchProductList' );	
			$cat_query = [];

		$taxonomy_objects = get_object_taxonomies( 'product', 'objects' );	
		//print $taxonomy_objects['name'];		
		$cat_query;
			if( isset( $_POST['taxTerm'] ) && !empty($_POST['vocabularySelect']) && !empty($_POST['taxTerm'] ) ){
				$this->category = sanitize_text_field( $_POST['vocabularySelect'] );
				$this->term = sanitize_text_field( $_POST['taxTerm'] );
					$cat_query = [
						[
							'taxonomy' => $this->category,
							'field'    => 'id',
							'terms'    => $this->term

						]
					];
			}
			
			$meta_query = [];
			//$meta_query = array('relation' => 'OR');
			$this->price_selector = $_POST['price_selector'] ;	
			$this->sale_price_selector =  $_POST['sale_price_selector'] ;			
			
			if(!empty($_POST['sku'])){ 
				$sku = array('key'     => '_sku','value'   => $this->sku,'compare' => 'LIKE');
				$this->sku = sanitize_text_field( $_POST['sku'] );
				array_push($meta_query,$sku );
			}else $this->sku='';
			if(!empty($_POST['keyword']))  $this->keyword = sanitize_text_field( $_POST['keyword'] );	
			if(!empty($_POST['regular_price'])){ 
				$this->regular_price = (int) $_POST['regular_price'];
				$regular = array('key'     => '_regular_price','value'   => $this->regular_price,'type' => 'numeric','compare' => $this->price_selector);
				array_push($meta_query,$regular );
			}else $this->regular_price='';	
			if(!empty($_POST['sale_price'])){
				$this->sale_price = (int)$_POST['sale_price'] ;
				$price = array('key'     => '_sale_price','value'   => $this->sale_price,'type' => 'numeric','compare' => $this->sale_price_selector);
				
				//array_push($meta_query,$price );
			}else $this->sale_price='';
			
			if(!empty($_POST['posts_per_page'])){
				$this->posts_per_page = (int)$_POST['posts_per_page'] ;
			}else $this->posts_per_page = '-1';
			
			if(!empty($_POST['offset'])){
				$this->offset = (int)$_POST['offset'] ;
			}else $this->offset = '-1';
			
			$query = new WP_Query( array(
				'post_type' => 'product',
				's' => $this->keyword,
				'meta_and_tax' => TRUE,
				'tax_query'  => $cat_query,			
				'meta_query' => $meta_query,				
				'posts_per_page' => $this->posts_per_page,	
				'offset' => $this->offset,
				) );

		
				//	===== START OF WHAT IS WORKING 
				if ( $query ->have_posts() ){ 
								
				$column_name = array("TITLE","DESCRIPTION"," SKU"," REGULAR PRICE"," SALE PRICE"," WEIGHT"," STOCK"," STOCK STATUS"," WIDTH"," LENGTH"," HEIGHT");	
				$post_meta = array('_sku','_regular_price','_sale_price','_weight','_stock','_stock_status','_width','_length','_height');							
				$id_Array = array();
				$vid_Array = array();	

							?>
				<div class='table-wrapper' ><div class='table table-bordered convertToTable'>			
							<div class='tableHead'>
							<div><input type="checkbox" class='selectAll'/></div><div></div>
							<?php
										
								$taxonomy_objects = get_object_taxonomies( 'product', 'objects' );
								
								foreach($taxonomy_objects as $tax){									
									if(!strstr($tax->name,'pa')){
										if( isset( $_REQUEST["toShow".$tax->name] ) ){ //show columns according to what is checked
											array_push($column_name,$tax->name);
										}
									}	
								}
								foreach($column_name as $d){
									
									if(  isset( $_REQUEST["toShow".strtolower(str_replace(" ","_",$d))] ) ){
										print "<div>".strtoupper(str_replace("_"," ",$d))."</div>";										
									}
								}
								
								$rowCount = 1;
								$column='';
								$count = count($column_name);
								$count = count($column_name);
								for ($i = 0; $i < $count; $i++){
									$column++;
								}
								$rowCount = 2;  
								$column = 'A';
								$column_value=array();	
								
								?>								
							</div>
							<div class='tableBody'>
							<?php
						
									
							while ( $query ->have_posts() ){
							$query ->the_post();
							global $product;
							if ( $product->is_type( 'simple' ) ) {							
								?>						
								<form method='post' action= "<?php echo admin_url( 'admin.php?page=webd-woocommerce-product-excel-importer-bulk-edit&tab=search-edit&action=update' ); ?>" name='single_edit_list' class='single_edit_list'>
								<div>
								<input type="checkbox" class='selectThis' name='id' value="<?php print esc_attr(get_the_ID()) ;?>" /></div>
								<div>
								<a href='<?php print esc_url( get_permalink(get_the_ID()));?>' target='_blank'>
									<i class='fa fa-eye' ></i> <?php esc_html_e("View","webd_bulk_edit");?>								
								</a>  
								<a href='<?php print esc_url( get_edit_post_link(get_the_ID())) ;?>' target='_blank'>
									<i class='fa fa-pencil' ></i> <?php esc_html_e("Edit","webd_bulk_edit");?>								
								</a>								
								<?php submit_button('Update','primary','Update'); ?></div>
								
								<input type='hidden' name='id' value="<?php print esc_attr(get_the_ID()) ;?>" />
								
								<?php if( isset( $_REQUEST["toShowtitle"] ) ){ ?>
								<div><input type='text' name='post_title' value="<?php esc_attr(the_title()) ;?>" />
								
								</div>
								<?php } ?>
								<?php if(  isset( $_REQUEST["toShowdescription"] ) ){ ?>
								<div>
									<textarea name='post_content' ><?php print esc_textarea(the_content()) ;?></textarea>
								</div>
								<?php } ?>
								<?php array_push($column_value,esc_attr(get_post_field('post_title', get_the_ID())),esc_attr(get_post_field('post_content', get_the_ID())) ); ?>
								<?php foreach($post_meta as $meta){
									if(  isset( $_REQUEST["toShow".$meta] ) ){ /*show columns according to what is checked)*/ ?>
										<div><input type='text' name='<?php print $meta ; ?>' value="<?php print esc_attr(get_post_meta(get_the_ID(), $meta, true )); ?>" /></div>
									<?php array_push($column_value,esc_attr(get_post_meta(get_the_ID(), $meta, true ))); ?>
									<?php 
									} 
								}//end foreach
								
								$terms = get_post_taxonomies( get_the_ID());
									//array of ids from search to bulk update
									array_push($id_Array, get_the_ID());
									

									$terms = get_post_taxonomies( get_the_ID());
									foreach($terms as $tax){									
										
										$term = get_the_terms( get_the_ID(), $tax );
										
											if(!strstr($tax,'pa')){
										
												if( isset( $_REQUEST["toShow".$tax] ) && !empty($term)  ){//show columns according to what is checked
														$countTerms = count($term);
															$i=0;
															print "<div>";
															$myterm = array();
															while($i<$countTerms ){
																array_push($myterm, $term[$i]->name);
																$i++;
															}
															$terms = implode(',',$myterm);
															print "<input type='text' name='".$tax."' value='".$terms."' />";
															print "</div>";
															array_push($column_value,$terms);
												}else{
													print "<div>";
													print "<input type='text' name='".$tax."' value='' />";
													print "</div>";
												}
												
											}
									}

									$column = 'A';
									for($j=0; $j<$count;$j++){	 
									 $column++;
									}
									$column_value=array(); // EMPTY THE ARRAY FOR THE NEW ROW
									$rowCount++;
								?>
										<input type='hidden' name='submitList' value='1'  />
										<?php wp_nonce_field('updateProductList'); ?>								
								</form><?php
							}
						}//end while
				print "</div></div></div>";		
				
			}else print "<p class=' error'>No Simple Product Found. For variable products please upgrade to <a  target='_blank'  href='https://webdeveloping.gr/product/woocommerce-product-excel-importer-bulk-editing-pro'>PRO VERSION</a>.</p>";//end if
							
		}	
		
		$this->updateMassive();				
		$this->updateFromList();

	}


	public function updateMassiveForm(){
		?><div>
			<form name='updateMassive' id='updateMassive' method='post' action= "<?php echo admin_url( 'admin.php?page=webd-woocommerce-product-excel-importer-bulk-edit&tab=search-edit' ); ?>">
				<table class='wp-list-table widefat fixed'>
					<input type='hidden' required name='massiveId' id='massiveId' />
				<tr>

					<td><label for='regular_price'><?php esc_html_e("Regular Price","webd_bulk_edit");?></label></td>
					<td><input step="0.01"  type='number' id='bulk_regular_price' name='regular_price' value='<?php print $this->regular_price; ?>' /></td>
					<td><label for='regular_operator'><?php esc_html_e("How to change Regular Price","webd_bulk_edit");?></label></td>
					<td>
						
						<select disabled name='regular_operator' id='regular_operator'>
							<option value='ea'> <?php esc_html_e("Exact Amount","webd_bulk_edit");?></option>
							<option value='mfa'><?php esc_html_e("Minus fixed amount","webd_bulk_edit");?></option>
							<option value='pfa'><?php esc_html_e("Plus fixed amount","webd_bulk_edit");?></option>
							<option value='mpa'><?php esc_html_e("Minus % amount","webd_bulk_edit");?></option>
							<option value='ppa'><?php esc_html_e("Plus % amount","webd_bulk_edit");?></option>
						</select>
					</td>					
				</tr>

				<tr>
					<td><label for='sale_price'><?php esc_html_e("Sale Price","webd_bulk_edit");?></label></td>
					<td><input min='0' step="0.01" type='number' id='bulk_sale_price' name='sale_price' value='<?php print $this->sale_price; ?>' /></td>
					<td><label for='sale_operator'><?php esc_html_e("How to change Sale Price","webd_bulk_edit");?></label></td>
					<td>						
						<select disabled name='sale_operator' id='sale_operator'>
							<option value='ea'> <?php esc_html_e("Exact Amount","webd_bulk_edit");?></option>
							<option value='mfa'><?php esc_html_e("Minus fixed amount","webd_bulk_edit");?></option>
							<option value='pfa'><?php esc_html_e("Plus fixed amount","webd_bulk_edit");?></option>
							<option value='mpa'><?php esc_html_e("Minus % amount","webd_bulk_edit");?></option>
							<option value='ppa'><?php esc_html_e("Plus % amount","webd_bulk_edit");?></option>
						</select>
					</td>					
				</tr>				
				<tr>					
					<td><label for='stock'><?php esc_html_e("Stock","webd_bulk_edit");?></label></td>
					<td><input type='text' class='warning' name='stock' value='PRO Version Only' /></td>
				</tr>
				
				<?php
				$taxonomy_objects = get_object_taxonomies( 'product', 'objects' );			
				foreach( $taxonomy_objects as $voc){

					if($voc->name =='product_type'){
print "<tr><td><label for='".str_replace('_',' ',esc_attr($voc->name))."'>". str_replace('_',' ',esc_attr($voc->name)). "</label></td>";							
							print "<td><select  name='".esc_attr($voc->name)."'>";
							print "<option value='simple' >Simple</option>";
							print "<option value='variable' >Variable</option>";
							print "<option value='grouped' >Grouped</option>";
							print "<option value='external' >External</option>";
							print "</select></td></tr>";																	
						
					}				
					
					if(!strstr($voc->name,'pa') && $voc->name !='product_type' ){
							print "<tr><td><label for='".str_replace('_',' ',esc_attr($voc->name))."'>". str_replace('_',' ',esc_attr($voc->name)). "</label></td>";							
							print "<td><select class='warning' name='".esc_attr($voc->name)."'>";
							print "<option value='' >PRO Version Only</option>";
							print "</select></td></tr>";													
					}

				}
				?>
								
				<input type='hidden' name='updateMassive' value='1'/>
				<tr>
					<td><input type='submit' class='button button-primary' value='Update Massively' /></td>
				</tr>
				</table>
				<?php wp_nonce_field('updateMassive'); ?>
			</form>	
		</div>
		<?php
	}
	
	public function updateMassive(){

		if($_SERVER['REQUEST_METHOD'] == 'POST' && current_user_can('administrator') && isset( $_REQUEST['updateMassive'] ) ){
				check_admin_referer( 'updateMassive' );
				check_ajax_referer( 'updateMassive' );
				$id_Array = explode(",", $_REQUEST['massiveId']);		
				
				foreach($id_Array as $id){
					
					if( isset( $_REQUEST['sale_price'] ) && !empty($_REQUEST['sale_price']) ){
						$existingValue = esc_attr(get_post_meta($id, '_sale_price',true ));
						$newPrice = sanitize_text_field($_REQUEST['sale_price']);
						$mfa =  $existingValue - $newPrice ;
						$pfa =  $existingValue + $newPrice;
						$mpa =  $existingValue - ($newPrice /100 ) * $existingValue ;
						$ppa =  $existingValue + ($newPrice /100 ) * $existingValue ;
						
						if(!empty($_REQUEST['sale_operator'] && $_REQUEST['sale_operator']=='ea')){
							update_post_meta( $id, '_sale_price',$newPrice );
							update_post_meta( $id, '_price',$newPrice );
						}elseif(!empty($_REQUEST['sale_operator'] && $_REQUEST['sale_operator']=='mfa')){
							update_post_meta( $id, '_sale_price',$mfa);
							update_post_meta( $id, '_price',$mfa );					
						}elseif(!empty($_REQUEST['sale_operator'] && $_REQUEST['sale_operator']=='pfa')){
							update_post_meta( $id, '_sale_price',$pfa );
							update_post_meta( $id, '_price',$pfa );
						}elseif(!empty($_REQUEST['sale_operator'] && $_REQUEST['sale_operator']=='mpa')){
							update_post_meta( $id, '_sale_price',$mpa );
							update_post_meta( $id, '_price',$mpa );
						}elseif(!empty($_REQUEST['sale_operator'] && $_REQUEST['sale_operator']=='ppa')){
							update_post_meta( $id, '_sale_price',$ppa );
							update_post_meta( $id, '_price',$ppa );
						}else {
							update_post_meta( $id, '_sale_price',$newPrice );						
							update_post_meta( $id, '_price',$newPrice );						
						}
					}
					if( isset( $_REQUEST['regular_price'] ) && !empty($_REQUEST['regular_price']) ){
						$existingValue = esc_attr(get_post_meta($id, '_regular_price',true ));
						$newPrice = sanitize_text_field($_REQUEST['regular_price']);
						$mfa =  $existingValue - $newPrice ;
						$pfa =  $existingValue + $newPrice;
						$mpa =  $existingValue - ($newPrice /100 ) * $existingValue ;
						$ppa =  $existingValue + ($newPrice /100 ) * $existingValue ;
						
						if(!empty($_REQUEST['regular_operator'] && $_REQUEST['regular_operator']=='ea')){
							update_post_meta( $id, '_regular_price',$newPrice );
						}elseif(!empty($_REQUEST['regular_operator'] && $_REQUEST['regular_operator']=='mfa')){
							update_post_meta( $id, '_regular_price',$mfa);
						}elseif(!empty($_REQUEST['regular_operator'] && $_REQUEST['regular_operator']=='pfa')){
							update_post_meta( $id, '_regular_price',$pfa );	
						}elseif(!empty($_REQUEST['regular_operator'] && $_REQUEST['regular_operator']=='mpa')){
							update_post_meta( $id, '_regular_price',$mpa );	
						}elseif(!empty($_REQUEST['regular_operator'] && $_REQUEST['regular_operator']=='ppa')){
							update_post_meta( $id, '_regular_price',$ppa );	
						}else{
							update_post_meta( $id, '_regular_price',$newPrice );
						}
					}
					
					if( isset( $_REQUEST['product_type'] ) && !empty($_REQUEST['product_type']) ){
						wp_delete_object_term_relationships($id, product_type);
						wp_set_object_terms( $id,sanitize_text_field($_REQUEST['product_type']),'product_type',true);
					}
				
					wc_delete_product_transients( $id );	
					wc_delete_product_transients( $productId );		

					if ( false === ( get_transient( 'webd_bulk_notified' ) ) ) {
						set_transient( 'webd_bulk_notification', true );
					}				
				}	
		}
		
	}
	
	
	public function updateFromList(){
			if($_SERVER['REQUEST_METHOD'] == 'POST' && current_user_can('administrator') && isset( $_REQUEST['submitList'] ) ){
				check_admin_referer( 'updateProductList' );
				check_ajax_referer( 'updateProductList' );	

				$this->id = sanitize_text_field($_POST['id']);
				
				if( isset( $_REQUEST['_sku'] ) && !empty($_POST['_sku']))update_post_meta( $this->id, '_sku', sanitize_text_field($_POST['_sku']) );
				if(isset( $_REQUEST['_regular_price'] ) && !empty($_POST['_regular_price']))update_post_meta( $this->id, '_regular_price', sanitize_text_field($_POST['_regular_price']) );
				if(isset( $_REQUEST['_sale_price'] ) && !empty($_POST['_sale_price'])){
						update_post_meta( $this->id, '_sale_price', sanitize_text_field($_POST['_sale_price']) );
						update_post_meta( $this->id, '_price', sanitize_text_field($_POST['_sale_price']));
				}else update_post_meta( $this->id, '_price', sanitize_text_field($_POST['_regular_price'])) ;
				if(isset( $_REQUEST['_stock'] ) && !empty($_POST['_stock']))update_post_meta( $this->id, '_stock', sanitize_text_field($_POST['_stock']) );
				if(isset( $_REQUEST['_stock_status'] ) && !empty($_POST['_stock_status']))update_post_meta( $this->id, '_stock_status', sanitize_text_field($_POST['_stock_status']) );
				if(isset( $_REQUEST['_length'] ) && !empty($_POST['_length']))update_post_meta( $this->id, '_length', sanitize_text_field($_POST['_length']) );
				if(isset( $_REQUEST['_width'] ) && !empty($_POST['_width']))update_post_meta( $this->id, '_width', sanitize_text_field($_POST['_width']) );
				if(isset( $_REQUEST['_height'] ) && !empty($_POST['_height']))update_post_meta( $this->id, '_height', sanitize_text_field($_POST['_height']) );
				if(isset( $_REQUEST['_weight'] ) && !empty($_POST['_weight']))update_post_meta( $this->id, '_weight',sanitize_text_field($_POST['_weight']) );
					
					$content = wp_specialchars_decode( $_POST['post_content'], $quote_style = ENT_QUOTES  );
					$content =  preg_replace('#<script(.*?)>(.*?)</script>#is', '', $content);
					
					$post = array(
						'ID' 		   => (int)$_POST['id'],
						'post_title'   => sanitize_text_field($_POST['post_title']),
						'post_content' => $content,
						'post_status'  => 'publish',
						'post_excerpt' => "",
						'post_name'    => sanitize_text_field($_POST['post_title']),
						'post_type'    => 'product'
					);							
					wp_update_post($post, $wp_error );


				//LOOP ALL TAXONOMIES ASSIGN VALUES
				$taxonomy_objects = get_object_taxonomies( 'product', 'objects' );			
				foreach( $taxonomy_objects as $voc){
					if(  !strstr($voc-name,'pa')  ){
					$value = sanitize_text_field($_REQUEST[$voc->name]);
							if(!empty($_REQUEST[$voc->name])){
								//if(!empty($_REQUEST[$voc->name]) && $voc->name =='product_type' ){
									wp_delete_object_term_relationships($this->id, $voc->name);
									//wp_remove_object_terms( $finalId,$category,$voc->name);
								//}
							
								$categories =  explode(',',sanitize_text_field($_POST[$voc->name]));							
								foreach($categories as $category){	
									wp_set_object_terms( $this->id,$category,$voc->name,true);
								}
								
							}
					}
				}
				wc_delete_product_transients( $this->id );	
				wc_delete_product_transients( $pid );	

				if ( false === ( get_transient( 'webd_bulk_notified' ) ) ) {
					set_transient( 'webd_bulk_notification', true );
				}			
		}
	}
				

}