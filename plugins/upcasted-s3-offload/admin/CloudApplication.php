<?php

/**
 * Class CloudApplication
 */
class CloudApplication
{
    /**
     * @var null
     */
    private static $instance = null;

    /**
     * @param null $accessKeyId
     * @param null $secretAccessKey
     * @param null $region
     * @param null $customEndpoint
     * @return iCloudActions|null
     */
    public static function getInstance($accessKeyId = null, $secretAccessKey = null, $region = null, $customEndpoint = null)
    {
        if (self::$instance == null) {
            if (!empty($accessKeyId) && !empty($secretAccessKey)) {
                update_option(UPCASTED_S3_OFFLOAD_SETTINGS, [
                    UPCASTED_S3_OFFLOAD_ACCESS_KEY_ID => $accessKeyId,
                    UPCASTED_S3_OFFLOAD_SECRET_ACCESS_KEY => $secretAccessKey,
                    UPCASTED_OFFLOAD_REGION => $region,
                    UPCASTED_CUSTOM_ENDPOINT => $customEndpoint
                ]);
                self::$instance = new CloudActions(
                    new AmazonCloudManipulator($accessKeyId, $secretAccessKey, $region, $customEndpoint)
                );
            } else {
                $options = get_option(UPCASTED_S3_OFFLOAD_SETTINGS);
                if (
                    !empty($options[UPCASTED_S3_OFFLOAD_ACCESS_KEY_ID]) &&
                    !empty($options[UPCASTED_S3_OFFLOAD_SECRET_ACCESS_KEY])
                ) {
                    self::$instance = new CloudActions(
                        new AmazonCloudManipulator(
                            $options[UPCASTED_S3_OFFLOAD_ACCESS_KEY_ID],
                            $options[UPCASTED_S3_OFFLOAD_SECRET_ACCESS_KEY],
                            $options[UPCASTED_OFFLOAD_REGION] ?? null,
                            $options[UPCASTED_CUSTOM_ENDPOINT] ?? null
                        ));
                }
            }
        }

        return self::$instance;
    }

    public static function destroy()
    {
        self::$instance = null;
    }
}