<?php

require_once __DIR__.'/../vendor/autoload.php';

use Aws\Silex\AwsServiceProvider;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\HttpFoundation\Response;

$app = new Silex\Application();


$app->register(new AwsServiceProvider(), [
    'aws.config' => [
        'version' => 'latest',
        'region' => 'eu-west-1',
        'credentials' => [
            'key' => getenv('S3_KEY'),
            'secret' => getenv('S3_SECRET')
        ]
    ]
]);
$app->register(new Silex\Provider\SwiftmailerServiceProvider());
$app['debug'] = true;
$app['swiftmailer.options'] = array(
    'host' => 'mail.sidalab.solutions',
    'port' => '25',
    'username' => 'matrimonio@sidalab.solutions',
    'password' => getenv('EMAIL_PASSWORD'),
    'encryption' => null,
    'auth_mode' => null
);
$app->get('/', function() use($app) {
    $imageFinder = new DabelloWdg\ImageFinder($app['aws']->createS3());

    return $app->json($imageFinder->render(), 201);
});

$app->get('/review', function() use($app) {
    $imageFinder = new DabelloWdg\ImageFinder($app['aws']->createS3(), 'dabello-wdg-review');

    return $app->json($imageFinder->render(), 201);
});

$app->post('/register', function() use($app) {
/*    $request = $app['request'];

    $to = $request->request->get('email');
    $token = $request->request->get('token');

    if ($token != getenv('TOKEN')) {
        return $app->json(['error' => 'Invalid Token'], 500);
    }

    if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
        return $app->json(['error' => 'Invalid Email'], 500);
    }

    try {
        (new DabelloWdg\Mailer($app['mailer']))->send($to);
    } catch(\Exception $e) {
        return $app->json(['error' => $e->getMessage()], 500);
    }*/

    return $app->json(['response' => true], 201);
});

$app->post('/moderate', function() use($app) {
/*    $request = $app['request'];

    $filename = $request->request->get('filename');
    $token = $request->request->get('token');
    $bucketName = $request->request->get('bucket');

    if ($token != getenv('TOKEN')) {
        return $app->json(['error' => 'Invalid Token'], 500);
    }

    if (!$filename) {
        return $app->json(['error' => 'Invalid Filename'], 500);
    }

    if (!$bucketName) {
        return $app->json(['error' => 'Invalid Bucket Name'], 500);
    }

    try {
        (new DabelloWdg\ImageDelete($app['aws']->createS3(), $bucketName))
            ->delete($filename);
    } catch(\Exception $e) {
        return $app->json(['error' => $e->getMessage()], 500);
    }*/

    return $app->json(['response' => true], 201);
});

$app->run();
