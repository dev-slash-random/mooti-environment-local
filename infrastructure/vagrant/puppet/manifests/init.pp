# puppet/manifests/init.pp

exec { 'apt-get update':
  path => '/usr/bin',
}

package { 'vim':
  ensure => present,
}

package { 'git':
  ensure => present,
}

package { 'curl':
  ensure => present,
}

file { '/home/vagrant/apps':
  ensure => 'directory',
}

package { ['build-essential', 'libssl-dev', 'libv8-3.14-dev']:
    ensure => present,
    require => Exec['apt-get update'],
}

file { '/etc/mooti':
  ensure => 'directory',
}

include dns, apache, php, npm, install-mysql, composer, mooti-web-modules