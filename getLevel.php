<?php
    session_start();
    $mysqli = new mysqli('localhost','root','','shopee');
    if ( isset($_POST['name']) && isset($_POST['login']) ) {
        $login = $mysqli->real_escape_string(trim($_POST['login']));
        $name = $mysqli->real_escape_string(trim($_POST['name']));
        $resultSeclect = $mysqli->prepare
        ("SELECT `lvl` FROM `levels` WHERE `login` = ?");
        $resultSeclect->bind_param('s', $login);
        $resultSeclect->execute();
        $resultSeclect = $resultSeclect->get_result();
        $lvl = implode($resultSeclect->fetch_assoc());
        if ( isset($lvl) && !empty($lvl) ) 
            echo $lvl;
        else
        {
            echo 1;
            $resultSeclect = $mysqli->prepare
            ("INSERT `levels`  
            VALUES (?, ?, 1)");
            $resultSeclect->bind_param('ss', $login, $name);
            $resultSeclect->execute();
        }
    }
    if (isset($_POST['whoIsWinner'])) 
    {
        $resultSeclect = $mysqli->prepare
        ("SELECT `lvl` FROM `levels` WHERE `login` = ?");
        $resultSeclect->bind_param('s', $_SESSION['login']);
        $resultSeclect->execute();
        $resultSeclect = $resultSeclect->get_result();
        $lvl = implode($resultSeclect->fetch_assoc());
        if ($_POST['whoIsWinner'] == 'X') 
            $lvl ++;
        else 
            $lvl--;
        if ($lvl < 1)
            $lvl = 1;
        $resultSeclect = $mysqli->prepare
        ("UPDATE `levels` 
        SET `lvl` = ? 
        WHERE `login` = ?");
        $resultSeclect->bind_param('is', $lvl,  $_SESSION['login']);
        $resultSeclect->execute();
    }