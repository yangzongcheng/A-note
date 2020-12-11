###删除集群节点

```text

排空节点上的集群容器 。
docker node update --availability drain g36lvv23ypjd8v7ovlst2n3yt

主动离开集群，让节点处于down状态，才能删除
docker swarm leave

删除指定节点 （管理节点上操作）
docker node rm g36lvv23ypjd8v7ovlst2n3yt

管理节点，解散集群
docker swarm leave --force

将manager角色降级为worker
docker node demote NODEID


将worker角色升级为manager
docker node promote NODEID


删除manager
先降级在离线 在从manager删除



```