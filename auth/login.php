<?php
// DB接続
require('../dbconnect.php');


// セッション開始
session_start();

if (!empty($_POST)) {

    // 入力フォームからの値を取得する
    $email = $_POST['email'];
    $password = $_POST['password'];

    // DBにあるか確認する
    $statement = $db->prepare('SELECT * FROM users WHERE email=?');
    $statement->execute(array($email));
    $login = $statement->fetch();

    // 入力されたパスワードとDBに保存されているパスワードの照合
    if (password_verify($password, $login['password'])) {
        // 入力されてたパスワードとDBのパスワードが正しかったら

        // セッションにログインしたユーザーの情報を入れておく
        $_SESSION['id'] = $login['id'];
        $_SESSION['name'] = $login['name'];

        // 投稿一覧ページに戻す
        header('Location: ../post/index.php');
    } else {
        // 入力されたパスワードとDBのパスワードが正しくなければ
        $errorMessage = '入力された内容が正しくない又はユーザーが作成されていません';
    }
}




?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン</title>
</head>

<body>
    <header>
        <div class="header-title">
            <h1>ログイン</h1>
        </div>

        <div class="header-nav">
            <p><a href="register.php">新規登録</a></p>
        </div>
    </header>

    <div class="main">

        <?php $errorMessage && print($errorMessage); ?>

        <!-- ログインフォーム -->
        <form action="login.php" method="post">

            <input type="email" name="email" placeholder="メアド" />

            <input type="password" name="password" placeholder="パスワード" />

            <input type="submit" value="ログイン" />

        </form>
    </div>

</body>

</html>