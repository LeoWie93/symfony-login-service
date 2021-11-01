<?php

namespace App\Controller;

use App\Exceptions\ServiceException;
use App\Services\RegistrationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register-user",name="register_user",methods={"POST"})
     */
    public function registerUser(
        RegistrationService $registrationService,
        Request             $request
    )
    {
        try {
            $registrationService->createUser($request);
            // if creation successfull => send mail and return success
            return new JsonResponse([], '2000');
        } catch (ServiceException $e) {
            return new JsonResponse([
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ], $e->getHttpCode());
        }
    }
}