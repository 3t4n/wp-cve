
<div class="wpsf-form-content-wrap">

    <div class="wpsf-content-left">
        <?php
        // Heading Text
        if ($heading_show && !empty($heading_text)) {
            ?>
            <h2 class="wpsf-heading-text"><?php echo $this->sanitize_html($heading_text); ?></h2>
            <?php
        }
        ?>
        <?php
        // Sub Heading Text
        if ($sub_heading_show && !empty($sub_heading_text)) {
            ?>
            <p class="wpsf-heading-text wpsf-heading-paragraph"><?php echo $this->sanitize_html($sub_heading_text); ?></p>
            <?php
        }
        ?>
    </div>
    <div class="wpsf-content-right">
        <div class="both-fields-wrap">
            <?php
            // Name Field
            if ($name_show) {
                ?>
                <div class="wpsf-field-wrap name-field has-pre-icon">
                    <label for="wpsf_name" class="sr-only wpsf-hidden-item"><?php echo esc_attr($name_label); ?></label>
                    <input type="text" name="wpsf_name" class="wpsf-name wpsf-rounded" placeholder="<?php echo esc_attr($name_label); ?>"/>
                    <i class="fas fa-user"></i>
                </div>
                <?php
            }
            ?>
            <!--Email Field-->
            <div class="wpsf-field-wrap has-pre-icon">
                <label for="wpsf_email" class="sr-only wpsf-hidden-item"><?php echo esc_attr($email_label); ?></label>
                <input type="email" name="wpsf_email" class="wpsf-email wpsf-rounded" placeholder="<?php echo esc_attr($email_label); ?>"/>
                <i class="far fa-envelope"></i>
            </div>
            <!-- Email Field-->
        </div>
        <?php
        // Terms and Agreement Text
        if ($terms_agreement_show && !empty($terms_agreement_text)) {
            ?>
            <div class="wpsf-field-wrap wpsf-terms-agreement-wrap wpsf-check-box-text">
                <label>
                    <input type="checkbox" name="wpsf_terms_agreement" class="wpsf-terms-agreement"/>
                    <?php echo $this->sanitize_html($terms_agreement_text); ?>
                </label>
            </div>
            <?php
        }
        ?>
        <!-- Subscribe Button-->
        <div class="wpsf-field-wrap">
            <input type="submit" name="wpsf_form_submit" class="wpsf-form-submit wpsf-rounded" value="<?php echo esc_attr($subscribe_button_text); ?>"/>

        </div>
        <?php
        // Footer Text
        if ($footer_show && !empty($footer_text)) {
            ?>
            <div class="wpsf-footer-text"><?php echo $this->sanitize_html($footer_text); ?></div>
            <?php
        }
        ?>
        <div class="wpsf-form-message"></div>

        <span class="wpsf-form-loader-wraper">
            <div class="wpsf-form-loader wpsf-form-loader-1"><?php esc_html_e('Loading...','wp-subscription-forms');?></div>
    </span>
    </div>
</div>


