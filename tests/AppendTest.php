<?php

namespace Keven\Flysystem\Tests\Concatenate;

use Keven\Flysystem\Concatenate\Append;
use League\Flysystem\Vfs\VfsAdapter;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;
use VirtualFileSystem\FileSystem as Vfs;

class AppendTest extends \PHPUnit\Framework\TestCase
{
    /** @var FilesystemInterface */
    private $filesystem;

    function setUp()
    {
        $adapter = new VfsAdapter(new Vfs);
        $this->filesystem = new Filesystem($adapter);
        $this->filesystem->addPlugin(new Append);
    }

    /** @expectedException \ArgumentCountError */
    function testNoFile()
    {
        $this->filesystem->append('/file1');
    }

    function testAppend()
    {
        $this->filesystem->write('/file1', 'file1');
        $this->filesystem->append('/file1', 'more');

        $this->assertEquals('file1more', $this->filesystem->read('/file1'));
    }

    /** @expectedException \League\Flysystem\FileNotFoundException */
    function testFileNotFound()
    {
        $this->filesystem->append('/file1', 'content');
    }
}
