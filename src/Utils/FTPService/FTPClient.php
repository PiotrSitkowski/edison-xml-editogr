<?php
namespace FTPService;

/**
 * FTP Client
 *
 * Author: Piotr Sitkowski
 */
class FTPClient
{

    /**
     * Connect to server and setting handler of this connection
     *
     * @param  string $host
     *
     * @return bool
     */
    public function connectFTP(string $host)
    {
        $handle = @ftp_connect($host);

        if (false == $handle) {
            throw new \Exception('Bad FTP connection for host '.$host);
        }
        return $handle;
    }

    /**
     * Closing FTP connection
     * @param  resource $FTPHandle
     * @return bool
     */
    public function closeFTP($FTPHandle)
    {
        if (is_null(@ftp_close($FTPHandle))) {
            throw new \Exception('Bad handle of FTP connection');
        }
    }


    /**
     * Login to FTP server
     *
     * @param  string $userName
     * @param  string $password
     *
     * @return bool
     */
    public function loginFTP($FTPHandle, string $userName, string $password) : bool
    {
        if(false == @ftp_login($FTPHandle, $userName, $password)) {
            throw new \Exception("FTP Connection: Bad username or password");
        }
        return true;
    }

    /**
     * Uploading  file to server
     * @param  string $remoteFile   Destination path and filename file in server
     * @param  string $localFile    Source path and filename localfile
     *
     * @return bool
     */
    public function uploadFile($FTPHandle, string $remoteFile, string $localFile) : bool
    {
        if(false == @ftp_put($FTPHandle, $remoteFile, $localFile, FTP_BINARY)) {
            throw new \Exception("FTP upload file: error when uploading file to server");
            return false;
        }
        return true;
    }

    /**
     * Deleting file from server
     * @param  string $filename
     *
     * @return bool
     */
    public function removeFile($FTPHandle, string $remoteFile) : bool
    {
        if(false == @ftp_delete($FTPHandle, $remoteFile)) {
            throw new \Exception("FTP removing file: error when removing file from server");
            return false;
        }
        return true;
    }

    /**
     * Getting files list
     * @param  string $directory
     *
     * @return array
     */
    public function nList($FTPHandle, string $directory) : array
    {

        $list = @ftp_nlist($FTPHandle, $directory);

        if(false == $list) {
            throw new \Exception("FTP Connection: Bad username or password");
        }
        return $list;
    }

    /**
     * Getting files list with details
     * @param  string $directory
     *
     * @return array
     */
    public function rawList($FTPHandle, string $directory) : array
    {

        $list = @ftp_rawlist($FTPHandle, $directory);

        if(false == $list) {
            throw new \Exception("FTP Connection: Bad username or password");
        }
        return $list;
    }

    public function fgetFTP($FTPHandle, $fileHandle, string $filePath, int $mode = FTP_BINARY, int $resumepos = 0) : bool
    {
        if(false == @ftp_fget($FTPHandle, $fileHandle, $filePath, $mode, $resumepos)) {
            throw new \Exception("FTP get file content: Bad filename or path for file: $filePath");
        }
        return true;
    }

}