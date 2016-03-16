<?php
class Page
{
    private $id;
    private $data;
    
    public function __construct($cid)
    {
        $data = Database::get_row("n_page", array("cid" => $cid));
        if(!empty($data))
        {
            $this->data = $data;
            $this->id = $cid;
        }
        else
        {
            throw new Exception('The page does not exist');
        }
    }
    
    public function __get($name) 
    {
        return $this->data->$name;
    }
}