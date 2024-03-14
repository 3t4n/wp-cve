<?php

declare(strict_types=1);

namespace WcMipConnector\Model;

class ShippingServiceDelay
{
    private const DELAY_DAYS_1 = 1;
    private const DELAY_DAYS_2 = 2;
    private const INDEX_DELAY_MIN = 0;
    private const INDEX_DELAY_MAX = 1;
    private const DELAY_NAME_24H = '24 h';
    private const DELAY_NAME_48H = '48 h';
    private const DELAY_NAME_DATA_DELIMITER = '-';

    /**
     * @var string
     */
    protected $shippingDaysMin;

    /**
     * @var int
     */
    protected $shippingDaysMax;

    /**
     * @param int $min
     * @param int $max
     */
    private function __construct(int $min, int $max)
    {
        $this->shippingDaysMin = $min;
        $this->shippingDaysMax = $max;
    }

    public static function fromMinAndMAx(int $min, int $max): ShippingServiceDelay
    {
        return new self($min, $max);
    }

    public static function fromString($delayName): ShippingServiceDelay
    {
        $delay = self::sanitize($delayName);

        if ($delay === self::DELAY_NAME_24H) {
            return new self(self::DELAY_DAYS_1, self::DELAY_DAYS_1);
        }

        if ($delay === self::DELAY_NAME_48H) {
            return new self(self::DELAY_DAYS_2, self::DELAY_DAYS_2);
        }

        $delayNameData = \explode(self::DELAY_NAME_DATA_DELIMITER, $delay);

        if (isset($delayNameData[self::INDEX_DELAY_MIN], $delayNameData[self::INDEX_DELAY_MAX])) {
            $minDelay = (int)$delayNameData[self::INDEX_DELAY_MIN];
            $maxDelay = (int)$delayNameData[self::INDEX_DELAY_MAX];

            return new self($minDelay, $maxDelay);
        }

        if (isset($delayNameData[self::INDEX_DELAY_MIN])) {
            $minDelay = (int)$delayNameData[self::INDEX_DELAY_MIN];
            $maxDelay = (int)$delayNameData[self::INDEX_DELAY_MIN];

            return new self($minDelay, $maxDelay);
        }

        return new self(self::DELAY_DAYS_1, self::DELAY_DAYS_1);
    }

    /**
     * @return int
     */
    public function getShippingDaysMin(): int
    {
        return $this->shippingDaysMin;
    }

    /**
     * @return int
     */
    public function getShippingDaysMax(): int
    {
        return $this->shippingDaysMax;
    }

    private static function sanitize(string $delayName): string
    {
        return \trim(\str_replace(['days', 'Sea service:'], '', $delayName));
    }

    public function getDelay(): string
    {
        return $this->shippingDaysMin.'-'.$this->shippingDaysMax;
    }

    public function __toString()
    {
        return $this->shippingDaysMin.' - '.$this->shippingDaysMax;
    }
}