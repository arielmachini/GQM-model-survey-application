<nav class="navbar navbar-dark navbar-expand-lg sticky-top" style="background-color: black;">
    <a class="navbar-brand" href="<?= Constants::URL_SURVEY_PAGE ?>"><?= Constants::APP_NAME ?></a>

    <button type="button" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler" data-target="#navbarSupportedContent" data-toggle="collapse">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <?php
            foreach (Constants::APP_PAGES as $title => $url) {
                $class = str_ends_with($_SERVER['REQUEST_URI'], $url) ? "nav-item active" : "nav-item";
            ?>
                <li class="<?= $class ?>">
                    <a class="nav-link" href="<?= $url ?>"><?= $title ?></a>
                </li>
            <?php } ?>
        </ul>
    </div>
</nav>