LOCK tables tp_admin read; --加锁

-- read:    加了read 锁 当前session不能 insert  不能 update（执行提示表已锁）,但可以select(只能select 加锁的表，其他的必须要关闭锁才能查) ,
--          其他的session可以select 但是insert update 时会排队，直到解锁才会结束排队执行sql  适合innodb myisam 引擎


-- write:   write 加了之后当前session对锁定表的查询、更新、插入(指针对锁的表，其他表不能操作)操作都可以执行，
--          其他session对锁定表的查询、更新、插入被阻塞， 需要等待锁被释放

-- LOCK tables tp_admin read local:     加了local 后其他的session 可以insert，但只能insert 不能update ，write 不能使用local

-- 只能锁一个表

-- 以上属于表锁


insert into tp_admin value(null,121231233);
update tp_admin set username = '00000' where id = 1;

Unlock tables; -- 解锁



-- 常用引擎支持锁类型
myisam :表锁
BDB    :表锁  页锁
innodb :表锁  行锁

开销 加锁速度 死锁 粒度 并发性能

表锁：   开销小，加锁快；不会出现死锁；锁定力度大，发生锁冲突概率高，并发度最低

行锁：   开销大，加锁慢；会出现死锁；锁定粒度小，发生锁冲突的概率低，并发度高

页锁：   开销和加锁速度介于表锁和行锁之间；会出现死锁；锁定粒度介于表锁和行锁之间，并发度一般
