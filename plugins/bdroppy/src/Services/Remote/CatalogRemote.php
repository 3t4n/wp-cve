<?php
namespace BDroppy\Services\Remote;

use mysql_xdevapi\Exception;

if (!defined('ABSPATH')) exit;


class CatalogRemote extends BaseServer
{



    public function all()
    {
        $response = $this->get('user_catalog/list');
        if ($response["response"]['code'] !== 200) {
            return 500;
        } else {
            return $response['body'];
        }
    }

    public function getbyCatalogId($catalog_id)
    {
        $catalogs = $this->all();

        if ($catalogs == 500) {
            return 500;
        } else {
            foreach ($catalogs as $catalog)
            {
                if($catalog->_id == $catalog_id)
                {
                    return $catalog;
                }
            }
            return 0;
        }
    }

    public function getSingleCatalogById($catalog_id)
    {
        if ($catalog_id == 0) {
            return 500;
        }
        $response = $this->get('user_catalog/' . $catalog_id);
        if ($response['response']['code'] !== 200) return 500;

        return $response['body'];
    }

    public function getCatalogs() {
        $catalogs = [
            [
                'id'=> 0,
                'name'=> 'No Catalog',
            ]
        ];
        $res = $this->all();
        if (is_null($res) || $res === 500) return 500;
        foreach ($res as $r)
        {
            $catalogs[] = [
                'id' => $r->_id,
                'name' => isset($r->name)? $r->name ." ( $r->currenct ) ( ". $r->count." products )" : null,
            ];
        }
        return $catalogs;
    }

}