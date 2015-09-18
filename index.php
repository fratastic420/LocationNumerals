<?php
require('locationNumerals.class.php');

$query = ''; $result = ''; $errors = array();
if(isset($_POST))
{
    $query = isset($_POST['q']) ? $_POST['q'] : '';
    $method = isset($_POST['m']) ? $_POST['m'] : '';
    if($method != '' && $query != '')
    {
        $numerals = new LocationNumerals($query, $method);
        $errors = $numerals->errors;
        $result = $numerals->result;
    }
}


?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Location Arithmetic Fun FUN FUUUUUUN!</title>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="bootstrap.css">
    </head>
    <body>
        <div class="container">
        <h2>Lets do some Location Numeral Arithmetic!</h2>
        <?php if(count($errors) > 0){
                foreach($errors as $error)
                { ?>
                <div class="alert alert-danger alert-error text-center"><?= $error ?></div>
            <?php   }    
        }?>
        <form method="POST" class="form form-horizontal">
            <div class="control-group">
                <label class="control-label" for="q">Enter Your Input</label>
                <div class="controls">
                    <input name="q" id="q" class="required" placeholder="Enter Integer or String based upon selection below">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="m">Make a Choice of Method</label>
                <div class="controls">
                    <select id="m" name="m" class="required">
                        <option value="integerToAbbreviated">Convert Integer to Location Numeral</option>
                        <option value="locationToInteger">Convert Location Numeral to Integer</option>
                        <option value="locationToAbbreviated">Parse Location Numerals to shortened Abbreviation</option>
                    </select>
                </div>
            </div>
            <div class="clearfix"></div>
            <br>
            <div class="control-group">
                <button type="submit" class="btn btn-large btn-primary btn-block">Submit</button>
            </div>
        </form>
        <br>
        <?php if($result != false) { ?>
            <div class="alert alert-success text-center">Your result is <strong><?= $result ?></strong></div>
        <?php } ?>
        </div>
        <!-- Scripts -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>    
    </body>
</html>