<?php 
 require 'citation/citationClass.php';

//home page
$app->get('/', function () use ($app, $twig) {
    echo $twig->render('home.html', array('name' => 'Fabien'));

});

//login page
$app->get('/login', function() use ($app, $twig) {
    echo $twig->render('login.html', array('name' => 'Fabien'));
});

//about page
$app->get('/about', function() use ($app, $twig)  {
    echo $twig->render('about.html', array('name' => 'Fabien'));
});

//professor dashboard
$app->get('/professor-dashboard', function ($request, $response) {
    return $this->view->render($response, 'professor-dashboard.html');
});

//student dashboard
$app->get('/student-dashboard', function ($request, $response) {
    return $this->view->render($response, 'student-dashboard.html');
});

//specific quiz page
$app->get('/quiz/:id', function($id) use ($app, $twig, $db) {
    $sql = "SELECT * FROM users";
    $users = $db->query($sql);
    echo $twig->render('quiz.html', array('id' => $id, 'users' => $users));
    $db = null;
});




//generating quiz
$app->get('/create_quiz', function() use ($app, $twig, $db) {
    // $sql = "SELECT * FROM users";
    // $users = $db->query($sql);
    echo $twig->render('create_quiz.html', array());
    // $db = null;
});
$app->post('/create_quiz', function() use ($app, $twig, $db) {

    $citationValue = $app->request->post('citation');     //request param
    $citationObject = new Citation($citationValue, 3);
    $citationAuthors = $citationObject->getAuthorNames();
  
    
    echo $twig->render('create_quiz.html', array( 'matches' => var_dump($citationAuthors), 'authors' => $app->request->post('citation')));
});
// $app->put('/quiz/:id', function($id) use ($app, $twig, $db) {
   
// });

?>