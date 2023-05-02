
-- 判断索引是否存在不存在则创建
SET @dbname = "school_dashboard";
SET @tablename = "report_card_census";
SET @index_name = "unique_index";


SET @preparedStatement = (SELECT IF(
        (
            SELECT COUNT(*) FROM  INFORMATION_SCHEMA.STATISTICS
            WHERE
            (table_name = @tablename)
            AND (table_schema = @dbname)
            AND (index_name = @index_name)
        ) > 0,
        "SELECT 1",
         CONCAT("alter table ", @tablename, " add unique unique_index(provider_id,provider_campus_id,third_no)",";")
        )
);

select @preparedStatement;

PREPARE alterIfNotExists FROM @preparedStatement;

EXECUTE alterIfNotExists;

DEALLOCATE PREPARE alterIfNotExists;