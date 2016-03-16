<?php

class Routing {

    private $request_array;
    private $slug;

    public function __construct($request) {
        $good_query_string = Sanitize::url($request);
        $request_array = explode("/", $good_query_string);
        $good_request_array = array();
        foreach ($request_array as $slug) {
            if ($slug == NULL || $slug == ROOT_FOLDER)
                continue;
            $slug_array = explode("?", $slug);
            $good_request_array[] = $slug_array[0];
        }

        $this->slug = $good_request_array[0];
        $this->request_array = array();
        foreach ($good_request_array as $index => $value) {
            if ($index == 0 || $index % 2 != 0)
                continue;
            $this->request_array[$good_request_array[$index - 1]] = $good_request_array[$index];
        }
    }

    public function run() {
        session_start();
        global $user, $url_root;
        Database::connect();
        $user = new User();
        $slug = $this->slug == NULL ? "home" : $this->slug;
        $url_root = ROOT_WEBSITE;

        /*
         * Non header
         */
        if ($slug == 'ajax') 
        {
            $this->checkAjax();
        } elseif ($slug == 'log-out') {
            session_destroy();
            $_SESSION = array();
            header("Location: /");
            exit();
        }
        
        $this->checkForms();

        if ($user->user_id == 0 && ($slug == 'mi-cuenta' || $slug == 'nuevo-proyecto')) {
            header("Location: /");
            exit();
        }
        if ($user->user_id != 0 && ($slug == 'login' || $slug == 'signup')) {
            header("Location: /my-account");
            exit();
        }
        
        /*
         * With header
         */
        include_once 'view/header.php';
        if ($slug == 'home') 
        {
            require_once 'controller/index.php';
        }
        elseif($slug == 'nosotros')
        {
            require_once 'controller/nosotros.php';
        }
        elseif($slug == 'como-funciona')
        {
            require_once 'controller/como-funciona.php';
        }
        elseif($slug == 'contactenos')
        {
            require_once 'controller/contactenos.php';
        }
        elseif($slug == 'mi-cuenta')
        {
            require_once 'controller/mi-cuenta.php';
        }
        elseif($slug == 'nuevo-proyecto')
        {
            require_once 'controller/nuevo-proyecto.php';
        }
        elseif($slug == 'proyecto')
        {
            require_once 'controller/proyecto.php';
        }
        else
        {
            require_once 'controller/404.php';
        }
        
        /*
         * Modals
         */
        if($user->user_id == 0)
        {
            require_once 'view/modals/login-disenador.php';
            require_once 'view/modals/login-proyecto.php';
            require_once 'view/modals/registro-disenador.php';
            require_once 'view/modals/registro-proyecto.php';
        }
        
        require_once 'view/footer.php';        
        return;
    }

    private function checkForms() {
        if ($_POST['form'] != NULL || $this->request_array['form'] != NULL) {
            $file = filter_var($_POST['form'], FILTER_SANITIZE_STRING);
            if ($file == NULL) {
                $file = filter_var($this->request_array['form'], FILTER_SANITIZE_STRING);
            }
            $file = str_replace("/", "", $file);
            if (file_exists(ROOT_WEBSITE . "/" . ROOT_FOLDER . "/forms/" . $file . ".php")) {
                include_once ROOT_WEBSITE . "/" . ROOT_FOLDER . "/forms/" . $file . ".php";
            }
        }
    }
    
    private function checkAjax()
    {
        $action = filter_var($_POST['action'],FILTER_SANITIZE_STRING);
        $action = str_replace("/", "", $action);
        if($action != NULL && file_exists(ROOT_WEBSITE . "/" . ROOT_FOLDER . "/controller/ajax/" . $action . ".php"))
        {
            require_once ROOT_WEBSITE . "/" . ROOT_FOLDER . "/controller/ajax/" . $action . ".php";
        }
        die();
    }

}
