<?php
require 'vendor/autoload.php';

// Routing
$page = 'index';

if (isset($_GET['p'])) {
    $page = $_GET['p'];
}

// Rendu du template
$loader = new Twig_Loader_Filesystem('twig');
$twig = new Twig_Environment($loader, [
    'cache' => false, // __DID__ . '/tmp'

]);

switch ($page) {
    case 'index':
        echo $twig->render('affichageFichiers.twig');
        break;
    case 'affichageExtension' :
        echo $twig->render('affichageExtension.twig'); 
    default:
        header('HTTP/1.0 404 not found');
        echo $twig->render('404.twig');
        break;
}

?>