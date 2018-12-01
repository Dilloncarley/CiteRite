<?php 
session_start();

//all basic logic for authentication on production and local environments
require_once('auth/authController.php');

$isAdmin = $adminValue($user_id, $db);
//home page
$app->get('/', function () use ($app, $twig, $netId, $isAdmin) {
    echo $twig->render('home.html', array('app' => $app, 'netId' => $netId, 'isAdmin' => $isAdmin));

})->setName('home');


$app->get('/404/:err', function($err) use ($app, $twig, $netId){
    echo $twig->render('404.html', array('app' => $app, 'netId' => $netId, 'error' => $err));

})->setName('error');


//login page
$app->get('/login', function() use ($app, $twig, $db) {
     //auth through CAS system
     echo $app->render('casSystem.php', array('app' => $app));
   
});
//logout page
//logout
$app->get('/logout', function() use ($app, $twig) {
    // remove all session variables
    session_unset(); 

    // destroy the session 
    session_destroy(); 
    
    $app->response->redirect($app->urlFor('home'));


});


//about page
$app->get('/about', function() use ($app, $twig, $netId)  {
    
    echo $twig->render('about.html', array('app' => $app, 'netId' => $netId));
});

$isAdmin = $adminValue($user_id, $db);
//professor dashboard
// API group
$app->group('/professor-dashboard', $authenticated($netId), $authenticateForRole($user_id, $db), function () use ($app, $db, $twig, $netId, $user_id, $isAdmin) {
        
        //professor dashboard
        $app->get('/', function () use ($app, $db, $twig, $user_id, $netId, $isAdmin){
            echo $twig->render('professor-dashboard.html', array('app' => $app, 'netId' => $netId, 'isAdmin' => $isAdmin));
        });

        // Quiz group
        $app->group('/quiz', function () use ($app, $db, $twig, $user_id, $netId, $isAdmin) {

            // quiz form
            $app->get('/create/quiz', function () use ($app, $db, $twig, $user_id, $netId, $isAdmin){
                echo $twig->render('create_quiz.html', array('app' => $app, 'netId' => $netId, 'isAdmin' => $isAdmin));
            });

            // create quiz
            $app->post('/create/quiz', function () use ($app, $db, $twig, $user_id, $netId, $isAdmin) {
                require_once('controllers/create_quiz.php');
            });

            // view quiz
            $app->get('/view/:id', function ($id) use ($app, $db, $twig, $user_id, $netId, $isAdmin){
                echo $twig->render('quiz.html', array('app' => $app, 'netId' => $netId));
            });

            // Delete quiz with ID
            $app->post('/delete/:id', function ($id) use ($app, $db, $twig, $user_id, $netId, $isAdmin) {

            });

            // Update quiz with ID
            $app->post('/update/:id', function ($id) use ($app, $db, $twig, $user_id, $netId, $isAdmin) {

            });
        });
        

        // Questions group
        $app->group('/quiz/question', function () use ($app, $db, $twig, $user_id, $netId, $isAdmin) {

            // create question form
            $app->get('/create', function () use ($app, $db, $twig, $user_id, $netId, $isAdmin){

            });
            // create question
            $app->post('/create', function () use ($app, $db, $twig, $user_id, $netId, $isAdmin) {

            });

            // update question form
            $app->get('/update/:id', function ($id) use ($app, $db, $twig, $user_id, $netId, $isAdmin) {

            });
            // Update question with ID
            $app->post('/update/:id', function ($id) use ($app, $db, $twig, $user_id, $netId, $isAdmin) {

            });

            // Delete question with ID
            $app->post('/delete/:id', function ($id) use ($app, $db, $twig, $user_id, $netId, $isAdmin) {

            });

            

        });

});



$app->group('/student-dashboard', $authenticated($netId), function () use ($app, $db, $twig, $netId, $user_id, $isAdmin) {
        //student dashboard
        $app->get('/', function() use ($app, $twig, $db, $netId, $isAdmin)  {
            echo $twig->render('student-dashboard.html', array('app' => $app, 'netId' => $netId, 'isAdmin' => $isAdmin));
        });

        //specific quiz page
        $app->get('/quiz/:quizId/question/:questId', function($quizId, $questId) use ($app, $twig, $db, $netId, $isAdmin) {
            echo $twig->render('quiz.html', array('id' => $id,'app' => $app, 'netId' => $netId, 'isAdmin' => $isAdmin));
        });

        //
        $app->post('/grade/quiz/:id', function($id) use ($app, $twig, $db, $netId, $isAdmin) {
            echo $twig->render('quiz.html', array('id' => $id,'app' => $app, 'netId' => $netId, 'isAdmin' => $isAdmin));
        });
});






// //route when professor is adding questions to quiz
// $app->get('/edit_quiz/:quizid', function($quizId) use ($app, $twig, $db) {
 
//     $sql = "SELECT title FROM quizzes WHERE id='$quizId'";
//     $quizTitle = $db->query($sql)->fetchColumn();

//     $dateSql = "SELECT due FROM quizzes WHERE id='$quizId'";
//     $quizDueDate = $db->query($dateSql)->fetchColumn();

    
//     if($quizTitle && $quizDueDate ) // were queries a success?
//     {
//         //split timestamp into date and 12 hour time
//         $splitTimeStamp = explode(" ", (string) $quizDueDate);
//         $date = $splitTimeStamp[0];
//         $time = date("g:i a", strtotime($splitTimeStamp[1]));

//         echo $twig->render('edit_quiz.html', array('quizId'=> $quizId, 'title' =>  $quizTitle, 'date' =>   $date, 'time' => $time));
       
//     } else {
//         $app->response->redirect('/404');
        
//     }
    
// });

// $app->post('/update_quiz', function() use ($app, $db) {
   
//     $req = $app->request();
//     $quizId = json_encode($req->post('quizId'));
//     $quizUpdatedTitle = json_encode($req->post('quiz_title'));
//     $quizUpdatedDate =  $req->post('date');
//     $quizUpdatedTime = $req->post('time');

//     $time_in_24_hour_format  =   date("H:i", strtotime($quizUpdatedTime));
//     $timeStamp = $quizUpdatedDate .= " " . $time_in_24_hour_format . ":00";

//     //quiz id check
//     $idSql = "SELECT id FROM quizzes WHERE id=$quizId";
//     $quizIdQuery = $db->query($idSql)->fetchColumn();

//     if($quizIdQuery) { // was query a success?
//         $sql = "UPDATE quizzes SET title=$quizUpdatedTitle, due='$timeStamp' WHERE id=$quizId";
//         $updateQuizQuery= $db->query($sql);
//         $response = 1;  

//     } else {
//         $response = 0;  
//     }
    
//     $res = new \Slim\Http\Response();
//     $res->setStatus(400);
//     $res->headers->set('Content-Type', 'application/json');
//     echo $response;
  
// });
?>