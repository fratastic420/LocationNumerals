<?php
require('locationNumerals.class.php');

$query = ''; $result = '';
if(isset($_POST))
{
    $query = isset($_POST['q']) ? $_POST['q'] : '';
    $method = isset($_POST['m']) ? $_POST['m'] : '';
    if($method != '' && $query != '')
    {
        $result = new LocationNumerals($query, $method);
    }
}


?>
<html>
    <head>
        <title>Location Numerals Fun FUN FUUUUUUN!</title>
    </head>
    <body>
        <form method="POST">
            
        </form>
        <br>
        <div class="result"><?= $result ?></div>
    </body>
</html>