<?php

namespace App\DataFixtures;

use App\Entity\Tweets;
use App\Entity\User;
use App\Entity\Follows;
use App\Entity\Likes;
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
        $usersData = [
            ["Alice", "alice@coda.fr", "alice", "Passionnée de design et de café. ☕ Toujours en quête du pixel parfait."],
            ["Bob", "bob@coda.fr", "bob", "Fan de littérature classique et de nouvelles technologies. 📚✨"],
            ["Charlie", "charlie@coda.fr", "charlie", "Toujours la tête dans les nuages... ou dans le Cloud. ☁️ Architecte infra."],
            ["JN", "jn@coda.fr", "jn", "Expert en cybersécurité. J'aime comprendre comment les choses se cassent."],
            ["Clémence", "clemence@coda.fr", "clemence", "Développeur Symfony le jour, gamer la nuit. 🎮 Code is life."],
            ["Titi", "titi@coda.fr", "titi", "Petit mais costaud ! Amateur de crossfit et de cuisine saine. 🍎"],
            ["Toto", "toto@coda.fr", "toto", "Le roi des blagues (pas toujours drôles). Je vis pour faire rire."],
            ["Tutu", "tutu@coda.fr", "tutu", "Danseuse étoile dans l'âme, développeuse Front-end dans la vraie vie. 🩰"],
            ["Tyty", "tyty@coda.fr", "tyty", "Discret mais efficace. Grand amateur de philosophie et de thé vert."],
            ["Tata", "tata@coda.fr", "tata", "La tante préférée du réseau ! Je partage mes meilleures recettes de gâteaux. 🍰"],
            ["Tete", "tete@coda.fr", "tete", "Globe-trotteur et photographe amateur. 📸 Le monde est mon terrain de jeu."],
        ];

        foreach ($usersData as $data) {
            $this->generatedUsers[] = $this->generateUser($data[0], $data[1], $data[2], $data[3]);
        }

        // 2. Génération des Tweets
        $messages = [
            "Enfin le week-end, direction la plage ! 🏖️",
            "Quelqu'un a une solution pour l'erreur 500 sur Symfony ? 😅",
            "Le nouveau projet avance super vite, j'adore l'architecture.",
            "Incroyable le dernier épisode de la série, j'en reviens pas ! 😱",
            "Petit café matinal en travaillant sur mes fixtures. ☕",
            "Est-ce que PHP est vraiment mort ? Je ne crois pas ! 🐘",
            "Rappel : n'oubliez pas de commit vos changements régulièrement.",
            "La météo est parfaite aujourd'hui pour aller courir. 🏃‍♂️",
            "Je cherche un bon resto sur Paris, des recommandations ? 🍕",
            "Une journée productive se termine. Demain sera encore mieux !",
            "Apprendre le TypeScript après le PHP, c'est un vrai défi.",
            "Qui d'autre utilise Docker pour ses projets locaux ? 🐳",
            "Vraiment hâte de partir en vacances le mois prochain... ✈️",
            "Mon chat vient de marcher sur mon clavier, adieu mon code. 🐈",
            "Le design pattern Strategy est vraiment élégant dans ce cas.",
            "Un grand merci à Adrien pour sa patience et son aide, c'est le meilleur !",
            "Sensation géniale quand ton test unitaire passe au vert ! ✅",
            "Charlot le beau gosse !!",
            "Soirée gaming entre potes ce soir, ça va être épique ! 🎮",
            "JN arrête de fumer !!",
            "C'est enfin l'heure de la pause déjeuner. Bon appétit ! 🍱",
            "Bisous des 3 petites chipies qui ont créé ce site <3."
        ];

        foreach ($messages as $msg) {
            $this->generatedTweets[] = $this->generateTweets($msg);
        }

        // 3. Génération des Follows et des Likes
        foreach ($this->generatedUsers as $user) {
            $this->generateFollows($user);
            $this->generateLikes($user);
        }

        $this->manager->flush();
    }

    private function generateUser(string $username, string $email, string $password, string $bio): User
    {
        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setBio($bio);
        $user->setPassword($this->hasher->hashPassword($user, $password));
        $user->setRoles(['ROLE_USER']);

        $this->manager->persist($user);
        return $user;
    }

    public function generateTweets(string $message): Tweets
    {
        $tweet = new Tweets();
        $tweet->setUid(Uuid::v7()->toString());
        $tweet->setMessage($message);

        $author = $this->generatedUsers[array_rand($this->generatedUsers)];
        $tweet->setCreatedBy($author);
        $tweet->setCreatedDate(new \DateTime());
        $tweet->setIsDeleted(false);

        $this->manager->persist($tweet);
        return $tweet;
    }

    private function generateFollows(User $follower): void
    {
        $potentialFollowed = array_filter($this->generatedUsers, fn($u) => $u !== $follower);
        shuffle($potentialFollowed);
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

    private function generateLikes(User $user): void
    {
        // On mélange les tweets pour chaque utilisateur
        $tweetsToLike = $this->generatedTweets;
        shuffle($tweetsToLike);

        // L'utilisateur like entre 3 et 8 tweets aléatoires
        $selectedTweets = array_slice($tweetsToLike, 0, rand(3, 8));

        foreach ($selectedTweets as $tweet) {
            $like = new Likes();
            $like->setTweet($tweet);

            // On utilise la propriété héritée de BaseEntity pour identifier le "liker"
            $like->setCreatedBy($user);
            $like->setCreatedDate(new \DateTime());

            $this->manager->persist($like);
        }
    }
}
