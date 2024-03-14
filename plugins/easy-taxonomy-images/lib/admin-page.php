<div class="wrap">
	<h2 class="eti-page-heading"><?php echo __('Easy Taxonomy Images', 'hb'); ?></h2>

	<form method="post" action="<?php echo admin_url('admin.php?page=eti_easy_tax_images'); ?>">
	
		<?php settings_fields( 'easy-tax-images-settings' ); ?>
		
		<div class="form-table">

			<div class="eti-field-wrap">
				<div class="eti-single-field">
                    <h3>
                        <?php echo __('Disable Featured Images for Following Taxonomies ', 'eti'); ?>
                    </h3>
					<?php
						$options = get_option('eti_options');
						$disabled_taxonomies = array('nav_menu', 'link_category', 'post_format');
						foreach ( get_taxonomies() as $tax) : 
							if (in_array($tax, $disabled_taxonomies)) 
								continue; 
						?>
							<input 
								type="checkbox"
                                class="css-checkbox"
                                id="css-checkbox-featured<?php echo $tax ?>"
                                name="eti_options[excluded_taxonomies_featured][<?php echo $tax ?>]"
								value="<?php echo $tax ?>" 
								<?php checked(isset($options['excluded_taxonomies_featured'][$tax])); ?>
							>
                            <label class="css-label" for="css-checkbox-featured<?php echo $tax ?>">
                                <?php echo $tax ?>
                            </label>
						<?php endforeach;
					?>
				</div>

                <div class="eti-single-field">
                    <h3>
                        <?php echo __('Disable Cover Images for Following Taxonomies ', 'eti'); ?>
                    </h3>
                    <?php
                    $options = get_option('eti_options');
                    $disabled_taxonomies = array('nav_menu', 'link_category', 'post_format');
                    foreach ( get_taxonomies() as $tax) :
                        if (in_array($tax, $disabled_taxonomies))
                            continue;
                        ?>
                        <input
                            type="checkbox"
                            class="css-checkbox"
                            id="css-checkbox-cover<?php echo $tax ?>"
                            name="eti_options[excluded_taxonomies_cover][<?php echo $tax ?>]"
                            value="<?php echo $tax ?>"
                            <?php checked(isset($options['excluded_taxonomies_cover'][$tax])); ?>
                            >
                        <label class="css-label" for="css-checkbox-cover<?php echo $tax ?>">
                            <?php echo $tax ?>
                        </label>
                    <?php endforeach;
                    ?>
                </div>

                <div class="eti-single-field eti-image-wrap">
                    <h3>
                        <?php
                            _e('Default Feature Image Placeholder', 'eti');
                        ?>
                    </h3>
                    <img class="eti_image" src="<?php echo isset($options['default_featured_image']) ? $options['default_featured_image'] : ''  ?>"/><br/>
                    <input type="text" class="eti_image_url" name="eti_options[default_featured_image]" id="default_featured_image" value="<?php echo isset($options['default_featured_image']) ? $options['default_featured_image'] : ''  ?>" />
                    <br/>
                    <button class="eti_upload_image button">
                        <?php
                        _e('Add Default Featured Image', 'eti');
                        ?>
                    </button>
                </div>

                <div class="eti-single-field eti-image-wrap">
                    <h3>
                        <?php
                        _e('Default Cover Image Placeholder', 'eti');
                        ?>
                    </h3>
                    <img class="eti_image" src="<?php echo isset($options['default_cover_image']) ? $options['default_cover_image'] : ''  ?>"/><br/>
                    <input type="text" class="eti_image_url" name="eti_options[default_cover_image]" id="default_cover_image" value="<?php echo isset($options['default_cover_image']) ? $options['default_cover_image'] : ''  ?>" />
                    <br/>
                    <button class="eti_upload_image button">
                        <?php
                        _e('Add Default Cover Image', 'eti');
                        ?>
                    </button>
                </div>
			</div>

		</div>
		<div class="eti-field-wrap">
			<?php submit_button('Save'); ?>
		</div>
	</form>
</div>
