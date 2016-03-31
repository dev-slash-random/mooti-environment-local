<?php
namespace Mooti\Platform\Config;
    
use Mooti\Platform\Util\FileSystem;
use Mooti\Framework\Framework;
use Mooti\Framework\ServiceProvider;
use Mooti\Platform\Exception\DataValidationException;
use Mooti\Validator\Validator;

class MootiConfig extends AbstractConfig
{
    const FILENAME = 'mooti.json';

    protected $rules = [
        'name' => [
            'required' => true,
            'type'     => 'string'
        ],
        'server' => [
            'required' => false,
            'type'     => 'object',
            'properties' => [
                'type' => [
                    'required' => 'true',
                    'type'     => 'string'
                ],
                'document_root' => [
                    'required' => 'true',
                    'type'     => 'string'
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
            'name' => 'mooti.example'
        ];
    }
}
