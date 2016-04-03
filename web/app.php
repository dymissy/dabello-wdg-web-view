<?php

require_once __DIR__.'/../vendor/autoload.php';

use Aws\Silex\AwsServiceProvider;
use Symfony\Component\Yaml\Yaml;

$app = new Silex\Application();
$app->register(new AwsServiceProvider(), [
    'aws.config' => [
        'version' => 'latest',
        'region' => 'us-west-2',
        'credentials' => [
            'key' => '',
            'secret' => ''
        ]
    ]
]);
$app['debug'] = true;

$app->get('/', function() use($app) {
    $imageFinder = new DabelloWdg\ImageFinder($app['aws']->createS3());

    return $app->json($imageFinder->render(), 201);
});

$app->run();
