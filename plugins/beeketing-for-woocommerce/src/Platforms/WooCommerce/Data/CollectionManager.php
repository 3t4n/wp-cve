<?php
/**
 * User: Quan Truong
 * Email: quan@beeketing.com
 * Date: 8/13/18
 * Time: 4:19 PM
 */

namespace BeeketingConnect_beeketing_woocommerce\Platforms\WooCommerce\Data;


use BeeketingConnect_beeketing_woocommerce\Common\Data\CommonHelper;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Exception\EntityNotFoundException;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Manager\CollectionManagerAbstract;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\Collection;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\Count;

class CollectionManager extends CollectionManagerAbstract
{
    private $collectionImages = array();
    private $permalinks = array();

    /**
     * CollectionManager constructor.
     */
    public function __construct()
    {
        $this->permalinks = get_option('woocommerce_permalinks');
    }

    /**
     * Get one collection
     * @param $arg
     * @return Collection
     * @throws \Exception
     */
    public function get($arg)
    {
        $id = $arg['resource_id'];
        $term = get_term($id, 'product_cat');

        // Traverse all terms
        if (!is_wp_error($term) && $term) {

            // Fill collection images
            $this->getCollectionImages(array($term->term_id));
            return $this->formatCollection($term);
        }

        throw new EntityNotFoundException(sprintf('Cant get collection id %s', $id));
    }

    /**
     * Format collection
     *
     * @param $collectionArray
     * @return Collection
     */
    private function formatCollection($collectionArray)
    {
        $categoryBase = isset($this->permalinks['category_base']) && $this->permalinks['category_base'] ?
            ltrim($this->permalinks['category_base'], '/') : null;

        // Get category image
        $image = isset($this->collectionImages[$collectionArray->term_id]) ?
            $this->collectionImages[$collectionArray->term_id] : '';
        if (!$image && $image_id = get_term_meta($collectionArray->term_id, 'thumbnail_id')) {
            $image = wp_get_attachment_url($image_id);
        }

        // Get collection url
        if (!$categoryBase) {
            $collectionUrl = ltrim(str_replace(get_home_url(), '', get_term_link($collectionArray)), '/');
        } else {
            $collectionUrl = $categoryBase . '/' . $collectionArray->slug;
        }

        $result = new Collection();
        $result->id = $collectionArray->term_id;
        $result->title = $collectionArray->name;
        $result->handle = $collectionUrl;
        $result->published_at = CommonHelper::formatDate(new \DateTime());
        $result->image_url = $image;

        return $result;
    }

    /**
     * Get many collections
     * @param $arg
     * @return array
     */
    public function getMany($arg)
    {
        $limit = $arg['limit'];
        $page = $arg['page'];
        $title = $arg['title'];

        $args = array(
            'hide_empty' => true,
        );

        // Limit
        if ($limit) {
            $args['number'] = $limit;
        }

        // Offset
        if ($page) {
            $page = $page - 1;
            $args['offset'] = $page * $limit;
        }

        // Title
        if ($title) {
            $args['search'] = $title;
        }

        $result = array();
        // Get terms
        $terms = get_terms('product_cat', $args);

        // Traverse all terms
        if (!is_wp_error($terms)) {
            $termsId = array();
            foreach ($terms as $term) {
                $termsId[] = $term->term_id;
            }

            // Fill collection images
            $this->getCollectionImages($termsId);

            foreach ($terms as $term) {
                $result[] = $this->formatCollection($term);
            }
        }

        return $result;
    }

