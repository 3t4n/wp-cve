<div class="af2_custom_builder_wrapper af2_formularbuilder_preview">
    <?php 
            $pgID = sanitize_text_field($_GET['id']);
            _e(do_shortcode('[funnelforms id="'.$pgID.'" preview="true"]')); 
    ?>
</div>