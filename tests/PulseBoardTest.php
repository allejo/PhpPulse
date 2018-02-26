<?php

namespace allejo\DaPulse\Tests;

use allejo\DaPulse\Exceptions\ArgumentMismatchException;
use allejo\DaPulse\Exceptions\InvalidArraySizeException;
use allejo\DaPulse\Exceptions\InvalidColumnException;
use allejo\DaPulse\Exceptions\InvalidObjectException;
use allejo\DaPulse\Objects\PulseColumnStatusValue;
use allejo\DaPulse\Pulse;
use allejo\DaPulse\PulseBoard;
use allejo\DaPulse\PulseColumn;
use allejo\DaPulse\PulseGroup;

class PulseBoardTest extends PulseUnitTest
{
    private $id;

    /**
     * @var PulseBoard
     */
    private $board;

    /**
     * @var Pulse[]
     */
    private $pulses;

    public function setUp()
    {
        parent::setUp();

        $this->id = 3844236;
        $this->board = new PulseBoard($this->id);
        $this->pulses = $this->board->getPulses(1000, 1);
    }

    public function testGetBoardUrl()
    {
        $expectedURL = "https://phppulse.monday.com/boards/" . $this->id;
        $this->assertEquals($expectedURL, $this->board->getUrl());
    }

    public function testGetBoardId()
    {
        $this->assertEquals($this->id, $this->board->getId());
    }

    public function testGetBoardName()
    {
        $expectedValue = "API Mocks";
        $this->assertEquals($expectedValue, $this->board->getName());
    }

    public function testGetBoardDescription()
    {
        $expectedValue = "A DaPulse board used for unit testing and providing mocks for the PhpPulse library we use and maintain";
        $this->assertEquals($expectedValue, $this->board->getDescription());
    }

    public function testGetBoardColumnsCount()
    {
        $this->assertCountGreaterThan(0, $this->board->getColumns());
    }

    public function testGetBoardColumnsType()
    {
        $column = $this->board->getColumns();

        $this->assertInstanceOf(PulseColumn::class, $column[0]);
    }

    public function testGetBoardGroupType()
    {
        $group = $this->board->getGroups();

        $this->assertInstanceOf(PulseGroup::class, $group[0]);
    }

    public function testGetBoardCreatedAt()
    {
        $this->assertInstanceOf(\DateTime::class, $this->board->getCreatedAt());
    }

    public function testGetBoardUpdatedAt()
    {
        $this->assertInstanceOf(\DateTime::class, $this->board->getUpdatedAt());
    }

    public function testGetPulses()
    {
        $this->assertCountEqual(4, $this->pulses);
    }

    public function testGroupOfPulseWithApiCall()
    {
        $newPulse = new Pulse(27157096);

        $this->assertEquals('topics', $newPulse->getGroupId());
    }

    public function testGetBoardColumns()
    {
        $columns = $this->board->getColumns();

        $this->assertIsArray($columns);
        $this->assertCount(8, $columns); // 6 custom columns + "name" + "last update"
        $this->assertInstanceOf(PulseColumn::class, $columns[0]);
    }

    public function testGetBoardStatusColumnLabels()
    {
        $columns = $this->board->getColumns();
        $labels = $columns[2]->getLabels();

        // The max value is 0 based, so add one for the total count
        $this->assertCount(PulseColumnStatusValue::MAX_VALUE + 1, $labels);
    }

    public function testGetBoardTextColumnLabelsThrowsException()
    {
        $this->setExpectedException(InvalidColumnException::class);

        $columns = $this->board->getColumns();
        $columns[0]->getLabels();
    }

    public function testGetBoardGroups()
    {
        $groups = $this->board->getGroups();

        $this->assertIsArray($groups);
        $this->assertCount(2, $groups);
        $this->assertInstanceOf(PulseGroup::class, $groups[0]);

        $group_one = $groups[0];
        $this->assertEquals('#037f4c', $group_one->getColor());
        $this->assertEquals('Group One', $group_one->getTitle());
        $this->assertEquals($this->board->getId(), $group_one->getBoardId());
    }

    public function testGetBoardGroupsIncludingArchived()
    {
        $groups = $this->board->getGroups(true);

        $this->assertIsArray($groups);
        $this->assertCount(3, $groups);
        $this->assertInstanceOf(PulseGroup::class, $groups[0]);
        $this->assertTrue($groups[2]->isArchived());
    }

    public function testBoardCreateStatusColumn()
    {
        $board = new PulseBoard(27168881, true);
        $board->createColumn('Overall Status', PulseColumn::Status, [
            PulseColumnStatusValue::Gold  => 'Warning',
            PulseColumnStatusValue::Green => 'Success'
        ]);

        $columns = $board->getColumns();
        $this->assertCount(9, $columns);
    }

    public function testBoardCreateTextColumnWithLabelsThrowsException()
    {
        $this->setExpectedException(ArgumentMismatchException::class);

        $board = new PulseBoard(27168881, true);
        $board->createColumn('Super Toast', PulseColumn::Text, [
            'toaster'
        ]);
    }

    public function testBoardCreateStatusColumnWithInvalidColumns()
    {
        $this->setExpectedException(InvalidArraySizeException::class);

        $board = new PulseBoard(27168881, true);
        $board->createColumn('Project Status', PulseColumn::Status, [
            0  => 'Success!',
            11 => 'Crashed and burned'
        ]);
    }

    public function testBoardCreateGroup()
    {
        $boardID = 27168881;
        $title = 'My new group';
        $board = new PulseBoard($boardID, true);
        $group = $board->createGroup($title);

        $this->assertInstanceOf(PulseGroup::class, $group);
        $this->assertEquals($title, $group->getTitle());
        $this->assertEquals($boardID, $group->getBoardId());
        $this->assertFalse($group->isArchived());
        $this->assertFalse($group->isDeleted());
    }

    public function testBoardDeleteGroup()
    {
        $groupID = 'my_new_group';
        $board = new PulseBoard(27168881, true);
        $groups = $board->deleteGroup($groupID);

        foreach ($groups as $group)
        {
            if ($group->getId() == $groupID)
            {
                $this->assertTrue($group->isArchived());
                break;
            }
        }
    }

    public function testBoardCreatePulse()
    {
        $title = 'Turn off the lights';
        $board = new PulseBoard(27168881);
        $pulse = $board->createPulse($title, self::MainUser);

        $this->assertEquals(self::MainUser, $pulse->getSubscribers()[0]->getId());
        $this->assertEquals($title, $pulse->getName());
        $this->assertEquals($board->getId(), $pulse->getBoardId());
        $this->assertEquals('topics', $pulse->getGroupId());
    }

    public function testBoardCreation()
    {
        $name = 'A New Bored';
        $desc = 'A purposeful typo';
        $newBoard = PulseBoard::createBoard($name, self::MainUser, $desc);

        $this->assertEquals(self::MainUser, $newBoard->getSubscribers()[0]->getId());
        $this->assertEquals($name, $newBoard->getName());
        $this->assertEquals($desc, $newBoard->getDescription());
    }

    public function testBoardDeletionMarksObjectAsDeleted()
    {
        $this->setExpectedException(InvalidObjectException::class);

        $board = new PulseBoard(27790765, true);
        $board->archiveBoard();
        $board->archiveBoard();
    }

    public function testBoardGetAll()
    {
        $boards = PulseBoard::getBoards();

        $this->assertCount(4, $boards);
        $this->assertInstanceOf(PulseBoard::class, $boards[0]);
    }
}
