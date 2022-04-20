<?php

use function Ramsey\Uuid\v1;

include("../../../inc/includes.php");
require_once("../inc/config.class.php");

$plugin = new Plugin();
if ($plugin->isActivated("holidays")) {
    $config = new PluginHolidaysConfig();

    if (isset($_POST["update"])) {
        Session::checkRight("config", UPDATE);
        $config->addHolidaysToCalendar($_POST);
    } 

    Html::header('Mon Plugin', $_SERVER["PHP_SELF"], "config", "plugins");
    $config->showConfigForm($_POST);
} else {
    Html::header('configuration', '', "config", "plugins");
    echo "<div class='center'><br><br>".
         "<img src=\"".$CFG_GLPI["root_doc"]."/pics/warning.png\" alt='warning'><br><br>";
    echo "<b>Merci d'activer le plugin</b></div>";
    Html::footer();
}

Html::footer();