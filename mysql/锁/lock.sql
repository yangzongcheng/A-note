Lock TABLE t_user;

SELECT * from t_user  where  id >1 FOR UPDATE ;
UPDATE t_user set name='kk' where id = 2

INSERT INTO t_user VALUE(null,'李四');


-- MyISAM存储引擎只支持表锁

-- 针对 MyISAM
lock tables t_order read; -- 读锁 只能查询 不能insert  update delete
-- 如果表被加上了读锁 执行写操作会提示错误
select * from t_order;

INSERT INTO t_order VALUE(null,300);
DELETE from t_order where id=1;
UPDATE t_order set uid=20 where id = 2
UNLOCK tables;-- 解锁

lock tables t_order WRITE; -- 写锁  其他会话不能执行select insert  update delete
-- 如果表被加上写锁  其他执行读/写操作会阻塞等待 直接当前会话解锁表  ，当前会话可以操作任何操作

select * from t_order;
UNLOCK tables;-- 解锁


-- 行锁 innodb    行锁分为共享锁（读锁）和排他锁（写锁）
-- 1、共享锁又叫做读锁，所有的事务只能对其进行读操作不能写操作，加上共享锁后在事务结束之前其他事务只能再加共享锁，除此之外其他任何类型的锁都不能再加了
--     用法：SELECT `id` FROM  table WHERE　id in(1,2) LOCK IN SHARE MODE 结果集的数据都会加共享锁

-- 2、若某个事物对某一行加上了排他锁，只能这个事务对其进行读写，在此事务结束之前，其他事务不能对其进行加任何锁，其他进程可以读取,不能进行写操作，需等待其释放。
-- SELECT `id` FROM mk_user WHERE id=1 FOR UPDATE 注意：由于在innodb中行锁，他是针对索引去锁定该条数据，而不是直接锁定该条数据的。
-- 另外，在行锁中，如果没有设置索引，InnoDB只能使用表锁。

-- 在innodb中行锁，他是针对索引去锁定该条数据，而不是直接锁定该条数据的。