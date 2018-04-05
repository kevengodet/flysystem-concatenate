# keven/flysystem-concatenate

> Concatenate files and append content to existing files in [league/flysystem](https://flysystem.thephpleague.com/).

This plugin is compatible with any adapter.

It relies on streams so manipulating big files does not fill the memory.

## Install

```shell
composer require keven/flysystem-concatenate
```

## Usage

Concatenate files into a new one:

```php
<?php

use Keven\Flysystem\Concatenate\Concatenate;

$filesystem->addPlugin(new Concatenate);
$filesystem->write('/file1', 'file1');
$filesystem->write('/file2', 'file2');
$filesystem->concatenate('/file3', '/file1', '/file2');

echo $this->filesystem->read('/file3'); // file1file2
```

Append content to an existing file:

```php
<?php

use Keven\Flysystem\Concatenate\Append;

$filesystem->addPlugin(new Append);
$this->filesystem->write('/file1', 'file1');
$this->filesystem->append('/file1', 'more');

echo $this->filesystem->read('/file1'); // file1more
```
