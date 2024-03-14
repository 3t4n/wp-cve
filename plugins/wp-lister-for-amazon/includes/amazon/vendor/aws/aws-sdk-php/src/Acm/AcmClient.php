<?php
namespace Aws\Acm;

use Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Certificate Manager** service.
 *
 * @method \Aws\Result addTagsToCertificate(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise addTagsToCertificateAsync(array $args = [])
 * @method \Aws\Result deleteCertificate(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteCertificateAsync(array $args = [])
 * @method \Aws\Result describeCertificate(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeCertificateAsync(array $args = [])
 * @method \Aws\Result exportCertificate(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise exportCertificateAsync(array $args = [])
 * @method \Aws\Result getAccountConfiguration(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getAccountConfigurationAsync(array $args = [])
 * @method \Aws\Result getCertificate(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getCertificateAsync(array $args = [])
 * @method \Aws\Result importCertificate(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise importCertificateAsync(array $args = [])
 * @method \Aws\Result listCertificates(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listCertificatesAsync(array $args = [])
 * @method \Aws\Result listTagsForCertificate(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listTagsForCertificateAsync(array $args = [])
 * @method \Aws\Result putAccountConfiguration(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise putAccountConfigurationAsync(array $args = [])
 * @method \Aws\Result removeTagsFromCertificate(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise removeTagsFromCertificateAsync(array $args = [])
 * @method \Aws\Result renewCertificate(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise renewCertificateAsync(array $args = [])
 * @method \Aws\Result requestCertificate(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise requestCertificateAsync(array $args = [])
 * @method \Aws\Result resendValidationEmail(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise resendValidationEmailAsync(array $args = [])
 * @method \Aws\Result updateCertificateOptions(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise updateCertificateOptionsAsync(array $args = [])
 */
class AcmClient extends AwsClient {}
