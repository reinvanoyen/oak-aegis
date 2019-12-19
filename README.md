# oak-aegis
Aegis bindings for Oak

### Config options

Name | Default
---- | -------
template_directory | templates
cache_directory | cache/templates
nodes | [PrintNode::class, IfNode::class, AssignmentNode::class, ForNode::class, ExtendNode::class, BlockNode::class, IncludeNode::class, RawNode::class, PhpNode::class,]
custom_nodes | []

### Basic example

```php
<?php

$app = new \Oak\Application();

// Register service provider
$app->register([
  \Oak\Config\ConfigServiceProvider::class,
  \Oak\Console\ConsoleServiceProvider::class,
  \Oak\Filesystem\FilesystemServiceProvider::class,
  \ReinVanOyen\OakAegis\AegisServiceProvider::class,
]);

$app->bootstrap();

$template = $app->get(Template::class);
$template->set('title', 'Aegis');
echo $template->render('index');

```
