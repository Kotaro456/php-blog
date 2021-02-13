<?php
    require('../dbconnect.php');

    // 編集後のDB操作
    $update_id = $_POST['id'];
    $new_title = htmlspecialchars($_POST['new_title']);
    $new_content = htmlspecialchars($_POST['new_content']);

    // 指定の値をアップデートするためのSQL文
    $new_post = $db->prepare('UPDATE posts SET title=?, content=?, modified=now() WHERE id=?');
    $new_post->execute(array($new_title, $new_content, $update_id));

    header('Location: /index.php/');


?>

