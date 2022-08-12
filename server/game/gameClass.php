<?php
class GameException extends Exception
{ // our own exceptions
    
}
interface IGame
{
    public function __toString(); // return JSON object
    public function __construct(array $conf);
}
abstract class Game implements IGame
{
    protected $_move = [];
    protected $_gameBoard;
    protected $_errorMessage = [];
    const GOING_ON = 2000, PLAYER = 1, DRAW = 1000, COMPUTER = 2, ALLOWED_MOVE = 2;
    public function setPlayer()
    { // toggle players
        $this->_currentPlayer = self::PLAYER == $this->_currentPlayer ? self::COMPUTER : self::PLAYER;
    }
    function clearAll()
    {
        $board = array();
        for ($i = 0;$i < static ::ROWS;$i++)
        {
            $board[$i] = array();
            for ($j = 0;$j < static ::COLS;$j++) array_push($board[$i], false);
        }
        $this->_gameBoard = $board;
    }
    function setGameBoard($gameBoard)
    {
        foreach ($gameBoard as $position1 => $columns) foreach ($columns as $position2 => $player) $this->_gameBoard[$position1][$position2] = !$player ? false : $player;
    }
    function computerTurn()
    {
        $getMinMaxResult = new MinMax($this);
        if ($getMinMaxResult->move) return $this->_move = $getMinMaxResult->move;
        return false;
    }
    public function __construct(array $conf)
    {
        if (isset($conf['gameBoard'])) // Continue or starting new game
        $this->setGameBoard($conf['gameBoard']);
        else $this->clearAll();
        $this->_currentPlayer = $conf['player'];
        $this->play($conf['move']);
    }
    static function isBoardFull(array $gameBoard)
    {
        foreach ($gameBoard as $position1 => $columns) foreach ($columns as $position2 => $player)
        {
            if ($player) continue;
            $blankPositions[] = array(
                $position1,
                $position2
            );
        }
        return !empty($blankPositions) ? $blankPositions : true;
    }
    function setPosition(array $move, $isEmpty = false)
    {
        if (false === self::isAlreadyTaken($move, $this->_gameBoard) || $isEmpty) return $this->_gameBoard[$move[0]][$move[1]] = $isEmpty ? false : $this->_currentPlayer;
    }
    function getGameBoard()
    {
        return $this->_gameBoard;
    }
    static function isAllowedMove(array $move)
    {
        if (!empty($move) && self::ALLOWED_MOVE == count($move)) return true;
        return false;
    }
    private static function isAlreadyTaken(array $move, array $gameBoard)
    {
        if (self::isAllowedMove($move)) return $gameBoard[$move[0]][$move[1]];
    }
    function __toString()
    { // Return JSON answers for ajax support
        $res = array();
        if (!empty($this->_errorMessage)) $res['errorMessage'] = $this->_errorMessage;
        if (!empty($this->_move)) $res['move'] = $this->_move;
        return json_encode($res);
    }
    abstract protected function play(array $move = array());
    abstract protected function isGameOver();
}

