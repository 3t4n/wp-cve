<?php
include_once 't-eesf_widget-header.php';
$option = get_option('ee_security_options');
?>

<!-- Powered by Elastic Email | Elastic Email Subscribe Form - https://wordpress.org/plugins/elastic-email-subscribe-form/-->
<div id="eewp_widget"
     style="background:<?php echo '#' . $instance['color_body'] ?>; border-radius: <?php echo $instance['border_radius'] ?>;
             padding: <?php echo $instance['widget_padding'] ?>">
    <h4 class="title"
        style="color:<?php echo '#' . $instance['color_header-txt'] ?>; text-align:<?php echo $instance['text_align'] ?>">
        <?php echo $instance['text_header']; ?>
    </h4>

    <p style="color:<?php echo '#' . $instance['color_description-txt'] ?>; text-align:<?php echo $instance['text_align'] ?>;">
        <?php echo $instance['text_description']; ?>
    </p>

    <form action="" method="post" id="eesf_subscribewidget">

        <?php if ($option['ee_security_status'] === 'yes') { ?>
            <div id='form-recaptcha'
                 class='g-recaptcha'
                 data-sitekey='<?= $option['ee_site_key'] ?>'
                 data-callback='onSubmit'
                 data-size='invisible'>
        </div>
        <?php } 
        
        $is_list_selection_checkbox = $instance['list_selection_checkbox'] ? 'true' : 'false';
        $checkbox_style = '';
        if ($is_list_selection_checkbox === 'true') {
            $checkbox_style = 'display: block';
        } else {
            $checkbox_style = 'display: none';
        }

        ?>

        <div class="eesf-form-group-checkbox" style="<?php echo $checkbox_style ?>">
            <?php

            $display = 'display:none;';
            $list_selection_checkbox = $instance['list_selection_checkbox'] ? 'true' : 'false';

            foreach ($instance['checked_lists_name_and_id'] as $listID => $listName) {

                if ($list_selection_checkbox === 'true') {
                    $display = 'dispaly:inline-block;';
                }

                echo '
                <div class="single-checkbox-list">
                    <input 
                        type="checkbox" 
                        class="eesw-single-list" 
                        value="' . $listName . '" 
                        id="' . $listID . '" 
                        checked="checked" style="' . $display . '
                    ">
                    
                    <label 
                        for="' . $listID . '" style="' . $display . '">
                        ' . $listName . '
                    </label>
                </div>
                ';

            }
            ?>
            <div class="row">
                <span class="form_error hide" id="invalid_list">Please select list</span>
            </div>
        </div>

        <div class="row">
        <?php 
            $is_hide_name_input = $instance['hide_name_checkbox'] ? 'true' : 'false';
            if ($is_hide_name_input !== 'true') { ?>
            <div class="eesf-form-group">
                <label for="eesw-name" style="color:<?php echo '#' . $instance['color_input-label'] ?>">Name</label>
                <input maxlength="60" name="eesw-name" id="eesw-name" type="text" size="20"
                       placeholder="Please enter your name" class="form-control contact-input"
                       style="text-align:left;padding-left:6px;padding-right:6px;background:<?php echo '#' . $instance['color_input-bg'] ?>; border-radius: <?php echo $instance['border_radius'] ?>; color:<?php echo '#' . $instance['color_input-txt'] ?>;">
                <span class="form_error hide" id="invalid_name">Please enter your name</span>
            </div>
            <?php } ?>

            <div id="isNameActive" isNameActive='<?php echo $is_hide_name_input ?>' style="display: none;"></div>
            <div id="activationTemplate" activationTemplate='<?php echo $instance['activation_template'] ?>' style="display: none;"></div>

            <div class="eesf-form-group">

                <label for="email">
                    Email
                </label>

                <input maxlength="60"
                       name="eesw-email"
                       id="eesw-email"
                       type="email"
                       size="20"
                       placeholder="Please enter your email"
                       class="form-control contact-input"
                       style="text-align:left;
                               padding-left:6px;
                               padding-right:6px;
                               background:<?php echo '#' . $instance['color_input-bg'] ?>;
                               border-radius: <?php echo $instance['border_radius'] ?>;
                               color:<?php echo '#' . $instance['color_input-txt'] ?>;
                               ">

                <span class="form_error  hide" id="empty_email">
                    Please enter your email
                </span>

                <span class="form_error  hide" id="invalid_email">
                    This email is not valid
                </span>

            </div>

            <?php
            $is_active_agree_and_terms = $instance['agree_and_terms_checkbox'] ? 'true' : 'false';

            if ($is_active_agree_and_terms === 'true') { ?>
                <div class="eesf-legal-info" id="eesf-legal-container">
                <div class="eesf-legal-content">
                    <input type="checkbox" id="eesf-legal" name="eesf-legal">
                    <a href="<?php echo $instance['url_terms'] ?>"><label for="eesf-legal"><?php echo $instance['text_terms'] ?></label></a>
                </div>
            </div>

            <span class="form_error hide" id="uncheck_agree">
                Required to subscribed
            </span>
            <?php } ?>
                
            <div id="isTermsActive" isTermsActive='<?php echo $is_active_agree_and_terms ?>' style="display: none;"></div>

            <div class="eesf-form-group" style="text-align:<?php echo $instance['button-position'] ?>">
                <input type="submit"
                       class="eesf-submit"
                       id="submit_from_widget"
                       value="<?php echo $instance['text_subscribe'] ?>"
                       style="background:<?php echo '#' . $instance['color_button-bg'] ?>;
                               color:<?php echo '#' . $instance['color_button-txt'] ?>;
                               text-align:<?php echo $instance['text_align'] ?>;
                               border-radius: <?php echo $instance['border_radius'] ?>;
                               "
                >
            </div>

        </div>

    </form>

    <?php include_once 't-eesf_widget-footer.php'; ?>

</div>
