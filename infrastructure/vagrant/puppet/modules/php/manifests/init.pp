# puppet/modules/php/manifests/init.pp
class php {

    package { ['libapache2-mod-php5', 'php5-cli', 'php5-dev']:
        ensure => '5.6.*',
        require => Exec['apt-get update'],
    }

    package { ['php5-curl', 'php-pear', 'php5-mysqlnd', 'php5-mcrypt']:
        ensure => present,
        require => Package['libapache2-mod-php5'],
    }

    exec { "enable-php-mod-mcrypt" :
        command => "/usr/sbin/php5enmod mcrypt",
        unless => "/bin/readlink -e /etc/php5/cli/conf.d/20-mcrypt.ini",
        notify => Service['apache2'],
        require => [
            Package['apache2-utils'],
            Package['php5-mcrypt']
        ]
    }
}