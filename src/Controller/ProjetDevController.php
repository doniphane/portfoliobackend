<?php

namespace App\Controller;

use App\Entity\ProjetDev;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ProjetDevController extends AbstractController
{
    #[Route('/api/projet_devs/create', name: 'api_projet_devs_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): Response
    {
        $data = $request->request->all();
        $files = $request->files->all();

        // Récupérer l'utilisateur
        $user = $entityManager->getRepository(User::class)->find(1);
        if (!$user) {
            return $this->json(['error' => 'User not found'], 404);
        }

        // Créer le projet
        $projetDev = new ProjetDev();
        $projetDev->setName($data['name'] ?? '');
        $projetDev->setDescription($data['description'] ?? '');
        $projetDev->setWebsiteLink($data['websiteLink'] ?? null);
        $projetDev->setGithubLink($data['githubLink'] ?? null);
        $projetDev->setUser($user);

        // Gérer les technologies
        if (isset($data['technologie']) && is_array($data['technologie'])) {
            $projetDev->setTechnologie($data['technologie']);
        }

        // Gérer l'upload d'image
        if (isset($files['imageFile']) && $files['imageFile']) {
            $projetDev->setImageFile($files['imageFile']);
        }

        $entityManager->persist($projetDev);
        $entityManager->flush();

        return $this->json([
            'id' => $projetDev->getId(),
            'name' => $projetDev->getName(),
            'description' => $projetDev->getDescription(),
            'technologie' => $projetDev->getTechnologie(),
            'imageName' => $projetDev->getImageName(),
            'websiteLink' => $projetDev->getWebsiteLink(),
            'githubLink' => $projetDev->getGithubLink(),
            'user' => $user->getEmail()
        ], 201);
    }
}