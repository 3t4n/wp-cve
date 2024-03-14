<?php
	$sortable  = isset($sortable)  && is_bool($sortable) ? $sortable  : true;
	$max_items = isset($max_items) && $max_items > 1     ? $max_items : 9999;
	$depth     = isset($depth)     && $depth > 1         ? $depth     : 1;
	
	$saved_value = str_replace('&quot;', '"', $saved_value);
?>
<div class="htmlListWrp HTML_LIST_WRP <?php _e($field_data['key']) ?>">
	<div class="dd" style="display: none;">
		<ol class="dd-list">
			<li class="dd-item dd3-item htmlListWrp HTML_LIST_ITEM <?php _e($field_data['key']) ?>">
				<div class="dd-handle dd3-handle">Drag</div>
				<div class="dd3-content <?php _e($field_data['key']) ?> <?php if ($sortable) { ?>ui-state-default<?php } ?> <?php _e($field_data['additional_class']) ?>">
					<input type="text" name="<?php _e($identifier) ?>" class="contentField" />
					<a href="#" class="button HTML_LIST_REMOVE <?php _e($field_data['key']) ?>">Remove</a>
				</div>	
			</li>
		</ol>
	</div>
	<a href="#" class="HTML_LIST_ADD <?php _e($field_data['key']) ?>">+ Add</a>
	<div class="dd nestable_<?php _e($field_data['key']) ?>">
		<ol class="dd-list HTML_LIST_CANVAS <?php _e($field_data['key']) ?>">
			<?php 
				$i = 0;
				if (!is_array($saved_value)) {
					if (strlen($saved_value) > 0) {
						$saved_value = json_decode($saved_value);
					} else {
						$saved_value = array();
					}
				}
				if (is_array($saved_value)) {
					foreach ($saved_value as $value) {
						$saved_value = htmlspecialchars($value->id);
						$children = $value->children;
						$saved_value = html_entity_decode($saved_value);
						$saved_value = html_entity_decode($saved_value);
						$saved_value = str_replace('"', '&quot;', $saved_value);
						?>
						<li class="dd-item dd3-item HTML_LIST_ITEM <?php _e($field_data['key']) ?>" data-id="<?php _e($saved_value) ?>">
							<div class="dd-handle dd3-handle">Drag</div>
							<div class="dd3-content <?php _e($field_data['key']) ?> <?php if ($sortable) { ?>ui-state-default<?php } ?> <?php _e($field_data['additional_class']) ?>">
								<input type="text" value="<?php _e($saved_value) ?>" class="contentField" />
								<a href="#" class="button HTML_LIST_REMOVE <?php _e($field_data['key']) ?>">Remove</a>
							</div>	
							<?php
								if (!empty($children)) { 
									foreach ($children as $child) {
										$saved_value = htmlspecialchars($child->id);
										$saved_value = html_entity_decode($saved_value);
										$saved_value = html_entity_decode($saved_value);
										$saved_value = str_replace('"', '&quot;', $saved_value);										
										?>
										<ol class="dd-list">
											<li class="dd-item dd3-item HTML_LIST_ITEM <?php _e($field_data['key']) ?>" data-id="<?php _e($saved_value) ?>">
												<div class="dd-handle dd3-handle">Drag</div>
												<div class="dd3-content <?php _e($field_data['key']) ?> <?php if ($sortable) { ?>ui-state-default<?php } ?> <?php _e($field_data['additional_class']) ?>">
													<input type="text" value="<?php _e($saved_value) ?>" class="contentField" />
													<a href="#" class="button HTML_LIST_REMOVE <?php _e($field_data['key']) ?>">Remove</a>
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
	<input type="hidden" value="" name="<?php _e($identifier) ?>" class="HIDDEN_NESTED_FIELD_<?php _e($field_data['key']) ?>">									
	<script type="text/javascript">
		jQuery(function ($)
		{
			var $htmlListRoot   = $(".HTML_LIST_WRP.<?php _e($field_data['key']) ?>");												
			var $htmlListItem   = $htmlListRoot.find(".HTML_LIST_ITEM.<?php _e($field_data['key']) ?>").first();
			var $htmlListAdd    = $htmlListRoot.find(".HTML_LIST_ADD.<?php _e($field_data['key']) ?>");
			var $htmlListRemove = $htmlListRoot.find(".HTML_LIST_REMOVE.<?php _e($field_data['key']) ?>");
			var $hiddenField    = $(".HIDDEN_NESTED_FIELD_<?php _e($field_data['key']) ?>");
			var maxItems        = <?php _e($max_items) ?>;
			
			var serializedList = function ()
			{
				var ser = $(".nestable_<?php _e($field_data['key']) ?>").nestable("serialize");
				// if (ser.length <= 0) return '';
				// console.log(ser);
				// console.log(typeof ser);
				// console.log(ser.length);
				return JSON.stringify(ser);
			}
			
			$htmlListRoot.find(".HTML_LIST_ITEM.<?php _e($field_data['key']) ?> input[type=text]").live("change", function ()
			{
				var $this = $(this);
				var value = $this.val();
				$this.parents(".HTML_LIST_ITEM").first().attr("data-id", value).data("id", value);
				$hiddenField.val(serializedList());
			});
			
			$htmlListAdd.click(function (e)
			{
				e.preventDefault();
				var idx = $htmlListRoot.find(".HTML_LIST_CANVAS input[type=text]").length;
				if (idx >= maxItems) return;
				var $listItem = $htmlListItem.clone();
				var $text     = $listItem.find("input[type=text]");
				$htmlListRoot.find(".HTML_LIST_CANVAS.<?php _e($field_data['key']) ?>").append($listItem.removeClass("htmlListWrp"));
				$hiddenField.val(serializedList());
			});
			
			$htmlListRemove.live("click", function (e)
			{
				e.preventDefault();
				$(this).parents(".HTML_LIST_ITEM").first().remove();
				$hiddenField.val(serializedList());
			});	
				
			<?php if ($sortable) { ?>
				$(".nestable_<?php _e($field_data['key']) ?>").nestable({
					maxDepth: <?php _e($depth) ?>
				}).on("change", function ()
				{
					$hiddenField.val(serializedList());
				});
			<?php } ?>
			
			$htmlListRoot.find(".EDIT_HTML").live("click", function (e)
			{
				e.preventDefault();
				if (typeof $loadingScreen === "object") $loadingScreen.fadeIn("fast");
				$clickedContainerRoot = $(this).parent();
				tinyMCE.get("DUMMY_EDITOR").setContent($clickedContainerRoot.find(".contentField").val());
			});			
			
			$hiddenField.val(serializedList());
			if (typeof REFRESH_BEFORE_EDITOR_CHANGE !== "undefined") REFRESH_BEFORE_EDITOR_CHANGE.push(serializedList);
		});
	</script>
</div>