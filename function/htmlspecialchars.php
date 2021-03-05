<?php 
    // htmlspecialchars()のメソッド
    function e($val) {
        echo htmlspecialchars($val, ENT_NOQUOTES);
    }
?>