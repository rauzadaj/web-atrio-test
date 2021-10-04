<?php

namespace App\Controller;

use App\Entity\Personne;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\PersonType;
class PersonController extends AbstractController
{
    /**
     * @Route("/personne", name="personne")
     */
    public function index(Request $request): Response
    {
        $personne = new Personne();
        $form = $this->createForm(PersonType::class, $personne);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $d1 = new \DateTime('NOW');
            $d2 = $personne->getBirthday();
            $diff = $d1->diff($d2);
            if ($diff->y <= 150)
            {
                $personne = $form->getData();
                $em = $this->getDoctrine()->getManager();
                $em->persist($personne);
                $em->flush();
            } else {
                return new FormError('Votre age doit etre inferieur ou egal a 150 ans');
            }
        }
        return $this->render('personne/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/personne/list", name="list")
     */
    public function list()
    {
        $result = $this->getDoctrine()->getRepository(Personne::class)->findBy([], ['lastname' => 'ASC']);
        return $this->render('personne/list.html.twig', [
            'list' => $result
        ]);
    }
}
