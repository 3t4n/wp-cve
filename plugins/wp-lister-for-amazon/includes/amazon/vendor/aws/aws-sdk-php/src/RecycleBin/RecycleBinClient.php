<?php
namespace Aws\RecycleBin;

use Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon Recycle Bin** service.
 * @method \Aws\Result createRule(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createRuleAsync(array $args = [])
 * @method \Aws\Result deleteRule(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteRuleAsync(array $args = [])
 * @method \Aws\Result getRule(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getRuleAsync(array $args = [])
 * @method \Aws\Result listRules(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listRulesAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise untagResourceAsync(array $args = [])
 * @method \Aws\Result updateRule(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise updateRuleAsync(array $args = [])
 */
class RecycleBinClient extends AwsClient {}
