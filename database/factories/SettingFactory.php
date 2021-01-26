<?php

namespace PrionDevelopment\Setting\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use PrionDevelopment\Setting\Models\Setting;
use PrionDevelopment\Setting\Traits\ValueTypeTrait;

class SettingFactory extends Factory
{
    use ValueTypeTrait;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Setting::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'key' => $this->faker->unique()->word,
            'type' => $this->faker->randomElement($this->valueTypes),
            'value' => $this->faker->sentence,
        ];
    }

    public function array()
    {
        return $this->state( function (array $attributes) {
            return [
                'key' => $this->faker->unique()->word,
                'type' => 'array',
                'value' => json_encode($this->faker->words()),
            ];
        });
    }

    public function boolean()
    {
        return $this->state( function (array $attributes) {
            return [
                'key' => $this->faker->unique()->word,
                'type' => 'boolean',
                'value' => $this->faker->boolean,
            ];
        });
    }

    public function integer()
    {
        return $this->state( function (array $attributes) {
            return [
                'key' => $this->faker->unique()->word,
                'type' => 'integer',
                'value' => $this->faker->randomNumber(),
            ];
        });
    }

    public function json()
    {
        return $this->state( function (array $attributes) {
            return [
                'key' => $this->faker->unique()->word,
                'type' => 'json',
                'value' => json_encode($this->faker->words()),
            ];
        });
    }

    public function object()
    {
        return $this->state( function (array $attributes) {
            return [
                'key' => $this->faker->unique()->word,
                'type' => 'object',
                'value' => json_encode(Setting::factory()->make()),
            ];
        });
    }

    public function string()
    {
        return $this->state( function (array $attributes) {
            return [
                'key' => $this->faker->unique()->word,
                'type' => 'string',
                'value' => $this->faker->sentence,
            ];
        });
    }
}
