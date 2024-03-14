<?php
namespace Aws\ComputeOptimizer;

use Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Compute Optimizer** service.
 * @method \Aws\Result deleteRecommendationPreferences(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteRecommendationPreferencesAsync(array $args = [])
 * @method \Aws\Result describeRecommendationExportJobs(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeRecommendationExportJobsAsync(array $args = [])
 * @method \Aws\Result exportAutoScalingGroupRecommendations(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise exportAutoScalingGroupRecommendationsAsync(array $args = [])
 * @method \Aws\Result exportEBSVolumeRecommendations(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise exportEBSVolumeRecommendationsAsync(array $args = [])
 * @method \Aws\Result exportEC2InstanceRecommendations(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise exportEC2InstanceRecommendationsAsync(array $args = [])
 * @method \Aws\Result exportLambdaFunctionRecommendations(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise exportLambdaFunctionRecommendationsAsync(array $args = [])
 * @method \Aws\Result getAutoScalingGroupRecommendations(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getAutoScalingGroupRecommendationsAsync(array $args = [])
 * @method \Aws\Result getEBSVolumeRecommendations(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getEBSVolumeRecommendationsAsync(array $args = [])
 * @method \Aws\Result getEC2InstanceRecommendations(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getEC2InstanceRecommendationsAsync(array $args = [])
 * @method \Aws\Result getEC2RecommendationProjectedMetrics(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getEC2RecommendationProjectedMetricsAsync(array $args = [])
 * @method \Aws\Result getEffectiveRecommendationPreferences(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getEffectiveRecommendationPreferencesAsync(array $args = [])
 * @method \Aws\Result getEnrollmentStatus(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getEnrollmentStatusAsync(array $args = [])
 * @method \Aws\Result getEnrollmentStatusesForOrganization(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getEnrollmentStatusesForOrganizationAsync(array $args = [])
 * @method \Aws\Result getLambdaFunctionRecommendations(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getLambdaFunctionRecommendationsAsync(array $args = [])
 * @method \Aws\Result getRecommendationPreferences(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getRecommendationPreferencesAsync(array $args = [])
 * @method \Aws\Result getRecommendationSummaries(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getRecommendationSummariesAsync(array $args = [])
 * @method \Aws\Result putRecommendationPreferences(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise putRecommendationPreferencesAsync(array $args = [])
 * @method \Aws\Result updateEnrollmentStatus(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise updateEnrollmentStatusAsync(array $args = [])
 */
class ComputeOptimizerClient extends AwsClient {}
