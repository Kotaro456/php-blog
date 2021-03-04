<?php
    try{

        // PDOでデータベースに接続
        $db = new PDO('mysql:dbname=myblog;host=localhost;charset=utf8', 'root', 'root');

        
        // DBに接続できなかった時の処理
    }catch(PDOException $e){

        //メッセージを表示する
        echo 'DB接続エラー' . $e->getMessage();
    }
?>