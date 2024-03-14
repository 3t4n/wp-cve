<?php namespace BDroppy\Services\WooCommerce;

use BDroppy\Init\Core;
use BDroppy\Models\Mask\ProductMask;
use BDroppy\Models\Product;
use BDroppy\Models\ProductModel;
use BDroppy\Services\System\SystemLanguage;

if ( ! defined( 'ABSPATH' ) ) exit;



class Importer
{
    private $config;
    private $logger;
    private $system;
    private $wc;
    private $attribute_taxonomies;

    public function __construct(WC $wc, Core $core)
    {
        $this->config = $core->getConfig();
        $this->logger = $core->getLogger();
        $this->system = $core->getSystem();
        $this->wc = $wc;
    }

    public function addVariationToProduct($post_id,$product,$model,$lang)
    {
        $post = wc_get_product($post_id);
        if($post == false) return false;

        $variation_post = [
            'post_title'  => $post->get_title(),
            'post_name'   => 'product-'.$post_id.'-variation',
            'post_status' => "publish",
            'post_parent' => $post_id,
//            'menu_order'   => $post->get_children() + 1,
            'post_type'   => 'product_variation',
            'guid'        => $post->get_permalink()
        ];

        $variation_id = wp_insert_post( $variation_post,true );

        $variation = new \WC_Product_Variation( $variation_id );

        $taxonomy  = $this->getAttributeTaxonomy( $this->config->catalog->getAttribute('size') );
        if( ! term_exists( $model->size, $taxonomy ) )
            wp_insert_term( $model->size, $taxonomy ); // Create the term

        $search = strtolower(str_replace(' ','-',trim($model->size)));
        $term_slug = get_term_by('slug', $search, $taxonomy )->slug; // Get the term slug

        $post_term_names =  wp_get_post_terms( $post_id, $taxonomy, ['fields' => 'names'] );

        if( ! in_array( $model->size, $post_term_names ) )
            wp_set_post_terms( $post_id, $model->size, $taxonomy, true );

        update_post_meta( $variation_id, 'attribute_'.$taxonomy, $term_slug );

        if($this->config->catalog->get('import-product-sku'))
        {
            $sku = $model->code . "-" . $model->size;
        }else{
            $sku = $model->barcode;
        }

        if($this->system->language->hasWpmlSupport())
        {
            global $sitepress;
            $def_lang = $sitepress->get_language_details($sitepress->get_default_language());
            $main_lang = $sitepress->get_language_details($sitepress->get_language_code_from_locale($lang));

            if($def_lang['code'] === $main_lang['code'])
            {
                try {
                    $variation->set_sku( $sku );
                }catch (\Exception $e){}
            }
        }else{
            try {
                $variation->set_sku( $sku );
            }catch (\Exception $e){}
        }

        $this->setProductPrice($variation->get_id(),$product);

        $variation->set_stock_quantity( $model->availability );
        $variation->set_manage_stock(true);
        $variation->set_backorders('no');
//        if ($model->availability == 0)
//        {
//
//        }


        $variation->set_weight($product->weight);

        $variation->save();

        ProductModel::create([
            'rewix_product_id' => $product->id,
            'rewix_model_id' => $model->id,
            'lang' => $lang,
            'wc_model_id' => $variation_id,
            'wc_product_id' => $post_id,
        ]);
        return false;
    }

