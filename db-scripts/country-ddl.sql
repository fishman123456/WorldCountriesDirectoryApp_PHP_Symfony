-- создание БД
DROP DATABASE IF EXISTS country_db_pv225;

CREATE DATABASE country_db_pv225;

-- переключение на данную БД
USE country_db_pv225;

-- создание таблицы аэропортов
CREATE TABLE country_t (
    id INT NOT NULL AUTO_INCREMENT,
    shortName_f NVARCHAR(200) NOT NULL,
    fullName_f NVARCHAR(200) NOT NULL,
    isoAlpha2_f NVARCHAR(2) NOT NULL,
    isoAlpha3_f NVARCHAR(3) NOT NULL,
    isoNumeric_f NVARCHAR(3) NOT NULL,
    population_f INT NOT NULL,
    square_f INT NOT NULL,
    --
    PRIMARY KEY(id),
    UNIQUE(isoAlpha2_f),
    UNIQUE(isoAlpha3_f)
);