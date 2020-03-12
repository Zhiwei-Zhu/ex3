<?php

namespace App\Controller;

use App\Entity\Matiere;
use App\Entity\Note;
use App\Form\MatieresType;
use App\Form\NoteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MoyenController extends AbstractController
{
    /**
     * @Route("/", name="moyen")
     */
    public function index(EntityManagerInterface $entityManager, Request $request)
    {
        $notes = new Note();

        $form = $this->createForm(NoteType::class,$notes);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $note= $form->getData();

            $entityManager->persist($note);
            $entityManager->flush();

        }
        $noterepo=$this->getDoctrine()
            ->getRepository(Note::class)
            ->findAll();

        $moyen =0;
        $count= 0;

        foreach($noterepo as $value){
            $moyen += $value->getNote()*$value->getMatiere()->getCoef();
            $count +=$value->getMatiere()->getCoef();
         }



        return $this->render('moyen/index.html.twig', [
            'controller_name' => 'MoyenController',
            'form'=>$form->createView(),
            'notes'=>$noterepo,
            'moyen'=>$moyen/$count,
        ]);
    }
    /**
     * @Route("/matière", name="matières")
     */
    public function matiere(EntityManagerInterface $entityManager, Request $request)
    {
        $matieres = new Matiere();

        $form = $this->createForm(MatieresType::class, $matieres);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $matiere= $form->getData();

            $entityManager->persist($matiere);
            $entityManager->flush();

        }

        $MatiereRepo = $this->getDoctrine()
            ->getRepository(Matiere::class)
            ->findAll();

        return $this->render('moyen/matière.html.twig', [
            'controller_name' => 'MoyenController',
            'form'=> $form->createView(),
            'matieres'=> $MatiereRepo
        ]);
    }
    /**
     * @Route("/matière/{id}", name="update")
     */
    public function singlematiere($id,EntityManagerInterface $entityManager, Request $request)
    {
        $MatiereRepo = $this->getDoctrine()
            ->getRepository(Matiere::class)
            ->find($id);

        $form = $this->createForm(MatieresType::class, $MatiereRepo);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $matiere= $form->getData();

            $entityManager->persist($matiere);
            $entityManager->flush();

        }

        return $this->render('moyen/singlematiere.html.twig', [
            'controller_name' => 'MoyenController',
            'form'=> $form->createView(),
            'matiere'=> $MatiereRepo
        ]);
    }
    /**
     * @Route("/matière/remove/{id}", name="remove")
     */
    public function remove($id,EntityManagerInterface $entityManager, Request $request)
    {
        $MatiereRepo = $this->getDoctrine()
            ->getRepository(Matiere::class)
            ->find($id);

        $NoteRepo = $this->getDoctrine()
            ->getRepository(Note::class)
            ->findBy(['matiere'=>['id'=>$id]]);


        $entityManager->remove($MatiereRepo);
        $entityManager->remove($NoteRepo);
        $entityManager->flush();

        return $this->redirectToRoute('matières');
    }
}
