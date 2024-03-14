<?php
namespace Aws\signer;

use Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Signer** service.
 * @method \Aws\Result addProfilePermission(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise addProfilePermissionAsync(array $args = [])
 * @method \Aws\Result cancelSigningProfile(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise cancelSigningProfileAsync(array $args = [])
 * @method \Aws\Result describeSigningJob(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeSigningJobAsync(array $args = [])
 * @method \Aws\Result getSigningPlatform(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getSigningPlatformAsync(array $args = [])
 * @method \Aws\Result getSigningProfile(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getSigningProfileAsync(array $args = [])
 * @method \Aws\Result listProfilePermissions(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listProfilePermissionsAsync(array $args = [])
 * @method \Aws\Result listSigningJobs(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listSigningJobsAsync(array $args = [])
 * @method \Aws\Result listSigningPlatforms(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listSigningPlatformsAsync(array $args = [])
 * @method \Aws\Result listSigningProfiles(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listSigningProfilesAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result putSigningProfile(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise putSigningProfileAsync(array $args = [])
 * @method \Aws\Result removeProfilePermission(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise removeProfilePermissionAsync(array $args = [])
 * @method \Aws\Result revokeSignature(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise revokeSignatureAsync(array $args = [])
 * @method \Aws\Result revokeSigningProfile(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise revokeSigningProfileAsync(array $args = [])
 * @method \Aws\Result startSigningJob(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise startSigningJobAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise untagResourceAsync(array $args = [])
 */
class signerClient extends AwsClient {}
