<?php

use Helper\Helper;

/**
 * Configuration (singleton)
 */
class config
{
    private static $instance;

    protected const configDefaultFilePath = __DIR__ . '/../config/confDefault.ini.php';
    protected const configMainFilePath = __DIR__ . '/../config/confMain.ini.php';

    public static $FTPConnParams = [
            'host' => null,
            'user' => null,
            'password' => null,
            'workdir' => null
        ];

    public static $Paths = [
            'storage' => null
        ];

    public static $isLocalStorage = true;

    public function __construct()
    {
        try {

            $this->getConfigFromFileINI();

        } catch (\Exception $ex) {

            die( $ex->getMessage() );
        }

    }

    public static function getInstance() {
        if (is_null(self::$instance)) self::$instance = new config();
        return self::$instance;
    }


    private function getConfigFromFileINI()
    {

        $config = [];

        if (file_exists(self::configMainFilePath)) {
            $config = @parse_ini_file(self::configMainFilePath, true);
        } else {
            if (file_exists(self::configDefaultFilePath)) {
                $config = @parse_ini_file(self::configDefaultFilePath, true);
            } else {
                throw new \Exception("Not found configuration file");
            }
        }

        # Paths
        self::$Paths = array_map(
                function($path) {
                    return __DIR__ . '/../' . $path;
                },
                $config['paths'] ?? []
            );

        # FTP connection params
        self::$FTPConnParams = $config['ftp'] ?? [];

        # is Local Storage?
        self::$isLocalStorage = $config['app']['localstorage'] ?? true;
    }


}