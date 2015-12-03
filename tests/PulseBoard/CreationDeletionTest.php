<?php

namespace allejo\DaPulse\Tests;

use allejo\DaPulse\PulseBoard;

class CreationDeletionTest extends PulseUnitTest
{
    /**
     * @var int
     */
    private $userID;

    public function setUp()
    {
        parent::setUp();

        $this->userID = 303448;
    }

    public function testBoardCreation()
    {
        $boardCountBefore = count(PulseBoard::getBoards());

        $newBoard = PulseBoard::createBoard("Fuzzy Unicorn Board", $this->userID, "Unicorns are incredibly majestic");

        $boardCountAfter = count(PulseBoard::getBoards());

        $this->assertNotNull($newBoard);
        $this->assertPulseObjectType("PulseBoard", $newBoard);
        $this->assertEquals($boardCountBefore + 1, $boardCountAfter);

        return array (
            "board" => $newBoard,
            "initial" => $boardCountBefore
        );
    }

    /**
     * @depends testBoardCreation
     *
     * @param   mixed $arrayOfValues
     */
    public function testBoardDeletion($arrayOfValues)
    {
        /* @var $board PulseBoard */
        $board = $arrayOfValues["board"];
        $board->archiveBoard();

        $currentBoardCount = count(PulseBoard::getBoards());

        $this->assertEquals($arrayOfValues["initial"], $currentBoardCount);
    }
}