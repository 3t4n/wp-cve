<div class="off_canvars_overlay">
</div>
<div class="offcanvas_menu">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="offcanvas_menu_wrapper">
                    <div class="offcanvas_menu_wrapper-shell">
                        <div class="canvas_close">
                            <a href="javascript:void(0)"><i class="fa fa-times"></i></a>
                        </div>
                        <div class="bar_open_close">
                           <span>
                               <?php \Elementor\Icons_Manager::render_icon( $settings[ 'offcanvas_menu_icon' ], [ 'aria-hidden' => 'true' ] );  ?>
                                <?php echo esc_html( $settings[ 'offcanvas_text' ] ); ?>
                            </span>
                        </div>
                       <div class="element-ready-ele-template-content-wrapper"> 
                            <?php echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $settings[ 'offcanvas_template_id' ] ); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
