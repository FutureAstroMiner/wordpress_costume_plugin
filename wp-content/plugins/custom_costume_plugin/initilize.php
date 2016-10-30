<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

error_reporting(E_ALL);

defined('DS') ? NULL : define('DS', DIRECTORY_SEPARATOR);

//site root path in the filesystem
//defined('SITE_ROOT') ? NULL :
//define('SITE_ROOT', DS.'var'.DS.'www'.DS.'html'.DS.'danceSchool');

//path to included files
//defined('LIB_PATH') ? NULL : define('LIB_PATH', SITE_ROOT.DS.'includes');

// load config first
require_once 'config.php';

//load basic functions next so that everything after can use them
//require_once LIB_PATH.DS.'functions.php';

//load core objects
require_once 'admin' . DS . 'db_dummy_data.php';
require_once 'admin' . DS . 'assemble_costume.php';
require_once 'admin' . DS . 'upload.php';
require_once 'admin' . DS . 'post_costume.php';
require_once 'admin' . DS . 'admin.php';
//require_once LIB_PATH.DS.'session.php';
//require_once LIB_PATH.DS.'database.php';
//require_once LIB_PATH.DS.'database_object.php';

//load database-related classes
//require_once LIB_PATH.DS.'user.php';
//require_once LIB_PATH.DS.'owner.php';
//require_once LIB_PATH.DS.'school.php';
//require_once LIB_PATH.DS.'student.php';
//require_once LIB_PATH.DS.'upload_file.php';

?>