<?php

namespace App\Controller;

use App\Exceptions\ServiceException;
use App\Services\RegistrationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register-user",methods={"POST"})
     */
    public function registerUser(
        RegistrationService $registrationService,
        Request             $request
    ): Response
    {
        try {
            $registrationService->registerUser($request);
            // if creation successfull => send mail and return success
            return new JsonResponse([], Response::HTTP_OK);
        } catch (ServiceException $e) {
            return new JsonResponse([
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ], $e->getHttpCode());
        }
    }

    /**
     * @Route("/activate-user",methods={"GET"})
     */
    public function activateUser(
        RegistrationService $registrationService,
        Request             $request
    ): Response
    {
        try {
            if (!$code = $request->query->get('c')) {
                throw  new ServiceException(
                    'No valid activation code provided',
                    '4000',
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            }

            $registrationService->activateUser($code);
            return new JsonResponse([]);

        } catch (ServiceException $e) {
            return new JsonResponse([
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ], $e->getHttpCode());
        }
    }
}