    public function createProductVariations( $post_id, $product ,$lang)
    {
        wp_set_object_terms( $post_id, 'variable', 'product_type' );
        $post = wc_get_product($post_id);

        $models = [];
        foreach ($product->models as $model)
        {
            if(isset($models[$model->size]))
            {
                if ($model->lastUpdate > $models[$model->size])
                {
                    $models[$model->size] = $model->lastUpdate;
                }
            }else{
                $models[$model->size] = $model->lastUpdate;
            }
        }


        foreach ($product->models as $key => $attribute )
        {
            if($models[$attribute->size] != $attribute->lastUpdate) continue;
            $variation_post = [
                'post_title'  => $post->get_title(),
                'post_name'   => 'product-'.$post_id.'-variation-'.$lang,
                'post_status' => "publish",
                'post_parent' => $post_id,
                'post_type'   => 'product_variation',
                'menu_order'   => $key + 1,
                'guid'        => $post->get_permalink()
            ];
            $variation_id = wp_insert_post( $variation_post );
            $variation = new \WC_Product_Variation( $variation_id );

            $taxonomy  = $this->getAttributeTaxonomy( $this->config->catalog->getAttribute('size') );
            if (empty($taxonomy)) continue;

            if( ! term_exists( $attribute->size, $taxonomy ) )
                wp_insert_term( $attribute->size, $taxonomy ); // Create the term

            $search = strtolower(str_replace(' ','-',trim($attribute->size)));
            $term_slug = get_term_by('slug', $search, $taxonomy )->slug; // Get the term slug


            $post_term_names =  wp_get_post_terms( $post_id, $taxonomy, ['fields' => 'names'] );

            if( ! in_array( $attribute->size, $post_term_names ) )
                wp_set_post_terms( $post_id, $attribute->size, $taxonomy, true );

            update_post_meta( $variation_id, 'attribute_'.$taxonomy, $term_slug );

            if($this->config->catalog->get('import-product-sku',false))
            {
                $sku = $attribute->code . "-" . $attribute->size;
            }else{
                $sku = $attribute->barcode;
            }

            if($this->system->language->hasWpmlSupport())
            {
                global $sitepress;
                $def_lang = $sitepress->get_language_details($sitepress->get_default_language());
                $main_lang = $sitepress->get_language_details($sitepress->get_language_code_from_locale($lang));

                if($def_lang['code'] === $main_lang['code'])
                {
                    try {
                        $variation->set_sku( $sku );
                    }catch (\Exception $e){}
                }
            }else{
                try {
                    $variation->set_sku( $sku );
                }catch (\Exception $e){}
            }

            $this->setProductPrice($variation->get_id(),$attribute);

            $variation->set_stock_quantity( $attribute->availability );
            $variation->set_manage_stock(true);
            $variation->set_stock_status('');
            $variation->set_backorders('no');

            $variation->set_weight($product->weight);

            if($key === 0)  {
                update_post_meta( $post_id, '_default_attributes', [
                    $taxonomy => $term_slug,
//                    $taxonomy2 => $term_slug2
                ] );
            }

            $variation->save();

            ProductModel::create([
                'rewix_product_id' => $product->id,
                'rewix_model_id' => $attribute->id,
                'lang' => $lang,
                'wc_model_id' => $variation_id,
                'wc_product_id' => $post_id,
            ]);
        }

    }

    public function addProductCategories($post_id,ProductMask $product,$lang)
    {
        foreach ($this->config->catalog->getCategoryStructure() as $categoryStructure)
        {
            $category = 0;
            $def_category_id = 0;
            if(!$this->categoryMapping($post_id,$product,$categoryStructure,$lang))
            {
                foreach ($categoryStructure as $tag_name)
                {
                    if($this->system->language->hasWpmlSupport())
                    {
                        global $sitepress;
                        $def_lang = $sitepress->get_language_details($sitepress->get_default_language());
                        $main_lang = $sitepress->get_language_details($sitepress->get_language_code_from_locale($lang));

                        if($def_lang['code'] !== $main_lang['code'])
                        {
                            $def_category_name = $product->getTagValue($tag_name,$def_lang['default_locale']);
                            $this->logger->info('importer',json_encode([1,$lang,$def_category_name]));
                            $def_category_id = $this->findOrCreateCategory($def_category_name,$def_lang['default_locale'],$def_category_id);
                            $this->logger->info('importer',json_encode([2,$lang,$def_category_name,$def_category_id]));
                            $sitepress->set_element_language_details(
                                $def_category_id, 'tax_product_cat', null,$def_lang['code'] );
                            $sitepress->set_element_language_details('','','','','');


                            $def_category = $sitepress->get_element_language_details($def_category_id,'tax_product_cat');
                            $this->logger->info('importer',json_encode([3,$lang,$def_category_name,$def_category]));

                            $category_name = $product->getTagValue($tag_name,$main_lang['default_locale']);
                            $this->logger->info('importer',json_encode([4,$lang,$def_category_name,$def_category,$category_name]));
                            $category = $this->findOrCreateCategory($category_name,$main_lang['default_locale'],$category);
                            $r = wp_set_object_terms( $post_id, $category, 'product_cat', true );
                            $this->logger->info('importer',json_encode([5,$lang,$def_category_name,$def_category,$category_name,$r]));
                            $sitepress->set_element_language_details(
                                $category, 'tax_product_cat', $def_category->trid, $main_lang['code'],$def_lang['code']);

                            continue;
                        }
                    }

                    $category_name = $product->getTagValue($tag_name,$lang);

                    $category = $this->findOrCreateCategory($category_name,$lang,$category);
                    $r = wp_set_object_terms( $post_id, $category, 'product_cat', true );

                }
            }

        }

    }

