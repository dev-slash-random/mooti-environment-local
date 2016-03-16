<?php
namespace Mooti\Base\Core;
    
class Repository
{
    use Xizlr;

    private $name;
    private $url;
    private $type;

    const REPOSITORY_TYPE_MOOTI_APP      = 'mooti-app';
    const REPOSITORY_TYPE_MOOTI_SERVICE  = 'mooti-service';
    const REPOSITORY_TYPE_MOOTI_DATABASE = 'mooti-database';

    private $apacheTemplates = [
        self::REPOSITORY_TYPE_MOOTI_APP     => '../templates/apache/app.mooti.apache.conf.tpl',
        self::REPOSITORY_TYPE_MOOTI_SERVICE => '../templates/apache/service.mooti.apache.conf.tpl',
        self::REPOSITORY_TYPE_SYMFONY_APP   => '../templates/apache/app.symfony.apache.conf.tpl',
    ];

    private $directoryPaths = [
        'service'    => 'repositories/services',
        'database'   => 'repositories/databases',
        'app'        => 'repositories/apps',
        'apacheConf' => 'apache/conf/sites-available',
    ];

    public function setup($dir, $configData = array()) {
        $git = $this->createNew(Git::class);
        $fileSystem = $this->createNew(FileSystem::class);

        foreach ($this->directoryPaths as $directoryPath) {
            $fullDir = $dir.'/'.$directoryPath;
            if (!$fileSystem->fileExists($fullDir)) {
                $fileSystem->createDirectory($fullDir);
            }
        }

        $type_array = explode('-', $this->type);

        $repoPath = $dir.'/'.$this->directoryPaths[$type_array[1]].'/'.$type_array[0].'/'.$this->name;

        if (!$fileSystem->fileExists($repoPath)) {
            $git->cloneRepo($this->url, $repoPath);    
        }

        $git->pull();

        if (isset($this->apacheTemplates[$this->type])) {
            $templatePath = __DIR__ . '/' . $this->apacheTemplates[$this->type];
            $apacheConfPath = $dir.'/'.$this->directoryPaths['apacheConf'].'/'.$this->name.'.'.$type_array[1].'.'.$type_array[0].'.apache.conf';

            $contents = $this->renderTemplate($templatePath, $configData);
            $fileSystem->filePutContents($apacheConfPath);
        }
    }

    public function renderTemplate($data, $templatePath)
    {
        ob_start(null, 0, PHP_OUTPUT_HANDLER_CLEANABLE);
        require $templatePath;
        $contents = ob_get_clean();
        return $contents;
    }
}
