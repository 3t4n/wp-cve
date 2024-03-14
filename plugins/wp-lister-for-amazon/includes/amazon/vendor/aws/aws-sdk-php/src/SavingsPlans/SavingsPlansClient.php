<?php
namespace Aws\SavingsPlans;

use Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Savings Plans** service.
 * @method \Aws\Result createSavingsPlan(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createSavingsPlanAsync(array $args = [])
 * @method \Aws\Result deleteQueuedSavingsPlan(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteQueuedSavingsPlanAsync(array $args = [])
 * @method \Aws\Result describeSavingsPlanRates(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeSavingsPlanRatesAsync(array $args = [])
 * @method \Aws\Result describeSavingsPlans(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeSavingsPlansAsync(array $args = [])
 * @method \Aws\Result describeSavingsPlansOfferingRates(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeSavingsPlansOfferingRatesAsync(array $args = [])
 * @method \Aws\Result describeSavingsPlansOfferings(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeSavingsPlansOfferingsAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise untagResourceAsync(array $args = [])
 */
class SavingsPlansClient extends AwsClient {}
