<?php
/**
 * アフィリエイトサービス実装クラス(ValueCommerce)
 * User: cottonspace
 * Date: 12/05/02
 */

/**
 * 基底クラス
 */
require_once "service-base.php";

/**
 * アフィリエイトサービスの実装クラス
 */
class ValueCommerce extends ServiceBase
{
    /**
     * 商品検索ソート方法
     * @var array 商品ソート指定の配列
     */
    private $sortTypes = array(
        "+price" => "price,asc",
        "-price" => "price,desc"
    );

    /**
     * 商品画像URL取得
     * @param \SimpleXMLElement $elements 商品画像のXML要素
     * @return string 画像のURL
     */
    private function getImageUrl(&$elements)
    {
        $hash = array();
        foreach ($elements as $element) {
            if ($url = (string)$element->attributes()->url) {
                $hash[(string)$element->attributes()->class] = $url;
            }
        }
        foreach (array("large", "small", "free") as $name) {
            if (array_key_exists($name, $hash)) {
                return $hash[$name];
            }
        }
        return "";
    }

    /**
     * カテゴリ検索クエリ生成
     * @link http://devcenter.valuecommerce.ne.jp/sdk/pdb_reference
     * @param string $category 対象カテゴリ
     * @return string RESTクエリ文字列
     */
    private function queryCategories($category)
    {
        if (empty($category)) {
            $category = "";
        }
        $baseurl = "http://webservice.valuecommerce.ne.jp/productdb/category";
        $params = array();
        $params["token"] = $this->account["token"];
        if (!empty($category)) {
            $params["category_name"] = $category;
            $params["category_level"] = substr_count($category, ",") + 2;
        } else {
            $params["category_level"] = 1;
        }
        ksort($params);
        return $baseurl . "?" . http_build_query($params) . "&childless";
    }

    /**
     * 商品検索クエリ生成
     * @link http://devcenter.valuecommerce.ne.jp/sdk/pdb_reference
     * @return string RESTクエリ文字列
     */
    private function queryItems()
    {
        $baseurl = "http://webservice.valuecommerce.ne.jp/productdb/search";
        $params = array();
        $params["token"] = $this->account["token"];
        if (!empty($this->requests["keyword"])) {
            $params["keyword"] = $this->requests["keyword"];
        }
        if (!empty($this->requests["category"])) {
            $params["category"] = $this->requests["category"];
        }
        if (!empty($this->requests["shop"])) {
            $params["ec_code"] = $this->requests["shop"];
        }
        $params["results_per_page"] = $this->requests["pagesize"];
        $params["page"] = $this->requests["pagenum"];
        if (!empty($this->requests["sort"]) && array_key_exists($this->requests["sort"], $this->sortTypes)) {
            $sort_array = explode(',', $this->sortTypes[$this->requests["sort"]], 2);
            $params["sort_by"] = $sort_array[0];
            $params["sort_order"] = $sort_array[1];
        }
        ksort($params);
        return $baseurl . "?" . http_build_query($params);
    }

    /**
     * サービス識別名
     * @return string サービス識別名
     */
    public function serviceName()
    {
        return "valuecommerce";
    }

    /**
     * 商品検索ソート方法取得
     * @return array ソート指定の連想配列
     */
    public function getSortTypes()
    {
        return $this->sortTypes;
    }

    /**
     * カテゴリ検索
     * @link http://devcenter.valuecommerce.ne.jp/sdk/pdb_reference
     * @param string $category 基底カテゴリ
     * @return array カテゴリ情報の連想配列
     */
    public function getCategories($category = "")
    {
        // RESTクエリ情報を取得
        $query = $this->queryCategories($category);

        // RESTクエリ実行
        $strxml = $this->download($query, $query);
        $strxml = str_replace("<vc:", "<", $strxml);
        $strxml = str_replace("</vc:", "</", $strxml);
        $objxml = simplexml_load_string($strxml);
        $hash = array();
        if (isset($objxml->channel->item)) {
            foreach ($objxml->channel->item as $node) {
                $description = (string)$node->description;
                $description = str_replace("、", "・", $description);
                $description = array_pop(explode(",", $description));
                $hash[(string)$node->title] = $description;
            }
        }
        return $hash;
    }

    /**
     * 商品検索
     * @link http://devcenter.valuecommerce.ne.jp/sdk/pdb_reference
     * @return array 商品情報の連想配列
     */
    public function getItems()
    {
        // RESTクエリ情報を取得
        $query = $this->queryItems();

        // RESTクエリ実行
        $strxml = $this->download($query, $query);
        $strxml = str_replace("<vc:", "<", $strxml);
        $strxml = str_replace("</vc:", "</", $strxml);
        $objxml = simplexml_load_string($strxml);
        $hash = array();
        if (isset($objxml->channel->item)) {
            $this->pages = intval($objxml->channel->pagecount);
            foreach ($objxml->channel->item as $node) {
                array_push($hash, array(
                        "name" => (string)$node->title,
                        "price" => number_format(floatval((string)$node->price)) . " 円",
                        "desc" => (string)$node->description,
                        "shop" => empty($node->subStoreName) ? (string)$node->merchantName : (string)$node->subStoreName,
                        "score" => 0,
                        "aurl" => (string)$node->link,
                        "iurl" => $this->getImageUrl($node->image),
                        "surl" => substr((string)$node->guid, 0, strpos((string)$node->guid, '/', 7)) . '/' // 商品ページURLから生成
                    )
                );
            }
        } else {
            $this->pages = 0;
        }
        return $hash;
    }
}
