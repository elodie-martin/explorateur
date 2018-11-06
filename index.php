<?php
require_once 'vendor/autoload.php';

// Routing
$page = 'index';
if (isset($_GET['p'])) {
    $page = $_GET['p'];
}

// Rendu du template
$loader = new Twig_Loader_Filesystem(__DIR__ . '/explorateur');
$twig = new Twig_Environment($loader, [
    'cache' => false, // __DID__ . '/tmp'

]);

switch ($page) {
    case 'secondary':
        echo $twig->render('twig/secondary.twig');
        break;
    case 'index':
        echo $twig->render('twig/index.html.twig');
        break;
    case 'affichageFichiers':
        echo $twig->render('twig/affichageFichiers.twig');
        break;
    default:
        header('HTTP/1.0 404 not found');
        echo $twig->render('twig/404.twig');
        break;
}

?>