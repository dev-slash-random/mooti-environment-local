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

    exec { "disable-mod-mpm_event" :
        command => "/usr/sbin/a2dismod mpm_event",
        onlyif => "/bin/readlink -e /etc/apache2/mods-enabled/mpm_event.load",
        require => Package['apache2-utils']
    }

    exec { "enable-mod-mpm_prefork" :
        command => "/usr/sbin/a2enmod mpm_prefork",
        unless => "/bin/readlink -e /etc/apache2/mods-enabled/mpm_prefork.load",
        notify => Service['apache2'],
        require => [
            Package['apache2-utils'],
            Exec['disable-mod-mpm_event']
        ]
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