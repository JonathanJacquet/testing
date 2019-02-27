<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Movie;
use App\Entity\Evaluation;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TestController extends AbstractController {
  /**
  * @Route("/", name="index")
  */
  public function index()
  {
    $movies = $this->getDoctrine()->getRepository(Movie::class)->findAll();
    return $this->render('test/index.html.twig', [
      "movies" => $movies
    ]);
  }
    // /**
    //  * fonction faites pour effectuer des tests
    //  * @Route("/test", name="test")
    //  */
    // public function test()
    // {
    //     $ms = $this->getDoctrine()->getRepository(Movie::class)->findAll();
    //     //fonction qui essé de calc moyen note flm mais prblm
    //     for ($i=0; $i < count($ms) ; $i) {
    //       $notes = $ms[$i]->getEvaluations()->getGrade();
    //     }
    //     return $this->render('test/index.html.twig', [
    //       "ms" => $ms
    //     ]);
    // }


    /**
     * @Route("/single/{id}", name="single")
     */
    public function show(Movie $movie)
    {
        return $this->render('test/single.html.twig', [
          "movie" => $movie
        ]);
    }

    /**
     * @Route("/evaluation/{id}", name="evaluation")
     * @Isgranted("ROLE")
     */
    public function rate(Movie $movie, Request $request)
    {
        $evaluation = new Evaluation();

        $form = $this->createFormBuilder($evaluation)
            ->add('comment', TextareaType::class)
            ->add('grade')
            ->add('save', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          $evaluation->setMovie($movie);
          $evaluation->setUser($user);
          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->persist($evaluation);
          $entityManager->flush();
        }

        return $this->render('test/evaluation.html.twig', [
          "movie" => $movie,
          "form" => $form->createView()
        ]);
    }
}
