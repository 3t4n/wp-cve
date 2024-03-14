<?php include_once "filter_form.php"; ?>
<div class="wobef-wrap">
    <div class="wobef-tab-middle-content wobef-mt64">
        <?php include_once "top_navigation.php"; ?>
        <div class="wobef-table" id="wobef-items-table">
            <?php include_once WOBEF_VIEWS_DIR . "data_table/items.php"; ?>
        </div>
        <div class="external-scroll_wrapper">
            <div class="external-scroll_x">
                <div class="scroll-element_outer">
                    <div class="scroll-element_size"></div>
                    <div class="scroll-element_track"></div>
                    <div class="scroll-bar"></div>
                </div>
            </div>
        </div>
        <div class="wobef-items-pagination wobef-mt-10">
            <?php include 'pagination.php'; ?>
        </div>
        <div class="wobef-items-count wobef-mt-10">

        </div>
    </div>
</div>
<input type="hidden" id="wobef-last-modal-opened" value="">
<?php include_once "bulk_edit_form.php"; ?>
<?php include_once "columns_modals/customer_details.php"; ?>
<?php include_once "columns_modals/order_details.php"; ?>
<?php include_once "columns_modals/order_billing.php"; ?>
<?php include_once "columns_modals/order_shipping.php"; ?>
<?php include_once "columns_modals/order_notes.php"; ?>
<?php include_once "columns_modals/order_items.php"; ?>
<?php include_once "columns_modals/order_address.php"; ?>
<?php include_once WOBEF_VIEWS_DIR . "modals/text_editor.php"; ?>
<?php include_once WOBEF_VIEWS_DIR . "modals/numeric_calculator.php"; ?>
<?php include_once WOBEF_VIEWS_DIR . "modals/duplicate_item.php"; ?>
<?php include_once WOBEF_VIEWS_DIR . "modals/new_item.php"; ?>
<?php include_once WOBEF_VIEWS_DIR . "modals/filter_profiles.php"; ?>
<?php include_once WOBEF_VIEWS_DIR . "modals/column_profiles.php"; ?>