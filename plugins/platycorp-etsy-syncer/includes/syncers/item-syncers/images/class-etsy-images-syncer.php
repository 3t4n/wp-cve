<?php
namespace platy\etsy;
use platy\etsy\api\OAuthException;

/**
 * Incharge of images syncing
 */
class EtsyImagesSyncer extends EtsyItemSyncer
{

    const LOG_TYPE = "image_sync";
    private $synced_imgs_ids;

    /**
     *
     * @var EtsyProduct
     */
    protected $etsy_product;

    public function __construct($etsy_product){
        parent::__construct($etsy_product);
        $this->etsy_product = $etsy_product;
        $this->synced_imgs_ids = [];

    }

    protected function get_etsy_listing_id() {
        return $this->get_etsy_id();
    }


    protected function upload_main_image() {
        $this->debug_logger->log_general("Uploading main image", self::LOG_TYPE);
        $thumbnail_id = (int) get_post_thumbnail_id($this->get_item_id());
        if(empty($thumbnail_id)) {
            $this->debug_logger->log_general("No main image found", self::LOG_TYPE);
            return 1;
        }

        $image_file= [];
        $image_file['rank'] = 1;
        $image_file['overwrite'] = false;
        $local_path = $this->get_image_path($thumbnail_id);
        $listing_image_id = $this->get_etsy_image_id($thumbnail_id);
        if(!empty($listing_image_id)){
            $image_file[EtsyProduct::LISTING_IMAGE_ID] = (float) $listing_image_id;
        }
        $this->upload_image($image_file,$thumbnail_id,$local_path);
        $this->debug_logger->log_general("Uploaded main image", self::LOG_TYPE);
        return 2;
    }

    protected function get_image_path($image_id) {
        $file = get_attached_file($image_id);
        // $this->debug_logger->log_general("Image $image_id path is $file", self::LOG_TYPE);
        $exists = file_exists( "$file" );

        if(!empty($file) && $exists) {
            return $file;
        }
        $this->debug_logger->log_general("Image $image_id is not hosted locally", self::LOG_TYPE);
        $image_url = wp_get_attachment_image_url($image_id, "woocommerce_single");
        $file = download_url($image_url);
        if(is_wp_error($file)) {
            $error = $file->get_error_message();
            throw new EtsySyncerException("could not download image $image_id from $image_url: $error");
        }
        return $file;

    }
    
    protected function upload_gallery_images($start_rank){
        $this->debug_logger->log_general("Uploading gallery images", self::LOG_TYPE);

        $images = $this->get_product_gallery_images();
        $num = \count($images);
        $this->debug_logger->log_general("found $num gallery images", self::LOG_TYPE);

        $rank = $start_rank;
        foreach ($images as $image_id){
            if($rank > EtsyProduct::MAX_IMAGES_PER_PRODUCT){
                break;
            }
            if(empty($image_id)) {
                continue;
            }
            $local_path = $this->get_image_path($image_id);
//             $url = apply_filters('platy_syncer_filter_images', $url['url']);
            $image_file= [];
            $image_file['rank'] = $rank;
            $image_file['overwrite'] = false;
            $listing_image_id = $this->get_etsy_image_id($image_id);
            if(!empty($listing_image_id)){
                $image_file[EtsyProduct::LISTING_IMAGE_ID] = (float) $listing_image_id;
            }
            $this->upload_image($image_file,$image_id,$local_path);
            
            $rank+=1;
            
        }
        $this->debug_logger->log_general("Uploaded gallery images", self::LOG_TYPE);

        return $rank;
    }

    protected function get_product_gallery_images(){
        $images = [];   
        $product_gallery_images = $this->etsy_product->get_product()->get_gallery_image_ids();

        foreach($product_gallery_images as $gallery_id){
            $images[] = $gallery_id;
        }
        $images = apply_filters('platy_syncer_etsy_images_array', $images, $this->get_item_id());
        return $images;
    }
    
    protected function upload_image($image_file,$image_id,$local_path){
        $rank = $image_file['rank'];
        $img_link = wp_get_attachment_image_url( $image_id, "woocommerce_single" );
        $img_link_html = "<a href='$img_link' target='_blank'>$image_id</a>";
        $this->debug_logger->log_general("Uploading image $img_link_html with rank $rank", self::LOG_TYPE);

        if(\in_array($image_id, $this->synced_imgs_ids)) {
            $this->debug_logger->log_general("Image id already synced", self::LOG_TYPE);
            return $image_file[EtsyProduct::LISTING_IMAGE_ID];
        }

        $has_etsy_id = !empty($image_file[EtsyProduct::LISTING_IMAGE_ID]);
        $thrown = false;
        try{
            if(!$has_etsy_id){
                throw new NoSuchPropertyException("Image file has no etsy id");
            }
            $ret = $this->api->uploadListingImage(array('params' =>array(EtsyProduct::LISTING_ID => $this->get_etsy_listing_id(),
            EtsyProduct::SHOP_ID => $this->shop_id),'data' => $image_file));
            $etsy_image_id = $image_file[EtsyProduct::LISTING_IMAGE_ID];
            $this->debug_logger->log_success("image id $img_link_html uploaded from etsy id $etsy_image_id", self::LOG_TYPE);

            return $this->on_image_upload($image_id, $ret[EtsyProduct::LISTING_IMAGE_ID]);
        }catch (NoSuchPropertyException $e){
            $thrown = true;
        }catch (OAuthException $e){
            $this->debug_logger->log_general("Exception thrown for image id $image_id: " . $e->getMessage(), self::LOG_TYPE);

            if($e->get_status_code() == 400 &&
                strpos($e->getMessage(), "ListingImage already exists and is associated with the requested Listing") !== false){
                    // temporary check while etsy fixes this bug hopefully.
                    return $this->on_image_upload($image_id, $image_file[EtsyProduct::LISTING_IMAGE_ID]);
                }
            $thrown = true;
        }
        if($thrown){
            $image_file['image'] = $local_path;
            $ret = $this->api->uploadListingImage(array('params' =>array(EtsyProduct::LISTING_ID => $this->get_etsy_listing_id(), 
                EtsyProduct::SHOP_ID => $this->shop_id),'data' => $image_file)); 
                $this->debug_logger->log_success("Image id $img_link_html uploaded", self::LOG_TYPE);       
        }
        return $this->on_image_upload($image_id, $ret[EtsyProduct::LISTING_IMAGE_ID]);
    }

