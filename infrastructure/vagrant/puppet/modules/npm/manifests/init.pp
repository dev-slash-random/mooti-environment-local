# puppet/modules/npm/manifests/init.pp
class npm {

    # Install npm related packages
    package { ['nodejs', 'nodejs-legacy', 'npm']:
        ensure => present,
        require => Exec['apt-get update']
    }

    exec { "npm-install-bower" :
        command => "/usr/bin/npm install -g bower",
        unless => "/bin/readlink -e /usr/local/lib/node_modules/bower",        
        require => [
            Package['nodejs'],
            Package['nodejs-legacy'],
            Package['npm']
        ]
    }

    exec { "npm-install-grunt-cli" :
        command => "/usr/bin/npm install -g grunt-cli",
        unless => "/bin/readlink -e /usr/local/lib/node_modules/grunt-cli",        
        require => [
            Package['nodejs'],
            Package['nodejs-legacy'],
            Package['npm']
        ]
    }
}