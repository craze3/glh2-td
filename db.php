<?php 
$link = @mysql_connect('localhost', 'craze3_blastify', 'craze');
if (!$link) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db('craze3_blastify');
?>
