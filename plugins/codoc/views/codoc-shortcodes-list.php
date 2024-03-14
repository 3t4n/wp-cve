<?php if (!$response['body']['status']): ?>
<b>ユーザーコードを入力してください</b>
<?php endif ?>
<div>
<table class="wp-list-table">
    <tr>
        <td>ユーザーコード</td>
        <td>
            <input id="codoc-usercode" value="<?php echo esc_html($usercode) ?>" placeholder="ここにコピーして貼付け" type="text">
            【<a href="<?php echo CODOC_URL . "/me/account" ?>" target="_blank">確認↗</a>】
        </td>
    </tr>
    <tr>
        <td>記事コード</td>
        <td><input id="codoc-entrycode" value="<?php echo esc_html($entrycode) ?>" type="text"><button class="submit-codoc-form">一覧を再取得</button></td>
        <td>
            【<a href="<?php echo CODOC_URL . "/me/account" ?>" target="_blank">記事を作成↗</a>】
        </td>
    </tr>
</table>


</div>
<table class="wp-list-table widefat fixed striped">
<!-- <table class="widefat fixed striped"> -->
    <thead>
        <tr>
            <th scope="col">タイトル</th>
            <th scope="col">記事コード</th>
            <th scope="col">shortcode</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($entries as $entry): ?>
        <tr>
            <td><?php echo esc_html($entry['title']) ?></td>
            <td><?php echo esc_html($entry['code']) ?></td>
            <td><a href="#entry" class="return_codoc_shortcode" data-id="<?php echo esc_html($entry['code']) ?>">張り付け</a></td>
        </tr>
    <?php endforeach ?>
    </tbody>
</table>
