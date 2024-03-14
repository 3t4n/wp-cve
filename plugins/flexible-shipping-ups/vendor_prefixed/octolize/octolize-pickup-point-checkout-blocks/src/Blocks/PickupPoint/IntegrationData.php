<?php

namespace UpsFreeVendor\Octolize\Blocks\PickupPoint;

class IntegrationData
{
    private string $nonce_name;
    private string $ajax_url;
    private string $ajax_action;
    private string $integration_name;
    private string $meta_data_name;
    private string $flexible_shipping_integration;
    public function get_script_data() : array
    {
        return ['nonce' => \wp_create_nonce($this->nonce_name), 'ajaxUrl' => $this->ajax_url, 'ajaxAction' => $this->ajax_action, 'integrationName' => $this->integration_name, 'fieldName' => $this->meta_data_name, 'flexibleShippingIntegration' => $this->flexible_shipping_integration];
    }
    public function set_nonce_name(string $nonce_name) : \UpsFreeVendor\Octolize\Blocks\PickupPoint\IntegrationData
    {
        $this->nonce_name = $nonce_name;
        return $this;
    }
    public function get_nonce_name() : string
    {
        return $this->nonce_name;
    }
    public function set_ajax_url(string $ajax_url) : \UpsFreeVendor\Octolize\Blocks\PickupPoint\IntegrationData
    {
        $this->ajax_url = $ajax_url;
        return $this;
    }
    public function get_ajax_url() : string
    {
        return $this->ajax_url;
    }
    public function set_ajax_action(string $ajax_action) : \UpsFreeVendor\Octolize\Blocks\PickupPoint\IntegrationData
    {
        $this->ajax_action = $ajax_action;
        return $this;
    }
    public function get_ajax_action() : string
    {
        return $this->ajax_action;
    }
    public function set_integration_name(string $integration_name) : \UpsFreeVendor\Octolize\Blocks\PickupPoint\IntegrationData
    {
        $this->integration_name = $integration_name;
        return $this;
    }
    public function get_integration_name() : string
    {
        return $this->integration_name;
    }
    public function set_meta_data_name(string $meta_data_name) : \UpsFreeVendor\Octolize\Blocks\PickupPoint\IntegrationData
    {
        $this->meta_data_name = $meta_data_name;
        return $this;
    }
    public function get_meta_data_name() : string
    {
        return $this->meta_data_name;
    }
    public function set_flexible_shipping_integration(string $flexible_shipping_integration) : \UpsFreeVendor\Octolize\Blocks\PickupPoint\IntegrationData
    {
        $this->flexible_shipping_integration = $flexible_shipping_integration;
        return $this;
    }
    public function get_flexible_shipping_integration() : string
    {
        return $this->flexible_shipping_integration;
    }
}
