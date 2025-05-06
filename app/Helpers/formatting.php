<?php

if (!function_exists('formatIDR')) {
    function formatIDR($number)
    {
        return 'IDR' . number_format($number, 0, ',', '.');
    }
}
