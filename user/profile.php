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

    // エラー用の変数
    $errorName = '';

    // $new_nameが1文字も入力されていない時にエラー
    if (mb_strlen($new_name) == 0) {
        $errorName = '名前を入力してください';
    }


    // エラーがなければ
    if ($errorName == '') {

        // 画像の処理
        // 変更前の画像を取得
        $old_picture = $user['picture'];

        // 画像アップロード先
        $upload_dir = '../images';
        // デフォルト画像
        $default_picture = '../images/default.png';



        //現状：画像ファイルを選択した時は正しく動作する
        // 画像ファイルを選択せずに更新すると、デフォルト画像が登録されずに日時だけが登録された状態になってしまう
        // おそらくelseif以降が正しく動作する様にかけていない 


        // 新しい画像ファイルが空出なければ、つまり新しい画像ファイルがアップされているなら
        if (isset($_FILES['new_picture'])) {

            // 画像ファイルの名前を一意のものにする
            $new_picture = date('YmsHis') . $_FILES['new_picture']['name'];

            // DBに保存する画像ファイル
            $new_picture = "$upload_dir/$new_picture";


            // 画像ファイルをimagesに移動させる
            $tmp_name = $_FILES['new_picture']['tmp_name'];
            $file = move_uploaded_file($tmp_name, "$upload_dir/$new_picture");

            // ここで$old_pictureを削除する、$old_pictureが$default_picture出なければ
            if ($default_picture != $old_picture ){
                unlink($old_picture);
            }

            // 新しい画像ファイルが空なのである且つデフォルト画像と古い画像が違うのであれば
        } elseif ($old_picture != $default_picture && empty($_FILES['new_picture'])) {

            // 古い画像の削除
            unlink($old_picture);

            // DBに保存する画像ファイルをデフォルトの画像にする
            $new_picture = $default_picture;
        }


        // ここでDBに変更内容を挿入する
        $new_user = $db->prepare('UPDATE users SET name=?, picture=?, profile=? WHERE id=? && email=?');
        $new_user->execute(array($new_name, $new_picture, $new_biography, $user['id'], $user['email']));
    }
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
    <header>
        <div class="header-title">
            <h1>PHP Blog</h1>
            <h2>PHPで作ったBlog</h2>
        </div>

        <div class="header-nav">
            <p><a href="../post/index.php">記事一覧へ</a></p>
            <p><a href="../auth/logout.php">ログアウト</a></p>
        </div>
    </header>
    <div class="main">

        <form action="profile.php" method="post" enctype="multipart/form-data">

            <img src="<?php echo $user['picture']; ?>" alt="現在の画像" width="100" height="100" />
            <input type="file" name="new_picture" /><br><br>

            <?php $errorName != '' && print("<p>$errorName</p>"); ?>
            <input type="text" name="new_name" placeholder="名前を記入してください" value="<?php echo $user['name'] ?>" /><br><br>

            <textarea name="new_biography" placeholder="自己紹介文を入力してください" rows="4" cols="40"><?php $user['profile']  && print($user['profile']); ?></textarea><br><br>

            <input type="submit" value="更新" />

        </form>

    </div>


    <!-- footer -->
    <?php include('../layouts/footer.html'); ?>

</body>

</html>