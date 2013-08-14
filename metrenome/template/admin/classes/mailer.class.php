<?php

class Mailer {
    private function makeHeaders($from_name, $from_email) {
        $headers = 'Content-type: text/html; charset="utf-8"' . "\r\n"
                    . 'From: ' . $from_name . ' <' . $from_email . '>';

        return $headers;
    }

    public function sendEmail($from_name, $from_email, $to_email, $subject, $message) {
        $headers = $this->makeHeaders($from_name, $from_email);
        $to_email = htmlspecialchars($to_email);

        $send_result = mail($to_email, $subject, $message, $headers);

        return $send_result;
    }
}

?>