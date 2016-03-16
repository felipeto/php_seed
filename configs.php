<?php
define('ENVIROMENT', 'development');                        //development | production
define('ROOT_FOLDER', '');                                  //set empty '' when is the website root folder
define('WEBSITEKEY','');
define('WEBSITE_DOMAIN_URL','');


define('ROOT_WEBSITE', $_SERVER['DOCUMENT_ROOT']);
define("GOOGLE_API_KEY", "");



/*
 * Facebook
 */
define('FACEBOOK_APP_ID','');
define('FACEBOOK_SECRET','');


/*
 * Images Sizes
 */
global $image_sizes;
$image_sizes = array("thumb" => array(88,88),
                   "medium" => array(292,200),
                   "large" => array(600,900),
    );
