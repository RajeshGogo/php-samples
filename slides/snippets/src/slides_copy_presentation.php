<?php
/**
 * Copyright 2022 Google Inc.
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

// [START slides_copy_presentation]

use Google\Service\Drive;
use Google\Client;
use Google\Service\Drive\DriveFile;



function copyPresentation($presentationId, $copyTitle)
{
    /* Load pre-authorized user credentials from the environment.
   TODO(developer) - See https://developers.google.com/identity for
    guides on implementing OAuth2 for your application. */
    $client = new Google\Client();
    $client->useApplicationDefaultCredentials();
    $client->addScope(Google\Service\Drive::DRIVE);
    $driveService = new Google_Service_Drive($client);
    try {

        $copy = new Google_Service_Drive_DriveFile(array(
            'name' => $copyTitle
        ));
        $driveResponse = $driveService->files->copy($presentationId, $copy);
        $presentationCopyId = $driveResponse->id;
        printf("copyCreated at:%s\n ", $presentationCopyId);
        return $presentationCopyId;
    } catch (Exception $e) {
        echo 'Message: ' . $e->getMessage();
    }
}

// [END slides_copy_presentation]

require_once 'vendor/autoload.php';
copyPresentation('12ZqIbNsOdfGr99FQJi9mQ0zDq-Q9pdf6T3ReVBz0Lms', 'New File');