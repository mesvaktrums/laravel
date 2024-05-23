<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use config\vtiger;
use Illuminate\Support\Facades\Log;
class VtigerService
{
    protected $baseUrl;
    protected $username;
    protected $userAccessKey;

    public function __construct()
    {
        $this->baseUrl = config('vtiger.url');
        $this->username = config('vtiger.username');
        $this->userAccessKey = config('vtiger.accesskey');
    }

  public function authenticate()
    {
        $endpointUrl = "{$this->baseUrl}webservice.php?operation=getchallenge&username={$this->username}";
        $tokenData = json_decode(file_get_contents($endpointUrl));

        if ($tokenData && $tokenData->success) {
            $crmToken = $tokenData->result->token;
            $accessKey = md5($crmToken . $this->userAccessKey);

            $serviceUrl = $this->baseUrl . "webservice.php";
            $curl = curl_init($serviceUrl);
            $curlPostData = [
                'operation' => 'login',
                'username' => $this->username,
                'accessKey' => $accessKey,
            ];

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($curlPostData));
            $curlResponse = json_decode(curl_exec($curl));
            curl_close($curl);

            return $curlResponse;
        }

        return null;
    }

public function createContact($sessionId, $contactData)
{
    // Prepare the URL and data for the create contact request
    $serviceUrl = "{$this->baseUrl}webservice.php";
    $curlPostData = [
        'operation' => 'create',
        'sessionName' => $sessionId,
        'elementType' => 'Contacts',
        'element' => json_encode($contactData)
    ];

    // Initialize cURL session
    $curl = curl_init($serviceUrl);

    // Set cURL options for POST request
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($curlPostData));

    // Execute cURL session
    $curlResponse = json_decode(curl_exec($curl), true);
    curl_close($curl);

    // Log the response for debugging
    Log::info('Create Contact Response:', ['response' => $curlResponse]);

    // Check if the request was successful
    if (isset($curlResponse['success']) && $curlResponse['success']) {
        return $curlResponse;
    } else {
        Log::error('Failed to create contact:', ['response' => $curlResponse]);
        return null;
    }
}
public function getContacts()
{
    try {
        $serviceUrl = "{$this->baseUrl}webservice.php";
        $sessionData = $this->authenticate();  // Assume this returns session data
        
        if (!isset($sessionData->success) || !$sessionData->success) {
    Log::error('Failed to authenticate with vTiger CRM.', ['response' => $sessionData]);
    return null;
}

$sessionId = $sessionData->result->sessionName;

        $params = [
            'operation' => 'query',
            'sessionName' => $sessionId,
            'query' => 'select * from Contacts;',  // Adjust the query as needed
        ];

        $response = Http::get($serviceUrl, $params);

        if ($response->successful()) {
            Log::info('Successfully retrieved contacts from vTiger CRM.', ['response' => $response->json()]);
            return $response->json()['result'];
        } else {
            Log::error('Failed to retrieve contacts from vTiger CRM.', ['response' => $response->body()]);
            return null;
        }
    } catch (\Exception $e) {
        Log::error('An error occurred while retrieving contacts from vTiger CRM.', ['exception' => $e->getMessage()]);
        return null;
    }
}



}
