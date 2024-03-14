<?php namespace BDroppy\CronJob\Jobs;

use BDroppy\Models\Product;
use BDroppy\Models\Queue;

class ChangeCatalogJob extends BaseJob
{

    protected $actionName = 'bdroppy_change_catalog_event';
    private $selectCatalog;
    private $mainCatalog;
    private $catalogLastUpdate;


    public function handle()
    {
        update_option( 'bdroppy-cron-change-catalog-last-run', (int) time());

        $this->catalogLastUpdate = get_option('bdroppy-cron-catalog-last-update',null);
        $this->selectCatalog = $this->config->catalog->get('catalog');
        $this->mainCatalog = Product::getActiveCatalog();

        if(Queue::count())
        {
            $this->logger->info('changeCatalogJob','Queue not Empty - Queues Count:' . Queue::count());
        }else if(($this->selectCatalog !== $this->mainCatalog
        ||  get_option('bdroppy-cron-change-catalog-insert-try-page',false)) )
        {
            $this->deleteOldProduct();

            if($this->selectCatalog != 0 ) {
                $this->multiLangaugeSetting();
                $this->insertMethod();
            }

        }else{
//            $this->logger->info('changeCatalogJob','Noting to Change');
        }
    }

    public function multiLangaugeSetting()
    {
        if ($this->system->language->hasWpmlSupport()) {
            global $sitepress;
            $iclsettings['custom_posts_sync_option']['product'] = 1;
            $iclsettings['custom_posts_sync_option']['product_variation'] = 1;
            $iclsettings['taxonomies_sync_option']['product_cat'] = 1;
            $iclsettings['taxonomies_sync_option']['product_tag'] = 1;
            $sitepress->save_settings($iclsettings);
        }
    }

    private function insertMethod()
    {
        $this->logger->info('changeCatalogJob','insert Method');
        $pageSize = 500;
        do{
            $page = get_option('bdroppy-cron-change-catalog-insert-try-page',1);
            $this->logger->info('changeCatalogJob','page : ' . $page);
            $setNextPage = update_option('bdroppy-cron-change-catalog-insert-try-page',$page+1);
            $result = $this->remote->product
                ->page($page)
                ->pageSize($pageSize)
                ->all($this->selectCatalog,$this->activeLanguages);
            if(!$result || !$setNextPage) return $this->logger->debug('changeCatalogJob','result or set next page has error');
            Queue::addQueses($this->selectCatalog,'import',$result->items , $pageSize/10);

            if($page == 1) update_option('bdroppy-cron-catalog-last-update',$result->lastUpdate);
            $page++;
        }while($page <= $result->totalPages);
        return delete_option('bdroppy-cron-change-catalog-insert-try-page');
    }

    private function deleteOldProduct()
    {
        $products = Product::where('rewix_catalog_id','!=',$this->selectCatalog)
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
            Product::where('rewix_catalog_id','!=',$this->selectCatalog)->delete();
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


}