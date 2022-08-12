<?php
    function getLevel($mysqli) {
        $resultSeclect = $mysqli->prepare
        ("SELECT DISTINCT `lvl` FROM `levels` WHERE `login` = ?");
        $resultSeclect->bind_param('s', $_SESSION['login']);
        $resultSeclect->execute();
        $resultSeclect = $resultSeclect->get_result();
        return implode($resultSeclect->fetch_assoc());
    }
    function insertFirstLevel($mysqli) {
        $resultSeclect = $mysqli->prepare
        ("INSERT `levels`  
        VALUES (?, ?, 1)");
        $resultSeclect->bind_param('ss', $_SESSION['login'], $_SESSION['name']);
        $resultSeclect->execute();
        echo 1;
    }
    function updateLevel($mysqli, $lvl) {
        $resultSeclect = $mysqli->prepare
        ("UPDATE `levels` 
        SET `lvl` = ? 
        WHERE `login` = ?");
        $resultSeclect->bind_param('is', $lvl, $_SESSION['login']);
        $resultSeclect->execute();
    }
    session_start();
    $mysqli = new mysqli('localhost','root','','shopee');
    if ( isset($_POST['name']) && isset($_POST['login']) ) {
        $lvl = getLevel($mysqli);
        if ( isset($lvl) && !empty($lvl) ) 
            echo $lvl;
        else
            insertFirstLevel($mysqli);
    }
    if (isset($_POST['whoIsWinner'])) 
    {
        $lvl = getLevel($mysqli);
        if ($_POST['whoIsWinner'] === 'O') 
            $lvl++;
        if ($_POST['whoIsWinner'] === 'X')
            $lvl = $lvl > 1 ? --$lvl : 1;
        updateLevel($mysqli, $lvl);
    }