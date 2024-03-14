<?php namespace BDroppy\CronJob\Jobs;


use BDroppy\Models\Product;
use BDroppy\Models\ProductModel;
use BDroppy\Models\Queue;

class QueueJob extends BaseJob
{

    protected $actionName = 'bdroppy_queues_event';
    private $selectCatalog;
    private $catalogLastUpdate;


    public function handle()
    {
        update_option('bdroppy-cron-queue-last-run', (int)time());
        if (Queue::count() == 0)
            return 0;
//        if (Queue::where('in_process', 1)->count() > $this->getQueueSize() * 2)
//            return $this->logger->info('QueuetJob', 'Queue In Processing Is Full o_^');

        $this->catalogLastUpdate = get_option('bdroppy-cron-catalog-last-update', null);
        $this->selectCatalog = $this->config->catalog->get('catalog');

        // Delete Queues From Other Catalog
        Queue::where('rewix_catalog_id', '!=', $this->selectCatalog)->delete();
        if(Product::where('lang','null')->count() > 0)
        {
            $this->changeDetaFromOldVersion();
        }

        if (Queue::where('type', 'delete')->count()) {
            return $this->deleteMethod();
        } else if (Queue::where('type', 'import')->count()) {
            return $this->importMethod();
        } else if (Queue::where('type', 'update')->count()) {
            return $this->updateMethod();
        } else {
//            return $this->logger->info('QueuetJob', 'Noting to Change');
        }
    }

    private function changeDetaFromOldVersion()
    {
        $products = Product::where('lang','null')->limit(50)->get();
        foreach ($products as $product){

            if ($this->system->language->hasWpmlSupport())
            {
                global $sitepress;
                $def_lang = $sitepress->get_default_language();
                $trid = $sitepress->get_element_trid($product->wc_product_id);
                $langs = $sitepress->get_element_translations($trid, 'post_product');

                foreach ($langs as $lang => $p)
                {
                    if($def_lang == $lang){
                        Product::where('id',$product->id)->update([
                            'lang' => $lang,
                            'import_base' => 1,
                            'import_images' => 1,
                            'import_categories' => 1,
                            'import_tags' => 1,
                            'import_attributes' => 1,
                            'import_models' => 1,
                        ]);
                    }
                }

                foreach ($langs as $lang => $p)
                {
                    if($def_lang != $lang){

                        if(! Product::where('wc_product_id',$p->element_id)->count())
                        {
                            Product::create([
                                'rewix_product_id' => $product->rewix_product_id,
                                'rewix_catalog_id' => $product->rewix_catalog_id,
                                'wc_product_id' => $p->element_id,
                                'last_update_at' => $product->last_update_at,
                                'parent' => $product->id,
                                'lang' => $lang,
                                'import_base' => 1,
                                'import_images' => 1,
                                'import_categories' => 1,
                                'import_tags' => 1,
                                'import_attributes' => 1,
                                'import_models' => 1,
                            ]);
                        }
                    }
                }

            }else{
                $productModel = ProductModel::where('rewix_product_id',$product->rewix_product_id)->get();
                Product::update([
                    'lang' => $productModel[0]->lang,
                    'import_base' => 1,
                    'import_images' => 1,
                    'import_categories' => 1,
                    'import_tags' => 1,
                    'import_attributes' => 1,
                    'import_models' => 1,
                ]);
            }
        }
    }
    private function importMethod()
    {
        $retry = 0;
        $cron = time();
        $item_processing =0;

        update_option("bdroppy-cron-queue-import-cron-run",$cron,false);
        do {
            $retryCPU = 0;
            wp_cache_delete("bdroppy-cron-queue-import-cron-run",'options');
            $db_cron = get_option("bdroppy-cron-queue-import-cron-run");
            $this->logger->debug('QueuetJob',$cron. " :::::: " . $db_cron );

            while (!$this->system->info->getCPULoadAvarage(1)) {
                if ($retryCPU > 3){
                    delete_option("bdroppy-cron-queue-import-cron-item");
                    $this->logger->debug('QueuetJob',$cron . " Cron is Killed - load avarage : ". $this->system->info->getCPULoadAvarage());
                    return false;
                }
                sleep(5000);
                $retryCPU++;
            }

            if($db_cron != $cron || $retry > 5 )
            {
                delete_option("bdroppy-cron-queue-import-cron-item");
                $this->logger->debug('QueuetJob',$cron . " Cron is Killed - load avarage : ". $this->system->info->getCPULoadAvarage());
                return false;
            }
            $item_processing++;
            $queue = Queue::where('type', 'import')
                ->orderBy('create_at')
                ->fistWithMask();
            if (is_null($queue)) return false;

            wp_cache_delete("bdroppy-cron-queue-import-cron-item",'options');
            $set_item_to_cron = update_option("bdroppy-cron-queue-import-cron-item",$queue->id,false);
            if($set_item_to_cron == false)
            {
                $retry++;
                sleep(1);
                $this->logger->debug('QueuetJob',$queue->id . " Queue Processing by Other Cron");
                continue;
            }
            Queue::where('id', $queue->id)->delete();
            if (Product::where('rewix_product_id', $queue->data->id)
                    ->where('rewix_catalog_id', $queue->rewix_catalog_id)->count() == 0) {

                try {
                    if ($this->system->language->hasWpmlSupport()) {
                        $wc_product = $this->importMultiLanguage($queue);
                    } else {
                        $lang = $this->config->catalog->get('import-language',get_locale());
                        Product::create([
                            'rewix_product_id' => $queue->data->id,
                            'rewix_catalog_id' => $queue->rewix_catalog_id,
                            'lang' => $lang,
                            'wc_product_id' => "0",
                        ]);
                        $wc_product = $this->wc->importer->importProductToWooCommerce($queue->data, $lang);
                    }
                    $this->logger->info('QueuetJob', $cron .':: import product : ' . $wc_product);
                } catch (\Exception $exception) {
                    $this->logger->error('QueuetJob', $queue->data);
                    $this->logger->error('QueuetJob', $exception->getMessage());
                    Product::where('rewix_product_id', $queue->data->id)
                        ->where('rewix_catalog_id', $queue->rewix_catalog_id)
                        ->delete();
                }
            }

        } while ($item_processing < $this->getQueueSize());

        return true;
    }