    public function categoryMapping($post_id,ProductMask $product,$categoryIds,$lang)
    {

        if(SystemLanguage::hasWpmlSupport()) {return 0;}

        $categories = get_option('bdroppy-category-mapping',[]);

        $result = array_filter($categories,function ($item) use ($product, $categoryIds)
        {
            $return = 1;
            foreach ($categoryIds as $tag_name)
            {
                $name = $product->getTagCode($tag_name);
                if($item['bdroppyIds'][$tag_name] != $name ) $return = 0;
            }
            return $return;
        });

        if (count($result) > 0) {

            foreach ($categoryIds as $tag_name)
            {
                wp_set_object_terms($post_id,(int) reset($result)['siteIds'][$tag_name], 'product_cat', true);
            }
            return 1;
        }
        return 0;
    }

    public function addProductTags($post_id,ProductMask $product,$lang)
    {
        wp_set_object_terms( $post_id, [
            $product->getCategory($lang),
            $product->getSubCategory($lang),
            $product->getColor($lang),
            $product->getGender($lang),
            $product->getSeason($lang),
            $product->getBrand($lang)
        ], 'product_tag' );
    }

    public function importProductToWooCommerce(ProductMask $product,$lang,$parent_id=0)
    {
        $post_title = $product->getName($lang);
        if($this->config->catalog->get('import-brand-to-title'))
        {
            $post_title = $product->getBrand($lang) . ' - '. $post_title;
        }

        if($this->config->catalog->get('import-color-to-title'))
        {
            $post_title .= ' - ' . $product->getTagValue('color',$lang);
        }

        if ($this->config->catalog->get('publish-product')){
            $post_status = "publish";
        }else{
            $post_status = "draft";
        }

        $post = [
            'post_content' => $product->getDescriptions($lang),
            'post_status'  => $post_status,
            'post_title'   => $post_title,
            'post_type'    => 'product',
            'post_parent'    => $parent_id,
        ];


        $post_id = wp_insert_post( $post );

        Product::where('rewix_product_id',$product->id)
            ->where('lang',$lang)
            ->update(['wc_product_id'=>$post_id]);

        update_post_meta( $post_id, '_visibility', 'visible' );
        update_post_meta( $post_id, '_downloadable', 'no' );
        update_post_meta( $post_id, '_virtual', 'no' );
        update_post_meta( $post_id, '_purchase_note', '' );
        update_post_meta( $post_id, '_featured', "no" );
        update_post_meta( $post_id, '_weight', (float) $product->weight );
        //update_post_meta( $post_id, '_sku', (string) $product->code );
        update_post_meta( $post_id, '_sale_price_dates_from', '' );
        update_post_meta( $post_id, '_sale_price_dates_to', '' );
        update_post_meta( $post_id, '_sold_individually', '' );
        update_post_meta( $post_id, '_backorders', 'no' );

        Product::where('rewix_product_id',$product->id)
            ->where('lang',$lang)
            ->update(['import_base'=>1]);

        $this->addProductTags($post_id,$product,$lang);

        Product::where('rewix_product_id',$product->id)
            ->where('lang',$lang)
            ->update(['import_tags'=>1]);

        $this->setProductAttribute( $post_id,$product,$lang);

        Product::where('rewix_product_id',$product->id)
            ->where('lang',$lang)
            ->update(['import_attributes'=>1]);

        if($product->isSimpleProduct())
        {
            update_post_meta( $post_id, '_manage_stock', 'yes' );
            wp_set_object_terms( $post_id, 'simple', 'product_type' );

            $model = $product->models[0];
            update_post_meta( $post_id, '_barcode', (string) $model->barcode );
            if($this->config->catalog->get('import-product-sku'))
            {
                $sku = $model->code;
            }else{
                $sku = $model->barcode;
            }

            if($this->system->language->hasWpmlSupport())
            {
                global $sitepress;
                $def_lang = $sitepress->get_language_details($sitepress->get_default_language());
                $main_lang = $sitepress->get_language_details($sitepress->get_language_code_from_locale($lang));

                if($def_lang['code'] === $main_lang['code'])
                {
                    try {
                        update_post_meta( $post_id, '_sku', (string) $sku );
                    }catch (\Exception $e){}

                }
            }else{
                try {
                    update_post_meta( $post_id, '_sku', (string) $sku );
                }catch (\Exception $e){}
            }


            $this->setProductPrice($post_id,$product);

            wc_update_product_stock_status($post_id, 'instock');
            ProductModel::create([
                'rewix_product_id' => $product->id,
                'rewix_model_id' => $product->models[0]->id,
                'lang' => $lang,
                'wc_model_id' => $post_id,
                'wc_product_id' => $post_id,
            ]);
        } else {
           if($post_id > 0)
           {
               if($this->config->catalog->get('import-product-sku'))
               {
                   $sku = $product->code ;
                   if($this->system->language->hasWpmlSupport())
                   {
                       global $sitepress;
                       $def_lang = $sitepress->get_language_details($sitepress->get_default_language());
                       $main_lang = $sitepress->get_language_details($sitepress->get_language_code_from_locale($lang));

                       if($def_lang['code'] === $main_lang['code'])
                       {
                           try {
                               update_post_meta( $post_id, '_sku', (string) $sku );
                           }catch (\Exception $e){}
                       }
                   }else{
                       try {
                           update_post_meta( $post_id, '_sku', (string) $sku );
                       }catch (\Exception $e){}
                   }
               }

               $this->createProductVariations($post_id,$product,$lang);
           }
            $this->setProductStock( $post_id, (int) $product->availability );
        }

        Product::where('rewix_product_id',$product->id)
            ->where('lang',$lang)
            ->update(['import_models'=>1]);

        $this->addProductCategories($post_id, $product,$lang);

        Product::where('rewix_product_id',$product->id)
            ->where('lang',$lang)
            ->update(['import_categories'=>1]);


        if ($this->config->catalog->get('add-image-url-tools'))
        {
            $cron_url = get_bloginfo('url') .'?doing_wp_cron&upload-image=1';
            $retry = 0;
            do{
                sleep($retry*2);

                $result = wp_remote_get( $cron_url ,[
                    'timeout' => 60,
                    'method' => "POST",
                    'body' => [
                        'bdroppy-product' => [
                            'id' => $post_id,
                            'pictures' => $product->pictures,
                        ]
                    ]
                ]);
                if (is_wp_error($result)){
                    $this->logger->info('ssssss','error : ' . json_encode($result->errors));
                }else{
                    $this->logger->debug('ssssss','result : ' . $result['response']['code']  );
                }
                $retry++;

            }while((is_wp_error($result) || $result['response']['code'] != 200 ) && $retry <= 6);

            if (is_wp_error($result)){

                $this->logger->info('ssssss','error : ' . json_encode($result->errors));
            }


//            $this->importProductImages( $post_id, $product->pictures );
        }else{
            $this->importProductUrl( $post_id, $product->pictures );
        }

        Product::where('rewix_product_id',$product->id)
            ->where('lang',$lang)
            ->update(['import_images'=>1]);

        return $post_id;
    }

