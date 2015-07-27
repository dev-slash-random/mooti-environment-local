# puppet/manifests/modules/mooti-web-modules/init.pp

class mooti-web-modules {

    file { 'mooti-config-ini':
        path => '/etc/mooti/config.ini',
        ensure => file,
        require => File['/etc/mooti'],
        source => 'puppet:///modules/mooti-web-modules/config.ini',
    }

    file { '/home/vagrant/apps/modules':
        ensure  => 'link',
        target  => '/vagrant/apps/modules',
    }

    # Add a vhost template
    file { 'vagrant-mooti-apachi-conf':
        path => '/etc/apache2/sites-available/module.dev.mooti.com.conf',
        ensure => file,
        require => Package['apache2'],
        source => 'puppet:///modules/mooti-web-modules/module.mooti.apache.conf',
    }

    # Symlink our vhost in sites-enabled to enable it
    file { 'vagrant-apache-mooti-enable':
        path => '/etc/apache2/sites-enabled/module.dev.mooti.com.conf',
        target => '/etc/apache2/sites-available/module.dev.mooti.com.conf',
        ensure => link,
        notify => Service['apache2'],
        require => [
            File['vagrant-mooti-apachi-conf']
        ],
    }
}