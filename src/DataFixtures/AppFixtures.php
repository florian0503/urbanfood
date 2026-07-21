<?php

namespace App\DataFixtures;

use App\Entity\MenuCategory;
use App\Entity\MenuItem;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private const MENU = [
        [
            'name' => 'Tacos',
            'tag' => 'GRATINÉS MINUTE',
            'items' => [
                ['name' => 'Le Classique', 'description' => 'poulet crispy, frites, sauce fromagère maison', 'price' => '8,50'],
                ['name' => 'Le Carnivore', 'description' => 'kefta + tenders, cheddar, sauce algérienne', 'price' => '9,90', 'featuredPosition' => 1, 'tag' => 'BEST-SELLER', 'featuredDescription' => 'Tacos kefta + tenders, cheddar fondu, sauce algérienne maison'],
                ['name' => 'Le Green', 'description' => 'poulet grillé, guacamole, cheddar, pickles', 'price' => '9,50'],
                ['name' => 'Le Végé', 'description' => 'falafels maison, sauce blanche, crudités', 'price' => '8,90'],
            ],
        ],
        [
            'name' => 'Smash burgers',
            'tag' => 'PRESSÉS MINUTE',
            'items' => [
                ['name' => 'UF Smash', 'description' => 'steak smashé, double cheddar, oignons caramélisés', 'price' => '8,90', 'featuredPosition' => 2, 'tag' => 'SIGNATURE', 'featuredDescription' => 'Steak smashé minute, double cheddar, oignons caramélisés, sauce UF'],
                ['name' => 'Double Trouble', 'description' => 'double steak, triple cheddar, bacon de bœuf, BBQ fumée', 'price' => '11,50'],
                ['name' => 'Spicy One', 'description' => 'jalapeños, cheddar, sauce samouraï maison', 'price' => '9,90'],
                ['name' => 'Crispy Chick', 'description' => 'poulet pané, coleslaw, miel-moutarde', 'price' => '9,50'],
            ],
        ],
        [
            'name' => 'Sides',
            'tag' => 'À PARTAGER (OU PAS)',
            'items' => [
                ['name' => 'Frites maison', 'description' => 'coupées et cuites sur place, sel fumé', 'price' => '3,50'],
                ['name' => 'Cheesy Fries', 'description' => 'sauce fromagère, oignons frits, ciboulette', 'price' => '5,50', 'featuredPosition' => 3, 'tag' => 'À PARTAGER', 'featuredDescription' => 'Frites maison, sauce fromagère coulante, oignons frits, ciboulette'],
                ['name' => 'Tenders ×5', 'description' => 'filets de poulet panés, 2 sauces au choix', 'price' => '6,90'],
                ['name' => 'Onion Rings ×8', 'description' => 'panure maison, sauce ranch', 'price' => '4,50'],
            ],
        ],
        [
            'name' => 'Desserts & drinks',
            'tag' => 'POUR FINIR PROPRE',
            'items' => [
                ['name' => 'Cookie XXL', 'description' => 'chocolat au lait, cœur coulant, servi tiède', 'price' => '3,00'],
                ['name' => 'Tiramisu spéculoos', 'description' => 'fait maison, en pot, généreux', 'price' => '4,50'],
                ['name' => 'Milkshake', 'description' => 'vanille, chocolat ou spéculoos', 'price' => '5,00'],
                ['name' => 'Boissons', 'description' => 'sodas 33cl, iced tea maison, eau', 'price' => '1,50+'],
            ],
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::MENU as $categoryPosition => $categoryData) {
            $category = new MenuCategory();
            $category->setName($categoryData['name']);
            $category->setTag($categoryData['tag']);
            $category->setPosition($categoryPosition);
            $manager->persist($category);

            foreach ($categoryData['items'] as $itemPosition => $itemData) {
                $item = new MenuItem();
                $item->setName($itemData['name']);
                $item->setDescription($itemData['description']);
                $item->setPrice($itemData['price']);
                $item->setPosition($itemPosition);
                $item->setFeatured(isset($itemData['featuredPosition']));
                $item->setFeaturedPosition($itemData['featuredPosition'] ?? null);
                $item->setTag($itemData['tag'] ?? null);
                $item->setFeaturedDescription($itemData['featuredDescription'] ?? null);
                $item->setCategory($category);
                $manager->persist($item);
            }
        }

        $manager->flush();
    }
}
