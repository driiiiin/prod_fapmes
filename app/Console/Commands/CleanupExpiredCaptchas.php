<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Session;

class CleanupExpiredCaptchas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'captcha:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up expired captcha data from sessions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting captcha cleanup...');

        $cleanedCount = 0;
        $sessionData = session()->all();

        foreach ($sessionData as $key => $value) {
            if (str_starts_with($key, 'captcha_expires_')) {
                $captchaId = str_replace('captcha_expires_', '', $key);
                $expiresAt = $value;

                if (now()->timestamp > $expiresAt) {
                    // Remove expired captcha data
                    session()->forget([
                        "captcha_code_{$captchaId}",
                        "captcha_expires_{$captchaId}"
                    ]);
                    $cleanedCount++;
                }
            }
        }

        $this->info("Cleaned up {$cleanedCount} expired captchas.");

        return Command::SUCCESS;
    }
}
