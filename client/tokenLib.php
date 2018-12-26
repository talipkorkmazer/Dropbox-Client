<?php
namespace DropboxClient;
class tokenLib
{
    public function init()
    {
        error_reporting(E_ALL);
        $this->enableImplicitFlush();
        $this->mkdirDownload();
    }


    public function storeToken($token, $name)
    {
        is_dir('tokens') || mkdir('tokens');
        if (!file_put_contents("tokens/$name.token", serialize($token))) {
            die('<br />Could not store token! <b>Make sure that the directory `tokens` exists and is writable!</b>');
        }
    }

    public function mkdirDownload()
    {
        is_dir('download') || mkdir('download');
    }

    public function loadToken($name)
    {
        if (!file_exists("tokens/$name.token")) {
            return null;
        }

        return @unserialize(@file_get_contents("tokens/$name.token"));
    }

    public function tokenDelete($name)
    {
        @unlink("tokens/$name.token");
    }


    public function enableImplicitFlush()
    {
        if (function_exists('apache_setenv')) {
            @apache_setenv('no-gzip', 1);
        }
        @ini_set('zlib.output_compression', 0);
        @ini_set('implicit_flush', 1);
        for ($i = 0; $i < ob_get_level(); $i++) {
            ob_end_flush();
        }
        ob_implicit_flush(1);
    }

}
