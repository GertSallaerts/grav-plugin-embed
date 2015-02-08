<?php
namespace Gertt\Grav\Embed\Twig;

use Gertt\Grav\Embed\Service\OEmbedService;

class EmbedTwigExtension extends \Twig_Extension
{
    /**
     * Returns extension name.
     *
     * @return string
     */
    public function getName()
    {
        return 'EmbedTwigExtension';
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('embed', [$this, 'embedFunction'])
        ];
    }

    public function embedFunction($url)
    {
        return OEmbedService::get_instance()->embed($url);
    }
}
