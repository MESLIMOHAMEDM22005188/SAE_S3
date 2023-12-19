<?php
require_once 'src/controller/IcsController.php';

$codeADE = isset($_GET['codeADE']) ? $_GET['codeADE'] : null;

$icsController = new Controllers\IcsController();

// Cela devrait retourner un tableau structuré des événements par jour et par heure.
$events = $icsController->getTimetableByCode($codeADE);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Emploi du temps</title>
    <style>
        // Votre CSS ici ...
    </style>
</head>
<body>
<table class="timetable">
    <tr>
        <th>Heure</th>
        <th>Lundi</th>
        // Les autres en-têtes ...
    </tr>
    <?php
    for ($hour = 8; $hour <= 18; $hour++) {
        echo "<tr>";
        echo "<th>" . str_pad($hour, 2, "0", STR_PAD_LEFT) . ":00</th>";

        foreach (['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'] as $day) {
            $timeSlotKey = str_pad($hour, 2, "0", STR_PAD_LEFT) . ":00";
            if (isset($events[$day]) && is_array($events[$day]) && array_key_exists($timeSlotKey, $events[$day])) {
                $event = $events[$day][$timeSlotKey];
                echo "<td>{$event['summary']}<br>{$event['start']} - {$event['end']}</td>";
            } else {
                echo "<td class='empty'></td>";
            }
        }
        echo "</tr>";
    }
    ?>
</table>
</body>
</html>
