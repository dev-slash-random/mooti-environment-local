<?php
namespace Mooti\Base\Core\Util;

use Mooti\Base\Core\Exception\FileSystemException;

class FileSystem
{
    public function getCurrentWorkingDirectory()
    {
        return getcwd();
    }

    public function fileExists($filePath)
    {
        return file_exists($filePath);
    }

    public function fileGetContents($filePath)
    {
        if ($this->fileExists($filePath) == false) {
            throw new FileSystemException('File '.$filePath.' was not found');
        }
        return file_get_contents($filePath);
    }

    public function filePutContents($filePath, $data)
    {
        $bytesWritten = file_put_contents($filePath, $data);
        if ($bytesWritten === false) {
            throw new FileSystemException('File '.$filePath.' could not be written');
        }
        return true;
    }

    public function createDirectory($directoryPath)
    {
        return mkdir($directoryPath, 0775, true);
    }

    public function changeDirectory($path)
    {
        return chdir($path);
    }
}
