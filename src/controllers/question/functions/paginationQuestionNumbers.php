<?php
    function getPaginationNumbers($db, $quizId, $questId, $userId){
        $query = 
        "SELECT @y:=@y + 1 AS row_index, quiz_id, quest_id, id,  isComplete
        FROM quest_stats cross join
        (select @y := 0) const
        WHERE quiz_id = $quizId and id = $userId 
        UNION 
        (SELECT row_index, quiz_id, quest_id, $questId as id, 0 as isComplete FROM (SELECT @x:=@x + 1 AS row_index, quiz_id, quest_id
        FROM quest_prob cross join
        (select @x := 0) const
        WHERE quiz_id = $quizId
        ORDER BY quiz_id) AS q1 WHERE q1.row_index = 3)";
    }
?>