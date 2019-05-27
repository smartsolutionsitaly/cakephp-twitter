<?php
/**
 * cakephp-twitter (https://github.com/smartsolutionsitaly/cakephp-twitter)
 * Copyright (c) 2019 Smart Solutions S.r.l. (https://smartsolutions.it)
 *
 * Twitter client and helpers for CakePHP
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE
 * Redistributions of files must retain the above copyright notice.
 *
 * @category  cakephp-plugin
 * @package   cakephp-twitter
 * @author    Lucio Benini <dev@smartsolutions.it>
 * @copyright 2019 Smart Solutions S.r.l. (https://smartsolutions.it)
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 * @link      https://smartsolutions.it Smart Solutions
 * @since     1.0.0
 */

namespace SmartSolutionsItaly\CakePHP\Twitter\Http\Client;

use Abraham\TwitterOAuth\TwitterOAuth;
use Cake\Core\Configure;

/**
 * Twitter client.
 * @package SmartSolutionsItaly\CakePHP\Twitter\Http\Client
 * @author Lucio Benini <dev@smartsolutions.it>
 * @since 1.0.0
 */
class TwitterClient
{
    /**
     * Twitter client instance.
     * @var TwitterOAuth
     */
    protected $_client;

    /**
     * Constructor.
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->_client = new TwitterOAuth(Configure::read('Socials.twitter.key'), Configure::read('Socials.twitter.secret'), Configure::read('Socials.twitter.access.token'), Configure::read('Socials.twitter.access.secret'));
    }

    /**
     * Makes a POST request.
     * @param string $path API path.
     * @param array $parameters Request parameters.
     * @return array|object Response.
     * @see TwitterOAuth::get()
     * @since 1.0.0
     */
    public function post(string $path, array $parameters = [])
    {
        return $this->_client->post($path, $parameters);
    }

    /**
     * Makes a GET request.
     * @param string $path API path.
     * @param array $parameters Request parameters.
     * @return array|object Response.
     * @see TwitterOAuth::get()
     * @since 1.0.0
     */
    public function get(string $path, array $parameters = [])
    {
        return $this->_client->get($path, $parameters);
    }

    /**
     * Makes a PUT request.
     * @param string $path API path.
     * @param array $parameters Request parameters.
     * @return array|object Response.
     * @see TwitterOAuth::get()
     * @since 1.0.0
     */
    public function put(string $path, array $parameters = [])
    {
        return $this->_client->get($path, $parameters);
    }

    /**
     * Makes a DELETE request.
     * @param string $path API path.
     * @param array $parameters Request parameters.
     * @return array|object Response.
     * @see TwitterOAuth::get()
     * @since 1.0.0
     */
    public function delete(string $path, array $parameters = [])
    {
        return $this->_client->get($path, $parameters);
    }

    /**
     * Uploads a media to the profile.
     * @param string $path Media's absolute path.
     * @return string Media ID.
     * @since 1.0.0
     */
    public function uploadMedia(string $path): string
    {
        $media = $this->upload('media/upload', ['media' => $path]);

        if (!empty($media->media_id_string)) {
            return $media->media_id_string;
        }

        return null;
    }

    /**
     * Uploads a file to the Twitter profile.
     * @param string $path API path.
     * @param array $parameters Request parameters.
     * @param bool $chunked A value indicating whether the file has to be uploaded in different chunks.
     * @return array|object Response.
     * @see TwitterOAuth::upload()
     * @since 1.0.0
     */
    public function upload(string $path, array $parameters = [], bool $chunked = false)
    {
        return $this->_client->upload($path, $parameters, $chunked);
    }

    /**
     * Gets the user's timeline.
     * @param string $user Twitter username.
     * @param int $count The number of entries to retrieve.
     * @return array The user's timeline.
     */
    public function getTimeline(string $user, int $count = 10): array
    {
        try {
            return $this->_client->get('statuses/user_timeline', [
                'screen_name' => $user,
                'count' => $count,
                'exclude_replies' => true,
                'trim_user' => true,
                'include_rts' => false
            ]);
        } catch (\Exception $ex) {
            return [];
        }
    }

    /**
     * Gets the credentials.
     * @return array|object The credentials.
     */
    public function getCredentials()
    {
        return $this->_client->get('account/verify_credentials');
    }
}