    private function importMultiLanguage($queue)
    {
        global $sitepress;
        $default_language = $sitepress->get_language_details($sitepress->get_default_language());
        $sitepress->switch_lang($default_language['code']);
        Product::create([
            'rewix_product_id' => $queue->data->id,
            'rewix_catalog_id' => $queue->rewix_catalog_id,
            'lang' => $default_language['default_locale'],
            'wc_product_id' => "0",
        ]);
        $bdroppy_product = Product::
        where('rewix_product_id' , $queue->data->id)
        ->where('rewix_catalog_id' , $queue->rewix_catalog_id)
        ->where('lang' , $default_language['default_locale'])->first();

        $wc_product = $this->wc->importer
            ->importProductToWooCommerce($queue->data, $default_language['default_locale']);

        $def_trid = $sitepress->get_element_trid($wc_product);
        foreach ($sitepress->get_active_languages() as $active_language) {
            if ($active_language['default_locale'] !== $default_language['default_locale']
                && isset($queue->data->descriptions->{$active_language['default_locale']})) {
                Product::create([
                    'rewix_product_id' => $queue->data->id,
                    'rewix_catalog_id' => $queue->rewix_catalog_id,
                    'lang' => $active_language['default_locale'],
                    'wc_product_id' => "0",
                    'parent' => $bdroppy_product->id,
                ]);
                $sitepress->switch_lang($active_language['code']);
                $wc_lang = $this->wc->importer->importProductToWooCommerce(
                    $queue->data, $active_language['default_locale'], $wc_product);

                $sitepress->set_element_language_details(
                    $wc_lang, 'post_product', $def_trid, $active_language['code']);
            }
        }
        return $wc_product;
    }

        private function getQueueSize()
    {
        $result = $this->config->catalog->get('import-per-minute',5);

        if ($this->config->catalog->get('add-image-url-tools',false) && $result > 10) {
            $result = 10;
        }

        return $result;
    }

    private function deleteMethod()
    {
        $item_processing = 0;
        do {
            $retryCPU = 0;
            $queue = Queue::where('type', 'delete')
                ->orderBy('create_at')
                ->fistWithMask();

            while (!$this->system->info->getCPULoadAvarage(1)) {
                if ($retryCPU > 3){
                    return false;
                }
                sleep(5000);
                $retryCPU++;
            }
            if (is_null($queue)) return false;

            Queue::where('id', $queue->id)->delete();

            if ($queue->data->wc_product_id !== 0) {
                $this->removeProduct($queue->data->wc_product_id);
            }
            Product::where('id', $queue->data->id)->delete();


            $item_processing++;
        } while ($item_processing <= ($this->getQueueSize() * 5));
        return true;
    }

    public function removeProduct($product_id)
    {
        if ($this->system->language->hasWpmlSupport()) {
            global $sitepress;
            $trid = $sitepress->get_element_trid($product_id);
            $langs = $sitepress->get_element_translations($trid, 'post_product');


            foreach ($langs as $lang) {
                wp_delete_post($lang->element_id, 1);
            }
        }

        delete_post_thumbnail($product_id);
        $metas = get_post_custom_keys($product_id);

        if (isset($metas) && count($metas) > 0) {
            foreach ($metas as $meta) {
                delete_post_meta($product_id, $meta);
            }
        }

        $product_variation = get_posts([
            'numberposts' => 100,
            'post_parent' => $product_id,
            'post_type' => 'product_variation'
        ]);
        foreach ($product_variation as $variation) {
            if (isset($variation->ID)) {
                wp_delete_post($variation->ID, 1);
            }
        }

        $images = get_children([
            'numberposts' => 100,
            'post_parent' => $product_id
        ]);
        foreach ($images as $image) {
            wp_delete_attachment($image->ID);
        }

        wp_delete_post($product_id, 1);
    }