    public function importProductImages( $wc_id, $images )
    {
        $import_image =$this->config->catalog->get('import-images');
        $this->logger->info('imageUploader','$import_image' .$wc_id.'===' . $import_image);

        if($import_image === 0 || empty($images)) return false;
        $this->logger->info('imageUploader','uploading--post_id : ' . $wc_id);
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
        require_once( ABSPATH . 'wp-admin/includes/media.php' );
        require_once( ABSPATH . 'wp-admin/includes/image.php' );

        $image_ids = [];
        foreach ( $images as $key => $image )
        {
            $image = (object)$image;
            $this->logger->info('imageUploader','$key :' . $key);
            if($import_image <= $key && $import_image !== 'all') break;
            $this->logger->info('imageUploader','image url : ' . $image->url);
            preg_match( '/[^\?]+\.(jpg|JPG|jpe|JPE|jpeg|JPEG|gif|GIF|png|PNG)/', $image->url, $matches );
            $name = basename( $matches[0] );
            $slug = sanitize_title( $name );
            // Check if image is in DB
            $attachment_in_db = get_page_by_title( $slug, 'OBJECT', 'attachment' );
            // Is attachment already in DB?
            if ( $attachment_in_db )
            {
                $this->logger->info('imageUploader','in DB :' . $attachment_in_db->ID);
                $image_ids[] = $attachment_in_db->ID;
            } else {
                if (strpos($image->url,"http")  !== false) {
                    $url = $image->url;
                }else{
                    if (strpos($this->config->api->get('api-base-url'),'dev') === false){
                        $base = "https://www.mediabd.it/storage-foto/prod/";
                    }else{
                        $base = "https://www.mediabd.it/storage-foto-dev/prod/";
                    }

                    $url = $base . $image->url;
                }

                $get = wp_remote_get( $url ,['timeout' => 60]);

                if ( (int) wp_remote_retrieve_response_code( $get ) != 200 ) {
                    $this->logger->info('imageUploader','Error to get file');
                    $this->logger->info('imageUploader',$url );
                    $this->logger->info('imageUploader',(int)wp_remote_retrieve_response_code( $get ) );
                    // error
                    continue;
                }

                $uploaded = wp_upload_bits( $name, null, wp_remote_retrieve_body( $get ) );
                if ( $uploaded['error'] ) {
                    $this->logger->info('imageUploader','Error : '.json_encode( $uploaded ) );
                    // error
                    continue;
                }
                $attachment  = [
                    'guid'           => $uploaded['url'],
                    'post_title'     => $slug,
                    'post_mime_type' => 'image/jpeg'
                ];
                $attach_id   = wp_insert_attachment( $attachment, $uploaded['file'], $wc_id );
                $attach_data = wp_generate_attachment_metadata( $attach_id, $uploaded['file'] );
                wp_update_attachment_metadata( $attach_id, $attach_data );

                $image_ids[] = $attach_id;
            }
        }
        $this->logger->info('imageUploader','count' . count( $image_ids ) );
        $this->logger->info('imageUploader','-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_' );
        if ( count( $image_ids ) > 0 ) {
            set_post_thumbnail( $wc_id, $image_ids[0] );
            $image_ids = array_slice($image_ids, 1);
            // Associate images as gallery
          $d = update_post_meta( $wc_id, '_product_image_gallery', implode( ',', $image_ids ) );

        }
        return 1;
    }

