<div id="dialog-message" title="Make Stories Setting">
    <form method="POST" id="make_stories_form">
        <?php wp_nonce_field('mscpt_register_amp_stories_post_type'); ?>
        <table style="width: 100%">
            <tbody>
            <tr>
                <th scope="row"><label>Post Slug</label></th>
                <td style="text-align: right"><input type="text" required="" name="mscpt_makestories_post_slug"></td>
            </tr>
            </tbody>
        </table>
    </form>
</div>
<script type="text/javascript">
    jQuery(function () {
        jQuery("#dialog-message").dialog({
            modal: true,
            draggable: false,
            closeOnEscape: false,
            buttons: {
                save: function () {
                    jQuery('#make_stories_form').submit();
                    jQuery(this).dialog("close");
                }
            }
        });
    });
</script>