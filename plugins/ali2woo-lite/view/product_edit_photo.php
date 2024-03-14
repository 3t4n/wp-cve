<div id="a2wl-edit-image" class="a2wl-modal-wrapper" style="position: relative; display: none;">
    <div tabindex="0" class="a2wl-modal wp-core-ui">
        <button type="button" class="a2wl-modal-close"><span class="a2wl-modal-icon"></span></button>
        <div class="a2wl-modal-title">
            <div class="actions">
                <a href="#" id="btn-clear-objects"><span class="dashicons dashicons-image-rotate"></span></a>
                <a href="#" id="btn-undo"><span class="dashicons dashicons-undo"></span></a>
                <a href="#" id="btn-redo"><span class="dashicons dashicons-redo"></span></a>
            </div>
        </div>
        <div class="a2wl-modal-content">
            <div class="a2wl-edit-photo-loader">
                <div><span class="spinner"></span><span class="message"><?php _e('Loading', 'ali2woo'); ?>...</span></div>
            </div>
            <div class="a2wl-edit-photo-container">
                <div class="tui-image-editor"></div>
                <div class="tui-image-editor-controls">
                    <div class='controls-content'>
                        <div class="sub-menu-container" id="crop-sub-menu">
                            
                            <div class="manual-crop-items">
                                <a href="#" class="manual-crop"><?php _e('Manual crop', 'ali2woo'); ?></a>    
                                <div class="actions" style="display:none"><a href="#" class="button-primary apply"><?php _e('Apply', 'ali2woo'); ?></a> <a href="#" class="cancel"><?php _e('Cancel', 'ali2woo'); ?></a></div>
                            </div>
                            <div class="crop-items">
                                <div class="crop-item"><a href="#" class="crop" data-type="original"><span class="crop-recr" style="padding-bottom: 80%;"></span><span class="name"><?php _e('original ratio', 'ali2woo'); ?></span></a></div>
                                <div class="crop-item"><a href="#" class="crop" data-type="1x1 "><span class="crop-recr" style="padding-bottom: 80%;"></span><span class="name">1x1</span></a></div>
                                <div class="crop-item"><a href="#" class="crop" data-type="5x4"><span class="crop-recr" style="padding-bottom: 60%;"></span><span class="name">5x4</span></a></div>
                                <div class="crop-item"><a href="#" class="crop" data-type="4x3"><span class="crop-recr" style="padding-bottom: 55%;"></span><span class="name">4x3</span></a></div>
                                <div class="crop-item"><a href="#" class="crop" data-type="3x2"><span class="crop-recr" style="padding-bottom: 46.67%;"></span><span class="name">3x2</span></a></div>
                                <div class="crop-item"><a href="#" class="crop" data-type="16x9"><span class="crop-recr" style="padding-bottom: 36.25%;"></span><span class="name">16x9</span></a></div>
                            </div>
                        </div>
                        <div class="sub-menu-container menu" id="draw-line-sub-menu">
                            <div class="block-title"><?php _e('Color', 'ali2woo'); ?>:</div>
                            <div class="block">
                                <div><input type="text" id="color-picker"/></div>
                                <div><a href="#" class="get-color"><span></span></a></div>
                            </div>

                            <div class="block-title"><?php _e('Size', 'ali2woo'); ?>:</div>
                            <div class="block">
                                <div style="width:100%"><input id="input-brush-width-range" type="range" min="1" max="50" value="10"></div>
                            </div>
                        </div>
                        <div class="sub-menu-container menu" id="filter-sub-menu">

                            <div class="block">
                                <div class="block-item input-wrapper">
                                    <span class="upload-icon"></span>
                                    <?php _e('Upload', 'ali2woo'); ?>
                                    <input type="file" accept="image/*" id="input-mask-image-file">
                                </div>
                                <?php foreach($srickers as $sricker):?>
                                    <div class="block-item"><a href="#" class="sticker"><img src="<?php echo $sricker ?>"/></a></div>
                                <?php endforeach;?>
                            </div>

                        </div>
                        
                        <div class="sub-menu-container menu" id="draw-text-sub-menu">
                            <div class="block-title"><?php _e('Color', 'ali2woo'); ?>:</div>
                            <div class="block">
                                <div><input type="text" id="text-color-picker"/></div>
                                <div><a href="#" class="get-color text"><span></span></a></div>
                            </div>

                            <div class="block-title"><?php _e('Size', 'ali2woo'); ?>:</div>
                            <div class="block">
                                <select id="input-text-size">
                                    <?php $default_size = 20; ?>
                                    <?php for($i=8;$i<=50;$i++):?>
                                    <option value="<?php echo $i;?>"<?php if($default_size===$i):?> selected<?php endif; ?>><?php echo $i;?></option>
                                    <?php endfor;?>
                                </select>
                                
                                <a href="#" class="btn-text-style" data-style-type="b"><span class="dashicons dashicons-editor-bold"></span></a>
                                <a href="#" class="btn-text-style" data-style-type="i"><span class="dashicons dashicons-editor-italic"></span></a>
                                <a href="#" class="btn-text-style" data-style-type="u"><span class="dashicons dashicons-editor-underline"></span></a>
                            </div>
                            
                        </div>

                        <div class="sub-menu-container menu" id="colorize-sub-menu">
                            <div class="block-title"><?php _e('Filters', 'ali2woo'); ?>:</div>
                            <div class="block">
                                <div class="a2wl-apply-filter" data-type="Grayscale">
                                    <label class="a2wl-apply-filter__label"><input class="a2wl-apply-filter__check" type="checkbox"> <?php _e('Grayscale', 'ali2woo'); ?></label>
                                </div>
                                <div class="a2wl-apply-filter" data-type="Invert">
                                    <label class="a2wl-apply-filter__label"><input class="a2wl-apply-filter__check" type="checkbox"> <?php _e('Invert', 'ali2woo'); ?></label>
                                </div>
                                <div class="a2wl-apply-filter" data-type="Sepia">
                                    <label class="a2wl-apply-filter__label"><input class="a2wl-apply-filter__check" type="checkbox"> <?php _e('Sepia', 'ali2woo'); ?></label>
                                </div>
                                <div class="a2wl-apply-filter" data-type="vintage">
                                    <label class="a2wl-apply-filter__label"><input class="a2wl-apply-filter__check" type="checkbox"> <?php _e('Sepia2', 'ali2woo'); ?></label>
                                </div>
                                <div class="a2wl-apply-filter" data-type="Sharpen">
                                    <label class="a2wl-apply-filter__label"><input class="a2wl-apply-filter__check" type="checkbox"> <?php _e('Sharpen', 'ali2woo'); ?></label>
                                </div>
                                <div class="a2wl-apply-filter" data-type="Emboss">
                                    <label class="a2wl-apply-filter__label"><input class="a2wl-apply-filter__check" type="checkbox"> <?php _e('Emboss', 'ali2woo'); ?></label>
                                </div>
                            </div>
                            <div class="block">
                                <div class="a2wl-apply-filter" data-type="Blur">
                                    <label class="a2wl-apply-filter__label"><input class="a2wl-apply-filter__check" type="checkbox"> <?php _e('Blur', 'ali2woo'); ?></label>
                                    <div class="a2wl-apply-filter__range-wrap">
                                        <input class="a2wl-apply-filter__range" type="range" min="0.1" max="1.0" step="0.1" value="0.1">
                                    </div>
                                </div>
                                <div class="a2wl-apply-filter" data-type="pixelate">
                                    <label class="a2wl-apply-filter__label"><input class="a2wl-apply-filter__check" type="checkbox"> <?php _e('Pixelate', 'ali2woo'); ?></label>
                                    <div class="a2wl-apply-filter__range-wrap">
                                        <input class="a2wl-apply-filter__range" type="range" min="1" max="50" value="10">
                                    </div>
                                </div>
                                <div class="a2wl-apply-filter" data-type="noise">
                                    <label class="a2wl-apply-filter__label"><input class="a2wl-apply-filter__check" type="checkbox"> <?php _e('Noise', 'ali2woo'); ?></label>
                                    <div class="a2wl-apply-filter__range-wrap">
                                        <input class="a2wl-apply-filter__range" type="range" min="1" max="500" value="50">
                                    </div>
                                </div>
                                <div class="a2wl-apply-filter" data-type="brightness">
                                    <label class="a2wl-apply-filter__label"><input class="a2wl-apply-filter__check" type="checkbox"> <?php _e('Brightness', 'ali2woo'); ?></label>
                                    <div class="a2wl-apply-filter__range-wrap">
                                        <input class="a2wl-apply-filter__range" type="range" min="-255" max="255" value="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="controls-menu">
                        <a href="#" id="btn-crop"><span class="dashicons dashicons-image-crop"></span></a>
                        <a href="#" id="btn-draw-line"><span class="dashicons dashicons-admin-customizer"></span></a>
                        <a href="#" id="btn-mask-filter"><span class="dashicons dashicons-format-image"></span></a>
                        <a href="#" id="btn-draw-text"><span class="dashicons dashicons-editor-textcolor"></span></a>
                        <a href="#" id="btn-draw-colorize"><span class="dashicons dashicons-admin-appearance"></span></a>
                    </div>
                </div>
            </div>            

        </div>
        <div class="a2wl-modal-toolbar">
            <span class="spinner"></span>
            <button type="button" class="button-primary save-image"><?php _e('Save', 'ali2woo'); ?></button>
            <button type="button" class="button cancel-image"><?php _e('Cancel', 'ali2woo'); ?></button>
        </div>
    </div>
    <div class="a2wl-modal-backdrop"></div>
</div>


