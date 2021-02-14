<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Repository\ProduitsRepository;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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
     *     default="asc",
     *     description="Sort order (asc or desc)"
     * )
     * @Rest\View
     */
    public function listProduits(ProduitsRepository $repo, $order)
    {
        $produits=$repo->findAll();
        return $produits;
    }
    
    /**
     * @Rest\Get(
     *     path = "/produit/{id}",
     *     name = "app_produit_show",
     *     requirements = {"id"="\d+"}
     * )
     * @Rest\View
     */
    public function showAction(Produits $produit)
    {

        return $produit;
    }

    /**
     * @Rest\Post(
     *    path = "/produits",
     *    name = "app_produit_create"
     * )
     * @Rest\View(StatusCode = 201)
     * @ParamConverter("produit", converter="fos_rest.request_body")
     */
    public function createAction(Produits $produit, ConstraintViolationList $violations)
    {

        if (count($violations)) {
            return $this->view($violations, Response::HTTP_BAD_REQUEST);
        }
        
        $em = $this->getDoctrine()->getManager();

        $em->persist($produit);
        $em->flush();
        return $this->view($produit, Response::HTTP_CREATED, ['Location' => $this->generateUrl('app_produit_show', ['id' => $produit->getId(), UrlGeneratorInterface::ABSOLUTE_URL])]);
    }
}
