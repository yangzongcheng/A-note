
-- 根据条件更新
UPDATE
    year_admin.year_user a
SET
    image_url = (
        SELECT
            concat(employee_id, '.png')
        FROM
            tanfony_erp.users b
        WHERE
                id = a.id
    )



-- 字段替换更新
UPDATE
    db_user_account
SET
    account = REPLACE(account, '@100', '@200')
WHERE
        account LIKE "%@100"
  AND type = 'mail';