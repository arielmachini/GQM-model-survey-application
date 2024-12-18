<?php
require_once './controller/DBController.php';
require_once './lib/Constants.php';

$listOfAttributes = [];

$queryResult = DBController::getInstance()->query('SELECT Attribute_Source.AttributeID, Attribute_Source.SourceID, Attribute.Name, Attribute.Description FROM Attribute JOIN Attribute_Source ON Attribute.ID = Attribute_Source.AttributeID ORDER BY Attribute.Name');

while ($row = $queryResult->fetch_assoc()) {
    $listOfAttributes[] = $row;
}

DBController::destroyInstance();
?>

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title><?= Constants::APP_NAME ?> - <?= Constants::TITLE_USABILITY_ATTRIBUTES_PAGE ?></title>
    <link rel="author" href="<?= Constants::URL_ABOUT_PAGE ?>">

    <link rel="icon" href="<?= Constants::URL_FAVICON ?>" type="image/x-icon">
    <link rel="stylesheet" crossorigin="anonymous" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm">
    <link rel="stylesheet" href="resources/css/style.css">
</head>

<body>
    <?php include_once './lib/Menu.php'; ?>

    <div class="container mb-4 mt-4">
        <table class="table table-hover table-striped">
            <thead class="thead-dark">
                <tr>
                    <th class="text-center" scope="col">Name</th>
                    <th class="text-center" scope="col">Description</th>
                    <th class="text-center" scope="col">Extracted from</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listOfAttributes as $attribute) { ?>
                    <tr id="<?= $attribute['AttributeID'] ?>">
                        <th class="text-center" scope="row"><?= $attribute['Name'] ?></th>
                        <td class="metric-details text-justify"><?= $attribute['Description'] ?></td>
                        <td class="text-center">[<a href="<?= Constants::URL_SOURCES_PAGE ?>#<?= $attribute['SourceID'] ?>" target="_blank"><?= $attribute['SourceID'] ?></a>]</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Scripts required for the application to function. -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" crossorigin="anonymous" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" crossorigin="anonymous" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" crossorigin="anonymous" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"></script>

</body>

</html>