<?php
namespace Mooti\Base\Core;
    
use Mooti\Base\Core\FileSystem;

class MootiConfig extends \ArrayObject
{
    private $filePath;

    private $validations = [
        'main' => [
            'puppet_config' => 'required',
            'repositories'  => 'required'
        ],
        'puppet_config' => [
            'apache_domain'    => 'required|alpha_dash',
            'root_db_password' => 'required'
        ],
        'repository' => [
            'name' => 'required|alpha_dash',
            'url'  => 'required',
            'type' => 'required|alpha_dash'
        ]        
    ];

    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;
    }

    public function validateConfig(array $config)
    {
        $validator = $this->get(ServiceProvider::VALIDATOR);

        $validatedData = $validator->run($config, $this->validations);
        if ($validatedData === false) {
            throw new DataValidationException('The mooti config is invalid: ', $this->get_readable_errors());
        }

        $validatedData = $validator->run($config['puppet_config'], $validations['puppet_config']);
        if ($validatedData === false) {
            throw new DataValidationException('The mooti config is invalid for the puppet_config item: ', $this->get_readable_errors(true));
        }        

        if (!is_array($config['repositories'])) {
            throw new DataValidationException('The mooti config is invalid', ['The key repositories needs to be an array']);
        }

        foreach ($config['repositories'] as $repository) {
            $validatedData = $validator->run($repository, $validations['repository']);
            $validatedData = $validator->run($config, $validations);
            if ($validatedData === false) {
                throw new DataValidationException('The mooti config is invalid for the repository item: ', $this->get_readable_errors(true));
            }
            if (isset($repository['alias_domains']) && !is_array($repository['alias_domains'])) {
                throw new DataValidationException('The mooti config is invalid for the repository item: ', ['The key alias_domains needs to be an array']);
            }
        }
    }

    public function init()
    {
        $mootiConfig = [
            'puppet_config' => [ 
                'apache_domain'    => 'dev.local',
                'root_db_password' => 'mooti3465Xi'
            ],
            'repositories' => []
        ];
        $this->exchangeArray($mootiConfig);
    }

    public function open()
    {
        $fileSystem = $this->createNew(FileSystem::class);

        $contents = $fileSystem->fileGetContents($this->filePath);

        $mootiConfig = json_decode($contents, true);

        if (isset($mootiConfig) == false) {
            throw new MalformedDataException('The contents of the file "'.$this->filePath.'" are not valid json');
        }

        $this->validateConfig($mootiConfig);
        $this->exchangeArray($mootiConfig);
    }

    public function save()
    {
        $mootiConfig = $this->getArrayCopy();
        $this->validateConfig($mootiConfig);
        $fileSystem = $this->createNew(FileSystem::class);
        $fileSystem->filePutContents($this->filePath, json_encode($mootiConfig, JSON_PRETTY_PRINT));
    }
}
