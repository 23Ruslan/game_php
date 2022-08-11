<?php
require_once("TicTacToe.php");

$config = array(
				'player'	=>	Game::PLAYER,
				'move'		=>	array($_POST['pos1'],$_POST['pos2']),
				); 
session_start();
if ( !isset($_SESSION['$victoryController']) ) 
	$_SESSION['$victoryController'] = true;

//if we starting new game we don't realy have to send game board 
if (isset($_POST['gameBoard'])){
	$config['gameBoard'] = $_POST['gameBoard'];
}
$ticTacToe = new TicTacToe($config);
echo $ticTacToe;
