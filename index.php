<?php
require 'vendor/autoload.php';

// Routing
$page = 'index';



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

        $listeDossier = array();
        $listeFichier = array(); 

        while (( $file = readdir( $dh)) !== false ){
           
            if( is_dir( $file ) && $file != '.' && $file != '..' && $file != '.git' && $file != '.sass-cache'){
            
                $listeDossier[] = array("fichier" => $file, "isDir" => true, "extension" => "");
            }else if ($file != '.' && $file != '..' && $file != '.git' && $file != '.sass-cache' && $file != '.gitignore' && $file != 'composer.json' && $file != 'composer.lock'){
                $listeFichier[] = array("fichier" => $file, "isDir" => false, "extension" => pathinfo( $dir.$file,PATHINFO_EXTENSION));
            }
        }
        closedir( $dh );

        sort($listeDossier);
        sort($listeFichier);
        
        echo $twig->render('affichageFichiers.twig', array ( 'dir' => $dir, 'listeDossier' => $listeDossier, 'listeFichier' => $listeFichier,'realpath' => $realpath,   ));
       
        break;
    default:
        header('HTTP/1.0 404 not found');
        echo $twig->render('404.twig');
        break;
}


?>