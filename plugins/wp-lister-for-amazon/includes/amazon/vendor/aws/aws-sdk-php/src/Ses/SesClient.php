<?php
namespace Aws\Ses;

use Aws\Api\ApiProvider;
use Aws\Api\DocModel;
use Aws\Api\Service;
use Aws\Credentials\CredentialsInterface;

/**
 * This client is used to interact with the **Amazon Simple Email Service (Amazon SES)**.
 *
 * @method \Aws\Result cloneReceiptRuleSet(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise cloneReceiptRuleSetAsync(array $args = [])
 * @method \Aws\Result createConfigurationSet(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createConfigurationSetAsync(array $args = [])
 * @method \Aws\Result createConfigurationSetEventDestination(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createConfigurationSetEventDestinationAsync(array $args = [])
 * @method \Aws\Result createConfigurationSetTrackingOptions(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createConfigurationSetTrackingOptionsAsync(array $args = [])
 * @method \Aws\Result createCustomVerificationEmailTemplate(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createCustomVerificationEmailTemplateAsync(array $args = [])
 * @method \Aws\Result createReceiptFilter(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createReceiptFilterAsync(array $args = [])
 * @method \Aws\Result createReceiptRule(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createReceiptRuleAsync(array $args = [])
 * @method \Aws\Result createReceiptRuleSet(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createReceiptRuleSetAsync(array $args = [])
 * @method \Aws\Result createTemplate(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createTemplateAsync(array $args = [])
 * @method \Aws\Result deleteConfigurationSet(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteConfigurationSetAsync(array $args = [])
 * @method \Aws\Result deleteConfigurationSetEventDestination(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteConfigurationSetEventDestinationAsync(array $args = [])
 * @method \Aws\Result deleteConfigurationSetTrackingOptions(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteConfigurationSetTrackingOptionsAsync(array $args = [])
 * @method \Aws\Result deleteCustomVerificationEmailTemplate(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteCustomVerificationEmailTemplateAsync(array $args = [])
 * @method \Aws\Result deleteIdentity(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteIdentityAsync(array $args = [])
 * @method \Aws\Result deleteIdentityPolicy(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteIdentityPolicyAsync(array $args = [])
 * @method \Aws\Result deleteReceiptFilter(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteReceiptFilterAsync(array $args = [])
 * @method \Aws\Result deleteReceiptRule(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteReceiptRuleAsync(array $args = [])
 * @method \Aws\Result deleteReceiptRuleSet(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteReceiptRuleSetAsync(array $args = [])
 * @method \Aws\Result deleteTemplate(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteTemplateAsync(array $args = [])
 * @method \Aws\Result deleteVerifiedEmailAddress(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteVerifiedEmailAddressAsync(array $args = [])
 * @method \Aws\Result describeActiveReceiptRuleSet(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeActiveReceiptRuleSetAsync(array $args = [])
 * @method \Aws\Result describeConfigurationSet(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeConfigurationSetAsync(array $args = [])
 * @method \Aws\Result describeReceiptRule(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeReceiptRuleAsync(array $args = [])
 * @method \Aws\Result describeReceiptRuleSet(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeReceiptRuleSetAsync(array $args = [])
 * @method \Aws\Result getAccountSendingEnabled(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getAccountSendingEnabledAsync(array $args = [])
 * @method \Aws\Result getCustomVerificationEmailTemplate(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getCustomVerificationEmailTemplateAsync(array $args = [])
 * @method \Aws\Result getIdentityDkimAttributes(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getIdentityDkimAttributesAsync(array $args = [])
 * @method \Aws\Result getIdentityMailFromDomainAttributes(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getIdentityMailFromDomainAttributesAsync(array $args = [])
 * @method \Aws\Result getIdentityNotificationAttributes(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getIdentityNotificationAttributesAsync(array $args = [])
 * @method \Aws\Result getIdentityPolicies(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getIdentityPoliciesAsync(array $args = [])
 * @method \Aws\Result getIdentityVerificationAttributes(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getIdentityVerificationAttributesAsync(array $args = [])
 * @method \Aws\Result getSendQuota(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getSendQuotaAsync(array $args = [])
 * @method \Aws\Result getSendStatistics(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getSendStatisticsAsync(array $args = [])
 * @method \Aws\Result getTemplate(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getTemplateAsync(array $args = [])
 * @method \Aws\Result listConfigurationSets(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listConfigurationSetsAsync(array $args = [])
 * @method \Aws\Result listCustomVerificationEmailTemplates(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listCustomVerificationEmailTemplatesAsync(array $args = [])
 * @method \Aws\Result listIdentities(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listIdentitiesAsync(array $args = [])
 * @method \Aws\Result listIdentityPolicies(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listIdentityPoliciesAsync(array $args = [])
 * @method \Aws\Result listReceiptFilters(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listReceiptFiltersAsync(array $args = [])
 * @method \Aws\Result listReceiptRuleSets(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listReceiptRuleSetsAsync(array $args = [])
 * @method \Aws\Result listTemplates(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listTemplatesAsync(array $args = [])
 * @method \Aws\Result listVerifiedEmailAddresses(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listVerifiedEmailAddressesAsync(array $args = [])
 * @method \Aws\Result putConfigurationSetDeliveryOptions(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise putConfigurationSetDeliveryOptionsAsync(array $args = [])
 * @method \Aws\Result putIdentityPolicy(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise putIdentityPolicyAsync(array $args = [])
 * @method \Aws\Result reorderReceiptRuleSet(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise reorderReceiptRuleSetAsync(array $args = [])
 * @method \Aws\Result sendBounce(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise sendBounceAsync(array $args = [])
 * @method \Aws\Result sendBulkTemplatedEmail(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise sendBulkTemplatedEmailAsync(array $args = [])
 * @method \Aws\Result sendCustomVerificationEmail(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise sendCustomVerificationEmailAsync(array $args = [])
 * @method \Aws\Result sendEmail(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise sendEmailAsync(array $args = [])
 * @method \Aws\Result sendRawEmail(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise sendRawEmailAsync(array $args = [])
 * @method \Aws\Result sendTemplatedEmail(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise sendTemplatedEmailAsync(array $args = [])
 * @method \Aws\Result setActiveReceiptRuleSet(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise setActiveReceiptRuleSetAsync(array $args = [])
 * @method \Aws\Result setIdentityDkimEnabled(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise setIdentityDkimEnabledAsync(array $args = [])
 * @method \Aws\Result setIdentityFeedbackForwardingEnabled(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise setIdentityFeedbackForwardingEnabledAsync(array $args = [])
 * @method \Aws\Result setIdentityHeadersInNotificationsEnabled(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise setIdentityHeadersInNotificationsEnabledAsync(array $args = [])
 * @method \Aws\Result setIdentityMailFromDomain(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise setIdentityMailFromDomainAsync(array $args = [])
 * @method \Aws\Result setIdentityNotificationTopic(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise setIdentityNotificationTopicAsync(array $args = [])
 * @method \Aws\Result setReceiptRulePosition(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise setReceiptRulePositionAsync(array $args = [])
 * @method \Aws\Result testRenderTemplate(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise testRenderTemplateAsync(array $args = [])
 * @method \Aws\Result updateAccountSendingEnabled(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise updateAccountSendingEnabledAsync(array $args = [])
 * @method \Aws\Result updateConfigurationSetEventDestination(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise updateConfigurationSetEventDestinationAsync(array $args = [])
 * @method \Aws\Result updateConfigurationSetReputationMetricsEnabled(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise updateConfigurationSetReputationMetricsEnabledAsync(array $args = [])
 * @method \Aws\Result updateConfigurationSetSendingEnabled(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise updateConfigurationSetSendingEnabledAsync(array $args = [])
 * @method \Aws\Result updateConfigurationSetTrackingOptions(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise updateConfigurationSetTrackingOptionsAsync(array $args = [])
 * @method \Aws\Result updateCustomVerificationEmailTemplate(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise updateCustomVerificationEmailTemplateAsync(array $args = [])
 * @method \Aws\Result updateReceiptRule(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise updateReceiptRuleAsync(array $args = [])
 * @method \Aws\Result updateTemplate(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise updateTemplateAsync(array $args = [])
 * @method \Aws\Result verifyDomainDkim(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise verifyDomainDkimAsync(array $args = [])
 * @method \Aws\Result verifyDomainIdentity(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise verifyDomainIdentityAsync(array $args = [])
 * @method \Aws\Result verifyEmailAddress(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise verifyEmailAddressAsync(array $args = [])
 * @method \Aws\Result verifyEmailIdentity(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise verifyEmailIdentityAsync(array $args = [])
 */
