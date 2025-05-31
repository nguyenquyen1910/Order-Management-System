<?php

class DateChangeUtils {
    // Ham chuyen dinh dang ngay thang khi chon 1 khoang thoi gian
    public static function getDateRangeOption($option) {
        $now = new DateTime();
        $today = $now->format('Y-m-d');
        switch ($option) {
            case 'today':
                $fromDate = $today . " 00:00:00";
                $toDate = $today . " 23:59:59";
                break;
            case 'last_3_day':
                $from = (clone $now)->modify('-2 days')->format('Y-m-d');
                $fromDate = $from . " 00:00:00";
                $toDate = $today . " 23:59:59";
                break;
            case 'last_7_day':
                $from = (clone $now)->modify('-6 days')->format('Y-m-d');
                $fromDate = $from . " 00:00:00";
                $toDate = $today . " 23:59:59";
                break;
            case 'last_15_day':
                $from = (clone $now)->modify('-14 days')->format('Y-m-d');
                $fromDate = $from . " 00:00:00";
                $toDate = $today . " 23:59:59";
                break;
            case 'last_30_day':
                $from = (clone $now)->modify('-29 days')->format('Y-m-d');
                $fromDate = $from . " 00:00:00";
                $toDate = $today . " 23:59:59";
                break;
            case 'optional':
                $fromDate = null;
                $toDate = null;
                break;
            default:
                $fromDate = null;
                $toDate = null;
        }
        return [$fromDate, $toDate];
    }
}