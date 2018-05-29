<?php
namespace Model\Invoices\Files;

use config;
use Helper\Helper;
use Model\Invoices\Files\FilesStrategyInterface as FilesStrategy;

class FilesLocal implements FilesStrategy
{

    private $path = null;

    public function __construct()
    {
        $this->path = config::$Paths['storage'] . '/' ?? null;
    }

    public function getFilesList() : array
    {
        if (empty($this->path) || !file_exists($this->path)) return [];

        $indir = array_filter(scandir($this->path), function($item) {
            return !is_dir(config::$Paths['storage'] . '/' . $item);
        });

        $result = [];
        $dirList = array_values($indir);

        foreach ($dirList as $filename) {
            $fileInfo = stat($this->path . $filename);
            $result[] = [
                    'filename' => $filename,
                    'size' => (isset($fileInfo['size'])) ? (round($fileInfo['size'] / 1024) . ' kb') : 0,
                    'date' => (isset($fileInfo['mtime'])) ? date('Y-m-d', $fileInfo['mtime']) : '',
                    'time' => (isset($fileInfo['mtime'])) ? date('H:i:s', $fileInfo['mtime']) : ''
                ];
        }

        return $result;
    }


    public function saveToFile(string $fileContent, string $filename)
    {

        try {

            $res = file_put_contents($this->path . '/' . $filename, $fileContent);
            if (false === $res) return false;

        } catch (\Exception $e) {
             return false;
        }

        return true;
    }

    public function deleteFile(string $filename)
    {

        try {

            $res = true;
            if (file_exists($this->path . '/' . $filename)) {
                $res = unlink($this->path . '/' . $filename);
            }
            if (false === $res) return false;

        } catch (\Exception $e) {
             return false;
        }

        return true;
    }

    public function isFileExists(string $filename)
    {
        $isFound = false;
        $result = [];

        if (empty($this->path) || !file_exists($this->path)) return [];

        if (!file_exists($this->path . $filename)) {
            if (file_exists($this->path . $filename . '.sent')) {
                $isFound = true;
            }
        } else {
            $isFound = true;
        }

        if ($isFound) {
            $fileInfo = stat($this->path . $filename);
            $result = [
                    'filename' => $filename,
                    'size' => (isset($fileInfo['size'])) ? (round($fileInfo['size'] / 1024) . ' kb') : 0,
                    'date' => (isset($fileInfo['mtime'])) ? date('Y-m-d', $fileInfo['mtime']) : '',
                    'time' => (isset($fileInfo['mtime'])) ? date('H:i:s', $fileInfo['mtime']) : ''
                ];
        }

        return $result;
    }

}