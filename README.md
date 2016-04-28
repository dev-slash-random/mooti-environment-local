# mooti-environment-local
Local Vagrant box for Mooti

{
    "require": {
        "php": ">=5.5.9"        
    },    
    "config": {
        "bin-dir": "bin/vendor"
    }
}


composer require mooti/platform

./bin/vendor/mooti-platform

.gitignore

/bin/vendor/
/vendor/
/synced-folder/