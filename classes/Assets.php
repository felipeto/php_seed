<?php
class Assets
{

    public static function get_custom_styles($slug, $request = array())
    {
        echo '<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">'; 
        if($slug == 'nuevo-proyecto')
        {
            echo '<link rel="stylesheet" href="/assets/css/dropzone.css" type="text/css" />';
            echo '<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">';
            echo '<link rel="stylesheet" href="/assets/css/style-nuevo-proyecto.css" type="text/css" />';
        }
        elseif($slug == 'proyecto')
        {
            echo '<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">';
            echo '<link rel="stylesheet" href="/assets/css/style-proyecto.css" type="text/css" />';
        }
        echo '<link rel="stylesheet" href="/assets/css/style.css" type="text/css" />';
        echo '<link rel="stylesheet" href="/assets/css/dark.css" type="text/css" />';
        echo '<link rel="stylesheet" href="/assets/css/font-icons.css" type="text/css" />';
        echo '<link rel="stylesheet" href="/assets/css/animate.css" type="text/css" />';
        echo '<link rel="stylesheet" href="/assets/css/magnific-popup.css" type="text/css" />';
    }

    public static function get_custom_scripts($slug, $request = array())
    {
        global $user;
        echo '<script src="/assets/js/jquery.js"></script>';
        echo '<script src="/assets/js/plugins.js"></script>';
        //echo '<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>';
        echo '<script src="/assets/js/scripts.js"></script>';
        if($user->user_id == 0)
        {
            echo '<script src="/assets/js/scripts-registro-login.js"></script>';
        }
        if($slug == 'nuevo-proyecto')
        {
            echo '<script src="/assets/js/dropzone.js"></script>';
            echo '<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>';
            echo '<script src="/assets/js/scripts-nuevo-proyecto.js"></script>';
        }
        elseif($slug == 'proyecto')
        {
            echo '<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>';
            echo '<script src="/assets/js/scripts-proyecto.js"></script>';
        }
    }

}
