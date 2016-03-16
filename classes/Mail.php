<?php
class Mail
{
    private $to;
    private $from;
    private $headers;
    private $message;
    private $subject;
    
    
    public function __construct($to = NULL, $headers = array())
    {
        $this->to = $to == NULL ? "contact@organicrestaurants.com" : $to;
        if(empty($headers))
        {
            $this->headers = array("MIME-Version: 1.0","Content-type: text/html; charset=UTF-8","from: OrganicRestaurants.com <noreply@organicrestaurants.com>");
        }
        else
        {
            $this->headers = $headers;
        }
    }
    
    public function setTo($to)
    {
        $email = filter_var($to,FILTER_VALIDATE_EMAIL);
        if($email)
        {
            $this->to = $email;
        }
        else
        {
            return FALSE;
        }
    }
    
    public function setMessage($message)
    {
        $this->message = $message;
    }
    
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }
    
    public function setHeaders($headers)
    {
        if(is_array($headers))
        {
            $this->headers = $headers;
        }
    }

    public function send($message = NULL)
    {
        $message = $message == NULL ? $this->message : $message;
        $headers = implode("\r\n", $this->headers);
        return mail($this->to,$this->subject,$message,$headers);
    }
    
    public function set_message_from_template($template_id,$vars = array())
    {
        $template_data = Database::get_row("mail_templates",array("id" => $template_id));
        if($template_data != NULL)
        {
            $this->subject = $template_data->subject;
            $this->message = $template_data->content;
            if(!empty($vars))
            {
                foreach($vars as $var_name => $var_content)
                {
                    $this->message = str_replace("[$var_name]", $var_content, $this->message);
                }
            }
            return TRUE;
        }
        else
        {
            return FALSE;
            /*
             * TODO: Add to error log -> the template was not found
             */
        }
    }
}