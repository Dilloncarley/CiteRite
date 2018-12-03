<?php
function getQuizId($db, $questId){
    $query = "SELECT quiz_id FROM quest_ans WHERE quest_id = $questId";
    return $db->query($query)->fetchColumn();

}
?>