<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Post;
use App\Entity\User;
use App\Entity\Comment;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
  const DEFAULT_FIXTURES_COUNT = 5;

  private $faker;

  private $manager;

  private $encoder;

  private $referenceCategories = [];

  private $referenceUsers = [];


  public function __construct(UserPasswordEncoderInterface $encoder)
  {
    $this->faker = Factory::create();
    $this->encoder = $encoder;
  }

  public function load(ObjectManager $manager)
  {
    $this->manager = $manager;

    $this->loadCategories();
    $this->loadUsers();
    $this->loadPosts(15);

    $this->manager->flush();
  }

  public function loadCategories($count = self::DEFAULT_FIXTURES_COUNT)
  {
    for($i = 0; $i < $count; $i++) {
      $category = new Category;
      $category->setTitle($this->faker->word);

      $this->manager->persist($category);

      $this->addReference($category->getTitle(), $category);
      $this->referenceCategories[] = $category->getTitle();
    }
  }

  public function loadUsers($count = self::DEFAULT_FIXTURES_COUNT)
  {
    for($i = 0; $i < $count; $i++) {
      $user = new User;
      $user->setName($this->faker->name);
      $user->setEmail($this->faker->email);

      $password = $this->encoder->encodePassword($user, $this->faker->password);
      $user->setPassword($password);

      $this->manager->persist($user);

      $this->addReference($user->getName(), $user);
      $this->referenceUsers[] = $user->getName();
    }
  }

  public function loadPosts($count = self::DEFAULT_FIXTURES_COUNT)
  {
    for($i = 0; $i < $count; $i++) {
      $post = new Post;
      $post->setTitle($this->faker->sentence(3));
      $post->setDescription($this->faker->text(200));
      $post->setContent($this->faker->text(500));
      $post->setImage('img_' . $i . '.jpg');
      $post->setViews($this->faker->numberBetween(0, 1500));

      $randomCategoryKey = $this->getRandomKey($this->referenceCategories);
      $randomCategoryTitle = $this->referenceCategories[$randomCategoryKey];  // is existing random category title
      $post->setCategory($this->getReference($randomCategoryTitle));

      $randomUserKey = $this->getRandomKey($this->referenceUsers);
      $randomUserName = $this->referenceUsers[$randomUserKey];  //is exitsting random user name
      $post->setUser($this->getReference($randomUserName));

      $this->manager->persist($post);
    }
  }


  private function getRandomKey(Array $array)
  {
    return $this->faker->numberBetween(0, count($array) - 1);
  }

}
