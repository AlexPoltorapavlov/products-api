<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\User;
use App\Entity\UserFavorite;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    // #[Route('/1', name: 'home')]
    // public function index(EntityManagerInterface $entityManager)
    // {
    //     // create a image
    //     $image = new Image();
    //     $image->setName('Cat');
    //     $entityManager->persist($image);

    //     // create a user
    //     $user = new User();
    //     $user->setPassword('password');
    //     $user->setName('Jone Doe');
    //     $entityManager->persist($user);
        
    //     // create a link 
    //     $userFavorite = new UserFavorite();
    //     $userFavorite->setCreatedAt(date_create_immutable('now'));
    //     $userFavorite->addImage($image);
    //     $userFavorite->addUser($user);
    //     $entityManager->persist($userFavorite);

    //     $entityManager->flush();

    //     return new Response("Image {$image->getName()} is favorite for {$user->getName()}");
    // }

}