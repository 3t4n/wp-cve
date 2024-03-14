<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       www.mydirtyhobby.com/registrationplugin
 * @since      1.0.0
 *
 * @package    Mdh_Promote
 * @subpackage Mdh_Promote/admin/partials
 */
?>
<div id="mdh-promo-admin-settings">
    <h2><?php echo esc_html(get_admin_page_title()); ?></h2>
    <form class="mdh-promo-settings-form" action="options.php" method="post">
        <?php
        do_settings_sections($this->plugin_name);
        settings_fields($this->plugin_name);
        ?>
        <div class="mdh-settings-info">
            <b>Shortcode usage:</b> "<strong> [mdh_register_btn]</strong> your text <strong>[/mdh_register_btn]</strong> "
        </div>
        <?php
        submit_button();
        ?>
    </form>
    <?php if ($this->profile_pic_link) : ?>
        <div id="mdh-promo-profile-pic-preview">
            <h3>Profile Picture</h3>
            <img src="<?php echo $this->profile_pic_link ?>">
        </div>
    <?php endif; ?>
</div>

<script>
    (function ($) {
        $('.mdh-promo-code-type-radio').on('change', function (e) {
            var code_type = this.value;
            $("input[name='mdh-promo_promo_code_type']").val(code_type);
        });
        $('.register_popup_lang-radio').on('change', function (e) {
            var lang = this.value;
            $("input[name='mdh-promo_register_popup_lang']").val(lang);
        });
    })(jQuery);
</script>