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

use Rutube\Transports\DefaultTransport as Transport;

/**
 * Корневой класс работы с библиотекой
 *
 * @package Rutube
 */
class Rutube
{
    /**
     * Транспорт выполнения запроса к API
     *
     * @var Transport
     */
    protected $transport;

    /**
     * Признак выполнения запросов через https
     *
     * @var bool
     */
    protected $secure = false;

    /**
     * Инициализация
     *
     * @param string|null $username логин Rutube
     * @param string|null $password пароль
     * @param bool $secure Использовать https
     * @param string $host Домен API
     * @param string $transport Транспорт
     */
    public function __construct(
        $username = null,
        $password = null,
        $secure = true,
        $host = 'rutube.ru',
        $transport = 'httpful'
    ) {
        $this->secure = $secure;
        $this->transport = new Transport($transport, $secure, $host);

        if ($username !== null && $password !== null) {
            $this->transport->authorize($username, $password);
        }
    }

    /**
     * Авторизован ли пользователь
     *
     * @return bool
     */
    public function isAuthorized()
    {
        return $this->transport->hasToken();
    }

    /**
     * Используется ли безопасное соединение
     *
     * @return bool
     */
    public function isSecure()
    {
        return $this->transport->isSecure();
    }


    /**
     * Стартовая точка работы с видео
     *
     * @return Video
     */
    public function video()
    {
        return new Video($this->getTransport());
    }

    /**
     * Стартовая точка работы с аккаунтом
     *
     * @return Account
     */
    public function account()
    {
        return new Account($this->getTransport());
    }

    /**
     * Стартовая точка поиска через API
     *
     * @return Search
     */
    public function search()
    {
        return new Search($this->getTransport());
    }

    /**
     * Стартовая точка прямых запросов к Rutube
     *
     * @return Raw
     */
    public function raw()
    {
        return new Raw($this->getTransport());
    }

    /**
     * Получение списка плейлистов
     * @param int $allow_owner_empty
     * @return mixed
     * @throws Exceptions\ConnectionErrorException
     */
    public function getPlayLists($allow_owner_empty = 1) {
        $account = $this->account();
        $visitor = $account->getVisitor();

        return $this->getTransport()->getPlayLists($visitor->id, $allow_owner_empty);
    }

    /**
     * Добавление в плейлист
     * @param $id
     * @param array $video_ids
     * @return boolean
     */
    public function addToPlayList($id, $video_ids) {
        $params = ['video_ids' => []];
        foreach ($video_ids as $video_id) {
            $params['video_ids'][] = $video_id;
        }

        try {
            $this->getTransport()->addToPlayList($id, $params);
        } catch (\Exception $ex) {
            return false;
        }

        return true;
    }

    /**
     * Возвращает текущий транспорт
     *
     * @return Transport
     */
    public function getTransport()
    {
        return $this->transport;
    }
}
