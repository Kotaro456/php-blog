<?php
    // DB接続
    require('../dbconnect.php');

    // セッションの開始
    session_start();

    // 入力されているかどうかのチェック
    if (!empty($_POST)) {
        

        // 入力フォームの値を取得
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);


        $errorName = '';
        $errorEmail = '';
        $errorPassword = '';


        // 名前が入力されているかのチェック
        if (mb_strlen($name) == 0) {
            $errorName = '名前を入力してください';
        }

        // メアドのための正規表現
        $email_pattern = '/^[a-zA-Z0-9_.+-]+@([a-zA-Z0-9][a-zA-Z0-9-]*[a-zA-Z0-9]*\.)+[a-zA-Z]{2,}$/u';

        // emailがメールの書式で入力されているかの確認、マッチしなかったらエラー文
        if (!preg_match($email_pattern, $email)) {
            $errorEmail = '正しいメールアドレスを入力してください';
        }

        
        // パスワードの文字数チェック
        if (strlen($password) <= 3) {
            $errorPassword = 'パスワードは4文字以上で入力してください';
        }

        // 画像ファイルのDBに入れる直前までの処理

        // 写真がアップロードされていたら
        if (!empty($_FILES['picture']['name'])) {

            // 画像ファイルの名前は$_FILESのnameキーのところに入っているファイル名を取得して
            // それに年月日時をくっつけて、画像のファイル名を被らない様にする(ほぼほぼ被らない)
            $image = date('YmsHis') . $_FILES['picture']['name'];
    
            // これをファイルディレクトリの中に格納している時用のパスを書く(DBでは文字列として保存)
            // ファイルではimgタグ様に使える
            $file = "images/$image";

        } else {
            // デフォルトの画像を保存
            $file = "images/default.png";
        }



        // エラーがなかったら、DBに入れる
        if ($errorName == '' && $errorEmail == '' && $errorPassword == '') {

            // パスワードをDBに保存する前に暗号化かます
            $hash_password = password_hash($password, PASSWORD_DEFAULT);

            // サニタイズして入れる
            $statement = $db->prepare('INSERT INTO users SET name=?, email=?, password=?, picture=?');
            $statement->execute(array($name, $email, $hash_password, $file));

            if (!empty($_FILES['picture']['name'])) {
                // 画像ファイルをimagesディレクトリに移動
                move_uploaded_file($_FILES['picture']['tmp_name'], '../images/', $image);
            }



            // ログイン処理
            $login = $db->prepare('SELECT id, name FROM users WHERE email=?');
            $login->execute(array($email));
            $user = $login->fetch();

            // セッションに登録されたユーザーの値を取得する、つまりログイン
            $_SESSION['id'] = $user['id'];
            $_SESSION['name'] = $user['name'];

            header('Location: ../post/index.php');
        }

    } else {
        echo '全て入力してください';
    }


?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規登録</title>
</head>
<body>
    <header>
        <div class="header-title">
            <h1>新規登録</h1>
        </div>

        <div class="header-nav">
            <p><a href="login.php">ログイン</a></p>
        </div>
    </header>

    <div class="main">

        <!-- 登録フォーム  -->                          <!-- 画像をアップするため -->
        <form action="register.php" method="post" enctype="multipart/form-data">

            <?php $errorName ? print("<p>$errorName</p>") : '';?>
            <input type="text" name="name" placeholder="名前" /><br><br>

            <?php $errorEmail ? print("<p>$errorEmail</p>") : '';?>
            <input type="email" name="email" placeholder="メアド" /><br><br>

            <?php $errorPassword ? print("<p>$errorPassword</p>") : '';?>
            <input type="password" name="password" placeholder="パスワード" /><br><br>

            <!-- 画像ファイルを入れるための場所、required を書くと入力必須になる、
                    acceptで拡張子を指定する、指定された拡張子の物しか選択できなくなる、
                    これでアップされたファイルデータはスーパーグローバル変数の$_FILESに入る -->
            <input type="file" name="picture" accept=".png, .jpg" />

            <input type="submit" value="登録する" />

        </form>
    </div>

</body>
</html>