    private function on_image_upload($image_id, $etsy_image_id) {
        $this->item_logger->log_success($image_id, $etsy_image_id, $this->shop_id, "image", $this->get_item_id());
        $this->synced_imgs_ids[] = $image_id;
        return $etsy_image_id;
    }

    protected function get_etsy_image_id($image_id) {
        try {
            return $this->get_etsy_product_id($image_id);
        }catch(NoSuchListingException $e) {

        }
        return get_post_meta($image_id,$this->get_image_meta_key() , true);
    }

    protected function get_image_meta_key(){
        return EtsyProduct::ETSY_IMAGE_ID_META_KEY . "_s_" . $this->shop_id;
    }

    /**
     * according to the API you can reuse delete images with
     * their image ids, so this should not hinder performance much.
     *
     * @return void
     */
    protected function delete_images() {
        $this->debug_logger->log_general("deleting images", self::LOG_TYPE);

        $ret = $this->api->getListingImages(array('params' =>array(EtsyProduct::LISTING_ID => $this->get_etsy_listing_id())));
        $images = $ret['results'];
        $delete_num = \count($images);
        $this->debug_logger->log_general("$delete_num to delete", self::LOG_TYPE);

        foreach($images as $image){

            $listing_image_id = $image['listing_image_id'];
            $this->debug_logger->log_general("deleting image $listing_image_id", self::LOG_TYPE);
            try {
                $this->api->deleteListingImage([
                    'params' => [
                        'shop_id' => (int) $this->get_shop_id(),
                        'listing_id' => (int) $this->get_etsy_listing_id(),
                        'listing_image_id' => (int) $listing_image_id
                    ]
                ]);
            }catch(EtsySyncerException $e) {
                $this->debug_logger->log_error("error deleting image $listing_image_id "
                    . $e->getMessage(), self::LOG_TYPE);
                continue;
            }
            $this->debug_logger->log_success("deleted image $listing_image_id", self::LOG_TYPE);
        }
        $this->debug_logger->log_general("deleted images", self::LOG_TYPE);

    }

    public function upload_images($options) {
        $this->delete_images();
        $rank = $this->upload_main_image();
        $upload_var_images = @$options['variation_images'];
        if($upload_var_images) {
            try{
                $variation_inventory = $this->api->getListingInventory(array('params' =>array(EtsyProduct::LISTING_ID => $this->get_etsy_listing_id())));
                $rank = $this->upload_variation_images($variation_inventory, $rank);
            }catch(VariationImagesException | ProductNotVariableException $e) {

            }
        }
        $rank = $this->upload_gallery_images($rank);
    }

    protected function upload_variation_images($variation_inventory, $rank) {
        if(!$this->etsy_product->get_product()->is_type('variable')){
            throw new ProductNotVariableException('Product is not variable');
        }
        if(empty($variation_inventory) || \count($this->etsy_product->get_product()->get_variation_attributes()) != 1){
            throw new VariationImagesException();
        }
        
        $this->debug_logger->log_general("uploading variation images", self::LOG_TYPE);

        $products = $variation_inventory['products'];
        $variations = $this->etsy_product->get_variations();
        $images = [];
        foreach($products as $product) {
            
            if($rank>EtsyProduct::MAX_IMAGES_PER_PRODUCT){
                break;
            }

            $prop_values = $product['property_values'][0];
            foreach($variations as $variation) {
                $attr = $variation->get_attribute($prop_values['property_name']);
                if(empty($attr) || $attr[0] == $prop_values['values'][0]) { // empty attr meens all attributes match
                    $thumbnail_id = (int) get_post_thumbnail_id($variation->get_product()->get_id());
                    if(!$thumbnail_id) {
                         break;
                    }
                    $listing_image_id = (int) $this->get_etsy_image_id($thumbnail_id);
                    $image_file = [
                        'rank' => $rank, 
                        'overwrite' => false,

                    ];
                    

                    if(!empty($listing_image_id)) {
                        $image_file[EtsyProduct::LISTING_IMAGE_ID] = (int) $listing_image_id;
                    }
                    $this->debug_logger->log_general("Uploading variation image $thumbnail_id", self::LOG_TYPE);
                    $listing_image_id = $this->upload_image($image_file, $thumbnail_id, $this->get_image_path( $thumbnail_id));
                    $images[] = [
                        'property_id' => $prop_values['property_id'],
                        'value_id' => $prop_values['value_ids'][0],
                        'image_id' => (int) $listing_image_id
                    ];
                    $this->debug_logger->log_general("Uploaded variation image $thumbnail_id", self::LOG_TYPE);

                    $rank += 1;
                    break;
                }
                
            }
        }
        
        $this->api->updateVariationImages(array('params' =>array(EtsyProduct::LISTING_ID => $this->get_etsy_listing_id(),
            EtsyProduct::SHOP_ID => $this->shop_id),'data' => ['variation_images' => $images]));
        $this->debug_logger->log_general("Uploaded variation images", self::LOG_TYPE);

        return $rank;
    }

}
