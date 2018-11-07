<?php

$dir = "./";
//  si le dossier pointe existe
if (is_dir($dir)) {
	
   // si il contient quelque chose
   if ($dh = opendir($dir)) {
	
       // boucler tant que quelque chose est trouve
       while (($file = readdir($dh)) !== false) {
            if(is_dir($file)) {
				echo '<a href='.$file.'><img src="media/scssfolder.svg"></a>';
		   } else {
               echo '<img src="media/html.svg">';
           }
           // affiche le nom et le type	
		   echo " $file : type : " . filetype($dir . $file ) . "<br />\n";
       }
       // on ferme la connection
       closedir($dh);
   }
}

?>