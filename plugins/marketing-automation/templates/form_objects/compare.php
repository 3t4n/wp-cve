<?php
	//$com_products	=	explode(",", $_GET['products']);
	$com_products	=	$_productsBuyIds;

	$products_array	=	array();
	$compare_data	=	array();
	
	function get_attributes_checkbox_format($products_options_name, $products_id, $isbuy = false)
	{
		global $cart, $languages_id, $currencies;
		
		$products_options_array = array();
		$products_options_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . (int)$products_id . "' and pa.options_id = '" . (int)$products_options_name['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . (int)$languages_id . "' order by pov.sorting_order");
		
		while ($products_options = tep_db_fetch_array($products_options_query)) 
		{			
			$item_img_url	=	'images/'.$products_options_name['option_image'];			
			$item_img		=	'<a><img src="./timthumb.php?src='.$item_img_url.'&w=74&h=58&a=c&zc=2" style="border: 1px solid rgb(49, 171, 220);" onmouseover="javascript:window.document.prodimg.src=\''.$item_img_url.'\'" border="0" vspace="1" width="59" height="61" hspace="1"></a>';
			
			$products_options_array[] = array('id' => $products_options['products_options_values_id'], 'text' => $products_options['products_options_values_name'], 'image'=>$item_img);
			
			if ($products_options['options_values_price'] >= 0) {
				$products_options_array[sizeof($products_options_array)-1]['text'] .= ' - ' . $currencies->display_price($products_options['options_values_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) .' ';
			}
		}

		if (isset($cart->contents[$products_id]['attributes'][$products_options_name['products_options_id']])) {
			$selected_attribute = $cart->contents[$products_id]['attributes'][$products_options_name['products_options_id']];
		} else {
			$selected_attribute = false;
		}
		
		$output	=	'';
		foreach($products_options_array as $value)
		{
			if (!$isbuy) {
				$output	.=	'<tr>
				  <td style="background-color: #EDEDED; padding-top: 10px; vertical-align: top; width: 25px; text-align: left;" align="left" valign="top">'.tep_draw_checkbox_field('id[' . $products_options_name['products_options_id'] . ']', $value['id'], $selected_attribute).'</td>
				  <td style="background-color: #fff; padding: 12px 20px; text-align: left;" class="main" align="left" valign="middle" width="450">'.$value['image'].$value['text'].' </td>
				</tr>';
			} else {
				$output	.=	'<tr>
				  <td style="background-color: #EDEDED; padding-top: 10px; vertical-align: top; width: 25px; text-align: left;" align="left" valign="top">'.tep_draw_checkbox_field('accid[' . $products_options_name['products_options_id'] . ']', $value['id'], $selected_attribute).'</td>
				  <td style="background-color: #fff; padding: 12px 20px; text-align: left;" class="main" align="left" valign="middle" width="450">'.$value['image'].$value['text'].' </td>
				</tr>';
			}
		}

		return $output;	
	}
	
	foreach($com_products as $com_product_id) {	
		// begin Extra Product Fields
		$epf = array();  
		$epf_query = tep_db_query("select e.epf_id, e.epf_uses_value_list, e.epf_show_parent_chain, e.epf_use_as_meta_keyword, l.epf_label from " . TABLE_EPF . " e join " . TABLE_EPF_LABELS . " l where e.epf_status and (e.epf_id = l.epf_id) and (l.languages_id = " . (int)$languages_id . ") and l.epf_active_for_language order by epf_order");
		
		while ($e = tep_db_fetch_array($epf_query)) {  // retrieve all active extra fields
		  $epf[] = array('id' => $e['epf_id'],
						 'label' => $e['epf_label'],
						 'uses_list' => $e['epf_uses_value_list'],
						 'show_chain' => $e['epf_show_parent_chain'],
						 'search' => $e['epf_advanced_search'],
						 'keyword' => $e['epf_use_as_meta_keyword'],
						 'field' => 'extra_value' . ($e['epf_uses_value_list'] ? '_id' : '') . $e['epf_id']);
		}
		
		$query = "select p.products_date_added, p.products_last_modified, pd.products_name";
		foreach ($epf as $e) {
		  if ($e['keyword']) $query .= ", pd." . $e['field'];
		}
		
		$query .= " from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$com_product_id . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'";
		$pname = tep_db_fetch_array(tep_db_query($query));
		$datemod = substr((tep_not_null($pname['products_last_modified']) ? $pname['products_last_modified'] : $pname['products_date_added']), 0, 10);
		// end Extra Product Fields
		  
		// begin Product Extra Fields
		$query = "select p.products_id, pd.products_name, pd.products_description, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id";
		foreach ($epf as $e) {
		  $query .= ", pd." . $e['field'];
		}

		$query .= " from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . $com_product_id . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'";
		$product_info_query = tep_db_query($query);
		$product_info	=	tep_db_fetch_array($product_info_query);
		
		$query	=	tep_db_query("SELECT `compare_fields_values`.*, `compare_fields`.`field_name`  FROM `compare_fields_values` LEFT JOIN compare_fields ON compare_fields_values.field_id = compare_fields.field_id WHERE `compare_fields_values`.`products_id` = '$com_product_id' order by `compare_fields`.`order`");				
		
		while( $data	=	tep_db_fetch_array($query) )
		{	
			$compare_data[$data['field_id']]['field_name']		=	$data['field_name'];
			unset($data['field_name']);
			$compare_data[$data['field_id']][$com_product_id]	=	array_merge($data, $product_info);			
			$products_array[$com_product_id]					=	$product_info;
		}		
	}			  
	
	$color[0]	=	"#dbe9f2";
	$color[1]	=	"#f6f6f6";
?>
<div id="helpcontent_containter" style="font-size: 12px; padding: 10px; border: 2px solid #000; width: 484px; height: 245px; background-color: #fff; position: absolute; right: 0; top: 0; display: none;">
	<h2 style="font-size: 26px; line-height: 1.2;padding-bottom: 10px;font-family: 'HelveticaNeue-BoldCond', sans-serif;">Rental Duration </h2>
	<ul style="margin-left: 18px;">
		<li style="line-height: 1.9em;list-style-type: disc;">Your rental starts the day that you receive your knee walker.</li>
		<li style="line-height: 1.9em;list-style-type: disc;">You will be charged for your first month in advance, and then put on our automatic billing system which insures you are charged the lowest possible rental price based on how long you keep your knee walker for.</li>
		<li style="line-height: 1.9em;list-style-type: disc;">If your rental fees add up to our retail price, you own it.</li>
		<li style="line-height: 1.9em;list-style-type: disc;">If you are unhappy with your knee walker, let us know within the first seven days you receive it, and we will send you a  completely free exchange.</li>
		<li style="line-height: 1.9em;list-style-type: disc;">Your happiness is our priority.</li>
	</ul>
	
	<a onclick="$(this).parent().hide();" href="javascript://"><img src="http://kneewalkercentral.com/images/tooltip_close.png" style="position: absolute; right: -10px; top: -10px;" /></a>
</div>
<table id="CompareTableSummary" width="100%" cellpadding="5" cellspacing="0" border="0" style="margin-top: 20px;">
	<thead>
		<?php if (MODULE_SALE_ENABLED && MODULE_SALE_TYPE == 'MULTI'): ?>
		<tr>
			<td class="tableCaption"></td>
			<td colspan="<?php echo count($com_products); ?>" style="text-align: right; padding-right: 4px;">
				<img src="images/multi-compare-sale.png" />
			</td>
		</tr>
		<?php endif; ?>
		<tr>
			<td class="tableCaption">
				<!--<h1>Compare All <br />Knee Walkers</h1>-->
				<h1>Knee Walkers (compared)</h1>
				<p>Which knee walker is right for you?</p>
			</td>
			<?php foreach($com_products as $pr_id): ?>
				<?php 
					if (MODULE_SALE_ENABLED): 
						if ($pr_id == 25) $pr_id1 = 34;
						if ($pr_id == 1) $pr_id1 = 32;
						if ($pr_id == 57) $pr_id1 = 55;
						if ($pr_id == 40) $pr_id1 = 39;
					endif;
				?>
				<td <?php /*if ($pr_id == 40): ?>class="mostpopular"<?php endif;*/ ?>>
					<?php if ($pr_id == '57'): ?>
					<a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=55')?>" style="display: block; position: relative;">
					<?php else: ?>
					<a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id='.get_buy2rent_id($pr_id))?>" style="display: block; position: relative;">
					<?php endif; ?>
					
					<?php if (MODULE_SALE_ENABLED && MODULE_SALE_TYPE == 'SINGLE'): 
						if ($pr_id == 25) $pr_id1 = 34;
						if ($pr_id == 1) $pr_id1 = 32;
						if ($pr_id == 57) $pr_id1 = 55;
						if ($pr_id == 40) $pr_id1 = 39;
						
					?>
					<?php 	if (MODULE_SALE_PRODUCT_ID == (int)$pr_id1): ?>
					<div class="saleIconSmall" style="right: 25px; left: auto;">
						<h3>Sale</h3>
						<h4>$<?php echo MODULE_SALE_PRODUCT_AFTER_SALE_AMOUNT; ?></h4>
						<h5 style="font-size: 12px;">first month</h5>
					</div>
					<?php 	endif; ?>
					<?php endif; ?>
					
						<?php if ($pr_id == 25): ?>
							<img border="0" src="images/_theme/basic.png" width="212" height="267" style="<?php if (MODULE_SALE_ENABLED && MODULE_SALE_TYPE == 'SINGLE' && MODULE_SALE_PRODUCT_ID == (int)$pr_id1): ?>border: 2px solid #D21212;<?php endif; ?>" />
							<h2>Economy</h2>
						<?php elseif ($pr_id == 1): ?>
							<img border="0" src="images/_theme/freespirit.png" width="212" height="267" style="<?php if (MODULE_SALE_ENABLED && MODULE_SALE_TYPE == 'SINGLE' && MODULE_SALE_PRODUCT_ID == (int)$pr_id1): ?>border: 2px solid #D21212;<?php endif; ?>" />
							<h2>Free Spirit</h2>
						<?php elseif ($pr_id == 40): ?>
							<img border="0" src="images/_theme/northrup.png" width="212" height="267" style="<?php if (MODULE_SALE_ENABLED && MODULE_SALE_TYPE == 'SINGLE' && MODULE_SALE_PRODUCT_ID == (int)$pr_id1): ?>border: 2px solid #D21212;<?php endif; ?>" />
							<h2>Northrup</h2>
						<?php elseif ($pr_id == 57): ?>
							<img border="0" src="images/_theme/dv8_compare.png" width="212" height="267" style="<?php if (MODULE_SALE_ENABLED && MODULE_SALE_TYPE == 'SINGLE' && MODULE_SALE_PRODUCT_ID == (int)$pr_id1): ?>border: 2px solid #D21212;<?php endif; ?>" />
							<h2>dv8</h2>
						<?php endif; ?>
					</a>
				</td>
			<?php endforeach; ?>
		</tr>
		<tr><td style="padding-bottom: 10px;" colspan="4" align="left"></td></tr>
	</thead>
	
	<tbody>
		<?php /*
		<tr>
			<th width="300" style="width: 300px;" valign="middle" align="left">&nbsp;</th>
			
			<td align="center">
				<table width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<?php 
							$queryBuy = "select p.products_id, p.products_weight, pd.products_name, pd.products_features, pd.products_specifications, pd.products_description, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_rental_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id"; 
							$queryBuy .= " from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '25' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'";
							$product_info_queryBuy = tep_db_query($queryBuy);
							$product_info_buy = tep_db_fetch_array($product_info_queryBuy);
							$products_price_buy = $currencies->display_price($product_info_buy['products_price'], tep_get_tax_rate($product_info_buy['products_tax_class_id']));
						?>
						<td style="font-weight:bold; vertical-align: top; border-right: none; width: 70px; text-align: right;"><b>BUY</b></td><td style="font-weight: bold; border-right: none; text-align: left; padding-left: 10px;"><?php echo $products_price_buy; ?></td>
					</tr>
					<tr>
						<?php 
							$queryBuy = "select p.products_id, p.products_weight, pd.products_name, pd.products_features, pd.products_specifications, pd.products_description, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_rental_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id"; 
							$queryBuy .= " from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '34' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'";
							$product_info_queryBuy = tep_db_query($queryBuy);
							$product_info_buy = tep_db_fetch_array($product_info_queryBuy);
							$products_price_rental = $currencies->display_price($product_info_buy['products_rental_price'], tep_get_tax_rate($product_info_buy['products_tax_class_id']));
						?>
						<td style="font-weight:bold; vertical-align: top; border-right: none; width: 70px; text-align: right;"><b>RENT</b></td><td style="font-weight: bold; border-right: none; text-align: left; padding-left: 10px;">$75.00/month, <?php echo $products_price_rental; ?>/week<br /><span style="font-style: italic; font-weight: 100;">(1 month minimum)</span></td>
					</tr>
				</table>
			</td>
			<td align="center">
				<table width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<?php 
							$queryBuy = "select p.products_id, p.products_weight, pd.products_name, pd.products_features, pd.products_specifications, pd.products_description, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_rental_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id"; 
							$queryBuy .= " from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '1' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'";
							$product_info_queryBuy = tep_db_query($queryBuy);
							$product_info_buy = tep_db_fetch_array($product_info_queryBuy);
							$products_price_buy = $currencies->display_price($product_info_buy['products_price'], tep_get_tax_rate($product_info_buy['products_tax_class_id']));
						?>
						<td style="font-weight:bold; vertical-align: top; border-right: none; width: 70px; text-align: right;"><b>BUY</b></td><td style="font-weight: bold; border-right: none; text-align: left; padding-left: 10px;"><?php echo $products_price_buy; ?></td>
					</tr>
					<tr>
						<?php 
							$queryBuy = "select p.products_id, p.products_weight, pd.products_name, pd.products_features, pd.products_specifications, pd.products_description, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_rental_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id"; 
							$queryBuy .= " from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '32' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'";
							$product_info_queryBuy = tep_db_query($queryBuy);
							$product_info_buy = tep_db_fetch_array($product_info_queryBuy);
							$products_price_rental = $currencies->display_price($product_info_buy['products_rental_price'], tep_get_tax_rate($product_info_buy['products_tax_class_id']));
						?>					
						<td style="font-weight:bold; vertical-align: top; border-right: none; width: 70px; text-align: right;"><b>RENT</b></td><td style="font-weight: bold; border-right: none; text-align: left; padding-left: 10px;">$100.00/month, <?php echo $products_price_rental; ?>/week<br /><span style="font-style: italic; font-weight: 100;">(1 month minimum)</span></td>
					</tr>
				</table>
			</td>
			<td align="center">
				<table width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<?php 
							$queryBuy = "select p.products_id, p.products_weight, pd.products_name, pd.products_features, pd.products_specifications, pd.products_description, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_rental_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id"; 
							$queryBuy .= " from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '".$northupSub['compareId']."' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'";
							$product_info_queryBuy = tep_db_query($queryBuy);
							$product_info_buy = tep_db_fetch_array($product_info_queryBuy);
							$products_price_buy = $currencies->display_price($product_info_buy['products_price'], tep_get_tax_rate($product_info_buy['products_tax_class_id']));
						?>
						<td style="font-weight:bold; vertical-align: top; border-right: none; width: 70px; text-align: right;"><b>BUY</b></td><td style="font-weight: bold; border-right: none; text-align: left; padding-left: 10px;"><?php echo $products_price_buy; ?></td>
					</tr>
					<tr>
						<?php 
							$queryBuy = "select p.products_id, p.products_weight, pd.products_name, pd.products_features, pd.products_specifications, pd.products_description, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_rental_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id"; 
							$queryBuy .= " from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '".$northupSub['id']."' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'";
							$product_info_queryBuy = tep_db_query($queryBuy);
							$product_info_buy = tep_db_fetch_array($product_info_queryBuy);
							$products_price_rental = $currencies->display_price($product_info_buy['products_rental_price'], tep_get_tax_rate($product_info_buy['products_tax_class_id']));
						?>
						<td style="font-weight:bold; vertical-align: top; border-right: none; width: 70px; text-align: right;"><b>RENT</b></td><td style="font-weight: bold; border-right: none; text-align: left; padding-left: 10px;">$<?php echo $northupSub['comparePrice']; ?>.00/month, <?php echo $products_price_rental; ?>/week<br /><span style="font-style: italic; font-weight: 100;">(1 month minimum)</span></td>
					</tr>
				</table>
			</td>
		</tr>
		*/ ?>
		<tr>
			<th width="300" style="width: 300px;" valign="middle" align="left">Average Review</th>
			
			<?php /*foreach($com_products as $products_id): ?>
				<td align="center"><span class="rating r4">0/5</span></td>
			<?php endforeach;*/ ?>
			<?php foreach($_productsIds as $pr_id): ?>
			<?php	if ($pr_id == 34): ?>
			<?php
				$reviews_query_raw = tep_db_query("select COUNT(*) as reviews_count, AVG(r.reviews_rating) AS reviews_average from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd where r.products_id = '34' and r.reviews_id = rd.reviews_id and rd.languages_id = '" . (int)$languages_id . "'");
				$review = tep_db_fetch_array($reviews_query_raw);
			?>
			<td align="center" style="text-align: center;"><a style="display: block; width: 140px; margin: 0 auto; color: #1c84c3; font-weight: bold;" data-productid="34" class="fancybox fancybox.iframe" href="javascript://"><span class="rating r3" style="float: left;">0/5</span>&nbsp;<span style="float: left; padding-top: 5px; padding-left: 5px;" class="ratingNumber">(<?php echo $review['reviews_count']; ?>)</span><!--<br />(Click to read reviews)--></a></td>
			<?php	elseif($pr_id == 32): ?>
			<?php
				$reviews_query_raw = tep_db_query("select COUNT(*) as reviews_count, AVG(r.reviews_rating) AS reviews_average from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd where r.products_id = '32' and r.reviews_id = rd.reviews_id and rd.languages_id = '" . (int)$languages_id . "'");
				$review = tep_db_fetch_array($reviews_query_raw);
			?>
			<td align="center" style="text-align: center;"><a style="display: block; width: 140px; margin: 0 auto; color: #1c84c3; font-weight: bold;" data-productid="32" class="fancybox fancybox.iframe" href="javascript://"><span class="rating r4" style="float: left;">0/5</span>&nbsp;<span style="float: left; padding-top: 5px; padding-left: 5px;" class="ratingNumber">(<?php echo $review['reviews_count']; ?>)</span><!--<br />(Click to read reviews)--></a></td>
			<?php	elseif($pr_id == 39): ?>
			<?php
				$reviews_query_raw = tep_db_query("select COUNT(*) as reviews_count, AVG(r.reviews_rating) AS reviews_average from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd where r.products_id = '39' and r.reviews_id = rd.reviews_id and rd.languages_id = '" . (int)$languages_id . "'");
				$review = tep_db_fetch_array($reviews_query_raw);
			?>
			<td align="center" style="text-align: center;"><a style="display: block; width: 140px; margin: 0 auto; color: #1c84c3; font-weight: bold;" data-productid="39" class="fancybox fancybox.iframe" href="javascript://"><span class="rating r45" style="float: left;">0/5</span>&nbsp;<span style="float: left; padding-top: 5px; padding-left: 5px;" class="ratingNumber">(<?php echo $review['reviews_count']; ?>)</span><!--<br />(Click to read reviews)--></a></td>
			<?php	elseif($pr_id == 55): ?>
			<?php
				$reviews_query_raw = tep_db_query("select COUNT(*) as reviews_count, AVG(r.reviews_rating) AS reviews_average from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd where r.products_id = '55' and r.reviews_id = rd.reviews_id and rd.languages_id = '" . (int)$languages_id . "'");
				$review = tep_db_fetch_array($reviews_query_raw);
			?>
			<td align="center" style="text-align: center;"><a style="display: block; width: 140px; margin: 0 auto; color: #1c84c3; font-weight: bold;" data-productid="55" class="fancybox fancybox.iframe" href="javascript://"><span class="rating r45" style="float: left;">0/5</span>&nbsp;<span style="float: left; padding-top: 5px; padding-left: 5px;" class="ratingNumber">(<?php echo $review['reviews_count']; ?>)</span><!--<br />(Click to read reviews)--></a></td>
			<?php	endif; ?>
			<?php endforeach; ?>
		</tr>
		
		<?php $i = 1; ?>
		<?php foreach($compare_data as $field_id=>$com_data): $i++; ?>
		<tr bgcolor="<?=$color[($i%2)]?>">
			<th width="300" style="width: 300px;" valign="top" align="left"><?php echo $com_data['field_name']; ?></th>
			
			<?php foreach($com_products as $products_id): ?>
				<td class="fixLargeXMark" width="<?php echo (78/sizeof($com_products)) - 3;?>%" align="center" style="font-weight: bold;"><?php echo $com_data[$products_id]['value']; ?></td>
			<?php endforeach; ?>
		</tr>
		<?php endforeach; ?>

		<tr bgcolor="<?=$color[(++$i%2)]?>" style="border-top: 1px solid #bdbdbd; border-bottom: 1px solid #bdbdbd;">
			<th width="300" style="width: 300px;" valign="top" align="left" style="padding-top: 5px;">
				Calculate Delivery Time
				<form id="ShippingRateForm" name="ShippingRateForm" method="post" action="<?php echo tep_href_link('shipping.php', '', 'NONSSL'); ?>" style="padding-top: 15px;">
					<select id="ShippingRateForm_State" name="ShippingRateForm[State]" style="font-size: 12px; width: 75px; padding: 3px 0 2px;">
						<option value="">State<span>*</span></option>
						<?php
							//	GET LIST OF STATES (US STATES)
							$states	= tep_db_query("select zone_code, zone_name from zones where zone_country_id='223' order by zone_name");
						?>
						<?php while($state = tep_db_fetch_array($states)): ?>
						<?php	if(isset($state_list[$state['zone_name']])): ?>	
						<option value="<?php echo $state['zone_code']; ?>"><?php echo $state['zone_name']; ?></option>
						<?php	endif; ?>
						<?php endwhile; ?>
					</select>
					<input id="ShippingRateForm_ZipCode" name="ShippingRateForm[ZipCode]" type="text" placeholder="Zip Code*" value="" style="font-size: 12px; width: 75px; padding: 3px 0 2px;" />
					
					<input type="image" src="./images/_theme/submit_black_button.png" id="ShippingRateFormImageButton" style="vertical-align: top; margin-top: 2px;" />
				</form>
			</th>
			<?php foreach($_products as $products_id => $product): ?>
				<td class="shipping-rate shipping-rate-<?php echo $products_id; ?>" align="center">&nbsp;</td>
			<?php endforeach; ?>
		</tr>
		
		<?php //$i++; ?>
		<?php /*
		<tr bgcolor="<?=$color[($i%2)]?>" style="display: none;">
			<th width="300" style="width: 300px;" valign="top" align="left">Rental Price</th>
			<?php 
				$queryBuy = "select p.products_id, p.products_weight, pd.products_name, pd.products_features, pd.products_specifications, pd.products_description, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_rental_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id"; 
				$queryBuy .= " from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '34' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'";
				$product_info_queryBuy = tep_db_query($queryBuy);
				$product_info_buy = tep_db_fetch_array($product_info_queryBuy);
				$products_price_rental = $currencies->display_price($product_info_buy['products_rental_price'], tep_get_tax_rate($product_info_buy['products_tax_class_id']));
			?>
			<td width="<?php echo (78/sizeof($com_products));?>%" align="center" style="font-weight: bold;">$75.00 /month<br /><?php echo $products_price_rental; ?> /week</td>
			
			<?php 
				$queryBuy = "select p.products_id, p.products_weight, pd.products_name, pd.products_features, pd.products_specifications, pd.products_description, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_rental_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id"; 
				$queryBuy .= " from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '32' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'";
				$product_info_queryBuy = tep_db_query($queryBuy);
				$product_info_buy = tep_db_fetch_array($product_info_queryBuy);
				$products_price_rental = $currencies->display_price($product_info_buy['products_rental_price'], tep_get_tax_rate($product_info_buy['products_tax_class_id']));
			?>
			<td width="<?php echo (78/sizeof($com_products));?>%" align="center" style="font-weight: bold;">$100.00 /month<br /><?php echo $products_price_rental; ?> /week</td>
			
			<?php 
				$queryBuy = "select p.products_id, p.products_weight, pd.products_name, pd.products_features, pd.products_specifications, pd.products_description, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_rental_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id"; 
				$queryBuy .= " from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '".$northupSub['id']."' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'";
				$product_info_queryBuy = tep_db_query($queryBuy);
				$product_info_buy = tep_db_fetch_array($product_info_queryBuy);
				$products_price_rental = $currencies->display_price($product_info_buy['products_rental_price'], tep_get_tax_rate($product_info_buy['products_tax_class_id']));
			?>
			<td width="<?php echo (78/sizeof($com_products));?>%" align="center" style="font-weight: bold;">$100.00 /month<br /><?php echo $products_price_rental; ?> /week</td>
		</tr>
		*/ ?>
		<?php /*$i++;*/ /*
		<tr bgcolor="<?=$color[($i%2)]?>" style="display: none;">
			<th width="300" style="width: 300px;" valign="top" align="left">Purchase Price</th>
			<?php 
				$queryBuy = "select p.products_id, p.products_weight, pd.products_name, pd.products_features, pd.products_specifications, pd.products_description, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_rental_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id"; 
				$queryBuy .= " from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '25' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'";
				$product_info_queryBuy = tep_db_query($queryBuy);
				$product_info_buy = tep_db_fetch_array($product_info_queryBuy);
				$products_price_buy = $currencies->display_price($product_info_buy['products_price'], tep_get_tax_rate($product_info_buy['products_tax_class_id']));
			?>
			<td width="<?php echo (78/sizeof($com_products));?>%" align="center" style="font-weight: bold;"><?php echo $products_price_buy; ?></td>
			
			<?php 
				$queryBuy = "select p.products_id, p.products_weight, pd.products_name, pd.products_features, pd.products_specifications, pd.products_description, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_rental_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id"; 
				$queryBuy .= " from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '1' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'";
				$product_info_queryBuy = tep_db_query($queryBuy);
				$product_info_buy = tep_db_fetch_array($product_info_queryBuy);
				$products_price_buy = $currencies->display_price($product_info_buy['products_price'], tep_get_tax_rate($product_info_buy['products_tax_class_id']));
			?>
			<td width="<?php echo (78/sizeof($com_products));?>%" align="center" style="font-weight: bold;"><?php echo $products_price_buy; ?></td>
			
			<?php 
				$queryBuy = "select p.products_id, p.products_weight, pd.products_name, pd.products_features, pd.products_specifications, pd.products_description, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_rental_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id"; 
				$queryBuy .= " from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '40' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'";
				$product_info_queryBuy = tep_db_query($queryBuy);
				$product_info_buy = tep_db_fetch_array($product_info_queryBuy);
				$products_price_buy = $currencies->display_price($product_info_buy['products_price'], tep_get_tax_rate($product_info_buy['products_tax_class_id']));
			?>
			<td width="<?php echo (78/sizeof($com_products));?>%" align="center" style="font-weight: bold;"><?php echo $products_price_buy; ?></td>
		</tr>
		*/ ?>
		<tr bgcolor="<?=$color[(++$i%2)]?>">
			<th width="300" style="width: 300px;" valign="top" align="left">&nbsp;</th>
			<?php foreach($com_products as $pr_id): ?>
				<td align="center" style="text-align: left; vertical-align: top; padding: 10px;">
					<?php  //echo $currencies->display_price($products_array[$pr_id]['products_price'], ''); 
						if ($pr_id == 25) $pr_id = 34;
						if ($pr_id == 1) $pr_id = 32;
						if ($pr_id == 57) $pr_id = 55;
						if ($pr_id == 40) $pr_id = 39;
					?>
					<?php /*
					<a style="width: 150px;" class="button blue-button" href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id='.get_buy2rent_id($pr_id)); ?>"><span>View Details</span></a>
					*/ ?>
					<?php echo tep_draw_form('cart_quantity_'.$pr_id, tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) . '&products_id='.$pr_id.'&action=add_product')); ?>
					
					<?php
						// begin Product Extra Fields
						$queryBuy = "select p.products_id, p.products_weight, pd.products_name, pd.products_features, pd.products_specifications, pd.products_description, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_rental_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id";
						
						foreach ($epf as $e) {
						  $queryBuy .= ", pd." . $e['field'];
						}
						
						if ((int)$pr_id == 39) {
							$productBuyId = 40;
						} elseif ((int)$pr_id == 32) {
							$productBuyId = 1;
						} elseif ((int)$pr_id == 34) {
							$productBuyId = 25;
						} elseif ((int)$pr_id == 55) {
							$productBuyId = 57;
						}
						
						$queryBuy .= " from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$productBuyId . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'";
						
						
						$product_info_queryBuy = tep_db_query($queryBuy);
						// end Product Extra Fields
						
						$product_info_buy = tep_db_fetch_array($product_info_queryBuy);
						$products_price_buy = $currencies->display_price($product_info_buy['products_price'], tep_get_tax_rate($product_info_buy['products_tax_class_id']));
					?>					
					<div class="infoBlock_<?php echo $pr_id; ?>" style="padding-bottom: 15px;">
						<input type="hidden" name="products_id" value="<?php echo (int)$pr_id; ?>" />
						
						<input id="<?php echo $pr_id; ?>" class="productAction" name="ProductAction" value="buy" type="radio" /><label style="width: 175px; font-size: 12px;" class="actionLabel" for="BuyItem">Buy</label><span style="display: block; padding-left: 20px; font-weight: bold;"><?php echo $products_price_buy; ?></span>
						
						<?php if ((int)$pr_id == 39): ?>
						<input type="hidden" name="productBuyId" value="40" />
						<?php echo tep_draw_hidden_field('rental2purchase_order_id', 40); ?>
						<?php elseif ((int)$pr_id == 32): ?>
						<input type="hidden" name="productBuyId" value="1" />
						<?php echo tep_draw_hidden_field('rental2purchase_order_id', 1); ?>
						<?php elseif ((int)$pr_id == 34): ?>
						<input type="hidden" name="productBuyId" value="25" />
						<?php echo tep_draw_hidden_field('rental2purchase_order_id', 25); ?>
						<?php elseif ((int)$pr_id == 55): ?>
						<input type="hidden" name="productBuyId" value="57" />
						<?php echo tep_draw_hidden_field('rental2purchase_order_id', 57); ?>
						<?php endif; ?>
						
						<div id="PurchaseAccessories_<?php echo $pr_id; ?>" style="display: none;">
							<?php
								$products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$productBuyId . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "'");
								$products_attributes = tep_db_fetch_array($products_attributes_query);
								
								if ($products_attributes['total'] > 0) {
							?>
								<table border="0" cellspacing="0" cellpadding="0" style="display: none;">
									<tr>
										<td class="main" colspan="2"><?php //echo TEXT_PRODUCT_OPTIONS; ?></td>
									</tr>
							<?php
									$check_output	=	array();
									$products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name, popt.option_image from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$productBuyId . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "' order by popt.products_options_name");

									while ($products_options_name = tep_db_fetch_array($products_options_name_query)) {
										if($products_options_name['products_options_id'] !== '2') {
											$check_output[]	=	get_attributes_checkbox_format($products_options_name, $productBuyId, true);
											continue;
										}
									
										$products_options_array = array();
										$products_options_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . (int)$productBuyId . "' and pa.options_id = '" . (int)$products_options_name['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . (int)$languages_id . "' order by pov.sorting_order");
										
										while ($products_options = tep_db_fetch_array($products_options_query)) {
											$products_options_array[] = array('id' => $products_options['products_options_values_id'], 'text' => $products_options['products_options_values_name']);
											
											//if ($products_options['options_values_price'] >= 0) {
												$products_options_array[sizeof($products_options_array)-1]['text'] .= ' - ' . $currencies->display_price($products_options['options_values_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) .'  ';
											//} 
										}

										if (isset($cart->contents[$productBuyId]['attributes'][$products_options_name['products_options_id']])) {
											$selected_attribute = $cart->contents[$productBuyId]['attributes'][$products_options_name['products_options_id']];
										} else {
											$selected_attribute = false;
										}
							?>
									<tr style="display: none;">
										<td width="200"><span style="font-size:14px; font-weight:normal;"><?php echo $products_options_name['products_options_name'] . ':'; ?></span></td>
										<td class="main" align="left"><?php //echo tep_draw_pull_down_menu('id[' . $products_options_name['products_options_id'] . ']', $products_options_array, $selected_attribute); ?></td>
									</tr>
							<?php			
									}
							?>
								</table>
							<?php
								}
							?>
						
							<?php /*if ($check_output != NULL): ?>
							<table id="AdditionalAccessories" width="100%" cellpadding="5" cellspacing="5" border="0" style="border: 1px solid #979797; margin-top: 10px;">
								<tr>
									<th style="background-color: #379ad6; color: #fff;" colspan="2">Additional Accessories</th>
								</tr>
								<?php foreach($check_output as $c_attr): ?>
								<?php	echo $c_attr; ?>
								<?php endforeach; ?>
							</table>
							<?php endif;*/ ?>
						</div>
					</div>
					<div class="infoBlock_<?php echo $pr_id; ?>" >
						<input id="<?php echo $pr_id; ?>" <?php if ($pr_id == 39): ?>checked="checked"<?php endif; ?> class="productAction" name="ProductAction" value="rent" type="radio" /><label style="width: 175px; font-size: 12px;" class="actionLabel" for="RentItem">Rent <span style="text-transform: lowercase; font-weight: normal; font-style: italic;font-size: 11px;">(1 month minimum)</span></label> 
						
						<?php if (MODULE_SALE_ENABLED && MODULE_SALE_TYPE == 'SINGLE'): ?>
						<?php 	if (MODULE_SALE_PRODUCT_ID == (int)$pr_id): ?>
						<span style="display: block; padding-left: 20px; font-weight: bold;width: 220px;">
						<?php
							echo '$'.MODULE_SALE_PRODUCT_BEFORE_SALE_AMOUNT.' /month';
							/*switch((int)$pr_id) {
								case 39:
									echo '$140.00 /month';
									break;
								case 32:
									echo '$100.00 /month';
									break;
								case 34:
									echo '$75.00 /month';
									break;
								case 55:
									echo '$100.00 /month';
									break;
							}*/
						?>&nbsp;-&nbsp;
						<?php 
							$queryBuy = "select p.products_id, p.products_weight, pd.products_name, pd.products_features, pd.products_specifications, pd.products_description, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_rental_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id"; 
							$queryBuy .= " from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '".$pr_id."' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'";
							$product_info_queryBuy = tep_db_query($queryBuy);
							$product_info_buy = tep_db_fetch_array($product_info_queryBuy);
							$products_price_rental = $currencies->display_price($product_info_buy['products_rental_price'], tep_get_tax_rate($product_info_buy['products_tax_class_id']));
						?>
						<?php echo $products_price_rental; ?> /week
						</span>
						<div style="<?php /* background-color: #D21212; color: #FFFFFF; float: right; font-family: 'HelveticaNeue-BoldCond',sans-serif; font-size: 14px; font-weight: normal; padding: 3px 8px; */ ?>" class="sale_block">
							<img src="http://www.kneewalkercentral.com/images/info-box-sale.jpg" />
							<!--<b>Sale!</b> $<?php /*echo number_format($_products[(int)$pr_id]['monthPrice'], 2);*/ ?> for first month rental!-->
						</div>			
						<?php	endif; ?>
						<?php else: ?>
						<span style="display: block; padding-left: 20px; font-weight: bold;">
						<?php
							echo '$'.number_format($_products[(int)$pr_id]['monthPrice'], 2).' /month';
							/*switch((int)$pr_id) {
								case 39:
									echo '$140.00 /month';
									break;
								case 32:
									echo '$100.00 /month';
									break;
								case 34:
									echo '$75.00 /month';
									break;
								case 55:
									echo '$100.00 /month';
									break;
							}*/
						?>&nbsp;-&nbsp;
						<?php 
							$queryBuy = "select p.products_id, p.products_weight, pd.products_name, pd.products_features, pd.products_specifications, pd.products_description, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_rental_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id"; 
							$queryBuy .= " from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '".$pr_id."' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'";
							$product_info_queryBuy = tep_db_query($queryBuy);
							$product_info_buy = tep_db_fetch_array($product_info_queryBuy);
							$products_price_rental = $currencies->display_price($product_info_buy['products_rental_price'], tep_get_tax_rate($product_info_buy['products_tax_class_id']));
						?>
						<?php echo $products_price_rental; ?> /week
						</span>
						<?php endif; ?>
						
						<?php if (MODULE_SALE_ENABLED && MODULE_SALE_TYPE == 'MULTI'): ?>
						<div style="text-align: right; padding-top: 10px;">
							<img src="http://www.kneewalkercentral.com/images/info-box-sale-multi.png" />
						</div>
						<?php endif; ?>
						
						<div id="RentalDuration_<?php echo $pr_id; ?>" style="clear:both; padding-left: 10px; <?php if ($pr_id != 39): ?>display: none;<?php endif; ?>">
							<ul>
								<li style="list-style: none;padding-top: 15px;">
									<label for="start_date_s_<?php echo $pr_id; ?>" style="font-weight: bold; padding-bottom: 0; display: block;">Rental Start Date</label>
									<div style="border: 1px solid #a1a1a1; background-color: #fff; padding: 5px 10px; width: 195px; overflow: hidden;">
										<input style="margin-top: 4px; border: 0; background-color: #fff; float: left; width: 149px;" id="start_date_s_<?php echo $pr_id; ?>" type="text" readonly="readonly" name="start_date_s" value="<?php echo date('m-d-Y'); ?>"  size="25"/>
										<image class="datapickerButton" id="<?php echo $pr_id; ?>" src="./images/_theme/calendar_icon.jpg" style="float: left; cursor: pointer;" />
									</div>
									<input id="start_date_<?php echo $pr_id; ?>" type="hidden" readonly="readonly" name="start_date"  size="25"/>
									<div style="display: none;" id="end_date_text_<?php echo $pr_id; ?>"></div><input type="hidden" readonly="" value="" id="end_date_<?php echo $pr_id; ?>" />
								</li>
								
								<li style="list-style: none;padding-top: 15px;">
									<label for="RentalDuration" style="font-weight: bold; padding-bottom: 0; display: block; position: relative;">
										Rental Duration <a class="openSimpleTooltip" href="javascript://<?php //echo tep_href_link('rental_duration_info.php'); ?>"><img src="http://kneewalkercentral.com/images/compare_info.png" /></a>
									</label>
									<?php
										$products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$pr_id . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "'");
										$products_attributes = tep_db_fetch_array($products_attributes_query);
										
										if ($products_attributes['total'] > 0) {
									?>
										<table border="0" cellspacing="0" cellpadding="0">
									<?php
											$check_output	=	array();
											$products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name, popt.option_image from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$pr_id . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "' order by popt.products_options_name");

											while ($products_options_name = tep_db_fetch_array($products_options_name_query)) {
												if($products_options_name['products_options_id'] !== '2') {
													$check_output[]	=	get_attributes_checkbox_format($products_options_name, $pr_id);
													continue;
												}
											
												$products_options_array = array();
												$products_options_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . (int)$pr_id . "' and pa.options_id = '" . (int)$products_options_name['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . (int)$languages_id . "' order by pov.sorting_order");
												
												while ($products_options = tep_db_fetch_array($products_options_query)) {
													$products_options_array[] = array('id' => $products_options['products_options_values_id'], 'text' => $products_options['products_options_values_name']);
													
													//if ($products_options['options_values_price'] >= 0) {
														$products_options_array[sizeof($products_options_array)-1]['text'] .= ' - ' . $currencies->display_price($products_options['options_values_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) .'  ';
													//} 
												}

												if (isset($cart->contents[$pr_id]['attributes'][$products_options_name['products_options_id']])) {
													$selected_attribute = $cart->contents[$pr_id]['attributes'][$products_options_name['products_options_id']];
												} else {
													$selected_attribute = false;
												}
									?>
											<tr>
												<td style="display: none;" width="200"><span style="font-size:14px; font-weight:normal;"><?php echo $products_options_name['products_options_name'] . ':'; ?></span></td>
												<td class="main" align="left" style="vertical-align: top; text-align: left; margin: 0!important; padding: 0!important;"><?php echo tep_draw_pull_down_menu('id[' . $products_options_name['products_options_id'] . ']', $products_options_array, $selected_attribute, 'style="padding: 10px 6px;"'); ?></td>
											</tr>
									<?php			
											}
									?>
										</table>
									<?php
										}
									?>
								</li>
							</ul>
						
							<?php /*if ($check_output != NULL): ?>
							<table id="AdditionalAccessories" width="100%" cellpadding="5" cellspacing="5" border="0" style="border: 1px solid #979797; margin-top: 25px;">
								<tr>
									<th style="background-color: #379ad6; color: #fff;" colspan="2">Additional Accessories</th>
								</tr>
								<?php foreach($check_output as $c_attr): ?>
								<?php	echo $c_attr; ?>
								<?php endforeach; ?>
							</table>
							<?php endif;*/ ?>
						</div>
						<br />
						<div style="border-top: 1px solid #bdbdbd; overflow: hidden; height: 50px; padding-top: 10px;">
							<a style="float: left; display: block; padding-top: 10px; color: #1c84c3; font-weight: bold;" href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id='.$pr_id); ?>">Details</a>
							<a id="PlaceOrderSubmit_<?php echo $pr_id; ?>" data-productid="<?php echo $pr_id; ?>" class="custom-button addtocart" style="float: right; padding: 5px 10px" href="javascript://">Add to Cart</a>
						</div>
					</div>
					</form>
				</td>
			<?php endforeach; ?>
		</tr>
	</tbody>
	
	<tfoot>
		<tr>
			<td></td>
		</tr>
	</tfoot>
</table>
 
<script type="text/javascript">
	var loadingVarAni;

	function add_day(olddate, days) {	
		iYear	=	olddate.getFullYear();
		iMonth	=	olddate.getMonth();
		idays	=	olddate.getDate();
					
		ndate	=	new Date(iYear, iMonth, idays-0+(days) );
						
		nYear	=	ndate.getFullYear();
		nMonth	=	ndate.getMonth() + 1;
		ndays	=	ndate.getDate();

		return nYear+'-'+nMonth+'-'+ndays;
	}

	$(document).ready( function(){
	
		$('.openSimpleTooltip').click(function() { 
			var offset = $(this).offset();
			
			$('#helpcontent_containter').css('left', offset.left);
			$('#helpcontent_containter').css('top', offset.top + 15);
			
			if ($("#helpcontent_containter").is(":visible")) {
				$('#helpcontent_containter').hide();
			} else {
				$('#helpcontent_containter').show();
			}
		});
				
		/*$('.productAction').change(function() { 
			if ($(this).val() == 'buy') {
				$('#RentalDuration_'+$(this).attr('id')).hide();
				$('#PurchaseAccessories_'+$(this).attr('id')).show();
				
				$('#PlaceOrderSubmit_'+$(this).attr('id')).attr('href', 'javascript://');
				//$('#PlaceOrderSubmit_'+$(this).attr('id')).html('Buy This &gt;<span class="single-arrow"></span>');
				
			} else if ($(this).val() == 'rent') {
				$('#RentalDuration_'+$(this).attr('id')).show();
				$('#PurchaseAccessories_'+$(this).attr('id')).hide();
				
				$('#PlaceOrderSubmit_'+$(this).attr('id')).attr('href', 'javascript://');
				//$('#PlaceOrderSubmit_'+$(this).attr('id')).html('Rent This &gt;<span class="single-arrow"></span>');
			}
		});*/
		
		$('.addtocart').click(function() { 
			if ($('form[name=cart_quantity_'+$(this).data('productid')+'] input:checked').length > 0)
				$('form[name=cart_quantity_'+$(this).data('productid')+']').submit();
			else
				alert('Please select an option before adding to cart!');
		});
		
		$('.datapickerButton').click(function() { 
			$('#start_date_s_'+$(this).attr('id')).datepicker('show');
		});
										
		weekText	=	$("select").find('option').filter(':selected').text();
		countWeek	=	weekText.replace(/(^\d+)(.+$)/i,'$1');															
		if( weekText.indexOf("month") != -1) countWeek = countWeek * 4;
		
		<?php foreach($com_products as $pr_id): ?>
		<?php  
				if ($pr_id == 25) $pr_id = 34;
				if ($pr_id == 1) $pr_id = 32;
				if ($pr_id == 57) $pr_id = 55;
				if ($pr_id == 40) $pr_id = 39;
		?>
		$('#start_date_s_<?php echo $pr_id; ?>').datepicker({
			defaultDate: "+1d",
			dateFormat:"mm-dd-yy", 
			changeMonth: true,
			numberOfMonths: 2,
			onClose: function( selectedDate ) {
				$('#start_date_<?php echo $pr_id; ?>').val(selectedDate.split('-')[2]+'-'+selectedDate.split('-')[0]+'-'+selectedDate.split('-')[1]);
			},
			beforeShowDay: function(date) {
				var day = date.getDay();
				return [(day != 0), ''];
			}
		});
		
		new_date	=	add_day( new Date('<?=date("D M j Y G:i:s")?>'), (countWeek * 7) - 1);
		$('#end_date_<?php echo $pr_id; ?>').val(new_date);
		
		f_date	=	new Array();
		f_date	=	new_date.split("-");
		
		$('#end_date_text_<?php echo $pr_id; ?>').html( f_date[1] +'/'+ f_date[2] +'/'+ f_date[0]  );
		
		$('#start_date_s_<?php echo $pr_id; ?>').val('<?php echo date('m-d-Y', strtotime('+1 day')); ?>');
		$('#start_date_<?php echo $pr_id; ?>').val('<?php echo date('Y-m-d'); ?>');
		<?php endforeach; ?>
	});
	
	$("select").change(function(){
		calculateWeekDuration();				
	});
	
	function calculateWeekDuration()
	{
		date_arr	=	$('#start_date_s').val();		

		date_arr	=	date_arr.split( '-' );
		olddate		=	new Date( date_arr[2], date_arr[0]-1, date_arr[1] );
						
		weekText	=	$("select").find('option').filter(':selected').text();
		countWeek	=	weekText.replace(/(^\d+)(.+$)/i,'$1');
		if( weekText.indexOf("month") != -1) countWeek = countWeek * 4;
									
		new_date	=	add_day( olddate, (countWeek * 7) - 1 ) ;				
		$('#end_date').val(new_date);	

		f_date	=	new Array();
		f_date	=	new_date.split("-");
		
		$('#end_date_text').html( f_date[1] +'/'+ f_date[2] +'/'+ f_date[0]  );
	}
						
	$(document).ready(function() { 
		$('.fancybox').click(function() { 
			var productId = $(this).data('productid');
			$.fancybox.open({
				<?php if (isset($_GET['osCsid'])): ?>
				href : '<?php echo HTTP_SERVER.DIR_WS_HTTP_CATALOG; ?>product_reviews.php?products_id='+productId+'<?php echo '&osCsid='.$_GET['osCsid']; ?>',
				<?php else: ?>
				href : '<?php echo HTTP_SERVER.DIR_WS_HTTP_CATALOG; ?>product_reviews.php?products_id='+productId,
				<?php endif; ?>
				type : 'iframe',
				padding : 5
			});
		});
		
		$(".various").fancybox({
				maxWidth	: 450,
				maxHeight	: 300,
				fitToView	: false,
				width		: '70%',
				height		: '70%',
				autoSize	: false,
				closeClick	: false,
				openEffect	: 'none',
				closeEffect	: 'none'
			});
		
		$('#ShippingRateForm').submit(function() {
			if ($('#ShippingRateForm_State').val() == '' || $('#ShippingRateForm_ZipCode').val() == '') {
				alert('State and Zip Code are required to calculate shipping time!!!');
			} else {
				//	AJAX CALL
				$.ajax({
					url: "<?php echo HTTP_SERVER.DIR_WS_HTTP_CATALOG; ?>ship_cal_ajax_compare.php",
					type: "POST",
					data: $(this).serialize(),
					dataType: 'JSON',
					
					beforeSend: function() {
						//	INVOKE THE OVERLAY
						$('.shipping-rate').html('<span style="font-weight: bold; color: #c24a53;">Loading Rates...</span>');
						loadingVarAni = window.setInterval('waiting()', 1000);
						
						$('#ShippingRateFormImageButton').attr('disabled', true);
					},
					success: function(data) { 
						window.clearInterval(loadingVarAni);
						
						if (typeof(data.error) === 'undefined') {
							//	RETURN RATES
							$.each(data, function(idx, obj) { 
								if (obj.id == 'FEDEXGROUND') {
									//$('.shipping-rate').html('<strong style="text-transform: lowercase;">1 day</strong><br />Ground Home Delivery - <strong>free</strong><br /><a style="text-decoration: underline; font-size: 11px; cursor: pointer;" class="viewMoreOption">View more shipping options</a>');
									
									$('.shipping-rate-'+idx).html('<strong style="text-transform: lowercase;">'+obj.freedate+' day(s)</strong><br />Ground Home Delivery - <strong>free</strong><br /><a style="text-decoration: underline; font-size: 11px; cursor: pointer;" class="viewMoreOption">View more shipping options</a>');
									
									$('.viewMoreOption').click(function() { 
										$.fancybox.open({
											href : '<?php echo HTTP_SERVER.DIR_WS_HTTP_CATALOG; ?>ship_cal_ajax.php?state='+$('#ShippingRateForm_State').val()+'&state_name='+$('#ShippingRateForm_State option:selected').text()+'&zipcode='+$('#ShippingRateForm_ZipCode').val()+'&withStyle',
											type : 'iframe',
											padding : 5
										});
									});
								}
							});
						} else {
							//	RETURN ERROR
							$('.shipping-rate').html('');
							alert('Please verify your State and Zip Code.');
						}
						
						$('#ShippingRateFormImageButton').attr('disabled', false);
					},
					error: function(ex) {
						alert("An error occured: " + ex.status + " " + ex.statusText);
						
						window.clearInterval(loadingVarAni);
						$('#ShippingRateFormImageButton').attr('disabled', false);
					}
				});
			}
			
			return false;
		});
	});
	
	function waiting()
	{
		if ($('.shipping-rate').html() == '')
			$('.shipping-rate').html('<span style="font-weight: bold; color: #c24a53;">Loading Rates...</span>');
		else
			$('.shipping-rate').html('');
	}
</script>