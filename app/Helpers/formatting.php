<?php

if (!function_exists('formatIDR')) {
    function formatIDR($number)
    {
        return 'IDR' . number_format($number, 0, ',', '.');
    }
}

if (!function_exists('formatTanggal')) {
    function formatTanggal($tanggal)
    {
        $bulan = [
            1 => 'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ];

        $tanggalObj = date_create($tanggal);
        $day = date_format($tanggalObj, 'j');
        $month = (int) date_format($tanggalObj, 'n');
        $year = date_format($tanggalObj, 'Y');

        return $day . ' ' . $bulan[$month] . ' ' . $year;
    }
}
