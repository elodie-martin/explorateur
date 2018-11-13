<?php
require 'vendor/autoload.php';

// Routing
$page = 'index';
$listeDossier = array();
$listeFichier = array();

// Rendu du template
$loader = new Twig_Loader_Filesystem('twig');
$twig = new Twig_Environment($loader, [
'cache' => false, // _DIR_ . '/tmp'

]);

switch ($page) {
case 'index':


break;
default:
header('HTTP/1.0 404 not found');
echo $twig->render('404.twig');
break;
}
function dossiers($twig) { 
if ( (isset($_GET['dossier'])) && (!empty($_GET['dossier']))) {
$dir = './'.$_GET['dossier'];
$dh = opendir( $dir );
$realpath = realpath($dir);

}
else { 
$dir = './';
$dh = opendir( $dir );
$realpath = realpath($dir);
}
$listeDossier = array();
$listeFichier = array(); 

while (( $file = readdir( $dh)) !== false ){

if( is_dir( $file ) && $file != '.' && $file != '..' && $file != '.git' && $file != '.sass-cache' && $file != 'symfony' && $file != 'erusev' && $file != 'composer'){

$listeDossier[] = array("fichier" => $file, "isDir" => true, "extension" => "");
}else if ($file != '.' && $file != '..' && $file != '.git' && $file != '.sass-cache' && $file != '.gitignore' && $file != 'composer.json' && $file != 'composer.lock' && $file != 'symfony' && $file != 'erusev' && $file != 'composer'){
$listeFichier[] = array("fichier" => $file, "isDir" => false, "extension" => pathinfo( $dir.$file,PATHINFO_EXTENSION));
}
}
sort($listeDossier);
sort($listeFichier);
closedir( $dh );
echo $twig->render('affichageFichiers.twig', array ( 'dir' => $dir, 'listeDossier' => $listeDossier, 'listeFichier' => $listeFichier,'realpath' => $realpath, ));
};

dossiers($twig);










?>