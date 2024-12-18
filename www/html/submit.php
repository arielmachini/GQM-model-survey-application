<?php
require_once './controller/DBController.php';
require_once './lib/Constants.php';

function saveToCSV($responseFields) {
    $csvFile = fopen('/var/www/GQM/responses.csv', 'a');
    $execResult = fputcsv($csvFile, $responseFields);

    fclose($csvFile);

    return $execResult;
}

date_default_timezone_set('America/Argentina/Rio_Gallegos');

$feedbackSent = false;
$errorMessage;

/* Response fields: */
$interviewParticipation = isset($_POST['interviewParticipation']) ? 'Yes' : 'No';
$providedEmailAddress = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$providedExpertiseLevel = $_POST['expertiseLevel'];
$responseDateTime = date('Y-m-d H:i:s');
$responseJSON = '[';

if (empty($providedEmailAddress)) {
    $errorMessage = 'One or more mandatory fields have not been filled';
}

if (!isset($errorMessage)) {
    for ($i = 1; $i <= Constants::TOTAL_NUMBER_OF_METRICS; $i++) {
        if (!isset($_POST['metricID' . $i . 'Radios'])) {
            $errorMessage = 'One or more mandatory fields have not been filled';

            break;
        }

        $commentsForCurrentMetric = filter_input(INPUT_POST, 'metricID' . $i . 'Comments', FILTER_SANITIZE_SPECIAL_CHARS); // Remove special characters, including double quotes.
	$commentsForCurrentMetric = str_replace('&#13;&#10;', ' ', $commentsForCurrentMetric); // Replace line breaks with spaces.

        $opinionForCurrentMetric = '{"id":' . $i . ',"value":"' . filter_input(INPUT_POST, 'metricID' . $i . 'Radios') . '","comments":"' . $commentsForCurrentMetric . '"},';
        $responseJSON = $responseJSON . $opinionForCurrentMetric;
    }

    $responseJSON = substr($responseJSON, 0, -1);
}

$responseJSON = $responseJSON . ']';

if (!isset($errorMessage)) { // This condition has to be checked again.
    try {
        $queryResult = DBController::getInstance()->execute_query('INSERT INTO Response VALUES (NULL, ?, ?, ?, ?, ?)', [$responseDateTime, $providedExpertiseLevel, $providedEmailAddress, $interviewParticipation, $responseJSON]);

        if (!$queryResult || DBController::getInstance()->affected_rows > 0) {
            $errorMessage = DBController::getInstance()->error;

            /* If MySQL fails, try saving the expert's feedback to a CSV file. */
            if (saveToCSV([$responseDateTime, $providedExpertiseLevel, $providedEmailAddress, $interviewParticipation, $responseJSON])) {
                $feedbackSent = true; // Everything is OK!
            }
        } else {
            $feedbackSent = true; // Everything is OK!
        }
    } catch (Exception $e) {
        $errorMessage = $e->getMessage();

        /* If MySQL fails, try saving the expert's feedback to a CSV file. */
        if (saveToCSV([$responseDateTime, $providedExpertiseLevel, $providedEmailAddress, $interviewParticipation, $responseJSON])) {
            $feedbackSent = true; // Everything is OK!
        }
    }
}
?>

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title><?= Constants::APP_NAME ?> - <?= Constants::TITLE_SUBMIT_PAGE ?></title>
    <link rel="author" href="<?= Constants::URL_ABOUT_PAGE ?>">

    <link rel="icon" href="<?= Constants::URL_FAVICON ?>" type="image/x-icon">
    <link rel="stylesheet" crossorigin="anonymous" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm">
    <link rel="stylesheet" href="resources/css/style.css">
</head>

<body>
    <?php include_once './lib/Menu.php'; ?>

    <div class="container mb-4 mt-4">
        <?php if ($feedbackSent) { ?>
            <h3>Thank you!</h3>
            <div class="alert alert-success" role="alert">
                Your feedback was saved successfully. Thank you for your time, and for contributing to the improvement of our model! :)
            </div>
        <?php } else { ?>
            <h3>Error</h3>
            <div class="alert alert-danger" role="alert">
                Sorry, but your feedback could not be saved due to an error. <b>Details:</b><span class="error-message"><?= $errorMessage ?></span>.
            </div>
        <?php } ?>
    </div>

    <!-- Scripts required for the application to function. -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" crossorigin="anonymous" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" crossorigin="anonymous" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" crossorigin="anonymous" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"></script>

</body>

</html>
