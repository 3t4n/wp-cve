<?php
namespace Aws\Support;

use Aws\AwsClient;

/**
 * AWS Support client.
 *
 * @method \Aws\Result addAttachmentsToSet(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise addAttachmentsToSetAsync(array $args = [])
 * @method \Aws\Result addCommunicationToCase(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise addCommunicationToCaseAsync(array $args = [])
 * @method \Aws\Result createCase(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createCaseAsync(array $args = [])
 * @method \Aws\Result describeAttachment(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeAttachmentAsync(array $args = [])
 * @method \Aws\Result describeCases(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeCasesAsync(array $args = [])
 * @method \Aws\Result describeCommunications(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeCommunicationsAsync(array $args = [])
 * @method \Aws\Result describeServices(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeServicesAsync(array $args = [])
 * @method \Aws\Result describeSeverityLevels(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeSeverityLevelsAsync(array $args = [])
 * @method \Aws\Result describeTrustedAdvisorCheckRefreshStatuses(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeTrustedAdvisorCheckRefreshStatusesAsync(array $args = [])
 * @method \Aws\Result describeTrustedAdvisorCheckResult(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeTrustedAdvisorCheckResultAsync(array $args = [])
 * @method \Aws\Result describeTrustedAdvisorCheckSummaries(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeTrustedAdvisorCheckSummariesAsync(array $args = [])
 * @method \Aws\Result describeTrustedAdvisorChecks(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeTrustedAdvisorChecksAsync(array $args = [])
 * @method \Aws\Result refreshTrustedAdvisorCheck(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise refreshTrustedAdvisorCheckAsync(array $args = [])
 * @method \Aws\Result resolveCase(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise resolveCaseAsync(array $args = [])
 */
class SupportClient extends AwsClient {}
