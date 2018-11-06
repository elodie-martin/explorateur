

<?php

/* racine */
$base = "/home/stagiaire/projets/explorateur";

/* infos à extraire */
function addScheme($entry,$base,$type) {
  $tab['name'] = $entry;
  $tab['type'] = filetype($base."/home/stagiaire/projets/explorateur".$entry);
  $tab['date'] = filemtime($base."/home/stagiaire/projets/explorateur".$entry);
  $tab['size'] = filesize($base."/home/stagiaire/projets/explorateur".$entry);
  $tab['perms'] = fileperms($base."/home/stagiaire/projets/explorateur".$entry);
  $tab['access'] = fileatime($base."/home/stagiaire/projets/explorateur".$entry);
  $t = explode("/home/stagiaire/projets/explorateur", $entry);
  $tab['ext'] = $t[count($t)-1];
  return $tab;
}

/* liste des dossiers */
function list_dir($base, $cur, $level=0) {
  global $PHP_SELF, $base, $order, $asc;
  if ($dir = opendir($base)) {
    $tab = array();
    while($entry = readdir($dir)) {
      if(is_dir($base."/home/stagiaire/projets/explorateur".$entry) && !in_array($entry, array(".",".."))) {
        $tab[] = addScheme($entry, $base, 'dir');
      }
    }
    /* tri */
    usort($tab,"cmp_name");
    foreach($tab as $elem) {
      $entry = $elem['name'];
      /* chemin relatif à la racine */
      $file = $base."/home/stagiaire/projets/explorateur".$entry;
     /* marge gauche */
      for($i=1; $i<=(4*$level); $i++) {
        echo "&nbsp;";
      }
      /* l'entrée est-elle le dossier courant */
      if($file == $cur) {
        echo "<img src=\"dir-open.gif\" />&nbsp;$entry<br />\n";
      } else {
        echo "<img src=\"dir-close.gif\" />&nbsp;<a href=\"$PHP_SELF?dir=". rawurlencode($file) ."&order=$order&asc=$asc\">$entry</a><br />\n";
      }
      /* l'entrée est-elle dans la branche dont le dossier courant est la feuille */
      if(ereg($file."/home/stagiaire/projets/explorateur",$cur."/home/stagiaire/projets/explorateur")) {
        list_dir($file, $cur, $level+1);
      }
    }
    closedir($dir);
  }
}

/* liste des fichiers */
function list_file($cur) {
  global $PHP_SELF, $order, $asc, $order0;
  if ($dir = opendir($cur)) {
    /* tableaux */
    $tab_dir = array();
    $tab_file = array();
    /* extraction */
    while($file = readdir($dir)) {
      if(is_dir($cur."/home/stagiaire/projets/explorateur".$file)) {
        if(!in_array($file, array(".",".."))) {
          $tab_dir[] = addScheme($file, $cur, 'dir');
        }
      } else {
          $tab_file[] = addScheme($file, $cur, 'file');
      }
    }
    /* tri */
    usort($tab_dir,"cmp_".$order);
    usort($tab_file,"cmp_".$order);
    /* affichage */
    echo "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\">";
    echo "<tr style=\"font-size:8pt;font-family:arial;\">
    <th>".(($order=='name')?(($asc=='a')?'/\\ ':'\\/ '):'')."<a href=\"$PHP_SELF?dir=".rawurlencode($cur). "&order=name&asc=$asc&order0=$order\">Nom</a></th><td>&nbsp;</td>
    <th>".(($order=='size')?(($asc=='a')?'/\\ ':'\\/ '):'')."<a href=\"$PHP_SELF?dir=".rawurlencode($cur). "&order=size&asc=$asc&order0=$order\">Taille</a></th><td>&nbsp;</td>
    <th>".(($order=='date')?(($asc=='a')?'/\\ ':'\\/ '):'')."<a href=\"$PHP_SELF?dir=".rawurlencode($cur). "&order=date&asc=$asc&order0=$order\">Dernière modification</a></th><td>&nbsp;</td>
    <th>".(($order=='type')?(($asc=='a')?'/\\ ':'\\/ '):'')."<a href=\"$PHP_SELF?dir=".rawurlencode($cur). "&order=type&asc=$asc&order0=$order\">Type</a></th><td>&nbsp;</td>
    <th>".(($order=='ext')?(($asc=='a')?'/\\ ':'\\/ '):'')."<a href=\"$PHP_SELF?dir=".rawurlencode($cur). "&order=ext&asc=$asc&order0=$order\">Extention</a></th><td>&nbsp;</td>
    <th>".(($order=='perms')?(($asc=='a')?'/\\ ':'\\/ '):'')."<a href=\"$PHP_SELF?dir=".rawurlencode($cur). "&order=perms&asc=$asc&order0=$order\">Permissions</a></th><td>&nbsp;</td>
    <th>".(($order=='access')?(($asc=='a')?'/\\ ':'\\/ '):'')."<a href=\"$PHP_SELF?dir=".rawurlencode($cur). "&order=access&asc=$asc&order0=$order\">Dernier accès</a></th></tr>";
    foreach($tab_dir as $elem) {
      echo "<tr><td><img src=\"dir-close.gif\" />&nbsp;".$elem['name']."</td><td>&nbsp;</td>
      <td>&nbsp;</td><td>&nbsp;</td>
      <td>".date("d/m/Y H:i:s", $elem['date'])."</td><td>&nbsp;</td>
      <td>".assocType($elem['type'])."</td><td>&nbsp;</td>
      <td>&nbsp;</td><td>&nbsp;</td>
      <td>".$elem['perms']."</td><td>&nbsp;</td>
      <td>".date("d/m/Y", $elem['access'])."</td></tr>\n";
    }
    foreach($tab_file as $elem) {
      echo "<tr><td><img src=\"file-none.gif\" />&nbsp;".$elem['name']."</td><td>&nbsp;</td>
      <td align=\"right\">".formatSize($elem['size'])."</td><td>&nbsp;</td>
      <td>".date("d/m/Y H:i:s", $elem['date'])."</td><td>&nbsp;</td>
      <td>".assocType($elem['type'])."</td><td>&nbsp;</td>
      <td>".assocExt($elem['ext'])."</td><td>&nbsp;</td>
      <td>".$elem['perms']."</td><td>&nbsp;</td>
      <td>".date("d/m/Y", $elem['access'])."</td></tr>\n";
    }
    echo "</table>";
    closedir($dir);
  }
}

