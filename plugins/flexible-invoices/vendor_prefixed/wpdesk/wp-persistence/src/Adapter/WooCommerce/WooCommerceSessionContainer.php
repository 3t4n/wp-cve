<?php

namespace WPDeskFIVendor\WPDesk\Persistence\Adapter\WooCommerce;

use WPDeskFIVendor\WPDesk\Persistence\ElementNotExistsException;
use WPDeskFIVendor\WPDesk\Persistence\FallbackFromGetTrait;
use WPDeskFIVendor\WPDesk\Persistence\PersistentContainer;
/**
 * @package WPDesk\Persistence\WooCommerce
 */
final class WooCommerceSessionContainer implements \WPDeskFIVendor\WPDesk\Persistence\PersistentContainer
{
    use FallbackFromGetTrait;
    /** @var \WC_Settings_API */
    private $session;
    public function __construct(\WC_Session $session)
    {
        $this->session = $session;
    }
    public function get($id)
    {
        if (!$this->has($id)) {
            throw new \WPDeskFIVendor\WPDesk\Persistence\ElementNotExistsException(\sprintf('Element %s not exists!', $id));
        }
        return $this->session->get($id);
    }
    public function has($id) : bool
    {
        $value = $this->session->get($id);
        return $value !== null;
    }
    public function set(string $id, $value)
    {
        $this->session->set($id, $value);
    }
    public function delete(string $id)
    {
        $this->session->set($id, null);
    }
}
