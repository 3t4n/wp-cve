<?php
//ADD OPTION


// 管理メニューにフックを登録
add_action('admin_menu', 'tinyjpfont_add_pages');

// メニューを追加する
function tinyjpfont_add_pages()
{
	$tinyjpfont_plugin_slug = "tinyjpfont";

	// トップレベルにオリジナルのメニューを追加
	add_menu_page(
		'Japanese Font for WordPressの設定',
		'Japanese Font for WordPressの設定',
		'manage_options',
		$tinyjpfont_plugin_slug,
		'tinyjpfont_options_page',
		plugins_url('icon.png', __FILE__)
	);
}

// メニューで表示されるページの内容を返す関数
function tinyjpfont_options_page()
{
	// POSTデータがあれば設定を更新
	if (isset($_POST['tinyjpfont_select'])) {
		update_option('tinyjpfont_select', $_POST['tinyjpfont_select']);
		update_option('tinyjpfont_head', $_POST['tinyjpfont_head']);
		update_option('tinyjpfont_default_font', $_POST['tinyjpfont_default_font']);
		// チェックボックスはチェックされないとキーも受け取れないので、ない時は0にする
		$tinyjpfont_check_cdn = isset($_POST['tinyjpfont_check_cdn']) ? 1 : 0;
		update_option('tinyjpfont_check_cdn', $tinyjpfont_check_cdn);

		$tinyjpfont_check_noto = isset($_POST['tinyjpfont_check_noto']) ? 1 : 0;
		update_option('tinyjpfont_check_noto', $tinyjpfont_check_noto);

		$tinyjpfont_gutenberg = isset($_POST['tinyjpfont_gutenberg']) ? 1 : 0;
		update_option('tinyjpfont_gutenberg', $tinyjpfont_gutenberg);
	} ?>
</head>

<body>

    <div id="wrap">

        <div id="nav">
            Japanese Font for WordPressの情報についてはTwitterにて#tinyjpfontのハッシュタグでたまーにツイートしています。<br>
            あとよろしければ <a href="https://twitter.com/raspi0124">作者のTwitter</a>もフォローお願いします!<br><br>
            なお、このプラグインの次を決める <a
                href="https://docs.google.com/forms/d/e/1FAIpQLSd_PLkuRGr-NcXQ1Jq36xru73WvvbmyCm0QjFH92pJ14yQQjQ/viewform?usp=send_form">アンケートフォーム</a>も公開中！よろしければ要望等どうぞ！<br>
            バグ等発見されましたらraspi0124<@>gmail.comかTwitter(@raspi0124)までお願いいたします。

        </div>
        <h1>Japanese Font for WordPress</h1>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/raspi0124/Japanese-font-for-TinyMCE@stable/admin.css">
        <div id="content">
            <?php
				// 更新完了を通知
				if (isset($_POST['tinyjpfont_select'])) {
					echo '<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible">
							<p><strong>設定を保存しました。</strong></p></div>';
				} ?>
            <form method="post" action="">
                <tr>
                    <th scope="row">
                        <h3><label for="tinyjpfont_select">フォントロードモード</label></h3>
                    </th><br>
                    <td>
                        <select name="tinyjpfont_select" id="tinyjpfont_select">
                            <option value="0" <?php selected(0, get_option('tinyjpfont_select')); ?>>フォントロードNormal
                            </option>
                            <option value="1" <?php selected(1, get_option('tinyjpfont_select')); ?>>フォントロードLite
                            </option>
                        </select>
                    </td>
                </tr><br>
                <strong>
                    フォントロードNormalは指定したフォントを読み込みます。Liteを指定した場合最低限のフォント(ふい字、Noto Sans Japanese)のみ読み込まれるようになります。
                </strong>
                <tr>
                    <th scope="row"><label for="tinyjpfont_check_cdn">
                            <h3>CDNモード (CSSもCDNから読み込むようになります)</h3>
                        </label></th><br>
                    <td><label><input name="tinyjpfont_check_cdn" type="checkbox" id="tinyjpfont_check_cdn" value="1"
                                <?php checked(1, get_option('tinyjpfont_check_cdn')); ?> /> CSSをCDNから読み込む</label></td>
                    <br>
                </tr>
                <strong>CDNはjsdelivrという無料サービスを使用しています。日本国内でのロード速度に自信があるようでしたらチェックボックスはオフにしましょう</strong>
                <tr>
                    <th scope="row">
                        <label for="tinyjpfont_head">
                            <h3>読み込み場所指定モード</h3>
                        </label>
                    </th><br>
                    <td>
                        <select name="tinyjpfont_head" id="tinyjpfont_head">
                            <option value="0" <?php selected(0, get_option('tinyjpfont_head')); ?>>ヘッダーで読み込む</option>
                            <option value="1" <?php selected(1, get_option('tinyjpfont_head')); ?>>フッターで読み込む</option>
                        </select>
                    </td>
                    </th>
                    <br><strong>テーマの仕様により対応していない場合もあります。</strong>
                </tr><br>
                <tr>
                    <th scope="row"><label for="tinyjpfont_gutenberg">
                            <h3>ブロックエディタ(Gutenberg)対応機能の有効化</h3>
                        </label></th><br>
                    <td><label><input name="tinyjpfont_gutenberg" type="checkbox" id="tinyjpfont_gutenberg" value="1"
                                <?php checked(1, get_option('tinyjpfont_gutenberg')); ?> />
                            ブロックエディタ(Gutenberg)への対応を有効化する</label></td><br>
                </tr><br>
                <strong>
                    Gutenberg対応機能はNoto Sans Japaneseとふい字フォントのみ現在サポートしています。
                </strong>
                <tr>
                    <th scope="row"><label for="tinyjpfont_default_font">
                            <h3>デフォルトフォント(beta) (TinyMCEエディタ上でデフォルトで利用するフォントを選択できます)</h3>
                        </label></th><br>
                    <td>
                        <select name="tinyjpfont_default_font" id="tinyjpfont_default_font">
                            <option value="Noto Sans Japanese"
                                <?php selected("noto", get_option('tinyjpfont_default_font')); ?>>Noto Sans Japanese
                            </option>
                            <option value="Huifont"
                                <?php selected("Huifont", get_option('tinyjpfont_default_font')); ?>>ふい字</option>
                            <option value="kokorom"
                                <?php selected("kokorom", get_option('tinyjpfont_default_font')); ?>>こころ明朝体</option>
                        </select>
                    </td>
                    </th>
                </tr>
                <br>
                </table>
                <?php submit_button(); ?>
            </form>

        </div>



    </div>

</body>

</html>
<?php
}