<?php

$table = new Wpil_Table_Report;

?>
<div id="wpil-link-stats-metabox" class="categorydiv wpil_styles">
    <table class="wp-list-table widefat fixed striped table-view-list linkingstats sticky-ignore">
        <thead>
            <tr>
                <th scope="col" id="wpil_links_inbound_internal_count" class="manage-column column-wpil_links_inbound_internal_count">
                    <a href="#"><span><?php _e('Inbound internal links', 'wpil'); ?></span></a>
                </th>
                <th scope="col" id="wpil_links_outbound_internal_count" class="manage-column column-wpil_links_outbound_internal_count">
                    <a href="#"><span><?php _e('Outbound internal links', 'wpil'); ?></span></a>
                </th>
                <th scope="col" id="wpil_links_outbound_external_count" class="manage-column column-wpil_links_outbound_external_count">
                    <a href="#"><span><?php _e('Outbound external links', 'wpil'); ?></span></a>
                </th>
            </tr>
        </thead>
        <tbody id="the-list" data-wp-lists="list:linkingstats">
            <tr>
                <td class="wpil_links_inbound_internal_count column-wpil_links_inbound_internal_count" data-colname="Inbound internal links">
                    <?php
                        $item = array('post' => $post, 'wpil_links_inbound_internal_count' => true);
                        echo $table->column_default($item, 'wpil_links_inbound_internal_count');
                    ?>
                </td>
                <td class="wpil_links_outbound_internal_count column-wpil_links_outbound_internal_count" data-colname="Outbound internal links">
                    <?php
                        $item = array('post' => $post, 'wpil_links_outbound_internal_count' => true);
                        echo $table->column_default($item, 'wpil_links_outbound_internal_count');
                    ?>
                </td>
                <td class="wpil_links_outbound_external_count column-wpil_links_outbound_external_count" data-colname="Outbound external links">
                    <?php
                        $item = array('post' => $post, 'wpil_links_outbound_external_count' => true);
                        echo $table->column_default($item, 'wpil_links_outbound_external_count');
                    ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>