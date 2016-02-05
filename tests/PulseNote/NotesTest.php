<?php

namespace allejo\DaPulse\Tests;

use allejo\DaPulse\Pulse;
use allejo\DaPulse\PulseNote;

class PulseNotesTest extends PulseUnitTest
{
    private $title;
    private $content;
    private $ownersOnly;

    /**
     * @var Pulse
     */
    private $pulse;

    public function setUp ()
    {
        parent::setUp();

        $this->pulse = new Pulse(5523969);

        $this->title      = "Violently Making Toast";
        $this->content    = "By adding the word 'violently' in front a phrase, it makes the phrase more amusing";
        $this->ownersOnly = true;
    }

    public function testCreateNoteWithUpdateButWithoutUser ()
    {
        $this->setExpectedException('\InvalidArgumentException');

        $this->pulse->addNote($this->title, $this->content, true, NULL, true);
    }

    public function testCreateNote ()
    {
        $note = $this->pulse->addNote($this->title, $this->content, $this->ownersOnly);

        $this->assertCountEqual(1, $this->pulse->getNotes());

        return $note;
    }

    /**
     * @depends testCreateNote
     *
     * @param PulseNote $note
     *
     * @return PulseNote
     */
    public function testNoteInfo ($note)
    {
        $this->assertEquals($this->pulse->getId(), $note->getPulseId());
        $this->assertEquals($this->title, $note->getTitle());
        $this->assertEquals($this->content, $note->getContent());
        $this->assertEquals("rich_text", $note->getType());
        $this->assertEquals("owners", $note->getPermissions());

        $this->assertInstanceOf('\DateTime', $note->getCreatedAt());
        $this->assertInstanceOf('\DateTime', $note->getUpdatedAt());

        return $note;
    }

    /**
     * @depends testNoteInfo
     *
     * @param PulseNote $note
     *
     * @return PulseNote
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
     *
     * @param PulseNote $note
     */
    public function testDeletingNote ($note)
    {
        $note->deleteNote();

        $this->assertCountEqual(0, $this->pulse->getNotes());
    }
}