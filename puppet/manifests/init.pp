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

package { ['build-essential', 'libssl-dev', 'libv8-3.14-dev', 'zip', 'unzip']:
    ensure => present,
    require => Exec['apt-get update'],
}

file { 'etc-mooti':
	path => '/etc/mooti',
	ensure => 'directory',
}

file { 'opt-mooti':
	path => '/opt/mooti',
	ensure => 'directory',
}

include mooti, dns, apache, php, composer, install-mysql, redis-server