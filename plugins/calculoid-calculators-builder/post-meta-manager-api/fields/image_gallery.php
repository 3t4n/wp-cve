<?php
	/* ***************************************************** */
	// FUNCTIONS
	/* ***************************************************** */	
	if (!function_exists('print_program_slideshow_content_boxes')) {
		function print_program_slideshow_content_boxes($slideshow_items, $type) {
			if ($slideshow_items && isset($slideshow_items)) {
				$box_content_all = html_entity_decode($slideshow_items);
				$box_content_all = stripslashes($box_content_all);
				$box_content_all = json_decode($box_content_all);
				foreach ($box_content_all as $box_content) {
					$att_id = $box_content->image;
					$attachment = null;
					if (is_numeric($att_id) && $att_id > 0) {
						$attachment = get_post($att_id);
					}
					?>
						<li class='sortableItem ui-state-default SORTABLE_ITEM <?php _e($type) ?>' data-type="<?php _e($type) ?>">
							<a href='#' class='remove REMOVE_ITEM' title='Remove Item'></a>
							<div class='imageFieldWrp'><a href='#' class='button ADD_IMAGE'>Add Image</a><a href='#' class='button REMOVE_IMAGE'>Remove Image</a><img src='<?php _e($attachment ? $attachment->guid : '') ?>' alt='' <?php _e($attachment ? '' : 'style="display: none;"') ?>' /><input type='hidden' class='imageField' value='<?php _e($attachment ? $attachment->ID : '') ?>' /></div>
						</li>																
					<?php
				}
			}	
		}
	}
	
	if (!function_exists('print_program_slideshow_box_wrp')) {
		function print_program_slideshow_box_wrp($slideshow_items, $identifier, $title) {
			?>
				<table class="form-table">
					<tbody>
						<!--
						<tr class="topFeaturedBoxes" data-classname="<?php _e($identifier) ?>">
							<th scope="row"><label for="<?php _e($identifier) ?>"><?php _e($title) ?></label></th>
						</tr>
						-->
						<tr>
							<td>
								<div class="featuredBoxWrp">
									<input type="hidden" name="<?php _e($identifier) ?>" class="HIDDEN_FIELD_ROOT" value='' />
									<input type="button" value="Add new item" class="button ADD_NEW_ITEM" />
									<ul class="sortableItemWrp SORTABLE_ITEM_WRP">
										<?php print_program_slideshow_content_boxes($slideshow_items, $identifier); ?>
									</ul>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			<?php
		}
	}
	
	/* ***************************************************** */
	// RENDER
	/* ***************************************************** */		
	function render_programs_metabox_slideshow_fields($slideshow_items, $identifier) {
		global $post;
		$wrap_id = 2;
		
		// var_dump($slideshow_items);
		if (is_array($slideshow_items) && count($slideshow_items) > 0) $slideshow_items = $slideshow_items[0];
		?>
			<div class="wrap-<?php _e($wrap_id) ?>">
				<?php print_program_slideshow_box_wrp($slideshow_items, $identifier, '') ?>
				<style type="text/css">
					.wrap-<?php _e($wrap_id) ?> .sortableItem { cursor: move; }
					.wrap-<?php _e($wrap_id) ?> .sortableItem, .wrap-<?php _e($wrap_id) ?> .ui-state-highlight { width: 200px; height: 180px; margin: 10px; float:left; position: relative; }
					.wrap-<?php _e($wrap_id) ?> .sortableItem > div { min-height: 70px; width: 96%; margin-left: 2%; }
					.wrap-<?php _e($wrap_id) ?> .sortableItem input[type=text], .wrap-<?php _e($wrap_id) ?> .sortableItem textarea { width: 100%; margin: 0 auto; resize: none; }
					.wrap-<?php _e($wrap_id) ?> .sortableItem textarea, .wrap-<?php _e($wrap_id) ?> .contentFieldWrp { height: 180px; }
					.wrap-<?php _e($wrap_id) ?> .contentFieldWrp { height: 230px !important; }
					.wrap-<?php _e($wrap_id) ?> .titleFieldWrp { height: 50px !important; }
					.wrap-<?php _e($wrap_id) ?> .imageFieldWrp img { max-width: 120px; max-height: 120px; margin-top: 5px; }
					.wrap-<?php _e($wrap_id) ?> .imageFieldWrp a { margin: 5px auto; display: block; }
					.wrap-<?php _e($wrap_id) ?> .imageFieldWrp a.REMOVE_IMAGE { display: none; }
					.wrap-<?php _e($wrap_id) ?> .sortableItem > .remove { width: 24px; height: 24px; background: url('<?php _e(TEMPLATE_URL) ?>admin_mods/images/icons.png') no-repeat; background-position: -24px 0; position: absolute; top: -13px; right: -13px; display: none; }
					.wrap-<?php _e($wrap_id) ?> .sortableItem.HOVER > .remove { display: block; }
					.wrap-<?php _e($wrap_id) ?> .sortableItem > .editHTML { width: 24px; height: 24px; background: url('<?php _e(TEMPLATE_URL) ?>admin_mods/images/icons.png') no-repeat; background-position: 0 0; position: absolute; top: 77px; right: -3px; display: none; }
					.wrap-<?php _e($wrap_id) ?> .sortableItem.HOVER > .editHTML { display: block; }
					.wrap-<?php _e($wrap_id) ?> .sortableItem > .imageFieldWrp { text-align: center; }
					
					.wrap-<?php _e($wrap_id) ?> .slider { height: 140px; }
					.wrap-<?php _e($wrap_id) ?> .slider .titleFieldWrp { display: none; }
					
					.wrap-<?php _e($wrap_id) ?> .most_popular { height: 140px; }
					.wrap-<?php _e($wrap_id) ?> .most_popular .imageFieldWrp { display: none; }

					.wrap-<?php _e($wrap_id) ?> .business_directory { height: 140px; }
					.wrap-<?php _e($wrap_id) ?> .business_directory .imageFieldWrp { display: none; }	

					.dummyImageContainer-<?php _e($wrap_id) ?> { position: absolute; max-width: 300px; max-height: 300px; padding: 5px; background-color: #ffffff; border: 1px solid black; }
					
					.wrap-<?php _e($wrap_id) ?> .typeFieldWrp input[type=checkbox] { margin-right: 5px; }
				</style>
				<script type="text/javascript">
					WAIT_FOR_ATTACHMENT_INSERT = false;
					$clickedContainerRoot = null;
					jQuery(function ($)
					{
						/* ***************************************************** */
						// FEATURED BOX MANAGEMENT LOGIC
						/* ***************************************************** */						
						var refreshItems<?php _e($wrap_id) ?> = function ()
						{
							$(".wrap-<?php _e($wrap_id) ?> .featuredBoxWrp").each(function ()
							{
								var $this = $(this);
								var data  = [];
								$this.find(".SORTABLE_ITEM_WRP > .SORTABLE_ITEM").each(function ()
								{
									var $that = $this;
									var $this = $(this);
									data.push({
										image:   $this.find(".imageField").val()
									});
								});
								data = JSON.stringify(data);
								$this.find(".HIDDEN_FIELD_ROOT").val(data);
							});
						}
						if (typeof REFRESH_BEFORE_POST === "function") REFRESH_BEFORE_POST.push(refreshItems<?php _e($wrap_id) ?>);
						
						var imageMediaPopup = wp.media({
							title : 'Pick an image to attach to this item',
							multiple : false,
							library : { type : 'image'},
							button : { text : 'Insert' }
						});
					
						function makeItemsSortable ()
						{
							$(".wrap-<?php _e($wrap_id) ?> .SORTABLE_ITEM_WRP").sortable({
								placeholder: "ui-state-highlight",
								containment: "body",
								start: function (e, ui)
								{
									$(".wrap-<?php _e($wrap_id) ?> .ui-state-highlight").attr("class", "ui-state-highlight " + ui.item.data("type"));
								},
								stop: refreshItems<?php _e($wrap_id) ?>
							})
							
							$(".wrap-<?php _e($wrap_id) ?> .SORTABLE_ITEM").unbind('mouseenter mouseleave').hover(
								function () { $(this).addClass("HOVER"); },
								function () { $(this).removeClass("HOVER"); }
							);
						}
						makeItemsSortable();
						
						function addNewItem ($el)
						{
							var classname = $el.parents("tr").first().data("classname");
							var html = "<li class='sortableItem ui-state-default SORTABLE_ITEM " + classname + "' data-type='" + classname + "'>";
							html += "<a href='#' class='remove REMOVE_ITEM' title='Remove Item'></a>";
							html += "<div class='imageFieldWrp'><a href='#' class='button ADD_IMAGE'>Add Image</a><a href='#' class='button REMOVE_IMAGE'>Remove Image</a><img src='' alt='' style='display: none;' /><input type='hidden' class='imageField' value='' /></div>";
							html += "</li>";
							$el.parents("tr").first().find(".SORTABLE_ITEM_WRP").append(html);
							makeItemsSortable();							
						}
						
						$(".wrap-<?php _e($wrap_id) ?> .ADD_NEW_ITEM").click(function (e)
						{
							e.preventDefault();
							var $this = $(this);
							var classname = $this.parents("tr").first().data("classname");
							if (classname == "top_featured_boxes")
							{
								if ($this.parents("tr").find(".SORTABLE_ITEM").length < 4) addNewItem($this); 
							}
							else
							{
								addNewItem($this); 
							}
						});
						
						$(".wrap-<?php _e($wrap_id) ?> .REMOVE_ITEM").live("click", function (e)
						{
							e.preventDefault();
							$(this).parent().remove();
						});

						$(".wrap-<?php _e($wrap_id) ?> .ADD_IMAGE").live("click", function (e)
						{
							e.preventDefault();
							$(this).addClass("ATT_SELECTED");
							imageMediaPopup.open();
						});
						
						$(".wrap-<?php _e($wrap_id) ?> .REMOVE_IMAGE").live("click", function (e)
						{
							e.preventDefault();
							var $this = $(this);
							$this.parent().find(".imageField").val("");
							$this.parent().find("img").hide(0);
						});							
						
						/* ***************************************************** */
						// MEDIA POPUP EVENTS
						/* ***************************************************** */	
						function mediaUploaderOpen (frame) 
						{
							var selection    = frame.state().get('selection');
							var $selected    = $(".ATT_SELECTED");
							var attachmentID = Number($selected.parent().find(".imageField").val());
							if (typeof attachmentID === "number" && attachmentID > 0)
							{
								var attachment = wp.media.attachment(attachmentID);
								attachment.fetch();
								selection.add(attachment ? [attachment] : []);
							}
							else
							{
								selection.add([]);
							}
						}
						
						function mediaUploaderClose ()
						{
							WAIT_FOR_ATTACHMENT_INSERT = true;
							setTimeout(function ()
							{
								if (WAIT_FOR_ATTACHMENT_INSERT)
								{
									$(".ATT_SELECTED").removeClass("ATT_SELECTED");
									WAIT_FOR_ATTACHMENT_INSERT = false;
								}
							}, 200);
						}

						function mediaUploaderSelect (frame)
						{
							WAIT_FOR_ATTACHMENT_INSERT = false;
							var $selected = $(".ATT_SELECTED");
							if ($selected.length > 0)
							{
								var selection = frame.state().get('selection');
								if (selection.length > 0)
								{
									selection.each(function (attachment)
									{
										$selected.parent().find(".imageField").val(attachment.id);
										$selected.parent().find("img").attr("src", attachment.collection._byId[attachment.id].attributes.url).show(0);
									});								
								}
								else
								{
									$selected.parent().find(".imageField").val("");
									$selected.parent().find("img").hide(0);
								}
							}
							$selected.removeClass("ATT_SELECTED");
						}							
						
						// BIND IMAGE MEDIA POPUP EVENTS						
						imageMediaPopup.on("open", function () { mediaUploaderOpen(imageMediaPopup); });
						imageMediaPopup.on("close", function () { mediaUploaderClose() });	
						imageMediaPopup.on("select", function () { mediaUploaderSelect(imageMediaPopup); });
						
						// ADD IMAGE HOVER LOGIC
						// var $dummyImageContainer = $("<img src='' alt='' class='dummyImageContainer-<?php _e($wrap_id) ?>' />");
						// $dummyImageContainer.fadeOut(0);
						// $("body").append($dummyImageContainer);
						// $(".wrap-<?php _e($wrap_id) ?> .imageFieldWrp img").live("mouseenter", function ()
						// {
							// $dummyImageContainer.attr("src", $(this).attr("src"));
							// $dummyImageContainer.fadeIn("fast");
							// console.log($dummyImageContainer);
						// }).live("mouseleave", function ()
						// {
							// $dummyImageContainer.fadeOut("fast");
						// }).live("mousemove", function (e)
						// {
							// $dummyImageContainer.css({
								// left: e.pageX + 5,
								// top:  e.pageY + 5
							// });
						// });
						
						// ITEM TYPE CHECKBOX CHANGE
						var checkboxChange = function ()
						{
							var $that   = $(this);
							var $parent = $that.parent();
							$(".wrap-<?php _e($wrap_id) ?> .typeFieldWrp input[type=checkbox]").die("change");
							$parent.find("input[type=checkbox]").each(function ()
							{
								var $this = $(this);
								if ($this.get(0) != $that.get(0))
								{
									$this.removeAttr("checked");
								}
							});							
							$(".wrap-<?php _e($wrap_id) ?> .typeFieldWrp input[type=checkbox]").live("change", checkboxChange);
						};
						$(".wrap-<?php _e($wrap_id) ?> .typeFieldWrp input[type=checkbox]").live("change", checkboxChange);
					});
				</script>
			</div>
		<?php
	}
	
	render_programs_metabox_slideshow_fields($saved_value, $identifier);
?>