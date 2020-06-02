<?php


namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Film;
use App\Form\FilmType;
use App\Form\GenreType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class FilmController extends AbstractController
{

    public function liste(Environment $twig)
    {
        $reposF = $this->getDoctrine()->getRepository(Film::class);
        $reposC = $this->getDoctrine()->getRepository(Categorie::class);

        $films = $reposF->findAll();
        $categories = $reposC->findAll();
        $content = $twig->render('listeFilms.html.twig', array('films' => $films, 'categories' => $categories));
        return new Response($content);
    }

    public function filtrer(Request $request, Environment $twig)
    {
        if($request->isMethod('POST')){

            $id = $request->request->get('categories');
            $reposC = $this->getDoctrine()->getRepository(Categorie::class);
            $reposF = $this->getDoctrine()->getRepository(Film::class);

            if($id != 0)
                $films = $reposF->findByCatId($id);
            else
                $films = $reposF->findAll();

            $categories = $reposC->findAll();

            $content = $twig->render('listeFilms.html.twig', array('films' => $films, 'categories' => $categories));
            return new Response($content);
        }
    }

    public function afficher($id, Environment $twig)
    {
        $repos = $this->getDoctrine()->getRepository(Film::class);
        $film = $repos->find($id);
        $content = $twig->render('afficherFilm.html.twig', array('film' => $film));
        return new Response($content);
    }

    public function supprimer($id, Environment $twig)
    {
        $manager = $this->getDoctrine()->getManager();
        $repos = $manager->getRepository(Film::class);
        $reposC = $this->getDoctrine()->getRepository(Categorie::class);
        $film = $repos->find($id);


        $manager->remove($film);
        $manager->flush();

        $message = "Le film a été supprimé avec succès.";

        $categories = $reposC->findAll();
        $films = $repos->findAll();
        $content = $twig->render('listeFilms.html.twig', array('films' => $films, 'categories' => $categories, 'message' => $message));
        return new Response($content);
    }


}