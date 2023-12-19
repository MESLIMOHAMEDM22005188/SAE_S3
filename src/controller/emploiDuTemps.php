<?php
require_once 'src/controller/IcsController.php';

$codeADE = isset($_GET['codeADE']) ? $_GET['codeADE'] : null;

$icsController = new Controllers\IcsController();

$emploiDuTemps = $icsController->getTimetableByCode($codeADE);
