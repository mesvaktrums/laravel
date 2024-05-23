<?php

namespace App\Observers;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\App; // Import the App facade
use Illuminate\Support\Facades\Log;

use App\Services\VtigerService;
use App\Models\User;
class UserObserver
{ 
protected $vtigerService;

    public function __construct(VtigerService $vtigerService)
    {
        $this->vtigerService = $vtigerService;
    }
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
           Log::info('VtigerService resolved');

$loginResponse = $this->vtigerService->authenticate();
Log::info('Login response received', ['response' => $loginResponse]);

if ($loginResponse && $loginResponse->success) {
    $sessionId = $loginResponse->result->sessionName;
    $contactData = [
        'lastname' => $user->name,
        'assigned_user_id' => '19x1',
        'email' => $user->email,
    ];
    Log::info('Contact data prepared', ['data' => $contactData]);

    $createContactResponse = $this->vtigerService->createContact($sessionId, $contactData);
    Log::info('Create contact response received', ['response' => $createContactResponse]);


} else {
    Log::error('Login failed', ['response' => $loginResponse]);
}
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
         $vtigerservice = app::make(vtigerservice::class); // manually resolve the service
 log::info('vtigerservice resolved');

 // authenticate and get session details
 $loginresponse = $vtigerservice->authenticate();
 log::info('login response received', ['response' => $loginresponse]);

 // check if login was successful
 if ($loginresponse && isset($loginresponse->success) && $loginresponse->success) {
     $sessionid = $loginresponse->result->sessionname;
     $contactdata = [
         'lastname' => $user->name,
         'assigned_user_id' => '19x1', // example user id, adjust accordingly
         'email' => $user->email,
         // add other required fields
     ];
     log::info('contact data prepared', ['data' => $contactdata]);

     // create contact in vtiger crm
     $createcontactresponse = $vtigerservice->createcontact($sessionid, $contactdata);
     log::info('create contact response received', ['response' => $createcontactresponse]);
 } else {
     log::error('login failed', ['response' => $loginresponse]);
 }
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
