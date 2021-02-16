<?php
require('../dbconnect.php');

// 編集前
// index.phpからのURLパラメータの値を取得
$id = $_REQUEST['id'];

// DBからURLパラメータと一致するレコードを取得
$statement = $db->prepare('SELECT * FROM posts WHERE id=?');
$statement->execute(array($id));

$post = $statement->fetch();


// 編集後の操作
// 編集後のDB操作
if (!empty($_POST)) {

    $update_id = $_POST['id'];
    $new_title = htmlspecialchars($_POST['new_title']);
    $new_content = htmlspecialchars($_POST['new_content']);
    
    // 指定の値をアップデートするためのSQL文
    $new_post = $db->prepare('UPDATE posts SET title=?, content=?, modified=now() WHERE id=?');
    $new_post->execute(array($new_title, $new_content, $update_id));
    
    header('Location: index.php');
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
            <input type="text" name="new_title" placeholder="記事のタイトル" value="<?php echo $post['title']; ?>" />

            <textarea type="text" name="new_content" placeholder="記事の内容"><?php echo $post['content']; ?></textarea>

            <input type="hidden" name="id" value="<?php echo $post['id']; ?>" />
            <input type="submit" value="記事を編集して投稿" />

        </form>
    </div>

    <footer>
        <p>〇〇のブログ</p>
    </footer>
</body>

</html>