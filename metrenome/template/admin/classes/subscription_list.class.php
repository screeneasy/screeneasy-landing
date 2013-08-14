<?php

class SubscriptionList {
    public $list;
    public $path;

    public function __construct($path = null) {
        if ($path) {
            $this->path = $path;
            return $this->readListFromFile($path);
        }
    }

    public function readListFromFile($path) {
        $this->path = $path;
        $list_file = @fopen($path, "r");

        if ($list_file) {
            $list = Array();

            while ($line = fgets($list_file)) {
                $line_email = trim($line);

                if ($line_email) {
                    $list[] = $line_email;
                }
            }

            fclose($list_file);

            return $this->list = $list;
        }

        return false;
    }

    public function isInList($email) {
        return in_array($email, $this->list);
    }

    public function addToList($email) {
        if ($this->isInList($email)) {
            return false;
        }

        $this->list[] = $email;

        return true;
    }

    public function saveList() {
        return $this->saveListToFile($this->path);
    }

    public function saveListToFile($path) {
        $list_text = implode("\r\n", $this->list);

        return file_put_contents($path, $list_text);
    }
}

?>