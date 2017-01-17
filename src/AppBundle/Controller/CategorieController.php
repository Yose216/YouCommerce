<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Categorie;
use AppBundle\Form\CategorieType;
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
 * Categorie controller.
 *
 *
 */
class CategorieController extends Controller
{

	/**
     * @Rest\View()
     * @Get("/categorie")
     */
    public function getAllCategorieAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
		$categorie = $em->getRepository('AppBundle:Categorie')->findAll();

        return $categorie;
    }

	/**
     * @Rest\View()
     * @Get("/categorie/{id}")
     */
    public function oneCategorieAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
		$categorie = $em->getRepository('AppBundle:Categorie')->find($id);

        return $categorie;
    }

     /**
     * @Rest\View()
     * @Rest\Post("/new/categorie")
     */
    public function createCategorieAction(Request $request)
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->flush();
            return $categorie;
        } else {
            return $form;
        }
    }

//    private function placeNotFound()
//    {
//        return \FOS\RestBundle\View\View::create(['message' => 'Place not found'], 			Response::HTTP_NOT_FOUND);
//    }

	/**
     * @Rest\View()
     * @Rest\Put("/edit/categorie/{id}")
     */
    public function updateCategorieAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $categorie = $em->getRepository('AppBundle:Categorie')->find($id);

        if (empty($categorie)) {
            return new JsonResponse(['message' => 'Categorie not found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm('AppBundle\Form\CategorieType', $product);
        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->merge($categorie);
            $em->flush();
            return $categorie;
        }
		else {
            return $form;
        }
    }

	/**
     * @Rest\View()
     * @Rest\Patch("/edit/categorie/{id}")
     */
    public function patchCategorieAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $categorie = $em->getRepository('AppBundle:Categorie')->find($id);

        if (empty($categorie)) {
            return new JsonResponse(['message' => 'Categorie not found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm('AppBundle\Form\CategorieType', $categorie);

        $form->submit($request->request->all(), false);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->merge($categorie);
            $em->flush();
            return $categorie;
        }
		else {
            return $form;
        }
    }

    /**
     * Deletes a product entity.
     *
     * @Rest\View()
     * @Rest\Delete("/delete/categorie/{id}")
	 *
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $categorie = $em->getRepository('AppBundle:Categorie')->find($id);

		if (empty($categorie)) {
            throw new NotFoundHttpException('Categorie not found');
        }

		if ($categorie) {
            $em->remove($categorie);
            $em->flush();
        }
    }

}
