<?php
 require_once('functions/updateQuiz.php');
 $req = $app->request();
 $quizId = json_encode($req->post('quizId'));
 $quizUpdatedTitle = json_encode($req->post('quiz_title'));
 $quizUpdatedDate =  $req->post('date');
 $quizUpdatedTime = $req->post('time');

 $time_in_24_hour_format  =   date("H:i", strtotime($quizUpdatedTime));
 $timeStamp = $quizUpdatedDate .= " " . $time_in_24_hour_format . ":00";

updateQuiz($db, $quizId, $quizUpdatedTitle, $timeStamp);
?>