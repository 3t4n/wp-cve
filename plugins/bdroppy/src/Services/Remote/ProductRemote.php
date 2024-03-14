<?php
namespace BDroppy\Services\Remote;

if (!defined('ABSPATH')) exit;

class ProductRemote extends BaseServer
{

    public function onlyId($catalog,$langs = ['en_US'])
    {
        if ( empty($catalog) ) return false;
        $this->catalog($catalog);
        $this->languages($langs);
        $this->addParams(['onlyid' => 'true']);
        $response = $this->get('export/api/products.json');
        if ($response['response']['code'] !== 200) return false;

        $ids = [];
        foreach ($response['body']->items as $key => $item) {
            $ids[] = $item->refId;
        }
        $result = $response['body'];
        $result->items = $ids;
        return $result;
    }

    public function all($catalog,$langs = ['en_US'])
    {
        if ( empty($catalog)  || $catalog == 0) return false;
        $this->catalog($catalog);
        $this->languages($langs);

        $response = $this->get('export/api/products.json');

        if ($response['response']['code'] !== 200) return false;

        return $response['body'];
    }

    public function filters($filters)
    {
        foreach ($filters as $key => $filter) {
            switch ($key) {
                case 'category':
                    $this->addParams(['tag_4' => strtolower($filter)]);
                    break;
                case 'subcategory':
                    $this->addParams(['subcategory' => $filter]);
                    break;
                case 'brand':
                    $this->addParams(['tag_1' => $filter]);
                    break;
                case 'gender':
                    $this->addParams(['tag_26' => $filter]);
                    break;
                case 'season':
                    $this->addParams(['tag_11' => $filter]);
                    break;
            }
        }
        return $this;
    }

    public function page($page)
    {
        $this->addParams(['page' => $page]);
        return $this;
    }

    public function ids($ids)
    {
        $this->addParams(['ids' => implode(',',$ids)]);
        return $this;
    }

    public function pageSize($perPage)
    {
        $this->addParams(['pageSize' => $perPage]);
        return $this;
    }

    public function languages($langs)
    {
        $this->addParams(['acceptedlocales' => implode(',',$langs)]);
        return $this;
    }

    public function light($true = 'true')
    {
        $this->addParams(['light_plus' => $true]);
        return $this;
    }

    public function since($since)
    {
        if (!is_null($since)){
            $this->addParams(['since' => $since]);
        }
        return $this;
    }

    public function catalog($catalog)
    {
        $this->addParams(['user_catalog' => $catalog]);
    }



}