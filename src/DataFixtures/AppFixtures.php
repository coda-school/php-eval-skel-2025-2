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
        $this->generatedUsers[] = $this->generateUser("Charlie", "charlie@coda.fr", "charlie");
        $this->generatedUsers[] = $this->generateUser("JN", "jn@coda.fr", "jn");
        $this->generatedUsers[] = $this->generateUser("Clémence", "clemence@coda.fr", "clemence");
        $this->generatedUsers[] = $this->generateUser("Titi", "titi@coda.fr", "titi");
        $this->generatedUsers[] = $this->generateUser("Toto", "toto@coda.fr", "toto");
        $this->generatedUsers[] = $this->generateUser("Tutu", "tutu@coda.fr", "tutu");
        $this->generatedUsers[] = $this->generateUser("Tyty", "tyty@coda.fr", "tyty");
        $this->generatedUsers[] = $this->generateUser("tata", "tata@coda.fr", "tata");
        $this->generatedUsers[] = $this->generateUser("Tete", "tete@coda.fr", "tete");

        // 2. Génération des Tweets
        $this->generatedTweets[] = $this->generateTweets("Enfin le week-end, direction la plage ! 🏖️");
        $this->generatedTweets[] = $this->generateTweets("Quelqu'un a une solution pour l'erreur 500 sur Symfony ? 😅");
        $this->generatedTweets[] = $this->generateTweets("Le nouveau projet avance super vite, j'adore l'architecture.");
        $this->generatedTweets[] = $this->generateTweets("Incroyable le dernier épisode de la série, j'en reviens pas ! 😱");
        $this->generatedTweets[] = $this->generateTweets("Petit café matinal en travaillant sur mes fixtures. ☕");
        $this->generatedTweets[] = $this->generateTweets("Est-ce que PHP est vraiment mort ? Je ne crois pas ! 🐘");
        $this->generatedTweets[] = $this->generateTweets("Rappel : n'oubliez pas de commit vos changements régulièrement.");
        $this->generatedTweets[] = $this->generateTweets("La météo est parfaite aujourd'hui pour aller courir. 🏃‍♂️");
        $this->generatedTweets[] = $this->generateTweets("Je cherche un bon resto sur Paris, des recommandations ? 🍕");
        $this->generatedTweets[] = $this->generateTweets("Une journée productive se termine. Demain sera encore mieux !");
        $this->generatedTweets[] = $this->generateTweets("Apprendre le TypeScript après le PHP, c'est un vrai défi.");
        $this->generatedTweets[] = $this->generateTweets("Qui d'autre utilise Docker pour ses projets locaux ? 🐳");
        $this->generatedTweets[] = $this->generateTweets("Vraiment hâte de partir en vacances le mois prochain... ✈️");
        $this->generatedTweets[] = $this->generateTweets("Mon chat vient de marcher sur mon clavier, adieu mon code. 🐈");
        $this->generatedTweets[] = $this->generateTweets("Le design pattern Strategy est vraiment élégant dans ce cas.");
        $this->generatedTweets[] = $this->generateTweets("Un grand merci à Adrien pour sa patience et son aide, c'est le meilleur !");
        $this->generatedTweets[] = $this->generateTweets("Sensation géniale quand ton test unitaire passe au vert ! ✅");
        $this->generatedTweets[] = $this->generateTweets("Charlot le beau gosse !!");
        $this->generatedTweets[] = $this->generateTweets("Soirée gaming entre potes ce soir, ça va être épique ! 🎮");
        $this->generatedTweets[] = $this->generateTweets("JN arrête de fumer !!");
        $this->generatedTweets[] = $this->generateTweets("C'est enfin l'heure de la pause déjeuner. Bon appétit ! 🍱");
        $this->generatedTweets[] = $this->generateTweets("Bisous des 3 petites chipies qui ont crée ce site <3.");


        // 3. Génération des liens Follows
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

        // On pioche un user au hasard dans notre liste
        $author = $this->generatedUsers[array_rand($this->generatedUsers)];

        $tweet->setCreatedBy($author);
        $tweet->setCreatedDate(new \DateTime());

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

        return $user;
    }

    // Méthode pour générer les follows aléatoires
    private function generateFollows(User $follower): void
    {
        // 1. On récupère tous les utilisateurs sauf celui qui va suivre
        $potentialFollowed = array_filter($this->generatedUsers, fn($u) => $u !== $follower);

        // 2. On mélange le tableau aléatoirement
        shuffle($potentialFollowed);

        // 3. On prend les 5 premiers utilisateurs du tableau mélangé
        $toFollow = array_slice($potentialFollowed, 0, 5);

        foreach ($toFollow as $followed) {
            $follow = new Follows();
            $follow->setFollower($follower);
            $follow->setFollowed($followed);

            $follow->setCreatedBy($follower);
            $follow->setCreatedDate(new \DateTime());

            $this->manager->persist($follow);
        }
    }
}
