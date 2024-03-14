<?php
/**
 * アフィリエイトサービスインターフェイス
 * User: cottonspace
 * Date: 12/04/11
 */

interface IService
{
    /**
     * コンストラクタ
     * @param array $account アフィリエイト登録情報の連想配列
     */
    public function __construct($account);

    /**
     * 商品検索条件の設定
     * @param array $params 商品検索条件
     */
    public function setRequestParams(&$params);

    /**
     * サービス識別名
     * @return string サービス識別名
     */
    public function serviceName();

    /**
     * サービスクレジット表記
     * @return string サービスクレジット表記
     */
    public function serviceCredit();

    /**
     * 商品検索ソート方法取得
     * @return array ソート指定の連想配列
     */
    public function getSortTypes();

    /**
     * 商品検索ページ総数
     * @return int 商品検索ページ総数
     */
    public function getPageCount();

    /**
     * カテゴリ検索
     * @param string $category 基底カテゴリ
     * @return array カテゴリ情報の連想配列
     */
    public function getCategories($category = "");

    /**
     * 商品検索
     * @return array 商品情報の連想配列
     */
    public function getItems();
}
