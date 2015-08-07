# puppet/manifests/modules/apache/init.pp

class apache {
    
    package { 'apache2' :
        name => 'apache2',
        ensure => present,
        require => Exec['apt-get update'],
    }

    package { 'apache2-utils' :
        name => 'apache2-utils',
        ensure => present,
        require => Package['apache2'],
    }

    # Make sure that the apache service is running
    service { 'apache2':
        ensure => running,
        require => Package['apache2'],
    }

    exec { "enable-mod-rewrite" :
        command => "/usr/sbin/a2enmod rewrite",
        unless => "/bin/readlink -e /etc/apache2/mods-enabled/rewrite.load",
        notify => Service['apache2'],
        require => Package['apache2-utils']
    }

    exec { "enable-mod-vhost-alias" :
        command => "/usr/sbin/a2enmod vhost_alias",
        unless => "/bin/readlink -e /etc/apache2/mods-enabled/vhost_alias.load",
        notify => Service['apache2'],
        require => Package['apache2-utils']
    }

    # Disable the default apache vhost
    file { 'default-apache-disable':
        path => '/etc/apache2/sites-enabled/000-default.conf',
        ensure => absent,
        require => Package['apache2'],
        notify  => Service["apache2"]
    }
}