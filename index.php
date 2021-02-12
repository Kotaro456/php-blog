<?php
    require('dbconnect.php');

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
    <header>
        <div class="header-title">
            <h1>PHP Blog</h1>
            <h2>PHPで作ったBlog</h2>
        </div>

        <div class="header-nav">
            <p><a href="#">新規登録</a></p>
            <p><a href="#">ログイン</a></p>
            <p><a href="#">ログアウト</a></p>
        </div>
    
    </header>
    <div class="main">

        <div class="bloger-profile">
            <h1>画像</h1>
            <h2>ブロガーの名前</h2>
            <p>僕はこういうやつです</p>
        </div>

        <p><a href="post/create.php">記事作成</a></p>

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
                <button><a href="post/edit.php">編集</a></button>
                <button><a href="post/delete.php">削除</a></button>
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

    <footer>
        <p>〇〇のブログ</p>
    </footer>
</body>
</html>