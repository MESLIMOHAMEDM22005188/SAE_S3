<?php

namespace Controllers;

use Exception;

class IcsController
{


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

    private function updateDisplay($code) {
        // Logic to parse the ICS file and update the display
    }

    private function getOldFilePath($code) {
        // Logic to return the old file path based on the code
        $base_path = ABSPATH . TV_ICSFILE_PATH;
        return $base_path . 'file' . $code . '.ics';
    }

    public function getUrl($code) {
        $str = strtotime("now");
        $str2 = strtotime(date("Y-m-d", strtotime('now')) . " +6 day");
        $start = date('Y-m-d', $str);
        $end = date('Y-m-d', $str2);
        $url = 'https://ade-web-consult.univ-amu.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=8&resources=' . $code . '&calType=ical&firstDate=' . $start . '&lastDate=' . $end;
        return $url;
    }

    public function addFile($code) {
        try {
            $path = ABSPATH . TV_ICSFILE_PATH . "file0/" . $code . '.ics';
            $url = $this->getUrl($code);
            //file_put_contents($path, fopen($url, 'r'));
            $contents = '';
            if (($handler = @fopen($url, "r")) !== FALSE) {
                while (!feof($handler)) {
                    $contents .= fread($handler, 8192);
                }
                fclose($handler);
            } else {
                throw new Exception('File open failed.');
            }
            if ($handle = fopen($path, "w")) {
                fwrite($handle, $contents);
                fclose($handle);
            } else {
                throw new Exception('File open failed.');
            }
        } catch (Exception $e) {
            $this->addLogEvent($e);
        }
    }

    public function getFilePath($code) {
        $base_path = ABSPATH . TV_ICSFILE_PATH;

        // Check if local file exists
        for ($i = 0; $i <= 3; ++$i) {
            $file_path = $base_path . 'file' . $i . '/' . $code . '.ics';
            // TODO: Demander a propos du filesize
            if (file_exists($file_path) && filesize($file_path) > 100)
                return $file_path;
        }

        // No local version, let's download one
        $this->addFile($code);
        return $base_path . "file0/" . $code . '.ics';
    }

    public function addLogEvent($event) {
        $time = date("D, d M Y H:i:s");
        $time = "[" . $time . "] ";
        $event = $time . $event . "\n";
        file_put_contents(ABSPATH . TV_PLUG_PATH . "fichier.log", $event, FILE_APPEND);
    }
}
