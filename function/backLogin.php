<!-- ユーザー認証ができなかった時にログイン画面に遷移する機能 -->
<?php

    function backLog() {
        if (!isset($_SESSION['id']) && !isset($_SESSION['name'])) {
            header('Location: ../auth/login.php');
        }
    }
    
?>