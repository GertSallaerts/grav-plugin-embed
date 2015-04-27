<?php
namespace Grav\Plugin;

use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;

class EmbedPlugin extends Plugin
{
    public static function getSubscribedEvents() {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0],
        ];
    }

    public function onPluginsInitialized()
    {
        $autoload = __DIR__ . '/vendor/autoload.php';

        if (!is_file($autoload)) {
            $this->grav['log']->error('Embed Plugin failed to load. Composer dependencies not met.');
        }

        require_once $autoload;

        $this->grav['assets']->addInlineCss('.gist table { table-layout: auto; }');

        $this->enable([
            'onPageContentRaw' => ['onPageContentRaw', 0],
            'onTwigExtensions' => ['onTwigExtensions', 0]
        ]);

        if (strtoupper($_SERVER['REQUEST_METHOD']) === "POST" && !empty($_POST['embed_dynamic']) && !empty($_POST['url'])) {
            $this->enable([
                'onTwigPageVariables' => ['onTwigPageVariables', 0]
            ]);
        }
    }

    public function onPageContentRaw(Event $e)
    {
        $output = '';
        $content = $e['page']->getRawContent();

        while (preg_match('/\[plugin:embed\]\(([^\)]+)\)(?!(?:\n{1,}|\s{1,})?(<\/(?:pre|code)>|`))/m', $content, $matches, PREG_OFFSET_CAPTURE)) {
            $match = $matches[0][0];
            $offset = $matches[0][1];
            $url = $matches[1][0];

            if ($offset) {
                $output .= substr($content, 0, $offset);
            }

            $output .= $this->embed($url, $match);

            $offset += strlen($match);
            $content = $offset < strlen($content) ? substr($content, $offset) : '';
        }

        $e['page']->setRawContent($output . $content);
    }

    /**
     * Add Twig Extensions
     */
    public function onTwigExtensions()
    {
        $this->grav['twig']->twig->addExtension(new \Gertt\Grav\Embed\Twig\EmbedTwigExtension());
    }

    public function onTwigPageVariables()
    {
        $url = $_POST['url'];

        $markup = $this->embed($url, $url);

        $twig = $this->grav['twig'];
        $twig->twig_vars['dynamic_embed_markup'] = $markup;
        $twig->twig_vars['dynamic_embed_url'] = $url;
    }

    public function embed($url, $original)
    {
        // We have one service (iframely oembed) to rule them all now.
        $service = new \Gertt\Grav\Embed\Service\OEmbedService();

        if (!$service) {
            return $original;
        }

        $content = $service->embed($url);

        if ($content === FALSE) {
            return $original;
        }

        return $content;
    }
}
