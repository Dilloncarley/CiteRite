<?php

    // CAS authentication 
    include_once('../cosmiccommerce/src/auth/inc/CAS/1.3.5/casAuth.php');

    $req = $app->request;
    //Get root URI
    $rootUri = $req->getRootUri();
    
    if(isset($_SESSION['phpCAS']['user'])){
        $netId = $_SESSION['phpCAS']['user'];
        $query = "SELECT username FROM users WHERE username = '$netId'";
        $findUserQuery = $db->query($query)->fetchColumn();

        //couldn't find user, so insert user into users table
        if(!$findUserQuery){
            $query = "INSERT INTO users (username, isAdmin) VALUES ('$netId', 0)";
          
           $db->query($query);
          
        } else {
           //set user_id in session
           $query = "SELECT id FROM users WHERE username = '$netId'";
           $_SESSION['user_id'] =  $db->query($query)->fetchColumn();
        }

         header("Location: ". $rootUri . "/");
         exit;
      } else {
         header("Location: ". $rootUri . "");
         exit;
      }
   
?>