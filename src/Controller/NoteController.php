<?php

namespace App\Controller;

use App\Entity\Note;

use App\Form\NoteType;
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
    public function index(NoteRepository $repo, Request $request, EntityManagerInterface $em): Response
    {

        $note = new Note();
        $form = $this->createForm(NoteType::class, $note);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $note->setCreatedAt(new \DateTime());
            $note->setUser($this->getUser());
            $em->persist($note);
            $em->flush();

            $note = new Note();
            $form = $this->createForm(NoteType::class, $note);
        }

        $idUser = $this->getUser()->getId();

        $notes = $repo->findByUser($idUser);
        return $this->render('note/notes.html.twig', [
            'notes' => $notes,
            "form" => $form->createView(),
        ]);

    }

    /**
     * @Route ("/user/notes/sup/{id}", name="deleteNote")
     */
    public function deleteNote($id, EntityManagerInterface $em,NoteRepository $repo){

            $note = $repo->find($id);

            if(!$note) {
                throw new \Exception("id ne correspond a aucune note");
            }

            $em->remove($note);
            $em->flush();
            $this->addFlash('success', "La note à été supprimé !");


            return $this->redirectToRoute("notes");




    }

    /**
     * @Route("/user/notes/modif/{id}", name="modifNote")
     */
    public function upgradeNote( $id, Request $request, EntityManagerInterface $om,NoteRepository $repo){

        $note = $repo->find($id);

        $form = $this->createForm(NoteType::class,$note);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $om->persist($note);
            $om->flush();
            $this->addFlash('success', "La note à été modifié");
            return $this->redirectToRoute("notes");

        }

        return $this->render('note/noteUpgrade.html.twig',[
            "note" => $note,
            "form" => $form->createView()
        ]);
    }



}
