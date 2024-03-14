<?php
class OfficeGuyDonation
{
    public static function CartContainsDonation()
    {
        $ProductIDs = OfficeGuySubscriptions::GetCartProductIDs();
        foreach ($ProductIDs as $ProductID)
        {
            if (get_post_meta($ProductID, 'OfficeGuyDonation', true) === 'yes')
                return true;
        }
        return false;
    }

    public static function OrderContainsDonation($Order)
    {
        foreach ($Order->get_items() as $OrderItem)
        {
            if (get_post_meta($OrderItem['product_id'], 'OfficeGuyDonation', true) === 'yes')
                return true;
        }
        return false;
    }
    public static function CartContainsNonDonation()
    {       
        $ProductIDs = OfficeGuySubscriptions::GetCartProductIDs();      
        foreach ($ProductIDs as $ProductID)
        {
            if (get_post_meta($ProductID, 'OfficeGuyDonation', true) != 'yes')
                return true;
        }
        return false;
    }

    public static function AddProductFields()
    {
        global $woocommerce, $post;
        $Value = get_post_meta($post->ID, 'OfficeGuyDonation', true) ? get_post_meta($post->ID, 'OfficeGuyDonation', true) : 'no';

        echo '<div>';
        woocommerce_wp_checkbox(
            array(
                'id' => 'OfficeGuyDonation',
                'label' => __('SUMIT Donation', 'officeguy'),
                'description' => __('Only available for non-profit organizations.', 'officeguy'),
                'value'         => $Value,
                'desc_tip'    => 'true'
            )
        );
        echo '</div>';

        echo '<style>.general_options  { display: block !important; }</style>';
    }

    public static function SaveProductFields($PostID)
    {
        update_post_meta($PostID, 'OfficeGuyDonation', isset($_POST['OfficeGuyDonation']) ? 'yes' : 'no');
    }

    public static function UpdateAvailableGateways($AvailableGateways)
    {
        if (empty($AvailableGateways) || !isset($AvailableGateways['officeguy']) || is_admin() || count(OfficeGuySubscriptions::GetCartProductIDs()) == 0)
            return $AvailableGateways;

        if (OfficeGuyDonation::CartContainsDonation() && OfficeGuyDonation::CartContainsNonDonation())
        {
            unset($AvailableGateways['officeguy']);
            if (isset($AvailableGateways['officeguybit']))
                unset($AvailableGateways['officeguybit']);
        }
        
        return $AvailableGateways;
    }
}

add_action('woocommerce_product_options_general_product_data', 'OfficeGuyDonation::AddProductFields');
add_action('woocommerce_process_product_meta', 'OfficeGuyDonation::SaveProductFields');
add_filter('woocommerce_available_payment_gateways', 'OfficeGuyDonation::UpdateAvailableGateways');
