<?php

namespace App\Rdb;

use App\Model\Country;
use App\Model\CountryRepository;
use App\Rdb\SqlHelper;
use mysqli;
use RuntimeException;
use DateTime;
use Exception;
use LengthException;

class CountryStorage implements CountryRepository
{
  public function __construct(
    private readonly SqlHelper $sqlHelper
  ) {}
  // getAll - получение всех стран
  function getAll(): array
  {
    try {
      // создать подключение к БД
      $connection = $this->sqlHelper->openDbConnection();
      // подготовить строку запроса
      $queryStr = '
          SELECT * 
          FROM country_t;';
      // выполнить запрос
      $rows = $connection->query(query: $queryStr);
      // считать результаты запроса в цикле 
      $countrys = [];
      while ($row = $rows->fetch_array()) {
        // каждая строка считается в тип массива
        $country = new Country(
          shortName: $row[1],
          fullName: $row[2],
          isoAlpha2: $row[3],
          isoAlpha3: $row[4],
          isoNumeric: $row[5],
          population: intval(value: $row[6]),
          square: intval(value: $row[7]),
        );
        array_push($countrys, $country);
      }
      // вернуть результат
      return $countrys;
    } finally {
      // echo'запрос на считывание выполнен';
      // в конце в любом случае закрыть соединение с БД если оно было открыто
      if (isset($connection)) {
        $connection->close();
      }
    }
  }

  // get - получить страну по коду
  function get(string $code): ?Country
  {
    //throw new Exception('not implemented');
    try {
      // создать подключение к БД
      $connection = $this->sqlHelper->openDbConnection();
      // подготовить строку запроса ищем по двухзначному коду и не цифры 00:27 20-09-2024
      if ($this->validateCodeTwoChar($code) === true) {
        $queryStr = 'SELECT * 
                    FROM country_t
                    WHERE isoAlpha2_f = ?';
      }

      // подготовить строку запроса ищем по трехзначному коду и не цифры 00:23 24-09-2024
      if ($this->validateCodeThreeChar($code) === true) {
        $queryStr = 'SELECT * 
                    FROM country_t
                    WHERE isoAlpha3_f = ?';
      }

      // подготовить строку запроса ищем по трем цифрам цифрам см. внизу 1:16 24-09-2024
      if ($this->validateCodeThreeInt($code) === true) {
        $queryStr = 'SELECT * 
                     FROM country_t
                     WHERE isoNumeric_f = ?';
      }

      // подготовить запрос
      $query = $connection->prepare(query: $queryStr);
      $query->bind_param('s', $code);
      // выполнить запрос
      $query->execute();
      $rows = $query->get_result();
      // считать результаты запроса в цикле 
      while ($row = $rows->fetch_array()) {
        // если есть результат - вернем его
        // каждая строка считается в тип массива
        return new Country(
          shortName: $row[1],
          fullName: $row[2],
          isoAlpha2: $row[3],
          isoAlpha3: $row[4],
          isoNumeric: $row[5],
          population: intval(value: $row[6]),
          square: intval(value: $row[7]),
        );
      }
      // иначе вернуть null
      return null;
    } finally {
      // в конце в любом случае закрыть соединение с БД если оно было открыто
      if (isset($connection)) {
        $connection->close();
      }
    }
  }

  // story - сохранение страны в БД
  function story(Country $country): void
  {
    try {
      // создать подключеник к БД
      $connection = $this->sqlHelper->openDbConnection();
      //echo('запрос на запись выполнен');
      // подготовить запрос INSERT
      $queryStr = 'INSERT INTO country_t (shortName_f, fullName_f, isoAlpha2_f, isoAlpha3_f, isoNumeric_f, population_f, square_f)
          VALUES (?, ?, ?, ?, ?, ?, ?);';
      //https://www.php.net/manual/ru/mysqli-stmt.bind-param.php 
      $query = $connection->prepare(query: $queryStr);
      $query->bind_param(
        'sssssii',
        $country->shortName,
        $country->fullName,
        $country->isoAlpha2,
        $country->isoAlpha3,
        $country->isoNumeric,
        $country->population,
        $country->square,
      );
      //  echo('запрос на запись прошел');
      // выполнить запрос
      //$query->execute();
      // выполнить запрос
      if (!$query->execute()) {
        echo ('  запрос на запись не выполнен  ');
        throw new Exception(message: 'insert execute failed');
      }
    } finally {
      // echo ('-запрос на запись выполнен');
      // в конце в любом случае закрыть соединение с БД если оно было открыто
      if (isset($connection)) {
        $connection->close();
      }
    }
  }

