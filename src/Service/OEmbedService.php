<?php

namespace Gertt\Grav\Embed\Service;

use Grav\Common\GravTrait;

class OEmbedService implements ServiceInterface
{
	use GravTrait;

	protected static $instance = null;
	
	protected $endpoint = null;

	static protected $params = 'format=json&origin=Gertt';

	public static function get_instance()
	{
		if (!static::$instance) {
			static::$instance = new static();
		}

		return static::$instance;
	}
	
	public function __construct()
	{
		$this->endpoint = static::getGrav()['config']->get('plugins.embed.oembed.endpoint');
	}

	public function embed($url = null)
	{
		if (!$this->endpoint) return false;

		$markup = $this->fetch($url);
		
		if (!$markup ) return false;

		return $markup;
	}

	protected function fetch($url)
	{
		$cache = static::getGrav()['cache'];
		$cache_key = 'gertt_embed_' . md5($url);
		$embed = $cache->fetch($cache_key);

		if ($embed === false) {
			$URI = $this->endpoint . (strstr($this->endpoint, '?') !== false ? '&' : '?') . 'url=' . urlencode($url);

			if (strlen(static::$params)) {
				$URI .= '&' . static::$params;
			}

			$curl = curl_init($URI);
	        curl_setopt($curl, CURLOPT_FAILONERROR, true);
	        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
	        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	        curl_setopt($curl, CURLOPT_TIMEOUT, 15);
	        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	        $response = curl_exec($curl);
	        curl_close($curl);

	        if (!$response || !($response = json_decode($response, true)) || empty($response['html'])) {
	        	return false;
	        }

	        $embed = $response['html'];

	        $cache->save($cache_key, $embed, 604800);
	        static::getGrav()['debugger']->addMessage('Cache Miss for ' . $url);
		} else {
	        static::getGrav()['debugger']->addMessage('Cache Hit for ' . $url);
		}

        return $embed;
	}
}