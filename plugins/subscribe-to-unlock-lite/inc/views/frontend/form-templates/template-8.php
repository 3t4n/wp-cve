<div class="form-contents-wrap">
    <?php
    $icon_image = STUL_URL . 'images/lockIcon.png';
    ?>

    <div class="stul-icon-holder">
        <span class="icon">
            <img src="<?php echo esc_url($icon_image); ?>"/>
        </span>
        <?php
        // Heading Text
        if ( $heading_show && !empty( $heading_text ) ) {
            ?>
            <h2 class="stul-heading-text"><?php echo $this->sanitize_html( $heading_text ); ?></h2>
            <?php
        }
        ?>
        <?php
        // Sub Heading Text
        if ( $sub_heading_show && !empty( $sub_heading_text ) ) {
            ?>
            <p class="stul-heading-text stul-heading-paragraph"><?php echo $this->sanitize_html( $sub_heading_text ); ?></p>
            <?php
        }
        ?>



    </div>

    <div class="form-holder">

        <div class="both-fields-wrap">

            <?php
            // Name Field
            if ( $name_show ) {
                ?>
                <div class="stul-field-wrap name-field has-pre-icon">
                    <label for="stul_name" class="sr-only stul-hidden-item"><?php echo esc_attr( $name_label ); ?></label>
                    <input type="text" name="stul_name" class="stul-name" placeholder="<?php echo esc_attr( $name_label ); ?>"/>
                    <i class="fas fa-user"></i>
                </div>
                <?php
            }
            ?>
            <!--Email Field-->
            <div class="stul-field-wrap has-pre-icon">
                <label for="stul_email" class="sr-only stul-hidden-item"><?php echo esc_attr( $email_label ); ?></label>
                <input type="email" name="stul_email" class="stul-email" placeholder="<?php echo esc_attr( $email_label ); ?>"/>
                <i class="far fa-envelope"></i>
            </div>
            <!-- Email Field-->
        </div>

        <?php
        // Terms and Agreement Text
        if ( $terms_agreement_show && !empty( $terms_agreement_text ) ) {
            ?>
            <div class="stul-field-wrap stul-terms-agreement-wrap stul-check-box-text">
                <label>
                    <input type="checkbox" name="stul_terms_agreement" class="stul-terms-agreement"/>
                    <?php echo $this->sanitize_html( $terms_agreement_text ); ?>
                </label>
            </div>
            <?php
        }
        ?>

        <!-- Subscribe Button-->
        <div class="stul-field-wrap stul-btn-parent">
            <input type="submit" name="stul_form_submit" class="stul-form-submit" value="<?php echo esc_attr( $subscribe_button_text ); ?>"/>

        </div>



        <?php
        // Footer Text
        if ( $footer_show && !empty( $footer_text ) ) {
            ?>
            <div class="stul-footer-text"><?php echo $this->sanitize_html( $footer_text ); ?></div>
            <?php
        }
        ?>

        <div class="stul-form-message"></div>
        <span class="stul-form-loader-wraper">
        <div class="stul-form-loader stul-form-loader-1"><?php esc_html_e('Loading...','subscribe-to-unlock');?></div>
    </span>
    </div>
</div>
