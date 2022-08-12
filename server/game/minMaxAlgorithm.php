<?php
interface IMinAndMax
{
    public function __construct(Game $game);
}
class MinMax implements IMinAndMax
{
    protected $_game;
    public $move;
    const MIN = - 700000;
    const MAX = 700000;
    public function __construct(Game $game)
    {
        $this->_game = $game;
        $this->move = $this->minMax();
    }
    protected function minMax()
    {
        $gameBoard = $this
            ->_game
            ->getGameBoard(); // clone the current game process, but don't destroy the returned result (after changing the board game status)
        $choose = self::MIN;
        foreach ($gameBoard as $position1 => $columns)
        {
            foreach ($columns as $position2 => $player)
            {
                if ($player) continue;
                $gameClone = clone $this->_game;
                $gameClone->setPosition(array(
                    $position1,
                    $position2
                ));
                $gameClone->setPlayer();
                $value = $this->minMove($gameClone, 1, $choose, self::MAX);
                $gameClone->setPosition(array(
                    $position1,
                    $position2
                ) , true);
                if ($value > $choose)
                {
                    $choose = $value;
                    $move = array(
                        $position1,
                        $position2
                    );
                }
                $gameClone->setPlayer();
            }
        }
        if (!empty($move))
        {
            $this
                ->_game
                ->setPosition($move);
            return $move;
        }
        return false;
    }
    protected function score(Game $gameClone, $d)
    {
        $res = $gameClone->isGameOver();
        if ($_SESSION['$victoryController']) // if true - the bot becomes invincible, if not - it gives the player many opportunities to win
        switch ($res)
        {
            case Game::COMPUTER:
                return 100 - $d;
            break;
            case Game::PLAYER:
                return $d - 100;
            break;
            case Game::DRAW:
                return 0;
            break;
        }
        else switch ($res)
        {
            case Game::PLAYER:
                return 100 - $d;
            break;
            case Game::COMPUTER:
                return $d - 100;
            break;
            case Game::DRAW:
                return 0;
            break;
        }
        return TicTacToe::GOING_ON;
    }
    protected function maxMove(Game $gameClone, $d, $a, $b)
    {
        $res = $this->score($gameClone, $d);
        if (TicTacToe::GOING_ON != $res) return $res;
        $gameBoard = $gameClone->getGameBoard();
        foreach ($gameBoard as $position1 => $colmuns)
        {
            foreach ($colmuns as $position2 => $player)
            {
                if ($player)
                {
                    continue;
                }
                $gameClone->setPosition(array(
                    $position1,
                    $position2
                ));
                $gameClone->setPlayer();
                $value = $this->minMove($gameClone, ++$d, $a, $b);
                $gameClone->setPosition(array(
                    $position1,
                    $position2
                ) , true); // Return the board so we don't have position 1, position 2 is taken
                $gameClone->setPlayer();
                if ($value > $a)
                {
                    $a = $value;
                }
                if ($a > $b)
                {
                    return $b;
                }
            }
        }
        return $value;
    }
    protected function MinMove(Game $gameClone, $d, $a, $b)
    {
        $res = $this->score($gameClone, $d);
        if (TicTacToe::GOING_ON != $res) return $res;
        $gameBoard = $gameClone->getGameBoard();
        foreach ($gameBoard as $position1 => $colmuns)
        {
            foreach ($colmuns as $position2 => $player)
            {
                if ($player) continue;
                $gameClone->setPosition(array(
                    $position1,
                    $position2
                ));
                $gameClone->setPlayer();
                $value = $this->maxMove($gameClone, ++$d, $a, $b);
                $gameClone->setPosition(array(
                    $position1,
                    $position2
                ) , true);
                $gameClone->setPlayer();
                if ($value < $b) $b = $value;
                if ($b < $a) return $a;
            }
        }
        return $value;
    }
}

