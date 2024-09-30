<?php

class Constants
{
    const APP_NAME = "GQM model for web API usability";

    /* Resource URLs: */
    const URL_AUTHOR_PHOTO = self::URL_RESOURCES_FOLDER . "me.jpg";
    const URL_GQM_DIAGRAM = self::URL_RESOURCES_FOLDER . "diagram.svg";
    const URL_ICON_CONICET = self::URL_RESOURCES_FOLDER . "icons/CONICET.png";
    const URL_ICON_GARLICJS = self::URL_RESOURCES_FOLDER . "icons/Garlicjs.png";
    const URL_ICON_GISP = self::URL_RESOURCES_FOLDER . "icons/GISP.png";
    const URL_ICON_GOOGLE_SCHOLAR = self::URL_RESOURCES_FOLDER . "icons/GoogleScholar.svg";
    const URL_ICON_LINKEDIN = self::URL_RESOURCES_FOLDER . "icons/LinkedIn.png";
    const URL_ICON_ORCID = self::URL_RESOURCES_FOLDER . "icons/ORCID.svg";
    const URL_ICON_RESEARCHGATE = self::URL_RESOURCES_FOLDER . "icons/ResearchGate.svg";
    const URL_ICON_UARG = self::URL_RESOURCES_FOLDER . "icons/UARG.png";
    const URL_FAVICON = "https://www.unpa.edu.ar/sites/default/files/genesis_unpa_favicon_0.jpg";
    const URL_RESOURCES_FOLDER = "resources/";

    /* Page titles: */
    const TITLE_ABOUT_PAGE = "About";
    const TITLE_GQM_DIAGRAM_PAGE = "Diagram";
    const TITLE_MODEL_PAGE = "Model";
    const TITLE_SOURCES_PAGE = "Sources";
    const TITLE_SUBMIT_PAGE = "Feedback submission";
    const TITLE_SURVEY_PAGE = "Survey";
    const TITLE_USABILITY_ATTRIBUTES_PAGE = "Usability attributes";

    /* Page URLs: */
    const URL_ABOUT_PAGE = "about.php";
    const URL_GQM_DIAGRAM_PAGE = "diagram.php";
    const URL_MODEL_PAGE = "model.php";
    const URL_SOURCES_PAGE = "sources.php";
    const URL_SURVEY_PAGE = "./"; // "index.php";
    const URL_SURVEY_SUBMIT_PAGE = "submit.php";
    const URL_USABILITY_ATTRIBUTES_PAGE = "attributes.php";

    /* Application pages grouped in an array (to generate the menu): */
    const APP_PAGES = array(
        self::TITLE_SURVEY_PAGE => self::URL_SURVEY_PAGE,
        self::TITLE_MODEL_PAGE => self::URL_MODEL_PAGE,
        self::TITLE_USABILITY_ATTRIBUTES_PAGE => self::URL_USABILITY_ATTRIBUTES_PAGE,
        self::TITLE_GQM_DIAGRAM_PAGE => self::URL_GQM_DIAGRAM_PAGE,
        self::TITLE_SOURCES_PAGE => self::URL_SOURCES_PAGE,
        self::TITLE_ABOUT_PAGE => self::URL_ABOUT_PAGE
    );

    const TOTAL_NUMBER_OF_METRICS = 52; // Not my preferred way to define this constant, but it is currently the safer way. (12/09/2024)
}
