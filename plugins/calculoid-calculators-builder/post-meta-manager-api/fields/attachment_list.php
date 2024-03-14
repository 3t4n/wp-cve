<?php
	$force_extension = isset($force_extension) && !empty($force_extension)  ? $force_extension : '';
	
	if (!empty($force_extension)) {
		$force_extension = explode(',', $force_extension);
	} else {
		$force_extension = array();
	}
	
	/* ***************************************************** */
	// FUNCTIONS
	/* ***************************************************** */	
	function print_attachment_list_content_boxes($overview_items, $type) {
		if ($overview_items && isset($overview_items)) {
			$box_content_all = html_entity_decode($overview_items);
			$box_content_all = stripslashes($box_content_all);
			$box_content_all = json_decode($box_content_all);
			foreach ($box_content_all as $box_content) {
				$att_id = $box_content->att;
				$attachment = null;
				if (is_numeric($att_id) && $att_id > 0) {
					$attachment = get_post($att_id);
				}
				?>
					<li class='sortableItem ui-state-default SORTABLE_ITEM <?php _e($type) ?>' data-type="<?php _e($type) ?>">
						<a href='#' class='remove REMOVE_ITEM' title='Remove Item'></a>
						<div class='imageFieldWrp'><a href='#' class='button ADD_IMAGE'>Pick/Upload Attachment</a><a href='#' class='button REMOVE_IMAGE'>Remove Attachment</a><br/><span <?php _e($attachment ? '' : 'style="display: none;"') ?>><?php _e($attachment ? $attachment->guid : '') ?></span><input type='hidden' class='imageField' value='<?php _e($attachment ? $attachment->ID : '') ?>' /></div>
						<div class='titleFieldWrp'>Title:<input type='text' class='titleField' value='<?php _e($box_content->title) ?>' /></div>
					</li>																
				<?php
			}
		}	
	}

	function print_attachment_list_box_wrp($overview_items, $identifier, $title) {
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
									<?php print_attachment_list_content_boxes($overview_items, $identifier); ?>
								</ul>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		<?php
	}	
	
	function render_attachment_list_metabox_overview_fields($overview_items, $identifier, $wrap_id, $force_extension = array()) {
		global $post;
		// var_dump($overview_items);die();
		if (is_array($overview_items) && count($overview_items) > 0) $overview_items = $overview_items[0];
		?>
			<div id='loadingOverlay'>
				<?php
					$args = array(
						'raw'           => true,
						'media_buttons' => false,
						'teeny'         => true,
						'tinymce'       => true
					);
					
					wp_editor(
						'',
						'DUMMY_EDITOR',
						$args
					);					
				?>
				<p class="submit">
					<input type="button" class="button button-primary INSERT_HTML" value="Save Changes">
					&nbsp;|&nbsp;
					<input type="button" class="button button-primary CANCEL_HTML" value="Cancel">
				</p>
			</div>
			<div class="wrap-<?php _e($wrap_id) ?>">
				<?php print_attachment_list_box_wrp($overview_items, $identifier, '') ?>
				<style type="text/css">
					.wrap-<?php _e($wrap_id) ?> .sortableItem { cursor: move; }
					.wrap-<?php _e($wrap_id) ?> .sortableItem, .wrap-<?php _e($wrap_id) ?> .ui-state-highlight { width: 300px; height: 180px; margin: 20px 5px; position: relative; float: left; }
					.wrap-<?php _e($wrap_id) ?> .sortableItem > div { min-height: 50px; width: 96%; margin-left: 2%; }
					.wrap-<?php _e($wrap_id) ?> .sortableItem input[type=text], .wrap-<?php _e($wrap_id) ?> .sortableItem textarea { width: 100%; margin: 0 auto; resize: none; }
					.wrap-<?php _e($wrap_id) ?> .titleFieldWrp { height: 50px !important; }
					.wrap-<?php _e($wrap_id) ?> .imageFieldWrp img { max-width: 150px; max-height: 150px; margin-top: 15px; }
					.wrap-<?php _e($wrap_id) ?> .sortableItem > .remove { width: 24px; height: 24px; background: url('<?php _e(PMM_IMAGES_URL) ?>icons.png') no-repeat; background-position: -24px 0; position: absolute; top: -12px; right: -12px; display: none; }
					.wrap-<?php _e($wrap_id) ?> .sortableItem.HOVER > .remove { display: block; }
					.wrap-<?php _e($wrap_id) ?> .sortableItem > .editHTML { width: 24px; height: 24px; background: url('<?php _e(PMM_IMAGES_URL) ?>icons.png') no-repeat; background-position: 0 0; position: absolute; top: 77px; right: -3px; display: none; }
					.wrap-<?php _e($wrap_id) ?> .sortableItem.HOVER > .editHTML { display: block; }
					.wrap-<?php _e($wrap_id) ?> .sortableItem > .imageFieldWrp { text-align: center; padding-top: 10px; }
					.wrap-<?php _e($wrap_id) ?> .sortableItem > .imageFieldWrp .REMOVE_IMAGE { display: none; }
					.wrap-<?php _e($wrap_id) ?> .imageFieldWrp span { display: block; margin: 10px 0; }
					
					.wrap-<?php _e($wrap_id) ?> .slider { height: 140px; }
					.wrap-<?php _e($wrap_id) ?> .slider .titleFieldWrp { display: none; }
					
					.wrap-<?php _e($wrap_id) ?> .most_popular { height: 140px; }
					.wrap-<?php _e($wrap_id) ?> .most_popular .imageFieldWrp { display: none; }

					.wrap-<?php _e($wrap_id) ?> .business_directory { height: 140px; }
					.wrap-<?php _e($wrap_id) ?> .business_directory .imageFieldWrp { display: none; }	

					.dummyImageContainer-<?php _e($wrap_id) ?> { position: absolute; max-width: 300px; max-height: 300px; padding: 5px; background-color: #ffffff; border: 1px solid black; display: none; }
					
					.wrap-<?php _e($wrap_id) ?> .typeFieldWrp input[type=checkbox] { margin-right: 5px; }
					.wrap-<?php _e($wrap_id) ?> .typeFieldWrp { display: none; }
					
					#loadingOverlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: url(<?php _e(PMM_IMAGES_URL) ?>bgOpacityBlack25.png) repeat; z-index: 9999; display: none; }					
					#loadingOverlay .submit { position: relative; text-align: right; margin-right: 10% !important; top: 120px; }
					#wp-DUMMY_EDITOR-wrap { margin: 0 auto; width: 80% !important; position: relative !important; top: 100px !important; }
				</style>
				<script type="text/javascript">
					WAIT_FOR_ATTACHMENT_INSERT = false;
					$loadingScreen = null;
					$clickedContainerRoot = null;
					jQuery(function ($)
					{
						$loadingScreen = $("#loadingOverlay");
						$("body").append($loadingScreen);
				
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
										title:          $this.find(".titleField").val().replace(/'/g, "&#39;").replace(/"/g, '&#34;'),
										att:            $this.find(".imageField").val()
									});
								});
								data = JSON.stringify(data);
								$this.find(".HIDDEN_FIELD_ROOT").val(data);
							});
						}
						if (typeof REFRESH_BEFORE_POST === "function") REFRESH_BEFORE_POST.push(refreshItems<?php _e($wrap_id) ?>);
						refreshItems<?php _e($wrap_id) ?>();
						
						$(".wrap-<?php _e($wrap_id) ?> .titleField, " +
							".wrap-<?php _e($wrap_id) ?> .imageField, " +
							".wrap-<?php _e($wrap_id) ?> .linkField").live("change", refreshItems<?php _e($wrap_id) ?>);
						
						var imageMediaPopup = wp.media({
							title : 'Pick an attachment',
							multiple : false,
							library : { type : '*'},
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
							html += "<div class='imageFieldWrp'><a href='#' class='button ADD_IMAGE'>Pick/Upload Attachment</a><a href='#' class='button REMOVE_IMAGE'>Remove Attachment</a><br/><span style='display: none;'></span><input type='hidden' class='imageField' value='' /></div>";
							html += "<div class='titleFieldWrp'>Title:<input type='text' class='titleField' value='' /></div>";
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

						$(".wrap-<?php _e($wrap_id) ?> .ADD_IMAGE").live("click", function (e)
						{
							e.preventDefault();
							$(this).addClass("ATT_SELECTED");
							imageMediaPopup.open();
							// if library contains "*"
							imageMediaPopup.uploader.uploader.uploader.bind("FileUploaded", function (up, file, response)
							{
								if (response != null && response.response != undefined && response.response != null && response.response.length > 0)
								{
									try
									{
										// var responseObj = JSON.parse(response.response);
										// if (responseObj.mime.indexOf("image") != -1) imageMediaPopup.views._views[".media-frame-content"][0].views._views[""][1].collection.props.set({ignore:(+(new Date()))})
										imageMediaPopup.views._views[".media-frame-content"][0].views._views[""][1].collection.props.set({ignore:(+(new Date()))})
									}
									catch (err) {}
								}
							});
						});
						
						$(".wrap-<?php _e($wrap_id) ?> .REMOVE_IMAGE").live("click", function (e)
						{
							e.preventDefault();
							var $this = $(this);
							$this.parent().find(".imageField").val("");
							$this.parent().find("img").hide(0);
							refreshItems<?php _e($wrap_id) ?>();
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

						function endsWith (str, suffix)
						{
							return str.indexOf(suffix, str.length - suffix.length) !== -1;
						}
						
						function mediaUploaderSelect (frame)
						{
							WAIT_FOR_ATTACHMENT_INSERT = false;
							var $selected = $(".ATT_SELECTED");
							var forceExtensions = <?php _e(json_encode($force_extension)); ?>;
							if ($selected.length > 0)
							{
								var selection = frame.state().get('selection');
								if (selection.length > 0)
								{
									selection.each(function (attachment)
									{
										if (forceExtensions.length > 0)
										{
											var matchFound = false;
											for (var i = 0; i < forceExtensions.length; i++)
											{
												var ext = $.trim(forceExtensions[i].toLowerCase());
												var url = $.trim(attachment.collection._byId[attachment.id].attributes.url.toLowerCase());
												if (endsWith(url, ext))
												{
													matchFound = true;
													break;
												}
											}
											if (!matchFound)
											{
												var err = "The chosen file extension isn't allowed for this field, please pick a file with one of the following extensions:\n\n";
												for (var i = 0; i < forceExtensions.length; i++)
												{
													err += String.fromCharCode(8226) + " " + forceExtensions[i] + "\n";
												}
												$selected.parent().find(".imageField").val("");
												$selected.parent().find("span").hide(0);	
												alert(err);
												return;
											}
										}									
									
										$selected.parent().find(".imageField").val(attachment.id);
										$selected.parent().find("span").text(attachment.collection._byId[attachment.id].attributes.url).show(0);
									});								
								}
								else
								{
									$selected.parent().find(".imageField").val("");
									$selected.parent().find("span").hide(0);
								}
							}
							$selected.removeClass("ATT_SELECTED");
							refreshItems<?php _e($wrap_id) ?>();
						}							
						
						/* BIND IMAGE MEDIA POPUP EVENTS */
						imageMediaPopup.on("open", function () { mediaUploaderOpen(imageMediaPopup); });
						imageMediaPopup.on("close", function () { mediaUploaderClose() });	
						imageMediaPopup.on("select", function () { mediaUploaderSelect(imageMediaPopup); });
						
						/* ADD IMAGE HOVER LOGIC */
						// var $dummyImageContainer = $("<img src='' alt='' class='dummyImageContainer-<?php _e($wrap_id) ?>' />");
						// $dummyImageContainer.fadeOut(0);
						// $("body").append($dummyImageContainer);
						// $(".wrap-<?php _e($wrap_id) ?> .imageFieldWrp img").live("mouseenter", function ()
						// {
							// $dummyImageContainer.attr("src", $(this).attr("src"));
							// $dummyImageContainer.fadeIn("fast");
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
						
						/* ITEM TYPE CHECKBOX CHANGE */
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
						
						/* EDIT HTML BUTTON LOGIC */
						$(".EDIT_HTML").live("click", function (e)
						{
							e.preventDefault();
							if (typeof $loadingScreen === "object") $loadingScreen.fadeIn("fast");
							$clickedContainerRoot = $(this).parent();
							tinyMCE.get("DUMMY_EDITOR").setContent($clickedContainerRoot.find(".contentField").val());
						});
						$(".INSERT_HTML").live("click", function (e)
						{
							e.preventDefault();
							var content = tinyMCE.get("DUMMY_EDITOR").getContent();
							var realContent = "";
							$("<div>" + content + "</div>").find("p").each(function ()
							{
								realContent += $(this).html();
							});
							$clickedContainerRoot.find(".contentField").val(realContent);
							if (typeof $loadingScreen === "object") $loadingScreen.fadeOut("fast");
							$clickedContainerRoot = null;
							$(".htmlListWrp.HTML_LIST_WRP.overview_features").find(".HTML_LIST_ITEM.overview_features input[type=text]").change();
						});
						$(".CANCEL_HTML").live("click", function (e)
						{
							e.preventDefault();
							if (typeof $loadingScreen === "object") $loadingScreen.fadeOut("fast");
							$clickedContainerRoot = null;
						});
					});
				</script>
			</div>
		<?php
	}
	
	global $wrap_id;
	if ($wrap_id == null) $wrap_id = 0;
	render_attachment_list_metabox_overview_fields($saved_value, $identifier, ++$wrap_id, $force_extension);
?>