-- 存储过程
-- 判断唯一组合索引是否存在 不存在则创建

DROP PROCEDURE IF EXISTS schema_change;
DELIMITER //
CREATE PROCEDURE schema_change() BEGIN
DECLARE  CurrentDatabase VARCHAR(100);
SELECT DATABASE() INTO CurrentDatabase;
IF NOT EXISTS (SELECT * FROM information_schema.statistics WHERE table_schema=CurrentDatabase AND table_name = 'report_card_census' AND index_name = 'unique_index') THEN
alter table report_card_census add unique unique_index(provider_id,provider_campus_id,third_no);
END IF;
END//
DELIMITER ;
CALL schema_change(); -- 执行存储过程
DROP PROCEDURE schema_change; --删除存储过程
