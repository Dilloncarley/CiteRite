<?php
function quizzes($db){
    $query = "SELECT * FROM quizzes";
    $foundQuizzes = $db->query($query);

    if($foundQuizzes) return $foundQuizzes;
        else return $db->errorInfo();
}

function individuaQuiz($id, $db){
    
    $sql = "SELECT title FROM quizzes WHERE quiz_id=$id";
    $quizTitle = $db->query($sql)->fetchColumn();

    $dateSql = "SELECT due_date FROM quizzes WHERE quiz_id=$id";
    $quizDueDate = $db->query($dateSql)->fetchColumn();

    $quizPublishedSql = "SELECT published FROM quizzes WHERE quiz_id = $id";
    $quizPublished = $db->query($quizPublishedSql)->fetchColumn();

    
    if($quizTitle && $quizDueDate ) // were queries a success?
    {
        //split timestamp into date and 12 hour time
        $splitTimeStamp = explode(" ", (string) $quizDueDate);
        $date = $splitTimeStamp[0];
        $time = date("g:i a", strtotime($splitTimeStamp[1]));

        $quiz = array( 'title' => $quizTitle, 'date' =>  $date, 'time' => $time, 'published' => $quizPublished); 
        return $quiz;
       
    } else {
        $error = "Sorry, something went wrong!";
        return $error;
        
    }
}
?>