    private function updateMethod()
    {
        $retry = 0;

        $cron = time();
        $item_processing = 0;

        update_option("bdroppy-cron-queue-update-cron-run",$cron,false);
        do {
            wp_cache_delete("bdroppy-cron-queue-update-cron-run",'options');
            $db_cron = get_option("bdroppy-cron-queue-update-cron-run");
            $this->logger->debug('QueuetJob',$cron. " :::::: " . $db_cron );
            $retryCPU = 0;
            while (!$this->system->info->getCPULoadAvarage(1)) {
                if ($retryCPU > 3){
                    delete_option("bdroppy-cron-queue-update-cron-item");
                    $this->logger->debug('QueuetJob',$cron . " Cron is Killed - load avarage : ". $this->system->info->getCPULoadAvarage());
                    return false;
                }
                sleep(5000);
                $retryCPU++;
            }

            if($db_cron != $cron || $retry > 5)
            {
                delete_option("bdroppy-cron-queue-update-cron-item");
                $this->logger->debug('QueuetJob',$cron . " Cron is Killed - load avarage : ". $this->system->info->getCPULoadAvarage());
                return false;
            }

            $queue = Queue::where('type', 'update')
                ->orderBy('create_at')
                ->fistWithMask();

            if (is_null($queue)) return false;

            wp_cache_delete("bdroppy-cron-queue-update-cron-item",'options');
            $set_item_to_cron = update_option("bdroppy-cron-queue-update-cron-item",$queue->id,false);
            if($set_item_to_cron == false)
            {
                $retry++;
                sleep(1);
                $this->logger->debug('QueuetJob',$queue->id . " Queue Processing by Other Cron");
                continue;
            }


            Queue::where('id', $queue->id)->delete();
            $product = Product::where('rewix_product_id', $queue->data->id)->first();

            if (!isset($product->wc_product_id) || empty(get_post($product->wc_product_id))) {
                Queue::where('id', $queue->id)->delete();
                if (isset($product->wc_product_id) && $product->wc_product_id != 0) {
                    Product::where('rewix_product_id', $queue->data->id)->delete();
                }
                continue;
            }


            $productModels = ProductModel::where('rewix_product_id', $queue->data->id)->get();


            foreach ($productModels as $productModel) {
                if (!isset($productModel->wc_model_id) || empty(get_post($productModel->wc_model_id))) {
                    continue;
                }
                $variable = ($productModel->wc_model_id !== $productModel->wc_product_id) ? 1 : 0;
                $this->wc->importer->updateProductToWooCommerce($productModel, $queue->data, $variable);
            }
            $addModelIds = array_diff(array_column($queue->data->models, 'id'), array_column($productModels, 'rewix_model_id'));
            $this->logger->debug('QueuetJob','id : ' .json_encode( array_column($queue->data->models, 'id')));
            $this->logger->debug('QueuetJob','rewix_model_id  ' . json_encode( array_column($productModels, 'rewix_model_id')));
            $this->logger->debug('QueuetJob',json_encode($addModelIds));
            // add product Variations if not Exists
            foreach ($queue->data->models as $model)
            {
                if (in_array($model->id, $addModelIds))
                {
                    $BdroppyProduct = Product::where('rewix_product_id', $queue->data->id)->first();
                    if ($BdroppyProduct && isset($BdroppyProduct->wc_product_id) && $BdroppyProduct->wc_product_id != 0)
                    {
                        $productId = $BdroppyProduct->wc_product_id;
                        if ($this->system->language->hasWpmlSupport())
                        {
                            global $sitepress;
                            foreach ($sitepress->get_active_languages() as $active_language) {
                                $productModel = ProductModel::where('rewix_product_id', $queue->data->id)
                                    ->where('lang',$active_language['default_locale'])->first();

                                $this->wc->importer->addVariationToProduct($productModel->wc_product_id, $queue->data, $model, $active_language['default_locale']);
                            }
                        } else {
                            $lang = $this->config->catalog->get('import-language',get_locale());
                            $this->wc->importer->addVariationToProduct($productId, $queue->data, $model, $lang);
                        }
                    }
                }
            }

            $item_processing++;
        } while ($item_processing <= 200);
        return true;
    }

    public function importMethodUploadImage($product)
    {
        return $this->wc->importer->importProductImages($product['id'], $product['pictures']);
    }


}