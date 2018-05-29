<?php
namespace DocumentXML\Edison;

use config;
use Helper\Helper;
use DocumentXML\ParserXML;
use FTPService\FTPService as FTP;

/**
 * Edison Invoice XML Importer
 * Author: Piotr Sitkowski
 */
class InvoiceXMLImporter
{

    private $invoiceData = [];

    public function __construct(string $filename = 'example.xml')
    {

        $this->invoiceData = $this->getInvoiceXML($filename);

    }

    /**
     * getInvoiceXML Open XML file and parse content to array
     *
     * @param  string $filename     Filename (without path)
     * @param  bool   $fromLocal    Whether read file from server/local directory (storage)
     *
     * @return array
     */
    private function getInvoiceXML(string $filename) : array
    {

        $contents = $this->readXMLFile($filename);
        if (empty($contents)) return [];

        return ParserXML::xml2array($contents)['Document-Invoice'] ?? [];
    }


    /**
     * readXMLFile Read XML file from server or local directory (storage)
     *
     * @param  string $fileXML      Filename (without path)
     * @param  bool   $fromLocal    Whether read file from server/local directory (storage)
     *
     * @return string               Content of XML file
     */
    private function readXMLFile(string $fileXML) : string
    {
        return config::$isLocalStorage
            ? $this->getFileContentFromLocal($fileXML)
            : $this->getFileContentFromServer($fileXML);
    }


    /**
     * getFileContentFromServer Connecting to server and getting XML file content
     *
     * @param  string $fileXML Filename (without path)
     *
     * @return string Content of XML file
     */
    private function getFileContentFromServer(string $fileXML) : string
    {

        $contents = '';

        $pathWithFileXML = config::$FTPConnParams['workdir'] . $fileXML;

        try {
            $ftp = new FTP(config::$FTPConnParams);

            $contents = $ftp->getFileContentFTP($pathWithFileXML);
        } catch (\Exception $ex) {

        }

        return $contents;
    }


    /**
     * getFileContentFromLocal Getting content of XML file from local directory (if XML is exists)
     *
     * @param  string $fileXML Filename (without path)
     *
     * @return string Content of XML file
     */
    private function getFileContentFromLocal(string $fileXML) : string
    {

        $pathWithFileXML = config::$Paths['storage'] .'/' . $fileXML;

        return file_exists($pathWithFileXML)
            ? file_get_contents($pathWithFileXML)
            : '';

    }

    /**
     * getInvoiceData Receive array data of XML file content
     *
     * @return array
     */
    public function getInvoiceData() : array
    {
        return $this->invoiceData ?? [];
    }


}