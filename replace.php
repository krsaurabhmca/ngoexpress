<?php
$files = ['receipt.php','membership.php','index.php','contact.php','gallery.php','donate.php','admin/layout_header.php'];
foreach($files as $f){
    if(file_exists($f)){
        $c=file_get_contents($f);
        $c=str_replace('APP_VERSION', 'CORE_VERSION', $c);
        file_put_contents($f,$c);
    }
}
echo "Replaced.";
?>
