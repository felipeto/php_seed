<?php
class General
{
    public static function generate_random_password()
    {
        $password = '';
        for($i=0;$i<10;$i++)
        {
            $dice = rand(0,2);
            if($dice == 0)
            {
                $password .= chr(rand(65, 90));     //A-Z
            }
            elseif($dice == 1)
            {
                $password .= chr(rand(97, 122));    //a-z
            }
            elseif($dice == 2)
            {
                $password .= chr(rand(48, 57));    //0-9
            }
        }
        return $password;
    }
    
    public static function get_list_states_abbr()
    {
        $states = Database::get_list("rez_states", array(), "name");
        $states_array = array();
        foreach ($states as $state)
        {
            $states_array[] = $state->abbr;
        }
        return $states_array;
    }
    
    public static function get_list_states()
    {
        $states = Database::get_list("rez_states", array(), "name");
        return $states;
    }
    
    public static function hour_to_number($hour,$close = FALSE)
    {
        $hour_number = str_replace(array(":","am"," ","pm"), "", trim($hour));
        $hour_number = intval($hour_number);
        //var_dump( debug_backtrace( false ) );
        if(strpos($hour, "pm") !== FALSE && $hour_number < 1200)
        {
            $hour_number += 1200;
        }
        if(strpos($hour, "am") !== FALSE && $hour_number >= 1200)
        {
            $hour_number -= 1200;
        }
        $hour_part = round($hour_number/100);
        $min_part = $hour_number - $hour_part*100;
        $hour_number_new = $hour_part*60 + $min_part;
            
        if($close && $hour_number_new == 0)
        {
            return 1440;
        }
        
        return $hour_number_new;
    }
    
    public static function number_to_hour($mins)
    {
        if($mins == 1440)
        {
            return "12:00am";
        }
        $hour = floor($mins/60);
        $mins_alone = abs($mins - $hour*60);
        if($hour == 0)
        {
            $hour = 12;
            $time_day = "am";
        }
        elseif($hour == 12)
        {
            $time_day = "pm";
        }
        elseif($hour > 12)
        {
            $hour = $hour - 12;
            $time_day = "pm";
        }
        else
        {
            $time_day = "am";
        }
        return $hour.":".str_pad($mins_alone, 2, "0", STR_PAD_LEFT).$time_day;
    }
    
    public static function number_to_weekday($number)
    {
        $number = intval($number);
        $days = array("sunday","monday","tuesday","wednesday","thursday","friday","saturday");
        return $days[$number];
    }
    
    public static function get_object_categories()
    {
        //$categories = Database::get_list_query("SELECT Distinct category FROM rez_layout_items ORDER BY category");
		$categories = array("Floor Plan", "Tables", "Furniture", "Specialties");
        return $categories == NULL ? array() : $categories;
    }
    
    public static function get_objects($category = NULL)
    {
        $where_category = array();
        if($category != NULL)
        {
            $where_category = array("category" => $category);
        }
        $objects = Database::get_list("rez_layout_items",$where_category);

        usort($objects, function($a, $b)
        {
		    return strcmp($a->name, $b->name);
        });

        return $objects == NULL ? array() : $objects;
    }
    
    public static function format_phone($phone)
    {
        return preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', '($1) $2-$3', $phone);
    }
    
    public static function feet_to_inches($feet)
    {
        return floatval($feet)*12;
    }
    
    public static function inches_to_feet($inches)
    {
        return floatval($inches)/12;
    }
    
    public static function error_handler($code, $string, $file, $line)
    {
        echo "Error!!<br>";
        echo $code."<br>";
        echo $string."<br>";
        echo $file."<br>";
        echo $line."<br>";
        var_dump(debug_backtrace());
        die();
        //throw new Exception("Error!");
    }
}
