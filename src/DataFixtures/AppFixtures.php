<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Category;
use App\Entity\Tag;
use App\Entity\Tutorial;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        // 1. Création des utilisateurs
        $users = [];

        $userData = [
            ['Melek', 'melek@devdocs.com', 'password123'],
            ['Ahmed', 'ahmed@devdocs.com', 'password123'],
            ['Sarra', 'sarra@devdocs.com', 'password123'],
        ];

        foreach ($userData as [$pseudo, $email, $password]) {
            $user = new User();
            $user->setPseudonyme($pseudo);
            $user->setEmail($email);
            $user->setRoles(['ROLE_USER']);
            $user->setPassword(
                $this->passwordHasher->hashPassword($user, $password)
            );

            $manager->persist($user);
            $users[] = $user;
        }

        // 2. Création des catégories
        $categoryNames = [
            'PHP',
            'Symfony',
            'JavaScript',
            'Python',
            'Développement Web',
        ];

        $categories = [];

        foreach ($categoryNames as $name) {
            $category = new Category();
            $category->setName($name);
            $category->setDescription(
                'Tutoriels et ressources pour apprendre ' . $name . '.'
            );

            $manager->persist($category);
            $categories[] = $category;
        }

        // 3. Création des tags
        $tagNames = [
            'Débutant',
            'Avancé',
            'Backend',
            'Frontend',
            'API',
            'Programmation',
            'Framework',
            'Base de données',
            'HTML',
            'CSS',
        ];

        $tags = [];

        foreach ($tagNames as $name) {
            $tag = new Tag();
            $tag->setName($name);

            $manager->persist($tag);
            $tags[] = $tag;
        }

        // 4. Création des tutoriels
        $tutorialTitles = [
            'Introduction à PHP',
            'Les bases de Symfony',
            'Créer une API REST',
            'Découvrir JavaScript',
            'Apprendre Python',
            'Créer une application web',
            'Comprendre les bases de données',
            'Les contrôleurs Symfony',
            'Découvrir Twig',
            'Créer des formulaires Symfony',
            'Comprendre Doctrine ORM',
            'Les relations entre entités',
            'Introduction au HTML',
            'Maîtriser les feuilles CSS',
            'Sécuriser une application Symfony',
        ];

        $tutorialContents = [
            'PHP est un langage de programmation utilisé pour développer des applications web dynamiques. Ce tutoriel présente ses principales fonctionnalités.',
            'Symfony est un framework PHP puissant qui facilite la création d applications web structurées et maintenables.',
            'Une API REST permet à différentes applications de communiquer grâce à des requêtes HTTP et des réponses structurées.',
            'JavaScript permet de rendre les pages web interactives et dynamiques. Découvrez ses concepts fondamentaux.',
            'Python est un langage polyvalent et accessible. Apprenez les bases de la programmation avec des exemples pratiques.',
            'La création d une application web nécessite plusieurs technologies qui travaillent ensemble pour offrir une expérience utilisateur complète.',
            'Les bases de données permettent de stocker et organiser les informations utilisées par une application.',
            'Dans Symfony, les contrôleurs reçoivent les requêtes et préparent les réponses envoyées aux utilisateurs.',
            'Twig est le moteur de templates utilisé par Symfony pour générer des pages HTML dynamiques.',
            'Les formulaires Symfony permettent de récupérer et valider les informations saisies par les utilisateurs.',
            'Doctrine ORM facilite la communication entre les objets PHP et les tables de la base de données.',
            'Les relations entre entités permettent de représenter les associations entre les différentes données d une application.',
            'HTML est le langage de base utilisé pour structurer le contenu des pages web.',
            'CSS permet de personnaliser le design des pages web avec des couleurs, des espacements et des mises en page.',
            'La sécurité Symfony permet de protéger les pages et les fonctionnalités selon les rôles des utilisateurs.',
        ];

        $tutorials = [];

        foreach ($tutorialTitles as $index => $title) {
            $tutorial = new Tutorial();

            $tutorial->setTitle($title);
            $tutorial->setSlug('tutoriel-' . ($index + 1));
            $tutorial->setContent($tutorialContents[$index]);
            $tutorial->setCreatedAt(
                new \DateTimeImmutable('-' . (15 - $index) . ' days')
            );

            $tutorial->setAuthor($users[$index % 3]);
            $tutorial->setCategory($categories[$index % 5]);

            // Ajouter deux tags à chaque tutoriel
            $tutorial->addTag($tags[$index % 10]);
            $tutorial->addTag($tags[($index + 1) % 10]);

            $manager->persist($tutorial);
            $tutorials[] = $tutorial;
        }

        // 5. Création de 30 commentaires
        $commentTexts = [
            'Merci pour ce tutoriel, les explications sont très claires !',
            'Excellent article, cela m a beaucoup aidé à comprendre.',
            'Très intéressant, j aimerais voir davantage d exemples pratiques.',
            'Le contenu est bien expliqué et facile à suivre.',
            'Merci pour le partage, vivement le prochain tutoriel !',
            'Une excellente introduction pour les débutants.',
            'J ai appris plusieurs nouvelles choses grâce à cet article.',
            'Les exemples sont très utiles pour comprendre le sujet.',
            'Très bon travail, continuez comme ça !',
            'Ce tutoriel m a permis de résoudre un problème.',
        ];

        for ($i = 0; $i < 30; $i++) {
            $comment = new Comment();

            $comment->setContent($commentTexts[$i % 10]);
            $comment->setCreatedAt(
                new \DateTimeImmutable('-' . ($i + 1) . ' days')
            );

            $comment->setAuthor($users[$i % 3]);
            $comment->setTutorial($tutorials[$i % 15]);

            $manager->persist($comment);
        }

        // Enregistrer toutes les données
        $manager->flush();
    }
}