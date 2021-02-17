<?php
// DB接続
require('../dbconnect.php');


// $_POSTが空っぽじゃない時にDBに保存するまでの操作を開始する
if (!empty($_POST)) {

    // DBに保存するまでの流れ↓
    // エラーメッセージ用の変数を用意しておく
    $errorTitle = '';
    $errorContent = '';

    // フォームの値を変数に入れておく htmlspecialcharsでHTMLエンティティ化する
    $title = htmlspecialchars($_POST['title'], ENT_QUOTES);
    $content = htmlspecialchars($_POST['content'], ENT_QUOTES);

    // バリデーション

    // strlen()だと全角文字は 1文字2文字としてカウントされる
    // バイト数によってカウントされるため
    if (mb_strlen($title) < 4) {
        $errorTitle = 'タイトルは4文字以上で入力してください';
    }

    if (mb_strlen($content) < 10) {
        $errorContent = '記事内容は10文字以上で入力してください';
    }

    if ($errorTitle == '' && $errorContent == '') {


        // SQL文を直書きはまずいので、サニタイズ
        $sth = $db->prepare('INSERT INTO posts (title, content, created) values (?, ?, now())');
        $sth->execute(array($title, $content));


        header('Location:index.php');
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
    <title>記事作成</title>
</head>

<body>
    <?php include('../layouts/header.html') ?>

    <div class="main">
        <h1>記事作成</h1>

        <!-- action=""はフォームの送信先をこのページに指定するという意味 -->
        <form action="create.php" method="post">
            <?php $errorTitle ?  print("<p>$errorTitle</p>") : ''; ?>
            <input type="text" name="title" placeholder="記事のタイトル" value="<?php echo $title; ?>"/><br><br>

            <?php $errorContent ?  print("<p>$errorContent<p>") : ''; ?>
            <textarea type="text" name="content" placeholder="記事の内容"><?php echo $content; ?></textarea><br>

            <input type="submit" value="記事を投稿" />

        </form>
    </div>

    <footer>
        <p>〇〇のブログ</p>
    </footer>
</body>

</html>