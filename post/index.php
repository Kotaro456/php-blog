<?php
    require('../dbconnect.php');

    $posts = $db->query('SELECT * FROM posts');
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>投稿一覧</title>
</head>
<body>
        <?php include('../layouts/header.html') ?>

    <div class="main">

        <div class="bloger-profile">
            <h1>画像</h1>
            <h2>ブロガーの名前</h2>
            <p>僕はこういうやつです</p>
        </div>

        <p><a href="create.php">記事作成</a></p>

        <!-- 記事一覧 -->
        <div class="contents">

        <?php foreach($posts as $post): ?>
            <hr>
            <div class="content">
                <h2><?php echo $post['title']; ?></h2>
                <p>
                   <?php echo $post['content']; ?>
                </p>
                <p>作成日時：<?php echo $post['created']; ?></p>

                <!--                URLパラメーターでDBからデータ取得するためのidを入れる -->
                <button><a href="edit.php?id=<?php echo $post['id']; ?>">編集</a></button>
                <button><a href="delete.php?id=<?php echo $post['id']; ?>">削除</a></button>
            </div>
            <hr>
        <?php endforeach; ?>

            <!-- ページング -->
            <div class="pages">
                <ul>
                    <li><a href="#">前のページへ</a></li>
                    <li><a href="#">次のページへ</a></li>
                </ul>
            </div>
        </div>
    </div>

    <?php include('../layouts/footer.html'); ?>
</body>
</html>