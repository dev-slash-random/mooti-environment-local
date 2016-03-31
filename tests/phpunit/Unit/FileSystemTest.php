<?php

namespace Mooti\Test\Unit\System\Base;
 
use Mooti\System\Base\Util\FileSystem;
use Mooti\System\Base\Exception\FileSystemException;

class FileSystemTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function getCurrentWorkingDirectorySucceeds()
    {
        $fileSystem = new FileSystem;
        self::assertEquals(getcwd(), $fileSystem->getCurrentWorkingDirectory());
    }

    /**
     * @test
     */
    public function fileExistsReturnsTrue()
    {
        $fileSystem = new FileSystem;
        self::assertTrue($fileSystem->fileExists(__FILE__));
    }

    /**
     * @test
     */
    public function fileExistsReturnsFalse()
    {
        $fileSystem = new FileSystem;
        self::assertFalse($fileSystem->fileExists('/foo/bar'));
    }

    /**
     * @test
     */
    public function fileGetContentsSucceeds()
    {
        $fileSystem = new FileSystem;
        self::assertEquals('bar', $fileSystem->fileGetContents(__DIR__.'/Fixture/foo.txt'));
    }

    /**
     * @test
     * @expectedException Mooti\System\Base\Exception\FileSystemException
     */
    public function fileGetContentsThrowsFileSystemException()
    {
        $fileSystem = new FileSystem;
        $fileSystem->fileGetContents('/foo/bar');
    }

    /**
     * @test
     */
    public function filePutContentsSucceeds()
    {
        $contents = uniqid();
        $path     = '/tmp/'.uniqid().'.txt';

        $fileSystem = new FileSystem;
        $fileSystem->filePutContents($path, $contents);
        self::assertEquals($contents, $fileSystem->fileGetContents($path));
        unlink($path);
    }

    /**
     * @test
     * @expectedException Mooti\System\Base\Exception\FileSystemException
     */
    public function filePutContentsThrowsFileSystemException()
    {
        $fileSystem = new FileSystem;
        $fileSystem->filePutContents('/foo/bar', 'test');
    }

    /**
     * @test
     */
    public function createDirectorySucceeds()
    {
        $path = '/tmp/'.uniqid();

        $fileSystem = new FileSystem;
        $fileSystem->createDirectory($path);
        self::assertTrue(is_dir($path));
        rmdir($path);
    }

    /**
     * @test
     * @expectedException Mooti\System\Base\Exception\FileSystemException
     */
    public function createDirectoryThrowsFileSystemException()
    {
        $path = '/'.uniqid();
        $fileSystem = new FileSystem;
        $fileSystem->createDirectory($path);
        rmdir($path);
    }

    /**
     * @test
     */
    public function changeDirectorySucceeds()
    {
        $oldPath = __DIR__;
        $path    = __DIR__.'/Fixture';
        $fileSystem = new FileSystem;
        $fileSystem->changeDirectory($path);
        self::assertEquals($path, $fileSystem->getCurrentWorkingDirectory());
        self::assertNotEquals($oldPath, $fileSystem->getCurrentWorkingDirectory());
    }

}
