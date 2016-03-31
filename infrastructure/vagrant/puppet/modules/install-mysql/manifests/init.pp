# puppet/modules/mysql/manifests/install-mysql/init.pp
class install-mysql {

    class { '::mysql::server':
        root_password => 'mooti3465Xi'
    }

    # Install the mysql-server
    package { ['mysql-client-5.5']:
        ensure => present,
        require => Exec['apt-get update'],
    }
}
