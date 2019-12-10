<?php
class Json
{

    private $file;
    private $content;

    public function __construct($file)
    {
        $this->file = $file;
        $this->contentDecode = file_get_contents($file);
        $this->contentEncode = json_decode($this->contentDecode, true);
    }

    public function read()
    {
        return $this->contentEncode;
    }

    public function save($save)
    {
        $this->contentDecode = json_encode($save);
        $this->contentEncode = $save;
        file_put_contents($this->file, $this->contentDecode);
    }

    public function dump()
    {
        echo "<pre>";
        var_dump($this->contentEncode);
        echo "</pre>";
    }

    public function length()
    {
        return count($this->contentEncode);
    }
}