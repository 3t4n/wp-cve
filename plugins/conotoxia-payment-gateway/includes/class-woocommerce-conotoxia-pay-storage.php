<?php

use CKPL\Pay\Storage\AbstractStorage;
use CKPL\Pay\Storage\StorageInterface;

/**
 * Class WC_Gateway_Conotoxia_Pay_Storage
 */
class WC_Gateway_Conotoxia_Pay_Storage extends AbstractStorage
{
    /**
     * WC_Gateway_Conotoxia_Pay_Storage constructor.
     */
    public function __construct()
    {
        $this->items = [];

        $this->load();
    }

    /**
     * @param string $key
     * @return bool
     */
    public function hasItem(string $key): bool
    {
        return array_key_exists($key, $this->items);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function setItem(string $key, $value): void
    {
        if ($key === StorageInterface::TOKEN || $key === StorageInterface::PAYMENT_SERVICE_PUBLIC_KEYS) {
            update_option(
                CONOTOXIA_PAY . '-' . $key,
                json_encode($value, JSON_PRESERVE_ZERO_FRACTION | JSON_UNESCAPED_SLASHES)
            );

            $this->items[$key] = $value;
        } else {
            update_option(CONOTOXIA_PAY . '-' . $key, (string)$value);

            $this->items[$key] = (string)$value;
        }
    }

    /**
     * @return void
     */
    public function clear(): void
    {
        update_option(CONOTOXIA_PAY . '-' . StorageInterface::PAYMENT_SERVICE_PUBLIC_KEYS, '');
        update_option(CONOTOXIA_PAY . '-' . StorageInterface::PUBLIC_KEY_CHECKSUM, '');
        update_option(CONOTOXIA_PAY . '-' . StorageInterface::PUBLIC_KEY_ID, '');
        update_option(CONOTOXIA_PAY . '-' . StorageInterface::TOKEN, '');
    }

    /**
     * @return void
     */
    protected function load(): void
    {
        $arrayItems = [StorageInterface::TOKEN, StorageInterface::PAYMENT_SERVICE_PUBLIC_KEYS];
        $stringItems = [StorageInterface::PUBLIC_KEY_ID, StorageInterface::PUBLIC_KEY_CHECKSUM];

        foreach ($arrayItems as $arrayItem) {
            if (!empty(get_option(CONOTOXIA_PAY . '-' . $arrayItem))) {
                $this->items[$arrayItem] = json_decode(get_option(CONOTOXIA_PAY . '-' . $arrayItem), true);
            }
        }

        foreach ($stringItems as $stringItem) {
            if (!empty(get_option(CONOTOXIA_PAY . '-' . $stringItem))) {
                $this->items[$stringItem] = get_option(CONOTOXIA_PAY . '-' . $stringItem);
            }
        }
    }
}