    public function importProductUrl( $wc_id, $images )
    {
        $import_image =$this->config->catalog->get('import-images');

        if($import_image === 0) return false;

        $image_URLs = [];
        foreach ( $images as $key => $image )
        {
            if (strpos($image->url,"http")  !== false) {
                $url = $image->url;
            }else{
                if (strpos($this->config->api->get('api-base-url'),'dev') === false)
                {
                    $base = "https://www.mediabd.it/storage-foto/prod/";
                }else{
                    $base = "https://www.mediabd.it/storage-foto-dev/prod/";
                }
                $url = $base . $image->url;
            }

            if($import_image <= $key && $import_image !== 'all' ) break;

            $imagesizes = @getimagesize( $image->url );
            $image_URLs[$key] = [
                'url'   => $url,
                'width'     => isset( $imagesizes[0] ) ? $imagesizes[0] : '',
                'height'    => isset( $imagesizes[1] ) ? $imagesizes[1] : '',
            ];
        }
        if(count( $image_URLs ))
        {
            $thumbnail = [
                'img_url'   => $image_URLs[0]['url'],
                'width'     => $image_URLs[0]['width'],
                'height'    => $image_URLs[0]['height'],
            ];

            update_post_meta( $wc_id, "_bdroppy_url", $thumbnail );
            update_post_meta( $wc_id, "_bdroppy_wcgallary", $image_URLs );
        }

        return 1;
    }

