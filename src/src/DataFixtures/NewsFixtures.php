<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\News;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use function Symfony\Component\Translation\t;

class NewsFixtures extends Fixture implements DependentFixtureInterface
{
    private CategoryRepository $categoryRepository;
    private UserRepository $userRepository;

    public function __construct(CategoryRepository $categoryRepository, UserRepository $userRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $categories = $this->categoryRepository->findAll();
        $users = $this->userRepository->findAll();
        for ($i = 0; $i < 20; $i++) {
            $news = new News();
            $tags = [
                0 => 'атмосфера',
                1 => 'covid',
                2 => 'победа',
                3 => 'бизнес',
                4 => 'луна',
                5 => 'мечта',
                6 => 'родина',
                7 => 'поездка',
                8 => 'лес',
                9 => 'режиссер',
                10 => 'кино'
            ];


            $news->setCategory($categories[$i]);
            $news->setAuthor($users[$i]);
            $news->setTitle('Tittle' . $i);
            $news->setBody("Если человек $i$i привился от COVID, а через два месяца заболел, QR-код ему продлевается на
             шесть месяцев. Получается, у него QR-код всего не на $i месяцев, а на восемь. Дело в том, что иммунитет 
             недостаточно хорошо сформировался, раз человек заразился. Всё равно человеку желательно через полгода 
             получить препарат. И это рекомендация Минздрава. Например, «Спутником V» вакцинировался, потом заболел 
             через два месяца, ему через полгода желательно повторить инъекцию, допустим, «Спутником Лайт». 
             Это универсальная рекомендация, — подчеркнул Леонов." . $i);
            $news->setTags([$tags[mt_rand(0,4)],$tags[mt_rand(5,10)] ]);
            $news->setCreatedAt(new \DateTimeImmutable('now'));
            $manager->persist($news);

        }
        $manager->flush();
    }


//    function generateString(int $strength): string
//    {
//        $input = ' йцукенгшщзхъфыва пролджэячсмитьбю ЙЦУКЕНГШЩЗХЪ ФЫВАПРОЛДЖЭЯЧСМИТЬБЮ ';
//        $input_length = strlen($input);
//        $random_string = '';
//        for ($i = 0; $i < $strength; $i++) {
//            $random_character = $input[random_int(0, $input_length - 1)];
//            $random_string .= $random_character;
//        }
//
//        return $random_string;
//    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            CategoryFixtures::class,
        ];
    }
}