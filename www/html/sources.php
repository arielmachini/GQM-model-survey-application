<?php
require_once './controller/DBController.php';
require_once './lib/Constants.php';

$listOfSources = [];

$queryResult = DBController::getInstance()->query('SELECT Source.ID, Source.Description, Source.Type, COUNT(Metric_Source.MetricID) AS "MetricCount" FROM Source LEFT JOIN Metric_Source ON Source.ID = Metric_Source.SourceID GROUP BY Source.ID');

while ($row = $queryResult->fetch_assoc()) {
    $listOfSources[] = $row;
}

DBController::destroyInstance();
?>

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title><?= Constants::APP_NAME ?> - <?= Constants::TITLE_SOURCES_PAGE ?></title>
    <link rel="author" href="<?= Constants::URL_ABOUT_PAGE ?>">

    <link rel="icon" href="<?= Constants::URL_FAVICON ?>" type="image/x-icon">
    <link rel="stylesheet" crossorigin="anonymous" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm">
    <link rel="stylesheet" href="resources/css/style.css">
</head>

<body>
    <?php include_once './lib/Menu.php'; ?>

    <div class="container mb-4 mt-4">
        <table class="table table-hover table-striped" id="sources-table">
            <thead class="thead-dark">
                <tr>
                    <th class="text-center" scope="col">#</th>
                    <th class="text-center" scope="col">Source</th>
                    <th class="text-center" scope="col">Type</th>
                    <th class="text-center" scope="col" style="cursor: help;" title="The number of metrics associated with each source.">Metrics</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listOfSources as $source) { ?>
                    <tr id="<?= $source['ID'] ?>">
                        <th class="text-center" scope="row"><?= $source['ID'] ?></th>
                        <td class="metric-details text-justify"><?= $source['Description'] ?></td>
                        <td class="text-center"><?= $source['Type'] ?></td>
                        <td class="text-center" style="cursor: help;" title="This source has <?= $source['MetricCount'] ?> metric(s) associated to it."><?= $source['MetricCount'] ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Scripts required for the application to function. -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" crossorigin="anonymous" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" crossorigin="anonymous" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" crossorigin="anonymous" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"></script>
    <script type="text/javascript">
        const sourcesTable = document.getElementById("sources-table");
        const sources = sourcesTable.querySelectorAll('td[class="metric-details text-justify"]');

        /* Replace URLs in plain text with clickable links. */
        sources.forEach(function(source) {
            $(source).html($(source).html().replace(/((http:|https:)[^\s]+[\w])/g, '<a href="$1" target="_blank">$1</a>'));
        });
    </script>
</body>

</html>