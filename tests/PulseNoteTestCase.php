<?php

namespace allejo\DaPulse\Tests;

use allejo\DaPulse\Exceptions\InvalidObjectException;
use allejo\DaPulse\Pulse;
use allejo\DaPulse\PulseNote;

class PulseNoteTestCase extends PulseUnitTestCase
{
    static $title = "Violently Making Toast";
    static $content = "By adding the word 'violently' in front a phrase, it makes the phrase more amusing";

    /**
     * @var Pulse
     */
    private $pulse;

    public function setUp ()
    {
        parent::setUp();

        $this->pulse = new Pulse(27345095, true);
    }

    public function testCreateNoteWithUpdateButWithoutUser ()
    {
        $this->setExpectedException(\InvalidArgumentException::class);

        $this->pulse->addNote(self::$title, self::$content, true, NULL, true);
    }

    public function testCreateNote ()
    {
        $note = $this->pulse->addNote(self::$title, self::$content, true);

        $this->assertEquals('owners', $note->getPermissions());
        $this->assertEquals(self::$title, $note->getTitle());
        $this->assertEquals(self::$content, $note->getContent());
        $this->assertEquals('rich_text', $note->getType());
        $this->assertInstanceOf(\DateTime::class, $note->getCreatedAt());
        $this->assertInstanceOf(\DateTime::class, $note->getUpdatedAt());

        return $note;
    }

    /**
     * @depends testCreateNote
     * @param   PulseNote $note
     * @return  PulseNote
     */
    public function testEditNote ($note)
    {
        $newTitle = "My new title";

        $note->editTitle($newTitle);
        $this->assertEquals($newTitle, $note->getTitle());

        $newContent = "The world that the children made, here!";

        $note->editContent($newContent);
        $this->assertEquals($newContent, $note->getContent());

        return $note;
    }

    /**
     * @depends testEditNote
     * @param   PulseNote $note
     */
    public function testDeletingNote ($note)
    {
        $this->setExpectedException(InvalidObjectException::class);

        $note->deleteNote();
        $note->deleteNote();
    }
}
