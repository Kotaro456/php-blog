<!-- 記事を削除する時の処理 -->
<?php 
    require('../dbconnect.php');

    // URLパラメーターからのidを取得
    $id = $_REQUEST['id'];


    // idと一致したものを削除する
    $delete = $db->prepare('DELETE from posts WHERE id=?');
    $delete->execute(array($id));

    header('Location: /index.php');
?>