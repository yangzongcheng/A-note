###安装Runner

```shell script
# For Debian/Ubuntu/Mint
 curl -L https://packages.gitlab.com/install/repositories/runner/gitlab-runner/script.deb.sh | sudo bash

 # For RHEL/CentOS/Fedora
 curl -L https://packages.gitlab.com/install/repositories/runner/gitlab-runner/script.rpm.sh | sudo bash



# MacOS
sudo brew install gitlab-ci-multi-runner
 # For Debian/Ubuntu/Mint

 sudo apt-get install gitlab-ci-multi-runner

 # For RHEL/CentOS/Fedora
 sudo yum install gitlab-ci-multi-runner


```

###注册
```shell script
gitlab-runner register

```