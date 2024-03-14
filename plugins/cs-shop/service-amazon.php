<?php
/**
 * アフィリエイトサービス実装クラス(Amazon)
 * User: cottonspace
 * Date: 12/04/12
 */

/**
 * 基底クラス
 */
require_once "service-base.php";

/**
 * アフィリエイトサービスの実装クラス
 */
class Amazon extends ServiceBase
{
    /**
     * SearchIndex定義
     * @var array SearchIndex定義
     */
    private $search_indexes = array(
        "Apparel" => array(
            "name" => "服＆ファッション小物",
            "browse" => 361245011,
            "sort" => array(
                "+price" => "price",
                "-price" => "-price",
                "-sales" => "salesrank"
            ),
            "search" => array(
                "keyword" => "Keywords",
                "shop" => "MerchantId"
            )
        ),
        "Automotive" => array(
            "name" => "カー＆バイク用品",
            "browse" => 2017305051,
            "sort" => array(
                "-sales" => "salesrank",
                "+price" => "price",
                "-price" => "-price",
                "-score" => "reviewrank"
            ),
            "search" => array(
                "keyword" => "Keywords",
                "shop" => "MerchantId"
            )
        ),
        "Baby" => array(
            "name" => "ベビー＆マタニティ",
            "browse" => 344919011,
            "sort" => array(
                "-sales" => "salesrank",
                "+price" => "price",
                "-price" => "-price",
                "+name" => "titlerank"
            ),
            "search" => array(
                "keyword" => "Keywords",
                "shop" => "MerchantId"
            )
        ),
        "Beauty" => array(
            "name" => "コスメ",
            "browse" => 52391051,
            "sort" => array(
                "+price" => "price",
                "-price" => "-price",
                "-score" => "reviewrank"
            ),
            "search" => array(
                "keyword" => "Keywords",
                "shop" => "MerchantId"
            )
        ),
        "Books" => array(
            "name" => "本(和書)",
            "browse" => 465610,
            "sort" => array(
                "-sales" => "salesrank",
                "+price" => "pricerank",
                "-price" => "inverse-pricerank",
                "-release" => "daterank",
                "+name" => "titlerank",
                "-name" => "-titlerank"
            ),
            "search" => array(
                "keyword" => "Keywords",
                "shop" => "MerchantId"
            )
        ),
        "DVD" => array(
            "name" => "DVD",
            "browse" => 562002,
            "sort" => array(
                "-sales" => "salesrank",
                "+price" => "price",
                "-price" => "-price",
                "+name" => "titlerank",
                "-name" => "-titlerank",
                "+release" => "releasedate",
                "-release" => "-releasedate"
            ),
            "search" => array(
                "keyword" => "Keywords"
            )
        ),
        "Electronics" => array(
            "name" => "家電＆カメラ",
            "browse" => 3210991,
            "sort" => array(
                "-sales" => "salesrank",
                "+price" => "price",
                "-price" => "-price",
                "+name" => "titlerank",
                "-name" => "-titlerank",
                "-release" => "-releasedate",
                "+release" => "releasedate"
            ),
            "search" => array(
                "keyword" => "Keywords",
                "shop" => "MerchantId"
            )
        ),
        "ForeignBooks" => array(
            "name" => "洋書",
            "browse" => 52231011,
            "sort" => array(
                "-sales" => "salesrank",
                "+price" => "pricerank",
                "-price" => "inverse-pricerank",
                "-release" => "daterank",
                "+name" => "titlerank",
                "-name" => "-titlerank"
            ),
            "search" => array(
                "keyword" => "Keywords",
                "shop" => "MerchantId"
            )
        ),
        "Grocery" => array(
            "name" => "食品＆飲料",
            "browse" => 57240051,
            "sort" => array(
                "-sales" => "salesrank",
                "+price" => "price",
                "-price" => "-price",
                "-score" => "reviewrank"
            ),
            "search" => array(
                "keyword" => "Keywords",
                "shop" => "MerchantId"
            )
        ),
        "HealthPersonalCare" => array(
            "name" => "ヘルス＆ビューティー",
            "browse" => 161669011,
            "sort" => array(
                "-sales" => "salesrank",
                "+price" => "price",
                "-price" => "-price",
                "+name" => "titlerank",
                "-name" => "-titlerank"
            ),
            "search" => array(
                "keyword" => "Keywords",
                "shop" => "MerchantId"
            )
        ),
        "Hobbies" => array(
            "name" => "ホビー",
            "browse" => 133321861,
            "sort" => array(
                "-sales" => "salesrank",
                "+price" => "price",
                "-price" => "-price",
                "+name" => "titlerank",
                "-name" => "-titlerank",
                "+release" => "release-date",
                "-release" => "-release-date"
            ),
            "search" => array(
                "keyword" => "Keywords",
                "shop" => "MerchantId"
            )
        ),
        "HomeImprovement" => array(
            "name" => "DIY・工具",
            "browse" => 2016930051,
            "sort" => array(
                "-sales" => "salesrank",
                "+price" => "price",
                "-price" => "-price",
                "-score" => "reviewrank"
            ),
            "search" => array(
                "keyword" => "Keywords",
                "shop" => "MerchantId"
            )
        ),
        "Jewelry" => array(
            "name" => "ジュエリー",
            "browse" => 85896051,
            "sort" => array(
                "-sales" => "salesrank",
                "+price" => "price",
                "-price" => "-price",
                "-score" => "reviewrank"
            ),
            "search" => array(
                "keyword" => "Keywords",
                "shop" => "MerchantId"
            )
        ),
        "Kitchen" => array(
            "name" => "ホーム＆キッチン",
            "browse" => 3839151,
            "sort" => array(
                "-sales" => "salesrank",
                "+price" => "price",
                "-price" => "-price",
                "+name" => "titlerank",
                "-name" => "-titlerank",
                "-release" => "-release-date",
                "+release" => "release-date"
            ),
            "search" => array(
                "keyword" => "Keywords",
                "shop" => "MerchantId"
            )
        ),
        "Music" => array(
            "name" => "ミュージック",
            "browse" => 562032,
            "sort" => array(
                "-sales" => "salesrank",
                "+price" => "price",
                "-price" => "-price",
                "+name" => "titlerank",
                "-name" => "-titlerank",
                "+release" => "releasedate",
                "-release" => "-releasedate"
            ),
            "search" => array(
                "keyword" => "Keywords"
            )
        ),
        "Shoes" => array(
            "name" => "シューズ＆バッグ",
            "browse" => 2016927051,
            "sort" => array(
                "-sales" => "salesrank",
                "+price" => "price",
                "-price" => "-price",
                "-score" => "reviewrank"
            ),
            "search" => array(
                "keyword" => "Keywords",
                "shop" => "MerchantId"
            )
        ),
        "Software" => array(
            "name" => "ソフトウェア",
            "browse" => 637630,
            "sort" => array(
                "-sales" => "salesrank",
                "+price" => "price",
                "-price" => "-price",
                "+name" => "titlerank",
                "-name" => "-titlerank",
                "-release" => "-release-date",
                "+release" => "release-date"
            ),
            "search" => array(
                "keyword" => "Keywords",
                "shop" => "MerchantId"
            )
        ),
        "SportingGoods" => array(
            "name" => "スポーツ＆アウトドア",
            "browse" => 14315361,
            "sort" => array(
                "-sales" => "salesrank",
                "+price" => "price",
                "-price" => "-price",
                "+name" => "titlerank",
                "-name" => "-titlerank",
                "+release" => "releasedate",
                "-release" => "-releasedate"
            ),
            "search" => array(
                "keyword" => "Keywords",
                "shop" => "MerchantId"
            )
        ),
        "Toys" => array(
            "name" => "おもちゃ",
            "browse" => 13299551,
            "sort" => array(
                "-sales" => "salesrank",
                "+price" => "price",
                "-price" => "-price",
                "+name" => "titlerank",
                "-name" => "-titlerank",
                "-release" => "-release-date",
                "+release" => "release-date"
            ),
            "search" => array(
                "keyword" => "Keywords",
                "shop" => "MerchantId"
            )
        ),
        "VHS" => array(
            "name" => "VHS",
            "browse" => 561972,
            "sort" => array(
                "-sales" => "salesrank",
                "+price" => "price",
                "-price" => "-price",
                "+name" => "titlerank",
                "-name" => "-titlerank",
                "+release" => "releasedate",
                "-release" => "-releasedate"
            ),
            "search" => array(
                "keyword" => "Keywords"
            )
        ),
        "Video" => array(
            "name" => "ビデオ",
            "browse" => 561972,
            "sort" => array(
                "-sales" => "salesrank",
                "+price" => "price",
                "-price" => "-price",
                "+name" => "titlerank",
                "-name" => "-titlerank",
                "+release" => "releasedate",
                "-release" => "-releasedate"
            ),
            "search" => array(
                "keyword" => "Keywords"
            )
        ),
        "VideoGames" => array(
            "name" => "ゲーム",
            "browse" => 637872,
            "sort" => array(
                "-sales" => "salesrank",
                "+price" => "price",
                "-price" => "-price",
                "+name" => "titlerank",
                "-name" => "-titlerank",
                "-release" => "-release-date",
                "+release" => "release-date"
            ),
            "search" => array(
                "keyword" => "Keywords",
                "shop" => "MerchantId"
            )
        ),
        "Watches" => array(
            "name" => "時計",
            "browse" => 331952011,
            "sort" => array(
                "-sales" => "salesrank",
                "+price" => "price",
                "-price" => "-price",
                "+name" => "titlerank",
                "-name" => "-titlerank"
            ),
            "search" => array(
                "keyword" => "Keywords",
                "shop" => "MerchantId"
            )
        )
    );

