<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Clients;
use App\Entity\ApiToken;
use App\Security\ApiTokenAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/api")
 */

//class SecurityController extends AbstractController
class SecurityController extends AbstractFOSRestController
{
    /**
     * @Route("/security", name="app_security")
     */
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/SecurityController.php',
        ]);
    }

    /**
     * @Rest\Post(
     *    path = "/register",
     *    name = "app_user_create"
     * )
     * @Rest\View(StatusCode = 201)
     * @ParamConverter("user", converter="fos_rest.request_body")
     */
    public function register(Request $request, Users $user,  UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager, ConstraintViolationList $violations,ApiTokenAuthenticator $Auth )
    {
        if (count($violations)) {
            return $this->view($violations, Response::HTTP_BAD_REQUEST);
        }
        $values = json_decode($request->getContent());
            //On récupère la le client avec son token
            $token=$Auth->getCredentials($request);
            $idClient=$this->getDoctrine()->getRepository(ApiToken::class)->findOneBy(['token' => $token]);
            $user = new Users();
            $user->setUsername($values->username);
            $user->setEmail($values->email);
            $user->setPassword($passwordEncoder->encodePassword($user, $values->password));
            $user->setRoles($user->getRoles());
            $user->setClient($idClient->getClient());
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->view($user, Response::HTTP_CREATED, ['Location' => $this->generateUrl('app_security', ['', UrlGeneratorInterface::ABSOLUTE_URL])]);
    }
}
