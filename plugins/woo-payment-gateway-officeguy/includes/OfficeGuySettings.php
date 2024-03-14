<?php
class OfficeGuySettings
{
    static function InitDefaultSettings($Gateway)
    {
        if (empty($Gateway->settings['maxpayments']))
            $Gateway->settings['maxpayments'] = '1';
        if (empty($Gateway->settings['minamountforpayments']))
            $Gateway->settings['minamountforpayments'] = '0';
        if (empty($Gateway->settings['minamountperpayment']))
            $Gateway->settings['minamountperpayment'] = '0';
        if (empty($Gateway->settings['automaticlanguages']))
            $Gateway->settings['automaticlanguages'] = 'yes';
        if (empty($Gateway->settings['mergecustomers']))
            $Gateway->settings['mergecustomers'] = 'no';
        if (empty($Gateway->settings['draftdocument']))
            $Gateway->settings['draftdocument'] = 'no';
        if (empty($Gateway->settings['paypalreceipts']))
            $Gateway->settings['paypalreceipts'] = 'no';
        if (empty($Gateway->settings['bluesnapreceipts']))
            $Gateway->settings['bluesnapreceipts'] = 'no';
        if (empty($Gateway->settings['fourdigitsyear']))
            $Gateway->settings['fourdigitsyear'] = 'no';
        if (empty($Gateway->settings['singlecolumnlayout']))
            $Gateway->settings['singlecolumnlayout'] = 'yes';
        if (empty($Gateway->settings['environment']))
            $Gateway->settings['environment'] = 'www';
        if (empty($Gateway->settings['checkout_stock_sync']))
            $Gateway->settings['checkout_stock_sync'] = 'no';
        if (empty($Gateway->settings['stock_sync_freq']))
            $Gateway->settings['stock_sync_freq'] = 'none';
        if (empty($Gateway->settings['support_tokens']))
            $Gateway->settings['support_tokens'] = 'no';
        if (empty($Gateway->settings['authorizeonly']))
            $Gateway->settings['authorizeonly'] = 'no';
    }