/* formatage de la taille */
function formatSize($s) {
  /* unités */
  $u = array('octets','Ko','Mo','Go','To');
  /* compteur de passages dans la boucle */
  $i = 0;
  /* nombre à afficher */
  $m = 0;
  /* division par 1024 */
  while($s >= 1) {
    $m = $s;
    $s /= 1024;
    $i++;
  }
  if(!$i) $i=1;
  $d = explode(".",$m);
  /* s'il y a des décimales */
  if($d[0] != $m) {
    $m = number_format($m, 2, ",", " ");
  }
  return $m." ".$u[$i-1];
}

/* formatage du type */
function assocType($type) {
  /* tableau de conversion */
  $t = array(
    'fifo' => "file",
    'char' => "fichier spécial en mode caractère",
    'dir' => "dossier",
    'block' => "fichier spécial en mode bloc",
    'link' => "lien symbolique",
    'file' => "fichier",
    'unknown' => "inconnu"
  );
  return $t[$type];
}

/* description de l'extention */
function assocExt($ext) {
  $e = array(
    '' => "inconnu",
    'doc' => "Microsoft Word",
    'xls' => "Microsoft Excel",
    'ppt' => "Microsoft Power Point",
    'pdf' => "Adobe Acrobat",
    'zip' => "Archive WinZip",
    'txt' => "Document texte",
    'gif' => "Image GIF",
    'jpg' => "Image JPEG",
    'png' => "Image PNG",
    'php' => "Script PHP",
    'php3' => "Script PHP",
    'htm' => "Page web",
    'html' => "Page web",
    'css' => "Feuille de style",
    'js' => "JavaScript"
  );
  if(in_array($ext, array_keys($e))) {
    return $e[$ext];
  } else {
    return $e[''];
  }
}

function cmp_name($a,$b) {
    global $asc;
    if ($a['name'] == $b['name']) return 0;
    if($asc == 'a') {
        return ($a['name'] < $b['name']) ? -1 : 1;
    } else {
        return ($a['name'] > $b['name']) ? -1 : 1;
    }
}
function cmp_size($a,$b) {
    global $asc;
    if ($a['size'] == $b['size']) return cmp_name($a,$b);
    if($asc == 'a') {
        return ($a['size'] < $b['size']) ? -1 : 1;
    } else {
        return ($a['size'] > $b['size']) ? -1 : 1;
    }
}
function cmp_date($a,$b) {
    global $asc;
    if ($a['date'] == $b['date']) return cmp_name($a,$b);
    if($asc == 'a') {
        return ($a['date'] < $b['date']) ? -1 : 1;
    } else {
        return ($a['date'] > $b['date']) ? -1 : 1;
    }
}
function cmp_access($a,$b) {
    global $asc;
    if ($a['access'] == $b['access']) return cmp_name($a,$b);
    if($asc == 'a') {
        return ($a['access'] < $b['access']) ? -1 : 1;
    } else {
        return ($a['access'] > $b['access']) ? -1 : 1;
    }
}
function cmp_perms($a,$b) {
    global $asc;
    if ($a['perms'] == $b['perms']) return cmp_name($a,$b);
    if($asc == 'a') {
        return ($a['perms'] < $b['perms']) ? -1 : 1;
    } else {
        return ($a['perms'] > $b['perms']) ? -1 : 1;
    }
}
function cmp_type($a,$b) {
    global $asc;
    if ($a['type'] == $b['type']) return cmp_name($a,$b);
    if($asc == 'a') {
        return ($a['type'] < $b['type']) ? -1 : 1;
    } else {
        return ($a['type'] > $b['type']) ? -1 : 1;
    }
}
function cmp_ext($a,$b) {
    global $asc;
    if ($a['ext'] == $b['ext']) return cmp_name($a,$b);
    if($asc == 'a') {
        return ($a['ext'] < $b['ext']) ? -1 : 1;
    } else {
        return ($a['ext'] > $b['ext']) ? -1 : 1;
    }
}
?>

<table border ="1" cellspacing="0" cellpadding="10" bordercolor="gray">
<tr valign="top"><td>

<!-- liste des répertoires
et des sous-répertoires -->
<?php 
if(!in_array($order, array('name','date','size','perms','ext','access','type'))) {
  $order = 'name';
}
if(($order == $order0) && ($asc != 'b')) {
  $asc = 'b';
} else {
  $asc = 'a';
}
/* lien sur la racine */
if(!$dir) {
  echo "<img src=\"dir-open.gif\" />&nbsp;/<br />\n";
} else {
  echo "<img src=\"dir-close.gif\" />&nbsp;<a href=\"$PHP_SELF\">/</a><br />\n";
}
list_dir($base, rawurldecode($dir), 1); 
?>

</td><td>

<!-- liste des fichiers -->
<?php
/* répertoire initial à lister */
if(!$dir) {
  $dir = $base;
} 
list_file(rawurldecode($dir)); 
?>

