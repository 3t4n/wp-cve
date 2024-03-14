<div class="lfd-body">
    <div class="lfd-header">
        <h1 class="wp-heading-inline"><?php _e('Layouts for Divi', LFD_TEXTDOMAIN); ?></h1>
    </div>
    <div id="lfd-wrap" class="lfd-wrap">
        <div class="lfd-header">
            <div class="lfd-title lfd-is-inline"><h2 class="lfd-title"><?php _e('Divi Template Kits:', LFD_TEXTDOMAIN); ?></h2></div>
            <div class="lfd-sync lfd-is-inline">
                <a href="javascript:void(0);" class="lfd-sync-btn"><?php _e('Sync Now', LFD_TEXTDOMAIN); ?></a>
            </div>
        </div>
        <?php
        $categories = Layouts_Divi_Remote::lfd_get_instance()->categories_list();

        if (!empty($categories['category']) && $categories != "") {
            ?>
            <div class="collection-bar">
                <h4><?php _e('Browse by Industry', LFD_TEXTDOMAIN); ?></h4>
                <ul class="collection-list">
                    <li><a class="lfd-category-filter active" data-filter="all" href="javascript:void(0)"><?php _e('All', LFD_TEXTDOMAIN); ?></a></li>
                    <?php
                    foreach ($categories['category'] as $cat) {
                        ?>
                        <li><a href="javascript:void(0);" class="lfd-category-filter" data-filter="<?php echo $cat['slug']; ?>" ><?php echo $cat['title']; ?></a></li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
            <?php
        }
        ?>

        <div class="lfd_wrapper">
            <?php
            $data = Layouts_Divi_Remote::lfd_get_instance()->templates_list();
            $i = 0;
            if (!empty($data['templates']) && $data !== "") {
                foreach ($data['templates'] as $key => $val) {
                    $categories = "";
                    foreach ($val['category'] as $ckey => $cval) {
                        $categories .= sanitize_title($cval) . " ";
                    }
                    ?>
                    <div class="lfd_box lfd_filter <?php echo $categories; ?>">
                        <div class="lfd_box_widget">
                            <div class="lfd-media">
                                <img src="<?php echo $val['thumbnail']; ?>" alt="screen 1">
                                <?php if ($val['is_premium'] == true) { ?>
                                    <span class="pro-btn"><?php echo _e('PRO', LFD_TEXTDOMAIN); ?></span>
                                <?php } else { ?>
                                    <span class="free-btn"><?php echo _e('FREE', LFD_TEXTDOMAIN); ?></span>
                                <?php } ?>
                            </div>
                            <div class="lfd-template-title"><?php _e($val['title'], LFD_TEXTDOMAIN); ?></div>
                            <div class="lfd-btn">
                                <a href="javascript:void(0);" data-url="<?php echo esc_url($val['url']); ?>" title="<?php _e('Preview', LFD_TEXTDOMAIN); ?>" class="btn pre-btn previewbtn"><?php _e('Preview', LFD_TEXTDOMAIN); ?></a>
                                <a href="javascript:void(0);" title="<?php _e('Install', LFD_TEXTDOMAIN); ?>" class="btn ins-btn installbtn"><?php _e('Install', LFD_TEXTDOMAIN); ?></a>
                            </div>
                        </div>
                    </div>

                    <!-- Preview popup div start -->
                    <div class="lfd-preview-popup" id="preview-in-<?php echo $i; ?>">
                        <div class="lfd-preview-container">
                            <div class="lfd-preview-header">
                                <div class="lfd-preview-title"><?php echo $val['title']; ?></div>
                                <?php if ($val['is_premium'] == true) { ?>
                                    <div class="lfd-buy">
                                        <p class="lfd-buy-msg"><?php _e('This template is premium version', LFD_TEXTDOMAIN); ?></p>
                                        <span class="lfd-buy-loader"></span>

                                        <a href="javascript:void(0);" class="btn ins-btn lfd-buy-btn" disabled data-template-id="<?php echo $val['id']; ?>" ><?php _e('Buy Now', LFD_TEXTDOMAIN); ?></a>
                                        <a href="javascript:void(0);" class="btn ins-btn lfd-buy-template" style="display:none" target="_blank"><?php _e('Edit Template', LFD_TEXTDOMAIN); ?></a>
                                    </div>
                                <?php } else { ?>
                                    <div class="lfd-import">
                                        <p class="lfd-msg"><?php _e('Import this template via one click', LFD_TEXTDOMAIN); ?></p>
                                        <span class="lfd-loader"></span>

                                        <a href="javascript:void(0);" class="btn ins-btn lfd-import-btn" disabled data-template-id="<?php echo $val['id']; ?>" ><?php _e('Import Template', LFD_TEXTDOMAIN); ?></a>
                                        <a href="#" class="btn ins-btn lfd-edit-template" style="display:none" target="_blank"><?php _e('Edit Template', LFD_TEXTDOMAIN); ?></a>
                                    </div>

                                    <span><?php _e('OR', LFD_TEXTDOMAIN); ?></span>

                                    <div class="lfd-import lfd-page-create">
                                        <p><?php _e('Create a new page from this template', LFD_TEXTDOMAIN); ?></p>
                                        <input type="text" class="lfd-page-name-<?php echo $val['id']; ?>" placeholder="Enter a Page Name" />
                                        <a href="javascript:void(0);" class="btn ins-btn lfd-create-page-btn" data-template-id="<?php echo $val['id']; ?>" ><?php _e('Create New Page', LFD_TEXTDOMAIN); ?></a>
                                    </div>

                                    <span class="lfd-loader-page"></span>

                                    <div class="lfd-import lfd-page-edit" style="display:none" >
                                        <p><?php _e('Your template is successfully imported!', LFD_TEXTDOMAIN); ?></p>
                                        <a href="javascript:void(0);" class="btn ins-btn lfd-edit-page" target="_blank" ><?php _e('Edit Page', LFD_TEXTDOMAIN); ?></a>
                                    </div>
                                    <div class="lfd-import lfd-page-error" style="display:none" >
                                        <p class="lfd-error"><?php _e('Something went wrong!', LFD_TEXTDOMAIN); ?></p>
                                    </div>
                                <?php } ?>
                                <span class="lfd-close-icon"></span>

                                <a href="<?php echo esc_url($val['url']); ?>" class="lfd-dashicons-link" title="<?php _e('Open Preview in New Tab', LFD_TEXTDOMAIN); ?>" rel="noopener noreferrer" target="_blank">
                                    <span class="lfd-dashicons"></span>
                                </a>
                            </div>
                            <iframe width="100%" height="100%" src=""></iframe>
                        </div>
                    </div>
                    <!-- Preview popup div end -->

                    <!-- Install popup div start -->
                    <div class="lfd-install-popup" id="content-in-<?php echo $i; ?>">
                        <div class="lfd-container">
                            <div class="lfd-install-header">
                                <div class="lfd-install-title"><?php echo $val['title']; ?></div>
                                <span class="lfd-close-icon"></span>
                            </div>
                            <div class="lfd-install-content">

                                <?php if ($val['is_premium'] == true) { ?>
                                    <p class="lfd-msg"><?php _e('This template is premium version', LFD_TEXTDOMAIN); ?></p>
                                    <div class="lfd-btn">
                                        <span class="lfd-loader"></span>
                                        <a href="javascript:void(0);" class="btn ins-btn lfd-buy-btn" data-template-id="<?php echo $val['id']; ?>" ><?php _e('Buy Now', LFD_TEXTDOMAIN); ?></a>
                                        <a href="javascript:void(0);" class="btn ins-btn lfd-buy-template" style="display:none" target="_blank"><?php _e('Edit Template', LFD_TEXTDOMAIN); ?></a>
                                    </div>

                                <?php } else { ?>

                                    <p class="lfd-msg"><?php _e('Import this template via one click', LFD_TEXTDOMAIN); ?></p>
                                    <div class="lfd-btn">
                                        <span class="lfd-loader"></span>
                                        <a href="javascript:void(0);" class="btn ins-btn lfd-import-btn" data-template-id="<?php echo $val['id']; ?>" ><?php _e('Import Template', LFD_TEXTDOMAIN); ?></a>
                                        <a href="javascript:void(0);" class="btn ins-btn lfd-edit-template" style="display:none" target="_blank"><?php _e('Edit Template', LFD_TEXTDOMAIN); ?></a>
                                    </div>

                                    <p class="lfd-horizontal"><?php _e('OR', LFD_TEXTDOMAIN); ?></p>

                                    <div class="lfd-page-create">
                                        <p><?php _e('Create a new page from this template', LFD_TEXTDOMAIN); ?></p>
                                        <input type="text" class="lfd-page-<?php echo $val['id']; ?>" placeholder="Enter a Page Name" />
                                        <div class="lfd-btn">
                                            <a href="javascript:void(0);" style="padding: 0;" class="btn pre-btn lfd-create-page-btn" data-name="crtbtn" data-template-id="<?php echo $val['id']; ?>" ><?php _e('Create New Page', LFD_TEXTDOMAIN); ?></a>
                                            <span class="lfd-loader-page"></span>
                                        </div>
                                    </div>
                                    <div class="lfd-create-div lfd-page-edit" style="display:none" >
                                        <p style="color: #000;"><?php _e('Your page is successfully imported!', LFD_TEXTDOMAIN); ?></p>
                                        <div class="lfd-btn">
                                            <a href="javascript:void(0);" class="btn pre-btn lfd-edit-page" target="_blank" ><?php _e('Edit Page', LFD_TEXTDOMAIN); ?></a>
                                        </div>
                                    </div>
                                    <div class="lfd-import lfd-page-error" style="display:none;" >
                                        <p class="lfd-error" style="color: #444;"><?php _e('Something went wrong!', LFD_TEXTDOMAIN); ?></p>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <!-- Install popup div end -->
                    <?php
                    $i++;
                }
            } else {
                echo $data['message'];
            }
            ?>
        </div>
    </div>
</div>
