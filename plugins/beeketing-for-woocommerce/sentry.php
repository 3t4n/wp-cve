<?php
// Init sentry
if (defined('BEEKETINGWOOCOMMERCE_SENTRY') && BEEKETINGWOOCOMMERCE_SENTRY) {
    $sentryClient = new SentryClient(BEEKETINGWOOCOMMERCE_SENTRY);
    $errorHandler = new Raven_ErrorHandler($sentryClient);
    $errorHandler->registerExceptionHandler();
    $errorHandler->registerErrorHandler();
    $errorHandler->registerShutdownFunction();

    $loader = \BeeketingConnect_beeketing_woocommerce\Platforms\WooCommerce\Plugin\Loader::instance();
    $loader->ravenClient = $sentryClient;

    // Set error params
    $sentryClient->setEnvironment(BEEKETINGWOOCOMMERCE_ENVIRONMENT);
    $sentryClient->setRelease(BEEKETINGWOOCOMMERCE_VERSION);

    // Only push sentry message for beeketing
    $sentryClient->setSendCallback(function ($data) {
        $hasBeeketing = false;
        if (isset($data['exception']['values'][0]['stacktrace']['frames'])) {
            $stacktraceFrames = $data['exception']['values'][0]['stacktrace']['frames'];
            if (is_array($stacktraceFrames)) {
                foreach ($stacktraceFrames as $stacktraceFrame) {
                    if (
                        isset($stacktraceFrame['filename']) &&
                        strpos($stacktraceFrame['filename'], 'sentry/sentry') === false &&
                        strpos($stacktraceFrame['filename'], 'sentry\sentry') === false &&
                        strpos($stacktraceFrame['filename'], 'beeketing') !== false
                    ) {
                        $hasBeeketing = true;
                    }
                }
            }
        }

        if (!$hasBeeketing) {
            return false;
        }

        return true;
    });
}
