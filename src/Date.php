<?php
namespace Simcify;

class Date {

    public static function DateDiffInWeeks($dateOne, $dateTwo)
    {
        $differenceInDays = Date::date_diff($dateOne,$dateTwo);
        $differenceInWeeks = $differenceInDays / 7;
        //Round down with floor and return the difference in weeks.
        return floor($differenceInWeeks);
    }

    public static function date_diff($date1, $date2): string
    {
        return date_diff(
            date_create($date2),
            date_create($date1)
        )->format('%a');
    }

    public static function GetLeaseEnd($leaseStart)
    {
        $diff=env("SITE_Portal")?' + 7 days':' + 14 days';
        return  date('Y-m-d', strtotime($leaseStart.$diff));
    }
}