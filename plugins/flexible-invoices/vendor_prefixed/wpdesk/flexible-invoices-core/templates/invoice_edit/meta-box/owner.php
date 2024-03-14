<?php

namespace WPDeskFIVendor;

/**
 * @var array $params
 */
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Seller;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document;
$params = isset($params) ? $params : [];
/**
 * @var Document $invoice
 */
$invoice = $params['invoice'];
/**
 * @var Seller $seller
 */
$seller = $params['owner'];
/**
 * @var array $signature_users
 */
$signature_users = isset($params['signature_users']) ? $params['signature_users'] : [];
$document_issuing = 'Manual Issuing Proforma and Invoices';
?>
<div class="form-wrap inspire-panel invoice-edit-display">
    <div class="display">
        <div class="inspire_invoices_owner_logo">
            <img src="<?php 
echo \esc_url($seller->get_logo());
?>" alt="" width="100" />
        </div>
        <div class="inspire_invoices_owner_name"><?php 
\esc_html_e('Company Name', 'flexible-invoices');
?>: <span><?php 
echo \esc_html($seller->get_name());
?></span></div>
        <div class="inspire_invoices_owner_address"><?php 
\esc_html_e('Company Address', 'flexible-invoices');
?>: <span><?php 
echo \esc_html($seller->get_address());
?></span></div>
        <div class="inspire_invoices_owner_nip"><?php 
\esc_html_e('VAT Number', 'flexible-invoices');
?>: <span><?php 
echo \esc_html($seller->get_vat_number());
?></span></div>
        <div class="inspire_invoices_owner_bank_name"><?php 
\esc_html_e('Bank Name', 'flexible-invoices');
?>: <span><?php 
echo \esc_html($seller->get_bank_name());
?></span></div>
        <div class="inspire_invoices_owner_account_number"><?php 
\esc_html_e('Bank Account Number', 'flexible-invoices');
?>: <span><?php 
echo \esc_html($seller->get_bank_account_number());
?></span></div>
        <?php 
$signature_name = '';
if ($seller->get_signature_user()) {
    $user = \get_user_by('id', $seller->get_signature_user());
    $signature_name = isset($user->display_name) ? $user->display_name : '';
}
?>
        <div class="inspire_invoices_owner_signature_user"><?php 
\esc_html_e('Seller signature', 'flexible-invoices');
?>: <span><?php 
echo \esc_html($signature_name);
?></span></div>
    </div>
    <div class="edit_data">
        <div class="form-field form-required">
            <label for="inspire_invoices_owner_name"><?php 
\esc_html_e('Company Name', 'flexible-invoices');
?></label>
            <input data-beacon_search="<?php 
echo \esc_attr($document_issuing);
?>" id="inspire_invoices_owner_name" type="text" name="owner[name]" class="medium hs-beacon-search" value="<?php 
echo \esc_attr($seller->get_name());
?>" />
        </div>

        <div class="form-field form-required">
            <label for="inspire_invoices_owner_logo"><?php 
\esc_html_e('Logo', 'flexible-invoices');
?></label>
            <div class="media-input-wrapper" id="image_picker">
                <input type="hidden" class="image-field-value" value="<?php 
echo \esc_html($seller->get_logo());
?>"
                       name="owner[logo]"
                       id="inspire_invoices_owner_logo"/>
                <div class="custom-img-container">
                    <?php 
if ($seller->get_logo()) {
    ?>
                        <img src="<?php 
    echo \esc_url($seller->get_logo());
    ?>" alt="" width="100" />
                    <?php 
}
?>
                </div>
                <p class="hide-if-no-js">
                    <a class="upload-custom-img <?php 
if ($seller->get_logo()) {
    ?>hidden<?php 
}
?>" href="<?php 
echo \esc_url($seller->get_logo());
?>">
                        <?php 
\esc_html_e('Set image', 'flexible-invoices');
?>
                    </a>
                    <a class="delete-custom-img <?php 
if (!$seller->get_logo()) {
    ?>hidden<?php 
}
?>" href="#">
                        <?php 
\esc_html_e('Remove image', 'flexible-invoices');
?>
                    </a>
                </p>
            </div>

        </div>

        <div class="form-field form-required">
            <label for="inspire_invoices_owner_address"><?php 
\esc_html_e('Company Address', 'flexible-invoices');
?></label>
            <textarea data-beacon_search="<?php 
echo $document_issuing;
?>" class="hs-beacon-search" id="inspire_invoices_owner_address" name="owner[address]"><?php 
echo $seller->get_address();
?></textarea>
        </div>

        <div class="form-field form-required">
            <label for="inspire_invoices_owner_nip"><?php 
\esc_html_e('VAT Number', 'flexible-invoices');
?></label>
            <input data-beacon_search="<?php 
echo $document_issuing;
?>" id="inspire_invoices_owner_nip" type="text" name="owner[nip]" class="medium hs-beacon-search" value="<?php 
echo $seller->get_vat_number();
?>" />
        </div>

        <div class="form-field form-required">
            <label for="inspire_invoices_owner_bank_name"><?php 
\esc_html_e('Bank Name', 'flexible-invoices');
?></label>
            <input data-beacon_search="<?php 
echo $document_issuing;
?>" id="inspire_invoices_owner_bank_name" type="text" name="owner[bank]" class="medium hs-beacon-search" value="<?php 
echo $seller->get_bank_name();
?>" />
        </div>

        <div class="form-field form-required">
            <label for="inspire_invoices_owner_account_number"><?php 
\esc_html_e('Bank Account Number', 'flexible-invoices');
?></label>
            <input data-beacon_search="<?php 
echo $document_issuing;
?>" id="inspire_invoices_owner_account_number" type="text" name="owner[account]" class="medium hs-beacon-search" value="<?php 
echo $seller->get_bank_account_number();
?>" />
        </div>

        <div class="form-field form-required">
            <label for="inspire_invoices_owner_signature_user"><?php 
\esc_html_e('Seller signature', 'flexible-invoices');
?></label>
            <select name="owner[signature_user]" id="inspire_invoices_owner_signature_user">
                <?php 
$selected_signature_user = !empty($seller->get_signature_user()) ? $seller->get_signature_user() : '';
foreach ($signature_users as $signature_user_id => $signature_user_name) {
    ?>
                    <option <?php 
    echo \selected($signature_user_id, $selected_signature_user);
    ?> value="<?php 
    echo $signature_user_id;
    ?>"><?php 
    echo $signature_user_name;
    ?></option>
                    <?php 
}
?>
            </select>
        </div>
    </div>
</div>
<?php 
