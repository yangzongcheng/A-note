-- 删除字段 num
-- 新增 start_at end_at 字段

SET @dbname = "school_dashboard";
SET @tablename = "report_card_census_subject";
SET @columnname = "num";
SET @columnnameStartAt = "start_at";
SET @columnnameEndAt = "end_at";


SET @preparedStatement = (SELECT IF(
        (
            SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
            WHERE
            (table_name = @tablename)
            AND (table_schema = @dbname)
            AND (column_name = @columnname)
        ) > 0,
        CONCAT("ALTER TABLE ", @tablename, " DROP COLUMN ", @columnname, ";"),
        "SELECT 1"
        )
);
select @preparedStatement;
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;






SET @preparedStatementStartAt = (SELECT IF(
        (
            SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
            WHERE
            (table_name = @tablename)
            AND (table_schema = @dbname)
            AND (column_name = @columnnameStartAt)
        ) > 0,
        "SELECT 1",
  CONCAT("ALTER TABLE ", @tablename, " ADD ", @columnnameStartAt, " bigint(20) NOT NULL DEFAULT 0 COMMENT '考试开始时间' AFTER education_type;")
        )
);
select @preparedStatementStartAt;
PREPARE alterIfNotExists FROM @preparedStatementStartAt;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;




SET @preparedStatementEndAt = (SELECT IF(
        (
            SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
            WHERE
            (table_name = @tablename)
            AND (table_schema = @dbname)
            AND (column_name = @columnnameEndAt)
        ) > 0,
        "SELECT 1",
  CONCAT("ALTER TABLE ", @tablename, " ADD ", @columnnameEndAt, " bigint(20) NOT NULL DEFAULT 0 COMMENT '考试结束时间' AFTER education_type;")
        )
);
select @preparedStatementEndAt;
PREPARE alterIfNotExists FROM @preparedStatementEndAt;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;



