<?php

namespace PivotalTracker\Api;

/**
 * Api interface.
 *
 */
interface ApiInterface
{
    public function getLimit();

    public function setLimit($limit);
}
