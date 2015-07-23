# puppet/manifests/modules/mooti-web-services/init.pp

class mooti-web-services {

    file { 'mooti-config-ini':
        path => '/etc/mooti/config.ini',
        ensure => file,
        require => File['/etc/mooti'],
        source => 'puppet:///modules/mooti-web-services/config.ini',
    }

    file { '/home/vagrant/apps/services':
        ensure  => 'link',
        target  => '/vagrant/apps/services',
    }

    # Add a vhost template
    file { 'vagrant-mooti-apachi-conf':
        path => '/etc/apache2/sites-available/service.dev.mooti.com.conf',
        ensure => file,
        require => Package['apache2'],
        source => 'puppet:///modules/mooti-web-services/service.mooti.apache.conf',
    }

    # Symlink our vhost in sites-enabled to enable it
    file { 'vagrant-apache-mooti-enable':
        path => '/etc/apache2/sites-enabled/service.dev.mooti.com.conf',
        target => '/etc/apache2/sites-available/service.dev.mooti.com.conf',
        ensure => link,
        notify => Service['apache2'],
        require => [
            File['vagrant-mooti-apachi-conf']
        ],
    }
}