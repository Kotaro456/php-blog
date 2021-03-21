<!-- 記事を削除する時の処理 -->
<?php
require('../dbconnect.php');

session_start();

// backLog()使える様にする
require('../function/backLogin.php');


// ログインしていない時ログイン画面に強制遷移
backLog();


// URLパラメーターからのidを取得
$id = $_REQUEST['id'];


// idと一致したものを削除する
$delete = $db->prepare('DELETE from posts WHERE id=?');
$delete->execute(array($id));

header('Location: index.php');
?>