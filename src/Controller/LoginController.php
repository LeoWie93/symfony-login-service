<?php

namespace App\Controller;

use App\Exceptions\ServiceException;
use App\Services\CredentialService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    /**
     * @Route("/login", name="login",methods={"POST"})
     */
    public function login(
        CredentialService $credentialService,
        Request           $request
    )
    {
        $username = $request->request->get('username') ?? '';
        $password = $request->request->get('password') ?? '';

        try {
            $credentialService->loginWithCredentials($username, $password);
            return new JsonResponse([
                'Code' => '2000',
                'message' => 'success',
                'body' => [
                    'token' => '$jwt',
                    'expires' => '$jwt->expires',
                ],
            ], '200');
        } catch (ServiceException $e) {
            return new JsonResponse([
                'Code' => $e->getCode(),
                'message' => $e->getMessage(),
            ], $e->getHttpCode());
        } catch (\Exception $e) {
            die(var_dump($e->getMessage()));
        }


        // get username and password from POST request
        // userService->loginWithCredentials(username,password);
        // returns valid jwt with userdata etc
        // or throws error
    }

    /**
     * @Route("/update-session", name="update_session", methods={"POST"})
     */
    public function updateSession()
    {
        // get jwt from request
        // userService->loginWithJWT(jwt);
        // return valid jwt
        // or throws error
    }
}