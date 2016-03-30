<?php
/**
 * FileSystemException
 *
 * @package      System
 * @subpackage   Base
 * @author       Ken Lalobo <ken@mooti.io>
 */

namespace Mooti\System\Base\Util;

use Mooti\System\Base\Exception\FileSystemException;

class FileSystem
{
    /**
     * Gets the current working directory
     *     
     * @return string The current working directory
     */
    public function getCurrentWorkingDirectory()
    {
        return getcwd();
    }

    /**
     * Tells us wether a file exists
     *     
     * @return bool Wether it exists or not
     */
    public function fileExists($filePath)
    {
        return file_exists($filePath);
    }

    /**
     * Get the contents of a file
     *
     * @param string $filePath The path of the file
     *
     * @return string The contents of the file
     */
    public function fileGetContents($filePath)
    {
        if ($this->fileExists($filePath) == false) {
            throw new FileSystemException('File '.$filePath.' was not found');
        }
        return file_get_contents($filePath);
    }

    /**
     * write to a file
     *
     * @param string $filePath The path of the file
     * @param string $data     The contents of the file
     *
     * @return string The contents of the file
     */
    public function filePutContents($filePath, $data)
    {
        $bytesWritten = @file_put_contents($filePath, $data);
        if ($bytesWritten === false) {
            throw new FileSystemException('File '.$filePath.' could not be written');
        }
        return true;
    }

    /**
     * Create a directory
     *
     * @param string $directoryPath The path of the directory     
     *
     */
    public function createDirectory($directoryPath)
    {
        if (@mkdir($directoryPath, 0775, true) == false) {
            throw new FileSystemException('Directory '.$directoryPath.' could not be created');
        }
        return true;
    }

    /**
     * Change the current working directory
     *
     * @param string $directoryPath The path of the directory     
     *
     */
    public function changeDirectory($directoryPath)
    {
        return chdir($directoryPath);
    }
}
