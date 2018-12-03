<?php
    function publishQuiz($db, $id){
        $query = "UPDATE quizzes SET published = 1 WHERE quiz_id = $id";
        $db->query($query);
    }

?>