    /**
     * RFC3986形式のURLエンコード処理
     * @param string $str パラメタ文字列
     * @return string エンコード結果文字列
     */
    private function urlencode_3986($str)
    {
        return str_replace('%7E', '~', rawurlencode($str));
    }

    /**
     * RFC3986形式のクエリ文字列生成
     * @param array $params クエリパラメタの配列
     * @return string RFC3986形式でURLエンコードされた文字列
     */
    private function http_build_query_3986($params)
    {
        $ret = "";
        foreach ($params as $k => $v) {
            $ret .= '&' . $this->urlencode_3986($k) . '=' . $this->urlencode_3986($v);
        }
        $ret = substr($ret, 1);
        return $ret;
    }

    // Amazon API 署名生成
    private function getAmazonSignature($baseurl, $querystring)
    {
        $parsedurl = parse_url($baseurl);
        $signdata = "GET\n" . $parsedurl["host"] . "\n" . $parsedurl["path"] . "\n" . $querystring;
        return base64_encode(hash_hmac("sha256", $signdata, $this->account["SecretAccessKeyId"], true));
    }

    /**
     * カテゴリ表現からSearchIndexとBrowseNodeの配列を返却
     * @param string $category Amazon 用カテゴリ表現(SearchIndex,BrowseNode形式)
     * @return array 配列(SearchIndex,BrowseNode)
     */
    private function parseCategory($category)
    {
        $pos = strpos($category, ',');
        if ($pos) {
            return explode(',', $category, 2);
        } else {
            $search_index = $category;
            if (array_key_exists($search_index, $this->search_indexes)) {
                $node_id = $this->search_indexes[$search_index]["browse"];
                return array($search_index, $node_id);
            }
        }
        return array("", "");
    }

