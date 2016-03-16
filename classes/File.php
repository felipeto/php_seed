<?php
class File
{
    public $file_id;
    public $user_id;
    protected $file_data;

    public function __construct($file_id = 0,$user_id = 0)
    {
        $this->file_id = intval($file_id);
        if($file_id > 0)
        {
            $data = Database::get_row("ez_files",array("file_id" => $file_id));
            if(!empty($data))
            {
                $this->file_id = $file_id;
                $this->file_data = $data;
            }
            else
            {
                $this->file_id = 0;
                $this->file_data = array();
            }
        }
        if($user_id == 0)
        {
            global $user;
            $this->user_id = $user->user_id;
        }
        else       
        {
            $this->user_id = $user_id;
        }
    }
    
    public function get_src()
    {
        $file = "/files/".$this->file_id.".".$this->extension;
        if(file_exists(ROOT_WEBSITE.$file))
        {
            return $file;
        }
        return "";
    }
    
    public function upload($file)
    {
        if($this->user_id > 0)
        {
            if($file['name'] == NULL)
            {
                return "Por favor seleccione un archivo";
            }
            $allowedExts = array("gif", "jpeg", "jpg", "png", "pdf","psd","ai","doc","docx","xls","xlsx","ppt","pps","ppz","ppm","ppa","pot","tiff");
            $temp = explode(".", $file["name"]);
            $extension = strtolower(end($temp));
            $folderDestination = $_SERVER['DOCUMENT_ROOT'] . "/files/";
            if (    
                    $file['error'] == 0 &&
                    (
                        ($file["type"] == "image/gif") || 
                        ($file["type"] == "image/jpeg") || 
                        ($file["type"] == "image/jpg") || 
                        ($file["type"] == "image/pjpeg") || 
                        ($file["type"] == "image/x-png") || 
                        ($file["type"] == "image/png") || 
                        ($file['type'] == "application/pdf") || 
                        ($file['type'] == 'application/x-pdf') ||
                        ($file['type'] == 'application/postscript') ||      //Illustrator
                        ($file['type'] == 'application/octet-stream') ||      //Photoshop
                        ($file['type'] == 'application/msword') ||      
                        ($file['type'] == 'application/excel') ||
                        ($file['type'] == 'application/vnd.ms-excel') ||
                        ($file['type'] == 'application/x-excel') ||
                        ($file['type'] == 'application/x-msexcel') ||
                        ($file['type'] == 'application/mspowerpoint') ||
                        ($file['type'] == 'application/powerpoint') ||
                        ($file['type'] == 'application/x-mspowerpoint') ||
                        ($file['type'] == 'image/tiff') ||
                        ($file['type'] == 'image/x-tiff')
                    ) 
                    && ($file["size"] < MAX_ALLOWED_UPLOAD) && in_array($extension, $allowedExts)
                )
            {
                $name = filter_var($file['name'],FILTER_SANITIZE_STRING);

                Database::insert("ez_files", array(
                    "name" => $name,
                    "extension" => $extension,
                    "user_id" => $this->user_id,
                    "curdate" => time()));
                $file_id = Database::getLastInsertId();

                $destination = $file_id . '.' . $extension;
                if (!move_uploaded_file($file["tmp_name"], $folderDestination . $destination))
                {
                    Database::delete("ez_files", array("file_id" => $file_id));
                    return "Hubo un error guardando el archivo, por favor intente nuevamente.";
                }
                $this->file_id = $file_id;
                $this->file_data = Database::get_row("ez_files", array("file_id" => $file_id));
                return TRUE;
            }
            else
            {
                switch ($file['error'])
                {
                    case UPLOAD_ERR_EXTENSION:
                        return 'Error con la extension del archivo';
                    case UPLOAD_ERR_FORM_SIZE:
                    case UPLOAD_ERR_INI_SIZE:
                        return 'El archivo supera el limite de 25Mbytes';
                    default:
                        return 'Error no identificado subiendo el archivo';
                        
                }
            }
        }
        else
        {
            return "Por favor inicie nuevamente sesion. Ingrese con su email y password.";
        }
    }
    
    public function delete()
    {
        if($this->file_id == 0)
            return false;
        $path = ROOT_WEBSITE.$this->get_src();
        unlink($path);
        Database::delete("ez_files", array("file_id" => $this->file_id));
        $this->file_id = 0;
        $this->file_data = array();
        return true;
    }
    
    
    public function __get($name)
    {
        return $this->file_data->$name;
    }
}
