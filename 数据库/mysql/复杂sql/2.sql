SET @dbname = "my_test";
SET @tablename = "t_ssq";
SET @columnname = "red1";

-- 查询 my_test库 t_ssq 表的red 字段是否存在
/*
SELECT * FROM INFORMATION_SCHEMA.COLUMNS
            WHERE
            (table_name = @tablename)
            AND (table_schema = @dbname)
            AND (column_name = @columnname)
*/


-- 如果第一个表达式结果大于0 则证明column_name 对应的字段存在，此变量等于第二哥表达式，当column_name 对应的字段不存在时
-- 打一个表达式等于0，则变量等于第三个表达式。
SET @preparedStatement = (SELECT IF(
        (
            SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
            WHERE
            (table_name = @tablename)
            AND (table_schema = @dbname)
            AND (column_name = @columnname)
        ) > 0,
        "SELECT 1",
        CONCAT("ALTER TABLE ", @tablename, " ADD ", @columnname, " text comment '附件' AFTER red;")
        )
);

-- 打印出变量
select @preparedStatement;

-- PREPARE语句准备好一条SQL语句，并分配给这条SQL语句一个名字
PREPARE alterIfNotExists FROM @preparedStatement;

-- 通过EXECUTE命令执行
EXECUTE alterIfNotExists;

-- 通过DEALLOCATE PREPARE命令释放
DEALLOCATE PREPARE alterIfNotExists;

