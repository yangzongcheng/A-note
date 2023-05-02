-- 更新字段名rela_name 为 real_name
SET @dbname = "big_data";
SET @tablename = "auth_user";
SET @columnname = "real_name";


SET @preparedStatement = (SELECT IF(
        (
            SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
            WHERE
            (table_name = @tablename)
            AND (table_schema = @dbname)
            AND (column_name = 'rela_name')
        ) > 0,

         CONCAT("ALTER TABLE `auth_user` CHANGE `rela_name` `real_name` VARCHAR(255)    DEFAULT ''  COMMENT '姓名';"),
        "SELECT 1"
        )
);

select @preparedStatement;

PREPARE alterIfNotExists FROM @preparedStatement;

EXECUTE alterIfNotExists;

DEALLOCATE PREPARE alterIfNotExists;