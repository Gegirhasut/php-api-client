<?php

/*
 * This file is part of the Rutube PHP API Client package.
 *
 * (c) Rutube
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rutube;

/**
 * Управление аккаунтом пользователя
 *
 * @package Rutube
 */
class Account extends Entity
{
    /**
     * Получение данных аккаунта
     *
     * @return \stdClass
     */
    public function getVisitor()
    {
        return $this->getTransport()->getVisitor();
    }
}
