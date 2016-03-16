<?php

class Database
{

    static $db;

    public static function connect()
    {
        if (self::$db == NULL)
        {
            try
            {
                $db = new PDO('mysql:host=' . DATABASE_HOST . ';dbname=' . DATABASE_NAME, DATABASE_USER, DATABASE_PASSWORD);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $db->exec("SET CHARACTER SET utf8");
                self::$db = $db;
            } 
            catch (Exception $ex) 
            {
                return self::trigger_error("Error connecting to the database: " . $ex->getMessage());
            }
        }
    }

    public static function get_var($table, $column, $where = array(), $order_column = NULL, $order = 'ASC')
    {
        if (!empty($where))
        {
            foreach (array_keys($where) as $field)
            {
                $wheres[] = "$field = '".$where[$field]."'";
            }
        }
        $sql = "SELECT `$column` FROM `$table` ";
        if(!empty($wheres))
        {
            $sql .= ' WHERE ' . implode( ' AND ', $wheres );
        }
        if ($order_column != NULL)
        {
            $sql .= " ORDER BY `$order_column` $order";
        }
        $sql .= " LIMIT 1";
        try
        {
            $query = self::$db->prepare($sql);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_NUM);
            return $result[0];
        }
        catch (Exception $e)
        {
            return self::trigger_error("Error in database get_var " . $e->getMessage()." ".$sql);
        }
    }
    
    public static function get_var_query($sql)
    {
        try
        {
            $query = self::$db->prepare($sql);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_NUM);
            return $result[0];
        }
        catch (Exception $e)
        {
            return self::trigger_error("Error in database get_var " . $e->getMessage()." ".$sql);
        }
    }

    public static function get_row($table, $where = array(), $order_column = NULL, $order = 'ASC')
    {
        if (!empty($where))
        {
            foreach (array_keys($where) as $field)
            {
                $wheres[] = "$field = '".trim($where[$field])."'";
            }
        }
        $sql = "SELECT * FROM `$table` ";
        if(!empty($wheres))
        {
            $sql .= ' WHERE ' . implode( ' AND ', $wheres );
        }
        if ($order_column != NULL)
        {
            $sql .= " ORDER BY `$order_column` $order";
        }
        $sql .= " LIMIT 1";
        try
        {
            $query = self::$db->prepare($sql);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_OBJ);
            return $result;
        }
        catch (Exception $e)
        {
            return self::trigger_error("Error in database get_row " . $e->getMessage()." ".$sql);
        }
    }
    
    public static function get_row_query($sql)
    {
        try
        {
            $query = self::$db->prepare($sql);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_OBJ);
            return $result;
        }
        catch (Exception $e)
        {
            return self::trigger_error("Error in database get_row " . $e->getMessage()." ".$sql);
        }
    }
    
    
    public static function get_list($table,$where = array(),$order_string = NULL,$start = NULL,$limit = NULL)
    {
        if (!empty($where))
        {
            foreach (array_keys($where) as $field)
            {
                $wheres[] = "$field = '".$where[$field]."'";
            }
        }
        $sql = "SELECT * FROM `$table` ";
        if(!empty($wheres))
        {
            $sql .= ' WHERE ' . implode( ' AND ', $wheres );
        }
        if ($order_string != NULL)
        {
            $sql .= " ORDER BY $order_string";
        }
        if($limit != NULL)
        {
            if($start != NULL)
            {
                $sql .= " LIMIT $start,$limit";
            }
            else
            {
                $sql .= " LIMIT $limit";
            }
        }
        try
        {
            //echo $sql;
            $query = self::$db->prepare($sql);
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_OBJ);
            return $result;
        }
        catch (Exception $e)
        {
            return self::trigger_error("Error in database get_list " . $e->getMessage()." ".$sql);
        }
    }
    
    public static function get_list_query($sql)
    {
        if($sql == NULL)
        {
            return self::trigger_error("Error in database get_list_query: The query cannot be null");
        }
        try
        {
            $query = self::$db->prepare($sql);
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_OBJ);
            return $result;
        }
        catch (Exception $e)
        {
            return self::trigger_error("Error in database get_list: " . $e->getMessage()." ".$sql);
        }
    }
    
    public static function insert($table,$data)
    {
        if(empty($data))
        {
            return Database::trigger_error("Data cannot be empty in insert");
        }
        $data = Database::sanitize_array_string($data);
        
        $sql = "INSERT INTO `$table` (`".implode('`,`',  array_keys($data))."`) VALUES ('" .implode("','", $data). "')";
        
        $sql = str_replace("'NULL'", "NULL", $sql);
        
        try
        {
            $query = self::$db->prepare($sql);
            $query->execute();
            return TRUE;
        }
        catch (Exception $e)
        {
            return self::trigger_error("Error in database insert: " . $e->getMessage()." ".$sql,FALSE);
        }
    }
    
    public static function update($table,$data,$where)
    {
        if(empty($where) || !is_array($where))
        {
            return self::trigger_error("The where var cannot be empty and has to be array in update");
        }
        if(empty($data))
        {
            return Database::trigger_error("Data cannot be empty in update");
        }
        $data = Database::sanitize_array_string($data);
        $wheres = array();
        if (!empty($where))
        {
            foreach (array_keys($where) as $field)
            {
                $wheres[] = "$field = '".$where[$field]."'";
            }
        }
        
        $bits = array();
        foreach ( (array) array_keys( $data ) as $field ) 
        {
                $bits[] = "`$field` = '{$data[$field]}'";
        }
        $sql = "UPDATE `$table` SET " . implode( ', ', $bits ) . ' WHERE ' . implode( ' AND ', $wheres );
        try
        {
            $query = self::$db->prepare($sql);
            $query->execute();
            return TRUE;
        }
        catch (Exception $e)
        {
            return self::trigger_error("Error in database update: " . $e->getMessage()." ".$sql);
        }
    }
    
    public static function delete($table,$where)
    {
        $wheres = array();
        if (!empty($where))
        {
            foreach (array_keys($where) as $field)
            {
                if($where[$field] === NULL)
                {
                    $wheres[] = "$field = NULL";
                }
                else
                {
                    $wheres[] = "$field = '".$where[$field]."'";
                }
            }
        }
        
        $sql = "DELETE FROM `$table` WHERE " . implode( ' AND ', $wheres );
        try
        {
            $query = self::$db->prepare($sql);
            $query->execute();
            return $query->rowCount();
        }
        catch (Exception $e)
        {
            return self::trigger_error("Error in database insert: " . $e->getMessage()." ".$sql);
        }   
    }


    public static function query($sql)
    {
        if($sql == NULL)
        {
            return self::trigger_error("Error in database query: The query cannot be null");
        }
        try
        {
            $query = self::$db->prepare($sql);
            return $query->execute();
        }
        catch (Exception $e)
        {
            return self::trigger_error("Error in database query: " . $e->getMessage()." ".$sql);
        }
    }
    
    public static function query_u($sql)
    {
        if($sql == NULL)
        {
            return self::trigger_error("Error in database query: The query cannot be null");
        }
        try
        {
            $query = self::$db->prepare($sql);
            return $query->execute();
        }
        catch (Exception $e)
        {
            throw new Exception($sql." ".$e->getMessage());
        }
    }
    
    public static function getLastInsertId()
    {
        return self::$db->lastInsertId();
    }

    public static function remove_all_tags($string)
    {
        $string = preg_replace('@<(script|style)[^>]*?>.*?</\\1>@si', '', $string);
        $string = strip_tags($string);
        return $string;
    }
    
    public static function sanitize_array_string($array)
    {
        $data = array();
        foreach($array as $index => $value)
        {
            if($index == 'hours_json')
                $data[$index] = $value;
            else
                $data[$index] = Database::sanitize_string($value);
        }
        return $data;
    }
    
    public static function sanitize_string($string)
    {
        return filter_var($string, FILTER_SANITIZE_STRING);
    }
    
    private static function trigger_error($msg,$echo = true)
    {
        global $user;
        if (ENVIROMENT == 'development')
        {
            if($echo)
            {
                echo $msg;
            }
            else
            {
                return $msg;
            }
        }
        return FALSE;
    }

}
