-- 智能填报

SET @dbname = "wisdom";
SET @themes_library_table = "fill_form";

-- 审核时间
SET @examine_at_field = "examine_at";



SET @realNamefieldhandle = (SELECT IF(
        (
            SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
            WHERE
            (table_name = @themes_library_table)
            AND (table_schema = @dbname)
            AND (column_name = @examine_at_field)
        ) > 0,
        "SELECT 1",
        CONCAT("ALTER TABLE ", @themes_library_table, " ADD ", @examine_at_field, " int(12) DEFAULT 0 COMMENT '最新审核时间'  AFTER examine;")

        )
);



select @realNamefieldhandle;
PREPARE alterIfNotExists FROM @realNamefieldhandle;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;
















