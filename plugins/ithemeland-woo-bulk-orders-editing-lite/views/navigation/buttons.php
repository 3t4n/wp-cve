<li>
    <a href="javascript:;" title="<?php _e('Filter', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>" data-toggle="float-side-modal" data-target="#wobel-float-side-modal-filter">
        <i class="wobel-icon-filter1"></i>
    </a>
</li>

<li class="wobel-quick-filter">
    <a href="javascript:;" title="<?php _e('Quick Search', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>">
        <i class="wobel-icon-search1"></i>
    </a>
    <?php include_once WOBEL_VIEWS_DIR . "bulk_edit/filter_bar.php"; ?>
</li>

<li>
    <a href="javascript:;" title="<?php _e('Bulk Edit', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>" data-toggle="float-side-modal" data-target="#wobel-float-side-modal-bulk-edit">
        <i class="wobel-icon-edit"></i>
    </a>
</li>

<li>
    <a href="javascript:;" title="<?php _e('Bind Edit', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>" class="wobel-bind-edit-switch">
        <i class="wobel-icon-link"></i>
    </a>

    <input type="checkbox" style="display: none;" id="wobel-bind-edit">
</li>

<li>
    <a href="javascript:;" class="wobel-top-nav-duplicate-button" title="<?php _e('Duplicate', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>">
        <i class="wobel-icon-copy"></i>
    </a>
</li>

<li>
    <a href="javascript:;" class="wobel-reload-table" title="<?php _e('Reload', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>">
        <i class="wobel-icon-refresh-cw"></i>
    </a>
</li>

<li>
    <a href="javascript:;" title="<?php _e('New', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>" class="wobel-new-item-button" data-toggle="modal" data-target="#wobel-modal-new-item">
        <i class="wobel-icon-plus1"></i>
    </a>
</li>

<li class="wobel-has-sub-tab">
    <a href="javascript:;" class="wobel-tab-icon-red" title="<?php _e('Delete', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>">
        <i class="wobel-icon-trash-2"></i>
    </a>

    <ul class="wobel-sub-tab">
        <li>
            <a href="javascript:;" class="wobel-bulk-edit-delete-action" data-delete-type="trash">
                <i class="wobel-icon-trash-2"></i>
                <span><?php _e('Move to trash', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></span>
            </a>
        </li>
        <li>
            <a href="javascript:;" class="wobel-bulk-edit-delete-action" data-delete-type="permanently">
                <i class="wobel-icon-delete"></i>
                <span><?php _e('Permanently', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></span>
            </a>
        </li>
        <li>
            <a href="javascript:;" class="wobel-bulk-edit-delete-action" data-delete-type="all">
                <i class="wobel-icon-x-square"></i>
                <span><?php _e('Delete All Orders', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></span>
            </a>
        </li>
    </ul>
</li>

<li>
    <a href="javascript:;" title="<?php _e('Column Profile', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>" data-toggle="float-side-modal" data-target="#wobel-float-side-modal-column-profiles">
        <i class="wobel-icon-table2"></i>
    </a>
</li>

<li>
    <a href="javascript:;" title="<?php _e('Filter Profile', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>" data-toggle="float-side-modal" data-target="#wobel-float-side-modal-filter-profiles">
        <i class="wobel-icon-insert-template"></i>
    </a>
</li>

<li>
    <a href="javascript:;" title="<?php _e('Column Manager', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>" data-toggle="float-side-modal" data-target="#wobel-float-side-modal-column-manager">
        <i class="wobel-icon-columns"></i>
    </a>
</li>

<li>
    <a href="javascript:;" title="<?php _e('Meta fields', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>" data-toggle="float-side-modal" data-target="#wobel-float-side-modal-meta-fields">
        <i class="wobel-icon-list"></i>
    </a>
</li>

<li class="wobel-has-sub-tab">
    <a href="javascript:;" class="wobel-tab-item" title="<?php _e('History', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>">
        <i class="wobel-icon-clock"></i>
    </a>

    <ul class="wobel-sub-tab">
        <li>
            <a href="javascript:;" data-toggle="float-side-modal" data-target="#wobel-float-side-modal-history">
                <i class="wobel-icon-clock"></i>
                <span><?php _e('History', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></span>
            </a>
        </li>
        <li>
            <a href="javascript:;" id="wobel-bulk-edit-undo">
                <i class="wobel-icon-rotate-ccw"></i>
                <span><?php _e('Undo', 'ithemeland-woocommerce-bulk-orders-editing-lite') ?></span>
            </a>
        </li>
        <li>
            <a href="javascript:;" id="wobel-bulk-edit-redo">
                <i class="wobel-icon-rotate-cw"></i>
                <span><?php _e('Redo', 'ithemeland-woocommerce-bulk-orders-editing-lite') ?></span>
            </a>
        </li>
    </ul>
</li>

<li>
    <a href="javascript:;" title="<?php _e('Import/Export', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>" data-toggle="float-side-modal" data-target="#wobel-float-side-modal-import-export">
        <i class="wobel-icon-repeat"></i>
    </a>
</li>

<li>
    <a href="javascript:;" title="<?php _e('Settings', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>" data-toggle="float-side-modal" data-target="#wobel-float-side-modal-settings">
        <i class="wobel-icon-settings"></i>
    </a>
</li>

<li style="display: none;">
    <a href="javascript:;" class="wobel-tab-icon-red wobel-reset-filter-form" title="<?php _e('Reset Filter', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>">
        <i class="wobel-icon-ungroup"></i>
    </a>
</li>

<li style="display: none;" class="wobel-has-sub-tab">
    <a href="javascript:;" class="wobel-tab-icon-red wobel-trash-options">
        <i class="wobel-icon-trash"></i>
    </a>

    <ul class="wobel-sub-tab">
        <li>
            <a href="javascript:;" class="wobel-trash-option-restore-selected-items">
                <i class="wobel-icon-rotate-ccw"></i>
                <span><?php _e('Restore Selected Items', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></span>
            </a>
        </li>
        <li>
            <a href="javascript:;" class="wobel-trash-option-restore-all">
                <i class="wobel-icon-rotate-ccw"></i>
                <span><?php _e('Restore All', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></span>
            </a>
        </li>
        <li>
            <a href="javascript:;" class="wobel-trash-option-delete-selected-items">
                <i class="wobel-icon-x-square"></i>
                <span><?php _e('Delete Permanently', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></span>
            </a>
        </li>
        <li>
            <a href="javascript:;" class="wobel-trash-option-delete-all">
                <i class="wobel-icon-trash-2"></i>
                <span><?php _e('Empty Trash', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></span>
            </a>
        </li>
    </ul>
</li>