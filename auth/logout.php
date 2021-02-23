<?php 

    // セッションの開始
    session_start();

    // セッション変数を空にする
    $_SESSION = array();

    header('Location: login.php');
?>