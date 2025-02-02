<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Services\ExperienceService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ExperienceServiceTest extends TestCase
{

  protected $experienceService;

  protected function setUp(): void
  {
    parent::setUp();
    $this->experienceService = new ExperienceService();
    $this->artisan('migrate');
    $this->artisan('db:seed');
  }

  public function test_actualize_experience_without_level_up()
  {
    $user = User::factory()->create();
    $user->userAttributes()->createMany([
      ['user_attribute_definition_id' => User::LEVEL, 'value' => 1],
      ['user_attribute_definition_id' => User::EXPERIENCE, 'value' => 50],
      ['user_attribute_definition_id' => User::COINS, 'value' => 100],
    ]);

    Cache::shouldReceive('remember')
      ->with('level_up_config', SHORT_CACHE_TIME, \Closure::class)
      ->andReturnUsing(function () {
        return [1 => 100, 2 => 200];
      });

    $result = $this->experienceService->actualizeExperience($user, 30);

    $this->assertEquals(['level' => ['level_up' => false, 'rewards' => ['coins' => 0]]], $result);
    $this->assertDatabaseHas('user_attributes', [
      'user_id' => $user->id,
      'user_attribute_definition_id' => User::EXPERIENCE,
      'value' => 80,
    ]);
  }

  public function test_actualize_experience_with_level_up()
  {
    $user = User::factory()->create();
    $user->userAttributes()->createMany([
      ['user_attribute_definition_id' => User::LEVEL, 'value' => 1],
      ['user_attribute_definition_id' => User::EXPERIENCE, 'value' => 90],
      ['user_attribute_definition_id' => User::COINS, 'value' => 100],
    ]);

    Cache::shouldReceive('remember')
      ->with('level_up_config', SHORT_CACHE_TIME, \Closure::class)
      ->andReturnUsing(function () {
        return [2 => 50];
      });

    Cache::shouldReceive('remember')
      ->with('level_rewards', SHORT_CACHE_TIME, \Closure::class)
      ->andReturnUsing(function () {
        return [2 => 50];
      });

    DB::beginTransaction();
    $result = $this->experienceService->actualizeExperience($user, experienceGained: 20);
    DB::rollBack();

    $this->assertEquals(['level' => ['level_up' => true, 'rewards' => ['coins' => 50]]], $result);
    $this->assertDatabaseHas('user_attributes', [
      'user_id' => $user->id,
      'user_attribute_definition_id' => User::LEVEL,
      'value' => 2,
    ]);
    $this->assertDatabaseHas('user_attributes', [
      'user_id' => $user->id,
      'user_attribute_definition_id' => User::COINS,
      'value' => 150,
    ]);
  }
}