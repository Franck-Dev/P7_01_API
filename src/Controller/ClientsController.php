<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Repository\ClientsRepository;
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
     * @Rest\QueryParam(
     *     name="order",
     *     requirements="asc|desc",
     *     default="asc",
     *     description="Sort order (asc or desc)"
     * )
     * @Rest\View
     */
    public function listClients(ClientsRepository $repo, $order)
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
     */
    public function createAction(Clients $client, ConstraintViolationList $violations)
    {
        if (count($violations)) {
            return $this->view($violations, Response::HTTP_BAD_REQUEST);
        }
        
        $em = $this->getDoctrine()->getManager();

        $em->persist($client);
        $em->flush();
        return $this->view($client, Response::HTTP_CREATED, ['Location' => $this->generateUrl('app_client_show', ['id' => $client->getId(), UrlGeneratorInterface::ABSOLUTE_URL])]);
    }

     /**
     * @Rest\Get(
     *     path = "/client/{id}",
     *     name = "app_client_show",
     *     requirements = {"id"="\d+"}
     * )
     * @Rest\View
     */
    public function showAction(Clients $client)
    {

        return $client;
    }

     /**
     * @Rest\Get(
     *     path = "/client/Users/List",
     *     name = "app_users_list"
     * )
     * @Rest\View
     */
    public function getUsersClient(Clients $client)
    {

        return $client;
    }
}
