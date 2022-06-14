<?php
/**
 * Copyright 2018 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
// [START apps_script_api_quickstart]
require __DIR__ . '/vendor/autoload.php';

if (php_sapi_name() != 'cli') {
    throw new Exception('This application must be run on the command line.');
}

use Google\Client;
use Google\Service\Script;

/**
 * Returns an authorized API client.
 * @return Client the authorized client object
 */
function getClient()
{
    $client = new Client();
    $client->setApplicationName('Google Apps Script API PHP Quickstart');
    $client->setScopes("https://www.googleapis.com/auth/script.projects");
    $client->setAuthConfig('credentials.json');
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');

    // Load previously authorized token from a file, if it exists.
    // The file token.json stores the user's access and refresh tokens, and is
    // created automatically when the authorization flow completes for the first
    // time.
    $tokenPath = 'token.json';
    if (file_exists($tokenPath)) {
        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);
    }

    // If there is no previous token or it's expired.
    if ($client->isAccessTokenExpired()) {
        // Refresh the token if possible, else fetch a new one.
        if ($client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
            printf("Open the following link in your browser:\n%s\n", $authUrl);
            print 'Enter verification code: ';
            $authCode = trim(fgets(STDIN));

            // Exchange authorization code for an access token.
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
            $client->setAccessToken($accessToken);

            // Check to see if there was an error.
            if (array_key_exists('error', $accessToken)) {
                throw new Exception(join(', ', $accessToken));
            }
        }
        // Save the token to a file.
        if (!file_exists(dirname($tokenPath))) {
            mkdir(dirname($tokenPath), 0700, true);
        }
        file_put_contents($tokenPath, json_encode($client->getAccessToken()));
    }
    return $client;
}


/**
 * Shows basic usage of the Apps Script API.
 *
 * Call the Apps Script API to create a new script project, upload files to the
 * project, and log the script's URL to the user.
 */
$client = getClient();
$service = new Script($client);

// Create a management request object.
try{

    $request = new Script\CreateProjectRequest();
    $request->setTitle('My Script');
    $response = $service->projects->create($request);

    $scriptId = $response->getScriptId();

    $code = <<<EOT
function helloWorld() {
  console.log('Hello, world!');
}
EOT;
    $file1 = new Script\ScriptFile();
    $file1->setName('hello');
    $file1->setType('SERVER_JS');
    $file1->setSource($code);

    $manifest = <<<EOT
{
  "timeZone": "America/New_York",
  "exceptionLogging": "CLOUD"
}
EOT;
    $file2 = new Script\ScriptFile();
    $file2->setName('appsscript');
    $file2->setType('JSON');
    $file2->setSource($manifest);

    $request = new Script\Content();
    $request->setScriptId($scriptId);
    $request->setFiles([$file1, $file2]);

    $response = $service->projects->updateContent($scriptId, $request);
    echo "https://script.google.com/d/" . $response->getScriptId() . "/edit\n";
}
catch(Exception $e) {
    // TODO(developer) - handle error appropriately
    echo 'Message: ' .$e->getMessage();
}
// [END apps_script_api_quickstart]