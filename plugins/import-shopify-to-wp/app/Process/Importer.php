<?php

namespace S2WPImporter\Process;

use S2WPImporter\Files;
use S2WPImporter\Traits\AjaxTrait;

class Importer
{
    use AjaxTrait;

    protected $ajaxPrefix = 'wp_ajax_shopify2wp_';

    public function __construct()
    {
    }

    public function init(): void
    {
        add_action("{$this->ajaxPrefix}import", [$this, 'import']);
        add_action("{$this->ajaxPrefix}next_page", [$this, 'next_page']);
        add_action("{$this->ajaxPrefix}upload", [$this, 'upload']);
        add_action("{$this->ajaxPrefix}clear_data", [$this, 'clear_data']);
    }

    public function import(): void
    {
        $request_body = file_get_contents('php://input');
        $data = json_decode($request_body, true);

        if (empty($data['nonce']) || !wp_verify_nonce($data['nonce'], 's2wp')) {
            $this->error('Failed to verify the nonce.');
        }

        $item = $data['item'];
        $type = sanitize_key($data['type']);

        $obj = null;

        if ($type === 'products') {
            $obj = new Product($item, new \WC_Product_Variable());
        }
        else if ($type === 'customers') {
            if (email_exists($item['email'])) {
                $this->error('Email is already registered.', [
                    'errors' => [],
                    'soft_errors' => [],
                ]);
            }
            $obj = new Customer($item, new \WC_Customer());
        }
        else if ($type === 'orders') {
            $obj = new Order($item, new \WC_Order());
        }
        else {
            $this->error('Unknown $type', []);
        }

        if (null !== $obj) {
            /** @var IRecord $obj */
            $obj->parse();

            if ($obj->hasErrors()) {
                $this->error('Failed', [
                    'errors' => $obj->getErrors(),
                    'soft_errors' => $obj->getSoftErrors(),
                ]);
            }

            $obj->beforeSave();

            $objId = $obj->save();

            if (!empty($objId)) {
                $obj->afterSave($objId);

                $this->success('Imported', [
                    'soft_errors' => $obj->getSoftErrors(),
                    'new_object_id' => $objId,
                ]);
            }
        }

        $this->error('Failed', [
            'errors' => null !== $obj ? $obj->getErrors() : [],
            'soft_errors' => null !== $obj ? $obj->getSoftErrors() : [],
        ]);

        die();
    }

    public function next_page()
    {
        $request_body = file_get_contents('php://input');
        $data = json_decode($request_body, true);

        if (empty($data['nonce']) || !wp_verify_nonce($data['nonce'], 's2wp')) {
            $this->error('Failed to verify the nonce.');
        }

        $type = sanitize_key($data['type']);

        $files = new Files($type);

        $files->setLastFileNumber();

        $this->success('New Data', [
            $type => (new Files($type))->getLastFileData(),
            "{$type}_total_pages" => (new Files($type))->getTotalFiles(),
            "{$type}_current_page" => (new Files($type))->getLastFileNumber(),
        ]);
    }

    public function upload()
    {
        if (empty($_GET['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['nonce'])), 's2wp')) {
            $this->error('Failed to verify the nonce.');
        }

        $fileIds = !empty($_FILES) ? $this->uploadFiles() : [];

        if (empty($fileIds[0])) {
            $this->error('Empty file ID. Try again.');
        }

        $filePath = get_attached_file($fileIds[0]);

        if (empty($filePath)) {
            $this->error('Empty file path. Try again');
        }

        $extracted = $this->unzipFile($fileIds[0]);

        (new Files('products'))->reset();
        (new Files('customers'))->reset();
        (new Files('orders'))->reset();

        $this->success('Uploaded`', [
            'fileIds' => $fileIds,
            'filePath' => $filePath,
            'extracted' => $extracted,
            'newState' => [
                'products' => (new Files('products'))->getLastFileData(),
                'products_total_pages' => (new Files('products'))->getTotalFiles(),
                'products_current_page' => (new Files('products'))->getLastFileNumber(),
                'products_import_complete' => (new Files('products'))->getLastFileNumber() > (new Files('products'))->getTotalFiles(),

                'customers' => (new Files('customers'))->getLastFileData(),
                'customers_total_pages' => (new Files('customers'))->getTotalFiles(),
                'customers_current_page' => (new Files('customers'))->getLastFileNumber(),
                'customers_import_complete' => (new Files('customers'))->getLastFileNumber() > (new Files('customers'))->getTotalFiles(),

                'orders' => (new Files('orders'))->getLastFileData(),
                'orders_total_pages' => (new Files('orders'))->getTotalFiles(),
                'orders_current_page' => (new Files('orders'))->getLastFileNumber(),
                'orders_import_complete' => (new Files('orders'))->getLastFileNumber() > (new Files('orders'))->getTotalFiles(),

                'current_step' => get_option('shopify2wp_current_step', 'products'),
            ],
        ]);
    }

    protected function uploadFiles()
    {
        // IDs of attachments
        $files = [];

        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        foreach ($_FILES as $fileId => $fileInfo) {
            if (empty($fileInfo['name'])) {
                continue; // Malformed file info
            }

            $attachId = media_handle_upload($fileId, 0, [], [
                'test_form' => false,
                'test_type' => true,
                'mimes' => [
                    "zip" => "application/zip",
                ],
            ]);

            if (is_wp_error($attachId)) {
                $this->error($attachId->get_error_message(), [
                    'fileId' => $fileId,
                ]);
            }

            $files[] = $attachId;
        }

        return $files;
    }

    protected function unzipFile($fileId)
    {
        global $wp_filesystem;

        WP_Filesystem();

        $upPath = untrailingslashit(wp_upload_dir()['basedir']) . '/s2wp-data/';

        $wp_filesystem->rmdir($upPath, true);

        $file = get_attached_file($fileId);

        $extracted = unzip_file($file, $upPath);

        if (is_wp_error($extracted)) {
            $this->error($extracted->get_error_message());
        }

        wp_delete_attachment($fileId, true);

        return $extracted;
    }

    public function clear_data()
    {
        global $wp_filesystem;

        $request_body = file_get_contents('php://input');
        $data = json_decode($request_body, true);

        if (empty($data['nonce']) || !wp_verify_nonce($data['nonce'], 's2wp')) {
            $this->error('Failed to verify the nonce.');
        }

        WP_Filesystem();

        $upPath = untrailingslashit(wp_upload_dir()['basedir']) . '/s2wp-data/';

        $deleted = $wp_filesystem->rmdir($upPath, true);

        if ($deleted) {
            $this->success('Deleted data');
        }

        $this->error('Failed to delete data');
    }

}
