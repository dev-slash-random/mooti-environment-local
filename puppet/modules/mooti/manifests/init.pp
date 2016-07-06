# puppet/manifests/modules/mooti/init.pp

class mooti {

    # Add mooti ini file
    file { 'etc-mooti-mooti-ini':
        path => '/etc/mooti/mooti.ini',
        ensure => file,
        group => 'root',
        owner => 'root',
        mode => '0644',
        source => 'puppet:///modules/mooti/mooti.ini',
        require => File['etc-mooti']
    }

    # Add mooti install script
    file { 'opt-mooti-mooti-install':
        path => '/opt/mooti/mooti-intsall.sh',
        ensure => file,
        group => 'root',
        owner => 'root',
        mode => '0755',
        source => 'puppet:///modules/mooti/mooti-intsall.sh',
        require => File['opt-mooti']
    }

    exec { "install-mooti-admin":
        command     => "/mooti/platform/puppet/modules/mooti/files/mooti-intsall.sh 0.0.4",
        cwd         => "/opt/mooti/",
        require     => [
            Class['php'],
            File['opt-mooti-mooti-install']
        ]
    }

    exec { "create-mooti-admin-link" :
        command => "/bin/ln -s /opt/mooti/mooti-platform-admin/bin/mooti-platform /usr/local/bin/mooti-platform-admin",
        unless => "/bin/readlink -e /usr/local/bin/mooti-platform-admin",
        require => Exec['install-mooti-admin']
    }
}