<?php 
    function gradeQuestion($db, $quizId, $questId, $user_id){
        $gradeQuery = $db->prepare("INSERT INTO quest_stats (quiz_id, quest_id, id) VALUES (:quizId, :questId, :id)");

        $gradeQuery->bindParam(':quizId', $quizId, PDO::PARAM_INT);
        $gradeQuery->bindParam(':questId', $questId, PDO::PARAM_INT);
        $gradeQuery->bindParam(':id', $user_id, PDO::PARAM_INT);
        // $gradeQuery->bindParam(':attmpt_cnt', 0, PDO::PARAM_INT);

        
    
        $gradeQuery->execute();
        var_dump($gradeQuery->errorInfo());
    }
?>