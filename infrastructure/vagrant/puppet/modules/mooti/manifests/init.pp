# puppet/manifests/modules/mooti/init.pp

class mooti {

    file { 'mooti-config-ini':
        path => '/etc/mooti/config.ini',
        ensure => file,
        require => File['/etc/mooti'],
        source => 'puppet:///modules/mooti/config.ini',
    }

    # Add a vhost template
    file { 'vagrant-mooti-apachi-conf':
        path => '/etc/apache2/sites-available/service.dev.mooti.local.conf',
        ensure => file,
        require => Package['apache2'],
        source => 'puppet:///modules/mooti/service.mooti.apache.conf',
        notify => Service['apache2']
    }

    # Symlink our vhost in sites-enabled to enable it
    file { 'vagrant-apache-mooti-enable':
        path => '/etc/apache2/sites-enabled/service.dev.mooti.local.conf',
        target => '/etc/apache2/sites-available/service.dev.mooti.local.conf',
        ensure => link,
        notify => Service['apache2'],
        require => [
            File['vagrant-mooti-apachi-conf']
        ],
    }
}