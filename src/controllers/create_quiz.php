<?php
$quizTitle = $app->request->post('quiz_title');
$quizDueDate= $app->request->post('quiz_due_date');
$quizDueTime= $app->request->post('quiz_due_time');

//convert quiz due date and time to timestamp
$time_in_24_hour_format  =   date("H:i", strtotime($quizDueTime));
$timeStamp = $quizDueDate .= " " . $time_in_24_hour_format . ":00";
$quiz_id = md5(microtime().rand());
$sql = "INSERT INTO quizzes ( title, due_date) VALUES ( '$quizTitle' ,'$timeStamp')";
$insertQuizQuery= $db->query($sql);
if($insertQuizQuery) // was query a success?
{
    //get id of the created quiz
    $quiz_id = $db->lastInsertId();
    $app->response->redirect($app->urlFor('home', array('error' => 'nope')));

} else {
    print_r($db->errorInfo());
    echo $twig->render('create_quiz.html', array('error' => "nope",'app' => $app));
}
$db = null;
?>