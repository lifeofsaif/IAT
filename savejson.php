<?php
$fp = fopen('exampleToDelete.json', 'w');


fwrite($fp, json_encode($_POST));




fclose($fp);


?>