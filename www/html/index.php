<?php
require_once './controller/DBController.php';
require_once './lib/Constants.php';

function printMetricSources($metricId) {
    $sourcesString = ' <span class="metric-sources">[';

    foreach ($GLOBALS['listOfSources'] as $sourceDetails) {
        if ($sourceDetails['MetricID'] == $metricId) $sourcesString = $sourcesString . '<a href="' . Constants::URL_SOURCES_PAGE . '#' . $sourceDetails['SourceID'] . '" target="_blank" title="' . $sourceDetails['Description'] . '">' . $sourceDetails['SourceID'] . "</a>, ";
    }

    if ($sourcesString != ' <span class="metric-sources">[') {
        $sourcesString = substr($sourcesString, 0, -2);
        $sourcesString = $sourcesString . ']</span>';

        return $sourcesString;
    } else {
        return; // If the metric has no sources associated to it, an empty string is returned.
    }
}

$queryResult = DBController::getInstance()->query('SELECT * FROM Metric');
$listOfMetrics = [];
$listOfSources = [];

while ($row = $queryResult->fetch_assoc()) {
    $listOfMetrics[] = $row;
}

$queryResult = DBController::getInstance()->query('SELECT Metric_Source.MetricID, Metric_Source.SourceID, Source.Description FROM Metric_Source INNER JOIN Source ON Metric_Source.SourceID = Source.ID');

while ($row = $queryResult->fetch_assoc()) {
    $listOfSources[] = $row;
}

const DESIRED_NUMBER_OF_PAGES = 3;

$totalNumberOfMetrics = count($listOfMetrics);
$numberOfMetricsPerPage = ceil($totalNumberOfMetrics / DESIRED_NUMBER_OF_PAGES);
$numberOfFormPages = ceil($totalNumberOfMetrics / $numberOfMetricsPerPage);

DBController::destroyInstance();
?>

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title><?= Constants::APP_NAME ?> - <?= Constants::TITLE_SURVEY_PAGE ?></title>
    <link rel="author" href="<?= Constants::URL_ABOUT_PAGE ?>">

    <link rel="icon" href="<?= Constants::URL_FAVICON ?>" type="image/x-icon">
    <link rel="stylesheet" crossorigin="anonymous" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm">
    <link rel="stylesheet" href="resources/css/style.css">
</head>

