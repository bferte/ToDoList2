<?php

namespace App\Controller;

use App\Entity\Note;
use App\Repository\NoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NoteController extends AbstractController
{
    /**
     * @Route("/user/notes", name="notes")
     */
    public function index(NoteRepository $repo): Response
    {
        if($this->isGranted('ROLE_USER'))
        {

            $idUser = $this->getUser()->getId();

            $notes = $repo->findByUser($idUser);
            return $this->render('note/notes.html.twig', [
                'notes' => $notes
            ]);
            
        } else
        {
            return $this->redirectToRoute('login');
        }

        
    }


    /**
     * @Route("/user/notes/add", name="addNote")
     */
    public function addNote(Note $note,EntityManagerInterface $em)
    {
        $note = new Note();
        $note->setTitle('Keyboard');
        $note->setDescription(1999);
        $note->setDescription('Ergonomic and stylish!');

        $em->persist($note);

        $em->flush();
        
    }
}
