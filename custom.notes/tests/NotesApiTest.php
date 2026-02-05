<?php
use PHPUnit\Framework\TestCase;
use Custom\Notes\NotesTable;

class NotesApiTest extends TestCase
{
    protected int $noteId;

    public function testCreateNote()
    {
        $result = NotesTable::add([
            'TITLE' => 'Test note',
            'CONTENT' => 'Test content'
        ]);

        $this->assertTrue($result->isSuccess());
        $this->noteId = $result->getId();
    }

    /**
     * @depends testCreateNote
     */
    public function testReadNote()
    {
        $note = NotesTable::getById($this->noteId)->fetch();
        $this->assertEquals('Test note', $note['TITLE']);
    }

    /**
     * @depends testCreateNote
     */
    public function testUpdateNote()
    {
        $result = NotesTable::update($this->noteId, [
            'TITLE' => 'Updated title'
        ]);

        $this->assertTrue($result->isSuccess());
    }

    /**
     * @depends testCreateNote
     */
    public function testDeleteNote()
    {
        $result = NotesTable::delete($this->noteId);
        $this->assertTrue($result->isSuccess());
    }
}
