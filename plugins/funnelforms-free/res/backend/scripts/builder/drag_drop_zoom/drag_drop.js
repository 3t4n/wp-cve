let af2_drag_insertData;

let af2_drag_offsetX;
let af2_drag_offsetY;

jQuery( document ).ready(function() {

    af2_drag_insertData = null;
    af2_drag_before_position = null;
    af2_drag_content_id = null;
    af2_trigger_id = null;

    interact('.af2_array_draggable').draggable({
        modifiers: [
            interact.modifiers.restrict({
              restriction: '.af2_array_draggable_restrict',
              endOnly: true
            })
        ],
        listeners: {
            start (event) {
                af2_drag_offsetX = 0;
                af2_drag_offsetY = 0;

                const old_ = event.target.getBoundingClientRect();

                let target = event.target;

                jQuery(target).addClass('af2_dragging');

                const new_ = target.getBoundingClientRect();
                af2_drag_offsetX = old_.x - new_.x;
                af2_drag_offsetY = old_.y - new_.y;

                af2_drag_array_draggable_object(af2_setInsertData, target);
            },
            move (event) { dragMoveListener(event, true); },
            end (event) {
                if(jQuery(event.target).hasClass('af2_no_remove')) return;
                jQuery(event.target).remove();
            }
        }
    });

    interact('.af2_array_add_draggable').draggable({
        modifiers: [
            interact.modifiers.restrict({
              restriction: '.af2_add_array_draggable_restrict',
              endOnly: true
            })
        ],
        listeners: {
            start (event) {
                af2_drag_offsetX = 0;
                af2_drag_offsetY = 0;
                const old_ = event.target.getBoundingClientRect();

                // Fetch Parent and old html
                let target = event.target;
                jQuery(target).addClass('af2_dragging');
                parent = jQuery(target).parent();
                parentHtml = jQuery(parent).html();

                // move it into
                jQuery('.af2_builder_content').prepend(jQuery(target));

                af2_drag_array_add_draggable_object(target);

                const width = old_.width;

                // reset the parent
                jQuery(parent).html(parentHtml);
                jQuery(parent).find('.af2_dragging').attr('style', '');
                jQuery(parent).find('.af2_dragging').removeClass('af2_dragging');
                jQuery(target).attr('style', 'width: '+width+'px;');

                // Drag offset
                const new_ = event.target.getBoundingClientRect();
                af2_drag_offsetX = old_.x - new_.x;
                af2_drag_offsetY = old_.y - new_.y;
            },
            move (event) { dragMoveListener(event, false); },
            end (event) {
                if(jQuery(event.target).hasClass('af2_drop_on_remove')) 
                {
                    jQuery(event.target).remove();
                    jQuery('.af2_array_dropzone_in').remove();
                }
                else {
                    jQuery(event.target).attr('data-x', jQuery(event.target).data('translationx'));
                    jQuery(event.target).attr('data-y', jQuery(event.target).data('translationy'));
                }
            }
        }
    });

    interact('.af2_line_draggable').draggable({
        modifiers: [],
        listeners: {
            start (event) {
                jQuery(event.target).addClass('af2_dragging_line');
                const {x1, y1} = af2_svg_calc_initial_path(event.target, '.af2_draw_svg');

                af2_svg_append_path('.af2_draw_svg', 'af2_path_dragging_gen', x1, y1, x1, y1);
                af2_svg_redraw('.af2_draw_svg');

                jQuery('.af2_array_dropzone_in').addClass('no_hover');
            },
            move (event) {
                const {x2, y2, d} = af2_svg_calc_moving_path('#af2_path_dragging_gen', event.dx, event.dy);

                af2_svg_change_path('#af2_path_dragging_gen', d, null, null, x2, y2);
                af2_svg_redraw('.af2_draw_svg');
            },
            end (event) {
                jQuery(event.target).removeClass('af2_dragging_line');
                
                jQuery('#af2_path_dragging_gen').remove();
                af2_svg_redraw('.af2_draw_svg');

                if(jQuery('.af2_is_droppable').length > 0) {
                    const dropElement = jQuery('.af2_is_droppable')[0];
                    const pos1 = af2_svg_calc_initial_path(event.target, '.af2_draw_svg');
                    const pos2 = af2_svg_calc_initial_path(dropElement, '.af2_draw_svg');

                    af2_dropped_line(event.target, dropElement, pos1.x1, pos2.x1, pos1.y1, pos2.y1, '.af2_array_dropzone_in');
                }

                jQuery('.af2_array_dropzone_in').removeClass('no_hover');
            }
        }
    });

    interact('.af2_array_dropzone_before').dropzone({
        // only accept elements matching this CSS selector
        accept: '.af2_array_draggable',
        // Require a 75% element overlap for a drop to be possible
        overlap: 0.01,
      
        ondrop: function (event) {

            const target = event.relatedTarget;
            af2_drop_array_draggable_object( af2_drag_insertData, jQuery(target).data('editcontentarrayid'), target);

            af2_drag_insertData = null;

            
            // Sidebar
            jQuery('.af2_builder_editable_object.selected').removeClass('selected');

            const handler = jQuery('.af2_builder_sidebar.editSidebar');
    
            handler.one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', _ => {
                jQuery('.editSidebar .af2_builder_sidebar_content_wrapper').html();
            });
            handler.addClass('hide');
            jQuery('.af2_builder_content').removeClass('no_margin');
        },
      });
    interact('.af2_array_dropzone_in').dropzone({
        // only accept elements matching this CSS selector
        accept: '.af2_array_draggable, .af2_array_add_draggable',
        overlap: 0.1,
      
        ondragenter: function (event) {
            if(jQuery(event.relatedTarget).hasClass('af2_array_draggable_no_border')) return;
            let dropzoneElement = event.target
            jQuery(dropzoneElement).addClass('af2_array_dropzone_droppable');
          },
          ondragleave: function (event) {
            if(jQuery(event.relatedTarget).hasClass('af2_array_draggable_no_border')) return;
            let dropzoneElement = event.target
            jQuery(dropzoneElement).removeClass('af2_array_dropzone_droppable');
          },
        ondrop: function (event) {
            let dropzoneElement = event.target;

            const target = event.relatedTarget;
            af2_drop_array_draggable_object( af2_drag_insertData, jQuery(dropzoneElement).data('arrayid'), target);

            af2_drag_insertData = null;


            // Sidebar
            jQuery('.af2_builder_editable_object.selected').removeClass('selected');

            const handler = jQuery('.af2_builder_sidebar.editSidebar');
    
            handler.one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', _ => {
                jQuery('.editSidebar .af2_builder_sidebar_content_wrapper').html();
            });
            handler.addClass('hide');
            jQuery('.af2_builder_content').removeClass('no_margin');


            jQuery(dropzoneElement).removeClass('af2_array_dropzone_droppable');
        },
      });
});

function af2_setInsertData(insertData) {
    af2_drag_insertData = insertData;
}

function dragMoveListener (event, throwEvent) {
    const target = event.target;
    // keep the dragged position in the data-x/data-y attributes
    const x = (parseFloat(target.getAttribute('data-x')) || 0) + event.dx;
    const y = (parseFloat(target.getAttribute('data-y')) || 0) + event.dy;
  
    const x_ = x + af2_drag_offsetX;
    const y_ = y + af2_drag_offsetY;

    // translate the element
    target.style.transform = 'translate(' + x_ + 'px, ' + y_ + 'px)';
  
    // update the posiion attributes
    target.setAttribute('data-x', x);
    target.setAttribute('data-y', y);

    target.setAttribute('data-translationx', x_);
    target.setAttribute('data-translationy', y_);

    if(throwEvent) af2_active_dragging(event.target);
}