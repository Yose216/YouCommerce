<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as Rest;

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
     * @Get("/products")
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $products = $em->getRepository('AppBundle:Product')->findAll();

		$formatted = [];
        foreach ($products as $product) {
            $formatted[] = [
            	'id' => $product->getId(),
            	'name' => $product->getName(),
				'image' => $product->getImage(),
				'image2' => $product->getImage2(),
				'image3' => $product->getImage3(),
				'image4' => $product->getImage4(),
				'price' => $product->getPrice(),
				'description' => $product->getDescription(),
				'categorie' => $product->getCategorie()->getName(),
            ];
        }

        $view = View::create($formatted);
        $view->setFormat('json');

        return $view;
    }

    /**
     * Creates a new product entity.
     *
	 * @Rest\View
     * @Post("/new/products")
     *
     */
    public function newAction(Request $request)
    {
        $product = new Product();
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
     *
     * @Get("/products/{id}")
     *
     */
    public function showAction($id, Request $request)
    {
//    	$deleteForm = $this->createDeleteForm($product);
		$em = $this->getDoctrine()->getManager();

        $product = $em->getRepository('AppBundle:Product')->find($id);

		$formatted[] = [
            'id' => $product->getId(),
            'name' => $product->getName(),
			'image' => $product->getImage(),
			'image2' => $product->getImage2(),
			'image3' => $product->getImage3(),
			'image4' => $product->getImage4(),
			'price' => $product->getPrice(),
			'description' => $product->getDescription(),
			'categorie' => $product->getCategorie()->getName(),
		];

		if (empty($product)) {
            return new JsonResponse(['message' => 'Object not found'], Response::HTTP_NOT_FOUND);
        }

        $view = View::create($formatted);
        $view->setFormat('json');

        return $view;
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
            return new JsonResponse(['message' => 'Place not found'], Response::HTTP_NOT_FOUND);
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
     * @Rest\View
     * @Rest\Delete("/delete/products/{id}")
	 *
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('AppBundle:Product')->find($id);

		if ($product) {
            $em->remove($product);
            $em->flush();
        }
    }

    /**
     * Creates a form to delete a product entity.
     *
     * @param Product $product The product entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
//    private function createDeleteForm(Product $product)
//    {
//        return $this->createFormBuilder()
//            ->setAction($this->generateUrl('product_delete', array('id' => $product->getId())))
//            ->setMethod('DELETE')
//            ->getForm()
//        ;
//    }
}
