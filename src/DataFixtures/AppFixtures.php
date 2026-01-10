<?php

namespace App\DataFixtures;

use App\Entity\Tweets;
use App\Entity\User;
use App\Entity\Follows;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

class AppFixtures extends Fixture
{
    private array $generatedUsers = [];
    private array $generatedTweets = [];

    private UserPasswordHasherInterface $hasher;
    private ObjectManager $manager;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;
        $this->generatedUsers = [];
        $this->generatedTweets = [];

        // 1. Génération des Users
        $this->generatedUsers[] = $this->generateUser("Alice", "alice@coda.fr", "alice");
        $this->generatedUsers[] = $this->generateUser("Bob", "bob@coda.fr", "bob");

        // 2. Génération des Tweets (avec lien vers auteur aléatoire)
        $this->generatedTweets[] = $this->generateTweets("Vacances");
        $this->generatedTweets[] = $this->generateTweets("Colloc");
        $this->generatedTweets[] = $this->generateTweets("Montagne");

        // $messages = [
        //            "Enfin le week-end, direction la plage ! 🏖️",
        //            "Quelqu'un a une solution pour l'erreur 500 sur Symfony ? 😅",
        //            "Le nouveau projet avance super vite, j'adore l'architecture.",
        //            "Incroyable le dernier épisode de la série, j'en reviens pas ! 😱",
        //            "Petit café matinal en travaillant sur mes fixtures. ☕",
        //            "Est-ce que PHP est vraiment mort ? Je ne crois pas ! 🐘",
        //            "Rappel : n'oubliez pas de commit vos changements régulièrement.",
        //            "La météo est parfaite aujourd'hui pour aller courir. 🏃‍♂️",
        //            "Je cherche un bon resto sur Paris, des recommandations ? 🍕",
        //            "Une journée productive se termine. Demain sera encore mieux !",
        //            "Apprendre le TypeScript après le PHP, c'est un vrai défi.",
        //            "Qui d'autre utilise Docker pour ses projets locaux ? 🐳",
        //            "Vraiment hâte de partir en vacances le mois prochain... ✈️",
        //            "Mon chat vient de marcher sur mon clavier, adieu mon code. 🐈",
        //            "Le design pattern Strategy est vraiment élégant dans ce cas.",
        //            "Un grand merci à la communauté Stack Overflow, comme toujours.",
        //            "Sensation géniale quand ton test unitaire passe au vert ! ✅",
        //            "Charlot le beau gosse !!",
        //            "Soirée gaming entre potes ce soir, ça va être épique ! 🎮",
        //            "C'est enfin l'heure de la pause déjeuner. Bon appétit ! 🍱"
        //        ];

        // 3. Génération des liens Follows (Optionnel)
        foreach ($this->generatedUsers as $user) {
            $this->generateFollows($user);
        }
        $this->manager->flush();
    }
    // Méthode pour générer un Tweet lié à un User aléatoire
    public function generateTweets(string $message): Tweets
    {
        $tweet = new Tweets();
        $tweet->setUid(Uuid::v7()->toString());
        $tweet->setMessage($message);

        // LIEN DIRECT : On pioche un user au hasard dans notre liste
        $author = $this->generatedUsers[array_rand($this->generatedUsers)];

        $tweet->setCreatedBy($author); // Hérité de BaseEntity
        $tweet->setCreatedDate(new \DateTime());
        $tweet->setIsDeleted(false);

        $this->manager->persist($tweet);
        return $tweet;
    }

    // Méthode pour générer l'User
    private function generateUser(string $username, string $email, string $password): User
    {
        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPassword($this->hasher->hashPassword($user, $password));

        $this->manager->persist($user);
        // Optionnel : ne pas faire de flush ici, le laisser à la fin du load()

        return $user;
    }

    private function generateFollows(User $follower): void
    {
        // 1. On récupère tous les utilisateurs sauf celui qui va suivre
        $potentialFollowed = array_filter($this->generatedUsers, fn($u) => $u !== $follower);

        // 2. On mélange le tableau aléatoirement
        shuffle($potentialFollowed);

        // 3. On prend les 2 premiers utilisateurs du tableau mélangé
        $toFollow = array_slice($potentialFollowed, 0, 2);

        foreach ($toFollow as $followed) {
            $follow = new Follows();
            $follow->setFollower($follower);
            $follow->setFollowed($followed);

            // Si ton entité Follows hérite de BaseEntity :
            $follow->setCreatedBy($follower);
            $follow->setCreatedDate(new \DateTime());

            $this->manager->persist($follow);
        }
    }
}
