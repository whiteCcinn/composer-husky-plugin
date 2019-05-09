<?php

namespace Husky\Payloads;

/**
 * Class FlagPayload
 *
 * @package Husky\Payloads
 */
class FlagPayload
{
    /** @var array */
    const SUCCESS = [
        '<info>',
        '</info>'
    ];

    /** @var array */
    const FAIL = [
        '<error>',
        '</error>'
    ];

    /** @var array */
    const WARN = [
        '<warning>',
        '</waring>'
    ];

    /** @var array */
    const COMMENT = [
        '<comment>',
        '</comment>'
    ];
}