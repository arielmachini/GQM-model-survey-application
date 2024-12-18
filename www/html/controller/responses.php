<?php
require_once 'DBController.php';
require_once '../lib/Constants.php';

$dbResponseCount = DBController::getInstance()->query("SELECT COUNT(`ID`) AS `ResponseCount` FROM Response");
$dbResponseCount = $dbResponseCount->fetch_assoc()['ResponseCount'];

$csvFile = file('/var/www/GQM/responses.csv', FILE_SKIP_EMPTY_LINES);
$csvResponseCount = count($csvFile) - 1;

$responseCount = $dbResponseCount + $csvResponseCount;
?>

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Response count</title>

    <link rel="icon" href="<?= Constants::URL_FAVICON ?>" type="image/x-icon">
    <style>
        * {
            color: white;
            font-family: sans-serif;
            font-weight: bold;
        }

        body {
            background-color: black;
        }

        div.container {
            text-align: center;
        }

        div.container > p {
            margin: 0;
            padding-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <p style="font-size: xxx-large;"><?= $responseCount ?></p>
        <p>experts have responded to the survey so far.</p>
    </div>
</body>
