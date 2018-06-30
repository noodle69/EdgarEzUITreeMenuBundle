<?php

namespace Edgar\EzUITreeMenu\Component;

use EzSystems\EzPlatformAdminUi\Component\Renderable;
use Twig\Environment;

class HeadTwigComponent implements Renderable
{
    /** @var string */
    protected $template;

    /** @var Environment */
    protected $twig;

    /** @var array */
    protected $parameters;

    /**
     * @param Environment $twig
     * @param string $template
     * @param array $parameters
     */
    public function __construct(
        Environment $twig,
        string $template,
        array $parameters = []
    ) {
        $this->twig = $twig;
        $this->template = $template;
        $this->parameters = $parameters;
    }

    /**
     * @param array $parameters
     *
     * @return string
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function render(array $parameters = []): string
    {
        return $this->twig->render($this->template, [
            ] + $this->parameters);
    }
}
