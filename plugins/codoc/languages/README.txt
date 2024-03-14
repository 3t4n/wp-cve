* 新規作成
  - poedit で作成

* 更新
  - poedit で codoc.pot を開く
    - 翻訳→ソースコードから更新
    - 保存
  - poedit で codoc-ja.potを開く
    - 翻訳→potファイルから更新
    - 新規追加分など、オレンジ分を変更（要確認しないとコンパイルされない）
    - ファイル→moファイルにコンパイル
  - make po2json を実行
    - poファイルからjsonを作成
