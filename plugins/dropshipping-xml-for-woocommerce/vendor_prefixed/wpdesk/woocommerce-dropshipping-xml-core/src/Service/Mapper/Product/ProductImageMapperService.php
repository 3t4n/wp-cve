<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO\ProductAttachmentDAO;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields;
use DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Logger\ImportLoggerService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\ImportMapperService;
use WC_Product;
use Exception;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\Abstraction\ProductMapperServiceInterface;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\ConnectorClientFactory;
/**
 * Class ProductCreatorService, creates woocommerce product.
 *
 * @package WPDesk\Library\DropshippingXmlCore\Service\Creator
 */
class ProductImageMapperService implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\Abstraction\ProductMapperServiceInterface
{
    const FILTER_NAME_IMAGES = 'wpdesk_dropshipping_mapper_images';
    const FILTER_DOWNLOAD_IMAGES = 'wpdesk_dropshipping_mapper_download_images';
    const FILTER_IMAGES_OPTIONS = 'wpdesk_dropshipping_mapper_images_options';
    const FILTER_IMAGES_ATTACHMENTS_INFO = 'wpdesk_dropshipping_mapper_images_attachment_info';
    const ATTACHMENT_META_URL = 'url';
    /**
     * @var ImportMapperService
     */
    protected $mapper;
    /**
     * @var ImportLoggerService
     */
    protected $logger;
    /**
     * @var ProductAttachmentDAO
     */
    protected $attachment_dao;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\ImportMapperService $mapper, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO\ProductAttachmentDAO $attachment_dao, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Logger\ImportLoggerService $logger)
    {
        $this->logger = $logger;
        $this->mapper = $mapper;
        $this->attachment_dao = $attachment_dao;
    }
    public function update_product(\WC_Product $wc_product) : \WC_Product
    {
        if ($this->mapper->is_product_field_group_should_be_mapped($wc_product, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::SYNC_FIELD_OPTION_IMAGES)) {
            $images = $this->get_images();
            $images_ids = $this->get_images_ids($images);
            if (!empty($images_ids)) {
                $first_image = \reset($images_ids);
                $remove_from_gallery = $this->is_featured_should_by_removed_from_gallery();
                $append_images = $this->is_img_should_be_append();
                $wc_product->set_image_id($first_image);
                if (\true === $remove_from_gallery) {
                    $images_ids = \array_diff($images_ids, [$first_image]);
                }
                if (\true === $append_images) {
                    $images_ids = \array_merge($wc_product->get_gallery_image_ids(), $images_ids);
                }
                if ('variation' !== $wc_product->get_type()) {
                    $wc_product->set_gallery_image_ids($images_ids);
                }
            }
        }
        return $wc_product;
    }
    protected function create_attachment_from_url(string $imageurl) : int
    {
        include_once ABSPATH . 'wp-admin/includes/image.php';
        $contents = \apply_filters(self::FILTER_DOWNLOAD_IMAGES, null, $imageurl);
        if (null === $contents) {
            $context_options = \apply_filters(self::FILTER_IMAGES_OPTIONS, []);
            if (\true === \apply_filters(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\ConnectorClientFactory::FILTER_BYPASS_SSL, \false)) {
                $ssl_options = ['ssl' => ['verify_peer' => \false, 'verify_peer_name' => \false]];
                $context_options = \array_merge($context_options, $ssl_options);
            }
            $contents = \file_get_contents($imageurl, \false, \stream_context_create($context_options));
        }
        $imagesize = \getimagesizefromstring($contents);
        $mime = \explode('/', $imagesize['mime']);
        $imagetype = \end($mime);
        if (empty($imagetype)) {
            throw new \Exception(\__('The image cannot be downloaded correctly', 'dropshipping-xml-for-woocommerce'));
        }
        $uniq_name = \date('dmY') . '' . \uniqid();
        $filename = $uniq_name . '.' . $imagetype;
        $uploaddir = \wp_upload_dir();
        $uploadfile = $uploaddir['path'] . '/' . $filename;
        $savefile = \fopen($uploadfile, 'w');
        \fwrite($savefile, $contents);
        \fclose($savefile);
        $wp_filetype = \wp_check_filetype(\basename($filename), null);
        $attachment = ['post_mime_type' => $wp_filetype['type'], 'post_title' => $filename, 'post_content' => '', 'post_status' => 'inherit'];
        $attachment = \apply_filters(self::FILTER_IMAGES_ATTACHMENTS_INFO, $attachment, $imageurl);
        $attach_id = \wp_insert_attachment($attachment, $uploadfile);
        \update_post_meta($attach_id, self::ATTACHMENT_META_URL, $imageurl);
        $imagenew = \get_post($attach_id);
        $fullsizepath = \get_attached_file($imagenew->ID);
        $attach_data = \wp_generate_attachment_metadata($attach_id, $fullsizepath);
        \wp_update_attachment_metadata($attach_id, $attach_data);
        return $attach_id;
    }
    protected function get_attachment_id_by_url(string $url) : int
    {
        $attachment = $this->attachment_dao->get_attachment_id_by_url($url);
        return $attachment->ID;
    }
    protected function get_url_from_img(string $img) : string
    {
        \preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $img, $matches);
        return isset($matches[1]) ? $matches[1] : '';
    }
    protected function get_images() : array
    {
        $result = [];
        $separator = $this->get_separator_field();
        $scan_img = $this->is_img_tag_should_be_scanned();
        $images = \apply_filters(self::FILTER_NAME_IMAGES, $this->get_img_field());
        if (!empty($images)) {
            $parts_new_line = \explode("\n", $images);
            foreach ($parts_new_line as $new_line) {
                $new_line = \trim($new_line);
                if (!empty($new_line)) {
                    $parts_img = \explode($separator, $new_line);
                    foreach ($parts_img as $img) {
                        $img = \trim($img);
                        if (\filter_var($img, \FILTER_VALIDATE_URL) !== \false) {
                            $result[] = $img;
                        } elseif ($scan_img) {
                            $url = $this->get_url_from_img($img);
                            if (\filter_var($url, \FILTER_VALIDATE_URL) !== \false) {
                                $result[] = $url;
                            }
                        }
                    }
                }
            }
        }
        return \array_unique($result);
    }
    protected function get_images_ids(array $images) : array
    {
        $result = [];
        foreach ($images as $url) {
            try {
                $attachment_id = $this->get_attachment_id_by_url($url);
                $this->logger->notice(\__('Found attachment in the media library, attachment: ', 'dropshipping-xml-for-woocommerce') . $attachment_id);
            } catch (\Exception $e) {
                try {
                    $attachment_id = $this->create_attachment_from_url($url);
                    $this->logger->notice(\__('Created new attachment: ', 'dropshipping-xml-for-woocommerce') . $attachment_id);
                } catch (\Exception $error) {
                    $this->logger->notice(\__('Error while creating the image: ', 'dropshipping-xml-for-woocommerce') . $error->getMessage());
                }
            }
            if (\is_numeric($attachment_id)) {
                $result[] = $attachment_id;
            }
        }
        return $result;
    }
    protected function get_separator_field() : string
    {
        $separator = $this->mapper->get_raw_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_IMAGES_SEPARATOR);
        return $separator === null ? ',' : $separator;
    }
    protected function get_img_field() : string
    {
        return $this->mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_IMAGES);
    }
    protected function is_img_tag_should_be_scanned() : bool
    {
        return \DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField::VALUE_TRUE === $this->mapper->get_raw_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_IMAGES_SCAN);
    }
    protected function is_featured_should_by_removed_from_gallery() : bool
    {
        return \DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField::VALUE_TRUE === $this->mapper->get_raw_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_IMAGES_FEATURED_NOT_IN_GALLERY);
    }
    protected function is_img_should_be_append() : bool
    {
        return \DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField::VALUE_TRUE === $this->mapper->get_raw_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_IMAGES_APPEND_TO_EXISTING);
    }
}
