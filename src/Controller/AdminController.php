<?php

namespace App\Controller;

use App\Entity\Conference;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    /**
     * @Route("/admin")
     */
    public function index(EntityManagerInterface $em): Response
    {
        $conferences = $em->getRepository(Conference::class)->findAll();

        return $this->render('admin/conferences.html.twig', [
            'conferences' => $conferences,
        ]);
    }
}
