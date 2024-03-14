<?php
	/* ***************************************************** */
	// FUNCTIONS
	/* ***************************************************** */	
	if (!function_exists('print_program_video_slideshow_content_boxes')) {
		function print_program_video_slideshow_content_boxes($video_slideshow_items, $type) {
			if ($video_slideshow_items && isset($video_slideshow_items)) {
				$box_content_all = html_entity_decode($video_slideshow_items);
				$box_content_all = stripslashes($box_content_all);
				$box_content_all = json_decode($box_content_all);
				$i = 1;
				foreach ($box_content_all as $box_content) {
					// print_r($box_content);die();
					?>
						<li class='sortableItem ui-state-default SORTABLE_ITEM <?php _e($type) ?>' data-type="<?php _e($type) ?>">
							<a href='#' class='remove REMOVE_ITEM' title='Remove Item'></a>
							<div class='videoIDFieldWrp'>Video ID:<input type='text' class='videoField' value='<?php _e($box_content->video_id) ?>' /></div>
							<div class='videoIDFieldWrp'>Video Type:<br /><label><input type='radio' class='videoType' name='videoType<?php _e($i) ?>' value='youtube' <?php _e($box_content->video_type == 'youtube' ? 'checked="checked"' : '') ?> />&nbsp;YouTube</label><br /><label><input type='radio' class='videoType' name='videoType<?php _e($i) ?>' value='vimeo' <?php _e($box_content->video_type == 'vimeo' ? 'checked="checked"' : '') ?> />&nbsp;Vimeo</label></div>
							<div class='videoIDFieldWrp'><a href='#' class='button VERIFY_VIDEO'>Verify video</a></div>
						</li>																
					<?php
					$i++;
				}
			}	
		}
	}
	
	if (!function_exists('print_program_video_slideshow_box_wrp')) {
		function print_program_video_slideshow_box_wrp($video_slideshow_items, $identifier, $title) {
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
										<?php print_program_video_slideshow_content_boxes($video_slideshow_items, $identifier); ?>
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
	function render_programs_metabox_video_slideshow_fields($video_slideshow_items, $identifier) {
		global $post;
		$wrap_id = 4;
		
		// var_dump($video_slideshow_items);die();
		if (is_array($video_slideshow_items) && count($video_slideshow_items) > 0) $video_slideshow_items = $video_slideshow_items[0];
		?>
			<div class="wrap-<?php _e($wrap_id) ?>">
				<?php print_program_video_slideshow_box_wrp($video_slideshow_items, $identifier, '') ?>
				<style type="text/css">
					.wrap-<?php _e($wrap_id) ?> .sortableItem { cursor: move; }
					.wrap-<?php _e($wrap_id) ?> .sortableItem, .wrap-<?php _e($wrap_id) ?> .ui-state-highlight { width: 200px; height: 150px; margin: 10px; float:left; position: relative; }
					.wrap-<?php _e($wrap_id) ?> .sortableItem > div { min-height: 50px; width: 96%; margin-left: 2%; }
					.wrap-<?php _e($wrap_id) ?> .sortableItem input[type=text], .wrap-<?php _e($wrap_id) ?> .sortableItem textarea { width: 100%; margin: 0 auto; resize: none; }
					.wrap-<?php _e($wrap_id) ?> .sortableItem textarea, .wrap-<?php _e($wrap_id) ?> .contentFieldWrp { height: 150px; }
					.wrap-<?php _e($wrap_id) ?> .contentFieldWrp { height: 230px !important; }
					.wrap-<?php _e($wrap_id) ?> .titleFieldWrp { height: 50px !important; }
					.wrap-<?php _e($wrap_id) ?> .imageFieldWrp img { max-width: 120px; max-height: 120px; margin-top: 5px; }
					.wrap-<?php _e($wrap_id) ?> .imageFieldWrp a, .wrap-<?php _e($wrap_id) ?> .videoIDFieldWrp a { margin: 5px auto; display: block; text-align: center; }
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
										video_id:   $this.find(".videoField").val(),
										video_type: $this.find(".videoType:checked").val()
									});
								});
								data = JSON.stringify(data);
								$this.find(".HIDDEN_FIELD_ROOT").val(data);
							});
						}
						if (typeof REFRESH_BEFORE_POST != "undefined") REFRESH_BEFORE_POST.push(refreshItems<?php _e($wrap_id) ?>);
						
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
							var found = false;
							var count = 0;
							var i = 0;
							while (!found)
							{
								count = $(".wrap-<?php _e($wrap_id) ?> .SORTABLE_ITEM").length + 1 + i;
								found = $("[name=videoType" + count + "]").length <= 0;
								i++;
							}
							var classname = $el.parents("tr").first().data("classname");
							var html = "<li class='sortableItem ui-state-default SORTABLE_ITEM " + classname + "' data-type='" + classname + "'>";
							html += "<a href='#' class='remove REMOVE_ITEM' title='Remove Item'></a>";
							html += "<div class='videoIDFieldWrp'>Video ID:<input type='text' class='videoField' value='' /></div>";
							html += "<div class='videoIDFieldWrp'>Video Type:<br /><label><input type='radio' class='videoType' name='videoType" + count + "' value='youtube' checked='checked' />&nbsp;YouTube</label><br /><label><input type='radio' class='videoType' name='videoType" + count + "' value='vimeo' />&nbsp;Vimeo</label></div>";
							html += "<div class='videoIDFieldWrp'><a href='#' class='button VERIFY_VIDEO'>Verify video</a></div>";
							html += "</li>";
							$el.parents("tr").first().find(".SORTABLE_ITEM_WRP").append(html);
							makeItemsSortable();
							refreshItems<?php _e($wrap_id) ?>();							
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
							refreshItems<?php _e($wrap_id) ?>();
						});
						
						$(".wrap-<?php _e($wrap_id) ?> .VERIFY_VIDEO").live("click", function (e)
						{
							e.preventDefault();
							var $this = $(this);
							var videoType = $this.parents(".SORTABLE_ITEM").first().find(".videoType:checked").val();
							var videoID = $this.parents(".SORTABLE_ITEM").first().find(".videoField").val();
							var checkURL = "";
							var videoTypeFound = false;
							
							var successMsg = 'SUCCESS: "' + videoID + '" is a valid ' + videoType + ' video ID.'
							var errorMsg = 'ERROR: "' + videoID + '" is NOT a valid ' + videoType + ' video ID.'
							
							if (videoType == "youtube")
							{
								videoTypeFound = true;
								checkURL = "http://gdata.youtube.com/feeds/api/videos/" + videoID;
								$.ajax({
									type: 'GET',
									url: checkURL,
									dataType: 'xml',
									crossDomain: true,
									success: function (resp)
									{
										resp = $(resp);
										if (resp.find("id").length > 0 && resp.find("id").text().length > 0)
										{
											alert(successMsg);
										}
										else
										{
											alert(errorMsg);
										}
									},
									error: function (resp)
									{
										alert(errorMsg);
									}
								});									
							}
							else if (videoType == "vimeo")
							{
								videoTypeFound = true;
								checkURL = 'http://vimeo.com/api/v2/video/' + videoID + '.json';
								$.ajax({
									type: 'GET',
									url: checkURL,
									data: { format: 'jsonp' },
									dataType: 'text',
									crossDomain: true,
									success: function (resp)
									{
										try
										{
											resp = $.parseJSON(resp);  
										}
										catch (e)
										{}
										if (typeof resp === "object" && resp.length > 0 && typeof resp[0].id != "undefined" && resp[0].id == videoID)
										{
											alert(successMsg);
										}
										else
										{
											alert(errorMsg);
										}
									},
									error: function (resp)
									{
										alert(errorMsg);
									}
								});									
							}
						});
						
						$(".wrap-<?php _e($wrap_id) ?> .SORTABLE_ITEM input").live("change keyup", refreshItems<?php _e($wrap_id) ?>);							
						
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
						
						refreshItems<?php _e($wrap_id) ?>();
					});
				</script>
			</div>
		<?php
	}
	
	render_programs_metabox_video_slideshow_fields($saved_value, $identifier);
?>