    private function setProductStock( $variation_id, $quantity )
    {
        $product = wc_get_product( $variation_id );
        $updated = false;
        update_post_meta( $variation_id, '_manage_stock', 'yes' );
        update_post_meta( $variation_id, '_backorders', 'no' );
        $current = wc_stock_amount( $product->get_stock_quantity() );
        if ($current != (int) $quantity) {
            wc_update_product_stock( $variation_id, (int) $quantity );
            if ( (int) $quantity > 0 ) {
                if (!$product->is_in_stock()){
                    wc_update_product_stock_status($variation_id, 'instock');
                }
            } else {
                if ($product->is_in_stock()){
                    wc_update_product_stock_status($variation_id, 'outofstock');
                }
            }
            $updated = true;
        }
        return $updated;
    }

    public function findOrCreateCategory($name,$lang,$parent = 0)
    {
        $slug = strtolower($name);

        if($parent >  0)
        {
            $parent_slug = get_term_meta( $parent, 'bdroppy_slug', true );
            if ( $parent_slug ) {
                $slug = strtolower($parent_slug . '-' . $slug);
            }

            $subcats = get_terms( 'product_cat', [
                'hierarchical'     => 1,
                'show_option_none' => '',
                'hide_empty'       => 0,
                'meta_query' => [
                    [
                        'key'       => 'bdroppy_slug',
                        'value'     => $slug,
                        'compare'   => '='
                    ]
                ]
            ]);

            if (count($subcats) == 1) {
                return $subcats[0]->term_id;
            }

            if($this->system->language->hasWpmlSupport())
            {
                global  $sitepress;
                $try = 0;
                $sender_lang = $sitepress->get_language_code_from_locale($lang);
                $sitepress->switch_lang($sender_lang);
                do{
                    $categories = get_term_by('slug',$slug,'product_cat' );
                    if($categories !== false)
                    {
                        $cat_lang = $sitepress->get_element_language_details($categories->term_id,'tax_product_cat');
                    }
                    $try++;
                }while($try < 5 && ($categories !== false) && isset($cat_lang) && ($categories->parent == $parent) && ($cat_lang->language_code == $sender_lang) );

                if (isset($cat_lang) && $cat_lang->language_code == $sender_lang)
                {
                    return $categories->term_id;
                }
            }

            $term = wp_insert_term(
                $name,
                'product_cat',
                [
                    'description' => $name,
                    'slug'        => $slug,
                    'parent'      => $parent
                ]
            );

            if (is_wp_error( $term )){
                return $term->get_error_data('term_exists');
            }else{
                add_term_meta( $term['term_id'], 'bdroppy_slug', $slug );
                return (int) $term['term_id'];
            }

        } else {

            $categories = get_terms( 'product_cat', [
                'hierarchical'     => 1,
                'show_option_none' => '',
                'hide_empty'       => 0,
                'meta_query' => [
                    [
                        'key'       => 'bdroppy_slug',
                        'value'     => $slug,
                        'compare'   => '='
                    ]
                ]
            ] );
            if (count($categories) == 1) {
                return $categories[0]->term_id;
            }

            if($this->system->language->hasWpmlSupport())
            {
                global  $sitepress;

                $try = 0;
                $sender_lang = $sitepress->get_language_code_from_locale($lang);
                $sitepress->switch_lang($sender_lang);
                $slug = strtolower($sender_lang . '-'.$slug);
                do{
                    $categories = get_term_by('slug',$slug,'product_cat' );
                    if($categories)
                    {
                        $cat_lang = $sitepress->get_element_language_details($categories->term_id,'tax_product_cat');
                    }
                    $try++;
                }while($try < 5 && ($categories !== false) && isset($cat_lang) && ($cat_lang->language_code == $sender_lang) );

                if (isset($cat_lang) && $cat_lang->language_code == $sender_lang)
                {
                    return $categories->term_id;
                }
            }

//            $categories = get_terms( 'product_cat', [
//                'hierarchical'     => 1,
//                'show_option_none' => '',
//                'hide_empty'       => 0,
//                'name'             => $name
//            ]);
//
//            if (count($categories) == 1) {
//                add_term_meta( $categories[0]->term_id, 'bdroppy_slug', $slug );
//                return $categories[0]->term_id;
//            }

            $term = wp_insert_term(
                $name,
                'product_cat',
                [
                    'description' => $name,
                    'slug'        => $slug
                ]
            );

            if (is_wp_error( $term )){
                $this->logger->error( 'findOrCreateCategory', 'An error occurred creating category' . $term->get_error_message() );
                return $term->get_error_data('term_exists');
            }else{
                add_term_meta( $term['term_id'], 'bdroppy_slug', $slug );
                return (int) $term['term_id'];
            }
        }
    }

