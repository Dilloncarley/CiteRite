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
     echo $app->render('casSystem.php', array('app' => $app, 'db' => $db));
   
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
            require_once('controllers/quiz/list-quizzes.php');
            echo $twig->render('professor-dashboard.html', array('app' => $app, 'netId' => $netId, 'isAdmin' => $isAdmin, 'quizzes' => $quizzes));
        });

        // Quiz group
        $app->group('/quiz', function () use ($app, $db, $twig, $user_id, $netId, $isAdmin) {

            // quiz form
            $app->get('/create', function () use ($app, $db, $twig, $user_id, $netId, $isAdmin){
                echo $twig->render('quiz/create_quiz.html', array('app' => $app, 'netId' => $netId, 'isAdmin' => $isAdmin));
            });

            // create quiz
            $app->post('/create', function () use ($app, $db, $twig, $user_id, $netId, $isAdmin) {
                require_once('controllers/quiz/create_quiz.php');
            });

            // view quiz
            $app->get('/view/:id', function ($id) use ($app, $db, $twig, $user_id, $netId, $isAdmin){
                echo $twig->render('quiz.html', array('app' => $app, 'netId' => $netId));
            });

            // Delete quiz with ID
            $app->post('/delete/:id', function ($id) use ($app, $db, $twig, $user_id, $netId, $isAdmin) {
                require_once('controllers/quiz/functions/deleteQuiz.php');
                deleteQuiz($db, $id);
                $app->response->redirect("/professor-dashboard");


            });

            // Update quiz
            $app->post('/update_quiz', function() use ($app, $db) {
                require_once('controllers/quiz/update-quiz.php');
               

            });

            ///QUESTIONS

            //list all questions in quiz
            $app->get('/:id/list/questions', function ($id) use ($app, $db, $twig, $user_id, $netId, $isAdmin) {
                require_once('controllers/quiz/list-quiz.php');
                require_once('controllers/question/functions/getQuestions.php');
                $questions = getAllQuests($db, $id);
            
               
                echo $twig->render('question/list_questions.html', array('app' => $app, 'netId' => $netId, 'isAdmin' => $isAdmin, 'quiz' => $quiz, 'id' => $id, 'questions' => $questions));

            });
             // create question form
             $app->get('/:id/question/create', function ($id) use ($app, $db, $twig, $user_id, $netId, $isAdmin){
                 
                require_once('controllers/quiz/list-quiz.php');
                require_once('controllers/question/functions/countQuestions.php');
                require_once('controllers/question/functions/getQuestions.php');

                $questionNumber = countQuestions($db, $id);
                $numberOfQuestions = json_decode($questionNumber, true);
                
                $numberOfQuestions = $numberOfQuestions['COUNT(quiz_id)'] + 1; //for new question
                $questionsInQuiz = getAllQuests($db, $id);
             
               

                echo $twig->render('question/create_question.html', array('app' => $app, 'netId' => $netId, 'isAdmin' => $isAdmin, 'quiz' => $quiz, 'id' => $id, 'questions'=> $questionsInQuiz, 'number' => $numberOfQuestions));
            });


            // create question
            $app->post('/:id/question/create', function ($id) use ($app, $db, $twig, $user_id, $netId, $isAdmin) {
                require_once('controllers/quiz/list-quiz.php');
                require_once('controllers/question/create-question.php');


            });



            // edit specific question
            $app->get('/question/:id/update', function ($questId) use ($app, $db, $twig, $user_id, $netId, $isAdmin) {
                require_once('controllers/question/functions/countQuestions.php');
                require_once('controllers/question/functions/getQuestions.php');
                require_once('controllers/question/functions/getQuizId.php');
                $quizId = getQuizId($db, $questId);
                $rightAnswer = getIndividualRightAnswer($db,$quizId, $questId);
                $wrongAnswer = getIndividualWrongAnswer($db,$quizId, $questId);
         
             
                  
                foreach ($rightAnswer as $right) {
                    foreach ($wrongAnswer as $wrong) {
                       $wrongAnswer = $wrong['problem'];
                    }
                    $rightAnswer = $right['answer'];
                    $id = $right['quiz_id'];
                    $row = $right['row_index'];
                }
                require_once('controllers/quiz/list-quiz.php');

                $questionNumber = countQuestions($db, $id);
                $numberOfQuestions = json_decode($questionNumber, true);
                
                $numberOfQuestions = $numberOfQuestions['COUNT(quiz_id)'] + 1; //for new question

                $questionsInQuiz = getAllQuests($db, $id);

                echo $twig->render('question/create_question.html', array('app' => $app, 'netId' => $netId, 'isAdmin' => $isAdmin, 'quiz' => $quiz, 'id'=> $id, 'questId'=> $questId, 'questions'=> $questionsInQuiz, 'wrongAnswer' => $wrongAnswer, 'rightAnswer' => $rightAnswer, 'questionNumber' => $row, 'number' => $numberOfQuestions ));

                
            });
            // Update specific question with ID
            $app->post('/question/:id/update', function ($id) use ($app, $db, $twig, $user_id, $netId, $isAdmin) {

            });

            // Delete question with ID
            $app->post('/question/:id/delete', function () use ($app, $db, $twig, $user_id, $netId, $isAdmin) {

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


?>