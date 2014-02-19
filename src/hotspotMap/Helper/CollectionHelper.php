<?php
/**
 * Created by PhpStorm.
 * User: alexandre
 * Date: 19/02/14
 * Time: 16:01
 */

namespace HotspotMap\Helper;

use Hateoas\Representation\PaginatedRepresentation;
use Hateoas\Representation\CollectionRepresentation;

class CollectionHelper {

    public function __construct()
    {

    }

    public function buildCollection($data, $route, $embedded, $page, $limit, $total)
    {
        return new PaginatedRepresentation(
            new CollectionRepresentation(
                $data,
                $embedded,
                $embedded
            ),
            $route,
            array(),
            $page,
            $limit,
            $total,
            'page',
            'limit',
            false
        );
    }

} 