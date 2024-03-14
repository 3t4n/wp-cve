<?php

namespace CODNetwork\Controller;

use CODNetwork\Repositories\CodNetworkRepository;
use CODNetwork\Services\CODN_Logger_Service;
use PHPMailer\PHPMailer\Exception;
use Throwable;

class CODN_Settings_Controller
{
    /** @var CodNetworkRepository */
    public $codNetworkRepository;

    /** @var CODN_Logger_Service */
    private $logger;

    public function __construct()
    {
        $this->codNetworkRepository = CodNetworkRepository::get_instance();
        $this->logger = new CODN_Logger_Service();
    }

    /**
     * Action update status logger
     * @return WP_Error|void
     */
    public function codn_update_status_logger()
    {
        try {
            if (!isset($_POST['submit'])) {
                return;
            }

            $status = isset($_POST['logs_activity']);
            $this->codNetworkRepository->update_status_logs_activity($status);
        } catch (Throwable $exception) {
            $this->logger->error(
                'something went wrong while updating status of logs activity',
                [
                    'extra.message' => $exception->getMessage(),
                    'trace' => $exception->getTraceAsString()
                ]
            );

            return new WP_Error(
                WP_Http::INTERNAL_SERVER_ERROR,
                esc_html__('something went wrong while updating status of logs activity'),
                [
                    'status' => WP_Http::INTERNAL_SERVER_ERROR
                ]
            );
        }
    }

    /**
     * Select status logger
     */
    public function codn_select_logs_status_is_active(): ?bool
    {
        return $this->codNetworkRepository->select_logs_status();
    }

    /**
     * @return void|WP_Error
     */
    public function codn_send_email()
    {
        try {
            if (!isset($_POST['submit'])) {
                return;
            }

            $this->logger->codn_send_log_via_email();
        } catch (Throwable $exception) {
            $this->logger->error(
                'something went wrong while sending email of logs',
                [
                    'extra.message' => $exception->getMessage(),
                    'trace' => $exception->getTraceAsString()
                ]
            );

            return new WP_Error(
                WP_Http::INTERNAL_SERVER_ERROR,
                esc_html__('something went wrong while sending email of logs'),
                [
                    'status' => WP_Http::INTERNAL_SERVER_ERROR
                ]
            );
        }
    }
}

