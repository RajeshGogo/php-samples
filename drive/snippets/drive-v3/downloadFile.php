<?php 
/**
* Copyright 2022 Google Inc.
*
* Licensed under the Apache License, Version 2.0 (the "License");
* you may not use this file except in compliance with the License.
* You may obtain a copy of the License at
*
* http://www.apache.org/licenses/LICENSE-2.0
*
* Unless required by applicable law or agreed to in writing, software
* distributed under the License is distributed on an "AS IS" BASIS,
* WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
* See the License for the specific language governing permissions and
* limitations under the License.
*/
// [START downloadFile]
require_once 'vendor/autoload.php';
function downloadFile()
 {
    try {

      $client = new Google_Client();
      $client->useApplicationDefaultCredentials();
      $client->addScope(Google\Service\Drive::DRIVE);
      $driveService = new Google_Service_Drive($client);
      $realFileId = readline("Enter File Id: ");
      // [START downloadFile]
      $fileId = '0BwwA4oUTeiV1UVNwOHItT0xfa2M';
      // [START_EXCLUDE silent]
      $fileId = $realFileId;
      // [END_EXCLUDE]
      $response = $driveService->files->get($fileId, array(
          'alt' => 'media'));
      $content = $response->getBody()->getContents();
      // [END downloadFile]
      return $content;

    } catch(Exception $e) {
      echo "Error Message: ".$e;
    }
   
 }
  // [END downloadFile]
  downloadFile();
?>