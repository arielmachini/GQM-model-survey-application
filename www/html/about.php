<?php require_once './lib/Constants.php'; ?>

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title><?= Constants::APP_NAME ?> - <?= Constants::TITLE_ABOUT_PAGE ?></title>
    <link rel="author" href="<?= Constants::URL_ABOUT_PAGE ?>">

    <link rel="icon" href="<?= Constants::URL_FAVICON ?>" type="image/x-icon">
    <link rel="stylesheet" crossorigin="anonymous" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm">
    <link rel="stylesheet" href="resources/css/style.css">
</head>

<body>
    <?php include_once './lib/Menu.php'; ?>

    <div class="container mb-4 mt-4">
        <h3>About me</h3>
        <div class="row mb-3">
            <div class="col-4">
                <img src="<?= Constants::URL_AUTHOR_PHOTO ?>" alt="A photo of Ariel Machini." class="img-fluid img-thumbnail">
            </div>
            <div class="col">
                <p class="text-justify" style="font-size: large;">
                    Hello, and thank you for your collaboration in the process of validating and improving our GQM model for web API usability assessment! My name is <b>Ariel Machini</b>, and I really enjoy programming. I am a Graduate in Systems, a researcher at <a href="https://www.unpa.edu.ar" target="_blank">Universidad Nacional de la Patagonia Austral</a> (<a href="https://www.uarg.unpa.edu.ar" target="_blank">Unidad Académica de Río Gallegos</a>), and a <a href="https://www.conicet.gov.ar/new_scp/detalle.php?keywords=&id=63664&datos_academicos=yes" target="_blank">PhD scholar at CONICET</a>. I am currently working on the improvement of web API usability.
                </p>
                <p class="mt-1" style="margin-bottom: .25rem;"><b>Links:</b></p>
                <ul>
                    <li><img class="icon" src="<?= Constants::URL_ICON_GOOGLE_SCHOLAR ?>"><a href="https://scholar.google.com/citations?user=pbkyWyMAAAAJ" target="_blank">Google Scholar</a></li>
                    <li><img class="icon" src="<?= Constants::URL_ICON_LINKEDIN ?>"><a href="https://www.linkedin.com/in/amachini" target="_blank">LinkedIn</a></li>
                    <li><img class="icon" src="<?= Constants::URL_ICON_ORCID ?>"><a href="https://orcid.org/0000-0002-2589-8182" target="_blank">ORCID</a></li>
                    <li><img class="icon" src="<?= Constants::URL_ICON_RESEARCHGATE ?>"><a href="https://www.researchgate.net/profile/Ariel-Machini-2" target="_blank">ResearchGate</a></li>
                </ul>
            </div>
            <div class="col-1">
                <a href="https://www.uarg.unpa.edu.ar" target="_blank"><img src="<?= Constants::URL_ICON_UARG ?>" class="img-fluid mb-4" style="display: block;"></a>
                <a href="https://www.conicet.gov.ar/new_scp/detalle.php?keywords=&id=63664&datos_academicos=yes" target="_blank"><img src="<?= Constants::URL_ICON_CONICET ?>" class="img-fluid mb-4" style="display: block;"></a>
                <a href="https://sites.google.com/uarg.unpa.edu.ar/gisp/staff#h.l3j3sownfvgm" target="_blank"><img src="<?= Constants::URL_ICON_GISP ?>" class="img-fluid" style="display: block;"></a>
            </div>
        </div>

        <h5>Goal</h5>
        <p class="text-justify">As previously mentioned, my current goal is <b>to help improve the usability of web APIs in general</b>, since it is known to be a critical factor for their adoption. The GQM model here proposed is meant for assessing certain aspects of a web API that can influence its usability. All metrics included by this model were extracted from <a href="<?= Constants::URL_SOURCES_PAGE?>" target="_blank">reliable sources</a>, such as academic research papers and blogs/guides written by web API experts.<br>At the moment, the priority is to validate the usefulness of the model's most important component: its metrics. But once enough feedback is collected (and the model adapted accordingly), I plan to build a tool to automate (as much as possible) the assessment process.</p>

        <h5>Contact</h5>
        <p>If you want, you can reach out to me through my <a href="https://www.linkedin.com/in/amachini" target="_blank">LinkedIn page</a> or by <a href="mailto:amachini@conicet.gov.ar">e-mailing me</a>.</p>
    </div>

    <!-- Scripts required for the application to function. -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" crossorigin="anonymous" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" crossorigin="anonymous" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" crossorigin="anonymous" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"></script>
</body>

</html>