<script type="text/html" id="tmpl-ras-image">
    <div class="ras-single-image-content {{data.orientation}}" <# if ( data.full != '' ) { #> style="background-image:url({{ data.thumbnail }})" <# } #> >
        <?php do_action( 'accordion_admin_slider_image_start' ) ?>
        <# if ( data.thumbnail != '' ) { #>
            <img src="{{ data.thumbnail }}">
        <# } #>
        <div class="actions">
            <?php do_action( 'ras_admin_slider_image_before_actions' ) ?>
            <a href="#" class="ras-edit-image" title="<?php esc_attr_e( 'Edit Image', 'responsive-accordion-slider' ) ?>"><span class="dashicons dashicons-edit"></span></a>
            <?php do_action( 'ras_admin_slider_image_after_actions' ) ?>
            <a href="#" class="ras-delete-image" title="<?php esc_attr_e( 'Delete Image', 'responsive-accordion-slider' ) ?>"><span class="dashicons dashicons-trash"></span></a>
        </div>
        <div class="segrip ui-resizable-handle ui-resizable-se"></div>
        <?php do_action( 'accordion_admin_slider_image_end' ) ?>
    </div>
</script>

<script type="text/html" id="tmpl-ras-image-editor">
    <div class="edit-media-header">
        <button class="left dashicons"><span class="screen-reader-text"><?php esc_html_e( 'Edit previous media item', 'responsive-accordion-slider' ); ?></span></button>
        <button class="right dashicons"><span class="screen-reader-text"><?php esc_html_e( 'Edit next media item', 'responsive-accordion-slider' ); ?></span></button>
    </div>
    <div class="media-frame-title">
        <h1><?php esc_html_e( 'Edit Metadata', 'responsive-accordion-slider' ); ?></h1>
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
                        <span class="name"><?php esc_html_e( 'Title', 'responsive-accordion-slider' ); ?></span>
                        <input type="text" name="title" value="{{ data.title }}" />
                        <div class="description">
                            <?php esc_html_e( 'Image titles can take any type of HTML.', 'responsive-accordion-slider' ); ?>
                        </div>
                    </label>
                  
                    
                    <!-- Caption Text -->
                    <label class="setting">
                        <span class="name"><?php esc_html_e( 'Caption Text', 'responsive-accordion-slider' ); ?></span>
                        <textarea name="description">{{ data.description }}</textarea>
                        <div class="description">
                        </div>
                    </label>

                     <!-- button Text -->
                    <label class="setting">
                        <span class="name"><?php esc_html_e( 'Button Text', 'responsive-accordion-slider' ); ?></span>
                        <input type="text" name="alt" value="{{ data.alt }}" />
                        <div class="description">
                            <?php esc_html_e( 'Button Text for external URL', 'responsive-accordion-slider' ); ?>
                        </div>
                    </label>

                    <!-- Link -->
                    <div class="setting">
                        <label class="">
                            <span class="name"><?php esc_html_e( 'Button URL', 'responsive-accordion-slider' ); ?></span>
                            <input type="text" name="link" value="{{ data.link }}" />
                          
                            <span class="description">
                                <?php esc_html_e( 'External URL for Button' ); ?>
                            </span>
                        </label>
                        <label style="display:flex;">
                        <span class="description">
                            <input type="checkbox" name="target" value="1"<# if ( data.target == '1' ) { #> checked <# } #> />
                            <span><?php esc_html_e( 'Opens Button links in a new browser window/tab.', 'responsive-accordion-slider' ); ?></span>
                        </span>
                        </label>
                    </div>

                    <!-- Alignment -->
                    <!-- <div class="setting">
                        <span class="name"><php esc_html_e( 'Alignment', 'responsive-accordion-slider' ); ?></span>
                        <select name="halign" class="inline-input">
                            <option <# if ( 'left' == data.halign ) { #> selected <# } #>><php esc_html_e( 'left', 'responsive-accordion-slider' ); ?></option>
                            <option <# if ( 'center' == data.halign ) { #> selected <# } #>><php esc_html_e( 'center', 'responsive-accordion-slider' ); ?></option>
                            <option <# if ( 'right' == data.halign ) { #> selected <# } #>><php esc_html_e( 'right', 'responsive-accordion-slider' ); ?></option>
                        </select>
                        <select name="valign" class="inline-input">
                            <option <# if ( 'top' == data.valign ) { #> selected <# } #>><php esc_html_e( 'top', 'responsive-accordion-slider' ); ?></option>
                            <option <# if ( 'middle' == data.valign ) { #> selected <# } #>><php esc_html_e( 'middle', 'responsive-accordion-slider' ); ?></option>
                            <option <# if ( 'bottom' == data.valign ) { #> selected <# } #>><php esc_html_e( 'bottom', 'responsive-accordion-slider' ); ?></option>
                        </select>
                    </div> -->
           

                    <!-- Addons can populate the UI here -->
                    <div class="ras-addons"></div>
                </div>
                <!-- /.settings -->     
               
                <!-- Actions -->
                <div class="actions">
                    <a href="#" class="ras-gallery-meta-submit button media-button button-large button-primary media-button-insert" title="<?php esc_attr_e( 'Save Metadata', 'responsive-accordion-slider' ); ?>">
                        <?php esc_html_e( 'Save', 'responsive-accordion-slider' ); ?>
                    </a>
                    <a href="#" class="ras-gallery-meta-submit-close button media-button button-large media-button-insert" title="<?php esc_attr_e( 'Save & Close', 'responsive-accordion-slider' ); ?>">
                        <?php esc_html_e( 'Save & Close', 'responsive-accordion-slider' ); ?>
                    </a>

                    <!-- Save Spinner -->
                    <span class="settings-save-status">
                        <span class="spinner"></span>
                        <span class="saved"><?php esc_html_e( 'Saved.', 'responsive-accordion-slider' ); ?></span>
                    </span>
                </div>
                <!-- /.actions -->
            </div>
        </div>
    </div>
</script>