    static function InitFormFields($Gateway)
    {
        $PCIOptions = array(
            'no' => __('Simple (Recommended option, supports all features, using PaymentsJS)', 'officeguy'),
            'redirect' => __('External page (Redirect, no support for recurring charges, storing card details or authorize without capture)', 'officeguy')
        );
        if ((!empty($_GET['og_advanced']) && $_GET['og_advanced'] == '1') || (!empty($Gateway->settings['pci']) && $Gateway->settings['pci'] == 'yes'))
            $PCIOptions['yes'] = __('Advanced (API calls, allowed only for PCI compliant websites)', 'officeguy');

        $Fields = array(
            'keys' => array(
                'title' => __('Company keys', 'officeguy'),
                'type' => 'title',
                'description' => __('There are three parameters used for integrating SUMIT with WooCommerce. <a href="https://app.sumit.co.il/developers/keys/" target="_blank">Click here to view them</a>', 'officeguy'),
            ),
            'companyid' => array(
                'title' => __('Company ID', 'officeguy') . ' *',
                'type' => 'number',
                'default' => ''
            ),
            'privatekey' => array(
                'title' => __('Private Key', 'officeguy') . ' *',
                'type' => 'text',
                'default' => ''
            ),
            'publickey' => array(
                'title' => __('Public Key', 'officeguy') . ' *',
                'type' => 'text',
                'default' => ''
            ),

            'general' => array(
                'title' => __('General Settings', 'officeguy'),
                'type' => 'title',
                'description' => '',
            ),
            'enabled' => array(
                'title' => __('Enable Payments', 'officeguy'),
                'label' => __('Enable SUMIT Payments', 'officeguy'),
                'type' => 'checkbox',
                'description' => '',
                'default' => 'no'
            ),
            'mergecustomers' => array(
                'title' => __('Merge customers', 'officeguy'),
                'type' => 'checkbox',
                'label' => __('Merge similar customers on SUMIT', 'officeguy'),
                'description' => __('Checking this will cause SUMIT to attach new orders to existing customers, when customers details match existing customers (name, email and WooCommerce customer id).', 'officeguy'),
                'default' => 'yes'
            ),
            'emaildocument' => array(
                'title' => __('Email Document', 'officeguy'),
                'type' => 'checkbox',
                'label' => __('Email created document to the customer', 'officeguy'),
                'description' => __('Checking this will cause SUMIT to send the created document (Invoice/Receipt) to the customer on successful payment.', 'officeguy'),
                'default' => 'yes'
            ),
            'createorderdocument' => array(
                'title' => __('Create Order document', 'officeguy'),
                'type' => 'checkbox',
                'label' => __('Create Order document in addition to invoice/receipt', 'officeguy'),
                'default' => 'no'
            ),
            'support_tokens' => array(
                'title' => __('Enable credit card tokens', 'officeguy'),
                'type' => 'checkbox',
                'label' => __('Allows customer to store their credit card details as secure tokens for future orders', 'officeguy'),
                'default' => 'no'
            ),

            'installmentssupport' => array(
                'title' => __('Installments support', 'officeguy'),
                'type' => 'title',
                'description' => '',
            ),
            'maxpayments' => array(
                'title' => __('Maximum installments', 'officeguy'),
                'type' => 'select',
                'description' => __('Maximum credit card installments to be allowed', 'officeguy'),
                'options' => array(
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                    '7' => '7',
                    '8' => '8',
                    '9' => '9',
                    '10' => '10',
                    '11' => '11',
                    '12' => '12',
                    '13' => '13',
                    '14' => '14',
                    '15' => '15',
                    '16' => '16',
                    '17' => '17',
                    '18' => '18',
                    '19' => '19',
                    '20' => '20',
                    '21' => '21',
                    '22' => '22',
                    '23' => '23',
                    '24' => '24',
                    '25' => '25',
                    '26' => '26',
                    '27' => '27',
                    '28' => '28',
                    '29' => '29',
                    '30' => '30',
                    '31' => '31',
                    '32' => '32',
                    '33' => '33',
                    '34' => '34',
                    '35' => '35',
                    '36' => '36'
                ),
                'default' => '1'
            ),
            'minamountforpayments' => array(
                'title' => __('Minimum payments amount', 'officeguy'),
                'type' => 'text',
                'label' => __('Minimum payments amount', 'officeguy'),
                'description' => __('This sets the minimum order amount for enabling payments. By default, payments are not restricted by the total order amount', 'officeguy'),
                'default' => '0'
            ),
            'minamountperpayment' => array(
                'title' => __('Minimum amount per payment', 'officeguy'),
                'type' => 'text',
                'label' => __('Minimum amount per payment', 'officeguy'),
                'description' => __('This sets the minimum amount for each payment when using payments transaction (installments). For instance, when setting a minimum amount per payment of 100NIS, a 300NIS transaction will allow up to 3 installments, and a 600NIS transactions will allow up to 6 installments.', 'officeguy'),
                'default' => '0'
            ),

            'stock_title' => array(
                'title' => __('Stock Management', 'officeguy'),
                'type' => 'title',
                'description' => __('Please note that in order to enable Stock Management, the <a href="https://app.sumit.co.il/modules/stock/" target="_blank">Stock Management Module</a> must be installed on SUMIT', 'officeguy'),
            ),
            'stock_sync_freq' => array(
                'title' => __('Stock synchronization frequency', 'officeguy'),
                'type' => 'select',
                'label' => __('Inventory sync frequency', 'officeguy'),
                // 'description' => __('', 'officeguy'),
                'default' => 'none',
                'options' => array(
                    'none' => __('None', 'officeguy'),
                    '12' => __('Every 12 hours', 'officeguy'),
                    '24' => __('Every 24 hours', 'officeguy'),
                )
            ),
            'checkout_stock_sync' => array(
                'title' => __('Update stock on checkout', 'officeguy'),
                'type' => 'checkbox',
                'label' => __('Checking this box will update inventory before checkout, up to once per hour', 'officeguy'),
                'default' => 'no',
            ),

            'userinterface' => array(
                'title' => __('User Interface', 'officeguy'),
                'type' => 'title',
                'description' => __('How your customer see the payment page and additional buttons', 'officeguy'),
            ),
            'title' => array(
                'title' => __('Title', 'officeguy'),
                'type' => 'text',
                'description' => __('This controls the title which the user sees during checkout', 'officeguy'),
                'default' => __('Credit Card (SUMIT)', 'officeguy')
            ),
            'description' => array(
                'title' => __('Description', 'officeguy'),
                'type' => 'textarea',
                'description' => __('This controls the description which the user sees during checkout', 'officeguy'),
                'default' => __('Pay with your credit card via SUMIT.', 'officeguy')
            ),
            'buynowloop' => array(
                'title' => __('"Buy Now" Button on catalog page', 'officeguy'),
                'type' => 'checkbox',
                'label' => __('Add "Buy Now" button to catalog page', 'officeguy'),
                'default' => 'no'
            ),
            'buynowitem' => array(
                'title' => __('"Buy Now" Button on product page', 'officeguy'),
                'type' => 'checkbox',
                'label' => __('Add "Buy Now" button to product page', 'officeguy'),
                'default' => 'no'
            ),

            'paymentsettings' => array(
                'title' => __('Payment settings', 'officeguy'),
                'type' => 'title',
                'description' => __('Set payment page fields', 'officeguy'),
            ),
            'citizenid' => array(
                'title' => __('Citizen ID', 'officeguy'),
                'type' => 'select',
                'label' => __('Require customer to enter credit card owner citizen id', 'officeguy'),
                'description' => __('Please note Citizen ID is required by the Israeli credit companies, unless explictly requested for Citizen ID exempt', 'officeguy'),
                'default' => 'yes',
                'options' => array(
                    'required' => __('Field is required', 'officeguy'),
                    'yes' => __('Field is optional', 'officeguy'),
                    'no' => __('Field is hidden', 'officeguy')
                )
            ),
            'cvv' => array(
                'title' => __('Security code (CVV)', 'officeguy'),
                'type' => 'select',
                'label' => __('Require customer to enter credit card CVV code', 'officeguy'),
                'description' => __('Please note CVV is required by the Israeli credit companies, unless explictly requested for CVV exempt', 'officeguy'),
                'default' => 'required',
                'options' => array(
                    'required' => __('Field is required', 'officeguy'),
                    'yes' => __('Field is optional', 'officeguy'),
                    'no' => __('Field is hidden', 'officeguy')
                )
            ),
            'fourdigitsyear' => array(
                'title' => __('Full year display', 'officeguy'),
                'type' => 'checkbox',
                'label' => __('Show year picker as four digits', 'officeguy'),
                'default' => 'yes'
            ),
            'singlecolumnlayout' => array(
                'title' => __('Single column layout', 'officeguy'),
                'type' => 'checkbox',
                'label' => __('Show payments fields in a single column instead of two columns', 'officeguy'),
                'default' => 'yes'
            ),

            'integrations' => array(
                'title' => __('Integrations', 'officeguy'),
                'type' => 'title',
                'description' => __('Integrate with additional payments options', 'officeguy'),
            ),
            'paypalreceipts' => array(
                'title' => __('PayPal', 'officeguy'),
                'type' => 'select',
                'description' => __('Create invoice/receipts on SUMIT following PayPal payments', 'officeguy'),
                'default' => 'no',
                'options' => array(
                    'no' => __('No', 'officeguy'),
                    'yes' => __('Yes, issue invoice automatically', 'officeguy'),
                    'async' => __('Yes (prevent duplicates using async job)', 'officeguy')
                )
            ),
            'bluesnapreceipts' => array(
                'title' => __('BlueSnap', 'officeguy'),
                'type' => 'checkbox',
                'label' => __('Create invoice/receipts on SUMIT following BlueSnap payments', 'officeguy'),
                'default' => 'no'
            ),
            'otherreceipts' => array(
                'title' => __('Other provider integration', 'officeguy'),
                'type' => 'text',
                'label' => __('Create invoice/receipts on SUMIT following custom payment provider.', 'officeguy'),
                'description' => __('Please enter the payment gateway id, as provided by <a href="https://app.sumit.co.il/help/support/" target="_blank">SUMIT support team</a>', 'officeguy')
            ),

            'authorize' => array(
                'title' => __('Authorize without capture', 'officeguy'),
                'type' => 'title',
                'description' => '',
            ),
            'authorizeonly' => array(
                'title' => __('Authorize without capture', 'officeguy'),
                'type' => 'checkbox',
                'label' => __('Enable support for authorization without capture', 'officeguy'),
                'description' => __('To understand the payment flow, you may read our <a href="https://help.sumit.co.il/he/articles/5832974" target="_blank">instructions page</a>', 'officeguy'),
                'default' => 'no'
            ),
            'authorizeaddedpercent' => array(
                'title' => __('Automatically added authorize percent amount', 'officeguy'),
                'type' => 'number',
                'description' => __('For unfixed payments, authorization can be made for increased amount based on increased percentage', 'officeguy'),
                'default' => ''
            ),
            'authorizeminimumaddition' => array(
                'title' => __('Minimum added authorize amount', 'officeguy'),
                'type' => 'number',
                'description' => __('For unfixed payments, authorization can be made for increased fixed amount', 'officeguy'),
                'default' => ''
            ),

            'advanced' => array(
                'title' => __('Advanced options', 'officeguy'),
                'type' => 'title',
                'description' => '',
            ),
            'draftdocument' => array(
                'title' => __('Draft documents', 'officeguy'),
                'type' => 'checkbox',
                'label' => __('Issue documents as draft', 'officeguy'),
                'default' => 'no'
            ),
            'testing' => array(
                'title' => __('Testing', 'officeguy'),
                'type' => 'checkbox',
                'label' => __('Testing mode (payments will not be committed, documents will be created as drafts)', 'officeguy'),
                'description' => __('Make sure to uncheck this before going live', 'officeguy'),
                'default' => 'no'
            ),
            'automaticlanguages' => array(
                'title' => __('Automatic document language', 'officeguy'),
                'type' => 'checkbox',
                'label' => __('Automatic document language according to the customer language', 'officeguy'),
                'description' => __('When unchecked, all documents will be issues in Hebrew', 'officeguy'),
                'default' => 'yes'
            ),
            'pci' => array(
                'title' => __('Payment input method', 'officeguy'),
                'type' => 'select',
                'label' => __('Choose payment input method', 'officeguy'),
                'default' => 'required',
                'options' => $PCIOptions
            ),
            'merchantnumber' => array(
                'title' => __('Merchant number', 'officeguy'),
                'type' => 'text',
                'label' => __('Merchant number', 'officeguy'),
                'description' => __('Do not use this parameter unless explictly requested by SUMIT team for multiple merchants usage', 'officeguy'),
                'default' => ''
            ),
            'subscriptionsmerchantnumber' => array(
                'title' => __('Subscriptions Merchant number', 'officeguy'),
                'type' => 'text',
                'label' => __('Subscriptions Merchant number', 'officeguy'),
                'description' => __('Do not use this parameter unless explictly requested by SUMIT team for multiple merchants usage', 'officeguy'),
                'default' => ''
            ),
            'logging' => array(
                'title' => __('Logging', 'officeguy'),
                'type' => 'checkbox',
                'label' => __('Log all plugin operations', 'officeguy'),
                'default' => 'no'
            ),

        );
        if ((!empty($_GET['og_advanced']) && $_GET['og_advanced'] == '1') || (!empty($Gateway->settings['environment']) && $Gateway->settings['environment'] != 'www'))
        {
            $Fields['environment'] = array(
                'title' => __('Environment', 'officeguy'),
                'type' => 'text',
                'label' => __('SUMIT environment (internal use)', 'officeguy'),
                'description' => __('Do not use this parameter unless explictly requested by SUMIT team for integration testing purposes.', 'officeguy'),
                'default' => 'www'
            );
        }
        $Gateway->form_fields = $Fields;
    }

    static function InitBitFormFields($Gateway)
    {
        $Fields = array(
            'general' => array(
                'title' => __('General Settings', 'officeguy'),
                'type' => 'title',
                'description' => '',
            ),
            'enabled' => array(
                'title' => __('bit', 'officeguy'),
                'type' => 'checkbox',
                'label' => __('Receive Bit payments', 'officeguy'),
                'description' => __('Checking this will enable customers to pay using Bit. Please note this option is only supported for Upay customers', 'officeguy'),
                'default' => 'no'
            ),
            'title' => array(
                'title' => __('Title', 'officeguy'),
                'type' => 'text',
                'description' => __('This controls the title which the user sees during checkout', 'officeguy'),
                'default' => __('bit (SUMIT)', 'officeguy')
            ),
            'description' => array(
                'title' => __('Description', 'officeguy'),
                'type' => 'textarea',
                'description' => __('This controls the description which the user sees during checkout', 'officeguy'),
                'default' => __('Pay using bit via SUMIT.', 'officeguy')
            ),
        );
        $Gateway->form_fields = $Fields;
    }
}
