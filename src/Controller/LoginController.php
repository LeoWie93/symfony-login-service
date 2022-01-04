<?php

namespace App\Controller;

use App\Exceptions\ServiceException;
use App\Services\CredentialService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
            $user = $credentialService->loginWithCredentials($username, $password);
            $jwt = $user->getTokens()[0];

            return new JsonResponse([
                'code' => '2000',
                'message' => 'success',
                'body' => [
                    'token' => $jwt->getToken(),
                    'expires' => $jwt->getExpires(),
                ],
            ], Response::HTTP_OK);
        } catch (ServiceException $e) {
            return new JsonResponse([
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ], $e->getHttpCode());
        } catch (\Exception $e) {
            //TODO create usable json response. LWI 04.01.2021
            die(var_dump($e->getMessage()));
        }
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