<?php require_once './lib/Constants.php'; ?>

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title><?= Constants::APP_NAME ?> - <?= Constants::TITLE_GQM_DIAGRAM_PAGE ?></title>
    <link rel="author" href="<?= Constants::URL_ABOUT_PAGE ?>">

    <link rel="icon" href="<?= Constants::URL_FAVICON ?>" type="image/x-icon">
    <link rel="stylesheet" crossorigin="anonymous" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm">
    <link rel="stylesheet" href="resources/css/style.css">
</head>

<body>
    <?php include_once './lib/Menu.php'; ?>

    <div class="container mb-4 mt-4">
        <h3>Hierarchical diagram</h3>
        <h5>Note</h5>
        <p>Next to each metric's name, there is a set of parentheses enclosing a letter. According to the authors of the GQM model [<a href="<?= Constants::URL_SOURCES_PAGE ?>#1" target="_blank">1</a>]:</p>
        <ul>
            <li><b>(O)bjective:</b> A metric is objective if its value depends only on the object that is being measured and not on the viewpoint from which it is taken.</li>
            <li><b>(S)ubjective:</b> A metric is subjective if its value depends on <b>both</b> the object that is being measured and the viewpoint from which it is taken.</li>
        </ul>

        <div class="mt-4 text-center">
            <a href="<?= Constants::URL_GQM_DIAGRAM ?>" target="_blank" title="Open this diagram in a new tab." style="cursor: zoom-in;">
                <img src="<?= Constants::URL_GQM_DIAGRAM ?>" alt="Hierarchical diagram of the GQM model proposed." class="img-fluid">
            </a>
        </div>
    </div>

    <!-- Scripts required for the application to function. -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" crossorigin="anonymous" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" crossorigin="anonymous" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" crossorigin="anonymous" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"></script>
</body>

</html>
