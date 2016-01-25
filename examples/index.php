<?php
require "../vendor/autoload.php";

// Set your API credentials.
\MotaWord\Configuration::$clientID = 'YOUR_API_CLIENT_ID';
\MotaWord\Configuration::$clientSecret = 'YOUR_API_CLIENT_SECRET';

// This will generate a new access token and it will be valid for all following calls.
// @todo This will be managed inside the library removing the necessity to call it explicitly.
MotaWord\APIHelper::generateAccessToken();

$basic = new MotaWord\Controllers\StaticController();
$projectsController = new MotaWord\Controllers\ProjectController();
$documentController = new MotaWord\Controllers\DocumentController();
$styleGuideController = new MotaWord\Controllers\StyleGuideController();
$glossaryController = new MotaWord\Controllers\GlossaryController();

// Get meta information how file formats and languages we support. These language codes will be used when creating a project.
$supportedFormats = $basic->getFormats();
$supportedLanguages = $basic->getLanguages();

// Get a list of projects
$projects = $projectsController->getProjects();

// Create a new project
$project = $projectsController->createProject('af', ['ak']);

// Add files to the project: a document to be translated, style guide and glossary.
//// Returns the updated list of documents in the project.
$document = $documentController->createDocument($project->id, realpath('./test_files/document.txt'));
//// Returns the updated list of style guides in the project.
$styleGuide = $styleGuideController->createStyleGuide($project->id, realpath('./test_files/document.txt'));
//// Returns the single glossary. A project currently accepts only 1 glossary file.
$glossary = $glossaryController->createGlossary($project->id, realpath('./test_files/glossary.xlsx'));

// Get the updated project information. Word count and price updated.
$project = $projectsController->getProject($project->id);

// Start the project. You will be charged only when you actually launch a project.
// Sandbox environment does not launch the project, but returns a positive response.
try {
    $launch = $projectsController->launchProject($project->id);
} catch(\Exception $e) {
    // If you don't have a payment method for your API client yet, this will yield an error.
    // For demo purposes, we can skip launching in sandbox environment.
    $launch = false;
}

// Get progress of the project, overall and per language.
$progress = $projectsController->getProgress($project->id);
// Package the latest translations and get them ready for download.
$package = $projectsController->createPackage($project->id);
$isPackaged = false;

// Check the packaging process. When packaging is "completed", then we can download it.
while(!$isPackaged) {
    $packageStatus = $projectsController->trackPackaging($project->id, $package->key);

    if($packageStatus->status === 'completed') {
        $isPackaged = true;
    }

    sleep(2);
}

// Download the recently packaged translations.
if($isPackaged) {
    $download = $projectsController->download($project->id);
}

// Alternatively, you can call createPackage synchronously. It will package the most recent translations and return the download() response.
// This call will take longer than usual as it waits for packaging to be complete.
$download = $projectsController->createPackage($project->id, null, 0);

// Let's see what kind of responses we received so far.
var_dump($supportedFormats);
var_dump($supportedLanguages);
var_dump($project);
var_dump($document);
var_dump($styleGuide);
var_dump($glossary);
var_dump($launch);
var_dump($progress);
var_dump($package);
var_dump($packageStatus);
var_dump($download);
