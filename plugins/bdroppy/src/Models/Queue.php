<?php
namespace BDroppy\Models;


use BDroppy\Models\Mask\ProductMask;

if ( ! defined( 'ABSPATH' ) ) exit;

class Queue extends BaseModel
{

    public static $table = 'dropshipping_queues';

    public static function addQueses($catalog_id,$type,$allData,$chunkSize = 50)
    {
        $allData = array_chunk($allData,$chunkSize);
        foreach ($allData as $datas)
        {
            $items = [];
            foreach ($datas as $data)
            {
                $jsonData =  str_replace("\\", "\\\\",json_encode($data));
                $jsonData =  str_replace("'", "\'",$jsonData);

                $items[] = [
                    'id' => md5(time() . $data->id),
                    'rewix_catalog_id' => $catalog_id,
                    'type' => $type,
                    'data' => $jsonData,
                ];
            }
             self::create($items) ;
        }
        return 1;
    }

    public static function getWithMask()
    {
        $data = self::get();
        return count($data) ? array_map([self::class,'getDataMask'],$data) : $data;
    }

    public static function fistWithMask()
    {
        $data = self::first();
        return !is_null($data) ? self::getDataMask($data) : $data;
    }

    public static function getDataMask($queue)
    {
        $queue->data = new ProductMask(json_decode($queue->data));
        return $queue;
    }

}