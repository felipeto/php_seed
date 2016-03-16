<?php
class Photo
{
    public $photo_id;
    protected $photo_data;
    private $image_sizes;



    public function __construct($photo_id = 0)
    {
        global $image_sizes;
        $this->image_sizes = $image_sizes;
        $this->photo_id = intval($photo_id);
        if($photo_id > 0)
        {
            $data = Database::get_row("rez_photos",array("photo_id" => $photo_id));
            if(!empty($data))
            {
                $this->photo_id = $photo_id;
                $this->photo_data = $data;
            }
            else
            {
                $this->photo_id = 0;
                $this->photo_data = array();
            }
        }
    }
    
    public function upload_photo($photo)
    {
        global $user;
        if($user->user_id > 0)
        {
            if($photo['name'] == NULL)
            {
                return "Please select a File";
            }
            $allowedExts = array("gif", "jpeg", "jpg", "png");
            $temp = explode(".", $photo["name"]);
            $extension = strtolower(end($temp));
            $folderDestination = $_SERVER['DOCUMENT_ROOT'] . "/photos/";
            if ((($photo["type"] == "image/gif") || ($photo["type"] == "image/jpeg") || ($photo["type"] == "image/jpg") || ($photo["type"] == "image/pjpeg") || ($photo["type"] == "image/x-png") || ($photo["type"] == "image/png")) && ($photo["size"] < 12000000) && in_array($extension, $allowedExts))
            {
                if ($photo["error"] > 0)
                {
                    return "Error uploading file. Error: " . $photo["error"];
                }
                else
                {
                    $name = filter_var($photo['name'],FILTER_SANITIZE_STRING);
                    
                    Database::insert("rez_photos", array("name" => $name,"extension" => $extension,"user_id_added" => $user->user_id,"curdate" => time()));
                    $photo_id = Database::getLastInsertId();
                    
                    $destination = $photo_id . '.' . $extension;
                    if (!move_uploaded_file($photo["tmp_name"], $folderDestination . $destination))
                    {
                        Database::delete("rez_photos", array("photo_id" => $photo_id));
                        return "There was an error saving the file. Please try again.";
                    }
                    $this->photo_id = $photo_id;
                    $this->photo_data = Database::get_row("rez_photos", array("photo_id" => $photo_id));
                    return TRUE;
                }
            }
            else
            {
                return "The file has a invalid extension.";
            }
        }
        else
        {
            return "Please log into your account";
        }
    }
    
    public function get_photo($size = 'thumb',$createImage = TRUE)
    {
        if($this->photo_id == NULL || $this->photo_id == 0)
        {
            return "";
        }
        if($size == 'full')
        {
            return $this->photo_id . '.' . $this->extension;
        }
        if(empty($this->image_sizes[$size]))
        {
            $size = 'thumb';
        }
        $thumb = $this->photo_id . '-'.$size.'.' .$this->extension;
        if(!file_exists(ROOT_WEBSITE."/photos/".$thumb) && $createImage)
        {
            ini_set('memory_limit', '100M');
            $full = $this->photo_id . '.' . $this->extension;
            if(file_exists(ROOT_WEBSITE."/photos/".$full))
            {
                if($this->create_image_size($size))
                {
                    return $thumb;
                }
                else
                {
                    return $full;
                }
            }
            else
            {
                return NULL;
            }
            
        }
        return $thumb;
    }
    
    public function get_photo_src($size = 'thumb')
    {
        if($this->photo_id == NULL || $this->photo_id == 0)
        {
            return "";
        }
        $thumb = $this->get_photo($size);
        return "/photos/".$thumb;
    }
    
    public function get_photo_html($size = 'thumb')
    {
        if($this->photo_id == NULL || $this->photo_id == 0)
        {
            return "";
        }
        $thumb = $this->get_photo($size);
        return "<img src='/photos/".$thumb."'>";
    }
    
    private function create_image_size($thumbname)
    {
        $filename = $this->photo_id . '.' .  $this->extension;
        $width = $this->image_sizes[$thumbname][0];
        $height = $this->image_sizes[$thumbname][1];
        $thumbFilename = $this->get_photo($thumbname,FALSE);
        $file_name_and_path = ROOT_WEBSITE.'/photos/'.$thumbFilename;
        
        $img = new Imagick(ROOT_WEBSITE.'/photos/'.$filename);
        
        // get file dims
        $dimX = $img->getImageWidth();
        $dimY = $img->getImageHeight();
        
        if($this->width == 0 || $this->height == 0)
        {
            $this->width = $dimX;
            $this->height = $dimY;
        }

        // check dims for proper size
        if($width >= 500)
        {
            if($dimX >= $width)
            {
                //crop and resize the image
                $img->thumbnailImage($width,$dimY);
                //remove the canvas (for .gif's only)
                if ($ext == 'gif') { $img->setImagePage(0, 0, 0, 0); }
                // Writes resultant image to output directory
                $result = $img->writeImage($file_name_and_path); // NULL .. overwrites original file
                // Destroys Imagick object, freeing allocated resources in the process
                $img->destroy();
                return $result;
            }
            elseif($dimY >= $height)
            {
                //crop and resize the image
                $img->thumbnailImage($height,$dimX);
                //remove the canvas (for .gif's only)
                if ($ext == 'gif') { $img->setImagePage(0, 0, 0, 0); }
                // Writes resultant image to output directory
                $result = $img->writeImage($file_name_and_path); // NULL .. overwrites original file
                // Destroys Imagick object, freeing allocated resources in the process
                $img->destroy();
                return $result;
            }
        }
        elseif ($dimX >= $width && $dimY >= $height) 
        {
            //crop and resize the image
            $img->cropThumbnailImage($width,$height);
            //remove the canvas (for .gif's only)
            if ($ext == 'gif') { $img->setImagePage(0, 0, 0, 0); }
            // Writes resultant image to output directory
            $result = $img->writeImage($file_name_and_path); // NULL .. overwrites original file
            // Destroys Imagick object, freeing allocated resources in the process
            $img->destroy();
            return $result;
        }
        else
        {
            $result = $img->writeImage($file_name_and_path);
            return $result;
        }
        return FALSE;
    }
    
    
    public function __get($name)
    {
        return $this->photo_data->$name;
    }
    
    public function __set($name, $value)
    {
        if(Database::update("rez_photos", array($name => $value), array("photo_id" => $this->photo_id)) === TRUE)
        {
            $this->photo_data = Database::get_row("rez_photos", array("photo_id" => $photo_id));
        }
    }
}
