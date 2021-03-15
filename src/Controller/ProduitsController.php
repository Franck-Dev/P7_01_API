<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Repository\ProduitsRepository;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Validator\ConstraintViolationList;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use OpenApi\Annotations\Tag as OA;

/**
 * ProduitsController.
 * @Route("/api",name="api_")
 */

class ProduitsController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(
     *     path = "/produits/list",
     *     name = "app_produits_list"
     * )
     * @Rest\QueryParam(
     *     name="order",
     *     requirements="asc|desc",
     *     nullable=true,
     *     default="asc",
     *     description="Sort order (asc or desc)"
     * )
     * @Rest\QueryParam(
     *     name="limit",
     *     requirements="\d+",
     *     default="15",
     *     description="Fin de pagination"
     * )
     * @Rest\QueryParam(
     *     name="offset",
     *     requirements="\d+",
     *     default="1",
     *     description="Début de pagination"
     * )
     * @Rest\View(StatusCode = 200)
     * 
     * @OA(name="Utilisateurs")
     */
    public function listProduits(ProduitsRepository $repo, $order, $limit, $offset)
    {        
        $produits=$repo->findBy([],['Name'=> $order],$limit,$offset);
        if(!$produits) {
            return $this->view('Not products with their criterias', Response::HTTP_NOT_FOUND);
        }
        return $produits;
    }
    
    /**
     * @Rest\Get(
     *     path = "/produit/{id}",
     *     name = "app_produit_show",
     *     requirements = {"id"="\d+"}
     * )
     * @Rest\View(serializerGroups={"detail"})
     * 
     * @OA(name="Utilisateurs")
     */
    public function showAction(?Produits $produit)
    {
        if(!$produit) {
            return $this->view('Product not found with this id', Response::HTTP_NOT_FOUND);
        }
        return $produit;
    }

    /**
     * @Rest\Post(
     *    path = "/admin/produit",
     *    name = "app_produit_create"
     * )
     * @Rest\View(StatusCode = 201, serializerGroups={"create"})
     * @ParamConverter("produit", converter="fos_rest.request_body")
     * 
     * @OA(name="Admin")
     */
    public function createAction(Produits $produit, ConstraintViolationList $violations)
    {
        if (count($violations)) {
            return $this->view($violations, Response::HTTP_BAD_REQUEST);
        }
        
        $em = $this->getDoctrine()->getManager();

        $em->persist($produit);
        $em->flush();
        return $this->view($produit, Response::HTTP_CREATED, ['Location' => $this->generateUrl('api_app_produit_show', ['id' => $produit->getId(), UrlGeneratorInterface::ABSOLUTE_URL])]);
    }
}
