<?php

namespace Keven\Flysystem\Concatenate;

use League\Flysystem\Plugin\AbstractPlugin;
use League\Flysystem\Adapter\NullAdapter;
use League\Flysystem\FileNotFoundException;
use Keven\AppendStream\AppendStream;

final class Append extends AbstractPlugin
{
    /**
     * Get the method name.
     *
     * @return string
     */
    public function getMethod()
    {
        return 'append';
    }

    /**
     * Concatenate several files into one
     *
     * Usage:
     *
     *     $filesystem->concatenate('/path/to/target', $path1, $path2, ...);
     *
     * @param   string  $target     Target path.
     * @param   string  $content    Content to append.
     * @return  boolean             True on success. False on failure.
     */
    public function handle($target, $content)
    {
         if ($this->filesystem->getAdapter() instanceof NullAdapter) {
            return false;
        }

        if (!$this->filesystem->has($target)) {
            throw new FileNotFoundException($target);
        }

        $this->filesystem->rename($target, $backup = $target.'.backup');

        $stream = (new AppendStream([
            $this->filesystem->readStream($backup),
            fopen('data://text/plain,'.$content,'r'),
        ]))->getResource();

        $this->filesystem->writeStream($target, $stream);

        $this->filesystem->delete($backup);
    }
}
