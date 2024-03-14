<?php

namespace Prokerala\Astrology\Vendor;

/*
 * This file is part of Prokerala Astrology API PHP SDK
 *
 * © Ennexa Technologies <info@ennexa.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
use Prokerala\Api\Astrology\Location;
use Prokerala\Api\Astrology\Service\InauspiciousPeriod;
use Prokerala\Common\Api\Exception\QuotaExceededException;
use Prokerala\Common\Api\Exception\RateLimitExceededException;
include 'prepend.inc.php';
/** @var \Prokerala\Common\Api\Client $client */
/**
 * InauspiciousPeriod.
 */
$input = ['datetime' => '1967-08-29T09:00:00+05:30', 'latitude' => '19.0821978', 'longitude' => '72.7411014'];
$datetime = new \DateTimeImmutable($input['datetime']);
$tz = $datetime->getTimezone();
$location = new Location($input['latitude'], $input['longitude'], 0, $tz);
try {
    $method = new InauspiciousPeriod($client);
    $result = $method->process($location, $datetime);
    $inauspiciousPeriod = [];
    $arData = $result->getMuhurat();
    foreach ($arData as $idx => $data) {
        $inauspiciousPeriod[$idx] = ['id' => $data->getId(), 'name' => $data->getName(), 'type' => $data->getType()];
        $arPeriod = $data->getPeriod();
        foreach ($arPeriod as $period) {
            $inauspiciousPeriod[$idx]['period'][] = ['start' => $period->getStart(), 'end' => $period->getEnd()];
        }
    }
    \print_r($inauspiciousPeriod);
} catch (QuotaExceededException $e) {
} catch (RateLimitExceededException $e) {
}
