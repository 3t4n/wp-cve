<?php

namespace CODNetwork\Services;

use Throwable;
use WP_Http;
use WP_Error;

class CODN_Slack_Service
{
    const SEND_MAX_LOG_TO_SLACK = 5;

    /** @var CODN_Logger_Service */
    private $logger;

    /**
     * @return array|WP_Error
     */
    public function sendAttachemetns(array $attachments)
    {
        $headers = [];
        $headers[] = 'Content-Type: application/json';
        $response = wp_remote_post(
            codn_get_url_hooks_slack(),
            [
                'method' => 'POST',
                'headers' => $headers,
                'httpversion' => '1.0',
                'sslverify' => false,
                'body' => json_encode($attachments)
            ]
        );

        return $response;
    }

    /**
     * formatting and return array attachments
     * @param array $log
     * @return array
     */
    public function formattingAttachemetns(array $log): array
    {
        $block = [];

        foreach ($log as $key => $value) {
            $block [] = [
                'type' => 'section',
                'text' => [
                    'type' => 'mrkdwn',
                    'text' => sprintf('*%s*%s%s%s', $key, PHP_EOL, $value, PHP_EOL)
                ]
            ];
        }

        return [
            'attachments' => [
                [
                    'color' => '#2EB67D',
                    'blocks' => $block
                ]
            ]
        ];
    }

    /**
     * @param array $logs
     * @return bool|WP_Error
     */
    function sendMessage(array $logs)
    {
        try {
            $this->logger = new CODN_Logger_Service();
            $index = 0;

            foreach ($logs as $key => $log) {
                if ($index == self::SEND_MAX_LOG_TO_SLACK) {
                    break;
                }

                $log = json_decode($log, true);
                if (!is_array($log)) {
                    return true;
                }

                $attachments = $this->formattingAttachemetns($log);
                $response = $this->sendAttachemetns($attachments);
                $responseCode = (int) wp_remote_retrieve_response_code($response);

                if ($responseCode != WP_Http::OK) {
                    $responseBody = wp_remote_retrieve_body($response);
                    $this->logger->error(
                        sprintf('error while pushing logs in slack message:%s', $response->get_error_code()),
                        [
                            'extra.message' => $response->get_error_message(),
                            'trace' => __FILE__
                        ]
                    );

                    return new WP_Error(
                        $responseCode,
                        $responseBody,
                        ['status' => $responseCode]
                    );
                }

                if (is_wp_error($response)) {
                    $errorMessage = $response->get_error_message();
                    $this->logger->error(
                        'error while pushing logs in slack message',
                        [
                            'extra.message' => $errorMessage,
                            'trace' => __FILE__
                        ]
                    );

                    return new WP_Error(
                        $responseCode,
                        $errorMessage,
                        ['status' => $responseCode]
                    );
                }

                $index++;
            }

            return true;
        } catch (Throwable $exception) {
            $this->logger->error(
                'something went wrong while pushing logs in slack message',
                [
                    'extra.message' => $exception->getMessage(),
                    'trace' => $exception->getTraceAsString()
                ]
            );

            return new WP_Error(
                WP_Http::INTERNAL_SERVER_ERROR,
                esc_html__('something went wrong while pushing logs in slack message'),
                ['status' => WP_Http::INTERNAL_SERVER_ERROR]
            );
        }
    }
}