    /**
     * カテゴリ検索クエリ生成
     * @link http://docs.amazonwebservices.com/AWSECommerceService/latest/DG/Welcome.html
     * @param string $node_id 対象カテゴリ(BrowseNode)
     * @return array クエリ情報(署名されていないRESTクエリ文字列,署名されたRESTクエリ文字列)
     */
    private function queryCategories($node_id)
    {
        // クエリ生成
        $baseurl = "http://ecs.amazonaws.jp/onca/xml";
        $params = array();
        $params["Service"] = "AWSECommerceService";
        $params["AWSAccessKeyId"] = $this->account["AccessKeyId"];
        $params["AssociateTag"] = $this->account["AssociateTag"];
        $params["Version"] = "2009-07-01";
        $params["Operation"] = "BrowseNodeLookup";
        $params["BrowseNodeId"] = $node_id;
        ksort($params);
        $no_signed_query = $this->http_build_query_3986($params);
        $params["Timestamp"] = gmdate("Y-m-d\TH:i:s\Z");
        ksort($params);
        $signed_query = $this->http_build_query_3986($params);
        $signed_query .= '&Signature=' . $this->urlencode_3986($this->getAmazonSignature($baseurl, $signed_query));
        return array($baseurl . '?' . $no_signed_query, $baseurl . '?' . $signed_query);
    }