  //   // edit - редактирование страны по коду
  function edit(string $code, Country $country): void
  {
    echo ('функция редактирование страны запущена - ');
    try {
      // создать подключеник к БД
      $connection = $this->sqlHelper->openDbConnection();
      // подготовить запрос INSERT
      // подготовить строку запроса ищем по двухзначному коду и не цифры 15:18 25-09-2024
      if ($this->validateCodeTwoChar($code) === true) {
        $queryStr = 'UPDATE country_t SET 
                   shortName_f = ?, 
                   fullName_f = ?,
                   population_f = ?, 
                   square_f = ?
          WHERE isoAlpha2_f = ?';
        echo ('запрос на редактирование isoAlpha2_f подготовлен - ');
      }

      // подготовить строку запроса ищем по трехзначному коду и не цифры 15:21 25-09-2024
      if ($this->validateCodeThreeChar($code) === true) {
        $queryStr = 'UPDATE country_t SET 
                   shortName_f = ?, 
                   fullName_f = ?, 
                   population_f = ?, 
                   square_f = ?
          WHERE isoAlpha3_f = ?';
        echo ('запрос на редактирование isoAlpha3_f подготовлен - ');
      }

      // подготовить строку запроса ищем по трем цифрам цифрам см. внизу 15:23 25-09-2024
      if ($this->validateCodeThreeInt($code) === true) {
        $queryStr = 'UPDATE country_t SET 
                   shortName_f = ?, 
                    fullName_f = ?, 
                    population_f = ?, 
                    square_f = ?
          WHERE isoNumeric_f = ?';
        echo ('запрос на редактирование isoNumeric_f подготовлен - ');
      }

      // подготовить запрос
      $query = $connection->prepare(query: $queryStr);
      $query->bind_param(
        'ssiis',
        $country->shortName,
        $country->fullName,
        // $country->isoAlpha2,
        // $country->isoAlpha3,
        // $country->isoNumeric,
        $country->population,
        $country->square,
        $code,
      );
      echo (' запрос на редактирование отправлен - ');
      // выполнить запрос
      $query->execute();
      if (!$query->execute()) {
        throw new Exception(message: 'update execute failed');
      }
    } finally {
      // в конце в любом случае закрыть соединение с БД если оно было открыто
      if (isset($connection)) {
        $connection->close();
        echo ('закрываем соединение  - ');
      }
    }
  }


  //  // delete - удаление страны по коду
  function delete(string $code): void
  {
    //throw new Exception('Not implemented');}
    //echo('запрос на удаление начало');
    // создать подключеник к БД
    $connection = $this->sqlHelper->openDbConnection();
    // подготовить запрос INSERT 
    // подготовить строку запроса ищем по двухзначному коду и не цифры 15:18 25-09-2024
    if ($this->validateCodeTwoChar($code) === true) {
      $queryStr = 'DELETE FROM country_t WHERE isoAlpha2_f = ?';
    }

    // подготовить строку запроса ищем по трехзначному коду и не цифры 15:35 25-09-2024
    if ($this->validateCodeThreeInt($code) === true) {
      $queryStr = 'DELETE FROM country_t WHERE isoAlpha3_f = ?';
    }

    // подготовить строку запроса ищем по трем цифрам цифрам см. внизу 15:36 25-09-2024
    if ($this->validateCodeThreeInt($code) === true) {
      $queryStr = 'DELETE FROM country_t WHERE isoNumeric_f = ?';
    }
    // подготовить запрос
    //echo('запрос на удаление отправлен');
    $query = $connection->prepare(query: $queryStr);
    $query->bind_param('s', $code);
    // выполнить запросif (!$query->execute()) {
    $query->execute();

    if (isset($connection)) {
      $connection->close();
    }
  }
  // validateCode - проверка корректности кода аэропорта
  // вход: строка кода аэропорта
  // выход: true если строка корректная, иначе false
  // проверка по числу трехзначному Three
  private function validateCodeThreeInt(string $code): bool
  {
    // ^[1-9]{3}$
    return preg_match(pattern: '/^[1-9]{3}$/', subject: $code);
  }
  // проверка по двум символам two
  private function validateCodeTwoChar(string $code): bool
  {
    // ^[A-Z]{2}$
    return preg_match(pattern: '/^[A-Z]{2}$/', subject: $code);
  }
  // проверка по трем символам three
  private function validateCodeThreeChar(string $code): bool
  {
    // ^[A-Z]{3}$
    return preg_match(pattern: '/^[A-Z]{3}$/', subject: $code);
  }
}
