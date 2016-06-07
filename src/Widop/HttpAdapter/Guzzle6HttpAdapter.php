<?php

/*
 * This file is part of the Wid'op package.
 *
 * (c) Wid'op <contact@widop.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Widop\HttpAdapter;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

/**
 * Guzzle Http adapter.
 *
 * @author Gunnar Lium <gunnarlium@gmail.com>
 */
class Guzzle6HttpAdapter extends AbstractHttpAdapter
{
    /** @var ClientInterface */
    private $client;

    /**
     * Creates a guzzle adapter.
     *
     * @param ClientInterface $client       The guzzle client.
     * @param integer         $maxRedirects The maximum redirects.
     */
    public function __construct(ClientInterface $client = null, $maxRedirects = 5)
    {
        parent::__construct($maxRedirects);

        if ($client === null) {
            $client = new Client(array('allow_redirects' => array('max' => $maxRedirects)));
        }

        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function getContent($url, array $headers = array())
    {
        try {
            $response = $this->client->get($url, $headers);

            return $response->getBody();
        } catch (\Exception $e) {
            throw HttpAdapterException::cannotFetchUrl($url, $this->getName(), $e->getMessage());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function postContent($url, array $headers = array(), $content = '')
    {
        try {
            $response = $this->client->post($url, array('headers' => $headers, 'form_params' => $content));

            return $response->getBody();
        } catch (\Exception $e) {
            throw HttpAdapterException::cannotFetchUrl($url, $this->getName(), $e->getMessage());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'guzzle6';
    }
}