class SesClient extends \Aws\AwsClient
{
    /**
     * @deprecated This method will no longer work due to the deprecation of
     *             V2 credentials with SES as of 03/25/2021
     * Create an SMTP password for a given IAM user's credentials.
     *
     * The SMTP username is the Access Key ID for the provided credentials.
     *
     * @link http://docs.aws.amazon.com/ses/latest/DeveloperGuide/smtp-credentials.html#smtp-credentials-convert
     *
     * @param CredentialsInterface $creds
     *
     * @return string
     */
    public static function generateSmtpPassword(CredentialsInterface $creds)
    {
        static $version = "\x02";
        static $algo = 'sha256';
        static $message = 'SendRawEmail';
        $signature = hash_hmac($algo, $message, $creds->getSecretKey(), true);

        return base64_encode($version . $signature);
    }

    /**
     * Create an SMTP password for a given IAM user's credentials.
     *
     * The SMTP username is the Access Key ID for the provided credentials. This
     * utility method is not guaranteed to work indefinitely and is provided as
     * a convenience to customers using the generateSmtpPassword method.  It is
     * not recommended for use in production
     *
     * @link https://docs.aws.amazon.com/ses/latest/DeveloperGuide/smtp-credentials.html#smtp-credentials-convert
     *
     * @param CredentialsInterface $creds
     * @param string $region
     *
     * @return string
     */
    public static function generateSmtpPasswordV4(CredentialsInterface $creds, $region)
    {
        $key = $creds->getSecretKey();

        $date = "11111111";
        $service = "ses";
        $terminal = "aws4_request";
        $message = "SendRawEmail";
        $version = 0x04;

        $signature = self::sign($date, "AWS4" . $key);
        $signature = self::sign($region, $signature);
        $signature = self::sign($service, $signature);
        $signature = self::sign($terminal, $signature);
        $signature = self::sign($message, $signature);
        $signatureAndVersion = pack('c', $version) . $signature;

        return  base64_encode($signatureAndVersion);
    }

    private static function sign($key, $message) {
        return hash_hmac('sha256', $key, $message, true);
    }

    /**
     * @internal
     * @codeCoverageIgnore
     */
    public static function applyDocFilters(array $api, array $docs)
    {
        $b64 = '<div class="alert alert-info">This value will be base64 encoded on your behalf.</div>';

        $docs['shapes']['RawMessage']['append'] = $b64;

        return [
            new Service($api, ApiProvider::defaultProvider()),
            new DocModel($docs)
        ];
    }
}
