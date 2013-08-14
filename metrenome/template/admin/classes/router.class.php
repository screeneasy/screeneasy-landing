<?php

class Router {
    private $protocol;

    public function getPage() {
        $page = (!empty($_GET['page']))? $_GET['page'] : 'main';
        $is_logged_in = Core::get()->user->isLoggedIn();

        if ($page == 'subscribe') {
            return $page;
        }

        if (!Core::get()->internal_config['launched']) {
            return 'first-run';
        } else if (!$is_logged_in && $page != 'login') {
            $this->internalRedirect('login');
        } else if ($is_logged_in && $page == 'login') {
            $this->internalRedirect();
        }

        if (in_array($page, Core::get()->routes)) {
            return $page;
        }

        $this->internalRedirect();

        return false;
    }

    public function redirect($url, $code = 303) {
        $messages = Array(
            301 => 'Moved Permanently',
            303 => 'See Other'
        );

        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $code . ' ' . $messages[$code]);
        header('Location: ' . $url);

        exit();

        return true;
    }

    public function internalRedirect($page = null, $parameters = null, $code = 303) {
        if ($page) {
            $parameters['page'] = $page;
        }

        $url = $this->makeInternalUrl($parameters);

        return $this->redirect($url, $code);
    }

    public function refresh() {
        return $this->redirect($this->getCurrentUrl());
    }

    public function makeInternalUrl($parameters = null) {
        $url = $this->getAdminUrl();

        if (!empty($parameters)) {
            $url .= '?' . http_build_query($parameters);
        }

        return $url;
    }

    public function getServerProtocol() {
        if (empty($this->protocol)) {
            if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
                $this->protocol = 'https';
            } else {
                $this->protocol = 'http';
            }
        }

        return $this->protocol;
    }

    public function getHostUrl() {
        return $this->getServerProtocol() . '://' . $_SERVER['HTTP_HOST'] . '/';
    }

    public function getCurrentUrl() {
        return $this->getHostUrl() . ltrim($_SERVER['REQUEST_URI'], '/');
    }

    public function getPageUrl($page = null) {
        if (!$page) {
            $page = Core::get()->page;
        }

        $parameters['page'] = $page;

        return $this->makeInternalUrl($parameters);
    }

    public function getAdminUrl() {
        return trim($this->getHostUrl() . Core::get()->internal_config['admin_path'], '/') . '/';
    }

    public function getSiteUrl() {
        return trim($this->getHostUrl() . Core::get()->internal_config['site_path'], '/') . '/';
    }

    public function definePath($type = 'admin') {
        $path = explode('?', $_SERVER['REQUEST_URI']);
        $path = explode('/', trim($path[0], '/'));

        if ($type == 'site') {
            array_pop($path);
        }

        return implode('/', $path);
    }
}

?>