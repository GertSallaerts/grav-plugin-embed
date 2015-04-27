# Grav Embed Plugin

`embed` is a simple [Grav](http://github.com/getgrav/grav) plugin that allows you to easily embed all kinds of url's in plain markdown or using a Twig function. It makes use of Iframely's free [OEmbed endpoint for open-source projects](http://oembedapi.com). This plugin does relies completely on Iframely's service for displaying content, it requests the embed from them and displays it in the page. If you need more control over your content, I suggest looking at Sommerregen's excellent [MediaEmbed plugin](https://github.com/Sommerregen/grav-plugin-mediaembed).

# Installation

Installing the Embed plugin can be done in one of two ways. Our GPM (Grav Package Manager) installation method enables you to quickly and easily install the plugin with a simple terminal command, while the manual method enables you to do so via a zip file.

## GPM Installation (Preferred)

The simplest way to install this plugin is via the [Grav Package Manager (GPM)](http://learn.getgrav.org/advanced/grav-gpm) through your system's Terminal (also called the command line).  From the root of your Grav install type:

    bin/gpm install embed

This will install the Embed plugin into your `/user/plugins` directory within Grav. Its files can be found under `/your/site/grav/user/plugins/embed`.

## Manual Installation

To install this plugin, just download the zip version of this repository and unzip it under `/your/site/grav/user/plugins`. Then, rename the folder to `embed`. You can find these files either on [GitHub](https://github.com/Gertt/grav-plugin-embed) or via [GetGrav.org](http://getgrav.org/downloads/plugins#extras).

You should now have all the plugin files under

    /your/site/grav/user/plugins/embed

>> NOTE: This plugin is a modular component for Grav which requires [Grav](http://github.com/getgrav/grav) to function

# Usage

The plugin can be used in markdown as well as in Twig.

Markdown example:

```
[plugin:embed](http://url.you/want/to/embed)
```

Twig example:

```
{{ embed(http://url.you/want/to/embed) }}
```

Demo: [embed.gertt.be](http://embed.gertt.be)

The plugin can be configured to use a different OEmbed endpoint than the free one from Iframely. You can do this configuration in the plugin's configuration.  Simply copy the `user/plugins/embed/embed.yaml` into `user/config/plugins/embed.yaml` and make your modifications.

```
enabled: true    # global enable/disable the entire plugin
oembed:
  endpoint: http://open.iframe.ly/api/oembed    # Switch to a different oembed endpoint
```

# Updating

As development for the Embed plugin continues, new versions may become available that add additional features and functionality, improve compatibility with newer Grav releases, and generally provide a better user experience. Updating Embed is easy, and can be done through Grav's GPM system, as well as manually.

## GPM Update (Preferred)

The simplest way to update this plugin is via the [Grav Package Manager (GPM)](http://learn.getgrav.org/advanced/grav-gpm). You can do this with this by navigating to the root directory of your Grav install using your system's Terminal (also called command line) and typing the following:

    bin/gpm update embed

This command will check your Grav install to see if your Embed plugin is due for an update. If a newer release is found, you will be asked whether or not you wish to update. To continue, type `y` and hit enter. The plugin will automatically update and clear Grav's cache.

## Manual Update

Manually updating Embed is pretty simple. Here is what you will need to do to get this done:

* Delete the `your/site/user/plugins/embed` directory.
* Download the new version of the Embed plugin from either [GitHub](https://github.com/Gertt/grav-plugin-embed) or [GetGrav.org](http://getgrav.org/downloads/plugins#extras).
* Unzip the zip file in `your/site/user/plugins` and rename the resulting folder to `embed`.
* Clear the Grav cache. The simplest way to do this is by going to the root Grav directory in terminal and typing `bin/grav clear-cache`.

> Note: Any changes you have made to any of the files listed under this directory will also be removed and replaced by the new set. Any files located elsewhere (for example a YAML settings file placed in `user/config/plugins`) will remain intact.
