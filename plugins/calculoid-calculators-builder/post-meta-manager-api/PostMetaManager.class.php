<?php
	/* ***************************************************** */
	// POST META MANAGER FIELD TYPES
	/* ***************************************************** */	
	class PostMetaManagerFieldType
	{
		const Textbox             = 'PMMF_Textbox';
		const Textarea            = 'PMMF_Textarea';
		const WPEditor            = 'PMMF_WPEditor';
		const Checkbox            = 'PMMF_Checkbox';
		const RadioButtons        = 'PMMF_RadioButtons';
		const DropDown            = 'PMMF_DropDown';
		const MediaFileUpload     = 'PMMF_MediaFileUpload';
		const NestablePicker      = 'PMMF_NestablePicker';
		const HTMLList            = 'PMMF_HTMLList';
		const ImageGallery        = 'PMMF_ImageGallery';
		const VideoGallery        = 'PMMF_VideoGallery';
		const CustomFeaturedStack = 'PMMF_CustomFeaturedStack';
		const Reviews             = 'PMMF_Reviews';
		const SectionHeader       = 'PMMF_SectionHeader';
		const MultiDropDown       = 'PMMF_MultiDropDown';
		const ColorPicker         = 'PMMF_ColorPicker';
		const DatePicker          = 'PMMF_DatePicker';
		const AttachmentList      = 'PMMF_AttachmentList';
		const Label               = 'PMMF_Label';
		const ItemPickerSorter    = 'PMMF_ItemPickerSorter';
		
		// CUSTOM
		const IWasWearing     = 'PMMF_IWasWearing';
		const SimilarItems    = 'PMMF_SimilarItems';		
		
		const Delimiter = 'PMMF_Delimiter';
	}
	
	/* ***************************************************** */
	// POST META MANAGER HELPER VARS & FUNCTIONS
	/* ***************************************************** */			
	class PostMetaManagerHelper
	{
		const CheckboxChecked   = 'pmmf_checked';
		const CheckboxUnchecked = 'pmmf_unchecked';
		
		public function sanitize_wp_editor_html($str) {
			$str = str_replace('\"', '"', $str);
			$str = htmlspecialchars_decode($str);
			$str = str_replace('\&', '&', $str);
			return $str;
		}
		
		public static function esc_attr($str) {
			$str = str_replace('"', '&quot;', $str);
			$str = str_replace('\&', '&', $str);
			$str = str_replace("\'", "'", $str);
			return $str;
		}		
		
		public function sanitize_css_selector($selector) {
			$selector = str_replace('[', '\\\\[', $selector);
			$selector = str_replace(']', '\\\\]', $selector);
			return $selector;
		}		
		
		public function sanitize_meta_for_db_input($meta) {
			if (is_array($meta)) return $meta;
			return htmlspecialchars($meta);		
		}
		
		public function strip_content($content, $limit) {
			if (strlen($content) > $limit) {
				$content = strip_tags($content);
				$content = substr($content, 0, strpos($content, ' ', $limit)) . ' ...';
			}
			return $content;
		}
		
		public function current_page_url() {
			$pageURL = 'http';
			if( isset($_SERVER["HTTPS"]) ) {
				if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
			}
			$pageURL .= "://";
			if ($_SERVER["SERVER_PORT"] != "80") {
				$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
			} else {
				$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
			}
			return $pageURL;
		}
		
		public function is_selected($current, $selected, $echo = true) {
			$out = '';
			foreach ($current as $c) {
				if ($c == $selected) {
					$out = 'selected="selected"';
					break;
				}
			}
			if ($echo) 
				_e($out);
			else
				return $out;
		}
		
		public function generate_dropdown($item_collection, $selected_value = '', $has_groups = false, $identifier = '', $multiple = false) {
			$selected_value_tmp = maybe_unserialize($selected_value);
			if (is_string($selected_value_tmp)) {
				$selected_value = array($selected_value);
			} else {
				$selected_value = $selected_value_tmp;
			}
			unset($selected_value_tmp);
			if (is_array($item_collection) && count($item_collection) > 0) {	
				?><select name="<?php _e(!empty($identifier) ? $identifier : '') ?><?php if ($multiple) { ?>[]<?php }?>" id="<?php _e(!empty($identifier) ? $identifier : '') ?>" <?php if ($multiple) { ?>multiple="multiple" style="width: 100%;"<?php } ?>><?php
				if (!$has_groups) {
					foreach ($item_collection as $key => $value) {
						// $selected = $selected_value == $value ? 'selected="selected"' : '';
						?><option value="<?php _e($value) ?>" <?php  _e(PostMetaManagerHelper::is_selected($selected_value, $value)) ?>><?php _e($key) ?></option><?php
					}
				} else {
					foreach ($item_collection as $group_name => $items) {
					?><optgroup label="<?php _e($group_name) ?>"><?php
						foreach ($items as $key => $value) {
							// $selected = $selected_value == $value ? 'selected="selected"' : '';
							?><option value="<?php _e($value) ?>" <?php _e(PostMetaManagerHelper::is_selected($selected_value, $value)) ?>><?php _e($key) ?></option><?php						
						}
						
					?></optgroup><?php
					}
				}
				?></select><?php				
			}		
		}
	}
	
	/* ***************************************************** */
	// POST META MANAGER FIELDS
	/* ***************************************************** */		
	class PostMetaManagerField
	{
		public function __construct() { }
		
		// WRAP FIELD AS TABLE ROW (<tr>)	
		public function wrap($field_content, $identifier, $field_data, $for = null, $is_section_header = false, $single_column = false) {
			ob_start();
			$for = !empty($for) ? $for : $identifier;
			if (!$is_section_header) {
				?>
					<?php if (!$single_column) { ?>
						<tr>
							<td style="width: 25%; text-align: right; padding-right: 10px" class="firstChild">
								<?php if (isset($field_data['name']) && !empty($field_data['name'])) { ?>
									<label for="<?php _e($for) ?>"><code><?php _e($field_data['name']) ?></code></label>
								<?php } ?>
							</td>
							<td class="secondChild">
								<?php 
									_e($field_content);
									if (isset($field_data['desc']) && !empty($field_data['desc'])) {
										?><br /><i style="font-size: 12px; color: #555;"><?php _e($field_data['desc']) ?></i><?php
									}
								?>	
							</td>
						</tr>
					<?php } else { ?>
						<tr>
							<td style="width: 100%; text-align: left; padding-right: 10px" class="firstChild">
								<?php 
									_e($field_content);
									if (isset($field_data['desc']) && !empty($field_data['desc'])) {
										?><br /><i style="font-size: 12px; color: #555;"><?php _e($field_data['desc']) ?></i><?php
									}
								?>	
							</td>
						</tr>
					<?php } ?>
				<?php
			} else {
				?>
					<tr>
						<td colspan="2" style="width: 100%; text-align: left; padding-right: 10px" class="firstChild">
							<?php if (isset($field_data['name']) && !empty($field_data['name'])) { ?>
								<label for="<?php _e($for) ?>"><code style="font-size: 20px; border: 2px solid #bbb; padding: 5px;"><?php _e($field_data['name']) ?></code></label>
							<?php } ?>
						</td>
					</tr>
				<?php			
			}
			return ob_get_clean();
		}
		
		// DELIMITER
		public function delimiter($identifier, $saved_value, $field_data) {
			if (!empty($field_data['custom_vars'])) extract($field_data['custom_vars']);
			ob_start();
			include(PMM_FIELDS_PATH . 'delimiter.php');
			$field_content = ob_get_clean();
			return $field_content;
		}		
		
		// TEXTBOX	
		public function textbox($identifier, $saved_value, $field_data) {
			if (!empty($field_data['custom_vars'])) extract($field_data['custom_vars']);
			ob_start();
			include(PMM_FIELDS_PATH . 'textbox.php');
			$field_content = ob_get_clean();
			return $this->wrap($field_content, $identifier, $field_data);
		}
		
		// TEXTAREA	
		public function textarea($identifier, $saved_value, $field_data) {
			if ($field_data['custom_vars']) extract($field_data['custom_vars']);
			ob_start();
			include(PMM_FIELDS_PATH . 'textarea.php');
			$field_content = ob_get_clean();
			return $this->wrap($field_content, $identifier, $field_data);
		}		
		
		// WP EDITOR		
		public function wp_editor($identifier, $saved_value, $field_data) {
			if ($field_data['custom_vars']) extract($field_data['custom_vars']);
			ob_start();
			include(PMM_FIELDS_PATH . 'wp_editor.php');
			$field_content = ob_get_clean();
			return $this->wrap($field_content, $identifier, $field_data);
		}
		
		// CHECKBOX			
		public function checkbox($identifier, $saved_value, $field_data) {
			if (!empty($field_data['custom_vars'])) extract($field_data['custom_vars']);
			ob_start();
			if (empty($saved_value)) {
				if (isset($checked_by_default) && $checked_by_default === true) {
					$saved_value = PostMetaManagerHelper::CheckboxChecked;
				} else {
					$saved_value = PostMetaManagerHelper::CheckboxUnchecked;
				}
			}
			include(PMM_FIELDS_PATH . 'checkbox.php');
			$field_content = ob_get_clean();
			return $this->wrap($field_content, $identifier, $field_data, 'dummy_' . $field_data['key']);			
		}
		
		// RADIO BUTTONS
		public function radio_buttons($identifier, $saved_value, $field_data) {
			if ($field_data['custom_vars']) extract($field_data['custom_vars']);
			ob_start();
			include(PMM_FIELDS_PATH . 'radio_buttons.php');
			$field_content = ob_get_clean();
			return $this->wrap($field_content, $identifier, $field_data);			
		}
		
		// DROP DOWN
		public function dropdown($identifier, $saved_value, $field_data) {
			if ($field_data['custom_vars']) extract($field_data['custom_vars']);
			ob_start();
			include(PMM_FIELDS_PATH . 'dropdown.php');
			$field_content = ob_get_clean();
			if ((isset($multiple) && is_bool($multiple) && $multiple === true) || (isset($stretch) && is_bool($stretch) && $stretch === true)) {
				return $this->wrap($field_content, $identifier, $field_data, null, false, true);
			} else {
				return $this->wrap($field_content, $identifier, $field_data);
			}	
		}		
		
		// MEDIA FILE UPLOAD (WP API)
		public function media_file_upload($identifier, $saved_value, $field_data) {
			if ($field_data['custom_vars']) extract($field_data['custom_vars']);
			ob_start();
			include(PMM_FIELDS_PATH . 'media_file_upload.php');
			$field_content = ob_get_clean();
			return $this->wrap($field_content, $identifier, $field_data);
		}
		
		// NESTABLE PICKER
		public function nestable_picker($identifier, $saved_value, $field_data) {
			if ($field_data['custom_vars']) extract($field_data['custom_vars']);
			ob_start();
			include(PMM_FIELDS_PATH . 'nestable_picker.php');
			$field_content = ob_get_clean();
			return $this->wrap($field_content, $identifier, $field_data);		
		}

		// HTML LIST
		public function html_list($identifier, $saved_value, $field_data) {
			if ($field_data['custom_vars']) extract($field_data['custom_vars']);
			ob_start();
			include(PMM_FIELDS_PATH . 'html_list.php');
			$field_content = ob_get_clean();
			return $this->wrap($field_content, $identifier, $field_data);		
		}
		
		// IMAGE GALLERY
		public function image_gallery($identifier, $saved_value, $field_data) {
			if ($field_data['custom_vars']) extract($field_data['custom_vars']);
			ob_start();
			include(PMM_FIELDS_PATH . 'image_gallery.php');
			$field_content = ob_get_clean();
			return $this->wrap($field_content, $identifier, $field_data);		
		}
		
		// VIDEO GALLERY
		public function video_gallery($identifier, $saved_value, $field_data) {
			if ($field_data['custom_vars']) extract($field_data['custom_vars']);
			ob_start();
			include(PMM_FIELDS_PATH . 'video_gallery.php');
			$field_content = ob_get_clean();
			return $this->wrap($field_content, $identifier, $field_data);		
		}		
		
		// CUSTOM FEATURED STACK
		public function custom_featured_stack($identifier, $saved_value, $field_data) {
			if ($field_data['custom_vars']) extract($field_data['custom_vars']);
			ob_start();
			include(PMM_FIELDS_PATH . 'custom_featured_stack.php');
			$field_content = ob_get_clean();
			return $this->wrap($field_content, $identifier, $field_data);		
		}
		
		// MULTI DROP DOWN
		public function multi_dropdown($identifier, $saved_value, $field_data) {
			if ($field_data['custom_vars']) extract($field_data['custom_vars']);
			ob_start();
			include(PMM_FIELDS_PATH . 'multi_drop_down.php');
			$field_content = ob_get_clean();
			return $this->wrap($field_content, $identifier, $field_data);		
		}
		
		// I WAS WARING
		public function i_was_wearing($identifier, $saved_value, $field_data) {
			if ($field_data['custom_vars']) extract($field_data['custom_vars']);
			ob_start();
			include(PMM_FIELDS_PATH . 'i_was_wearing.php');
			$field_content = ob_get_clean();
			return $this->wrap($field_content, $identifier, $field_data);		
		}		
		
		// SIMILAR ITEMS
		public function similar_items($identifier, $saved_value, $field_data) {
			if ($field_data['custom_vars']) extract($field_data['custom_vars']);
			ob_start();
			include(PMM_FIELDS_PATH . 'similar_items.php');
			$field_content = ob_get_clean();
			return $this->wrap($field_content, $identifier, $field_data);		
		}
		
		// COLOR
		public function color_picker($identifier, $saved_value, $field_data) {
			if ($field_data['custom_vars']) extract($field_data['custom_vars']);
			ob_start();
			include(PMM_FIELDS_PATH . 'color_picker.php');
			$field_content = ob_get_clean();
			return $this->wrap($field_content, $identifier, $field_data);		
		}
		
		// DATE PICKER
		public function date_picker($identifier, $saved_value, $field_data) {
			if ($field_data['custom_vars']) extract($field_data['custom_vars']);
			ob_start();
			include(PMM_FIELDS_PATH . 'date_picker.php');
			$field_content = ob_get_clean();
			return $this->wrap($field_content, $identifier, $field_data);		
		}
		
		// ATTACHMENT LIST
		public function attachment_list($identifier, $saved_value, $field_data) {
			if ($field_data['custom_vars']) extract($field_data['custom_vars']);
			ob_start();
			include(PMM_FIELDS_PATH . 'attachment_list.php');
			$field_content = ob_get_clean();
			return $this->wrap($field_content, $identifier, $field_data);		
		}

		// LABEL
		public function label($identifier, $saved_value, $field_data) {
			if ($field_data['custom_vars']) extract($field_data['custom_vars']);
			ob_start();
			include(PMM_FIELDS_PATH . 'label.php');
			$field_content = ob_get_clean();
			return $this->wrap($field_content, $identifier, $field_data);		
		}		
		
		// ITEM PICKER / SORTER
		public function item_picker_sorter($identifier, $saved_value, $field_data) {
			if ($field_data['custom_vars']) extract($field_data['custom_vars']);
			ob_start();
			include(PMM_FIELDS_PATH . 'item_picker_sorter.php');
			$field_content = ob_get_clean();
			return $this->wrap($field_content, $identifier, $field_data, null, false, true);		
		}		
		
		// SECTION HEADER
		public function section_header($identifier, $saved_value, $field_data) {
			if ($field_data['custom_vars']) extract($field_data['custom_vars']);
			return $this->wrap($field_content, $identifier, $field_data, null, true);		
		}		
	}
	
	/* ***************************************************** */
	// POST META MANAGER
	/* ***************************************************** */		
	class PostMetaManager
	{
		private $content = '';
		private $pmmf = null;
		private $meta = null;
		private $custom_field_identifier = null;
		
		public function __construct($custom_field_identifier, $meta) {
			$this->custom_field_identifier = $custom_field_identifier;
			$this->meta = $meta;
			$this->pmmf = new PostMetaManagerField();
		}

		public function start($class = 'PMMRoot', $style = 'border-collapse: collapse; width: 90%; margin: 0 auto;') {
			ob_start();
			?>
				<table class="<?php _e($class) ?>" style="<?php _e($style) ?>">
			<?php
			$this->content = ob_get_clean();
		}
		
		public function end() {
			ob_start();
			?>
				</table>
			<?php
			$this->content .= ob_get_clean();
		}
		
		public function flush() {
			_e($this->content);
		}
		
		public function reset() {
			$this->content = '';
		}
		
		public function add_fields($field_data) {
			if (empty($field_data['key']))
			{
				$field_data['key'] = 0;
			}
			$identifier  = $this->custom_field_identifier . '[' . $field_data['key'] . ']';
			$saved_value = isset($this->meta[$field_data['key']]) ? $this->meta[$field_data['key']] : '';
			$saved_value = is_array($saved_value) && count($saved_value) == 1 ? $saved_value[0] : $saved_value;

			switch ($field_data['type']) {
				case PostMetaManagerFieldType::Delimiter:
					$this->content .= $this->pmmf->delimiter($identifier, $saved_value, $field_data);
				break;			
				case PostMetaManagerFieldType::Textbox:
					$this->content .= $this->pmmf->textbox($identifier, $saved_value, $field_data);
				break;
				case PostMetaManagerFieldType::Textarea:
					$this->content .= $this->pmmf->textarea($identifier, $saved_value, $field_data);
				break;				
				case PostMetaManagerFieldType::WPEditor:
					$this->content .= $this->pmmf->wp_editor($identifier, $saved_value, $field_data);
				break;
				case PostMetaManagerFieldType::Checkbox:
					$this->content .= $this->pmmf->checkbox($identifier, $saved_value, $field_data);
				break;
				case PostMetaManagerFieldType::RadioButtons:
					$this->content .= $this->pmmf->radio_buttons($identifier, $saved_value, $field_data);
				break;
				case PostMetaManagerFieldType::DropDown:
					$this->content .= $this->pmmf->dropdown($identifier, $saved_value, $field_data);
				break;				
				case PostMetaManagerFieldType::MediaFileUpload:
					$this->content .= $this->pmmf->media_file_upload($identifier, $saved_value, $field_data);
				break;
				case PostMetaManagerFieldType::NestablePicker:
					$this->content .= $this->pmmf->nestable_picker($identifier, $saved_value, $field_data);
				break;
				case PostMetaManagerFieldType::HTMLList:
					$this->content .= $this->pmmf->html_list($identifier, $saved_value, $field_data);
				break;
				case PostMetaManagerFieldType::ImageGallery:
					$this->content .= $this->pmmf->image_gallery($identifier, $saved_value, $field_data);
				break;
				case PostMetaManagerFieldType::VideoGallery:
					$this->content .= $this->pmmf->video_gallery($identifier, $saved_value, $field_data);
				break;				
				case PostMetaManagerFieldType::CustomFeaturedStack:
					$this->content .= $this->pmmf->custom_featured_stack($identifier, $saved_value, $field_data);
				break;
				case PostMetaManagerFieldType::MultiDropDown:
					$this->content .= $this->pmmf->multi_dropdown($identifier, $saved_value, $field_data);
				break;	
				case PostMetaManagerFieldType::IWasWearing:
					$this->content .= $this->pmmf->i_was_wearing($identifier, $saved_value, $field_data);
				break;
				case PostMetaManagerFieldType::SimilarItems:
					$this->content .= $this->pmmf->similar_items($identifier, $saved_value, $field_data);
				break;
				case PostMetaManagerFieldType::ColorPicker:
					$this->content .= $this->pmmf->color_picker($identifier, $saved_value, $field_data);
				break;
				case PostMetaManagerFieldType::DatePicker:
					$this->content .= $this->pmmf->date_picker($identifier, $saved_value, $field_data);
				break;
				case PostMetaManagerFieldType::AttachmentList:
					$this->content .= $this->pmmf->attachment_list($identifier, $saved_value, $field_data);
				break;
				case PostMetaManagerFieldType::Label:
					$this->content .= $this->pmmf->label($identifier, $saved_value, $field_data);
				break;
				case PostMetaManagerFieldType::ItemPickerSorter:
					$this->content .= $this->pmmf->item_picker_sorter($identifier, $saved_value, $field_data);
				break;	
				case PostMetaManagerFieldType::SectionHeader:
					$this->content .= $this->pmmf->section_header($identifier, $saved_value, $field_data);
				break;				
				default:
				break;
			}		
		}
		
		public function output($data_fields, $tabbed = false, $vertical = false) {
			if (count($data_fields) > 0) {
				if ($tabbed) {
					?>
						<div id="<?php _e($this->custom_field_identifier) ?>" style="visibility: hidden;">
							<ul>
								<?php foreach ($data_fields as $field_name => $field_data) { ?>
									<li><a href="#<?php _e(sanitize_title($field_name)) ?>"><?php _e($field_name) ?></a></li>
								<?php } ?>
							</ul>
							<?php foreach ($data_fields as $field_name => $fields) { ?>
								<div id="<?php _e(sanitize_title($field_name)) ?>">
									<?php
									$this->start();
									foreach ($fields as $field_data) $this->add_fields($field_data);
									$this->end();
									$this->flush();
									?>
								</div>
							<?php } ?>
						</div>
						<script type="text/javascript">
							jQuery(function ($)
							{
								<?php if (!$vertical) { ?>
									$("#<?php _e($this->custom_field_identifier) ?>").tabs().css("visibility", "visible");
								<?php } else { ?>
									$("#<?php _e($this->custom_field_identifier) ?>").tabs().addClass("ui-tabs-vertical ui-helper-clearfix");
									$("#<?php _e($this->custom_field_identifier) ?> > .ui-tabs-nav > li").removeClass("ui-corner-top").addClass("ui-corner-left");
									$("#<?php _e($this->custom_field_identifier) ?>").css("visibility", "visible");
								<?php } ?>
							});
						</script>			
					<?php
				} else {
					?>
						<?php foreach ($data_fields as $field_name => $fields) { ?>
							<div id="<?php _e(sanitize_title($field_name)) ?>">
								<?php
									$this->start();
									foreach ($fields as $field_data) $this->add_fields($field_data);
									$this->end();
									$this->flush();
								?>
							</div>
						<?php } ?>					
					<?php			
				}
			}
		}
	}
?>