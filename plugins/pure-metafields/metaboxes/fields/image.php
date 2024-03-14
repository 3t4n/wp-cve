<?php
/**
 * Image
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<?php if(isset($row_db_value)): ?>
<div class="tm-image-field">
    <input 
        type="hidden" 
        name="<?php echo esc_attr($id); ?>[]" 
        class="<?php echo esc_attr($id); ?> tm-image-value"
        value="<?php echo esc_html($row_db_value); ?>"/>
    <button class="tm-add-image" type="button">
        <span class="">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M13.4444 1H2.55556C1.69645 1 1 1.69645 1 2.55556V13.4444C1 14.3036 1.69645 15 2.55556 15H13.4444C14.3036 15 15 14.3036 15 13.4444V2.55556C15 1.69645 14.3036 1 13.4444 1Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M5.27799 6.44466C5.92233 6.44466 6.44466 5.92233 6.44466 5.27799C6.44466 4.63366 5.92233 4.11133 5.27799 4.11133C4.63366 4.11133 4.11133 4.63366 4.11133 5.27799C4.11133 5.92233 4.63366 6.44466 5.27799 6.44466Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M14.9991 10.3332L11.1102 6.44434L2.55469 14.9999" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </span>
        <span><?php echo esc_html__('Add Image', 'pure-metafields'); ?></span>
    </button>
    <div class="tm-image-container">
        <?php
            $images_ids = esc_html($row_db_value);
            if($images_ids != ''):
            $images_ids = explode(',', $images_ids);
            foreach($images_ids as $image_id):
                $image_src = wp_get_attachment_image_src($image_id, 'thumbnail');
        ?>
        <div class="tm-gallery-item">
            <div class="tm-gallery-img">
                <img src="<?php echo esc_url($image_src[0]); ?>" alt=""/>
            </div>
            <div class="tm-image-actions">
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
    <button id="<?php echo esc_attr($id); ?>-image" type="button">
        <span class="dashicons dashicons-format-gallery"></span>
        <span><?php echo esc_html__('Add Image', 'pure-metafields'); ?></span>
    </button>
    <div class="tm-gallery-container" id="<?php echo esc_attr($id); ?>-g-container">
    <?php if(tpmeta_field($id) != ''): 
    $images_ids = tpmeta_field($id);
    $images_ids = explode(',', $images_ids);
    foreach($images_ids as $image_id):
        $image_src = wp_get_attachment_image_src($image_id, 'thumbnail');
        
    ?>
    <div class="tm-gallery-item">
        <div class="tm-gallery-img">
            <img src="<?php echo esc_url($image_src[0]); ?>" alt=""/>
        </div>
        <div class="tm-image-actions">
            <a data-attachment-id="<?php echo esc_attr($image_id); ?>" href="#" class="tm-delete"><span class="dashicons dashicons-trash"></span></a>
        </div>
    </div>
    <?php endforeach; endif; ?>
    </div>
</div>

<script type="text/javascript">
    (function($){
        $(document).ready(function(){
            var frame, editFrame;

            $('#<?php echo esc_attr($id); ?>-image').on('click', function(){
                if(frame){
                    frame.open()
                    return false;
                }

                frame = wp.media({
                    title:'Select an image',
                    button:{
                        text:'Add Image'
                    },
                    multiple:false
                })

                frame.on('select', function(){
                    var attachment = frame.state().get('selection').first().toJSON();
                    var attchmentURL = attachment.sizes.thumbnail? attachment.sizes.thumbnail.url : attachment.sizes.full.url;
                    
                    $('#<?php echo esc_attr($id); ?>-g-container').html(`
                    <div class="tm-gallery-item">
                        <div class="tm-gallery-img">
                            <img src="${attchmentURL}" alt=""/>
                        </div>
                        <div class="tm-image-actions">
                            <a data-attachment-id="${attachment.id}" href="#" class="tm-delete"><span class="dashicons dashicons-trash"></span></a>
                        </div>
                    </div>
                    `)
                    
                    $('#<?php echo esc_attr($id); ?>').val(attachment.id)


                    $('.tm-image-actions > a.tm-delete').on('click', function(e){
                        e.preventDefault();
                        var selected = $( e.target ).closest('.tm-gallery-field');
                        var input = selected.find('input[type="hidden"]');
                        var imageItem = selected.find('.tm-gallery-item');
                        input.val('');
                        imageItem.remove();
                    })
                    
                })


                frame.on('open', function(){
                    
                })


                frame.open()
                return;
            })


            $('.tm-image-actions > a.tm-delete').click(function(e){
                e.preventDefault();
                var selected = $( e.target ).closest('.tm-gallery-field');
                var input = selected.find('input[type="hidden"]');
                var imageItem = selected.find('.tm-gallery-item');
                input.val('');
                imageItem.remove();
            })
        })
    })(jQuery)
</script>
<?php endif; ?>
