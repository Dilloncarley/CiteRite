<?php
function countQuestions($db, $quizId){
    $countQuery = $db->prepare("SELECT COUNT(quiz_id) FROM quest_ans WHERE quiz_id = $quizId");
    $countQuery->bindParam(':quizId', $quizId, PDO::PARAM_INT);
    $countQuery->execute();

     $result = $countQuery->fetch(PDO::FETCH_ASSOC);

     return json_encode($result);

}
?>