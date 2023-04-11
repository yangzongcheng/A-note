
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