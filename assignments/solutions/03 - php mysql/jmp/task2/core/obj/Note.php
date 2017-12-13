<?php

class Note
{
    private $id;
    private $title;
    private $text;

    public function __construct($id, $title, $text)
    {
        $this->id = $id;
        $this->title = $title;
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}