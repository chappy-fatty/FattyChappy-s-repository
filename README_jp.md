ポートフォリオサイトに置いた画像アップロード＆管理用アプリのソースコードたち（の一部）

（アップローダー）
https://ns-1441.meowwow.name/imgupload_guest/img_upload.html
（画像一覧）
https://ns-1441.meowwow.name/imgupload_guest/imgmanager.php

※サイトは未完成なので、見ちゃだめよ（というかパスワード認証かかってる）

（内訳：名前から冊子が付くのもあるが）
addDB.php         -> img_upload.htmlから送信された画像ファイル名等をデータベースに追加

DBinfo.php        -> 名前の通り（データベース接続情報はもちろん伏せている）

img_resize.php    -> img_upload.htmlから送信された画像ファイルを縮小・最適化（upload_process.phpから呼び出す）

imgdel.php        -> imgmanager.phpのDeleteボタンクリックで当該画像とデータベース内容を削除

imginfo.php       -> imgmanager.phpのInfoボタンクリックで当該画像の詳細情報を確認・修正

imgmanager.php    -> 画像一覧表示と管理

info_process.php  -> imginfo.phpで修正を行った際の処理を行う

modifyDB.php      -> info_process.phpから呼び出してデータベース変更を行う

upload_process.php -> img_upload.htmlから送信された画像ファイルをアップロードし、addDB.phpとimg_resize.phpに処理を渡す

