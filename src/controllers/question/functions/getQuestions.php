<?php
    function getIndividualRightAnswer($db, $quizId, $questId){
            $answerQuery = "SELECT * FROM (SELECT @x:=@x + 1 AS row_index, quest_id, quiz_id, answer
            FROM quest_ans cross join
                 (select @x := 0) const
                 WHERE quiz_id = $quizId
            ORDER BY quiz_id) AS q1 WHERE q1.quest_id = $questId";
            $rightAnswer = $db->query($answerQuery);

            
        
        
            
            if($rightAnswer) // were queries a success?
            {
              
                return $rightAnswer;
               
            } else {
                $error = "Sorry, something went wrong!";
                return $error;
                
            }
        
    }

    function getIndividualWrongAnswer($db,$quizId, $questId){
        $probQuery = "SELECT * FROM (SELECT @x:=@x + 1 AS row_index, quest_id, quiz_id, problem
            FROM quest_prob cross join
                 (select @x := 0) const
                 WHERE quiz_id = $quizId
            ORDER BY quiz_id) AS q1 WHERE q1.quest_id = $questId";
            $wrongAnswer = $db->query($probQuery);

            if($wrongAnswer) // were queries a success?
            {

                return $wrongAnswer;
               
            } else {
                $error = "Sorry, something went wrong!";
                return $error;
                
            }
    }
    function getAllQuests($db, $quizId){
            $answerQuery = "SELECT @x:=@x + 1 AS row_index, quest_id, quiz_id, answer
            FROM quest_ans cross join
                (select @x := 0) const
            WHERE quiz_id = $quizId
            ORDER BY quest_id;";
            $questionAnswers = $db->query($answerQuery);
        
            $probQuery = "SELECT @x:=@x + 1 AS row_index, quest_id, quiz_id, problem
            FROM quest_prob cross join
                (select @x := 0) const
            WHERE quiz_id = $quizId
            ORDER BY quest_id;";
            $questionProblems = $db->query($probQuery);
        
        
            
            if($questionProblems && $questionAnswers ) // were queries a success?
            {
        
                $questions = array( 'rightAnswers' => $questionAnswers, 'wrongAnswers' =>  $questionProblems); 
                return $questionAnswers;
            
               
            } else {
                $error = $db->errorInfo();
                return $error;
                
            }
    }

    function getLastKnownQuestion($db, $quizId){
        $lastQuery = "SELECT quest_id FROM quest_ans WHERE quiz_id = $quizId ORDER BY quest_id DESC LIMIT 1";
        return $db->query($lastQuery)->fetchColumn();
    }
?>