    /**
     * 商品検索クエリ生成
     * @link http://docs.amazonwebservices.com/AWSECommerceService/latest/DG/Welcome.html
     * @return string RESTクエリ文字列
     */
    private function queryItems()
    {
        // カテゴリ情報を分割
        $category_array = $this->parseCategory($this->requests["category"]);

        // クエリ生成
        $baseurl = "http://ecs.amazonaws.jp/onca/xml";
        $params = array();
        $params["Service"] = "AWSECommerceService";
        $params["AWSAccessKeyId"] = $this->account["AccessKeyId"];
        $params["AssociateTag"] = $this->account["AssociateTag"];
        $params["Version"] = "2011-08-01";
        $params["Operation"] = "ItemSearch";
        $params["ResponseGroup"] = "Medium,OfferSummary,Reviews";

        // ページ番号の指定(Amazon のページサイズは10固定)
        if (!empty($this->requests["pagenum"])) {
            $params["ItemPage"] = $this->requests["pagenum"];
        }

        // SearchIndex 指定有無の判定
        if (empty($category_array[0])) {

            // SearchIndex が All の場合は Keywords のみ指定可能
            $params["SearchIndex"] = "All";
            if (!empty($this->requests["keyword"])) {
                $params["Keywords"] = $this->requests["keyword"];
            }
        } else {

            // SearchIndex と BrowseNode を設定
            $params["SearchIndex"] = $category_array[0];
            $params["BrowseNode"] = $category_array[1];

            // 並び替えの設定
            $sort_types = $this->getSortTypes();
            if (array_key_exists($this->requests["sort"], $sort_types)) {
                $params["Sort"] = $sort_types[$this->requests["sort"]];
            }

            // SearchIndexがサポートしているパラメタを設定
            if (array_key_exists($category_array[0], $this->search_indexes)) {
                $extend_params = $this->search_indexes[$category_array[0]]["search"];
            }
            foreach ($extend_params as $k => $v) {
                if (!empty($this->requests[$k])) {
                    $params[$v] = $this->requests[$k];
                }
            }
        }
        ksort($params);
        $no_signed_query = $this->http_build_query_3986($params);
        $params["Timestamp"] = gmdate("Y-m-d\TH:i:s\Z");
        ksort($params);
        $signed_query = $this->http_build_query_3986($params);
        $signed_query .= '&Signature=' . $this->urlencode_3986($this->getAmazonSignature($baseurl, $signed_query));
        return array($baseurl . '?' . $no_signed_query, $baseurl . '?' . $signed_query);
    }

