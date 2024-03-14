<?php
global $bizcor_options;
$info_disable = get_theme_mod('bizcor_info_disable',$bizcor_options['bizcor_info_disable']);
$infos = bizcor_homepage_info_data();
if($info_disable==false){
?>
<section id="info-section" class="info-section">
    <div class="container">
        <div class="row">
            <div class="col-12 wow fadeInUp">
                <div class="row g-lg-4 g-4 info-wrapper">
                    <?php 
                    if(!empty($infos)) { 
                        foreach ($infos as $info) {
                            $icon = isset( $info['icon'] ) ?  $info['icon'] : '';
                            $title = isset( $info['title'] ) ?  $info['title'] : '';
                            $desc = isset( $info['desc'] ) ?  $info['desc'] : '';
                    ?>
                    <div class="col-lg-4 col-md-6 col-12">
                        <aside class="widget widget-contact">
                            <div class="contact-area">
                                <div class="contact-icon">
                                    <div class="contact-corn">
                                        <i class="<?php echo esc_attr( $icon ); ?>"></i>
                                    </div>
                                </div>
                                <div class="contact-info">
                                    <h5 class="title">
                                        <?php echo esc_html($title); ?>
                                    </h5>
                                    <p class="text"><?php echo esc_html($desc); ?></p>
                                </div>
                            </div>
                        </aside>
                    </div>
                    <?php } } ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php } ?>