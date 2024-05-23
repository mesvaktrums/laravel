<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Log;
use Illuminate\Console\Command;
use App\Services\VtigerService;
use App\Models\User;
class CheckPortalUsers extends Command
{
    protected $signature = 'check:portal-users';



    protected $description = 'Check if users have become portal users and delete them from local DB if they have';

 


    /**
     * Execute the console command.
     */
   public function handle()
{
    Log::info('Starting to check portal users...');
    $this->info('Checking portal users...');

    try {
        $vtigerService = new VtigerService();
        $contacts = $vtigerService->getContacts();

        if (empty($contacts)) {
            Log::info('No contacts retrieved.');
            $this->info('No contacts retrieved.');
        }

        foreach ($contacts as $contact) {
            Log::info('Checking contact', ['contact' => $contact]);
            if ($contact['portal'] == '1') {
                $user = User::where('email', $contact['email'])->first();
                if ($user) {
                    $user->delete();
                    $this->info("Deleted user: {$user->email}");
                    Log::info("Deleted user: {$user->email}");
                }
            }
        }
    } catch (\Exception $e) {
        Log::error('Error in check:portal-users command', ['exception' => $e->getMessage()]);
        $this->error('Error: ' . $e->getMessage());
    }
}

}
