<?php

namespace App\Model;

class Country
{
    public function __construct(
        public string $shortName,
        public string $fullName,
        public string $isoAlpha2,
        public string $isoAlpha3,
        public string $isoNumeric,
        public int $population,
        public int $square
    ) {}
}

//Поля класса (с примерами значений): 
//shortName - короткое наименование страны (Россия)
//fullName - полное наименование страны (Российская Федерация)
//isoAlpha2 - двухбуквенный код страны (RU) -- будем по коду искать
//isoAlpha3 - трехбуквенный код страны (RUS)
//isoNumeric - числовой код страны (643)
//population - население страны - кол-во человек (146 150 789)
//square - площадь страны кв. км. (17 125 191)
