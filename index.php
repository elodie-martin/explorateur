<link rel="stylesheet" href="css/style.css" href="css/styles.css">
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

        $dir = './';
        $dh = opendir( $dir );
        
       
        $realpath = realpath($dir);

        $liste = array();

        while (( $file = readdir( $dh)) !== false ){
            if( is_dir( $file ) ){
                $liste[] = array("fichier" => $file, "isDir" => true, "extension" => "");
            }else{
                $liste[] = array("fichier" => $file, "isDir" => false, "extension" => pathinfo( $dir.$file,PATHINFO_EXTENSION));
            }
        }
        closedir( $dh );
        
        echo $twig->render('affichageFichiers.twig', array ( 'dir' => $dir, 'liste' => $liste, 'realpath' => $realpath  ));
       
        break;
    default:
        header('HTTP/1.0 404 not found');
        echo $twig->render('404.twig');
        break;
}
?>