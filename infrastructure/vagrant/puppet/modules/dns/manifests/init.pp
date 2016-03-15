# puppet/manifests/modules/dns/init.pp

class dns {
    
    package { 'dnsmasq' :
        name => 'dnsmasq',
        ensure => present,
        require => Exec['apt-get update'],
    }

    # Make sure that the dns service is running
    service { 'dnsmasq':
        ensure => running,
        require => Package['dnsmasq'],
    }

    # Add dnsmasq conf
    file { 'etc-dnsmasq-conf':
        path => '/etc/dnsmasq.conf',
        ensure => file,
        group => 'root',
        owner => 'root',
        mode => '0644',
        content => template('dns/dnsmasq.conf.erb'),        
        require => Package['dnsmasq'],
        notify  => Service['dnsmasq']
    }

}