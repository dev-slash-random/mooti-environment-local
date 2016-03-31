<?php
namespace Mooti\Platform\Config;
    
use Mooti\Platform\Util\FileSystem;
use Mooti\Framework\Framework;
use Mooti\Framework\ServiceProvider;
use Mooti\Platform\Exception\DataValidationException;
use Mooti\Validator\Validator;

class PlatformConfig extends AbstractConfig
{
    const FILENAME = 'platform.json';

    protected $rules = [
        'config' => [
            'required' => true,
            'type'     => 'object',
            'properties' => [
                'domain' => [
                    'required' => 'true',
                    'type'     => 'string'
                ],
                'db' => [
                    'required' => 'true',
                    'type'     => 'object',
                    'properties' => [
                        'password' => [
                            'required' => 'true',
                            'type'     => 'string'
                        ]
                    ]
                ]
            ]
        ],
        'repositories' => [
            'required' => true,
            'type'     => 'array',
            'items'    => [
                '*' => [
                    'type'       => 'object',
                    'properties' => [
                        'name' => [
                            'required' => true,
                            'type'     => 'string'
                        ],
                        'url' => [
                            'required' => true,
                            'type'     => 'string'
                        ]
                    ]
                ]
            ]
        ]
    ];

    public function __construct()
    {
        $this->filename = self::FILENAME;
    }

    public function init()
    {
        $this->config = [
            'config' => [ 
                'domain'    => 'dev.local',
                'db' => [
                    'password' => 'mooti3465Xi'
                ]
            ],
            'repositories' => []
        ];
    }

    public function addRepository($name, $url)
    {
        if (isset($this->config) == false) {
            throw new DataValidationException('Platform config has not been initialised');
        }

        foreach ($this->config['repositories'] as $repository) {
            if ($name == $repository['name']) {
                throw new DataValidationException('That repository already exists');
            }
        }
        $this->config['repositories'][] = [
            'name' => $name,
            'url'  => $url
        ];
    }

    public function removeRepository($name)
    {
        if (isset($this->config) == false) {
            throw new DataValidationException('Platform config has not been initialised');
        }

        $newRepositoryList = [];
        foreach ($this->config['repositories'] as $repository) {
            if ($name != $repository['name']) {
                $newRepositoryList[] = $repository;
            }
        }
        $this->config['repositories'] = $newRepositoryList;
    }
}
