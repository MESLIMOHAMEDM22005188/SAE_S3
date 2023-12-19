<?php
function prepareData($value) {
    if (is_string($value)) {
        $value = preg_replace('/\b\d{13,}\b/', '', $value);
        $value = str_replace('\n', PHP_EOL, $value);
        $value = stripslashes($value);
    } elseif (is_array($value)) {
        return array_map('prepareData', $value);
    }
    return $value;
}


/**
 * @throws Exception
 */
function recupIcs($url) {
    $icsContent = file_get_contents($url);
    if ($icsContent === false) {
        throw new Exception("Unable to retrieve ICS content.");
    }

    $lines = explode("\n", $icsContent);
    foreach ($lines as $line) {
        if (strpos($line, 'BEGIN:VEVENT') !== false) {
            $event = [];
        } elseif (strpos($line, 'END:VEVENT') !== false) {
            processEvent($event);
        } elseif (isset($event)) {
            if (strpos($line, ':') !== false) {
                list($key, $value) = explode(':', $line, 2);
                $event[$key] = prepareData($value); // Appel à prepareData pour nettoyer la valeur
            }
        }
    }
}

$events = [];

function processEvent($eventData) {
    global $events;

    // Supposons que les données incluent la date, l'heure de début et l'heure de fin
    $dayOfWeek = date('l', strtotime($eventData['DTSTART'])); // Convertit en jour de la semaine
    $startTime = date('H:i', strtotime($eventData['DTSTART'])); // Heure de début au format HH:mm
    $endTime = date('H:i', strtotime($eventData['DTEND'])); // Heure de fin au format HH:mm
    $summary = $eventData['SUMMARY'];

    // Stocker les données dans le tableau global $events
    if (!isset($events[$dayOfWeek])) {
        $events[$dayOfWeek] = [];
    }

    // Vous devrez ajuster la logique ci-dessous en fonction de la structure exacte de vos données ICS
    $events[$dayOfWeek][] = [
        'start' => $startTime,
        'end' => $endTime,
        'summary' => $summary
    ];
}

$adeLinks = [
    //1AG1
    1 => "https://ade-web-consult.univ-amu.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=8&resources=8382&calType=ical&firstDate=2023-12-18&lastDate=2023-12-22",
    //1AG2
    2 => "https://ade-web-consult.univ-amu.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=8&resources=8380&calType=ical&firstDate=2023-12-18&lastDate=2023-12-22",
    //1AG3
    3 => "https://ade-web-consult.univ-amu.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=8&resources=8383&calType=ical&firstDate=2023-12-18&lastDate=2023-12-22",
    //1AG4
    4 => "https://ade-web-consult.univ-amu.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=8&resources=8381&calType=ical&firstDate=2023-12-18&lastDate=2023-12-22",
    //1A
    5 => "https://ade-web-consult.univ-amu.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=8&resources=8379&calType=ical&firstDate=2023-12-18&lastDate=2023-12-22",
    //2AGA1
    6 => "https://ade-web-consult.univ-amu.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=8&resources=8396&calType=ical&firstDate=2024-01-15&lastDate=2024-01-19",
    //2AGA2
    7 => "https://ade-web-consult.univ-amu.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=8&resources=8397&calType=ical&firstDate=2023-12-18&lastDate=2023-12-22",
    //2AGB
    8 => "https://ade-web-consult.univ-amu.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=8&resources=8398&calType=ical&firstDate=2023-12-18&lastDate=2023-12-22",
    //2A
    9 => "https://ade-web-consult.univ-amu.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=8&resources=45843&calType=ical&firstDate=2023-12-18&lastDate=2023-12-22",
    //3AGA1
    10 => "https://ade-web-consult.univ-amu.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=8&resources=42523&calType=ical&firstDate=2023-12-18&lastDate=2023-12-22",
    //3AGA2
    11 => "https://ade-web-consult.univ-amu.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=8&resources=42524&calType=ical&firstDate=2023-12-18&lastDate=2023-12-22",
    //3AGB
    12 => "https://ade-web-consult.univ-amu.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=8&resources=42525&calType=ical&firstDate=2023-12-18&lastDate=2023-12-22",
    //3A
    13 => "https://ade-web-consult.univ-amu.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=8&resources=8408&calType=ical&firstDate=2023-12-18&lastDate=2023-12-22"
];

foreach ($adeLinks as $key => $link) {
    if ($key > 13) {
        break;
    }
    try {
        recupIcs($link);
    } catch (Exception $e) {
        echo "Error for ADE code $key: " . $e->getMessage() . "\n";
    }}
