# puppet/modules/mysql/manifests/gearman-server/init.pp
class gearman-server {
    
    package { ['gearman-tools']:
        ensure => present,
        require => Exec['apt-get update'],
    }

    # Install the gearman server
    package { ['gearman-job-server']:
        ensure => present,
        require => Exec['apt-get update'],
    }
}
