<?php

/*
 * Select Schemes
 */

$schemes = $this->get_schemes();

?>


<!--    Scheme List-->
<form method="get" action="" id="posts-filter">

    <table cellspacing="0" class="wp-list-table widefat fixed posts">

        <thead>
        <tr>

            <th style="" class="manage-column column-title sortable desc" id="title" scope="col">
                <a href="">
                    <span><?php _e("Name", $this->plugin_slug); ?></span><span class="sorting-indicator"></span>
                </a>
            </th>
            <th style="" class="manage-column column-event" scope="col">
                <?php _e("Event", $this->plugin_slug); ?>
            </th>
            <th style="" class="manage-column column-width" scope="col">
                <?php _e("Width", $this->plugin_slug); ?>
            </th>
            <th style="" class="manage-column column-height" scope="col">
                <?php _e("Height", $this->plugin_slug); ?>
            </th>
            <th style="" class="manage-column column-purchase-limit" scope="col">
                <?php _e("Purchase Limit", $this->plugin_slug); ?>
            </th>
            <th style="" class="manage-column column-shortcode" id="tags" scope="col">
                <?php _e("Shortcode", $this->plugin_slug); ?>
            </th>

        </tr>
        </thead>

        <tfoot>
        <tr>

            <th style="" class="manage-column column-title sortable desc" scope="col">
                <a href="">
                    <span><?php _e("Name", $this->plugin_slug); ?></span><span class="sorting-indicator"></span>
                </a>
            </th>
            <th style="" class="manage-column column-event" scope="col">
                <?php _e("Event", $this->plugin_slug); ?>
            </th>
            <th style="" class="manage-column column-width" scope="col">
                <?php _e("Width", $this->plugin_slug); ?>
            </th>
            <th style="" class="manage-column column-height" scope="col">
                <?php _e("Height", $this->plugin_slug); ?>
            </th>
            <th style="" class="manage-column column-purchase-limit" scope="col">
                <?php _e("Purchase Limit", $this->plugin_slug); ?>
            </th>
            <th style="" class="manage-column column-shortcode" scope="col">
                <?php _e("Shortcode", $this->plugin_slug); ?>
            </th>

        </tr>
        </tfoot>

        <tbody id="the-list">

        <?php if ($schemes && is_array($schemes)): ?>
            <?php foreach ($schemes as $scheme) : ?>

                <tr valign="top" class="post-1 type-post status-publish format-standard hentry category-uncategorized alternate iedit author-self" id="post-1">

                    <td class="post-title page-title column-title">
                        <strong>
                            <a title="<?php _e("Edit", $this->plugin_slug); ?>" href="<?php echo $this->page_url; ?>&scheme=<?php echo $scheme->scheme_id; ?>&action=edit" class="row-title">
                                <?php echo $scheme->name; ?>
                            </a>
                        </strong>

                        <div class="row-actions">
                            <span class="edit"><a title="<?php _e("Edit this item", $this->plugin_slug); ?>" href="<?php echo $this->page_url; ?>&scheme=<?php echo $scheme->scheme_id; ?>&action=edit"><?php _e("Edit", $this->plugin_slug); ?></a> | </span>
                            <span class="view"><a title="<?php _e("View this item", $this->plugin_slug); ?>" href="<?php echo $this->page_url; ?>&scheme=<?php echo $scheme->scheme_id; ?>&action=view"><?php _e("View", $this->plugin_slug); ?></a> | </span>
                            <span class="duplicate"><a title="<?php _e("Duplicate this item", $this->plugin_slug); ?>" href="<?php echo $this->page_url; ?>&scheme=<?php echo $scheme->scheme_id; ?>&action=duplicate"><?php _e("Duplicate", $this->plugin_slug); ?></a> | </span>
                            <span class="trash submitdelete"><a href="<?php echo $this->page_url; ?>&scheme=<?php echo $scheme->scheme_id; ?>&action=delete" title="<?php _e("Delete this item", $this->plugin_slug); ?>"><?php _e("Delete", $this->plugin_slug); ?></a></span>
                        </div>
                    </td>
                    <td class="event column-event">
                        <?php if ($scheme->event) : ?>
                            <?php echo $scheme->event; ?><br>
                            (<?php echo $scheme->start; ?>)
                        <?php else : ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td class="author column-author">
                        <?php echo $scheme->width; ?>
                    </td>
                    <td class="categories column-categories">
                        <?php echo $scheme->height; ?>
                    </td>
                    <td class="categories column-categories">
                        <?php echo $scheme->purchase_limit; ?>
                    </td>
                    <td class="tags column-shortcodes">
                        <?php echo '[book_a_place scheme="' . $scheme->scheme_id . '"]'; ?>
                    </td>

                </tr>


            <?php endforeach; ?>

        <?php else : ?>

            <tr valign="top" class="post-1 type-post status-publish format-standard hentry category-uncategorized alternate iedit author-self" id="post-1">
                <td colspan="4">
                    <?php _e("There are no schemes yet.", $this->plugin_slug); ?>
                </td>
            </tr>

        <?php endif; ?>


        </tbody>

    </table>

</form>
<!--    End Scheme List-->