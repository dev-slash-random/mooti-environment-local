# puppet/modules/mysql/manifests/redis-server/init.pp
class redis-server {
    
    package { ['redis-tools']:
        ensure => present,
        require => Exec['apt-get update'],
    }

    # Install the redis server
    package { ['redis-server']:
        ensure => present,
        require => Exec['apt-get update'],
    }
}
