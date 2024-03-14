<?php
namespace Aws\ServiceQuotas;

use Aws\AwsClient;

/**
 * This client is used to interact with the **Service Quotas** service.
 * @method \Aws\Result associateServiceQuotaTemplate(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise associateServiceQuotaTemplateAsync(array $args = [])
 * @method \Aws\Result deleteServiceQuotaIncreaseRequestFromTemplate(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteServiceQuotaIncreaseRequestFromTemplateAsync(array $args = [])
 * @method \Aws\Result disassociateServiceQuotaTemplate(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise disassociateServiceQuotaTemplateAsync(array $args = [])
 * @method \Aws\Result getAWSDefaultServiceQuota(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getAWSDefaultServiceQuotaAsync(array $args = [])
 * @method \Aws\Result getAssociationForServiceQuotaTemplate(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getAssociationForServiceQuotaTemplateAsync(array $args = [])
 * @method \Aws\Result getRequestedServiceQuotaChange(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getRequestedServiceQuotaChangeAsync(array $args = [])
 * @method \Aws\Result getServiceQuota(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getServiceQuotaAsync(array $args = [])
 * @method \Aws\Result getServiceQuotaIncreaseRequestFromTemplate(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getServiceQuotaIncreaseRequestFromTemplateAsync(array $args = [])
 * @method \Aws\Result listAWSDefaultServiceQuotas(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listAWSDefaultServiceQuotasAsync(array $args = [])
 * @method \Aws\Result listRequestedServiceQuotaChangeHistory(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listRequestedServiceQuotaChangeHistoryAsync(array $args = [])
 * @method \Aws\Result listRequestedServiceQuotaChangeHistoryByQuota(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listRequestedServiceQuotaChangeHistoryByQuotaAsync(array $args = [])
 * @method \Aws\Result listServiceQuotaIncreaseRequestsInTemplate(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listServiceQuotaIncreaseRequestsInTemplateAsync(array $args = [])
 * @method \Aws\Result listServiceQuotas(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listServiceQuotasAsync(array $args = [])
 * @method \Aws\Result listServices(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listServicesAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result putServiceQuotaIncreaseRequestIntoTemplate(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise putServiceQuotaIncreaseRequestIntoTemplateAsync(array $args = [])
 * @method \Aws\Result requestServiceQuotaIncrease(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise requestServiceQuotaIncreaseAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise untagResourceAsync(array $args = [])
 */
class ServiceQuotasClient extends AwsClient {}
