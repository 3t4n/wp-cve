<div class="eli-shortcode widget-elementinvader_addons_for_elementor elementinvader_contact_form contact-form <?php echo esc_attr($settings['custom_class']);?>">
    <div class="elementinvader_addons_for_elementor-container">
        <form class="elementinvader_addons_for_elementor_f">
            <div class="config" data-url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>"></div>
            <input type="hidden" name="element_id" value="1">
            <input type="hidden" name="shortcode" value="1">
            <?php foreach($settings as $key => $value):?>
                <?php if(empty($value)) continue;?>
                <input type="hidden" name="<?php echo esc_attr($key);?>" value="<?php echo esc_attr($value);?>">
            <?php endforeach;?>
            <div class="elementinvader_addons_for_elementor_f_box_alert"></div>
            <div class="elementinvader_addons_for_elementor_f_container">
                <div class="elementinvader_addons_for_elementor_f_group email elementinvader_addons_for_elementor_f_group_el_7250807 " style="">
                    <input name="Email" id="emailemail" type="email" class="elementinvader_addons_for_elementor_f_field" required="required" value="" placeholder="<?php echo esc_html__('Email','elementinvader-addons-for-elementor');?>">
                </div>               
                <div class="elementinvader_addons_for_elementor_f_group elementinvader_addons_for_elementor_f_group_el_button justify">
                    <button type="submit">
                        <span class="elementor-button-text"><?php echo esc_html__('Subscribe','elementinvader-addons-for-elementor');?></span>
                        <i class="fa fa-spinner fa-spin fa-custom-ajax-indicator ajax-indicator-masking " style="display: none;"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>