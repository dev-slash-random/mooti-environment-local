<?php
namespace Mooti\Base\Core;
    
class Git
{

    public function cloneRepo($url, $path)
    {
        return shell_exec('git clone '.$url.' '.$path);
    }

    public function pull()
    {
        return shell_exec('git pull');
    }
}
