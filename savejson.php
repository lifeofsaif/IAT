<?php
$fp = fopen('savedSurveys.json', 'w');


fwrite($fp, json_encode($_POST));




fclose($fp);


?>