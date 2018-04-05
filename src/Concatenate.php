<?php

namespace Keven\Flysystem\Concatenate;

use League\Flysystem\Plugin\AbstractPlugin;
use League\Flysystem\Adapter\NullAdapter;
use League\Flysystem\FileNotFoundException;
use Keven\AppendStream\AppendStream;

final class Concatenate extends AbstractPlugin
{
    /**
     * Get the method name.
     *
     * @return string
     */
    public function getMethod()
    {
        return 'concatenate';
    }

    /**
     * Concatenate several files into one
     *
     * Usage:
     *
     *     $filesystem->concatenate('/path/to/target', $path1, $path2, ...);
     *
     * @param   string  $target         Target path.
     * @param   string  $fragmentPath1  Path to fragment to concatenate.
     * @param   string  $fragmentPath2  Path to fragment to concatenate.
     *                              ...
     * @return  boolean             True on success. False on failure.
     */
    public function handle($target, $fragmentPath1, $fragmentPath2 = null)
    {
        if ($this->filesystem->getAdapter() instanceof NullAdapter) {
            return false;
        }

        $fragments = array_slice(func_get_args(), 1);
        foreach ($fragments as $path) {
            if (!$this->filesystem->has($path)) {
                throw new FileNotFoundException($path);
            }
        }

        $overwrite = in_array($target, $fragments);
        if ($overwrite) {
            $this->filesystem->rename($target, $targetBackupPath = $target.'.backup');
            $key = array_search($target, $fragments);
            $fragments[$key] = $targetBackupPath;
        }

        $stream = new AppendStream;
        foreach ($fragments as $fragment) {
            $stream->append($this->filesystem->readStream($fragment));
        }
        $this->filesystem->writeStream($target, $resource = $stream->getResource());

        if ($overwrite) {
            $this->filesystem->delete($targetBackupPath);
        }

        return true;
    }
}
