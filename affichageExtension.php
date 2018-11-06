<?php
$d = dir("explorateur");
echo "Pointeur: " .$d->handle."<br>\n";
echo "Chemin: ".$d-> projets/explorateur/explorateur."<br\n";
while($entry =$d->read()){
    echo $entry."<br>\n";
}
$d->close();
?>