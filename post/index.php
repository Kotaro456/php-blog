<?php
    require('../dbconnect.php');

    // セッション開始
    session_start();

        if (!isset($_SESSION['id']) && !isset($_SESSION['name'])) {
            header('Location: ../auth/login.php');
        }

   

        // ログインしてるユーザー名
        $login_user = $_SESSION['name'];

        // 表示する画像を取得する
        $statement = $db->prepare('SELECT picture FROM users WHERE id=?');
        $statement->execute(array($_SESSION['id']));

        $user_pic = $statement->fetch();

        // ページング
        if(isset($_REQUEST['page']) && is_numeric($_REQUEST['page'])) {
    
            // 前へ次へのリンクのURLパラメータのpageの値を取得する
            $page = $_REQUEST['page'];
        } else {
            // URLパラメータに値がないときは、デフォで1
            $page = 1;
        }
        // これで1ページ目は0スタート、2ページ目はDBの5番目のデータからスタート
        $start = 5 * ($page - 1);
        // サニタイズしてページ事に持ってくるデータを選択する
        $posts = $db->prepare('SELECT * FROM posts ORDER BY id LIMIT ?, 5');
        $posts->bindParam(1, $start, PDO::PARAM_INT);
        $posts->execute();
    
        // 最大ページを取得する
        $datas = $db->query('SELECT COUNT(*) AS cnt FROM posts');
        $data = $datas->fetch();
        // 商を切り上げ
        $maxPage = ceil($data['cnt'] / 5);


   

    


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
            
            <h2><img src="<?php echo $user_pic['picture']; ?>" alt="user_pic" width="100" height="100"/><?php echo $login_user; ?>さん</h2>
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
                    <!-- 前のページがないならリンクは無しで表示する -->
                    <?php if($page == 1): ?>
                        <li>前のページへ</li>
                    <?php else: ?>
                        <li><a href="index.php?page=<?php echo $page - 1; ?>">前のページへ</a></li>
                    <?php endif; ?>



                    <?php if($page == $maxPage): ?>
                        <li>次のページへ</li>
                    <?php else: ?>
                        <li><a href="index.php?page=<?php echo $page + 1; ?>">次のページへ</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>

    <?php include('../layouts/footer.html'); ?>
</body>
</html>