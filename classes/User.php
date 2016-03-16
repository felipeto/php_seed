<?php
class User
{
    public $user_id;
    protected $user_data;
    
    public function __construct()
    {
        $user_id = intval($user_id);
        if($user_id == 0)
        {
            $user_id = intval($_SESSION['user']['id']);
        }
        $user_data = Database::get_row("ez_users",array("user_id" => $user_id));
        if($user_data == NULL)
        {
            $this->user_id = 0;
            $this->user_data = 0;
        }
        else
        {
            $this->user_id = $user_id;
            $this->user_data = $user_data;
        }
    }
    
    public function remove_projectless_files()
    {
        $files = Database::get_list("ez_files",array("user_id" => $this->user_id,"project_id" => 0));
        if(!empty($files))
        {
            foreach($files as $file)
            {
                $file_obj = new File($file->file_id);
                $file_obj->delete();
            }
        }
    }
    
    public function __get($name)
    {
        if(isset($this->user_data->$name))
        {
            return $this->user_data->$name;
        }
        return NULL;
    }
}
