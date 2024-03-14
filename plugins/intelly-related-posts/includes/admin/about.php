<?php
function irp_ui_about() {
    global $irp;
    irp_ui_tracking(TRUE);
    ?>
    <div><?php $irp->Lang->P('AboutText')?></div>
    <style>
        ul li {
            padding:2px;
        }
    </style>
    <ul>
        <li>
            <img style="float:left; margin-right:10px;" src="<?php echo esc_url(IRP_PLUGIN_IMAGES)?>email.png" />
            <a href="mailto:support@intellywp.com">support@intellywp.com</a>
        </li>
        <li>
            <img style="float:left; margin-right:10px;" src="<?php echo esc_url(IRP_PLUGIN_IMAGES)?>twitter.png" />
            <?php $irp->Utils->twitter('data443risk')?>
        </li>
        <li>
            <img style="float:left; margin-right:10px;" src="<?php echo esc_url(IRP_PLUGIN_IMAGES)?>internet.png" />
            <a href="https://data443.com" target="_new">Data443.com</a>
        </li>
    </ul>
    <?php
}