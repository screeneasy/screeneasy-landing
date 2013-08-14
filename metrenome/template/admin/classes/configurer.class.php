<?php

class Configurer {
    public function loadConfig($path) {
        if (!file_exists($path)) {
            return false;
        }

        $config = file_get_contents($path);

        return $this->decodeConfig($config);
    }

    public function saveConfig($config, $path, $php_wrap = false) {
        $encoded_config = $this->encodeConfig($config, $php_wrap);

        return file_put_contents($path, $encoded_config);
    }

    public function decodeConfig($config) {
        $php_regexps = Array('/^<\?php\s*/i', '/\s*\?>$/');
        $config = preg_replace($php_regexps, '', $config);

        $comment_regexp = '/^\s*\/\/.*$/m';
        $config = preg_replace($comment_regexp, '', $config);

        return json_decode($config, true);
    }

    public function encodeConfig($config, $php_wrap = false) {
        $config = json_encode($config);

        if ($php_wrap) {
            $config = '<?php' . $config . '?>';
        }

        return $config;
    }
}

?>