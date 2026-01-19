<?php

namespace App\Helpers;

class FormatHelper {
    public function escapeChars($value) : string {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    function formatNumber(int $value) : string {
        return number_format($value, 0, ',', '.');
    }
}