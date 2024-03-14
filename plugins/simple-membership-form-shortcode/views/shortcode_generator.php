<?php 
?>
<div class="wrap">
    <div id="poststuff"><div id="post-body">
            <h2><?php SwpmUtils::e('Simple Membership Form Shortcode Addon'); ?></h2>

            <div class="postbox">
                <h3><label for="title">Shortcode Generator</label></h3>
                <div class="inside">

                    <form action="javascript:void(0);" id="swpm-shortcode-generator" >
                        <p>
                            <select id="membership-level">
                                <option value="0" selected>Default</option>
                                <?php echo SwpmUtils::membership_level_dropdown(); ?>
                            </select>
                        </p>
                        <p>
                            <input type="text" size="50" onclick="this.select()" readonly value="[swpm_registration_form]" id="shortcode" name="shortcode" />
                        </p>
                    </form>
                </div></div>

        </div></div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('#swpm-shortcode-generator #membership-level').change(function () {
            $('#swpm-shortcode-generator #shortcode').val('[swpm_registration_form'
                    + ($(this).val() === '0' ? '' : ' level=' + $(this).val()) + ']');
        });
    });
</script>