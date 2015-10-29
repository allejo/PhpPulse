<?php

namespace allejo\DaPulse\Tests;

use allejo\DaPulse\PulseBoard;

class PulseBoardModificationTest extends PulseUnitTest
{
    private $userID;

    public function setUp()
    {
        parent::setUp();

        $this->userID = 303448;
    }

    public function testBoardCreation()
    {
        $newBoard = PulseBoard::createBoard($this->userID, "Fuzzy Unicorn Board", "Unicorns are incredibly majestic");

        $this->assertNotNull($newBoard);
        $this->assertPulseObjectType("PulseBoard", $newBoard);

        $newBoard->deleteBoard();
    }
}