    /**
     * サービス識別名
     * @return string サービス識別名
     */
    public function serviceName()
    {
        return "amazon";
    }

    /**
     * 商品検索ソート方法取得
     * @return array ソート指定の連想配列
     */
    public function getSortTypes()
    {
        // カテゴリ情報を分割
        $category_array = $this->parseCategory($this->requests["category"]);

        // SearchIndexを取得
        $search_index = $category_array[0];

        // SearchIndexがサポートしている並び替え方法を返却
        if (array_key_exists($search_index, $this->search_indexes)) {
            return $this->search_indexes[$search_index]["sort"];
        }

        // 並び替え定義が無い場合(空の配列を返却)
        return array();
    }

    /**
     * カテゴリ検索
     * @link http://docs.amazonwebservices.com/AWSECommerceService/latest/DG/Welcome.html
     * @param string $category 基底カテゴリ
     * @return array カテゴリ情報の連想配列
     */
    public function getCategories($category = "")
    {
        // カテゴリ情報を分割
        $category_array = $this->parseCategory($category);

        // ブラウズノードを取得
        $node_id = $category_array[1];

        // Amazon はルートカテゴリが存在しないため定義情報から取得
        if (empty($node_id)) {
            $hash = array();
            foreach ($this->search_indexes as $search_index_name => $search_index_hash) {
                $hash[$search_index_name . ',' . $search_index_hash["browse"]] = $search_index_hash["name"];
            }
            return $hash;
        }

        // RESTクエリ情報を取得
        $queries = $this->queryCategories($node_id);

        // RESTクエリ実行
        $strxml = $this->download($queries[0], $queries[1]);
        $objxml = simplexml_load_string($strxml);
        $hash = array();
        if (isset($objxml->BrowseNodes->BrowseNode->Children)) {
            foreach ($objxml->BrowseNodes->BrowseNode->Children->BrowseNode as $node) {
                $hash[$category_array[0] . ',' . (string)$node->BrowseNodeId] = (string)$node->Name;
            }
        }
        return $hash;
    }

    /**
     * 商品検索
     * @link http://docs.amazonwebservices.com/AWSECommerceService/latest/DG/Welcome.html
     * @return array 商品情報の連想配列
     */
    public function getItems()
    {
        // RESTクエリ情報を取得
        $queries = $this->queryItems();

        // RESTクエリ実行
        $strxml = $this->download($queries[0], $queries[1]);
        $objxml = simplexml_load_string($strxml);
        $hash = array();
        if (isset($objxml->Items)) {
            $this->pages = min(intval($objxml->Items->TotalPages), (($objxml->Items->Request->ItemSearchRequest->SearchIndex == "All") ? 5 : 10));
            foreach ($objxml->Items->Item as $node) {
                array_push($hash, array(
                        "name" => (string)$node->ItemAttributes->Title,
                        "price" => (string)$node->OfferSummary->LowestNewPrice->FormattedPrice,
                        "desc" => join("\n",
                            array(
                                "ASIN: " . (string)$node->ASIN,
                                (string)@$node->ItemAttributes->Manufacturer,
                                str_replace(array("\r", "\n", "　", "  "), " ", strip_tags((string)@$node->ItemAttributes->Feature)),
                                str_replace(array("\r", "\n", "　", "  "), " ", strip_tags((string)@$node->EditorialReviews->EditorialReview->Content))
                            )
                        ),
                        "shop" => "Amazon.co.jp",
                        "score" => floatval((string)@$node->CustomerReviews->AverageRating), // 現在は AverageRating は存在しない
                        "aurl" => (string)$node->DetailPageURL,
                        "iurl" => empty($this->requests["mobile"]) ? (string)$node->MediumImage->URL : (string)$node->SmallImage->URL,
                        "surl" => "http://www.amazon.co.jp/"
                    )
                );
            }
        } else {
            $this->pages = 0;
        }
        return $hash;
    }
}
