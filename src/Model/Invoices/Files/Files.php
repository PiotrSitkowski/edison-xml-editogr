<?php
namespace Model\Invoices\Files;

use config;
use Helper\Helper;
use Model\Invoices\Files\FilesStrategyInterface as FilesStrategy;

class Files implements FilesStrategy
{
    private $fileManager;

    function __construct()
    {
        $this->fileManager = config::$isLocalStorage
            ? new FilesLocal()
            : new FilesServer();
    }

    public function getFilesList() : array
    {
        return $this->fileManager->getFilesList();
    }

    public function saveToFile(string $fileContent, string $filename)
    {
        return $this->fileManager->saveToFile($fileContent, $filename);
    }

    public function deleteFile(string $filename)
    {
        return $this->fileManager->deleteFile($filename);
    }

    public function isFileExists(string $filename)
    {
        return $this->fileManager->isFileExists($filename);
    }


}