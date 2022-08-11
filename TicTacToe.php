<?php
require_once("Game.php");
require_once("MinMax.php");

class TicTacToe extends Game { 	

	const BOARD_ROWS = 3; 
	const BOARD_COLS = 3; 
	

	protected function play(array $move = array()){ 
		//check if game is over (winner\draw\going on)
		if (Game::GOING_ON != ($res = $this->isGameOver())){ 
			$this->_errorMessage[] = $res; 
			$_SESSION['$victoryController'] = ! $_SESSION['$victoryController'];
			return false; 
		}
		
		if (Game::PLAYER == $this->_currentPlayer){ 
			$this->setPosition($move); 
			$this->setPlayer();
			$this->computerTurn(); 
		} else {
			$this->computerTurn(); 
		} 
		
		//check if game is over AFTER computer turn is done (winner\draw\going on)
		if (Game::GOING_ON != ($res = $this->isGameOver())){ 
			$this->_errorMessage[] = $res; 
			$_SESSION['$victoryController'] = ! $_SESSION['$victoryController'];
			return false; 
		}
		
	}

	public function isGameOver(){ 
		$gameBoardCount = count($this->_gameBoard); 
        
		//FULL ROW 
		for($i=0; $i<$gameBoardCount;$i++){
			if (false !== $this->_gameBoard[$i][0] &&($this->_gameBoard[$i][0] == $this->_gameBoard[$i][1]
				&& $this->_gameBoard[$i][1] == $this->_gameBoard[$i][2])){ 
				
				return $this->_gameBoard[$i][0]; 
			} 
		} 
		
		$gameBoardCount = count($this->_gameBoard[0]);
		//FULL COLUMN
		for($i=0; $i<$gameBoardCount;$i++){
			if (false !== $this->_gameBoard[0][$i] &&($this->_gameBoard[0][$i] == $this->_gameBoard[1][$i]
				&& $this->_gameBoard[1][$i] == $this->_gameBoard[2][$i])){ 
				
				return $this->_gameBoard[0][$i]; 
			} 
		} 
		
		//DIAGONAL 
		if (($this->_gameBoard[0][0] == $this->_gameBoard[1][1] 
				&& $this->_gameBoard[1][1] == $this->_gameBoard[2][2]) 
				|| ($this->_gameBoard[0][2] == $this->_gameBoard[1][1]
				&& $this->_gameBoard[1][1] == $this->_gameBoard[2][0])){
				
			if (false !== $this->_gameBoard[1][1]){ 
				
				return $this->_gameBoard[1][1]; 
			} 
		}
		
		
		//DRAW
		if (true === Game::isBoardFull($this->_gameBoard)){
		
			return Game::DRAW; 
		}
		
		//GAME IS ON
		return Game::GOING_ON;
	} 
} 