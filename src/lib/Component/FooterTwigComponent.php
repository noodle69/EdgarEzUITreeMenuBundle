<?php

namespace Edgar\EzUITreeMenu\Component;

use EzSystems\EzPlatformAdminUi\Component\Renderable;
use Twig\Environment;

class FooterTwigComponent implements Renderable
{
    /** @var string */
    protected $template;

    /** @var Environment */
    protected $twig;

    /** @var array */
    protected $parameters;

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
     */
    public function render(array $parameters = []): string
    {
        try {
            return $this->twig->render($this->template, [
                ] + $this->parameters);
        } catch (\Exception $e) {
            return '';
        }

    }
}