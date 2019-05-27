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

namespace SmartSolutionsItaly\CakePHP\Twitter\Model\Behavior;

use Cake\Collection\CollectionInterface;
use Cake\ORM\Behavior;
use Cake\ORM\Query;
use SmartSolutionsItaly\CakePHP\Twitter\Http\Client\TwitterClient;

/**
 * Twitter behavior.
 * @package SmartSolutionsItaly\CakePHP\Twitter\Model\Behavior
 * @author Lucio Benini
 * @since 1.0.0
 */
class TwitterBehavior extends Behavior
{
    /**
     * Default configuration.
     * @var array
     */
    protected $_defaultConfig = [
        'count' => 5,
        'field' => 'twitter'
    ];

    /**
     * Finder for Twitter user feed.
     * Adds a formatter to the query.
     * @param Query $query The query object.
     * @param array $options Query options. May contains "count", "field" and "property" elements.
     * @return Query The query object.
     */
    public function findTweets(Query $query, array $options): Query
    {
        $options = $options + [
                'count' => (int)$this->getConfig('count'),
                'field' => (string)$this->getConfig('field'),
                'property' => 'tweets'
            ];

        return $query
            ->formatResults(function (CollectionInterface $results) use ($options) {
                return $results->map(function ($row) use ($options) {
                    $row[$options['property']] = [];

                    if (!empty($row[$options['field']])) {
                        $client = new TwitterClient;
                        $row[$options['property']] = $client->getTimeline($row[$options['field']], (int)$options['count']);
                    }

                    return $row;
                });
            }, Query::APPEND);
    }
}
