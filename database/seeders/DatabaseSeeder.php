<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        User::truncate();
        Category::truncate();
        Product::truncate();
        Transaction::truncate();
        DB::table('category_product')->truncate();

        User::flushEventListeners();
        Category::flushEventListeners();
        Product::flushEventListeners();
        Transaction::flushEventListeners();

        $usersQuantity = 1000;
        $categoriesQuantity = 30;
        $productsQuantity = 1000;
        $transactionsQuantity = 1000;

        \App\Models\User::factory($usersQuantity)->create();
        \App\Models\Category::factory($categoriesQuantity)->create();
        \App\Models\Product::factory($productsQuantity)->create()->each(
            function ($product){
            $categories = Category::all()->random(mt_rand(1,5))->pluck('id');
            $product->categories()->attach($categories);
            }
    );
        \App\Models\Transaction::factory($transactionsQuantity)->create();

    }
}
