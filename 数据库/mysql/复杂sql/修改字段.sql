ALTER TABLE `unified_app`
    CHANGE `screenshot` `screenshot` VARCHAR(255)
    CHARACTER SET utf8  COLLATE utf8_general_ci  NULL  DEFAULT  COMMENT '应用截图';



-- 只修改字段备注
alter table t_cal_res_channel  MODIFY  column resume_channel varchar(30) COMMENT  '渠道'



