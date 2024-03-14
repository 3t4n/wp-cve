<?php 
// footer above section
add_action( 'Industri_Footer_Section',  'industri_above_footer' );
function industri_above_footer(){
    $default = amigo_industri_default_settings();
    $office_top_contact = get_theme_mod( 'footer_top_items', amigo_industri_default_footer_above() );
    ?>
    <?php if ( ! empty( $office_top_contact ) ) { $office_top_contact = json_decode( $office_top_contact ); ?>
        <div class="footer-contact">
            <div class="container">
                <div class="row footer-contact-row">
                    <?php foreach ( $office_top_contact as $item ) { ?>
                        <div class="col-md-6 col-lg-4 footer-contact-col">
                            <div class="address footer-contact-col-inner">
                                <i class="icon fa fa <?php echo esc_html($item->icon_value) ?> animated"> </i>
                                <span> <?php echo esc_html($item->title) ?></span>
                            </div>
                        </div>               
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>
    <?php } ?>