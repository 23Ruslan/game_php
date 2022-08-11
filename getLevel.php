<?php
    session_start();
    $mysqli = new mysqli('localhost','link2sit_user','NGd^hOkyU&o_','link2sit_db');
    $login = ($_POST['login']) ? $mysqli->real_escape_string(trim($_POST['login'])) : '-';
    $name = ($_POST['name']) ? $mysqli->real_escape_string(trim($_POST['name'])) : '-';
    $resultSeclect = $mysqli->prepare
    ("SELECT `lvl` FROM `levels` WHERE `login` = ?");
    $resultSeclect->bind_param('s', $login);
    $resultSeclect->execute();
    $resultSeclect = $resultSeclect->get_result();
    $lvl = implode($resultSeclect->fetch_assoc());

    if ( !isset($_POST['whoIsWinner']) || empty($_POST['whoIsWinner']) ) {
        if (isset($lvl)) 
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
        } else {
            if ($_POST['whoIsWinner'] == 'X') 
                $lvl ++;
            else 
                $lvl--;
            if ($lvl < 1)
                $lvl = 1;
            // echo 'LEVEL IS ' . $lvl . ' LOGIN IS ' . $_SESSION['login'] . ' POST IS ' . $_POST['whoIsWinner'];
            $resultSeclect = $mysqli->prepare
            ("UPDATE `levels` 
            SET `lvl` = ? 
            WHERE `login` = ?");
            $resultSeclect->bind_param('is', $lvl,  $_SESSION['login']);
            $resultSeclect->execute();
    }
    