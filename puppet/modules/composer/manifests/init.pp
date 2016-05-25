# puppet/modules/composer/manifests/init.pp
class composer{

    exec { "download-composer":
        command     => "/usr/bin/curl -sS https://getcomposer.org/installer | /usr/bin/php",
        environment => ["COMPOSER_HOME=/home/vagrant/.composer"],
        cwd         => "/usr/local/src/",
        creates     => "//usr/local/src/composer.phar",
        require     => Class['php'],
    }

    exec { "install-composer":
        command => "/bin/cp composer.phar /usr/bin/composer",
        cwd     => "/usr/local/src",
        creates => "/usr/bin/composer",
        require => Exec ["download-composer"]
    }

    file { '/home/vagrant/.composer':
      ensure => 'directory',
      owner  => 'vagrant',
      group  => 'vagrant'
    }
}