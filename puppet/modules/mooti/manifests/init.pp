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

}