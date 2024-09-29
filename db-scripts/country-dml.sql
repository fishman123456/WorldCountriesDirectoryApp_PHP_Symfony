-- заполнение БД
USE country_db_pv225;

-- удалить данные
TRUNCATE TABLE country_t;

-- добавить данные
--id INT NOT NULL AUTO_INCREMENT,
--shortName_f NVARCHAR(200) NOT NULL,
--fullName_f NVARCHAR(200) NOT NULL,
--isoAlpha2_f NVARCHAR(2) NOT NULL,
--isoAlpha3_f NVARCHAR(3) NOT NULL,
--isoNumeric_f  NVARCHAR(3) NOT NULL,
--population_f INT NOT NULL,
--square_f INT NOT NULL,
--1
INSERT INTO
    country_t (
        shortName_f,
        fullName_f,
        isoAlpha2_f,
        isoAlpha3_f,
        isoNumeric_f,
        population_f,
        square_f
    )
VALUES
    (
        'Абхазия',
        'Республика Абхазия',
        'AB',
        'ABH',
        '895',
        243564,
        8665
    )
INSERT INTO
    country_t (
        shortName_f,
        fullName_f,
        isoAlpha2_f,
        isoAlpha3_f,
        isoNumeric_f,
        population_f,
        square_f
    )
VALUES
    (
        'Беларусь',
        'Республика Беларусь',
        'BY',
        'BLR',
        '112',
        9155978,
        207600
    )
INSERT INTO
    country_t (
        shortName_f,
        fullName_f,
        isoAlpha2_f,
        isoAlpha3_f,
        isoNumeric_f,
        population_f,
        square_f
    )
VALUES
    (
        'Казахстан',
        'Республика Казахстан',
        'KA',
        'KAZ',
        '398',
        19196000,
        2724900
    )
INSERT INTO
    country_t (
        shortName_f,
        fullName_f,
        isoAlpha2_f,
        isoAlpha3_f,
        isoNumeric_f,
        population_f,
        square_f
    )
VALUES
    (
        'Китай',
        'Китайская Народная Республика',
        'CN',
        'CHN',
        '156',
        1443497378,
        9596960
    )
INSERT INTO
    country_t (
        shortName_f,
        fullName_f,
        isoAlpha2_f,
        isoAlpha3_f,
        isoNumeric_f,
        population_f,
        square_f
    )
VALUES
    (
        'Кувейт',
        'Государство Кувейт',
        'KW',
        'KWT',
        '414',
        3400000,
        17800
    )
INSERT INTO
    country_t (
        shortName_f,
        fullName_f,
        isoAlpha2_f,
        isoAlpha3_f,
        isoNumeric_f,
        population_f,
        square_f
    )
VALUES
    (
        'Азербайджан',
        'Азербайджаанская Республика',
        'AZ',
        'AZE',
        '031',
        10133078,
        86600
    ) -- получим данные
SELECT
    *
FROM
    country_t;