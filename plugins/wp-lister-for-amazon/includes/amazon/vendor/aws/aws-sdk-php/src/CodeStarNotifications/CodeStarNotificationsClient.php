<?php
namespace Aws\CodeStarNotifications;

use Aws\AwsClient;

/**
 * This client is used to interact with the **AWS CodeStar Notifications** service.
 * @method \Aws\Result createNotificationRule(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createNotificationRuleAsync(array $args = [])
 * @method \Aws\Result deleteNotificationRule(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteNotificationRuleAsync(array $args = [])
 * @method \Aws\Result deleteTarget(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteTargetAsync(array $args = [])
 * @method \Aws\Result describeNotificationRule(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeNotificationRuleAsync(array $args = [])
 * @method \Aws\Result listEventTypes(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listEventTypesAsync(array $args = [])
 * @method \Aws\Result listNotificationRules(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listNotificationRulesAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result listTargets(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listTargetsAsync(array $args = [])
 * @method \Aws\Result subscribe(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise subscribeAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result unsubscribe(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise unsubscribeAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise untagResourceAsync(array $args = [])
 * @method \Aws\Result updateNotificationRule(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise updateNotificationRuleAsync(array $args = [])
 */
class CodeStarNotificationsClient extends AwsClient {}
