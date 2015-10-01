<?php

require_once __DIR__ . '/../vendor/autoload.php';

function getRelativeFile()
{
    $args = func_get_args();

    array_unshift($args, __DIR__);

    return join(DIRECTORY_SEPARATOR, $args);
}

function writeFile ($fileName, $content)
{
    $targetFile = getRelativeFile("..", "src", "Objects", $fileName . ".php");

    if (file_exists($targetFile))
    {
        unlink($targetFile);
    }

    $file = fopen($targetFile, "w");

    fwrite($file, $content);
    fclose($file);
}

$loader = new Twig_Loader_Filesystem(getRelativeFile("templates"));
$twig   = new Twig_Environment($loader);

$apiDefLocation = getRelativeFile("api", "*.json");
$apiDefinitions = glob($apiDefLocation);

foreach ($apiDefinitions as $apiDef)
{
    $jsonData = json_decode(file_get_contents($apiDef), true);

    $generatedClass = $twig->render("PulseObjectClass.php.twig", array(
        "json" => $jsonData
    ));

    writeFile("Api" . $jsonData["name"], $generatedClass);
}
