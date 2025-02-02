<?php

namespace App\Console\Commands;

use App\Models\GameConfig;
use Illuminate\Console\Command;
use App\Models\UserAttribute;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CheckLifeRefill extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:refill-life';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';


    function __construct(private readonly GameConfig $gameConfig)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = now();

        $refillAttributes = UserAttribute::where('user_attribute_definition_id', User::LAST_REFILL_TIMER)->get();

        $config = $this->gameConfig->getCoreConfig();

        $maxLives = $config->max_lives;
        $refillTime = $config->lives_refill_time;

        $refillAttributes->each(function ($attribute) use ($now, $maxLives, $refillTime) {
            $lastRefill = Carbon::parse($attribute->value);

            $diff = $lastRefill->diffInMinutes($now);

            if ($diff < $refillTime) {
                return;
            }

            $lives = $attribute->user->userAttributes->where('user_attribute_definition_id', User::LIVES)->first()->value;

            if ($lives >= $maxLives) {
                return;
            }

            $refillCount = floor($diff / $refillTime);

            if ($refillCount > 0) {

                $lives += $refillCount;

                if ($lives > $maxLives) {
                    $lives = $maxLives;
                }

                $attribute->user->userAttributes()->where('user_attribute_definition_id', User::LIVES)->update(['value' => $lives]);
                $attribute->update(['value' => time()]);
            }
        });
    }
}
