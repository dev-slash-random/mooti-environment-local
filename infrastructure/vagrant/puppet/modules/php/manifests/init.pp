# puppet/modules/php/manifests/init.pp
class php {
 
    package { ['php7.0', 'libapache2-mod-php7.0', 'php7.0-cli', 'php-dev']:
        require => Exec['apt-get update'],
    }

    package { ['php7.0-curl', 'php-pear', 'php7.0-mysql', 'php7.0-mcrypt']:
        ensure => present,
        require => Package['php7.0'],
    }

    exec { "enable-php-mod-mcrypt" :
        command => "/usr/sbin/phpenmod mcrypt",
        unless => "/bin/readlink -e /etc/php/7.0/cli/conf.d/20-mcrypt.ini",
        notify => Service['apache2'],
        require => [
            Package['apache2-utils'],
            Package['php7.0-mcrypt']
        ]
    }

    exec { "enable-mod-vhost-alias" :
        command => "/usr/sbin/a2enmod php7.0",
        unless => "/bin/readlink -e /etc/apache2/mods-enabled/php7.0.load",
        notify => Service['apache2'],
        require => [
            Package['apache2-utils'],
            Package['libapache2-mod-php7.0']
        ]
    }
}