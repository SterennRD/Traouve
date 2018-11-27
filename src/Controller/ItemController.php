<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\Status;
use App\Form\ItemType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/item")
 */
class ItemController extends AbstractController
{
    /**
     * @Route("/", name="item_index", methods="GET")
     */
    public function index(): Response
    {
        $items = $this->getDoctrine()
            ->getRepository(Item::class)
            ->findAll();

        return $this->render('item/index.html.twig', ['items' => $items]);
    }

    /**
     * @Route("/new", name="item_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $item = new Item();
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($item);
            $em->flush();

            return $this->redirectToRoute('item_index');
        }

        return $this->render('item/new.html.twig', [
            'item' => $item,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="item_show", methods="GET")
     */
    public function show(Item $item): Response
    {
        return $this->render('item/show.html.twig', ['item' => $item]);
    }

    /**
     * @Route("/{id}/edit", name="item_edit", methods="GET|POST")
     */
    public function edit(Request $request, Item $item): Response
    {
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('item_index', ['id' => $item->getId()]);
        }

        return $this->render('item/edit.html.twig', [
            'item' => $item,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="item_delete", methods="DELETE")
     */
    public function delete(Request $request, Item $item): Response
    {
        if ($this->isCsrfTokenValid('delete'.$item->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($item);
            $em->flush();
        }

        return $this->redirectToRoute('item_index');
    }

    /**
     * @Route("/city/{city}", name="item_city")
     */
    public function findByCity(string $city) : Response
    {
        $items = $this->getDoctrine()->getRepository(Item::class)->findBy(["city" => $city]);

        return $this->render('item/city.html.twig', [
            "items" => $items,
            "city" => $city
        ]);
    }
}
