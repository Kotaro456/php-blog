<!-- ユーザーのプロフィールページ編集も可能 -->
<?php
    // DB接続
    require('../dbconnect.php');

    // セッション開始
    session_start();

    // ログインできてなかったら、ログインページに戻る
    if (!isset($_SESSION['id']) && !isset($_SESSION['name'])) {
        header('Location: ../auth/login.php');
    }

    $user_id = $_SESSION['id'];
    $user_name = $_SESSION['name'];

    // ログイン中のユーザーの画像と自己紹介文を引っ張ってくる
    $statement = $db->prepare('SELECT * FROM users WHERE id=? && name=?');
    $statement->execute(array($user_id, $user_name));

    $user = $statement->fetch();


    // 更新の処理
    if (!empty($_POST)) {

        // フォームからの値を受け取る
        $new_name = htmlspecialchars($_POST['new_name']);
        $new_biography = htmlspecialchars($_POST['new_biography']);


        // 画像の処理

        
        $new_picture = date('YmsHis') . $_FILES['new_picture']['name'];
        $upload_dir = '../images';

        // DBに保存する画像ファイル
        $new_picture = "$upload_dir/$new_picture";

        
        // 画像ファイルをimagesに移動させる
        $tmp_name = $_FILES['new_picture']['tmp_name'];
        $file = move_uploaded_file($tmp_name, "$upload_dir/$new_picture");


        $new_user = $db->prepare('UPDATE users SET name=?, picture=?, profile=? WHERE id=? && email=?');
        $new_user->execute(array($new_name, $new_picture, $new_biography, $user['id'], $user['email']));

    }


?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザープロフィール</title>
</head>
<body>
    <!-- header -->
<?php include('../layouts/header.html') ?>

<div class="main">

    <form action="profile.php" method="post" enctype="multipart/form-data">

        <img src="<?php echo $user['picture']; ?>" alt="現在の画像" width="100" height="100" />
        <input type="file" name="new_picture" /><br><br>
        <input type="text" name="new_name" placeholder="名前を記入してください" value="<?php echo $user['name'] ?>" /><br><br>

        <textarea name="new_biography" placeholder="自己紹介文を入力してください" rows="4" cols="40"><?php $user['profile']  && print($user['profile']); ?></textarea><br><br>

        <input type="submit" value="更新" />      

    </form>

</div>


<!-- footer -->
<?php include('../layouts/footer.html'); ?>

</body>
</html>