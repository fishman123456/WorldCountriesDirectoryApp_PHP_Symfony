<?php

namespace App\Model;

use app\Model\Country;
use App\Model\Exceptions\CountryNotFoundException;
use App\Model\Exceptions\InvalidCodeException;
use App\Model\Exceptions\DuplicatedCodeException;
use App\Rdb\CountryStorage;
use Throwable;
use Exception;

class CountryScenarios
{

  //Выполните инъекцию данного интерфейса в класс CountryScenarios
  public function __construct(
    private readonly CountryRepository $countryRepository
  ) {}

  // getAll - получение всех стран
  // вход: -
  // выход: список объектов Country
  public function getAll(): array
  {
    // заглушка
    return $this->countryRepository->getAll();
  }

  // getById - получение страны по коду 20-09-2024 15:16
  // вход: двухбуквенный код страны
  // выход: объект извлеченной страны
  // исключения: InvalidCodeException, CountryNotFoundException
  public function get(string $code): ?Country
  {
    // проверка коректности кода, две буквы, три буквы, три цифры
    if (
      !$this->validateCodeTwoChar($code)
      &&  !$this->validateCodeThreeChar($code)
      &&  !$this->validateCodeThreeInt($code)
    ) {
      throw new InvalidCodeException($code, 'validation failed');
    }
    $country = $this->countryRepository->get($code);
    if ($country === null) {
      // если страна не найдена - выбросить ошибку
      throw new CountryNotFoundException($code);
    }
    //  вернуть полученную страну
    return $country;
  }

  // add - добавление новой страны
  // вход: объект страна json
  // выход: -
  // исключения: InvalidCodeException, DuplicatedCodeException
  // исключения по коду страны и по повторению кода
  public function story(Country $country): void
  {
    // выполнить проверку корректности кода 26-09-2024
    if (
      !$this->validateCodeTwoChar($country->isoAlpha2)
      ||  !$this->validateCodeThreeChar($country->isoAlpha3)
      ||  !$this->validateCodeThreeInt($country->isoNumeric)
    ) {
      echo ' поймали невалидность  ';
      throw new InvalidCodeException('$code', 'validation failed');
    }
    // выполнить проверку уникальности двухзначного строкового кода
    $sameCodeCountryisoAlpha2 = $this->countryRepository->get(code: strval($country->isoAlpha2));
    if ($sameCodeCountryisoAlpha2 != null) {
      throw new DuplicatedCodeException(duplicatedCode: strval($country->isoAlpha2));
    }
    // выполнить проверку уникальности трехзначного строкового кода
    $sameCodeCountryisoAlpha3 = $this->countryRepository->get(code: strval($country->isoAlpha3));
    if ($sameCodeCountryisoAlpha3 != null) {
      throw new DuplicatedCodeException(duplicatedCode: strval($country->isoAlpha3));
    }
    // выполнить проверку уникальности трехзначного цифрового кода
    $sameCodeCountryisoNumeric = $this->countryRepository->get(code: strval($country->isoNumeric));
    if ($sameCodeCountryisoNumeric != null) {
      throw new DuplicatedCodeException(strval($country->isoNumeric));
    }
    // если все ок, то сохранить страну в БД
    $this->countryRepository->story(country: $country);
    //throw new Exception('not implemented');
  }


  // edit - редактирование страны по коду
  // вход: код редактируемого страны (не обновленный)
  // выход: -
  // исключения: InvalidCodeException, CountryNotFoundException, DuplicatedCodeException
  public function edit(string $code, Country $country): void
  {
    //throw new Exception('Not implemented');
    // проверка коректности кода, две буквы, три буквы, три цифры
    if (
      !$this->validateCodeTwoChar($code)
      &&  !$this->validateCodeThreeChar($code)
      &&  !$this->validateCodeThreeInt($code)
    ) {
      throw new InvalidCodeException($code, 'validation failed');
    }
    // выполнить проверку наличия страны для редактирования
    $updatedcountry = $this->countryRepository->get($code);
    if ($updatedcountry === null) {
      // если страна не найдена - выбросить ошибку
      throw new CountryNotFoundException($code);
    }
    $this->countryRepository->edit(code: $code,  country: $country);
  }

  // delete - удаление страны по коду
  // вход: код удаляемой страны
  // выход: -
  // исключения: InvalidCodeException, CountryNotFoundException
  public function delete(string $code): void
  {
    // проверка коректности кода, две буквы, три буквы, три цифры
    if (
      !$this->validateCodeTwoChar($code)
      &&  !$this->validateCodeThreeChar($code)
      &&  !$this->validateCodeThreeInt($code)
    ) {
      throw new InvalidCodeException($code, 'validation failed');
    }
    $country = $this->countryRepository->get($code);
    if ($country === null) {
      // если страна не найдена - выбросить ошибку
      throw new CountryNotFoundException($code);
    }
    //throw new Exception('Not implemented');
    $this->countryRepository->delete(code: $code);
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
