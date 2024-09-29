<?php

namespace App\Model;

// CountryRepository - интерфейс хранилища стран

interface CountryRepository
{

    // getAll - получение всех стран
    function getAll(): array;

    // get - получить страну по коду
    function get(string $code): ?Country;

    // story - сохранение страны в БД
    function story(Country $country): void;

    // edit - редактирование страны по коду
    function edit(string $code, Country $country): void;

    // delete - удаление страны по коду
    function delete(string $code): void;
}
