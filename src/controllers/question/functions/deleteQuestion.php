<?php
function deleteQuestion($db, $id, $questId){
    $query = "DELETE FROM quest_ans WHERE quiz_id = $id and quest_id = $questId";
    $db->query($query);
}
 
?>