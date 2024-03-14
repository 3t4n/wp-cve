<?php
/**
 * アフィリエイトサービス実装クラス(Yahoo!ショッピング)
 * User: cottonspace
 * Date: 12/04/25
 */

/**
 * 基底クラス
 */
require_once "service-base.php";

/**
 * アフィリエイトサービスの実装クラス
 */
class Yahoo extends ServiceBase
{
    /**
     * 商品検索ソート方法
     * @var array 商品ソート指定の配列
     */
    private $sortTypes = array(
        "+price" => "+price",
        "-price" => "-price",
        "-sales" => "-sold",
        "-reviews" => "-review_count",
        "+reviews" => "+review_count"
    );

    /**
     * 価格表示フォーマット処理
     * @param string $price 商品価格
     * @param string $currency 通貨単位
     * @param string $tax 消費税区分
     * @param string $shipping 配送条件コード
     * @return string 価格表示文字列
     */
    private function formatPrice($price, $currency, $tax, $shipping)
    {
        $ret = "";
        if (!empty($price)) {
            $ret = number_format(floatval($price));
            if ($currency == "JPY") {
                $ret .= " 円";
            } else {
                $ret .= " " . $currency;
            }
            if ($tax == "true") {
                $ret .= " (税込)";
            }
            if ($shipping == "2") {
                $ret .= " 送料込";
            }
            if ($shipping == "3") {
                $ret .= " 条件有 送料込";
            }
        }
        return $ret;
    }

    /**
     * カテゴリ検索クエリ生成
     * @link http://developer.yahoo.co.jp/webapi/shopping/shopping/v1/categorysearch.html
     * @param string $category 対象カテゴリ
     * @return string RESTクエリ文字列
     */
    private function queryCategories($category)
    {
        if (empty($category)) {
            $category = 1;
        }
        $baseurl = "http://shopping.yahooapis.jp/ShoppingWebService/V1/categorySearch";
        $params = array();
        $params["appid"] = $this->account["appid"];
        $params["affiliate_type"] = "yid";
        $params["affiliate_id"] = $this->account["affiliate_id"];
        $params["category_id"] = $category;
        ksort($params);
        return $baseurl . "?" . http_build_query($params);
    }

    /**
     * 商品検索クエリ生成
     * @link http://developer.yahoo.co.jp/webapi/shopping/shopping/v1/itemsearch.html
     * @return string RESTクエリ文字列
     */
    private function queryItems()
    {
        $baseurl = "http://shopping.yahooapis.jp/ShoppingWebService/V1/itemSearch";
        $params = array();
        $params["appid"] = $this->account["appid"];
        $params["affiliate_type"] = "yid";
        $params["affiliate_id"] = $this->account["affiliate_id"];
        if (!empty($this->requests["keyword"])) {
            $params["query"] = $this->requests["keyword"];
        }
        if (!empty($this->requests["shop"])) {
            $params["store_id"] = $this->requests["shop"];
        }
        $params["type"] = "all";
        $params["category_id"] = empty($this->requests["category"]) ? "1" : $this->requests["category"];
        $params["hits"] = $this->requests["pagesize"];
        $params["offset"] = $this->requests["pagesize"] * ($this->requests["pagenum"] - 1);
        if (!empty($this->requests["sort"]) && array_key_exists($this->requests["sort"], $this->sortTypes)) {
            $params["sort"] = $this->sortTypes[$this->requests["sort"]];
        }
        $params["availability"] = "1";
        ksort($params);
        return $baseurl . "?" . http_build_query($params);
    }

    /**
     * サービス識別名
     * @return string サービス識別名
     */
    public function serviceName()
    {
        return "yahoo";
    }

    /**
     * サービスクレジット表記
     * @return string サービスクレジット表記
     */
    public function serviceCredit()
    {
        $credit = <<<EOF
<!-- Begin Yahoo! JAPAN Web Services Attribution Snippet -->
<a href="http://developer.yahoo.co.jp/about">
<img src="http://i.yimg.jp/images/yjdn/yjdn_attbtn1_125_17.gif" title="Webサービス by Yahoo! JAPAN" alt="Web Services by Yahoo! JAPAN" width="125" height="17" border="0" style="margin:15px 15px 15px 15px"></a>
<!-- End Yahoo! JAPAN Web Services Attribution Snippet -->\n
EOF;
        return $credit;
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
     * @link http://developer.yahoo.co.jp/webapi/shopping/shopping/v1/categorysearch.html
     * @param string $category 基底カテゴリ
     * @return array カテゴリ情報の連想配列
     */
    public function getCategories($category = "")
    {
        if (empty($category)) {
            $category = 1;
        }

        // RESTクエリ情報を取得
        $query = $this->queryCategories($category);

        // RESTクエリ実行
        $strxml = $this->download($query, $query);
        $objxml = simplexml_load_string($strxml);
        $hash = array();
        if (isset($objxml->Result->Categories->Children)) {
            foreach ($objxml->Result->Categories->Children->Child as $node) {
                $hash[(string)$node->Id] = str_replace("、", "・", (string)$node->Title->Medium);
            }
        }
        return $hash;
    }

    /**
     * 商品検索
     * @link http://developer.yahoo.co.jp/webapi/shopping/shopping/v1/itemsearch.html
     * @return array 商品情報の連想配列
     */
    public function getItems()
    {
        // RESTクエリ情報を取得
        $query = $this->queryItems();

        // RESTクエリ実行
        $strxml = $this->download($query, $query);
        $objxml = simplexml_load_string($strxml);
        $hash = array();
        $itemcount = intval((string)$objxml->attributes()->totalResultsAvailable);
        if (0 < $itemcount) {
            $this->pages = ceil(min(1000, $itemcount) / $this->requests["pagesize"]);
            foreach ($objxml->Result->Hit as $node) {
                array_push($hash, array(
                        "name" => (string)$node->Name,
                        "price" => $this->formatPrice((string)$node->Price, (string)$node->Price->attributes()->currency, (string)$node->PriceLabel->attributes()->taxIncluded, (string)$node->Shipping->Code),
                        "desc" => (string)$node->Description,
                        "shop" => (string)$node->Store->Name,
                        "score" => floatval((string)$node->Review->Rate),
                        "aurl" => (string)$node->Url,
                        "iurl" => empty($this->requests["mobile"]) ? (string)$node->Image->Medium : (string)$node->Image->Small,
                        "surl" => (string)$node->Store->Url
                    )
                );
            }
        } else {
            $this->pages = 0;
        }
        return $hash;
    }
}
