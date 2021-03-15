<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Clients;
use App\Entity\ApiToken;
use OpenApi\Annotations\Tag as OA;
use App\Repository\UsersRepository;
use App\Repository\ClientsRepository;
use App\Security\ApiTokenAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * ClientsController.
 * @Route("/api",name="api_")
 */

class ClientsController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(
     *     path = "/admin/clients/list",
     *     name = "app_clients_list"
     * )
     * @Rest\View(serializerGroups={"list"})
     * 
     * @OA(name="Admin")
     */
    public function listClients(ClientsRepository $repo)
    {
        $clients=$repo->findAll();
        return $clients;
    }

    /**
     * @Rest\Post(
     *    path = "/admin/client",
     *    name = "app_client_create"
     * )
     * @Rest\View(StatusCode = 201)
     * @ParamConverter("client", converter="fos_rest.request_body")
     * 
     * @OA(name="Admin")
     */
    public function createAction(Clients $client, ConstraintViolationList $violations)
    {
        if (count($violations)) {
            return $this->view($violations, Response::HTTP_BAD_REQUEST);
        }
        
        $em = $this->getDoctrine()->getManager();
        //Création du token API du client
        $apiToken1 = new ApiToken($client,null);
        $em->persist($apiToken1);
        
        $em->persist($client);
        $em->flush();
        return $this->view($client, Response::HTTP_CREATED, ['Location' => $this->generateUrl('api_app_client_show', ['id' => $client->getId(), UrlGeneratorInterface::ABSOLUTE_URL])]);
    }

     /**
     * @Rest\Get(
     *     path = "/client/{id}",
     *     name = "app_client_show",
     *     requirements = {"id"="\d+"}
     * )
     * @Rest\View(serializerGroups={"detail"})
     * 
     * @OA(name="Client")
     */
    public function showAction(?Clients $client)
    {
        if(!$client) {
            return $this->view('Client not found with this id', Response::HTTP_NOT_FOUND);
        }
        return $client;
    }

     /**
     * @Rest\Get(
     *     path = "/client/Users/List",
     *     name = "app_users_list"
     * )
     * @Rest\QueryParam(
     *     name="order",
     *     requirements="asc|desc",
     *     default="asc",
     *     description="Sort order (asc or desc)"
     * )
     * @Rest\QueryParam(
     *     name="limit",
     *     requirements="\d+",
     *     default="15",
     *     description="Fin liste utilisateurs"
     * )
     * @Rest\QueryParam(
     *     name="offset",
     *     requirements="\d+",
     *     default="1",
     *     description="Début liste utilisateurs"
     * )
     * @Rest\View(StatusCode = 200,serializerGroups={"Show","client"})
     * 
     * @OA(name="Client")
     */
    public function getUsersClient(ApiTokenAuthenticator $Auth, Request $request, UsersRepository $repo, $order, $limit, $offset)
    {
        // On doit trouver le token du client de cet utilisateur
        $token=$Auth->getCredentials($request);
        $idToken=$this->getDoctrine()->getRepository(ApiToken::class)->findOneBy(['token' => $token]);

        $users=$repo->findBy(['Client'=> $idToken->getUserClient()->getClient()],['username'=> $order],$limit,$offset);
        return $users;
    }

     /**
     * @Rest\Delete(
     *     path = "/client/user/{id}",
     *     name = "app_client_delete_user",
     *     requirements = {"id"="\d+"}
     * )
     * @Rest\View(StatusCode = 200)
     * 
     * @OA(name="Client")
     */
    public function deleteUser(Users $user)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($user);
        $em->flush();
        return $this->view(null, Response::HTTP_OK, []);
    }
}
