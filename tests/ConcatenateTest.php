<?php

namespace Keven\Flysystem\Tests\Concatenate;

use Keven\Flysystem\Concatenate\Concatenate;
use League\Flysystem\Vfs\VfsAdapter;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;
use VirtualFileSystem\FileSystem as Vfs;

class ConcatenateTest extends \PHPUnit\Framework\TestCase
{
    /** @var FilesystemInterface */
    private $filesystem;

    function setUp()
    {
        $adapter = new VfsAdapter(new Vfs);
        $this->filesystem = new Filesystem($adapter);
        $this->filesystem->addPlugin(new Concatenate);
    }

    /** @expectedException \ArgumentCountError */
    function testNoFile()
    {
        $this->filesystem->concatenate('/file3');
    }

    function testOneFile()
    {
        $this->filesystem->write('/file1', 'file1');
        $this->filesystem->concatenate('/file2', '/file1');

        $this->assertEquals('file1', $this->filesystem->read('/file2'));
    }

    function testTwoFiles()
    {
        $this->filesystem->write('/file1', 'file1');
        $this->filesystem->write('/file2', 'file2');
        $this->filesystem->concatenate('/file3', '/file1', '/file2');

        $this->assertEquals('file1file2', $this->filesystem->read('/file3'));
    }

    /** @expectedException \League\Flysystem\FileNotFoundException */
    function testFileNotFound()
    {
        $this->filesystem->concatenate('/file2', '/file1');
    }
}
