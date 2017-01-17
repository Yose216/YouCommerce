<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Product controller.
 *
 *
 */
class ProductController extends Controller
{
    /**
     * Lists all product entities.
     *
	 * @Rest\View()
     * @Rest\Get("/products")
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
		$products = $em->getRepository('AppBundle:Product')->findAll();
		/* @var $products Product[] */

        return $products;
    }

    /**
     * Creates a new product entity.
     *
	 * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/new/categorie/{id}/products")
     *
     */
    public function newAction($id, Request $request)
    {
		$em = $this->getDoctrine()->getManager();
		$categorie = $em->getRepository('AppBundle:Categorie')->find($id);
		/* @var $categorie Categorie[] */

        $product = new Product();
		$product->setCategorie($categorie);
        $form = $this->createForm('AppBundle\Form\ProductType', $product);
        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush($product);

            return $product;
        }
		else {
			return $form;
		}
    }

    /**
     * Finds and displays a product entity.
     * @Rest\View()
     * @Rest\Get("/products/{id}")
     *
     */
    public function showAction($id, Request $request)
    {
		$em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('AppBundle:Product')->find($id);
		/* @var $products Product[] */

        return $product;
    }

	/**
     * @Rest\View()
     * @Rest\Put("/edit/products/{id}")
     */
    public function updateProductAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('AppBundle:Product')->find($id);

        if (empty($product)) {
            return new JsonResponse(['message' => 'Place not found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm('AppBundle\Form\ProductType', $product);
        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->merge($product);
            $em->flush();
            return $product;
        }
		else {
            return $form;
        }
    }

	/**
     * @Rest\View()
     * @Rest\Patch("/edit/products/{id}")
     */
    public function patchProductAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('AppBundle:Product')->find($id);

        if (empty($product)) {
            return new JsonResponse(['message' => 'Categorie not found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm('AppBundle\Form\ProductType', $product);

         // Le paramètre false dit à Symfony de garder les valeurs dans notre
         // entité si l'utilisateur n'en fournit pas une dans sa requête
        $form->submit($request->request->all(), false);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->merge($product);
            $em->flush();
            return $product;
        }
		else {
            return $form;
        }
    }

    /**
     * Deletes a product entity.
     *
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("/delete/products/{id}")
	 *
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('AppBundle:Product')->find($id);

		if (empty($product)) {
            throw new NotFoundHttpException('product not found');
        }

		if ($product) {
            $em->remove($product);
            $em->flush();
        }
    }

}
