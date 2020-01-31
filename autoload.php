<?php
use Doctrine\Common\Annotations\AnnotationRegistry; 
use Composer\Autoload\ClassLoader;
/** @var ClassLoader $loader */
$loader = require __DIR__.'/vendor/autoload.php';
$loader -> add('Zend', __DIR__.'/vendor/Zend/Loader/Autoloader.php');
AnnotationRegistry::registerLoader([$loader, 'loadClass']);
set_include_path (__DIR__.'/vendor'); 
require_once 'Zend/Loader/Autoloader.php'; 
require_once 'Zend/Search/Lucene.php'; 
return $loader;
