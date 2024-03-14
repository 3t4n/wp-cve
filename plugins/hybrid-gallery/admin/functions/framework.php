<?php

if( !defined( 'ABSPATH') ) exit();

// Function create panel fields
// ======================================================

if (!function_exists('hybrid_gallery_framework')) {
function hybrid_gallery_framework($atts, $mode = 'grid')
{
    $output = '';
    
    foreach ($atts as $value) {
        $dep_class = '';
        $dep_data  = '';
        
        $parent_value = $value['dependency']['value'];
        if (is_array($parent_value)) {
            $parent_value = implode(',', $parent_value);
        }
        
        if ($value['dependency']['id']) {
            $dep_class = ' has-parent';
            $dep_data  = ' data-parent="hybgl-panel-field-' . $value['dependency']['id'] . '" data-parent-value="' . $parent_value . '"';
        }
        
        if ($value['class']) {
            $extra_class = ' ' . $value['class'];
        } else {
            $extra_class = '';
        }
        
        if (!$value['name']) {
            $field_title = ' hybgl-field-no-title';
        } else {
            $field_title = '';
        }
        
        switch ($value['type']) {
            
            // Type: Preview
            case 'preview':
                
                $output .= '<div id="hybgl-panel-field-' . $value['id'] . '" class="hybgl-field-type-preview">';
                $output .= '<div id="hybgl-preview" class="hybgl-clearfix">';
                
                if (is_array($value['options'])) {
                    foreach ($value['options'] as $hybrid_gallery_panel_field) {
                        $pr_image = wp_get_attachment_image_src($hybrid_gallery_panel_field, 'thumbnail');
                        
                        $output .= '<div class="hybgl-preview-image">';
                            $output .= '<div class="hybgl-preview-image-inner">';
                                $output .= '<div id="' . $hybrid_gallery_panel_field . '" class="hybgl-preview-image-content" style="background-image:url(' . $pr_image[0] . ');"></div>';                                
                                $output .= '<i class="hybgl-preview-remove"></i>';
                                $output .= '<i class="hybgl-preview-edit"></i>';
                                $output .= '<i class="hybgl-preview-replace"></i>';
                            $output .= '</div>';
                        $output .= '</div>';
                        
                    }
                }
                
                $output .= '</div>';
                $output .= '</div>';
                
                
                break;
            
            // Type: Cols
            case 'cols':
                
                if ($value['action'] == 'start') {
                    $output .= '<div class="hybgl-cols ' . $value['class'] . ' hybgl-clearfix">';
                    $output .= '<div class="hybgl-col hybgl-' . $value['size'] . '">';
                } elseif ($value['action'] == 'change') {
                    $output .= '</div>';
                    $output .= '<div class="hybgl-col hybgl-' . $value['size'] . '">';
                } elseif ($value['action'] == 'end') {
                    $output .= '</div>';
                    $output .= '</div>';
                }
                
                
                break;
            
            // Type: Image Properties
            case 'img_selector':
                
                $output .= '<div id="hybgl-panel-field-' . $value['id'] . '" class="hybgl-control-buttons hybgl-clearfix">';
                    $output .= '<div id="hybgl-' . $value['id'] . '-erase" class="hybgl-panel-icon-button hybgl-panel-icon-button-erase"><i class="fa fa-eraser"></i></div>';
                    $output .= '<div id="hybgl-' . $value['id'] . '-edit" class="hybgl-panel-icon-button hybgl-panel-icon-button-edit"><i class="fa fa-pencil"></i></div>';
                    $output .= '<div id="hybgl-' . $value['id'] . '-add" class="hybgl-panel-icon-button hybgl-panel-icon-button-add"><i class="fa fa-plus"></i></div>';
                $output .= '</div>';
                
                
                break;
            
            // Type: Tab Switcher
            case 'tab-switcher':
                
                $output .= '<div id="hybgl-tabs-switcher" class="hybgl-align-right hybgl-clearfix">';
                $output .= '<ul class="hybgl-opt-tabs hybgl-clearfix">';
                
                foreach ($value['tabs'] as $tab => $tkey) {
                    $tn++;
                    if ($tn == 1) {
                        $tselected = ' class="hybgl-current-tab-swithcer"';
                    } else {
                        $tselected = '';
                    }
                    
                    $output .= '<li' . $tselected . ' data-tab="#hybgl-tab-' . $tn . '">' . $tkey . '</li>';
                }
                
                $output .= '</ul>';
                $output .= '</div>';
                
                
                break;
            
            // Type: Tab
            case 'tab':
                
                if ($value['action'] == "start") {
                    $tab_num++;
                    if ($tab_num == 1) {
                        $tab_checked = ' hybgl-tab-current';
                    } else {
                        $tab_checked = '';
                    }
                    
                    $output .= '<div id="hybgl-tab-' . $tab_num . '" class="hybgl-tab-content' . $tab_checked . '">';
                } else {
                    $output .= '</div>';
                }
                
                
                break;
            
            // Type: Info
            case 'info':
                
                $output .= '<div id="hybgl-panel-field-' . $value['id'] . '"' . $dep_data . ' class="hybgl-field hybgl-field-type-info' . $extra_class . $dep_class . $field_title . ' hybgl-clearfix">';
                
                    if ($value['name']) {
                        $output .= '<label for="' . $value['id'] . '">' . $value['name'] . '</label>';
                    }
                    $output .= '<p>' . $value['html'] . '</p>';
                
                $output .= '</div>';
                
                
                break;
            
            // Type: Text
            case 'text':
                
                $output .= '<div id="hybgl-panel-field-' . $value['id'] . '"' . $dep_data . ' class="hybgl-field hybgl-field-type-text' . $extra_class . $dep_class . $field_title . ' hybgl-clearfix">';
                    $output .= '<label for="' . $value['id'] . '">' . $value['name'] . '</label>';
                    $output .= '<input name="' . $value['id'] . '" id="hybgl-' . $value['id'] . '" type="' . $value['type'] . '" value="' . $value['default'] . '" />';
                    if ($value['description']) {
                        $output .= '<div class="hybgl-panel-field-descr">' . $value['description'] . '</div>';
                    }
                $output .= '</div>';
                
                
                break;
            
            // Type: Number
            case 'number':
                
                $output .= '<div id="hybgl-panel-field-' . $value['id'] . '"' . $dep_data . ' class="hybgl-field hybgl-field-type-number' . $extra_class . $dep_class . $field_title . ' hybgl-clearfix">';
                    $output .= '<label for="' . $value['id'] . '">' . $value['name'] . '</label>';
                    $output .= '<input name="' . $value['id'] . '" id="hybgl-' . $value['id'] . '" type="' . $value['type'] . '" value="' . $value['default'] . '" />';
                    if ($value['description']) {
                        $output .= '<div class="hybgl-panel-field-descr">' . $value['description'] . '</div>';
                    }
                $output .= '</div>';
                

                break;

            // Type: Color
            case 'color':

                $output .= '<div id="hybgl-panel-field-' . $value['id'] . '"' . $dep_data . ' class="hybgl-field hybgl-field-type-color' . $extra_class . $dep_class . $field_title . ' hybgl-clearfix">';
                    $output .= '<label for="' . $value['id'] . '">' . $value['name'] . '</label>';
                    $output .= '<input id="hybgl-' . $value['id'] . '-visual" type="text" class="hybgl-input-color-visual" value="' . $value['default'] . '" data-default-color="' . $value['hex'] . '">';
                    $output .= '<input name="' . $value['id'] . '" id="hybgl-' . $value['id'] . '" type="text" class="hybgl-input-color" value="' . $value['default'] . '">';
                    if ($value['description']) {
                        $output .= '<div class="hybgl-panel-field-descr">' . $value['description'] . '</div>';
                    }
                $output .= '</div>';
                

                break;
            
            // Type: Select
            case 'select':
                
                $output .= '<div id="hybgl-panel-field-' . $value['id'] . '"' . $dep_data . ' class="hybgl-field hybgl-field-type-select' . $extra_class . $dep_class . $field_title . ' hybgl-clearfix">';
                    $output .= '<label for="' . $value['id'] . '">' . $value['name'] . '</label>';
                    $output .= '<select name="' . $value['id'] . '" id="hybgl-' . $value['id'] . '">';
                
                        foreach ($value['options'] as $hybrid_gallery_panel_field => $key) {
                            if (isset($value['default']) && $value['default'] == $hybrid_gallery_panel_field) {
                                $selected = ' selected="selected"';
                            } else {
                                $selected = '';
                            }
                    
                            if ( $hybrid_gallery_panel_field == "lg" || $hybrid_gallery_panel_field == "pp" || $hybrid_gallery_panel_field == "fyb" || $hybrid_gallery_panel_field == "lib" || $hybrid_gallery_panel_field == "lc" || $hybrid_gallery_panel_field == "ilb" ) {
                                $disabled = ' disabled="disabled"';
                            } else {
                                $disabled = '';
                            }

                            $output .= '<option' . $disabled . ' value="' . $hybrid_gallery_panel_field . '"' . $selected . '>' . $key . '</option>';
                        }
                
                    $output .= '</select>';
                    if ($value['description']) {
                        $output .= '<div class="hybgl-panel-field-descr">' . $value['description'] . '</div>';
                    }
                $output .= '</div>';
                
                
                break;
        }
    }
    
    $output.= '<div class="hybgl-field-type-button hybgl-field-insert-buttons hybgl-clearfix">';
        $output.= '<div class="hybgl-field-insert-buttons-content">';
            $output.= '<div class="hybgl-field-tmpl-insert"><input type="text" placeholder="' . esc_html__('Enter tmplate name', 'hybrid-gallery') . '" value="" id="hybgl-field-tmpl-name"><span id="hybgl-field-tmpl-save-button"><i class="fa fa-chevron-right"></i></span></div>';
            $output.= '<div class="hybgl-button hybgl-button-sep" id="hybgl-save-button"><span>' . esc_html__('Save Template', 'hybrid-gallery') . '</span> <i class="fa fa-clipboard"></i></div>';
            $output.= '<div class="hybgl-button hybgl-button-sep" id="hybgl-cancel-button"><span>' . esc_html__('Cancel', 'hybrid-gallery') . '</span> <i class="fa fa-times-circle"></i></div>';
            $output.= '<div class="hybgl-button" id="hybgl-ins-button"><span>' . esc_html__('Insert', 'hybrid-gallery') . '</span> <i class="fa fa-cloud-download"></i></div>';
        $output.= '</div>';
    $output.= '</div>';

    // Output All;
    echo $output;
?>

<script>
jQuery.noConflict();
(function($) {
    "use strict";
    
    $.fn.HybridGalleryPopupPanel = function(screen) {
        var panel = {
                popup: this,
                popupMask: $('.hybgl-popup-mask'),
                popupEditor: this.find('.hybgl-popup-editor'),
                preview: this.find('#hybgl-preview'),
                ids: this.find('#hybgl-ids'),
                imgButtonsAdd: this.find('#hybgl-image-selector-add'),
                imgButtonsEdit: this.find('#hybgl-image-selector-edit'),
                imgButtonsErase: this.find('#hybgl-image-selector-erase'),
                insButton: this.find('#hybgl-ins-button'),
                saveButton: this.find('#hybgl-save-button'),
                cancelButton: this.find('#hybgl-cancel-button'),
            };


        $(document).ready(function() {
            preview();

            imgsPrNew();
            imgsPrEdit();
            imgsPrErase();

            imgRemove();
            imgEdit();
            imgReplace();

            shGen();

            fields();
            tabs();

            picker();
        });


        // HYBGL - Sort Preview Images
        function preview() {
            panel.preview.sortable({
                itemSelector: '.hybgl-preview-image-content',
                update: function(event, ui) {
                    var img_container = ui.item.parent().attr('id');
                    var img_id_url = ui.item.attr('src');
                    var img_container_id = '#' + img_container;
                    var img_id_numbers = [];

                    $(img_container_id).find('.hybgl-preview-image-content').each(function() {
                        var tid = $(this).attr('id');
                        img_id_numbers.push(tid);
                    });

                    panel.ids.val(img_id_numbers);
                },
            });
        }


        // HYBGL - Reemove Preview Images
        function imgRemove() {
            panel.preview.find(".hybgl-preview-remove").on("click", function() {
                var img_id = $(this).parent().find(".hybgl-preview-image-content").attr('id'),
                    img_id_numbers = [];

                $(this).parent().remove();

                panel.preview.find('.hybgl-preview-image-content').each(function() {
                    var tid = $(this).attr('id');
                    img_id_numbers.push(tid);
                });

                panel.ids.val(img_id_numbers);
            });
        }


        // HYBGL - Edit Preview Images
        function imgEdit() {
            panel.preview.find(".hybgl-preview-edit").on("click", function() {
                var attachment_ids = [],
                    attachment_id = $(this).parent().find(".hybgl-preview-image-content").attr('id'),
                    frame;

                attachment_ids.push(attachment_id);

                if (frame) {
                    frame.open();
                    return;
                }

                frame = wp.media.frames.frame = wp.media({
                    title: 'Edit Image',
                    library: {
                        type: 'image',
                        post__in: attachment_ids
                    },
                    button: {
                        text: '<?php echo esc_js(__("Done", "hybrid-gallery")); ?>'
                    },
                    multiple: 'false'
                });

                frame.on('open', function() {
                    var selection = frame.state().get('selection'),
                        attachment = wp.media.attachment(attachment_id);
                        attachment.fetch();
                        selection.add(attachment ? [attachment] : []);
                });

                frame.open();
            });
        }


        // HYBGL - Replace Replace Images
        function imgReplace() {
            panel.preview.find(".hybgl-preview-replace").on("click", function() {
                var frame,
                    selected_data = {},
                    selection = [],
                    attachment_id = $(this).parent().find(".hybgl-preview-image-content").attr('id'),
                    $this = $(this);

                if (frame) {
                    frame.open();
                    return;
                }

                frame = wp.media.frames.frame = wp.media({
                    title: '<?php echo esc_js(__("Select Image", "hybrid-gallery")); ?>',
                    library: {
                        type: 'image'
                    },
                    button: {
                        text: '<?php echo esc_js(__("Replace", "hybrid-gallery")); ?>'
                    },
                    multiple: 'false'
                });

                frame.on('select', function() {
                    selected_data = frame.state().get('selection').first().toJSON();

                    var bImage = selected_data[k].sizes.thumbnail ? selected_data[k].sizes.thumbnail.url : selected_data[k].sizes.full.url;
                    $this.parent().parent().html("<div class='hybgl-preview-image'><div class='hybgl-preview-image-inner'><div id='" + selected_data.id + "' class='hybgl-preview-image-content' style='background-image:url(" + bImage + ");'></div><i class='hybgl-preview-remove'></i><i class='hybgl-preview-edit'></i><i class='hybgl-preview-replace'></i></div></div>");
                    var img_id_numbers = [];

                    panel.preview.find('.hybgl-preview-image-content').each(function() {
                        var tid = $(this).attr('id');
                        img_id_numbers.push(tid);
                    });

                    panel.ids.val(img_id_numbers);

                    imgRemove();
                    imgEdit();
                    imgReplace();
                });

                frame.on('open', function() {
                    selection = frame.state().get('selection');
                    var attachment = wp.media.attachment(attachment_id);
                    attachment.fetch();
                    selection.add(attachment ? [attachment] : []);
                });

                frame.open();
            });
        }


        // HYBGL - Upload New Images
        function imgsPrNew() {
            panel.imgButtonsAdd.on("click", function(event) {
                var frame,
                    selected_data = {},
                    image_ids = [],
                    image_src = [],
                    image_prv = [];

                if (frame) {
                    frame.open();
                    return;
                }

                frame = wp.media.frames.frame = wp.media({
                    title: '<?php echo esc_js(__("Select New Images", "hybrid-gallery")); ?>',
                    library: {
                        type: 'image'
                    },
                    button: {
                        text: '<?php echo esc_js(__("Insert", "hybrid-gallery")); ?>'
                    },
                    multiple: 'add'
                });

                frame.on('select', function() {
                    selected_data = frame.state().get('selection').toJSON();
                    // append images
                    for (var k in selected_data) {
                        // new ids array
                        image_ids.push(selected_data[k].id);
                        var bImage = selected_data[k].sizes.thumbnail ? selected_data[k].sizes.thumbnail.url : selected_data[k].sizes.full.url;
                        panel.preview.append("<div class='hybgl-preview-image'><div class='hybgl-preview-image-inner'><img id='" + selected_data[k].id + "' src='" + bImage + "'><i class='hybgl-preview-remove'></i><i class='hybgl-preview-edit'></i><i class='hybgl-preview-replace'></i></div></div>");
                    }
                    var old_ids = panel.ids.val();

                    // create array
                    if (old_ids.length > 0) {
                        old_ids = old_ids.split(',');
                    } else {
                        old_ids = old_ids.split('');
                    }

                    // merge and create new array
                    old_ids = $.merge(old_ids, image_ids);

                    // set imgae ids
                    panel.ids.val(old_ids);

                    imgRemove();
                    imgEdit();
                    imgReplace();
                });
                frame.open();
            });
        }

        // HYBGL - Edit images
        function imgsPrEdit() {
            panel.imgButtonsEdit.on("click", function(event) {
                panel.preview.find('.hybgl-preview-image-content').each(function() {
                    var tid = $(this).attr('id');
                    img_id_numbers.push(tid);
                });

                var img_id_numbers = [],
                    frame;

                if (frame) {
                    frame.open();
                    return;
                }

                frame = wp.media.frames.frame = wp.media({
                    title: '<?php echo esc_js(__("Edit Images", "hybrid-gallery")); ?>',
                    library: {
                        type: 'image',
                        orderby: 'post__in',
                        post__in: img_id_numbers,
                    },
                    button: {
                        text: '<?php echo esc_js(__("Ready", "hybrid-galery")); ?>'
                    },
                    multiple: 'false'
                });

                frame.open();
            });
        }

        // HYBGL - Insert Generated Shortcode
        function shGen() {
            panel.insButton.on("click", function() {
                <?php
                $ins_attr_array = array();
                $ins_res = '';

                    foreach ($atts as $value) {  
                        if ( $value['attr'] && $value['type'] != "info" ) {
                            if ( $value['mode'] == "res" ) {
                                $s_w = preg_replace("/[^0-9]/", "", $value['attr']);
                                $s_w_regex = 'res_' . $s_w . '_';
                                $ins_res_attr = "#" . str_replace($s_w_regex, "", $value['attr']) . "#";
                                $ins_res_var = 'ins_res_attr_' . $value['point'] . '_' . $value['attr'];

                                echo 'var ' . $ins_res_var . ' = $("#hybgl-' . $value['id'] . '").val(); ';

                                if ( $value['point'] == "width" ) {
                                    $pf_before = "{";
                                } else {
                                    $pf_before = '';
                                }

                                if ( $value['point'] == "align" ) {
                                    $pf_after = "}";
                                } else {
                                    $pf_after = ",";
                                }

                                if ( $value['point'] == "screen" ) {
                                    $ins_ss++; 

                                    if ( $ins_ss == 1 ) {
                                        $ins_res_ss_prefix = "";
                                    }  else {
                                        $ins_res_ss_prefix = ",";
                                    }
                                    $ins_res.= $ins_res_ss_prefix . "#" . '"+' . $ins_res_var . ' +"' . "#:";
                                } else {
                                    $ins_res.= $pf_before . $ins_res_attr . ":#" . '"+' . $ins_res_var . ' +"' . "#" . $pf_after;
                                }
                            } else {
                                echo 'var ins_attr_' . $value['attr'] . ' = $("#hybgl-' . $value['id'] . '").val(); ';   
  
                                $attr_array_value = $value['attr'] . '="' . "'+ ins_attr_" . $value['attr'] . " +'" . '"';
                                array_push($ins_attr_array, $attr_array_value);
                            }
                        }      
                    }

                    $ins_attr_array = implode(' ', $ins_attr_array); 
            ?>

                var ins_all_attr = '<?php echo $ins_attr_array; ?>',
                    ins_all_res_attr = "<?php echo $ins_res; ?>",

                    hybEditor = panel.popupEditor.html();
                    
                if (!hybEditor) {
                    hybEditor = 'content';
                }

                var insertShortcode = '[hybrid_gallery_<?php echo esc_js(__($mode)); ?> ' + ins_all_attr + ' res="{' + ins_all_res_attr + '}"]';

                if (hybEditor && hybEditor != 'content') {
                    tinymce.get(hybEditor).execCommand('mceInsertContent', false, insertShortcode);
                } else {
                    wp.media.editor.insert(insertShortcode);
                }
         
                panel.popup.removeClass("hybgl-popup-active");
                panel.popupMask.removeClass("hybgl-popup-mask-active");
            });
            panel.cancelButton.on("click", function() {
                panel.popup.removeClass("hybgl-popup-active");
                panel.popupMask.removeClass("hybgl-popup-mask-active");
            });
            panel.saveButton.on("click", function() {
                if ($(".hybgl-field-tmpl-insert").hasClass("hybgl-field-tmpl-insert-active")) {
                    $(".hybgl-field-tmpl-insert").removeClass("hybgl-field-tmpl-insert-active");
                } else {
                    $(".hybgl-field-tmpl-insert").addClass("hybgl-field-tmpl-insert-active");
                }
            });
            $("#hybgl-field-tmpl-save-button").on("click", function() {
                var $button = $(this);
                var $icon = $(this).find("i");
                $icon.removeClass("fa-chevron-right").addClass("fa-spinner fa-spin");

                var save_attr_layout = '';

                <?php 
                $save_attr_array = array();
                $save_res = '';

                    foreach ($atts as $value) {  
                        if ( $value['attr'] && $value['type'] != "info" && $value['attr'] != "ids" ) {
                            if ( $value['mode'] == "res" ) {
                                $s_w = preg_replace("/[^0-9]/", "", $value['attr']);
                                $s_w_regex = 'res_' . $s_w . '_';
                                $save_res_attr = "#" . str_replace($s_w_regex, "", $value['attr']) . "#";
                                $save_res_var = 'ins_res_attr_' . $value['point'] . '_' . $value['attr'];

                                echo 'var ' . $save_res_var . ' = $("#hybgl-' . $value['id'] . '").val(); ';
                                
                                if ( $value['attr'] == "layout" ) {
                                    echo 'save_attr_layout = ' . $save_res_var . '; ';
                                }

                                if ( $value['point'] == "width" ) {
                                    $pf_before = "{";
                                } else {
                                    $pf_before = '';
                                }

                                if ( $value['point'] == "align" ) {
                                    $pf_after = "}";
                                } else {
                                    $pf_after = ",";
                                }

                                if ( $value['point'] == "screen" ) {
                                    $save_ss++; 

                                    if ( $save_ss == 1 ) {
                                        $save_res_ss_prefix = "";
                                    }  else {
                                        $save_res_ss_prefix = ",";
                                    }
                                    $save_res.= $save_res_ss_prefix . "#" . '"+' . $save_res_var . ' +"' . "#:";
                                } else {
                                    $save_res.= $pf_before . $save_res_attr . ":#" . '"+' . $save_res_var . ' +"' . "#" . $pf_after;
                                }
                            } else {
                                echo 'var save_attr_' . $value['attr'] . ' = $("#hybgl-' . $value['id'] . '").val(); ';   

                                $attr_array_value = $value['attr'] . '="' . "'+ save_attr_" . $value['attr'] . " +'" . '"';
                                array_push($save_attr_array, $attr_array_value);
                            }
                        }      
                    }

                    $save_attr_array = implode(' ', $save_attr_array); 
            ?>

                var save_all_attr = '<?php echo $save_attr_array; ?>',
                    save_all_res_attr = "<?php echo $save_res; ?>",
                    save_final_attr = save_all_attr + ' res="{' + save_all_res_attr + '}"';

                var tmpl_name = $("#hybgl-field-tmpl-name").val();
                var tmpl_save = {};
                tmpl_save.action = 'hybrid_gallery_sc_tmpl_save';
                tmpl_save.nonce = hybrid_gallery_ajax_request.nonce;
                tmpl_save.name = tmpl_name;
                tmpl_save.shortcode = '<?php echo esc_attr($mode); ?>';
                tmpl_save.layout = save_attr_layout,
                tmpl_save.value = save_final_attr;

                $.ajax({
                    type: 'POST',
                    dataType: 'html',
                    url: hybrid_gallery_ajax_request.ajaxUrl,
                    cache: false,
                    data: tmpl_save,
                    success: function(data) {
                        $icon.removeClass("fa-spinner fa-spin").addClass("fa-check");
                        $(".hybgl-field-tmpl-insert").removeClass("hybgl-field-tmpl-insert-active");
                        $("#hybgl-field-tmpl-name").val("");
                        $icon.removeClass("fa-check").addClass("fa-chevron-right");
                        panel.saveButton.addClass('hybgl-save-button-saved').find('span').text('<?php echo esc_js(__("Template was saved!", "hybrid-gallery")); ?>');

                        setTimeout(function() {
                            panel.saveButton.removeClass('hybgl-save-button-saved').find('span').text('<?php echo esc_js(__("Save Template", "hybrid-gallery")); ?>');
                        }, 3000);
                    }
                });
            });
        }

        // HYBGL - Edit images
        function imgsPrErase() {
            panel.imgButtonsErase.on("click", function() {
                panel.preview.html('');
                panel.ids.val('');
            });
        }

        // HYBGL Field
        function fields() {
            var child = ".has-parent";
            var hide_class = "hybgl-field-hide";
            var show_class = "hybgl-field-show";
            var parent = [];
            var selected_parent_value = [];
            $(child).each(function() {
                var $this = $(this);
                parent = $this.attr('data-parent');
                var parent_lev2 = $("#" + parent).attr('data-parent');
                var parent_val = $this.attr('data-parent-value');
                var parent_def_val = $('#' + parent + ' :selected').val();
                parent_val = parent_val.split(',');
                if ($.inArray(parent_def_val, parent_val) != -1) {
                    $this.removeClass(hide_class).addClass(show_class);
                } else {
                    $this.removeClass(show_class).addClass(hide_class);
                }
                $("#" + parent).on('change', function() {
                    var parent_sel_val = $(this).find(":selected").val();
                    if ($.inArray(parent_sel_val, parent_val) != -1) {
                        $this.removeClass(hide_class).addClass(show_class);
                    } else {
                        $this.removeClass(show_class).addClass(hide_class);
                    }
                    $(child).each(function() {
                        var $sel_this = $(this),
                            selected_parent = $sel_this.attr('data-parent');
                        selected_parent_value = $sel_this.attr('data-parent-value');
                        var selected_sel_val = $('#' + selected_parent).find(":selected").val();
                        selected_parent_value = selected_parent_value.split(',');
                        if ($('#' + selected_parent).hasClass(hide_class)) {
                            $sel_this.removeClass(show_class).addClass(hide_class);
                        }
                        if ($('#' + selected_parent).hasClass(show_class)) {
                            if ($.inArray(selected_sel_val, selected_parent_value) != -1) {
                                $sel_this.removeClass(hide_class).addClass(show_class);
                            } else {
                                $sel_this.removeClass(show_class).addClass(hide_class);
                            }
                        }
                    });
                });
            });
        }

        // HYBGL Tabs
        function tabs() {
            $(".hybgl-opt-tabs li").on("click", function() {
                var $this = $(this);
                $(".hybgl-opt-tabs li").each(function() {
                    $(this).removeClass("hybgl-current-tab-swithcer");
                });
                $this.addClass("hybgl-current-tab-swithcer");
                $(".hybgl-tab-content").each(function() {
                    $(this).removeClass("hybgl-tab-current");
                });
                var tab = $this.attr('data-tab');
                $(tab).addClass("hybgl-tab-current");
            });
        }

        function picker() {
            $('.hybgl-popup').find('.hybgl-input-color-visual').wpColorPicker({
                change: function(event, ui) {
                    $(this).closest('.hybgl-field-type-color').find('.hybgl-input-color').val(ui.color.toString());
                },
                clear: function() {
                    var defColor = $(this).data('default-color');
                    $(this).closest('.hybgl-field-type-color').find('.hybgl-input-color').val(defColor);                    
                },
                hide: true,
                palettes: true
            });
        }
    }

    $(document).ready(function() {
        $('.hybgl-popup').HybridGalleryPopupPanel();
    });
})(jQuery);
</script>

<?php 
}}