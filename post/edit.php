<?php
require('../dbconnect.php');

/// 引数をhtmlspecialchars()に入れ,echoするメソッド
require('../function/htmlspecialchars.php');


session_start();

if (!isset($_SESSION['id']) && !isset($_SESSION['name'])) {
    header('Location: ../auth/login.php');
}




// 編集前
// index.phpからのURLパラメータの値を取得
$id = $_REQUEST['id'];

// DBからURLパラメータと一致するレコードを取得
$statement = $db->prepare('SELECT * FROM posts WHERE id=?');
$statement->execute(array($id));

$post = $statement->fetch();


// 編集後の操作

// $_POSTがからじゃないことを確認する
if (!empty($_POST)) {

    // エラー文用の変数を作っておく
    $errorTitle = '';
    $errorContent = '';

    // それぞれ変数に入れておく
    $update_id = $_POST['id'];
    $new_title = $_POST['new_title'];
    $new_content = $_POST['new_content'];

        // これで前後の空白を削除しておく
    // 正規表現により文字列の前後の空白を削除することができる
    $new_title = preg_replace( '/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u', '', $new_title);


    // 文字数チェック 条件を満たしていたらエラー文の変数を空にする

    // mb_strlen()じゃないと日本語のバリデーションがうまくいきませんぞ
    if (mb_strlen($new_title) < 4) {
        $errorTitle = '4文字以上で入力してください';
    } else {
        $errorTitle = '';
    }

    if (mb_strlen($new_content) < 10) {
        $errorContent = '10文字以上で入力してください';
    } else {
        $errorContent = '';
    }

    if ($errorTitle == '' && $errorContent == '') {

        // 指定の値をアップデートするためのSQL文
        $new_post = $db->prepare('UPDATE posts SET title=?, content=?, modified=now() WHERE id=?');
        $new_post->execute(array($new_title, $new_content, $update_id));
        
        header('Location: index.php');
    } 
    
} else {
    echo '入力してください';
}


?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>記事編集</title>
</head>

<body>
    <?php include('../layouts/header.html') ?>
    <div class="main">
        <h1>記事作成</h1>
        <form action="edit.php" method="post">
            <?php $errorTitle != '' ? print("<p>$errorTitle</p>") : '' ; ?>
            <input type="text" name="new_title" placeholder="記事のタイトル" value="<?php $new_title ? e($new_title) : e($post['title']); ?>" /><br><br>

            <?php $errorTitle != '' ? print("<p>$errorContent</p>") : '' ; ?>
            <textarea type="text" name="new_content" placeholder="記事の内容"><?php $new_content ? e($new_content) : e($post['content']); ?></textarea><br>

            <input type="hidden" name="id" value="<?php e($post['id']); ?>" />
            <input type="submit" value="記事を編集して投稿" />

        </form>
    </div>

    <footer>
        <p>〇〇のブログ</p>
    </footer>
</body>

</html>