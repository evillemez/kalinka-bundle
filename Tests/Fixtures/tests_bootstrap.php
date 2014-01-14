<?php

function includeIfExists($file)
{
    if (file_exists($file)) {
        return include $file;
    }
}

if ((!$loader = includeIfExists(__DIR__.'/../../vendor/autoload.php')) && (!$loader = includeIfExists(__DIR__.'/../../../../../../autoload.php'))) {
    die('You must set up the project dependencies, run the following commands:'.PHP_EOL.
        'curl -s http://getcomposer.org/installer | php'.PHP_EOL.
        'php composer.phar install'.PHP_EOL);
}

if (class_exists('Doctrine\Common\Annotations\AnnotationRegistry')) {
    \Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(array($loader, 'loadClass'));
}

//remove cached data before running tests
$tmpDir = sys_get_temp_dir().'/ACKalinkaBundleTests';
if (file_exists($tmpDir)) {
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($tmpDir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );

    foreach ($files as $fileinfo) {
        $remove = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
        $remove($fileinfo->getRealPath());
    }
}
