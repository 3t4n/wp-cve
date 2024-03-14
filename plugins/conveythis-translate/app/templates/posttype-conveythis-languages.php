<div id="posttype-conveythis-languages" class="posttypediv">
    <div id="tabs-panel-conveythis-endpoints" class="tabs-panel tabs-panel-active">
        <ul id="conveythis-endpoints-checklist" class="categorychecklist form-no-clear">

            <?php foreach( $languages as $index => $language ) : ?>
            <li>
                <label class="menu-item-title">
                    <input type="checkbox" class="menu-item-checkbox" name="menu-item[<?php echo  esc_attr( $index + 1 ); ?>][menu-item-object-id]" value="<?php echo  esc_attr( $index + 1 ); ?>" /> <?php echo  esc_html( $language['title_en'] ); ?>
                </label>
                <input type="hidden" class="menu-item-type" name="menu-item[<?php echo  esc_attr( $index + 1 ); ?>][menu-item-type]" value="custom" />
                <input type="hidden" class="menu-item-title" name="menu-item[<?php echo  esc_attr( $index + 1 ); ?>][menu-item-title]" value="[ConveyThis_<?php echo  esc_html( $language['title_en'] ); ?>]" />

                <input type="hidden" class="menu-item-classes" name="menu-item[<?php echo  esc_attr( $index + 1 ); ?>][menu-item-classes]" />
            </li>

            <?php endforeach; ?>

        </ul>
    </div>
    <p class="button-controls">
				<span class="list-controls">
					<a href="<?php echo  esc_url( admin_url( 'nav-menus.php?page-tab=all&selectall=1#posttype-conveythis-languages' ) ); ?>" class="select-all"><?php esc_html_e( 'Select all', 'conveythis-translate' ); ?></a>
				</span>
        <span class="add-to-menu">
					<button type="submit" class="button-secondary submit-add-to-menu right" value="<?php esc_attr_e( 'Add to menu', 'conveythis-translate' ); ?>" name="add-post-type-menu-item" id="submit-posttype-conveythis-languages"><?php esc_html_e( 'Add to menu', 'conveythis-translate' ); ?></button>
					<span class="spinner"></span>
				</span>
    </p>
</div>