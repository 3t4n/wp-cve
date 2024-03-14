<?php

namespace FRFreeVendor\WPDesk\DeactivationModal\Model;

use FRFreeVendor\WPDesk\DeactivationModal\Exception\DuplicatedFormOptionKeyException;
use FRFreeVendor\WPDesk\DeactivationModal\Exception\ReservedFormOptionKeyException;
use FRFreeVendor\WPDesk\DeactivationModal\Exception\UnknownFormOptionKeyException;
use FRFreeVendor\WPDesk\DeactivationModal\Sender\DataWpdeskSender;
/**
 * Manages the list of deactivation reason in the form.
 */
class FormOptions
{
    /**
     * @var FormOption[]
     */
    private $options = [];
    /**
     * @param FormOption $new_option .
     *
     * @throws DuplicatedFormOptionKeyException
     * @throws ReservedFormOptionKeyException
     */
    public function set_option(\FRFreeVendor\WPDesk\DeactivationModal\Model\FormOption $new_option) : self
    {
        if ($new_option->get_key() === \FRFreeVendor\WPDesk\DeactivationModal\Sender\DataWpdeskSender::NO_REASON_CHOSEN_KEY) {
            throw new \FRFreeVendor\WPDesk\DeactivationModal\Exception\ReservedFormOptionKeyException(\FRFreeVendor\WPDesk\DeactivationModal\Sender\DataWpdeskSender::NO_REASON_CHOSEN_KEY);
        }
        foreach ($this->options as $option) {
            if ($option->get_key() === $new_option->get_key()) {
                throw new \FRFreeVendor\WPDesk\DeactivationModal\Exception\DuplicatedFormOptionKeyException($new_option->get_key());
            }
        }
        $this->options[] = $new_option;
        return $this;
    }
    /**
     * @param string $option_key .
     *
     * @throws UnknownFormOptionKeyException
     */
    public function delete_option(string $option_key) : self
    {
        foreach ($this->options as $option_index => $option) {
            if ($option->get_key() === $option_key) {
                unset($this->options[$option_index]);
                return $this;
            }
        }
        throw new \FRFreeVendor\WPDesk\DeactivationModal\Exception\UnknownFormOptionKeyException($option_key);
    }
    /**
     * @param string   $option_key      .
     * @param callable $update_callback Example: "function ( FormOption $option ) { }".
     *
     * @throws UnknownFormOptionKeyException
     */
    public function update_option(string $option_key, callable $update_callback) : self
    {
        foreach ($this->options as $option) {
            if ($option->get_key() === $option_key) {
                \call_user_func($update_callback, $option);
                return $this;
            }
        }
        throw new \FRFreeVendor\WPDesk\DeactivationModal\Exception\UnknownFormOptionKeyException($option_key);
    }
    /**
     * @return FormOption[]
     */
    public function get_options() : array
    {
        $options = $this->options;
        \usort($options, function (\FRFreeVendor\WPDesk\DeactivationModal\Model\FormOption $option_a, \FRFreeVendor\WPDesk\DeactivationModal\Model\FormOption $option_b) {
            return $option_a->get_priority() <=> $option_b->get_priority();
        });
        return $options;
    }
}