    /**
     * Get collection images
     *
     * @param $collectionsId
     */
    private function getCollectionImages($collectionsId)
    {
        global $wpdb;

        // Get all images id
        $imagesResult = $wpdb->get_results(
            "
            SELECT meta_value, term_id
            FROM $wpdb->termmeta
            WHERE term_id IN (" . implode(',', $collectionsId) . ") AND meta_key = 'thumbnail_id'
            "
        );

        $imagesId = $termImages = array();
        foreach ($imagesResult as $item) {
            $imagesId[] = $item->meta_value;
            $termImages[$item->meta_value] = $item->term_id;
        }

        $imagesId = array_filter(array_unique($imagesId));
        if ($imagesId) {
            // Integrate with WP-Stateless plugin
            $smMode = strtolower(get_option('sm_mode'));
            $isUseGCS = ($smMode === 'cdn' || $smMode === 'stateless');

            $result = $wpdb->get_results(
                "
                SELECT p.ID, pm.meta_key, pm.meta_value
                FROM $wpdb->postmeta pm JOIN $wpdb->posts p ON pm.post_id = p.ID
                WHERE pm.meta_key IN ('_wp_attached_file', '_wp_attachment_metadata'" . ($isUseGCS ? ", 'sm_cloud'" : "") . ")
                  AND p.post_type = 'attachment'
                  AND p.ID IN (" . implode(',', $imagesId) . ")
                "
            );

            $imagesConverted = array();
            foreach ($result as $item) {
                $imagesConverted[$item->ID][$item->meta_key] = $item->meta_value;
            }

            foreach ($imagesConverted as $imageId => $imageConverted) {
                // Get medium image
                $file = null;

                // Integrate with WP-Stateless plugin
                $sm_cloud = ($isUseGCS && isset($imageConverted['sm_cloud'])) ? unserialize($imageConverted['sm_cloud']) : null;

                if (isset($imageConverted['_wp_attachment_metadata'])) {
                    $image = $imageConverted['_wp_attachment_metadata'];
                    $image = unserialize($image);
                    $sizes = array('medium', 'shop_catalog', 'thumbnail', 'shop_thumbnail');

                    foreach ($sizes as $size) {
                        if (isset($image['sizes'][$size]['file'])) {
                            // Integrate with WP-Stateless plugin
                            if (is_array($sm_cloud) && !empty($sm_cloud['sizes'][$size]['fileLink'])) {
                                $file = apply_filters('wp_stateless_bucket_link', $sm_cloud['sizes'][$size]['fileLink']);
                                break;
                            }

                            $file = $image['sizes'][$size]['file'];
                            $image = $image['file'];
                            $file = preg_replace('/[^\/]+$/', $file, $image);

                            break;
                        }
                    }
                }

                // Fall back to main image
                if (!$file) {
                    // Integrate with WP-Stateless plugin
                    if (is_array($sm_cloud) && !empty($sm_cloud['fileLink'])) {
                        $file = apply_filters('wp_stateless_bucket_link', $sm_cloud['fileLink']);
                    } else {
                        $file = $imageConverted['_wp_attached_file'];
                    }
                }

                // Get upload directory.
                $url = null;
                $isUseS3 = strpos($file, 's3://') !== false;
                if (preg_match_all('/^http(s)?:\/\//', $file) == 1 || $isUseS3) { // If image use cdn
                    $url = $file;
                    // if use s3 short tag
                    if ($isUseS3) {
                        $url = str_replace('s3://', 'https://s3.amazonaws.com/', $file);
                    }
                } else { // Local image
                    if (function_exists('wp_get_upload_dir') && ($uploads = wp_get_upload_dir()) && false === $uploads['error']) {
                        // Check that the upload base exists in the file location.
                        if (0 === strpos($file, $uploads['basedir'])) {
                            // Replace file location with url location.
                            $url = str_replace($uploads['basedir'], $uploads['baseurl'], $file);
                        } else {
                            // It's a newly-uploaded file, therefore $file is relative to the basedir.
                            $url = $uploads['baseurl'] . "/$file";
                        }
                    }
                }

                // Ignore image
                if (!$url) {
                    continue;
                }

                if (isset($termImages[$imageId])) {
                    $this->collectionImages[$termImages[$imageId]] = $url;
                }
            }
        }
    }

    /**
     * Count collections in db
     * @return Count
     */
    public function count()
    {
        $countValue = wp_count_terms( 'product_cat', array(
            'hide_empty' => true,
        ) );

        return new Count($countValue);
    }

}