<body>
    <?php include_once './lib/Menu.php'; ?>

    <div class="container mb-4 mt-4">
        <form action="<?= Constants::URL_SURVEY_SUBMIT_PAGE ?>" data-persist="garlic" id="survey" method="POST" novalidate>
            <?php for ($pageNo = 1; $pageNo <= $numberOfFormPages; $pageNo++) { ?>
                <div id="pageNo<?= $pageNo ?>" class="page<?= $pageNo == 1 ? " active" : "" ?>">
                    <div class="container">
                        <?php
                        $firstMetric = ($pageNo - 1) * $numberOfMetricsPerPage;
                        $lastMetric = min($firstMetric + $numberOfMetricsPerPage, $totalNumberOfMetrics);

                        for ($i = $firstMetric; $i < $lastMetric; $i++) {
                            $metric = $listOfMetrics[$i];
                            $pageClass = ($pageNo != $numberOfFormPages && $i == ($lastMetric - 1)) ? 'row mb-2' : 'row mb-2 metric-separator';
                            $sourcesForMetric = array_column($listOfSources, $i);
                        ?>
                            <div class="<?= $pageClass ?>">
                                <div class="col">
                                    <h5>#<?= $metric['ID'] ?> <b><?= $metric['Name'] ?></b><?= printMetricSources($metric['ID']) ?> <sup><?= $metric['Type'] ?></sup></h5>
                                    <div class="metric-details">
                                        <p><?= $metric['Description'] ?></p>
                                        <p style="margin-bottom: .25rem;"><b>Possible values:</b></p>
                                        <?php
                                        if ($metric['ValueList'] != '{"0": "", "1": "", "0.5": ""}') {
                                            $possibleValues = json_decode($metric['ValueList']);
                                        ?>
                                            <ul>
                                                <?php
                                                foreach ($possibleValues as $value => $conditionForValue) {
                                                ?>
                                                    <li><b><?= $value ?>:</b> <?= $conditionForValue ?></li>
                                                <?php } ?>
                                            </ul>
                                        <?php } else { ?>
                                            <p><i>Still to be defined.</i></p>
                                        <?php } ?>
                                        <p><b>Multiplier (weight):</b> <?= $metric['Weight'] ?></p>
                                        <?php if (!empty($metric['NotApplicableIf'])) { ?>
                                            <p class="metric-not-applicable-if"><b>DOES NOT APPLY:</b> <?= $metric['NotApplicableIf'] ?>
                                            <?php } ?>
                                    </div>
                                </div>
                                <div class="col">
                                    <p><b>Should this metric be included in the model?:</b></p>
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" id="metricID<?= $metric['ID'] ?>Yes" name="metricID<?= $metric['ID'] ?>Radios" style="cursor: pointer;" value="Yes" required>
                                        <label class="form-check-label" for="metricID<?= $metric['ID'] ?>Yes" style="cursor: pointer;">Yes</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" id="metricID<?= $metric['ID'] ?>NotSure" name="metricID<?= $metric['ID'] ?>Radios" style="cursor: pointer;" value="Not sure" required>
                                        <label class="form-check-label" for="metricID<?= $metric['ID'] ?>NotSure" style="cursor: pointer;">Not sure</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" id="metricID<?= $metric['ID'] ?>No" name="metricID<?= $metric['ID'] ?>Radios" style="cursor: pointer;" value="No" required>
                                        <label class="form-check-label" for="metricID<?= $metric['ID'] ?>No" style="cursor: pointer;">No</label>
                                    </div>

                                    <div class="form-group mt-3">
                                        <label for="metricID<?= $metric['ID'] ?>Comments">Additional comments (optional):</label>
                                        <textarea class="form-control" id="metricID<?= $metric['ID'] ?>Comments" name="metricID<?= $metric['ID'] ?>Comments" rows="2"></textarea>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>

                    <?php if ($pageNo == $numberOfFormPages) { // Check if the current page is the last. ?>
                    	<div class="form-group mb-3">
                            <label for="expertiseLevel"><b>Level of expertise:</b></label>
                            <p>Please indicate your level of expertise in the area of web APIs.</p>
                            <select aria-describedby="expertiseLevelHelp" class="form-control" id="expertiseLevel" name="expertiseLevel" required>
                            	<option value="Low">Low</option>
                            	<option value="Medium">Medium</option>
                            	<option value="High">High</option>
                            </select>
                            <small id="expertiseLevelHelp" class="form-text text-muted">The <b>level of expertise</b> refers to the level of knowledge and skill you possess in this particular area, acquired through practice and study.</small>
                        </div>
                        <div class="form-group mb-3">
                            <label for="email"><b>E-mail address:</b></label>
                            <input type="email" aria-describedby="emailHelp" class="form-control" id="email" name="email" required>
                            <small id="emailHelp" class="form-text text-muted">We will not share this information with anyone else.</small>
                        </div>
                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="interviewParticipation" name="interviewParticipation">
                            <label class="form-check-label" for="interviewParticipation">I would like to participate in a future interview to further improve the model.</label>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>

            <div class="form-navigation-buttons">
                <button type="button" class="btn btn-secondary" id="prevBtn" onclick="changePage(-1)">Previous</button>
                <button type="button" class="btn btn-secondary" id="nextBtn" onclick="changePage(1)">Next</button>
                <button type="submit" class="btn btn-success" id="submitBtn" style="display: none;">Submit feedback</button>
            </div>
        </form>
        <p class="mt-3" style="font-size: x-small; text-align: right;">Form data persisted with <img class="icon" src="<?= Constants::URL_ICON_GARLICJS ?>"><a href="https://garlicjs.org" target="_blank"><b>Garlic.js</b></a></p>
    </div>

    <!-- Modal for displaying incomplete metrics. -->
    <div aria-hidden="true" aria-labelledby="incompleteFormModalTitle" class="modal fade" id="incompleteFormModal" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="incompleteFormModalTitle">Feedback not sent</h5>
                    <button type="button" aria-label="Close" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Please provide an opinion (i.e. select a radio button) for all the metrics before submitting your feedback. Remember that additional comments are <b>optional</b>.
                    <p class="mt-2"><b>Incomplete fields:</b></p>
                    <ul id="incomplete-fields-list">
                        <!-- This is filled automatically with JavaScript. -->
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Ok</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal for displaying when the user has not indicated their level of expertise. -->
    <div aria-hidden="true" aria-labelledby="incompleteExpertiseLevelModalTitle" class="modal fade" id="incompleteExpertiseLevelModal" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="incompleteExpertiseLevelModalTitle">Feedback not sent</h5>
                    <button type="button" aria-label="Close" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Please indicate your level of expertise before submitting your feedback.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Ok</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for displaying when the user has not provided a valid e-mail address. -->
    <div aria-hidden="true" aria-labelledby="invalidEmailModalTitle" class="modal fade" id="invalidEmailModal" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="invalidEmailModalTitle">Feedback not sent</h5>
                    <button type="button" aria-label="Close" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Please provide a valid e-mail address before submitting your feedback.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Ok</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts required for the application to function. -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" crossorigin="anonymous" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" crossorigin="anonymous" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" crossorigin="anonymous" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"></script>
    <script src="lib/Garlic.js-1.4.2/garlic.js"></script>
    <script type="text/javascript">
        const numberOfFormPages = <?= $numberOfFormPages ?>;
        let activeFormPage = 1;

        /* To be used only within this script. */
        function appendToIncompleteList(content) {
            const listOfIncompleteFields = document.getElementById("incomplete-fields-list");
            const incompleteField = document.createElement("li");

            incompleteField.innerHTML = content;

            listOfIncompleteFields.appendChild(incompleteField);
        }

        /* To be used only within this script. */
        function clearIncompleteList() {
            const listOfIncompleteFields = document.getElementById("incomplete-fields-list");

            while (listOfIncompleteFields.firstChild) {
                listOfIncompleteFields.removeChild(listOfIncompleteFields.firstChild);
            }
        }

        function changePage(direction) {
            const currentPageElement = document.getElementById(`pageNo${activeFormPage}`);

            currentPageElement.classList.remove("active");

            activeFormPage += direction;

            /* This should not be neccessary, but JUST IN CASE... */
            if (activeFormPage < 1) {
                activeFormPage = 1;
            } else if (activeFormPage > numberOfFormPages) {
                activeFormPage = numberOfFormPages;
            }

            const newPageElement = document.getElementById(`pageNo${activeFormPage}`);

            newPageElement.classList.add("active");

            /* Apply changes to the navigation buttons if neccessary. */
            document.getElementById("prevBtn").disabled = activeFormPage == 1 ? true : false;
            document.getElementById("nextBtn").style.display = activeFormPage == numberOfFormPages ? "none" : "inline-block";
            document.getElementById("submitBtn").style.display = activeFormPage == numberOfFormPages ? "inline-block" : "none";

            globalThis.scrollTo({top: 0, left: 0, behavior: "instant"});
        }

        document.getElementById("survey").addEventListener("submit", function(event) {
            const form = event.target;

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const listOfMetrics = <?= json_encode($listOfMetrics) ?>;
            const providedEmailAddress = document.getElementById("email").value;
            const providedExpertiseLevel = document.getElementById("expertiseLevel").value;
            let incompleteCount = 0;

            clearIncompleteList();

            listOfMetrics.forEach(metric => {
                const radiosForMetric = form.querySelectorAll(`input[name="metricID${metric.ID}Radios"]:checked`);

                if (radiosForMetric.length == 0) {
                    incompleteCount++;

                    if (incompleteCount <= 10) {
                        appendToIncompleteList("<b>#" + metric.ID + ":</b> " + metric.Name);
                    }
                }
            });

            if (incompleteCount > 0) {
                event.preventDefault();

                if (incompleteCount > 10) {
                    appendToIncompleteList("<i>And " + (incompleteCount - 10) + " more…</i>");
                }

                $("#incompleteFormModal").modal("show");
            } else if (providedExpertiseLevel ==  "") {
                event.preventDefault();

                $("#incompleteExpertiseLevelModal").modal("show");
            } else if (!emailRegex.test(providedEmailAddress)) {
                event.preventDefault();

                $("#invalidEmailModal").modal("show");
            }
        });

        document.getElementById("prevBtn").disabled = true;
    </script>
</body>

</html>
