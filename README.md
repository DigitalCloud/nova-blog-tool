# Nova Blog Tool.

This tool allow you to create a simple blog  for your website using Laravel Nova.

## Installation

You can install the package via composer:

```bash
composer require digitalcloud/nova-blog-tool
```

## Usage

You must register the tool with Nova. This is typically done in the tools method of the NovaServiceProvider, in app/Providers/NovaServiceProvider.php.

```php

use DigitalCloud\NovaBlogTool\NovaBlogTool;
// ....

public function tools()
{
    return [
        // ...
        new NovaBlogTool(),
        // ...
    ];
}

```
