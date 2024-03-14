=== SEO BreadCrumb ===
Contributors: redsnow_
Tags: breadcrumb, topic path, microdata
Requires at least: 3.1
Tested up to: 3.7
Stable tag: 1.0.2

HTML5 microdata対応のパンくずナビ（トピックパス）表示機能を追加します。

== Description ==
HTML5 microdata対応のパンくずナビ（トピックパス）表示機能を追加します。表示タイプ、スタイルに関する多数のパラメータやパンくずナビ独自のプラグインフックが用意されており、フレキシブルなカスタマイズが可能です。
"[Prime Strategy Bread
Crumb](http://wordpress.org/plugins/prime-strategy-bread-crumb/
"WordPressbreadcrumb plugin")" を元に改修しています

= Examples =
**Default**
Template Tag
`
<?php if (function_exists('bread_crumb')) bread_crumb(); ?>
`
Output Sample
`
<div id="breadcrumb" class="bread_crumb">
    <div itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="http://www.example.com/" itemprop="url">
            <span itemprop="title">Home</span>
        </a>  &gt; 
    </div>
    <div itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="http://www.example.com/?cat=2" itemprop="url">
            <span itemprop="title">Seminar</span>
        </a>  &gt; 
    </div>
    <div itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="http://www.example.com/?cat=4" itemprop="url">
            <span itemprop="title">Tokyo</span>
        </a>  &gt; 
    </div>
</div>
`

**List types**
Template Tag
`
<?php if (function_exists('bread_crumb')) bread_crumb('type=list'); ?>
`
Output sample
`
<div id="breadcrumb" class="bread_crumb">
    <ul>
        <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb" class="level-1 top">
            <a href="http://www.example.com/" itemprop="url">
                <span itemprop="title">トップページ</span>
            </a> &gt; 
        </li>
        <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb" class="level-2 sub">
            <a href="http://www.example.com/?cat=2" itemprop="url">
                <span itemprop="title">Seminar</span>
            </a> &gt; 
        </li>
        <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb" class="level-3 sub">
            <a href="http://www.example.com/?cat=4" itemprop="url">
                <span itemprop="title">Tokyo</span>
            </a> &gt; 
        </li>
    </ul>
</div>
`

= Special Thanks =

== Installation ==

1. pluginsフォルダに、ダウンロードした SEO BreadCrumb のフォルダをアップロードしてください。
2. プラグインページで "SEO BreadCrumb" を有効化して下さい。
3. 利用しているテーマのパンくずナビを表示したい箇所にページナビのテンプレートタグ "bread_crumb" を追加してください。テンプレートタグで指定できるパラメータについては、下記の Parameters を参照してください。

= Parameters =

**type**
stringを指定すると、リストではなく文字列として出力します。デフォルトはlist

**home_label**
トップページの表示テキスト。デフォルトは「トップページ」

**search_label**
検索結果の表示テキスト。デフォルトは「『%s』の検索結果」（%sが検索文字列）

**404_label**
404ページの表示テキスト。デフォルトは「404 Not Found」

**category_label**
カテゴリーの表示テキスト。デフォルトは「%s」（%sがカテゴリー名）

**tag_label**
投稿タグの表示テキスト。デフォルトは「%s」（%sが投稿タグ名）

**taxonomy_label**
カスタムタクソノミーの表示テキスト。デフォルトは「%s」（%sがタクソノミー名）

**author_label**
寄稿者の表示テキスト。デフォルトは「%s」（%sが寄稿者名）

**attachment_label**
アタッチメントの表示テキスト。デフォルトは「%s」（%sがアタッチメント名）

**year_label**
年の表示テキスト。デフォルトは「%s年」（%sが年の数字）

**month_label**
月の表示テキスト。デフォルトは「%s」（%sは日付フォーマットで指定した月の表示設定）

**day_label**
日の表示テキスト。デフォルトは「%s日」（%sが日の数字）

**post_type_label**
カスタム投稿タイプアーカイブの表示テキスト。デフォルトは「%s」（%sがカスタム投稿タイプ名）

**joint_string**
typeでstringを指定した場合の結合文字列。デフォルトは「 &amp;gt; 」（ > ）

**navi_element**
ラッパー要素名。divまたはnavを選択可能。デフォルトはdiv

**elm_class**
ラッパー要素のクラス名。ラッパー要素がなくタイプがリストの場合は、ulのクラス名となる。デフォルトは、「bread_crumb」

**elm_id**
ラッパー要素のid名。ラッパー要素がなくタイプがリストの場合は、ulのid名となる。デフォルトはbreadcrumb。

**li_class**
タイプがリストの場合のliに付くクラス名。デフォルトは空（なし）

**class_prefix**
各クラスに付く接頭辞。デフォルトは空（なし）

**current_class**
表示中のページのパンくずナビに付与されるクラス名。デフォルトは「current」

**indent**
タブでのインデント数。デフォルトは０。

**echo**
出力を行うか。デフォルトはtrue（出力する）。0またはfalseの指定でPHPの値としてreturnする。 

**disp_current**
表示中のページを表示するか。デフォルトはfalse（出力しない）0またはfalseの指定でPHPの値としてreturnする。

== Changelog ==
= 1.0.2 =
* 日本語翻訳ファイルを修正
* readme-ja.txtを修正

= 1.0.1 =
* 日本語翻訳ファイルを修正

= 1.0.0 =
* 一般公開

== Screenshots ==
1. パンくずナビ出力サンプル

== Links ==
https://github.com/nobuhiko/seo-breadcrumb
