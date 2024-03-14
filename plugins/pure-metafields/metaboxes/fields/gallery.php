<?php
/**
 * Gallery
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<?php if(isset($row_db_value)): ?>
<div class="tm-gallery-field">
    <input 
        type="hidden" 
        name="<?php echo esc_attr($id); ?>[]" 
        class="<?php echo esc_attr($id); ?> tm-gallery-value"
        value="<?php echo esc_html($row_db_value); ?>"/>
    <button class="tm-add-gallery" type="button">
        <span class="">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M13.4444 1H2.55556C1.69645 1 1 1.69645 1 2.55556V13.4444C1 14.3036 1.69645 15 2.55556 15H13.4444C14.3036 15 15 14.3036 15 13.4444V2.55556C15 1.69645 14.3036 1 13.4444 1Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M5.27799 6.44466C5.92233 6.44466 6.44466 5.92233 6.44466 5.27799C6.44466 4.63366 5.92233 4.11133 5.27799 4.11133C4.63366 4.11133 4.11133 4.63366 4.11133 5.27799C4.11133 5.92233 4.63366 6.44466 5.27799 6.44466Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M14.9991 10.3332L11.1102 6.44434L2.55469 14.9999" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </span>
        <span><?php echo esc_html__('Add Gallery', 'pure-metafields'); ?></span>
    </button>
    <div class="tm-gallery-container">
    <?php
    $images_ids = esc_html($row_db_value);
    if($row_db_value != ''): 
    $images_ids = explode(',', $images_ids);
    foreach($images_ids as $image_id):
        $image_src = wp_get_attachment_image_src($image_id, 'thumbnail');
        
    ?>
    <div class="tm-gallery-item" data-image_id="<?php echo esc_attr($image_id); ?>">
        <div class="tm-gallery-img">
            <img src="<?php echo esc_url($image_src[0]); ?>" alt=""/>
        </div>
        <div class="tm-gallery-img-actions">
            <a data-attachment-id="<?php echo esc_attr($image_id); ?>" href="#" class="tm-delete">
                <span class="dashicons dashicons-trash"></span>
            </a>
        </div>
    </div>
    <?php endforeach; endif; ?>
    </div>
</div>
<?php else: ?>
<div class="tm-gallery-field">
    <input 
        type="hidden" 
        name="<?php echo esc_attr($id); ?>" 
        id="<?php echo esc_attr($id); ?>"
        value="<?php echo esc_html(tpmeta_field($id)); ?>"/>
    <button id="<?php echo esc_attr($id); ?>-gallery" type="button">
        <span class="dashicons dashicons-format-gallery"></span>
        <span><?php echo esc_html__('Add Gallery', 'pure-metafields'); ?></span>
    </button>
    <div class="tm-gallery-container" id="<?php echo esc_attr($id); ?>-g-container">
    <?php 
    if(tpmeta_field($id) != ''): 
    $images_ids = tpmeta_field($id);
    $images_ids = explode(',', $images_ids);
    foreach($images_ids as $image_id):
        $image_src = wp_get_attachment_image_src($image_id, 'thumbnail');
        
    ?>
    <div class="tm-gallery-item" data-image_id="<?php echo esc_attr($image_id); ?>">
        <div class="tm-gallery-img">
            <img src="<?php echo esc_url($image_src[0]); ?>" alt=""/>
        </div>
        <div class="tm-gallery-actions">
            <a data-attachment-id="<?php echo esc_attr($image_id); ?>" href="#" class="tm-delete"><span class="dashicons dashicons-trash"></span></a>
        </div>
    </div>
    <?php endforeach; endif; ?>
    </div>
</div>
<!-- <script src="<?php echo esc_url(TPMETA_URL . '/metaboxes/js/dragula.min.js')?>"></script> -->
<script type="text/javascript" >
    ;(function($){
        "use strict";

        $( document ).ready(function(){
            var frame, editFrame;

            $('#<?php echo esc_attr($id); ?>-gallery').on('click', function(){
                if(frame){
                    frame.open()
                    return false;
                }

                frame = wp.media({
                    title:'Choose images for your gallery',
                    button:{
                        text:'Add Images'
                    },
                    multiple:true
                })

                frame.on('select', function(){
                    var attachments = frame.state().get('selection').toJSON();
                    var ids = $('#<?php echo esc_attr($id); ?>').val() != '' ? $('#<?php echo esc_attr($id); ?>').val().split(',') : [];
                    var attachmentURL;
                    
                    attachments.map(function(el, i){
                        ids = [...ids, el.id];
                        attachmentURL = el.sizes.thumbnail? el.sizes.thumbnail.url : el.sizes.full.url;
                        $('#<?php echo esc_attr($id); ?>-g-container').append(`
                        <div class="tm-gallery-item" data-image_id="${el.id}">
                            <div class="tm-gallery-img">
                                <img src="${attachmentURL}" alt=""/>
                            </div>
                            <div class="tm-gallery-actions">
                                <a data-attachment-id="${el.id}" href="#" class="tm-delete"><span class="dashicons dashicons-trash"></span></a>
                            </div>
                        </div>
                        `)
                    })
                    $('#<?php echo esc_attr($id); ?>').val(ids.join(','))


                    $('.tm-gallery-actions > a.tm-delete').on('click', function(e){
                        e.preventDefault();
                        var selected = $( e.target ).parent().attr( 'data-attachment-id' );
                        ids = ids.filter( id => id != selected )
                        $('#<?php echo esc_attr($id); ?>').val(ids.join(','))
                        $(e.target).parent().parent().parent().remove()
                    })

                    sortableGallery('<?php echo esc_attr($id); ?>');
                    
                })


                frame.on('open', function(){
                    
                })

                

                frame.open()
                return;
            })


            $('.tm-gallery-actions > a.tm-delete').on('click', function(e){
                e.preventDefault();
                var selected = $( e.target ).parent().attr( 'data-attachment-id' );
                var ids = $('#<?php echo esc_attr($id); ?>').val();
                ids = ids.split(',');
                ids = ids.filter( id => id != selected );
                $('#<?php echo esc_attr($id); ?>').val(ids.join(','));
                $(e.target).parent().parent().parent().remove();
            });
                
            function sortableGallery( gallery_id ){
                const galleryItems = document.querySelectorAll(".tm-gallery-container");
                const drake = dragula(Array.from(galleryItems),{
                    direction:'horizontal',
                    revertOnSpill:true,
                    removeOnSpill:true,
                });

                drake.on('dragend', function( e ){
                    
                    const rearrangedGallery = $(e).parent().children('.tm-gallery-item');
                    let ids = [];
                    rearrangedGallery.each( (index, el) => {
                        ids.push($(el).data('image_id'))
                    })
                    $(e).closest('.tm-gallery-field').find('input[type="hidden"]').val(ids.join(','))
                })
            }
            sortableGallery('<?php echo esc_attr($id); ?>');
        })
    })(jQuery);
</script>
<?php endif; ?>
