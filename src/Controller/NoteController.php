<?php

namespace App\Controller;

use App\Repository\NoteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NoteController extends AbstractController
{
    /**
     * @Route("/user/notes", name="notes")
     */
    public function index(NoteRepository $repo): Response
    {
        $idUser = $this->getUser()->getId();

        $notes = $repo->findByUser($idUser);
        return $this->render('note/notes.html.twig', [
            'notes' => $notes
        ]);
    }


    /**
     * @Route("/user/notes/add", name="addNote")
     */
    public function addNote()
    {

    }
}
