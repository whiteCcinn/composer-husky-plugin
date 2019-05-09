<?php

namespace Husky\Payloads;

/**
 * Class BehaviorPayload
 *
 * @package Husky\Payloads
 */
class BehaviorPayload
{
    /** @var string  */
    const INSTALL = 'install';

    /** @var string  */
    const UPDATE = 'update';

    /** @var string  */
    const UNINSTALL = 'uninstall';
}