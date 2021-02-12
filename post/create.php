<?php
    // DB接続
    require('../dbconnect.php');

    // DBに保存するまでの流れ↓
    // エラーメッセージ用の変数を用意しておく
    $errorTitle = '';
    $errorContent = '';


    // $_POSTが空っぽじゃない時にDBに保存するまでの操作を開始する
    if(!empty($_POST)) {

        // フォームの値を変数に入れておく htmlspecialcharsでHTMLエンティティ化する
        $title = htmlspecialchars($_POST['title'], ENT_QUOTES);
        $content = htmlspecialchars($_POST['content'], ENT_QUOTES);

            // SQL文を直書きはまずいので、サニタイズ
            $sth = $db->prepare('INSERT INTO posts (title, content, created) values (?, ?, now())');
            $sth->execute(array($title, $content));


            header('Location:/php-blog/index.php/');
            

    }else{
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
<header>
        <div class="header-title">
            <h1>PHP Blog</h1>
            <h2>PHPで作ったBlog</h2>
        </div>

        <div class="header-nav">
            <p><a href="#">記事一覧へ</a></p>
            <p><a href="#">ログアウト</a></p>
        </div>
    
    </header>
    <div class="main">
        <h1>記事作成</h1>

        <!-- action=""はフォームの送信先をこのページに指定するという意味 -->
        <form action="create.php" method="post">
            <input type="text" name="title" placeholder="記事のタイトル"/>

            <textarea type="text" name="content" placeholder="記事の内容"></textarea>

            <input type="submit" value="記事を投稿" />

        </form>
    </div>

    <footer>
        <p>〇〇のブログ</p>
    </footer>
</body>
</html>