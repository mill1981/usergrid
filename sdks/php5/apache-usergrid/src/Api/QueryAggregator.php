<?php
/**
 * Copyright 2010-2014 baas-platform.com, Pty Ltd. or its affiliates. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License").
 * You may not use this file except in compliance with the License.
 * A copy of the License is located at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * or in the "license" file accompanying this file. This file is distributed
 * on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either
 * express or implied. See the License for the specific language governing
 * permissions and limitations under the License.
 */

namespace Apache\Usergrid\Api;


use Guzzle\Http\QueryAggregator\QueryAggregatorInterface;
use Guzzle\Http\QueryString;

/**
 * Class QueryAggregator
 *
 * @package    Apache/Usergrid
 * @version    1.0.0
 * @author     Jason Kristian <jasonkristian@gmail.com>
 * @license    Apache License, Version  2.0
 * @copyright  (c) 2008-2014, Baas Platform Pty. Ltd
 * @link       http://baas-platform.com
 */
class QueryAggregator implements QueryAggregatorInterface
{

    /**
     * {@inheritDoc}
     */
    public function aggregate($key, $value, QueryString $query)
    {
        $response = [];

        foreach ($value as $k => $v) {
            if (is_int($k)) {
                return [
                    $query->encodeValue("{$key}[]") => $value
                ];
            }

            $k = "{$key}[{$k}]";

            if (is_array($v)) {
                $response = array_merge($response, self::aggregate($k, $v, $query));
            } else {
                $response[$query->encodeValue($k)] = $query->encodeValue($v);
            }
        }

        return $response;
    }
} 