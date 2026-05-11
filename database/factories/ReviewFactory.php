<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::inRandomOrder()->first()?->id_produit,
            'user_id' => User::where('role', 'client')->inRandomOrder()->first()?->id,
            'rating' => fake()->numberBetween(1, 5),
            'comment' => fake()->paragraph(),
            'is_approved' => true,
        ];
    }

    /**
     * Indicate that the review is approved.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_approved' => true,
        ]);
    }

    /**
     * Indicate that the review is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_approved' => false,
        ]);
    }

    /**
     * Indicate that the review has a high rating (4-5).
     */
    public function positive(): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => fake()->numberBetween(4, 5),
            'comment' => fake()->randomElement([
                'Excellent produit, je suis très satisfait de mon achat !',
                'Très bonne qualité, conforme à la description.',
                'Je recommande vivement ce produit.',
                'Parfait, rien à redire.',
                'Super produit, livraison rapide en plus.',
                'Qualité au rendez-vous, je rachèterai.',
                'Très content de cet achat, excellent rapport qualité-prix.',
                'Produit de qualité, je suis ravi.',
            ]),
        ]);
    }

    /**
     * Indicate that the review has a medium rating (3).
     */
    public function average(): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => 3,
            'comment' => fake()->randomElement([
                'Produit correct sans plus.',
                'Qualité moyenne, correspond au prix.',
                'Pas mal, mais pourrait être mieux.',
                'Correct, rien d\'exceptionnel.',
                'Mitigé sur ce produit.',
            ]),
        ]);
    }

    /**
     * Indicate that the review has a low rating (1-2).
     */
    public function negative(): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => fake()->numberBetween(1, 2),
            'comment' => fake()->randomElement([
                'Déçu par ce produit, ne correspond pas à la description.',
                'Qualité médiocre, je ne recommande pas.',
                'Produit arrivé endommagé, service client à améliorer.',
                'Pas satisfait du tout, je demande un remboursement.',
                'Très décevant, je ne rachèterai pas.',
                'Produit non conforme, attention à l\'achat.',
            ]),
        ]);
    }
}