    private function setProductAttribute( $post_id,ProductMask $product,$lang)
    {
        $productAttributes = [];
        $attributes = ['gender','color', 'season', 'brand'];
        foreach ($attributes as $attribute)
        {
            $value = $product->getTagValue($attribute,$lang);
            $id = $this->config->catalog->getAttribute($attribute);
            $attribute  = $this->getAttributeTaxonomy( $id );
            if($value != null)
            {
                $productAttributes[ $attribute ] = [
                    'name'         => $attribute,
                    'value'        => '',
                    'is_visible'   => '1',
                    'is_variation' => '0',
                    'is_taxonomy'  => '1'
                ];
                wp_set_object_terms( $post_id,[$value], $attribute );
            }
        }

        if (!$product->isSimpleProduct())
        {
            $id = $this->config->catalog->getAttribute('size');
            $attribute  = $this->getAttributeTaxonomy( $id );
            $productAttributes[ $attribute ] = [
                'name'         => $attribute,
                'value'        => '',
                'is_visible'   => '1',
                'is_variation' => '1',
                'is_taxonomy'  => '1'
            ];

//            if(!empty($product->models[0]->color))
//            {
//                $id = $this->config->getAttribute('color');
//                $attribute  = $this->getAttributeTaxonomy( $id );
//                $productAttributes[ $attribute ] = [
//                    'name'         => $attribute,
//                    'value'        => '',
//                    'is_visible'   => '1',
//                    'is_variation' => '1',
//                    'is_taxonomy'  => '1'
//                ];
//            }
        }
        update_post_meta( $post_id, '_product_attributes', $productAttributes );
    }

    private function setProductPrice($post_id,$product)
    {
        if ($this->config->catalog->get('import-retail'))
        {
            if ($product->streetPrice != null)
            {
                update_post_meta( $post_id, '_regular_price', (float) $product->streetPrice );
            }
            update_post_meta( $post_id, '_sale_price', (float) $product->sellPrice );
            update_post_meta( $post_id, '_price', (float) $product->sellPrice );
        }else {
            update_post_meta( $post_id, '_regular_price', (float) $product->sellPrice );
            update_post_meta( $post_id, '_sale_price', null );
            update_post_meta( $post_id, '_price', (float) $product->sellPrice );

        }
    }

    private function getAttributeTaxonomy($id)
    {
        if ( isset( $this->attribute_taxonomies[ $id ] ) ) {
            return $this->attribute_taxonomies[ $id ];
        }
        $attribute = wc_attribute_taxonomy_name_by_id((int)$id);
        $this->attribute_taxonomies[ $id ] = $attribute;
        return $attribute;
    }

    public function updateProductToWooCommerce($productModel, ProductMask $ProductMask,$variable = false)
    {
        if ($variable){

            $item = false;

            foreach ($ProductMask->models as $model )
            {

                if ($productModel->rewix_model_id == $model->id) {
                    $item = $model;
                    break;
                }
            }

            if($item && $this->config->catalog->get('update-prices')) {
                $this->setProductPrice($productModel->wc_model_id, $item);
            }

            $variation = new \WC_Product_Variation( $productModel->wc_model_id);
            $variation->set_stock_quantity( $item ? $item->availability : 0 );
            $variation->set_manage_stock(true);
            $variation->set_stock_status('');
            $variation->set_backorders('no');
            $variation->set_weight($ProductMask->weight);
            $variation->save();
        }

        $p = wc_get_product( $productModel->wc_product_id );
        if ($p){
            if ($this->config->catalog->get('update-prices'))
            {
                $this->setProductPrice($productModel->wc_product_id,$ProductMask);
            }
            if($p->get_type() === "variable"){
                update_post_meta( $productModel->wc_product_id, '_manage_stock', $p->get_type() === "variable" ? 'no':'yes' );
            }else{
                $this->setProductStock( $productModel->wc_product_id, (int) ($ProductMask->availability )  );
            }
        }
        return true;
    }

}