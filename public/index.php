<?php
    require '../group4/vendor/autoload.php';
    require '../group4/config/config.php';

    if (PHP_SAPI === 'cli-server' && $_SERVER['SCRIPT_FILENAME'] !== __FILE__) { return false; }

    //config app with settings
    $app = new \Slim\Slim();

    $app->config($config_array);
    $settingValue = $app->config('templates.path');
    $loader = new Twig_Loader_Filesystem($settingValue);
    $twig = new Twig_Environment($loader, array(
        'cache' => null,
    ));
    //set timezone
    date_default_timezone_set("America/Chicago");

    //database set up PDO class
    $db = new PDO('mysql:host='.$app->config('dbHost').';dbname='.$app->config('dbName').'', $app->config('dbUser'), $app->config('dbPass'));


    // App routes
    require '../CiteRite/src/routes.php';
    // Run app
    $app->run();
?>