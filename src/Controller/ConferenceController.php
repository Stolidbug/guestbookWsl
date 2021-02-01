<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Conference;
use App\Form\CommentType;
use App\Form\ConferenceType;
use App\Service\FileUploader;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConferenceController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="conference")
     */

    public function index(EntityManagerInterface $em): Response
    {


        $conferences = $em->getRepository(Conference::class)->findAll();

        return $this->render('conference/index.html.twig', [
            'conferences' => $conferences,
        ]);
    }

    /**
     * @Route("/edition/conference")
     */

    public function created(Request $request, FileUploader $fileUploader)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $confe = new Conference();
        $form = $this->createForm(ConferenceType::class, $confe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $confe->setCreatedAt(new \DateTime());

            if ($file = $form->get('image')->getData()) {
                $fileName = $fileUploader->upload($file);
                $confe->setImage($fileName);
            }
            try {
                $this->em->persist($confe);
                $this->em->flush();
            } catch (\Exception $e) {
                $this->addFlash('warning', 'une erreur est survenue :'.$e->getMessage());

                return $this->redirectToRoute('conference');
            }
            $this->addFlash('message', 'Felicition la conference est bien crée');

            return $this->redirectToRoute('conference');
        }

        return $this->render('conference/create.html.twig', [
            'formConference' => $form->createView(),
        ]);
    }


    /**
     * @Route("/conference/{id}")
     */

    public function show(Conference $confe, Request $request,EntityManagerInterface $em): Response
    {
        $message = new Comment();
        $comment = $em->getRepository(Comment::class)->findAll();

        $form = $this->createForm(CommentType::class, $message);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $message->setCreatedAt(new \DateTime())
                ->setAuthor($this->getUser()->getPseudo())
                ->setUsers($this->getUser())
                ->setConference($confe);

            $this->em->persist($message);
            $this->em->flush();

            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();

            return $this->redirectToRoute('conference/show.html', [
                'id' => $confe->getId(),
            ]);
        }
        return $this->render('conference/show.html.twig',[
            'conference' => $confe,
            'comments'=>$comment,
            'messageForm' =>  $form->createView(),
        ]);
    }

    /**
     * @Route ("/update/{id}")
     */

    public function update(Conference $confe,Request $request, FileUploader $fileUploader)
    {
        $this->denyAccessUnlessGranted('EDIT', $confe);
        $form = $this->createForm(ConferenceType::class, $confe);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            if ($file = $form->get('file')->getData()) {
                $fileName = $fileUploader->upload($file);
                $confe->setImage($fileName);
            }

            $this->em->persist($confe);
            $this->em->flush();

            return $this->redirectToRoute('app_app_show',
                ['id' => $confe->getId()]);
        }

        return $this->render('conference/create.html.twig', [
            'formConference' => $form->createView(),
            'conference' => $confe,
            'editMode' => null !== $confe->getId(),
        ]);
    }






    /**
     * @Route ("/delete/{id}")
     */

    public function delete(Conference $confe)
    {
        $this->denyAccessUnlessGranted('EDIT', $confe);
        try {
            $this->em->remove($confe);
            $this->em->flush();
        }catch (\Exception $e) {
            $this->addFlash('warning','la supression na pas pue aboutir:' .$e->getMessage());

            return $this->redirectToRoute('conference');
        }
        $this->addFlash('message','la surpression a bien ete prise en compte');

        return $this->redirectToRoute('conference');
    }
}
