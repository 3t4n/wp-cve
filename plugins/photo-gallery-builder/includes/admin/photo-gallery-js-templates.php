<script type="text/html" id="tmpl-photo-gallery-image">
    <div class="photo-gallery-single-image-content {{data.orientation}}" <# if ( data.full != '' ) { #> style="background-image:url({{ data.thumbnail }})" <# } #> >
        <?php do_action( 'photo_gallery_admin_image_start' ) ?>
        <# if ( data.thumbnail != '' ) { #>
            <img src="{{ data.thumbnail }}">
        <# } #>
        <div class="actions">
            <?php do_action( 'photo_gallery_admin_image_before_actions' ) ?>
            <a href="#" class="photo-gallery-edit-image" title="<?php esc_attr_e( 'Edit Image', 'photo-gallery-builder' ) ?>"><span class="dashicons dashicons-edit"></span></a>
            <?php do_action( 'photo_gallery_admin_image_after_actions' ) ?>
            <a href="#" class="photo-gallery-delete-image" title="<?php esc_attr_e( 'Delete Image', 'photo-gallery-builder' ) ?>"><span class="dashicons dashicons-trash"></span></a>
        </div>
        <div class="segrip ui-resizable-handle ui-resizable-se"></div>
        <?php do_action( 'photo_gallery_admin_image_end' ) ?>
    </div>
</script>

<script type="text/html" id="tmpl-photo-gallery-image-editor">
    <div class="edit-media-header">
        <button class="left dashicons"><span class="screen-reader-text"><?php esc_html_e( 'Edit previous media item', 'photo-gallery-builder' ); ?></span></button>
        <button class="right dashicons"><span class="screen-reader-text"><?php esc_html_e( 'Edit next media item', 'photo-gallery-builder' ); ?></span></button>
    </div>
    <div class="media-frame-title">
        <h1><?php esc_html_e( 'Edit Metadata', 'photo-gallery-builder' ); ?></h1>
    </div>
    <div class="media-frame-content">
        <div class="attachment-details save-ready">
            <!-- Left -->
            <div class="attachment-media-view portrait">
                <div class="thumbnail thumbnail-image">
                    <img class="details-image" src="{{ data.full }}" draggable="false" />
                </div>
            </div>
            
            <!-- Right -->
            <div class="attachment-info">
                <!-- Settings -->
                <div class="settings">
                    <!-- Attachment ID -->
                    <input type="hidden" name="id" value="{{ data.id }}" />
                    
                    <!-- Image Title -->
                    <label class="setting">
                        <span class="name"><?php esc_html_e( 'Title', 'photo-gallery-builder' ); ?></span>
                        <input type="text" name="title" value="{{ data.title }}" />
                        <div class="description">
                            <?php esc_html_e( 'Image titles can take any type of HTML.', 'photo-gallery-builder' ); ?>
                        </div>
                    </label>
                  
                    
                    <!-- Caption Text -->
                    <label class="setting">
                        <span class="name"><?php esc_html_e( 'Caption Text', 'photo-gallery-builder' ); ?></span>
                        <textarea name="description">{{ data.description }}</textarea>
                        <div class="description">
                        </div>
                    </label>

                                        
                    <!-- Link -->
                    <div class="setting">
                        <label class="">
                            <span class="name"><?php esc_html_e( 'URL', 'photo-gallery-builder' ); ?></span>
                            <input type="text" name="link" value="{{ data.link }}" />
                            <span class="description">
                                <?php esc_html_e( 'Enter a hyperlink if you wish to link this image to somewhere other than its attachment page. In order to use it you will need to select attachment page on Lightbox & Links setting under General.', 'photo-gallery-builder' ); ?>
                            </span>
                        </label>
                        <label>
                        <span class="description">
                            <input type="checkbox" name="target" value="1"<# if ( data.target == '1' ) { #> checked <# } #> />
                            <span><?php esc_html_e( 'Opens your image links in a new browser window / tab.', 'photo-gallery-builder' ); ?></span>
                        </span>
                        </label>
                    </div>
                    

                    <!-- Addons can populate the UI here -->
                    <div class="photo-gallery-addons"></div>
                </div>
                <!-- /.settings -->     
               
                <!-- Actions -->
                <div class="actions">
                    <a href="#" class="photo-wp-gallery-meta-submit button media-button button-large button-primary media-button-insert" title="<?php esc_attr_e( 'Save Metadata', 'photo-gallery-builder' ); ?>">
                        <?php esc_html_e( 'Save', 'photo-gallery-builder' ); ?>
                    </a>
                    <a href="#" class="photo-wp-gallery-meta-submit-close button media-button button-large media-button-insert" title="<?php esc_attr_e( 'Save & Close', 'photo-gallery-builder' ); ?>">
                        <?php esc_html_e( 'Save & Close', 'photo-gallery-builder' ); ?>
                    </a>

                    <!-- Save Spinner -->
                    <span class="settings-save-status">
                        <span class="spinner"></span>
                        <span class="saved"><?php esc_html_e( 'Saved.', 'photo-gallery-builder' ); ?></span>
                    </span>
                </div>
                <!-- /.actions -->
            </div>
        </div>
    </div>
</script>