<?php

namespace App\Controller;

use App\Model\Country;
use App\Model\CountryScenarios;
use App\Model\Exceptions\InvalidCodeException;
use App\Model\Exceptions\CountryNotFoundException;
use App\Model\Exceptions\DuplicatedCodeException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

#[Route(path: '/api/country', name: 'app_api_root')]

class CountryController extends AbstractController
{
    public function __construct(
        private readonly CountryScenarios $countryScenarios
    ) {}
    #[Route('/c', name: 'app_country')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/CountryController.php',
        ]);
    }

    // получение всех стран
    #[Route(path: '/all', name: 'app_api_country_root', methods: ['GET'])]
    public function getAll(): JsonResponse
    {
        return $this->json(data: $this->countryScenarios->getAll(), status: 200);
    }

    // получение страны по коду
    #[Route(path: '/{code}', name: 'app_api_country_code', methods: ['GET'])]
    public function get(string $code): JsonResponse
    {
        try {
            $country = $this->countryScenarios->get($code);
            return $this->json(data: $country, status: 200);
        } catch (InvalidCodeException $ex) {
            $response = $this->buildErrorResponse(ex: $ex);
            $response->setStatusCode(code: 400);
            return $response;
        } catch (CountryNotFoundException $ex) {
            $response = $this->buildErrorResponse(ex: $ex);
            $response->setStatusCode(code: 404);
            return $response;
        }
    }

    // добавление страны
    #[Route(path: '', name: 'app_api_country_story', methods: ['POST'])]
    public function story(Request $request, #[MapRequestPayload] Country $country): JsonResponse
    {
        try {

            $this->countryScenarios->story(country: $country);
            return $this->json($country, status: 200);
        } catch (InvalidCodeException $ex) {
            echo ' исключение в контроллере добавления страны невалидный код ';
            $response = $this->buildErrorResponse(ex: $ex);
            $response->setStatusCode(code: 400);
            return $response;
        } catch (DuplicatedCodeException $ex) {
            echo ' исключение в контроллере добавления страны дублирующийся код ';
            $response = $this->buildErrorResponse(ex: $ex);
            $response->setStatusCode(code: 409);
            return $response;
        }
    }

    // редактирование страны
    #[Route(path: '/{code}', name: 'app_api_country_edit', methods: ['PATCH'])]
    public function edit(Request $request, string $code, #[MapRequestPayload] Country $country): JsonResponse
    {
        try {
            $this->countryScenarios->edit(code: $code, country: $country);
            return $this->json(data: $country, status: 200);
        } catch (CountryNotFoundException $ex) {
            $response = $this->buildErrorResponse(ex: $ex);
            $response->setStatusCode(code: 404);
            return $response;
        } catch (InvalidCodeException $ex) {
            $response = $this->buildErrorResponse(ex: $ex);
            $response->setStatusCode(code: 400);
            return $response;
        }
    }
    // удаление страны
    #[Route(path: '/{code}', name: 'app_api_country_remove', methods: ['DELETE'])]
    public function remove(string $code): JsonResponse
    {
        try {
            $this->countryScenarios->delete(code: $code);
            return $this->json('страна удалена', status: 200);
        } catch (InvalidCodeException $ex) {
            $response = $this->buildErrorResponse(ex: $ex);
            $response->setStatusCode(code: 400);
            return $response;
        } catch (CountryNotFoundException $ex) {
            $response = $this->buildErrorResponse(ex: $ex);
            $response->setStatusCode(code: 404);
            return $response;
        }
    }
    // вспомогательный метод формирования ошибки
    private function buildErrorResponse(Exception $ex): JsonResponse
    {
        return $this->json(data: [
            'errorCode' => $ex->getCode(),
            'errorMessage' => $ex->getMessage(),
        ]);
    }
}
