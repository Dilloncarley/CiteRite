<?php
    // require_once('controllers/quiz/functions/getQuiz.php');
    // require_once('controllers/question/functions/getQuestions.php');
    // require_once('controllers/question/functions/countQuestions.php');

    function getNextQuestionId($db, $quizId, $nextQuestionRow){
        $query ="SELECT quest_id FROM (SELECT @x:=@x + 1 AS row_index, quest_id
        FROM quest_prob cross join
            (select @x := 0) const
        WHERE quiz_id = $quizId
        ORDER BY quest_id) AS q1 WHERE q1.row_index = $nextQuestionRow;";
        $nextQuestionId = $db->query($query)->fetchColumn();

        return $nextQuestionId;
    }
    function isQuestionDone($db, $quizId, $questId, $userId){
        $query = "SELECT isComplete FROM quest_stats WHERE quiz_id = $quizId and id=$userId and quest_id = $questId";
        $isDone = $db->query($query)->fetchColumn();
        return $isDone;
    }
    //find the next question if latest question stat from table is done
    function findNextQuestionNumber($db, $quizId, $questId, $userId){
        
            $query = $db->prepare("SELECT row_index, quest_id FROM
             (SELECT @x:=@x + 1 AS row_index, quest_id
              FROM quest_prob cross join
              (select @x := 0) const
             WHERE quiz_id = $quizId
            ORDER BY quest_id) AS q1 WHERE q1.quest_id = $questId");

            $query->execute(); 

            $totalCountOfQuestions = countQuestions($db, $quizId);
            $numberOfQuestions = json_decode($totalCountOfQuestions, true);
                
            $numberOfQuestions = $numberOfQuestions['COUNT(quiz_id)'];

            $row = $query->fetch();
            if($numberOfQuestions !== $row['row_index'] ){
                $nextQuestionRow = $row['row_index'] + 1;
                $nextQuestion = getNextQuestionId($db, $quizId, $nextQuestionRow);
                return    $nextQuestion;
            } else {
                //user has completed quiz
                return true;
            }
            
            //we found the question they were on with the question number in the quiz increment to get next one
            //if quiz isn't complete
            return $nextQuestion; 
    }
    function findUserQuestionLocation($db, $quizId, $userId){
        $query = "SELECT quest_id FROM quest_stats WHERE quiz_id = $quizId and id=$userId ORDER BY quest_id DESC LIMIT 1;";
        $foundQuestion = $db->query($query)->fetchColumn();

        if($foundQuestion) {
            
            //if the question isn't done
            if(!isQuestionDone($db, $quizId, $foundQuestion, $userId)){
                $userIsOnQuestion = $foundQuestion;
                return $userIsOnQuestion;
            } else {
                return findNextQuestionNumber($db, $quizId, $foundQuestion, $userId);
            }

        } else {
            //we know user hasn't started quiz yet so they are on 1
            $userIsOnQuestion = getNextQuestionId($db, $quizId, 1);
            return $userIsOnQuestion;
        }
    }
?>