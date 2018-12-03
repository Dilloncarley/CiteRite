<?php
function updateQuiz($db, $id, $quizUpdatedTitle, $timeStamp ){
    //quiz id check
    $idSql = "SELECT quiz_id FROM quizzes WHERE quiz_id = $id";
    $quizIdQuery = $db->query($idSql)->fetchColumn();

    if($quizIdQuery) { // was query a success?
        $sql = "UPDATE quizzes SET title=$quizUpdatedTitle, due_date='$timeStamp' WHERE quiz_id=$id";
        $updateQuery = $db->query($sql);
        if($updateQuery){
            $messageArray =  array( 'error' => $db->errorInfo(),
                'status' => "1");
            $response = $messageArray ; 
        } else {
            $messageArray =  array( 'error' => $db->errorInfo(),
                'status' => "0");
            $response = $messageArray ; 
        }

        

        } else {
            $messageArray =  array( 'error' => $db->errorInfo(),
            'status' => "0");
            $response =  $messageArray;  
        }
                
        $res = new \Slim\Http\Response();
        $res->setStatus(400);
        $res->headers->set('Content-Type', 'application/json');
        echo json_encode($response);
        $db = null;
}
?>