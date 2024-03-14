<?php namespace BDroppy\CronJob\Jobs;


use BDroppy\Models\Product;
use BDroppy\Models\Queue;

class UpdateCatalogJob extends BaseJob
{

    protected $actionName = 'bdroppy_update_catalog_event';
    private $selectCatalog;
    private $mainCatalog;

    public function handle()
    {
        update_option( 'bdroppy-cron-update-catalog-last-run', (int) time());

        $this->selectCatalog = $this->config->catalog->get('catalog');
        $this->mainCatalog = Product::getActiveCatalog();

        if(Queue::where('type','import')->count())
        {
            $this->logger->info('updateCatalogJob','Queue not Empty - Queues import Count:' . Queue::where('type','import')->count());
        }else if($this->selectCatalog == $this->mainCatalog)
        {
            $this->updateMethod();
        }
        return true;
    }

    public function updateMethod()
    {

        $retry = 0;
        do{
            sleep($retry * 5);
            $result = $this->remote->product
                ->onlyId($this->selectCatalog,$this->activeLanguages);
            $retry++;
        }while($retry <= 3 && !$result);
        if(!$result) return false;

        $shopProducts = Product::pluck('rewix_product_id');

        if(!$this->config->catalog->get('delete-products',false)){
            $needRemove = array_diff($shopProducts,$result->items);
            $this->deleteProducts($needRemove);
            $this->logger->info('updateCatalogJob','count delete product : ' . count($needRemove));
        }

        $needImportIds = array_diff($result->items,$shopProducts);
        $this->importProducts($needImportIds);

        $this->logger->info('updateCatalogJob','count import product : ' . count($needImportIds));
        return true;
    }

    private function importProducts($data)
    {
        $pageSize = 100;
        $chunks = array_chunk($data,$pageSize);
        foreach ($chunks as $key => $chunk)
        {
            if ($key > 10) return 1;
            $retry = 0;
            do{
                sleep($retry * 5);
                $retry++;
                $result = $this->remote->product
                    ->page(1)
                    ->pageSize($pageSize)
                    ->ids($chunk)
                    ->all($this->selectCatalog,$this->activeLanguages);
            }while($retry <= 3 && !$result);
            if(!$result) return false;
            Queue::addQueses($this->selectCatalog,'import',$result->items);
        }
        return true;
    }

    private function deleteProducts($rewix_product_ids = null)
    {
        $products = Product::whereIn('rewix_product_id',$rewix_product_ids)
            ->where('wc_product_id','!=',0)->get();

        if (count($products) == 0) return false;

        if(!$this->config->catalog->get('add-image-url-tools',false))
        {
            $products = implode(',',array_column($products,'wc_product_id'));
            global $wpdb;
            $prefix = $wpdb->prefix;
            $sql1 = "DELETE relations.* FROM ".$prefix ."term_relationships AS relations WHERE object_id IN (".$products.") or object_id IN (SELECT id from ".$prefix."posts where post_parent in  ( ".$products.")) ;";
            $sql2 = "DELETE FROM ".$prefix."postmeta WHERE post_id IN (".$products.") or post_id in (SELECT id from ".$prefix."posts where post_parent in  ( ".$products.")); ";
            $sql3 = "DELETE FROM ".$prefix."posts WHERE id IN (".$products.") or post_parent in (".$products.");";

            $wpdb->query($sql1);
            $wpdb->query($sql2);
            $wpdb->query($sql3);
            Product::whereIn('rewix_product_id',$rewix_product_ids)->delete();
        }else{
            foreach ($products as $product){
                Queue::create([
                    'id' => md5(time() . $product->rewix_product_id),
                    'rewix_catalog_id' => $this->selectCatalog,
                    'type' => 'delete',
                    'data' => json_encode($product),
                ]);
            }
        }
        return true;
    }


    // old methods
    public function updateMethodOld()
    {

        $pageSize = 500;
        $page = 1;
        $ids = [];
        $allProducts = [];
        do{
            $this->logger->info('updateCatalogJob','page : ' . $page);
            $retry = 0;
            do{
                sleep($retry * 3);
                $retry++;
                $result = $this->remote->product
                    ->page($page)
                    ->pageSize($pageSize)
                    ->all($this->selectCatalog,$this->activeLanguages);
            }while($retry <= 3 && !$result);
            if(!$result) return false;

            $ids = array_merge($ids,array_column($result->items,'id'));
            $allProducts = array_merge($allProducts,$result->items);
            $page++;
        }while($page <= $result->totalPages);

        $shopProducts = Product::pluck('rewix_product_id');

        $needRemove = array_diff($shopProducts,$ids);
        $this->deleteProducts($needRemove);
        $this->logger->info('updateCatalogJob','count delete product : ' . count($needRemove));

        $needImportIds = array_diff($ids,$shopProducts);
        $newProducts = array_filter($allProducts, function ($var) use ($needImportIds) {
            return in_array($var->id, $needImportIds);
        });
        Queue::addQueses($this->selectCatalog,'import',$newProducts);
        $this->logger->info('updateCatalogJob','count import product : ' . count($newProducts));
        return true;
    }

}