<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\View\View;

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
     *@Get("/products")
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

        return new JsonResponse($formatted);
    }

    /**
     * Creates a new product entity.
     *
     * @Post("/new/products")
     *
     */
    public function newAction(Request $request)
    {
        $product = new Product();
        $form = $this->createForm('AppBundle\Form\ProductType', $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush($product);

            return $this->redirectToRoute('product_show', array('id' => $product->getId()));
        }

        return $this->render('product/new.html.twig', array(
            'product' => $product,
            'form' => $form->createView(),
        ));
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

        return new JsonResponse($formatted);
    }

    /**
     * Displays a form to edit an existing product entity.
     *
     *
     *
     */
//    public function editAction(Request $request, Product $product)
//    {
//        $deleteForm = $this->createDeleteForm($product);
//        $editForm = $this->createForm('AppBundle\Form\ProductType', $product);
//        $editForm->handleRequest($request);
//
//        if ($editForm->isSubmitted() && $editForm->isValid()) {
//            $this->getDoctrine()->getManager()->flush();
//
//            return $this->redirectToRoute('product_edit', array('id' => $product->getId()));
//        }
//
//        return $this->render('product/edit.html.twig', array(
//            'product' => $product,
//            'edit_form' => $editForm->createView(),
//            'delete_form' => $deleteForm->createView(),
//        ));
//    }

    /**
     * Deletes a product entity.
     *
     *
     *
     */
//    public function deleteAction(Request $request, Product $product)
//    {
//        $form = $this->createDeleteForm($product);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $em = $this->getDoctrine()->getManager();
//            $em->remove($product);
//            $em->flush($product);
//        }
//
//        return $this->redirectToRoute('product_index');
//    }

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
