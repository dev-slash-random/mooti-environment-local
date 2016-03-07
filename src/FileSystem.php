<?php
namespace Mooti\Base\Core;
    
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
            throw new FileNotFoundException('File '.$filePath.' was not found', 1);
        }
        return file_get_contents($filePath);
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
