<?php
$global_options = $options['global'];

$extensions = $options['extensions'];
?>
<div class="ep-extension-tab-content">
    <h2><?php esc_html_e( 'EventPrime Extensions', 'eventprime-event-calendar-management' );?></h2>
</div>
<?php if(!empty($extensions)):?>
    <div class="ep-emailer-list">
        <table class="ep-setting-table-main">
            <tbody>
                <tr  valign="top" >
                    <td class="ep-form-table-wrapper" colspan="2">
                        <table class="ep-setting-table ep-setting-table-wide ep-from-manage-setting" cellspacing="0" id="ep-extension-manage-setting">
                            <thead>
                                <tr>
                                    <th><?php esc_html_e('Extension', 'eventprime-event-calendar-management'); ?></th>
                                    <th><?php esc_html_e('Description', 'eventprime-event-calendar-management'); ?></th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody id="ep-emailer-sortable">
                                <?php
                                if (count($extensions)) {
                                    foreach ($extensions as $key => $extension) {
                                        ?>
                                            <td class="ep-emailer-label">
                                                <?php echo esc_html($extension['extension']); ?>
                                            </td>

                                            <td class="ep-emailer-description">
                                                <?php echo esc_html($extension['description']); ?>
                                            </td>

                                            <td class="ep-emailer-setting">
                                                <a href="<?php echo $extension['url']; ?>" class="button alignright"><?php _e('Manage', 'eventprime-event-calendar-management'); ?></a>
                                            </td>
                                        </tr><?php
                                    }
                                }?>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
<?php else:
    esc_html_e( 'No Extension found.', 'eventprime-event-calendar-management' );
endif;