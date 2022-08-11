<?php
Interface IMinMax { 
	public function __construct(Game $game);
} 
class MinMax implements IMinMax { 

	protected $_game; 

	public $move; 

	const DEFAULT_MIN = -10000;
	const DEFAULT_MAX = 10000;
	

	public function __construct(Game $game){
		$this->_game = $game; 
		$this->move  = $this->minMax(); 
	}
	
	protected function minMax(){
		//clone current game so we won't destory the return result (after changing the board game) 
		
		$gameBoard = $this->_game->getGameBoard(); 

		$choose = self::DEFAULT_MIN; 

		foreach($gameBoard as $pos1=>$columns){
			foreach($columns as $pos2=>$player){ 
				if (false !== $player){
					continue; 
				}
				$gameClone = clone $this->_game; 
				$gameClone->setPosition(array($pos1,$pos2));
				$gameClone->setPlayer();
				$value = $this->minMove($gameClone,1,$choose,self::DEFAULT_MAX); 
				$gameClone->setPosition(array($pos1,$pos2),true);
				if ($value > $choose){
					$choose = $value;
					$move = array($pos1,$pos2); 
				} 
				$gameClone->setPlayer();
			
			} 
		}
		
		if (!empty($move)){ 
			$this->_game->setPosition($move); 
			return $move;
		}
		return false;
	}
	
	protected function score(Game $gameClone,$depth){
		$res = $gameClone->isGameOver();
		if ($_SESSION['$victoryController'])
			switch($res){
				case Game::COMPUTER: 
					return 100 - $depth; 
				break; 
				case Game::PLAYER: 
					return $depth - 100; 
				break; 
				case Game::DRAW: 
					return 0; 
				break; 
			}
		else
			switch($res){
				case Game::PLAYER: 
					return 100 - $depth; 
				break; 
				case Game::COMPUTER: 
					return $depth - 100; 
				break; 
				case Game::DRAW: 
					return 0; 
				break; 
			}
		return TicTacToe::GOING_ON; 
	}

	protected function maxMove(Game $gameClone,$depth,$alpha,$beta){ 
		$res = $this->score($gameClone,$depth);
		if(TicTacToe::GOING_ON != $res){
			return $res;
		}
		
		$gameBoard = $gameClone->getGameBoard();
		foreach($gameBoard as $pos1=>$colmuns){
			foreach($colmuns as $pos2=>$player){ 
				if(false !== $player){ 
					continue ; 
				}
				$gameClone->setPosition(array($pos1,$pos2));
				$gameClone->setPlayer();
				$value = $this->minMove($gameClone,++$depth,$alpha,$beta); 
				//return board so we won't have pos1,pos2 taken
				$gameClone->setPosition(array($pos1,$pos2),true);
				$gameClone->setPlayer();
				if ($value > $alpha){ 
					$alpha = $value; 
				} 
				
				if ($alpha > $beta){
					return $beta; 
				} 
			} 
		}

		return $value;
	}
	
	protected function MinMove(Game $gameClone,$depth,$alpha,$beta){
		$res = $this->score($gameClone,$depth);
		if(TicTacToe::GOING_ON != $res){
			return $res;
		}
		 
		$gameBoard = $gameClone->getGameBoard();
		
		foreach($gameBoard as $pos1=>$colmuns){
			foreach($colmuns as $pos2=>$player){ 
				if(false !== $player){
					continue ; 	
				}
				$gameClone->setPosition(array($pos1,$pos2));
				$gameClone->setPlayer();
				$value = $this->maxMove($gameClone,++$depth,$alpha,$beta); 

				$gameClone->setPosition(array($pos1,$pos2),true);
				$gameClone->setPlayer();
				if ($value < $beta){ 
					$beta = $value; 
				} 
				
				if ($beta < $alpha){
					return $alpha; 
				} 
			} 
		}

		return $value;
	}
} 