<?php
session_start();
include_once("gameSession.php");

$conf = [		'player'	=>	Game::PLAYER,
				'move'		=>	[ $_POST['position1'], $_POST['position2'] ]
			]; 

if ( !isset($_SESSION['$victoryController']) ) 
	$_SESSION['$victoryController'] = true;

if (isset($_POST['gameBoard'])) // if we start a new game, we don't need to send the game board
	$conf['gameBoard'] = $_POST['gameBoard'];
	
$ticTacToe = new TicTacToe($conf);
echo $ticTacToe;