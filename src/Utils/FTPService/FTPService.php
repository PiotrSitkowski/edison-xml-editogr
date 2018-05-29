<?php
namespace FTPService;

use Helper\Helper;

/**
 * FTP Service
 *
 * Author: Piotr Sitkowski
 */
class FTPService extends FTPClient
{

    private $FTPHandle = null;
    protected $connParam = [];

    public function __construct(array $connectionParams)
    {
        $this->connParam = $connectionParams;
        $this->initConnection();

    }

    /**
     * Init FTP connection - creating FTP handle of connection and authorize connection
     * @return bool
     */
    protected function initConnection()
    {

        $host = $this->connParam['host'] ??  null;
        $userName = $this->connParam['user'] ??  null;
        $password = $this->connParam['password'] ??  null;

        $this->FTPHandle = $this->connectFTP($host);

        return $this->loginFTP($this->FTPHandle, $userName, $password);
    }

    /**
     * Checking connection - handle of connection must by not null
     *
     * @return bool
     */
    public function checkConnection() : bool
    {
        if (is_null($this->FTPHandle)) throw new \Exception("No FTP connection");
        return true;
    }

    /**
     * Closing FTP connection
     */
    public function __destruct()
    {
        try {
            if ($this->checkConnection()) $this->closeFTP($this->FTPHandle);
        } catch (\Exception $ex) {
            // echo $ex->getMessage();
        }
    }

    /**
     * Uploading  file to server
     * @param  string $remoteFile   Destination path and filename file in server
     * @param  string $localFile    Source path and filename localfile
     *
     * @return bool
     */
    public function putFile(string $remoteFile, string $localFile) : bool
    {
        $this->checkConnection();

        try {
            $result = $this->uploadFile($this->FTPHandle, $remoteFile, $localFile);

        } catch (\Exception $ex) {

        }

        return $result ?? false;
    }

    /**
     * Deleting file from server
     * @param  string $filename
     *
     * @return bool
     */
    public function deleteFile(string $remoteFile) : bool
    {
        $this->checkConnection();

        try {
            $result = $this->removeFile($this->FTPHandle, $remoteFile);

        } catch (\Exception $ex) {

        }

        return $result ?? false;
    }


    /**
     * Getting files list
     * @param  string $directory
     *
     * @return array
     */
    public function getFilesList(string $directory)
    {
        $this->checkConnection();

        try {

            $result = $this->nList($this->FTPHandle, $directory);

        } catch (\Exception $ex) {

        }

        return $result ?? [];
    }

    /**
     * Getting files list with details
     * @param  string $directory
     *
     * @return array
     */
    public function getFilesRawList(string $directory)
    {
        $this->checkConnection();

        $result = [];
        try {

            $resultRaw = $this->rawList($this->FTPHandle, $directory);
            if (count($resultRaw)) {
                foreach ($resultRaw as $line) {
                    $result[] = $this->getDetailsFromFileRawItem($line);
                }
            }
        } catch (\Exception $ex) {

        }

        return $result ?? [];
    }

    /**
     * Getting details about file from raw line
     * @param  string $item
     * @param  string $line
     *
     * @return array
     */
    public function getDetailsFromFileRawItem(string $line)
    {
        // echo '<br>'.$line;
        $vinfo = preg_split('/[\s]+/', $line, 9);
        $info = [];

        if ($vinfo[0] !== "total") {
          $info['chmod'] = $vinfo[0];
          $info['num']   = $vinfo[1];
          $info['owner'] = $vinfo[2];
          $info['group'] = $vinfo[3];
          $info['size']  = $vinfo[4];
          $info['month'] = $vinfo[5];
          $info['day']   = $vinfo[6];
          $info['time']  = $vinfo[7];
          $info['name']  = $vinfo[8];
        }

       return $info;
    }

    /**
     * Geting content of file from server
     *
     * @param  string   $pathWithFileName
     *
     * @return string   Content of file
     */
    public function getFileContentFTP(string $pathWithFilename) : string
    {
        $this->checkConnection();

        $fileContent = null;

        try {

            $tmpFileHandle = fopen('php://temp', 'r+');
            $this->fgetFTP($this->FTPHandle, $tmpFileHandle, $pathWithFilename, FTP_BINARY, 0);

            $fileStats = @fstat($tmpFileHandle);
            fseek($tmpFileHandle, 0);

            $fileSize = isset($fileStats['size']) && $fileStats['size'] > 0
                ? $fileStats['size']
                : null;

            if (!is_null($fileSize)) {
                $fileContent = @fread($tmpFileHandle, $fileSize);
                fclose($tmpFileHandle);
            } else {
                throw new \Exception("File $fileName is empty");
            }


        } catch (\Exception $ex) {

            throw new \Exception($ex->getMessage());
        }

        return $fileContent;
    }

}