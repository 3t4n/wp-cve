<?php

if ( !empty($DMS) && !empty($this) && $DMS instanceof DMS && $this instanceof DMS_Seo_Yoast ) {
    ?>
    <div class="dmsy">
		<?php 
    
    if ( !empty($data) ) {
        
        if ( empty($this->getOptionsPerDomain()) || !$DMS->dms_fs->can_use_premium_code__premium_only() ) {
            $popupExist = true;
            ?>
                    <div class="dmsy-upgrader">
                        <p style="padding: 0 16px">
							<?php 
            echo  sprintf( __( 'To customize Yoast meta content per domain, please %s' ), ' <a class="upgrade" href="' . $DMS->dms_fs->get_upgrade_url() . '">' . __( 'Upgrade', $DMS->plugin_name ) . '&#8594;</a>' ) ;
            ?>
                        </p>
                    </div>
					<?php 
        }
        
        ?>

			<?php 
        foreach ( $data as $key => $item ) {
            $hostAndPath = $item['host_path'];
            $meta_data = $item['meta_data'];
            ?>
                <div class="dmsy-accordion closed <?php 
            echo  ( !empty($popupExist) ? 'popup-behind' : '' ) ;
            ?>">
                    <div class="dmsy-accordion-header">
                        <button class="dmsy-accordion-toggle">
                            <span><?php 
            echo  esc_html( $hostAndPath ) ;
            ?></span>
                            <svg role="img" focusable="false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M7.41,8.59L12,13.17l4.59-4.58L18,10l-6,6l-6-6L7.41,8.59z"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="dmsy-accordion-body">
                        <div class="dmsy-accordion-body-in">
                            <div class="dmsy-tabs">
                                <ul role="tablist" class="dmsy-tabs-in" aria-label="Yoast SEO">
                                    <!-- add 'active' to li for active tab-->
                                    <li role="presentation" class="dmsy-tabs-item active">
                                        <a role="tab" href="#dmsy-seo-<?php 
            echo  esc_attr( $key ) ;
            ?>"
                                           aria-selected="false" tabindex="-1">
                                            <span><?php 
            echo  __( 'SEO', $DMS->plugin_name ) ;
            ?></span>
                                        </a>
                                    </li>
                                    <li role="presentation" class="dmsy-tabs-item">
                                        <a role="tab" href="#dmsy-social-<?php 
            echo  esc_attr( $key ) ;
            ?>" aria-selected="false" tabindex="-1">
                                            <span class="dmsy-tabs-icon dashicons dashicons-share"></span>
                                            <span><?php 
            echo  __( 'Social', $DMS->plugin_name ) ;
            ?></span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <!-- add 'active' to tab for active tab-->
                            <div role="tabpanel" id="dmsy-seo-<?php 
            echo  esc_attr( $key ) ;
            ?>" aria-labelledby="dmsy-tabs" tabindex="0" class="dmsy-tabs-body active">
                                <div class="dmsy-tabs-row">
                                    <label class="dmsy-tabs-input-holder">
                                        <span class="dmsy-tabs-input-label"><?php 
            echo  __( 'Title', $DMS->plugin_name ) ;
            ?></span>
                                        <input class="dmsy-tabs-input"
                                               name="<?php 
            echo  DMS_Seo_Yoast::$form_prefix ;
            ?>title<?php 
            echo  DMS_Seo_Yoast::$domain_separator . esc_attr( $hostAndPath ) ;
            ?>"
                                               type="text"
                                               value="<?php 
            echo  esc_attr( $meta_data['title'] ?? '' ) ;
            ?>"
                                               placeholder="Seo title">
                                    </label>
                                </div>
                                <div class="dmsy-tabs-row">
                                    <label class="dmsy-tabs-input-holder">
                                        <span class="dmsy-tabs-input-label"><?php 
            echo  __( 'Description', $DMS->plugin_name ) ;
            ?></span>
                                        <input class="dmsy-tabs-input"
                                               name="<?php 
            echo  DMS_Seo_Yoast::$form_prefix ;
            ?>description<?php 
            echo  DMS_Seo_Yoast::$domain_separator . esc_attr( $hostAndPath ) ;
            ?>"
                                               type="text"
                                               value="<?php 
            echo  esc_attr( $meta_data['description'] ?? '' ) ;
            ?>"
                                               placeholder="Seo description">
                                    </label>
                                </div>
                                <div class="dmsy-tabs-row">
                                    <label class="dmsy-tabs-input-holder">
                                        <span class="dmsy-tabs-input-label"><?php 
            echo  __( 'Keywords', $DMS->plugin_name ) ;
            ?></span>
                                        <input class="dmsy-tabs-input"
                                               name="<?php 
            echo  DMS_Seo_Yoast::$form_prefix ;
            ?>keywords<?php 
            echo  DMS_Seo_Yoast::$domain_separator . esc_attr( $hostAndPath ) ;
            ?>"
                                               type="text"
                                               value="<?php 
            echo  esc_attr( $meta_data['keywords'] ?? '' ) ;
            ?>"
                                               placeholder="Focus key-phrase">
                                    </label>
                                </div>
                            </div>
                            <div role="tabpanel" id="dmsy-social-<?php 
            echo  esc_attr( $key ) ;
            ?>" aria-labelledby="dmsy-tabs" tabindex="0" class="dmsy-tabs-body">
                                <div class="dmsy-tabs-group">
                                    <div class="dmsy-tabs-row">
                                        <div class="dmsy-tabs-upload">
                                            <span class="dmsy-tabs-input-label"><?php 
            echo  __( 'Social Image', $DMS->plugin_name ) ;
            ?></span>
                                            <button class="dmsy-tabs-upload-button" type="button"><?php 
            echo  ( !empty($meta_data['opengraph-image']) ? __( 'Replace image', $DMS->plugin_name ) : __( 'Select image', $DMS->plugin_name ) ) ;
            ?></button>
                                            <input class="dmsy-tabs-upload-image-id" type="hidden"
                                                   name="<?php 
            echo  DMS_Seo_Yoast::$form_prefix ;
            ?>opengraph-image-id<?php 
            echo  DMS_Seo_Yoast::$domain_separator . esc_attr( $hostAndPath ) ;
            ?>"
                                                   value="<?php 
            echo  esc_attr( $meta_data['opengraph-image-id'] ?? '' ) ;
            ?>">
                                            <input class="dmsy-tabs-upload-image-url" type="hidden"
                                                   name="<?php 
            echo  DMS_Seo_Yoast::$form_prefix ;
            ?>opengraph-image<?php 
            echo  DMS_Seo_Yoast::$domain_separator . esc_attr( $hostAndPath ) ;
            ?>"
                                                   value="<?php 
            echo  esc_attr( $meta_data['opengraph-image'] ?? '' ) ;
            ?>">
                                            <a class="dmsy-tabs-upload-image-remove" href="#" style="color: red"><?php 
            echo  __( 'Remove image' ) ;
            ?></a>
											<?php 
            
            if ( !empty($meta_data['opengraph-image']) ) {
                ?>
                                                <img class="dmsy-tabs-upload-image" src="<?php 
                echo  esc_url( $meta_data['opengraph-image'] ) ;
                ?>">
											<?php 
            }
            
            ?>
                                        </div>
                                        <label class="dmsy-tabs-input-holder">
                                            <span class="dmsy-tabs-input-label"><?php 
            echo  __( 'Social Title', $DMS->plugin_name ) ;
            ?></span>
                                            <input class="dmsy-tabs-input"
                                                   name="<?php 
            echo  DMS_Seo_Yoast::$form_prefix ;
            ?>opengraph-title<?php 
            echo  DMS_Seo_Yoast::$domain_separator . esc_attr( $hostAndPath ) ;
            ?>"
                                                   type="text"
                                                   value="<?php 
            echo  esc_attr( $meta_data['opengraph-title'] ?? '' ) ;
            ?>"
                                                   placeholder="<?php 
            echo  __( 'Social title', $DMS->plugin_name ) ;
            ?>">
                                        </label>
                                    </div>
                                    <div class="dmsy-tabs-row">
                                        <label class="dmsy-tabs-input-holder">
                                            <span class="dmsy-tabs-input-label"><?php 
            echo  __( 'Social Description', $DMS->plugin_name ) ;
            ?></span>
                                            <input class="dmsy-tabs-input"
                                                   name="<?php 
            echo  DMS_Seo_Yoast::$form_prefix ;
            ?>opengraph-description<?php 
            echo  DMS_Seo_Yoast::$domain_separator . esc_attr( $hostAndPath ) ;
            ?>"
                                                   type="text"
                                                   value="<?php 
            echo  esc_attr( $meta_data['opengraph-description'] ?? '' ) ;
            ?>"
                                                   placeholder="<?php 
            echo  __( 'Social description', $DMS->plugin_name ) ;
            ?>">
                                        </label>
                                    </div>
                                </div>
                                <div class="dmsy-tabs-group">
                                    <div class="dmsy-tabs-row">
                                        <div class="dmsy-tabs-upload">
                                            <span class="dmsy-tabs-input-label"><?php 
            echo  __( 'Twitter Image', $DMS->plugin_name ) ;
            ?></span>
                                            <button class="dmsy-tabs-upload-button" type="button"><?php 
            echo  ( !empty($meta_data['twitter-image']) ? __( 'Replace image', $DMS->plugin_name ) : __( 'Select image', $DMS->plugin_name ) ) ;
            ?></button>
                                            <input class="dmsy-tabs-upload-image-id"
                                                   type="hidden"
                                                   name="<?php 
            echo  DMS_Seo_Yoast::$form_prefix ;
            ?>twitter-image-id<?php 
            echo  DMS_Seo_Yoast::$domain_separator . esc_attr( $hostAndPath ) ;
            ?>"
                                                   value="<?php 
            echo  esc_attr( $meta_data['twitter-image-id'] ?? '' ) ;
            ?>">
                                            <input class="dmsy-tabs-upload-image-url"
                                                   type="hidden"
                                                   name="<?php 
            echo  DMS_Seo_Yoast::$form_prefix ;
            ?>twitter-image<?php 
            echo  DMS_Seo_Yoast::$domain_separator . esc_attr( $hostAndPath ) ;
            ?>"
                                                   value="<?php 
            echo  esc_attr( $meta_data['twitter-image'] ?? '' ) ;
            ?>">
                                            <a class="dmsy-tabs-upload-image-remove" href="#" style="color: red"><?php 
            echo  __( 'Remove image' ) ;
            ?></a>
											<?php 
            
            if ( !empty($meta_data['twitter-image']) ) {
                ?>
                                                <img class="dmsy-tabs-upload-image" src="<?php 
                echo  esc_url( $meta_data['twitter-image'] ) ;
                ?>">
											<?php 
            }
            
            ?>
                                        </div>
                                        <label class="dmsy-tabs-input-holder">
                                            <span class="dmsy-tabs-input-label"><?php 
            echo  __( 'Twitter Title', $DMS->plugin_name ) ;
            ?></span>
                                            <input class="dmsy-tabs-input"
                                                   name="<?php 
            echo  DMS_Seo_Yoast::$form_prefix ;
            ?>twitter-title<?php 
            echo  DMS_Seo_Yoast::$domain_separator . esc_attr( $hostAndPath ) ;
            ?>"
                                                   type="text"
                                                   value="<?php 
            echo  esc_attr( $meta_data['twitter-title'] ?? '' ) ;
            ?>"
                                                   placeholder="<?php 
            echo  __( 'Twitter title', $DMS->plugin_name ) ;
            ?>">
                                        </label>
                                    </div>
                                    <div class="dmsy-tabs-row">
                                        <label class="dmsy-tabs-input-holder">
                                            <span class="dmsy-tabs-input-label"><?php 
            echo  __( 'Twitter Description', $DMS->plugin_name ) ;
            ?></span>
                                            <input class="dmsy-tabs-input"
                                                   name="<?php 
            echo  DMS_Seo_Yoast::$form_prefix ;
            ?>twitter-description<?php 
            echo  DMS_Seo_Yoast::$domain_separator . esc_attr( $hostAndPath ) ;
            ?>"
                                                   type="text"
                                                   value="<?php 
            echo  esc_attr( $meta_data['twitter-description'] ?? '' ) ;
            ?>"
                                                   placeholder="<?php 
            echo  __( 'Twitter description', $DMS->plugin_name ) ;
            ?>">
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
			<?php 
        }
        ?>

			<?php 
    } else {
        $popupExist = true;
        ?>
                <div class="dmsy-upgrader">
                    <p style="padding: 0 16px">
						<?php 
        echo  sprintf( __( 'To customize Yoast meta content per domain, please %s' ), ' <a class="upgrade" href="' . $DMS->dms_fs->get_upgrade_url() . '">' . __( 'Upgrade', $DMS->plugin_name ) . '&#8594;</a>' ) ;
        ?>
                    </p>
                </div>
				<?php 
        ?>
            <div class="dmsy-accordion <?php 
        echo  ( !empty($popupExist) ? 'popup-behind' : '' ) ;
        ?>">
                <div class="dmsy-accordion-header">
                    <button class="dmsy-accordion-toggle">
                        <span></span>
                        <svg role="img" focusable="false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M7.41,8.59L12,13.17l4.59-4.58L18,10l-6,6l-6-6L7.41,8.59z"></path>
                        </svg>
                    </button>
                </div>
                <div class="dmsy-accordion-body">
                    <div class="dmsy-accordion-body-in">
                        <div class="dmsy-tabs">
                            <ul role="tablist" class="dmsy-tabs-in" aria-label="Yoast SEO">
                                <!-- add 'active' to li for active tab-->
                                <li role="presentation" class="dmsy-tabs-item active">
                                    <a role="tab" href="#dmsy-seo-0"
                                       aria-selected="false" tabindex="-1">
                                        <span><?php 
        echo  __( 'SEO', $DMS->plugin_name ) ;
        ?></span>
                                    </a>
                                </li>
                                <li role="presentation" class="dmsy-tabs-item">
                                    <a role="tab" href="#dmsy-social-0" aria-selected="false" tabindex="-1">
                                        <span class="dmsy-tabs-icon dashicons dashicons-share"></span>
                                        <span><?php 
        echo  __( 'Social', $DMS->plugin_name ) ;
        ?></span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- add 'active' to tab for active tab-->
                        <div role="tabpanel" id="dmsy-seo-0" aria-labelledby="dmsy-tabs" tabindex="0" class="dmsy-tabs-body active">
                            <div class="dmsy-tabs-row">
                                <label class="dmsy-tabs-input-holder">
                                    <span class="dmsy-tabs-input-label"><?php 
        echo  __( 'Title', $DMS->plugin_name ) ;
        ?></span>
                                    <input class="dmsy-tabs-input"
                                           name="<?php 
        echo  DMS_Seo_Yoast::$form_prefix ;
        ?>title"
                                           type="text"
                                           value=""
                                           placeholder="Seo title">
                                </label>
                            </div>
                            <div class="dmsy-tabs-row">
                                <label class="dmsy-tabs-input-holder">
                                    <span class="dmsy-tabs-input-label"><?php 
        echo  __( 'Description', $DMS->plugin_name ) ;
        ?></span>
                                    <input class="dmsy-tabs-input"
                                           name="<?php 
        echo  DMS_Seo_Yoast::$form_prefix ;
        ?>description"
                                           type="text"
                                           value=""
                                           placeholder="Seo description">
                                </label>
                            </div>
                            <div class="dmsy-tabs-row">
                                <label class="dmsy-tabs-input-holder">
                                    <span class="dmsy-tabs-input-label"><?php 
        echo  __( 'Keywords', $DMS->plugin_name ) ;
        ?></span>
                                    <input class="dmsy-tabs-input"
                                           name="<?php 
        echo  DMS_Seo_Yoast::$form_prefix ;
        ?>keywords"
                                           type="text"
                                           value=""
                                           placeholder="Focus key-phrase">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
			<?php 
    }
    
    ?>
    </div>
	<?php 
}
