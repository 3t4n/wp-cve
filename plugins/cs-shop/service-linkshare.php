<?php
/**
 * アフィリエイトサービス実装クラス(LinkShare)
 * User: cottonspace
 * Date: 12/04/28
 */

/**
 * 基底クラス
 */
require_once "service-base.php";

/**
 * アフィリエイトサービスの実装クラス
 */
class LinkShare extends ServiceBase
{
    /**
     * 商品検索ソート方法
     * @var array 商品ソート指定の配列
     */
    private $sortTypes = array(
        "+price" => "retailprice,asc",
        "-price" => "retailprice,dsc",
        "+name" => "productname,asc",
        "-name" => "productname,dsc"
    );

    /**
     * 価格表示フォーマット処理
     * @param string $price 商品価格
     * @param string $currency 通貨単位
     * @return string 価格表示文字列
     */
    private function formatPrice($price, $currency)
    {
        $ret = "";
        if (!empty($price)) {
            $ret = number_format(floatval($price));
            if ($currency == "JPY") {
                $ret .= " 円";
            } else {
                $ret .= " " . $currency;
            }
        }
        return $ret;
    }

    /**
     * 商品検索クエリ生成
     * @link http://linkshare.okweb3.jp/EokpControl?&tid=207339&event=FE0006
     * @return string RESTクエリ文字列
     */
    private function queryItems()
    {
        $baseurl = "http://productsearch.linksynergy.com/productsearch";
        $params = array();
        $params["token"] = $this->account["token"];
        if (!empty($this->requests["keyword"])) {
            $params["keyword"] = $this->requests["keyword"];
        }
        if (!empty($this->requests["shop"])) {
            $params["mid"] = $this->requests["shop"];
        }
        if (!empty($this->requests["category"])) {
            $params["cat"] = $this->requests["category"];
        }
        $params["max"] = $this->requests["pagesize"];
        $params["pagenumber"] = $this->requests["pagenum"];
        if (!empty($this->requests["sort"]) && array_key_exists($this->requests["sort"], $this->sortTypes)) {
            $sort_array = explode(',', $this->sortTypes[$this->requests["sort"]], 2);
            $params["sort"] = $sort_array[0];
            $params["sorttype"] = $sort_array[1];
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
        return "linkshare";
    }

    /**
     * サービスクレジット表記(LinkShareは必須クレジットが無いためBentoBoxアプリケーションタグを表示)
     * @return string サービスクレジット表記
     */
    public function serviceCredit()
    {
        $credit = <<<EOF
<!-- Begin BentoBox Application Tag -->
<img border="0" width="1" height="1" src="http://ad.linksynergy.com/fs-bin/show?id=Dk8JKvDVYwE&bids=186984.200248&type=3&subid=0" />
<!-- End BentoBox Application Tag -->\n
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
     * 商品検索
     * @link http://linkshare.okweb3.jp/EokpControl?&tid=207339&event=FE0006
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
        if (isset($objxml->item)) {
            $this->pages = intval($objxml->TotalPages);
            foreach ($objxml->item as $node) {
                array_push($hash, array(
                        "name" => (string)$node->productname,
                        "price" => $this->formatPrice((string)$node->price, (string)$node->price->attributes()->currency),
                        "desc" => empty($node->description->long) ? (string)$node->description->short : (string)$node->description->long,
                        "shop" => (string)$node->merchantname,
                        "score" => 0,
                        "aurl" => (string)$node->linkurl,
                        "iurl" => (string)$node->imageurl,
                        "surl" => substr((string)$node->imageurl, 0, strpos((string)$node->imageurl, '/', 7)) . '/' // 商品画像URLから生成
                    )
                );
            }
        } else {
            $this->pages = 0;
        }
        return $hash;
    }
}
