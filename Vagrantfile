# -*- mode: ruby -*-
# vi: set ft=ruby :

# Vagrantfile API/syntax version. Don't touch unless you know what you're doing!
VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|

  config.vm.box = "ubuntu/trusty32"
  config.vm.network :private_network, ip: "192.168.33.111"

  config.vm.provider :virtualbox do |vb|
    vb.memory = 2048
    vb.cpus   = 1
  end

  config.vm.provision :shell do |shell|
    shell.inline = "
        mkdir -p /etc/puppet/modules;
        (puppet module list | grep puppetlabs-mysql) ||
          puppet module install puppetlabs/mysql
        apt-get update && apt-get install -y python-software-properties;
        (ls /etc/apt/sources.list.d/ | grep brightbox-ruby-ng-trusty.list) || 
          apt-add-repository -y ppa:ondrej/php5-5.6;
        apt-get update;
      "
  end

  config.vm.provision :shell, :inline => "echo -e '#{File.read("#{Dir.home}/.ssh/id_rsa")}' > '/home/vagrant/.ssh/id_rsa'"

  config.vm.provision :puppet do |puppet|
    puppet.manifests_path = "puppet/manifests"
    puppet.manifest_file  = "init.pp"
    puppet.module_path = "puppet/modules"
  end

end
