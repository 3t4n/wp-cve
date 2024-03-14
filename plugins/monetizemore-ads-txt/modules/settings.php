<?php 
//addmin menues 
add_action('admin_menu', 'wat_item_menu');

function wat_item_menu() {
	add_menu_page(   __('Ads.txt', 'sc'), __('Ads.txt', 'sc'), 'edit_published_posts', 'wat_editor', 'wat_editor');
	
}

// main editor
function wat_editor(){

?>
<div class="wrap tw-bs">
<h2><?php _e('Ads.txt by <a href="http://monetizemore.com">MonetizeMore</a>', 'wcc'); ?></h2>

<ul>
<li>Click "Add Row" to start adding the values.</li>
<li>Fill in Domain Name of Ad Network, Account ID and Type. Certification Authority is optional.</li>
<li>Click "Save and publish Ads.txt file" when you're done.</li>
</ul>

<hr/>
 <?php if(  wp_verify_nonce($_POST['_wpnonce']) ): ?>
  <div id="message" class="updated" ><?php _e('File generated. Check it <a target="_blank" href="'.get_option('home').'/ads.txt">here</a>', 'wcc'); ?></div>  
  <?php 
  $config = get_option('wat_editor'); 

	foreach( $_POST as $key=>$value ){
		$tmp_array = array();
		if( count($value ) > 0 ){
			foreach( (array)$value  as $single_val ){
				$tmp_array[] = sanitize_text_field( $single_val );
			}
		}
		
		$options[$key] = $tmp_array;
	}
	update_option('wat_editor', $options );
  
  /*
	$count = count( $options['account_id'] );
	$out_arr = array();
	for( $i = 1; $i < $count; $i++ ){
	  $out_arr[] = array( $options['domain'][$i], $options['account_id'][$i], $options['type'][$i], $options['autority'][$i]  );
	}
  
	$fp = fopen( ABSPATH.'ads.txt', 'w');

	foreach ($out_arr as $fields) {		
		$fields = array_filter($fields);	
		$print_arr[] = 	implode(',', $fields);
	}
	fwrite($fp, implode("\n", $print_arr)  );
	fclose($fp);
  */
	// making file
	/*
	$file_path = ABSPATH.'ads.txt';
	unlink( $file_path );
	
	
	
	file_put_contents( $file_path, '' );
*/
  else:  ?>

  <?php //exit; ?>
  
  <?php endif; ?> 
<form class="form-horizontal" method="post" action="" id="submit_shortcodes" enctype="multipart/form-data" >
<?php wp_nonce_field();  
$config = get_option('wat_editor'); 
 
/*
 $config = array();
 if( file_exists(ABSPATH.'ads.txt') ){
	 if (($handle = fopen( ABSPATH.'ads.txt' , "r")) !== FALSE) {
		 while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			 $config[] = $data ;
		 }
	 }
 }
*/
?>  

<div class="write_check">
	<?php 
 /*
	$rand = rand(1000,9999);
	$filename = $rand.'.rand';
	$file_path = ABSPATH.$filename;
	file_put_contents( $file_path, '' );
	if( file_exists($file_path) ){
		$allowed = 1;
		unlink( $file_path );
	}else{
		$allowed = 0;
	}
	if( $allowed == 0 ){
		?>
		<div class="alert alert-block error_block_1">  
		  Script can't write to root dir. Plase, check permissions.
		</div> 
		<?php
	}
	*/
	?>
</div>

<div class="errors_block">
	<div class="alert alert-block error_block_1">  
	  Please, enter Domain Name of Ad Network
	</div>  
	<div class="alert alert-block error_block_2">  
	  Please, enter Account ID
	</div> 
	<div class="alert alert-block error_block_3">  
		In column 3 only use either "DIRECT" or "RESELLER"
	</div> 
</div>


<fieldset>
	 
	<table class="table editor_table">  
        <thead> 
			
		
          <tr>  
            <th>Domain Name of Ad Network  <span class="helper_block"><i class="fa fa-info-circle info_cont" aria-hidden="true" title="The domain name of the advertising system (required): The canonical domain name of the SSP, exchange, header wrapper, etc. system that bidders connect to. This may be the operational domain of the system, if that is different than the parent corporate domain, to facilitate WHOIS and reverse IP lookups to establish clear ownership of the delegate system. Ideally the SSP or exchange publishes a document detailing what domain name to use."></i></span></th>           
			<th>Account ID  <span class="helper_block"><i class="fa fa-info-circle info_cont" aria-hidden="true" title="The publisher’s account ID (required): The identifier associated with the seller or reseller account within the advertising system in field #1. This must contain the same value used in transactions (such as OpenRTB bid requests) in the field specified by the SSP/exchange. Typically, in OpenRTB this is the publisher.id field. For OpenDirect, it is typically the publisher’s organization ID."></i></span></th>  
            <th>Type  <span class="helper_block"><i class="fa fa-info-circle info_cont" aria-hidden="true" title="A value of 'DIRECT' indicates that the publisher (content owner) directly controls the account indicated in field #2 on the system in field #1. This tends to mean a direct business contract between the publisher and the advertising system.<br/>
			A value of 'RESELLER' indicates that the publisher has authorized another entity to control the account indicated in field #2 and resell their ad space via the system in field #1. Other types may be added in the future. Note that this field should be treated as case-insensitive when interpreting the data."></i></span></th>  
            <th>Certification Authority  <span class="helper_block"><i class="fa fa-info-circle info_cont" aria-hidden="true" title="An ID that uniquely identifies the advertising system within a certification authority (this ID maps to the entity listed in field #1). A current certification authority is the Trustworthy Accountability Group (TAG), and the TAG ID would be included here. For Google seller accounts, the TAG ID is f08c47fec0942fa0."></i></span></th>
            <th>Actions</th>  
          </tr>  
		  
		 
		  
        </thead>  
        <tbody class="editor_content">  
		
		<tr class="cliche_row_block">  
					<td>
						<input name="domain[]" placeholder="eg. google.com, appnexus.com"  class="domain_input_field" type="text" value="" />
					</td>  
					<td>
						<input name="account_id[]" placeholder="eg. 156017, z87wm" class="account_id_input_field" type="text" value="" />
					</td>  
					<td>
						<select name="type[]" class="type_id_input_field">
							<option  value="">Select Type
							<option  value="DIRECT">DIRECT
							<option  value="RESELLER">RESELLER
						
						</select>
																	
						
					</td>  
					<td>
						<input name="autority[]" placeholder="eg. f08c47fec0942fa0"  class="autority_input_field" type="text" value="" />
					</td> 
					<td>
						<button type="button" class="btn btn-danger delete_row" ><span class="dashicons dashicons-trash" title="<?php _e('Delete Element', 'wks') ?>"></span></button>
						
					</td>  
		</tr> 
		
		
		
		<?php 
	
		
		if( is_array($config['domain'])&& count( $config['domain'] ) > 0 ): ?>
		
		<?php 

			for( $i=1; $i < count($config['domain'] ); $i++ ){
				?>
				
				 <tr>  
					<td>
						<input name="domain[]"  placeholder="eg. google.com, appnexus.com" class="domain_input_field" type="text" value="<?php echo $config['domain'][$i];  ?>" />
					</td>  
					<td>
						<input name="account_id[]"  placeholder="eg. 156017, z87wm" class="account_id_input_field" type="text" value="<?php echo $config['account_id'][$i];  ?>" />
					</td>  
					<td>
						<select name="type[]" class="type_id_input_field">
							<option  value="">Select Type
							<option  <?php if( $config['type'][$i] == 'DIRECT' ){ echo ' selected ';} ?> value="DIRECT">DIRECT
							<option <?php if( $config['type'][$i] == 'RESELLER'  ){ echo ' selected ';} ?> value="RESELLER">RESELLER
						
						</select>
																	
						
					</td>  
					<td>
						<input name="autority[]"  placeholder="eg. f08c47fec0942fa0" class="autority_input_field" type="text" value="<?php echo $config['autority'][$i];  ?>" />
					</td> 
					<td>

						<button type="button" class="btn btn-danger delete_row" ><span class="dashicons dashicons-trash" title="<?php _e('Delete Element', 'wks') ?>"></span></button>
						
					</td>  
				  </tr> 
				
			<?php
			}
		?>
		
		<?php else: ?>
		 
		
		<?php endif;?>
		
			
           
 
        </tbody>
<tfoot>
	<tr>  
				<td><input type="button" class="btn btn-success add_row" value="Add Row" /></td>  
				<td> </td>  
				<td> </td>  
				<td> </td>  
				<td></td>  
			</tr>
</tfoot>		
      </table>  	
	 
	  
	 
		
		
          <div class="form-actions1">  
            <button type="submit" class="btn btn-primary">Save and publish Ads.txt file</button>  

          </div>  
        </fieldset>   

</form>

</div>


<?php 
}



?>