<?php
namespace Aws\ApplicationAutoScaling;

use Aws\AwsClient;

/**
 * This client is used to interact with the **Application Auto Scaling** service.
 * @method \Aws\Result deleteScalingPolicy(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteScalingPolicyAsync(array $args = [])
 * @method \Aws\Result deleteScheduledAction(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteScheduledActionAsync(array $args = [])
 * @method \Aws\Result deregisterScalableTarget(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deregisterScalableTargetAsync(array $args = [])
 * @method \Aws\Result describeScalableTargets(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeScalableTargetsAsync(array $args = [])
 * @method \Aws\Result describeScalingActivities(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeScalingActivitiesAsync(array $args = [])
 * @method \Aws\Result describeScalingPolicies(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeScalingPoliciesAsync(array $args = [])
 * @method \Aws\Result describeScheduledActions(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeScheduledActionsAsync(array $args = [])
 * @method \Aws\Result putScalingPolicy(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise putScalingPolicyAsync(array $args = [])
 * @method \Aws\Result putScheduledAction(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise putScheduledActionAsync(array $args = [])
 * @method \Aws\Result registerScalableTarget(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise registerScalableTargetAsync(array $args = [])
 */
class ApplicationAutoScalingClient extends AwsClient {}
