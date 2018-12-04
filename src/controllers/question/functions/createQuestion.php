<?php
function createQuestion($db, $quizId, $answer, $prob, $info){
    $answer =   filter_var($answer );
    $prob =   filter_var($prob);
    $answerQuery = $db->prepare("INSERT INTO quest_ans (quiz_id, answer) VALUES(:quizId, :answer)");

    $answerQuery->bindParam(':quizId', $quizId, PDO::PARAM_INT);
    $answerQuery->bindParam(':answer', $answer, PDO::PARAM_STR);

    $answerQuery->execute();

    $questId = $db->lastInsertId(); 

    $probQuery = $db->prepare("INSERT INTO quest_prob (quiz_id, quest_id, problem, additional_info) VALUES(:quizId, :questId, :prob, :info)");
    $probQuery->bindParam(':quizId', $quizId, PDO::PARAM_INT);
    $probQuery->bindParam(':questId', $questId , PDO::PARAM_INT);
    $probQuery->bindParam(':prob', $prob, PDO::PARAM_STR);
    $probQuery->bindParam(':info', $info, PDO::PARAM_STR);


    $probQuery->execute();

}

function saveQuestion($db, $quizId, $questId, $answer, $prob, $info){
    $answer =   filter_var($answer );
    $prob =   filter_var($prob);
    $questId = filter_var($questId);
    $answerQuery = $db->prepare("UPDATE quest_ans SET answer = :answer WHERE quiz_id= :quizId AND quest_id = :questId");

    $answerQuery->bindParam(':quizId', $quizId, PDO::PARAM_INT);
    $answerQuery->bindParam(':questId', $questId, PDO::PARAM_INT);
    $answerQuery->bindParam(':answer', $answer, PDO::PARAM_STR);

    $answerQuery->execute();

    $probQuery = $db->prepare("UPDATE quest_prob SET problem = :prob, additional_info = :info WHERE quiz_id= :quizId AND quest_id = :questId");
    $probQuery->bindParam(':quizId', $quizId, PDO::PARAM_INT);
    $probQuery->bindParam(':questId', $questId , PDO::PARAM_INT);
    $probQuery->bindParam(':prob', $prob, PDO::PARAM_STR);
    $probQuery->bindParam(':info', $info, PDO::PARAM_STR);



    $probQuery->execute();
    
    var_dump($answerQuery->errorInfo());
    var_dump($probQuery->errorInfo());
}
?>