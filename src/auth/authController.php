<?php
$netId = null;
$user_id = null;

//production environment
if($_SERVER['SERVER_NAME'] == "pluto.cse.msstate.edu"){
    if(isset($_SESSION['phpCAS']['user'])){
        $netId = $_SESSION['phpCAS']['user'];
        $user_id = $_SESSION['user_id'];
    }
    
} else {
    //local environment
    $_SESSION['phpCAS']['user'] =  "abc123";
    $_SESSION['phpCAS']['user_id'] = 0;
    $netId = $_SESSION['phpCAS']['user'];
    $user_id = $_SESSION['phpCAS']['user_id'];
}
$userIsAdmin = true;
//checks if user is logged in and fills the correct admin role
$authenticateForRole = function ($user_id, $db) {
    return function () use ($user_id, $db) {
        if($user_id === null){
            $app = \Slim\Slim::getInstance();
            $app->response->redirect($app->urlFor('error', array('err' => 'Sorry, you need to log in!')));
        } else {
            $query = "SELECT isAdmin FROM users WHERE id = $user_id";
            $userIsAdmin= $db->query($query)->fetchColumn();
           
             if (!$userIsAdmin ) {
                $app = \Slim\Slim::getInstance();
                $app->response->redirect($app->urlFor('error', array('err' => 'Sorry, you do not have the right permissions to view this page')));

            }
          
        }
        
    };
};
$adminValue = function($user_id, $db){
    if($user_id === null) return false;
    else {
        $query = "SELECT isAdmin FROM users WHERE id = $user_id";
        $userIsAdmin= $db->query($query)->fetchColumn();
        if(!$userIsAdmin){
            return false;
        } else {
            return true;
        }
    }
};
//checks if user is logged in
$authenticated = function($netId){
    return function () use ($netId) {
        if ($netId == null) {
            $app = \Slim\Slim::getInstance();
            $app->response->redirect('/login');
        }
    };
};
?>