ALTER TABLE `unified_app`
    CHANGE `screenshot` `screenshot` VARCHAR(255)
    CHARACTER SET utf8  COLLATE utf8_general_ci  NULL  DEFAULT  COMMENT '应用截图';



-- 只修改字段备注
alter table t_cal_res_channel  MODIFY  column resume_channel varchar(30) COMMENT  '渠道'





ALTER TABLE wisdom.`fill_form_answer`
    CHANGE `answer` `answer` mediumtext
    CHARACTER SET utf8  COLLATE utf8_general_ci  NULL    COMMENT '答案';



ALTER TABLE wisdom.`fill_form_problem`
    CHANGE `content` `content` mediumtext
    CHARACTER SET utf8  COLLATE utf8_general_ci  NULL    COMMENT '问题内容';




