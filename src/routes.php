<?php 


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

?>