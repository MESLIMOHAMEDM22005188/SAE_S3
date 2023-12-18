<?php

namespace Controllers;

use Exception;

class IcsController
{
    // Method to set the timetable by ADE code and check for updates
    public function setTimetableByCode($code) {
        $this->checkAndUpdateFile($code);
    }

    // Method to check and update the ICS file
    public function checkAndUpdateFile($code) {
        $newFilePath = $this->getFilePath($code);
        $oldFilePath = $this->getOldFilePath($code);

        if (file_exists($newFilePath)) {
            if (!file_exists($oldFilePath) || md5_file($newFilePath) != md5_file($oldFilePath)) {
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath); // Delete old file
                }
                rename($newFilePath, $oldFilePath); // Move new file to old file's place
                $this->updateDisplay($code);
            }
        }
    }

    // Method to update the display with the new ICS data
    private function updateDisplay($code) {
        // Logic to parse the ICS file and update the display
    }

    // Method to get the path of the old ICS file
    private function getOldFilePath($code) {
        $base_path = ABSPATH . ICSFILE_PATH;
        return $base_path . 'old_file' . $code . '.ics';
    }

    // Method to generate the URL to download the ICS file
    public function getUrl($code) {
        $str = strtotime("now");
        $str2 = strtotime(date("Y-m-d", strtotime('now')) . " +6 day");
        $start = date('Y-m-d', $str);
        $end = date('Y-m-d', $str2);
        $url = 'https://ade-web-consult.univ-amu.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=8&resources=' . $code . '&calType=ical&firstDate=' . $start . '&lastDate=' . $end;
        return $url;
    }

    // Method to add a new ICS file
    public function addFile($code) {
        try {
            $path = ABSPATH . ICSFILE_PATH . "file0/" . $code . '.ics';
            $url = $this->getUrl($code);
            $contents = file_get_contents($url);
            if ($contents === false) {
                throw new Exception('File download failed.');
            }
            file_put_contents($path, $contents);
        } catch (Exception $e) {
            $this->addLogEvent($e->getMessage());
        }
    }

    // Method to get the path of the new ICS file
    public function getFilePath($code) {
        $base_path = ABSPATH . ICSFILE_PATH . "file0/";
        $file_path = $base_path . $code . '.ics';
        if (!file_exists($file_path)) {
            $this->addFile($code);
        }
        return $file_path;
    }

    // Method to add an event to the log
    public function addLogEvent($event) {
        $time = date("D, d M Y H:i:s");
        $time = "[" . $time . "] ";
        $event = $time . $event . "\n";
        file_put_contents(ABSPATH . TV_PLUG_PATH . "fichier.log", $event, FILE_APPEND);
    }
}
