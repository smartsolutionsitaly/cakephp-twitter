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

namespace SmartSolutionsItaly\CakePHP\Twitter\View\Helper;

use Cake\Core\Configure;
use Cake\View\Helper;
use Cake\View\StringTemplateTrait;

/**
 * Provides methods used in Twitter page configuration.
 * @package SmartSolutionsItaly\CakePHP\Twitter\View\Helper
 * @author Lucio Benini <dev@smartsolutions.it>
 * @since 1.0.0
 */
class TwitterHelper extends Helper
{
    use StringTemplateTrait;

    /**
     * List of helpers used by this helper
     * @var array
     * @see Helper::$helpers
     * @since 1.0.0
     */
    public $helpers = [
        'Text',
        'Url'
    ];

    /**
     * Helper's properties.
     * @var array
     * @since 1.0.0
     */
    protected $_properties = [];

    /**
     * Default configuration for this helper.
     * @var array
     * @see Helper::$_defaultConfig
     * @since 1.0.0
     */
    protected $_defaultConfig = [
        'templates' => [
            'sdk' => '<script>window.twttr=function(t,e,r){var n,i=t.getElementsByTagName(e)[0],w=window.twttr||{};return t.getElementById(r)?w:(n=t.createElement(e),n.id=r,n.src="https://platform.twitter.com/widgets.js",i.parentNode.insertBefore(n,i),w._e=[],w.ready=function(t){w._e.push(t)},w)}(document,"script","twitter-wjs");</script>',
            'me' => '<link rel="me" href="{{link}}">',
            'meta' => '<meta name="twitter:{{name}}" content="{{content}}" />'
        ]
    ];

    /**
     * Gets the Twitter's SDK.
     * @return string The Twitter's SDK.
     */
    public function sdk(): string
    {
        return (string)$this->formatTemplate('sdk', []);
    }

    /**
     * Returns the Twitter's "me" metatag.
     * @return string The Twitter's "me" metatag.
     */
    public function me()
    {
        return $this->formatTemplate('me', [
            'link' => 'https://twitter.com/' . Configure::read('Socials.twitter.username')
        ]);
    }

    /**
     * Sets the image property.
     * @param string $property The property name.
     * @param array|string $value The image filename.
     * @return Helper The current instance.
     * @since 1.0.0
     */
    public function setPropertyImage(string $property, $value): Helper
    {
        $this->_properties[$property] = $this->Url->image($value, [
            'fullBase' => true
        ]);

        return $this;
    }

    /**
     * Sets the description property.
     * @param string $property The property name.
     * @param array|string $value The description.
     * @return Helper The current instance.
     * @since 1.0.0
     */
    public function setPropertyDescription(string $property, string $value): Helper
    {
        $this->_properties[$property] = $this->Text->truncate($value, 140, ['ellipsis' => '', 'exact' => false]);

        return $this;
    }

    /**
     * Sets a property.
     * @param mixed $property The property name.
     * @param mixed $value Property's value.
     * @return Helper The current instance.
     * @since 1.0.0
     */
    public function setProperty($property, $value): Helper
    {
        $this->_properties[$property] = $value;

        return $this;
    }

    /**
     * Removes a property.
     *
     * @param mixed $property The property name.
     * @return Helper The current instance.
     * @since 1.0.0
     */
    public function removeProperty($property): Helper
    {
        unset($this->_properties[$property]);

        return $this;
    }

    /**
     * Clean the stored properties.
     * @return Helper The current instance.
     * @since 1.0.0
     */
    public function clearProperties(): Helper
    {
        $this->_properties = [];

        return $this;
    }

    /**
     * Renders Twitter's cards.
     * @return string The Twitter's card.
     */
    public function cards(): string
    {
        $cards = $this->getProperties() + [
                'card' => 'app',
                'site' => '@' . Configure::read('Socials.twitter.username'),
                'creator' => '@' . Configure::read('Socials.twitter.username')
            ];
        $out = '';

        foreach ($cards as $key => $value) {
            $out .= $this->formatTemplate('meta', [
                'name' => $key,
                'content' => $value
            ]);
        }

        return $out;
    }

    /**
     * Gets the properties.
     * @return array The properties.
     * @since 1.0.0
     */
    protected function getProperties()
    {
        return $this->_properties;
    }
}
