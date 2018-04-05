<?php

require_once __DIR__.'/../vendor/autoload.php';

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Keven\Flysystem\Concatenate\Concatenate;

$filesystem = new Filesystem(new Local('/tmp/test'));
$filesystem->addPlugin(new Concatenate);
try { $filesystem->delete('/file1'); } catch (\Exception $e) {}
try { $filesystem->delete('/file2'); } catch (\Exception $e) {}
try { $filesystem->delete('/file3'); } catch (\Exception $e) {}
try { $filesystem->delete('/file4'); } catch (\Exception $e) {}
$filesystem->write('/file1', "This is file 1.\n");
$filesystem->write('/file2', "This is file 2.\n");
$filesystem->write('/file3', "This is file 3.\n");
$filesystem->concatenate('/file4', '/file1', '/file2', 'file3');
var_dump($filesystem->read('/file4'));
