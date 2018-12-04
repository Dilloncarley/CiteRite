<?php
    require_once(realpath(dirname(__FILE__) . '/..'). '/functions/findUserQuestionLocation.php');
    function canUserSeeQuestion($app, $db, $quizId, $questId, $user_id){
        $userIsOnQuestion = findUserQuestionLocation($db, $quizId, $user_id);

            if ($questId !== $userIsOnQuestion && $userIsOnQuestion !== true ){
                $isUserDoneWithQuestion = isQuestionDone($db, $quizId, $questId, $user_id);
                if(!$isUserDoneWithQuestion){
                    return 0;
                } else {
                    return true;
                }
            } else {
                return true;
            }
        }
    
    function redirectIfQuestionSkipping($app, $db, $quizId, $questId, $user_id){
        $canUserBeHere = canUserSeeQuestion($app, $db, $quizId, $questId, $user_id);

        if($canUserBeHere !== true){
            $userShouldBeHere = findUserQuestionLocation($db, $quizId, $user_id);

            $app->response->redirect("/student-dashboard/take/quiz/".$quizId."/question". "/".  $userShouldBeHere);
        }
    }
?>