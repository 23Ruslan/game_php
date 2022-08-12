<?php
include_once ("gameClass.php");
include_once ("minMaxAlgorithm.php");
class TicTacToe extends Game
{
    const ROWS = 3;
    const COLS = 3;
    protected function play(array $move = array())
    {
        if (Game::GOING_ON != ($res = $this->isGameOver()))
        { // Checking if game is over \ going on
            $this->_errorMessage[] = $res;
            $_SESSION['$victoryController'] = !$_SESSION['$victoryController'];
            return false;
        }
        if (Game::PLAYER == $this->_currentPlayer)
        {
            $this->setPosition($move);
            $this->setPlayer();
            $this->computerTurn();
        }
        else $this->computerTurn();
        if (Game::GOING_ON != ($res = $this->isGameOver()))
        { // Checking if game is over after computer turn is done
            $this->_errorMessage[] = $res;
            $_SESSION['$victoryController'] = !$_SESSION['$victoryController'];
            return false;
        }
    }
    public function isGameOver()
    {
        $gameBoardCount = count($this->_gameBoard);
        for ($i = 0;$i < $gameBoardCount;$i++)
        { // whole row
            if (false !== $this->_gameBoard[$i][0] && ($this->_gameBoard[$i][0] == $this->_gameBoard[$i][1] && $this->_gameBoard[$i][1] == $this->_gameBoard[$i][2]))
            {
                return $this->_gameBoard[$i][0];
            }
        }
        $gameBoardCount = count($this->_gameBoard[0]);
        for ($i = 0;$i < $gameBoardCount;$i++)
        { // whole column
            if (false !== $this->_gameBoard[0][$i] && ($this->_gameBoard[0][$i] == $this->_gameBoard[1][$i] && $this->_gameBoard[1][$i] == $this->_gameBoard[2][$i]))
            {
                return $this->_gameBoard[0][$i];
            }
        }
        if (($this->_gameBoard[0][0] == $this->_gameBoard[1][1] // diagonally
         && $this->_gameBoard[1][1] == $this->_gameBoard[2][2]) || ($this->_gameBoard[0][2] == $this->_gameBoard[1][1] && $this->_gameBoard[1][1] == $this->_gameBoard[2][0]))
        {
            if ($this->_gameBoard[1][1]) return $this->_gameBoard[1][1];
        }
        if (true === Game::isBoardFull($this->_gameBoard))
        { // draw
            return Game::DRAW;
        }
        return Game::GOING_ON; // the game is on
        
    }
}