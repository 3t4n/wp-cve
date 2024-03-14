<?php
/**
 * アフィリエイトサービス実装クラス(楽天)
 * User: cottonspace
 * Date: 15/05/30
 */

/**
 * 基底クラス
 */
require_once "service-base.php";

/**
 * アフィリエイトサービスの実装クラス
 */
class Rakuten extends ServiceBase
{
    /**
     * 商品検索ソート方法
     * @var array 商品ソート指定の配列
     */
    private $sortTypes = array(
        "+price" => "+itemPrice",
        "-price" => "-itemPrice",
        "-reviews" => "-reviewCount",
        "+reviews" => "+reviewCount",
        "-score" => "-reviewAverage",
        "+score" => "+reviewAverage"
    );

    /**
     * 価格表示フォーマット処理
     * @param string $price 商品価格
     * @param string $tax 消費税区分
     * @param string $postage 送料区分
     * @return string 価格表示文字列
     */
    private function formatPrice($price, $tax, $postage)
    {
        $ret = "";
        if (!empty($price)) {
            $ret = number_format(floatval($price)) . " 円";
            if ($tax === "0") {
                $ret .= " (税込)";
            }
            if ($postage === "0") {
                $ret .= " 送料込";
            }
        }
        return $ret;
    }

    /**
     * カテゴリ検索クエリ生成
     * @link https://webservice.rakuten.co.jp/api/ichibagenresearch/
     * @param string $category 対象カテゴリ
     * @return string RESTクエリ文字列
     */
    private function queryCategories($category)
    {
        if (empty($category)) {
            $category = 0;
        }
        $baseurl = "https://app.rakuten.co.jp/services/api/IchibaGenre/Search/20140222";
        $params = array();
        $params["applicationId"] = $this->account["developerId"];
        $params["affiliateId"] = $this->account["affiliateId"];
        $params["format"] = "xml";
        $params["genrePath"] = 0;
        $params["genreId"] = $category;
        ksort($params);
        return $baseurl . "?" . http_build_query($params);
    }

    /**
     * 商品検索クエリ生成
     * @link https://webservice.rakuten.co.jp/api/ichibaitemsearch/
     * @return string RESTクエリ文字列
     */
    private function queryItems()
    {
        $baseurl = "https://app.rakuten.co.jp/services/api/IchibaItem/Search/20140222";
        $params = array();
        $params["applicationId"] = $this->account["developerId"];
        $params["affiliateId"] = $this->account["affiliateId"];
        $params["format"] = "xml";
        $params["hits"] = $this->requests["pagesize"];
        $params["availability"] = 1;
        $params["field"] = 1;
        $params["carrier"] = empty($this->requests["mobile"]) ? 0 : 1;
        $params["imageFlag"] = 1;
        $params["purchaseType"] = 0;
        $params["genreId"] = empty($this->requests["category"]) ? "0" : $this->requests["category"];
        if (!empty($this->requests["keyword"])) {
            $params["keyword"] = $this->requests["keyword"];
        }
        if (!empty($this->requests["shop"])) {
            $params["shopCode"] = $this->requests["shop"];
        }
        if (!empty($this->requests["sort"]) && array_key_exists($this->requests["sort"], $this->sortTypes)) {
            $params["sort"] = $this->sortTypes[$this->requests["sort"]];
        } else {
            $params["sort"] = "standard";
        }
        $params["page"] = $this->requests["pagenum"];
        ksort($params);
        return $baseurl . "?" . http_build_query($params);
    }

    /**
     * サービス識別名
     * @return string サービス識別名
     */
    public function serviceName()
    {
        return "rakuten";
    }

    /**
     * サービスクレジット表記
     * @return string サービスクレジット表記
     */
    public function serviceCredit()
    {
        $credit = <<<EOF
<!-- Rakuten Web Services Attribution Snippet FROM HERE -->
<a href="http://webservice.rakuten.co.jp/" target="_blank"><img src="http://webservice.rakuten.co.jp/img/credit/200709/credit_4936.gif" border="0" alt="楽天ウェブサービスセンター" title="楽天ウェブサービスセンター" width="49" height="36"/></a>
<!-- Rakuten Web Services Attribution Snippet TO HERE -->\n
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
     * @link http://webservice.rakuten.co.jp/api/genresearch/
     * @param string $category 基底カテゴリ
     * @return array カテゴリ情報の連想配列
     */
    public function getCategories($category = "")
    {
        if (empty($category)) {
            $category = 0;
        }

        // RESTクエリ情報を取得
        $query = $this->queryCategories($category);

        // RESTクエリ実行
        $strxml = $this->download($query, $query);
        $objxml = simplexml_load_string($strxml);
        $hash = array();
        if (isset($objxml->children)) {
            foreach ($objxml->children->child as $node) {
                $hash[(string)$node->genreId] = (string)$node->genreName;
            }
        }
        return $hash;
    }

    /**
     * 商品検索
     * @link http://webservice.rakuten.co.jp/api/itemsearch/
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
        if (isset($objxml->Items)) {
            $this->pages = intval($objxml->pageCount);
            foreach ($objxml->Items->Item as $node) {
                array_push($hash, array(
                        "name" => (string)$node->itemName,
                        "price" => $this->formatPrice((string)$node->itemPrice, (string)$node->taxFlag, (string)$node->postageFlag),
                        "desc" => (string)$node->itemCaption,
                        "shop" => (string)$node->shopName,
                        "score" => floatval((string)$node->reviewAverage),
                        "aurl" => (string)$node->affiliateUrl,
                        "iurl" => empty($this->requests["mobile"]) ? (string)$node->mediumImageUrls->imageUrl[0] : (string)$node->smallImageUrls->imageUrl[0],
                        "surl" => (string)$node->shopUrl
                    )
                );
            }
        } else {
            $this->pages = 0;
        }
        return $hash;
    }
}
