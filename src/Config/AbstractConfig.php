<?php
namespace Mooti\Platform\Config;
    
use Mooti\Platform\Util\FileSystem;
use Mooti\Framework\Framework;
use Mooti\Platform\Exception\DataValidationException;
use Mooti\Validator\Validator;

abstract class AbstractConfig
{
    use Framework;

    protected $filename;
    protected $dirPath;
    protected $rules = [];
    protected $config = [];

    abstract public function init();

    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    public function setDirPath($dirPath)
    {
        $this->dirPath = $dirPath;
    }

    public function getFilepath()
    {
        if (isset($this->dirPath)) {
            $filePath = $this->dirPath .'/'.$this->filename;
        } else {
            $fileSystem = $this->createNew(FileSystem::class);
            $filePath   = $fileSystem->getCurrentWorkingDirectory() .'/'.$this->filename;    
        }
        return $filePath;        
    }    

    public function validateConfig()
    {
        $validator = $this->createNew(Validator::class);
        
        if ($validator->isValid($this->rules, $this->config) == false) {
            throw new DataValidationException('The config is invalid: ' . print_r($validator->getErrors(), 1));
        }
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function open()
    {
        $fileSystem = $this->createNew(FileSystem::class);

        $filePath = $this->getFilepath();

        $contents = $fileSystem->fileGetContents($filePath);

        $this->config = json_decode($contents, true);

        if (isset($this->config) == false) {
            throw new MalformedDataException('The contents of the file "'.$filePath.'" are not valid json');
        }
        $this->validateConfig();
    }

    public function save()
    {
        $filePath = $this->getFilepath();
        $this->validateConfig();
        $fileSystem = $this->createNew(FileSystem::class);
        $fileSystem->filePutContents($filePath, json_encode($this->platformConfig, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}
