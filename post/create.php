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
        <form action="#" method="post">
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