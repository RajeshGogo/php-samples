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
// [START createDrive]
require_once 'vendor/autoload.php';
use Ramsey\Uuid\Uuid;
function createDrive()
{
    try {
        $client = new Google\Client();
        $client->useApplicationDefaultCredentials();
        $client->addScope(Google\Service\Drive::DRIVE);
        $driveService = new Google_Service_Drive($client);
        
        $driveMetadata = new Google_Service_Drive_Drive(array(
                'name' => 'Project Resources'));
        $requestId = Uuid::uuid4()->toString();
        $drive = $driveService->drives->create($requestId, $driveMetadata, array(
                'fields' => 'id'));
        printf("Drive ID: %s\n", $drive->id);
        return $drive->id;
    } catch(Exception $e)  {
        echo "Error Message: ".$e;
    }  
   
}     
// [END createDrive]
createDrive();   
?>