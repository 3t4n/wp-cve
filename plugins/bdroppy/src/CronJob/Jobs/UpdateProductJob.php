<?php namespace BDroppy\CronJob\Jobs;



use BDroppy\Models\Product;
use BDroppy\Models\Queue;

class UpdateProductJob extends BaseJob
{

    protected $actionName = 'bdroppy_update_product_event';
    private $selectCatalog;
    private $mainCatalog;
    private $catalogLastUpdate;

    public function handle()
    {

        update_option( 'bdroppy-cron-update-product-last-run', (int) time());

        $this->catalogLastUpdate = get_option('bdroppy-cron-catalog-last-update',null);

        $this->selectCatalog = $this->config->catalog->get('catalog');
        $this->mainCatalog = Product::getActiveCatalog();

        if(Queue::count())
        {
            Queue::where('DATE_ADD(create_at, INTERVAL 30 MINUTE) < NOW()')
                ->where('in_process',1)
                ->where('type','update')
                ->update(["in_process" =>0]);
        }else if($this->selectCatalog == $this->mainCatalog)
        {
            $this->updateMethod();
        }
        return true;
    }

    public function updateMethod()
    {
        $pageSize = 1000;
        $page = 1;
        $allProducts = [];
        $timestamp = strtotime($this->catalogLastUpdate) +1;
        $since = date('Y-m-d\TH:i:s.v', $timestamp) . 'Z';

        do{
            $this->logger->info('updateProductJob','page : ' . $page);
            $retry = 0;
            do{
                sleep($retry * 5);
                $retry++;
                $result = $this->remote->product
                    ->page($page)
                    ->pageSize($pageSize)
                    ->since($since)
                    ->light()
                    ->all($this->selectCatalog,$this->activeLanguages);
            }while($retry <= 3 && !$result);
            if(!$result) {
                return false;
            }

            $allProducts = array_merge($allProducts,$result->items);
            if($page == 1 && count($allProducts) > 0) update_option('bdroppy-cron-catalog-last-update',$result->lastUpdate);
            $page++;
        }while($page <= $result->totalPages);

        Queue::addQueses($this->selectCatalog,'update',$allProducts);
        return true;
    }



}