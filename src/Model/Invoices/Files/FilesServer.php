<?php
namespace Model\Invoices\Files;

use config;
use Helper\Helper;
use FTPService\FTPService as FTP;
use Model\Invoices\Files\FilesStrategyInterface as FilesStrategy;

class FilesServer implements FilesStrategy
{

    public function getFilesList() : array
    {
        $path = config::$FTPConnParams['workdir'];

        $result = [];
        try {
            $ftp = new FTP(config::$FTPConnParams);

            $filesList = $ftp->getFilesRawList($path);
            foreach ($filesList as $fileInfo) {
                $result[] = [
                        'filename' => $fileInfo['name'] ?? '',
                        'size' => (round($fileInfo['size'] / 1024) . ' kb') ?? 0,
                        'date' => ($fileInfo['day'] . ' ' . $this->translateMonth($fileInfo['month'])) ?? '',
                        'time' => $fileInfo['time'] ?? '',
                    ];
            }
        } catch (\Exception $ex) {

        }

        return $result ?? [];
    }


    public function saveToFile(string $fileContent, string $filename)
    {
        $path = config::$FTPConnParams['workdir'];

        try {
            $ftp = new FTP(config::$FTPConnParams);

            $tmpFile = tmpfile();
            fwrite($tmpFile, $fileContent);
            rewind($tmpFile);
            $tmpMetaData = stream_get_meta_data($tmpFile);

            $ftp->putFile($path . $filename, $tmpMetaData['uri']);

        } catch (\Exception $e) {
            // Helper::debug($e);
            return false;
        }

        return true;
    }

    public function deleteFile(string $filename)
    {
        $path = config::$FTPConnParams['workdir'];

        try {
            $ftp = new FTP(config::$FTPConnParams);

            $ftp->deleteFile($path . $filename);

        } catch (\Exception $e) {
            Helper::debug($e);
            return false;
        }

        return true;
    }

    public function isFileExists(string $filename)
    {
        $isFound = false;
        $result = [];

        $filesListRaw = $this->getFilesList();
        if (0 == count($filesListRaw)) return [];
        foreach ($filesListRaw as $key => &$fileInfo) {
            $filenameRaw = $fileInfo['filename'];

            if ($filenameRaw !== $filename) {
                if ($filenameRaw === ($filename . '.sent')) {
                    $isFound = true;
                }
            } else {
                $isFound = true;
            }
            if ($isFound) break;
        }
        if ($isFound) $result = $fileInfo;

        // Helper::debug($filesList,false);
        // Helper::debug($result,false);
        return $result;
    }



    private function translateMonth(string $monthName)
    {
        $trans = [
                'Jan' => 'Sty',
                'Feb' => 'Lut',
                'Mar' => 'Mar',
                'Apr' => 'Kwi',
                'May' => 'Maj',
                'Jun' => 'Cze',
                'Jul' => 'Lip',
                'Aug' => 'Sie',
                'Sep' => 'Wrz',
                'Oct' => 'Paź',
                'Nov' => 'Lis',
                'Dec' => 'Gru',
            ];
        return (isset($trans[$monthName])) ? $trans[$monthName] : $monthName;

    }
}