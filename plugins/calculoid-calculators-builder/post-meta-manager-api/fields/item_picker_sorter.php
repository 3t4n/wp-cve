<?php
	global $flexfit_design_kit_item_types;
	$adjust_for_fit = isset($adjust_for_fit) && is_bool($adjust_for_fit) ? $adjust_for_fit : false;
	$adjust_for_visor = isset($adjust_for_visor) && is_bool($adjust_for_visor) ? $adjust_for_visor : false;
	
	$saved_value = str_replace('&quot;', '"', $saved_value);
	$saved_value = str_replace('&uuot;', '"', $saved_value);
	$saved_value = stripcslashes($saved_value);
	$saved_value = json_decode($saved_value);
	$ips = new IPS($saved_value);
?>
<div id="<?php _e($identifier) ?>" class="ipsRoot">
	<?php if ($adjust_for_fit) { ?>
		<br />
		<a href="#" class="button IPS_CHECK_EVERY_GROUP">Check Every Group</a>
		<a href="#" class="button IPS_UNCHECK_EVERY_GROUP">Uncheck Every Group</a>
		<br /><br />
		<hr />
	<?php } ?>
	<?php foreach ($flexfit_design_kit_item_types as $item_type_name => $item_type_key) { ?>
		<?php if ($adjust_for_fit) { if ($item_type_key == 'fit') continue; } ?>
		<?php if ($adjust_for_visor) { if ($item_type_key == 'visor') continue; } ?>
		<?php if ($adjust_for_fit) { if ($item_type_key != 'visor') continue; } //  && $item_type_key != 'visor_curve' ?>
		<?php if ($adjust_for_visor) { if ($item_type_key != 'visor_curve') continue; } ?>
		<div class="ipsGroup IPS_GROUP" data-itemkey="<?php _e($item_type_key) ?>">
			<h2><?php _e($item_type_name) ?></h2>
			<?php
				global $post, $wpdb;
				$query = "SELECT {$wpdb->prefix}posts.* 
					FROM {$wpdb->prefix}posts
					INNER JOIN {$wpdb->prefix}postmeta ON ( {$wpdb->prefix}posts.ID = {$wpdb->prefix}postmeta.post_id ) 
					INNER JOIN {$wpdb->prefix}postmeta AS mt1 ON ( {$wpdb->prefix}posts.ID = mt1.post_id ) 
					WHERE 1 =1
					AND {$wpdb->prefix}posts.post_type =  '" . CPT_DESIGN_KIT_ITEM . "'
					AND (
						{$wpdb->prefix}posts.post_status =  'publish'
					)
					AND (
						(
							{$wpdb->prefix}postmeta.meta_key =  'type'
							AND CAST( {$wpdb->prefix}postmeta.meta_value AS CHAR ) =  '{$item_type_key}'
						)
						AND (
							(
								mt1.meta_key =  'available_for_design_kits'
								AND CAST( mt1.meta_value AS CHAR ) LIKE  '%\"{$post->ID}\"%'
							)
							OR (
								mt1.meta_key =  'available_for_design_kits'
								AND CAST( mt1.meta_value AS CHAR ) LIKE  '%\"0\"%'
							)
							OR (
								mt1.meta_key =  'available_for_design_kits'
								AND CAST( mt1.meta_value AS CHAR ) LIKE  '%i:0;i:0;%'
							)
						)
					)
					GROUP BY {$wpdb->prefix}posts.ID
					ORDER BY {$wpdb->prefix}posts.post_date ASC";
				$all_posts_tmp = $wpdb->get_results($query, OBJECT);
				$all_posts = new stdClass();
				$all_posts->posts = $all_posts_tmp;
				unset($all_posts_tmp);
				// _e($query);die();
			?>		
			<?php if (!empty($all_posts->posts)) { ?>	
				<a href="#" class="button IPS_CHECK_ALL">Check All</a>
				<a href="#" class="button IPS_UNCHECK_ALL">Uncheck All</a>
				<br />
				<?php if (!$adjust_for_fit) { ?>
					<?php if ($adjust_for_visor) { ?>
						<label class="ispLbl" style="display: none;">Show Group&nbsp;&nbsp;<input type="checkbox" name="ipsShowGroup" class="IPS_SHOW_GROUP" disabled="disabled" checked="checked" /></label>
					<?php } else { ?>
						<label class="ispLbl" <?php if ($item_type_key == 'fit') { ?>style="display: none;"<?php } ?>>Show Group&nbsp;&nbsp;<input type="checkbox" name="ipsShowGroup" class="IPS_SHOW_GROUP" <?php if (empty($all_posts->posts)) { ?>disabled="disabled"<?php } else { _e($ips->show_group($item_type_key) === true || $ips->show_group($item_type_key) === null ? 'checked="checked"' : ''); } ?> /></label>
					<?php } ?>
				<?php } else { ?>
					<label class="ispLbl" style="display: none;">Show Group&nbsp;&nbsp;<input type="checkbox" name="ipsShowGroup" class="IPS_SHOW_GROUP" disabled="disabled" checked="checked" /></label>
				<?php } ?>
				<div class="ipsSubgroup IPS_SUBGROUP">
						<hr />
						<?php $all_posts->posts = $ips->sort_posts($item_type_key, $all_posts->posts); ?>
						<ul class="ipsItems">
							<?php foreach ($all_posts->posts as $p) { ?>
								<li class="ui-state-default IPS_SORTABLE_ITEM" data-id="<?php _e($p->ID) ?>"><?php _e($p->post_title) ?>&nbsp;<label><input type="checkbox" class="IPS_CHECK_ITEM" <?php _e($p->ips_check ? 'checked="checked"' : '') ?> /></label><?php if (!$adjust_for_fit && $item_type_key == 'fit') { ?><br /><label>Popular: <input type="checkbox" class="IPS_POPULAR_ITEM" <?php _e($p->ips_popular ? 'checked="checked"' : '') ?> /></label><?php if (isset($p->show_on) && !empty($p->show_on)) { ?><br /><?php _e($p->show_on) ?><?php } ?><?php } ?></li>
							<?php } ?>
						</ul>
					<div class="clear"></div>
				</div>
			<?php } else { ?>
				<h4>No design kit items found...</h4>
			<?php } ?>
			<div class="clear"></div>
			<br />
			<hr />
		</div>
	<?php } ?>
	<input type="hidden" name="<?php _e($identifier) ?>" class="IPS_SAVE_FIELD" />
