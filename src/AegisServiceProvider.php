<?php

namespace ReinVanOyen\OakAegis;

use Aegis\Compiler\Compiler;
use Aegis\Contracts\CompilerInterface;
use Aegis\Contracts\EngineInterface;
use Aegis\Contracts\LexerInterface;
use Aegis\Contracts\LoaderInterface;
use Aegis\Contracts\NodeCollectionInterface;
use Aegis\Contracts\ParserInterface;
use Aegis\Contracts\RuntimeInterface;
use Aegis\Engine\Engine;
use Aegis\Lexer\Lexer;
use Aegis\Node\AssignmentNode;
use Aegis\Node\BlockNode;
use Aegis\Node\ComponentNode;
use Aegis\Node\ExtendNode;
use Aegis\Node\ForNode;
use Aegis\Node\IfNode;
use Aegis\Node\IncludeNode;
use Aegis\Node\PhpNode;
use Aegis\Node\PropNode;
use Aegis\Node\RawNode;
use Aegis\NodeCollection\NodeCollection;
use Aegis\Parser\Parser;
use Aegis\Runtime\Runtime;
use Aegis\Template;
use Oak\Contracts\Config\RepositoryInterface;
use Oak\Contracts\Container\ContainerInterface;
use Oak\Contracts\Filesystem\FilesystemInterface;
use Oak\ServiceProvider;
use ReinVanOyen\OakAegis\Loader\FilesystemLoader;
use \Aegis\Node\PrintNode;

class AegisServiceProvider extends ServiceProvider
{
    public function boot(ContainerInterface $app)
    {
        //
    }

    public function register(ContainerInterface $app)
    {
        $app->set(Template::class, Template::class);

        $app->set(LoaderInterface::class, function($app) {
            return new FilesystemLoader(
                $app->get(FilesystemInterface::class),
                $app->get(RepositoryInterface::class)->get('aegis.template_directory', 'templates'),
                $app->get(RepositoryInterface::class)->get('aegis.cache_directory', 'cache/templates')
            );
        });

        $app->set(EngineInterface::class, Engine::class);
        $app->set(RuntimeInterface::class, Runtime::class);

        $app->set(LexerInterface::class, Lexer::class);
        $app->set(ParserInterface::class, Parser::class);
        $app->set(CompilerInterface::class, Compiler::class);

        $app->set(NodeCollectionInterface::class, function($app) {

            $nodeCollection = new NodeCollection();

            $nodeCollection->register(
                $app->get(RepositoryInterface::class)
                    ->get('aegis.nodes', [
                        PrintNode::class,
                        IfNode::class,
                        AssignmentNode::class,
                        ForNode::class,
                        ExtendNode::class,
                        ComponentNode::class,
                        BlockNode::class,
                        PropNode::class,
                        IncludeNode::class,
                        RawNode::class,
                        PhpNode::class,
                    ])
            );

            $nodeCollection->register(
                $app->get(RepositoryInterface::class)
                    ->get('aegis.custom_nodes', [])
            );

            return $nodeCollection;
        });
    }
}