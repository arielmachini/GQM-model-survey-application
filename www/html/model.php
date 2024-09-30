<?php
require_once './controller/DBController.php';
require_once './lib/Constants.php';

function printMetricSources($metricId) { // Yup, this function is also needed in this page.
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

$listOfGoalsAndQuestions = [];
$listOfMetrics = [];

/* Auxiliary arrays: */
$listOfGoalAttributes = [];
$listOfSources = [];

/*
 * Yes, the code and MySQL queries present in this page are complex, and it is
 * likely that they could be improved. But as for now, this is the most
 * efficient way I could come up with.
 */
$queryResult = DBController::getInstance()->query('SELECT Question.GoalID, Goal.Description AS GoalDescription, Question.ID AS QuestionID, Question.Description AS QuestionDescription FROM Goal JOIN Question ON Goal.ID = Question.GoalID ORDER BY Question.GoalID');

while ($row = $queryResult->fetch_assoc()) {
    if (!array_key_exists($row['GoalID'], $listOfGoalsAndQuestions)) {
        $listOfGoalsAndQuestions[$row['GoalID']] = array(
            'Description' => $row['GoalDescription'],
            'Questions' => [array(
                'ID' => $row['QuestionID'],
                'Description' => $row['QuestionDescription']
            )],
            'UsabilityAttributes' => []
        );
    } else {
        $listOfGoalsAndQuestions[$row['GoalID']]['Questions'][] = array(
            'ID' => $row['QuestionID'],
            'Description' => $row['QuestionDescription']
        );
    }
}

$queryResult = DBController::getInstance()->query('SELECT Question_Metric.QuestionID, Question_Metric.MetricID, Metric.Name, Metric.Description, Metric.Type, Metric.ValueList, Metric.Weight FROM Metric JOIN Question_Metric ON Metric.ID = Question_Metric.MetricID ORDER BY Question_Metric.MetricID');

while ($row = $queryResult->fetch_assoc()) {
    if (!array_key_exists($row['MetricID'], $listOfMetrics)) {
        $listOfMetrics[$row['MetricID']] = array(
            'Name' => $row['Name'],
            'Description' => $row['Description'],
            'Type' => $row['Type'],
            'ValueList' => $row['ValueList'],
            'Weight' => $row['Weight'],
            'Questions' => [$row['QuestionID']]
        );
    } else {
        $listOfMetrics[$row['MetricID']]['Questions'][] = $row['QuestionID'];
    }
}

/* Now, to fill the auxiliary arrays. */
$queryResult = DBController::getInstance()->query('SELECT Goal_Attribute.GoalID, Goal_Attribute.AttributeID, Attribute.Name FROM Goal JOIN Goal_Attribute ON Goal.ID = Goal_Attribute.GoalID JOIN Attribute ON Goal_Attribute.AttributeID = Attribute.ID ORDER BY Attribute.Name');

while ($row = $queryResult->fetch_assoc()) {
    foreach ($listOfGoalsAndQuestions as $goalID => $goalDetails) {
        if ($goalID == $row['GoalID'] && !array_key_exists($row['Name'], $goalDetails['UsabilityAttributes'])) {
            $listOfGoalsAndQuestions[$goalID]['UsabilityAttributes'][] = array(
                'ID' => $row['AttributeID'],
                'Name' => $row['Name']
            );
        }
    }
}

$queryResult = DBController::getInstance()->query('SELECT Metric_Source.MetricID, Metric_Source.SourceID, Source.Description FROM Metric_Source INNER JOIN Source ON Metric_Source.SourceID = Source.ID');

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

    <title><?= Constants::APP_NAME ?> - <?= Constants::TITLE_MODEL_PAGE ?></title>
    <link rel="author" href="<?= Constants::URL_ABOUT_PAGE ?>">

    <link rel="icon" href="<?= Constants::URL_FAVICON ?>" type="image/x-icon">
    <link rel="stylesheet" crossorigin="anonymous" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm">
    <link rel="stylesheet" href="resources/css/style.css">
</head>

<body>
    <?php include_once './lib/Menu.php'; ?>

    <div class="container mb-4 mt-4">
        <h3>Our model, detailed</h3>
        <p>Here you can see the full definition of the GQM model proposed. You can also view the model graphically in <a href="<?= Constants::URL_GQM_DIAGRAM_PAGE ?>">this page</a>.</p>
        <p class="metric-details">Each Metric can have one of the following <b>values</b>:</p>
        <ul class="metric-details">
            <li><b>1</b> (Complies)</li>
            <li><b>0.5</b> (Complies partially)</li>
            <li><b>0</b> (Does not comply)</li>
        </ul>
        <p class="metric-details">This value is multiplied by the <b>weight</b> assigned to the metric being measured. It is important to clarify that these weights were not applied arbitrarily; they were determined after studying the responses of a survey conducted between September and December 2023 [<a href="<?= Constants::URL_SOURCES_PAGE ?>#2" target="_blank">2</a>]. The participants of this survey were experienced web API consumers and developers.</p>
        <hr>

        <?php foreach ($listOfGoalsAndQuestions as $goalID => $goalDetails) { ?>
            <h5><b>Goal #<?= $goalID ?>:</b> <?= $goalDetails['Description'] ?></h5>
            <p>
                <b>Usability attributes:</b>
                <?php
                $numberOfAttributes = count($goalDetails['UsabilityAttributes']);

                if ($numberOfAttributes == 1) {
                    echo '<a href="' . Constants::URL_USABILITY_ATTRIBUTES_PAGE . '#' . $goalDetails['UsabilityAttributes'][0]['ID'] . '" target="_blank">' . $goalDetails['UsabilityAttributes'][0]['Name'] . '</a>';
                } else {
                    $attributesString = '';
                    for ($i = 0; $i < $numberOfAttributes; $i++) {
                        if ($i == $numberOfAttributes - 1) {
                            $attributesString = substr($attributesString, 0, -2) . ' and <a href="' . Constants::URL_USABILITY_ATTRIBUTES_PAGE . '#' . $goalDetails['UsabilityAttributes'][$i]['ID'] . '" target="_blank">' . $goalDetails['UsabilityAttributes'][$i]['Name'] . '</a>';

                            break;
                        }

                        $attributesString = $attributesString . '<a href="' . Constants::URL_USABILITY_ATTRIBUTES_PAGE . '#' . $goalDetails['UsabilityAttributes'][$i]['ID'] . '" target="_blank">' . $goalDetails['UsabilityAttributes'][$i]['Name'] . '</a>, ';
                    }

                    echo $attributesString;
                }
                ?>
            </p>
            
            <table class="mb-5 model table table-bordered table-hover table-sm table-striped">
                <?php
                $realQuestionID = 0;

                foreach ($goalDetails['Questions'] as $question) {
                    $realQuestionID++;
                ?>
                    <thead class="thead-dark">
                        <tr>
                            <th class="align-middle text-center" scope="col">Q<?= $goalID . '.' . $realQuestionID ?></th>
                            <th class="align-middle" colspan="4" scope="col"><?= $question['Description'] ?></th>
                        </tr>
                    </thead>
                    <thead class="thead-light">
                        <tr>
                            <th class="align-middle text-center" scope="col">Name</th>
                            <th class="align-middle text-center" scope="col">Description</th>
                            <th class="align-middle text-center" scope="col">Type</th>
                            <th class="align-middle text-center" scope="col">Possible values</th>
                            <th class="align-middle text-center" scope="col" style="cursor: help;" title="The weight of a metric is a multiplier that is applied to the obtained value (from the list of possible values).">Weight</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($listOfMetrics as $metricID => $metricDetails) {
                            if (in_array($question['ID'], $metricDetails['Questions'])) {
                        ?>
                                <tr>
                                    <th class="text-justify" scope="row"><?= $metricDetails['Name'] ?><?= printMetricSources($metricID) ?></th>
                                    <td class="text-justify"><?= $metricDetails['Description'] ?></td>
                                    <td class="text-center"><?= $metricDetails['Type'] ?></td>
                                    <td class="text-justify" style="font-size: x-small;">
                                        <?php
                                        if ($metricDetails['ValueList'] != '{"0": "", "1": "", "0.5": ""}') {
                                            $possibleValues = json_decode($metricDetails['ValueList']);

                                            foreach ($possibleValues as $value => $conditionForValue) {
                                        ?>
                                                <span class="mb-1" style="display: block;"><b><?= $value ?>:</b> <?= $conditionForValue ?></span>
                                            <?php
                                            }
                                        } else {
                                            ?>
                                            <i style="color: gray;">Still to be defined.</i>
                                        <?php } ?>
                                    </td>
                                    <td class="text-center"><?= $metricDetails['Weight'] ?></td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                <?php } ?>
            </table>
        <?php } ?>
    </div>

    <!-- Scripts required for the application to function. -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" crossorigin="anonymous" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" crossorigin="anonymous" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" crossorigin="anonymous" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"></script>

</body>

</html>