</div>
<style type="text/css">
	.ipsRoot h2 { border: 0; padding: 0 !important; cursor: default !important; margin-bottom: 10px; }
	.ipsRoot h4 { border: 0; padding: 0 !important; cursor: default !important; }
	.ipsRoot .ispLbl { margin: 10px 0; display: block; }
	.ipsItems { list-style-type: none; margin: 0; padding: 0; width: 100%; }
	.ipsItems li { margin: 0 3px 3px 3px; padding: 0.4em; font-size: 1em; height: auto; float: left; width: 20%; text-align: center; line-height: 24px; cursor: move; }
	.ipsItems li label { font-size: 1em; }
	.ipsSubgroup { display: none; }
</style>
<script type="text/javascript">
	var key = "";
	jQuery(function ($)
	{
		var ipsUpdateSort = function ()
		{
			var $ = jQuery;
			var $root = $("#<?php _e(PostMetaManagerHelper::sanitize_css_selector($identifier)) ?>");
			var data = {};
			var i = 0;
			$root.find(".IPS_GROUP").each(function ()
			{
				var $this = $(this);
				var key = $this.data("itemkey");
				console.log($root.get(0));
				console.log($this.get(0));
				console.log(key);
				data[i] = {};
				data[i]["key"] = key;
				data[i]["show_group"] = $this.find(".IPS_SHOW_GROUP").is(":checked");
				data[i]["items"] = {};
				var j = 0;
				$this.find(".IPS_SORTABLE_ITEM").each(function ()
				{
					var $this = $(this);
					var id = $this.data("id");
					data[i]["items"][j] = {};
					data[i]["items"][j]["id"] = id;
					data[i]["items"][j]["check"] = $this.find(".IPS_CHECK_ITEM").is(":checked"); // show or use
					data[i]["items"][j]["popular"] = $this.find(".IPS_POPULAR_ITEM").is(":checked");
					j++;
				});
				i++;
			});
			// console.log(data);
			var jsonData = JSON.stringify(data);
			$root.find(".IPS_SAVE_FIELD").val(jsonData);
			<?php if ($adjust_for_fit) { ?>
				// $root.find(".ipsGroup:not([data-itemkey=visor]):not([data-itemkey=visor_curve])").each(function ()
				// {
					// var $this = $(this);
					// $this.hide(0);
					// $this.find(".IPS_SORTABLE_ITEM .IPS_CHECK_ITEM").attr("checked", "checked");
				// });
			<?php } ?>		
		}
	
		var $root = $("#<?php _e(PostMetaManagerHelper::sanitize_css_selector($identifier)) ?>");
		
		$root.find(".IPS_CHECK_ALL").click(function (e)
		{
			e.preventDefault();
			var $this = $(this);
			var $ipsGroup = $this.parents(".IPS_GROUP").first();
			$ipsGroup.find(".IPS_SORTABLE_ITEM .IPS_CHECK_ITEM").attr("checked", "checked");
			ipsUpdateSort();
		});
		
		$root.find(".IPS_UNCHECK_ALL").click(function (e)
		{
			e.preventDefault();
			var $this = $(this);
			var $ipsGroup = $this.parents(".IPS_GROUP").first();
			$ipsGroup.find(".IPS_SORTABLE_ITEM .IPS_CHECK_ITEM").removeAttr("checked");
			ipsUpdateSort();
		});		
		
		$root.find(".IPS_CHECK_EVERY_GROUP").click(function (e)
		{
			e.preventDefault();
			var $this = $(this);
			$root.find(".IPS_SORTABLE_ITEM .IPS_CHECK_ITEM").attr("checked", "checked");
			ipsUpdateSort();
		});
		
		$root.find(".IPS_UNCHECK_EVERY_GROUP").click(function (e)
		{
			e.preventDefault();
			var $this = $(this);
			$root.find(".IPS_SORTABLE_ITEM .IPS_CHECK_ITEM").removeAttr("checked");
			ipsUpdateSort();
		});		
		
		$root.find(".IPS_SORTABLE_ITEM .IPS_CHECK_ITEM, .IPS_SHOW_GROUP, .IPS_POPULAR_ITEM").change(ipsUpdateSort);
		
		$root.find(".IPS_SHOW_GROUP").change(function ()
		{
			var $this = $(this);
			var $ipsGroup = $this.parents(".IPS_GROUP").first();
			if ($this.is(":checked"))
			{
				$ipsGroup.find(".IPS_SUBGROUP").fadeIn("fast");
			}
			else
			{
				$ipsGroup.find(".IPS_SUBGROUP").fadeOut("fast");
			}
		}).trigger("change");
		
		$root.find(".ipsItems").sortable({
			stop: ipsUpdateSort
		}).disableSelection();
		
		// ipsUpdateSort();
	});
</script>