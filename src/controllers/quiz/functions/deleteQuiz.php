<?php
    function deleteQuiz($db, $quizId){
        $query = "DELETE FROM quizzes WHERE quiz_id = $quizId";

        $db->query($query);
    }
?>