<?php

class GameException extends Exception {//Game Exception to handle our own exceptions 
} 
interface IGame {
	public function __construct(array $config); 
	public function __toString(); // Returns JSON object
} 
abstract class Game implements IGame {
	protected $_gameBoard;
	protected $_errorMessage = array();
	protected $_move = array();
	const PLAYER 	= 1;
	const COMPUTER 	= 2;
	const DRAW			= 400;
	const GOING_ON 		= 401;
	const ALLOWD_MOVE 	= 2;
	public function __construct(array $config){
		// continue or starting new game?
		if (isset($config['gameBoard'])){
		$this->setGameBoard($config['gameBoard']);
		} else {
		$this->clearBoard();
		}
	
		$this->_currentPlayer = $config['player'];
	
		$this->play($config['move']); 
	}

	protected function computerTurn(){ 
		$getMinMaxResult = new MinMax($this); 
		if ($getMinMaxResult->move) {
			return $this->_move = $getMinMaxResult->move;
		}
		return false; 
	}
	
	public function setPlayer(){ //Toggle between players
		$this->_currentPlayer = (self::PLAYER == $this->_currentPlayer)? self::COMPUTER:self::PLAYER;
	}
	
	protected static function isBoardFull(array $gameBoard){
		foreach($gameBoard as $pos1=>$columns){
			foreach($columns as $pos2=>$player){
				if (false !== $player){
					continue ; 	
				}
				$blankPositions[] = array($pos1,$pos2);
			}
		}
	
		return (!empty($blankPositions))? $blankPositions:true;
	}
	
	public function setPosition(array $move,$isEmpty=false){
		if (false === self::isAlreadyTaken($move,$this->_gameBoard) || $isEmpty){
			return $this->_gameBoard[$move[0]][$move[1]] = ($isEmpty)? false:$this->_currentPlayer;
		}
		
		throw new GameException("Unallowd move ({$move[0]},{$move[1]}), code need to be fixed");
	}
		
	protected function clearBoard(){
		$board = array(); 
		for ($i = 0; $i<static::BOARD_ROWS; $i++){	
			$board[$i] = array(); 
			for ($j = 0; $j<static::BOARD_COLS; $j++){
				array_push($board[$i],false); 
			}
		} 
		$this->_gameBoard = $board; 
	}

	protected function setGameBoard($gameBoard){
		foreach($gameBoard as $pos1=>$columns){
			foreach($columns as $pos2=>$player){
				$this->_gameBoard[$pos1][$pos2] = (!$player)? false:$player;
			}
		}
	}
	
	public function getGameBoard(){
		return $this->_gameBoard;
	}

	protected static function isAllowdMove(array $move){
		if (!empty($move) && self::ALLOWD_MOVE == count($move)){
			return true;
		}
		return false;
	}
	
	private static function isAlreadyTaken(array $move,array $gameBoard){
		if (self::isAllowdMove($move)){
			return $gameBoard[$move[0]][$move[1]];
		}
	
		throw new GameException('Unallowd move taken');
	}

	public function __toString(){ // to return json answers for ajax support
		$res = array();
		if (!empty($this->_errorMessage)){
			$res['errorMessage'] = $this->_errorMessage;
		}
		if (!empty($this->_move)){
			$res['move'] = $this->_move;
		}
		return json_encode($res);
	}
	
	abstract protected function play(array $move = array());
	abstract protected function isGameOver();

} 