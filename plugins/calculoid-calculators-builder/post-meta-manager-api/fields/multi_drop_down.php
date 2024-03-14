<?php
	$has_groups  = isset($has_groups)  && is_bool($has_groups)  ? $has_groups  : false;
	$sortable    = isset($sortable)    && is_bool($sortable)    ? $sortable    : true;
	$max_items   = isset($max_items)   && $max_items > 0        ? $max_items   : 9999;
	$depth       = isset($depth)       && $depth > 0            ? $depth       : 1;
	
	if (count($items) > 0) {
		$first_value = reset($items);
		?>
		<div class="root <?php _e($identifier) ?>">
			<a href="#" class="button DD_ADD">Add</a>
			<br />
			<div class="dd dropdownWrp root" style="display: none;">
				<ol class="dd-list">
					<li class="dd-item dd3-item HTML_LIST_ITEM <?php _e($field_data['key']) ?>" data-id="<?php _e($first_value) ?>">
						<div class="dd-handle dd3-handle">Drag</div>
						<div class="dd3-content <?php _e($field_data['key']) ?> <?php if ($sortable) { ?>ui-state-default<?php } ?> <?php _e($field_data['additional_class']) ?>">
							<?php PostMetaManagerHelper::generate_dropdown($items, '', $has_groups, ''); ?>
							<a href="#" id="<?php _e($identifier) ?>" class="button DD_REMOVE">Remove</a>
						</div>	
					</li>
				</ol>
			</div>	
			<div class="dd nestable <?php _e($identifier) ?>">
				<ol class="dd-list HTML_LIST_CANVAS <?php _e($identifier) ?>">		
					<?php 
						$i = 0;
						
						if (!is_array($saved_value)) {
							if (strlen($saved_value) > 0) {
								$saved_value = str_replace('&quot;', '"', $saved_value);
								$saved_value = stripslashes($saved_value);
								$saved_value = json_decode($saved_value);
							} else {
								$saved_value = array();
							}
						}
						
						if (is_array($saved_value)) {
							foreach ($saved_value as $value) {
								$saved_value = htmlspecialchars($value->id);
								$children = $value->children;
								?>
								<li class="dd-item dd3-item HTML_LIST_ITEM <?php _e($field_data['key']) ?>" data-id="<?php _e($saved_value) ?>">
									<div class="dd-handle dd3-handle">Drag</div>
									<div class="dd3-content <?php _e($field_data['key']) ?> <?php if ($sortable) { ?>ui-state-default<?php } ?> <?php _e($field_data['additional_class']) ?>">
										<?php PostMetaManagerHelper::generate_dropdown($items, $saved_value, $has_groups, ''); ?>
										<a href="#" id="<?php _e($identifier) ?>" class="button DD_REMOVE">Remove</a>
									</div>	
									<?php
										if (!empty($children)) { 
											foreach ($children as $child) {
												$saved_value = htmlspecialchars($child->id);
												?>
												<ol class="dd-list">
													<li class="dd-item dd3-item HTML_LIST_ITEM <?php _e($field_data['key']) ?>" data-id="<?php _e($saved_value) ?>">
														<div class="dd-handle dd3-handle">Drag</div>
														<div class="dd3-content <?php _e($field_data['key']) ?> <?php if ($sortable) { ?>ui-state-default<?php } ?> <?php _e($field_data['additional_class']) ?>">
															<?php PostMetaManagerHelper::generate_dropdown($items, $saved_value, $has_groups, ''); ?>
															<a href="#" id="<?php _e($identifier) ?>" class="button DD_REMOVE">Remove</a>
														</div>
													</li>
												</ol>
										<?php } ?>
									<?php } ?>					
								</li>												
								<?php
								$i++;
							}
						}
					?>				
				</ol>
			</div>
			<input type="hidden" value="" name="<?php _e($identifier) ?>" class="HIDDEN_NESTED_FIELD">
		</div>
		<script type="text/javascript">
			jQuery(function ($)
			{
				var $root = $(".root.<?php _e(PostMetaManagerHelper::sanitize_css_selector($identifier)) ?>");
				var $add = $root.find(".DD_ADD");
				var $remove = $root.find(".DD_REMOVE");
				var $hiddenField = $root.find(".HIDDEN_NESTED_FIELD[name=<?php _e(PostMetaManagerHelper::sanitize_css_selector($identifier)) ?>]");
				
				function serializedList ()
				{
					return JSON.stringify($(".nestable.<?php _e(PostMetaManagerHelper::sanitize_css_selector($identifier)) ?>").nestable("serialize"));
				}				
				
				$add.click(function (e)
				{
					e.preventDefault();
					if ($root.find(".HTML_LIST_CANVAS .HTML_LIST_ITEM").length >= <?php _e($max_items) ?>) return;
					var $dd = $root.find(".dropdownWrp.root .HTML_LIST_ITEM").clone();
					$dd.removeClass("root").fadeIn(0);
					$root.find(".HTML_LIST_CANVAS").append($dd);
					var $firstOption = $dd.find(".dd3-content select option").first();
					$dd.find(".dd3-content select").val($firstOption.val());
					$hiddenField.val(serializedList());
				});
				
				$root.find(".dd3-content > select").live("change", function ()
				{
					var $this = $(this);
					var value = $this.val();
					$this.parents(".HTML_LIST_ITEM").first().attr("data-id", value).data("id", value);
					$hiddenField.val(serializedList());
				});				
				
				$(document).on("click", ".root.<?php _e(PostMetaManagerHelper::sanitize_css_selector($identifier)) ?> .DD_REMOVE", function (e)
				{
					e.preventDefault();
					$(this).parents(".HTML_LIST_ITEM").first().remove();
					$hiddenField.val(serializedList());
				});
				
				<?php if ($sortable) { ?>
					$(".nestable.<?php _e(PostMetaManagerHelper::sanitize_css_selector($identifier)) ?>").nestable({
						maxDepth: <?php _e($depth) ?>
					}).on("change", function ()
					{
						$hiddenField.val(serializedList());
					});
				<?php } ?>	

				$hiddenField.val(serializedList());				
			});
		</script>
		<?php
	}
?>