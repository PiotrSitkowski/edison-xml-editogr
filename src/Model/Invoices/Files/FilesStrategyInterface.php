<?php
namespace Model\Invoices\Files;

interface FilesStrategyInterface
{

    public function getFilesList() : array;

    public function saveToFile(string $fileContent, string $filename);

    public function deleteFile(string $filename);

    public function isFileExists(string $filename);

}