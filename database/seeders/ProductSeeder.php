<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = $this->createCategories();

        Product::create([
            'ref' => '.LEGAPO',
            'price_acquisition' => 380,
            'price_selling' => 570,
            'main_category_id' => $categories['vege']->id,
            'name' => 'Asperge verte pointe',
        ])->categories()->attach([$categories['vege']->id, $categories['discounts']->id]);

        Product::create([
            'ref' => '.LEGAUL',
            'price_acquisition' => 190,
            'price_selling' => 285,
            'main_category_id' => $categories['vege']->id,
            'name' => 'Aubergine allongée',
        ])->categories()->attach([$categories['vege']->id]);

        Product::create([
            'ref' => '.ANPO',
            'price_acquisition' => 183,
            'price_selling' => 256,
            'main_category_id' => $categories['frozen']->id,
            'name' => 'Anneaux de poireau',
        ])->categories()->attach([$categories['frozen']->id, $categories['vege']->id]);

        Product::create([
            'ref' => '.97186',
            'price_acquisition' => 300,
            'price_selling' => 420,
            'main_category_id' => $categories['frozen']->id,
            'name' => 'Brunoise de légumes',
        ])->categories()->attach([$categories['frozen']->id, $categories['vege']->id, $categories['discounts']->id]);

        Product::create([
            'ref' => '.CFDB6',
            'price_acquisition' => 1344,
            'price_selling' => 1822,
            'main_category_id' => $categories['deserts']->id,
            'name' => 'Coupe fruits des bois',
        ])->categories()->attach([$categories['deserts']->id, $categories['frozen']->id]);

        Product::create([
            'ref' => '.53121',
            'price_acquisition' => 652,
            'price_selling' => 913,
            'main_category_id' => $categories['deserts']->id,
            'name' => 'Coupe crème glacée brésilienne',
        ])->categories()->attach([$categories['deserts']->id, $categories['frozen']->id]);

        Product::create([
            'ref' => '.YAGREC',
            'price_acquisition' => 202,
            'price_selling' => 248,
            'main_category_id' => $categories['deserts']->id,
            'name' => 'Yaourt grec',
        ])->categories()->attach([$categories['deserts']->id]);

        Product::create([
            'ref' => '.MOJ',
            'price_acquisition' => 385,
            'price_selling' => 578,
            'main_category_id' => $categories['drinks']->id,
            'name' => 'Mojito sans alcool',
        ])->categories()->attach([$categories['drinks']->id]);

        Product::create([
            'ref' => '.FOGC',
            'price_acquisition' => 1728,
            'price_selling' => 2074,
            'main_category_id' => $categories['drinks']->id,
            'name' => 'Fanta Orange',
        ])->categories()->attach([$categories['drinks']->id]);

        Product::create([
            'ref' => '.TRCO',
            'price_acquisition' => 1428,
            'price_selling' => 1856,
            'main_category_id' => $categories['drinks']->id,
            'name' => 'Tropico Original',
        ])->categories()->attach([$categories['drinks']->id, $categories['discounts']->id]);
    }

    protected function createCategories(): array
    {
        return [
            'vege' => Category::create(['name' => 'Fruits & légumes', 'order' => 1]),
            'deserts' => Category::create(['name' => 'Desserts', 'order' => 2]),
            'frozen' => Category::create(['name' => 'Surgelés', 'order' => 4]),
            'drinks' => Category::create(['name' => 'Boissons', 'order' => 3]),
            'discounts' => Category::create(['name' => 'Promotions', 'order' => 0]),
        ];
    }
}
