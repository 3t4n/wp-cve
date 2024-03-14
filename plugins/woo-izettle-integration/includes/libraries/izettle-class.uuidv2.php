<?php

class IZ_UUID_V2
{

    /**
     * @var string
     */
    private $uuid;

    public function __construct(string $uuid)
    {
        $this->uuid = $uuid;
    }

    public static function get_uuid_from_wc_product($product)
    {
        if ($product->get_date_created() && $product->get_id()){
            $identifier = (int) $product->get_date_created()->format('U') + (int) $product->get_id();

            if ($product instanceof WC_Product_Simple) {
                $identifier += 1;
            }
    
            $uuid_instance = new self(
                self::generate($identifier)
            );

            return $uuid_instance->__toString();
        } else {
            return false;
        }
    }

    public function __toString(): string
    {
        return $this->uuid;
    }
    
    private static function generate(int $identifier): string
    {
        $identifier = self::hexTimestamp($identifier);

        $clockSeq = 0;

        $node = substr(md5(home_url()), -10);

        return sprintf(
            '%08s-%04s-1%03s-%04x-%012s',
            substr($identifier, -8),
            substr($identifier, -12, 4),
            substr($identifier, -15, 3),
            $clockSeq | 0x8000,
            $node
        );
    }

    private static function hexTimestamp(int $timestamp): string
    {
        if (PHP_INT_SIZE >= 8) {
            return str_pad(
                dechex($timestamp),
                16,
                '0',
                STR_PAD_LEFT
            );
        }

        return bin2hex(
            str_pad(self::toBinary((string) $timestamp), 8, "\0", STR_PAD_LEFT)
        );
    }

    private static function toBinary(string $digits): string
    {
        $bytes = '';
        $count = strlen($digits);

        while ($count) {
            $quotient = [];
            $remainder = 0;

            for ($i = 0; $i !== $count; ++$i) {
                $carry = $digits[$i] + $remainder * 10;
                $digit = $carry >> 8;
                $remainder = $carry & 0xFF;

                if ($digit || $quotient) {
                    $quotient[] = $digit;
                }
            }

            $bytes = chr($remainder) . $bytes;
            $count = count($digits = $quotient);
        }

        return $bytes;
    }
}