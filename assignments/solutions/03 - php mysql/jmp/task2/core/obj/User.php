<?php

require_once("./core/obj/Note.php");

class User
{
    private $id;
    private $nickname;
    private $notes = array();

    public function __construct($id, $nickname, $notes)
    {
        $this->id = $id;
        $this->nickname = $nickname;
        $this->notes = $notes;
    }

    public function addNote($id, $title, $text)
    {
        $this->notes[] = new Note($id, $title, $text);
    }

    public function removeNote($id)
    {
        $index = -1;

        for ($i = 0; $i < sizeof($this->notes); $i++) {
            if ($this->notes[$i]->getId() == $id) {
                $index = $i;
                break;
            }
        }

        if ($index > -1) {
            // delete
            array_splice($this->notes, $index, 1);
            return true;
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getNotes()
    {
        return $this->notes;
    }
}