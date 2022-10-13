<?php
/**
 * @link      https://github.com/lDeNl/astro for the canonical source repository
 * @license   GNU General Public License version 2 or later
 */

namespace Jyotish\Calendar\Event;

/**
 * Bengal specific Holidays.
 *
 * 
 */
class BengalHolidays extends EventBase
{
    public static $eventsTitle = 'Bengal specific Holidays';

    public static $eventsList = [
        [
            self::COL_NAME => 'Jahnu Saptami',
            self::COL_MASA => 2,
            self::COL_TITHI => 7,
        ],
        [
            self::COL_NAME => 'Ganga Puja',
            self::COL_MASA => 3,
            self::COL_TITHI => 10,
        ],
        [
            self::COL_NAME => 'Durga Puja',
            self::COL_MASA => 7,
            self::COL_TITHI => 7,
        ],
        [
            self::COL_NAME => 'Lakshmi Puja',
            self::COL_MASA => 7,
            self::COL_TITHI => 15,
        ],
        [
            self::COL_NAME => 'Jagaddhatri Puja',
            self::COL_MASA => 8,
            self::COL_TITHI => 9,
        ],
        [
            self::COL_NAME => 'Sarasvati Puja',
            self::COL_MASA => 11,
            self::COL_TITHI => 5,
        ],
        [
            self::COL_NAME => 'Shiva Ratri',
            self::COL_MASA => 12,
            self::COL_TITHI => 29,
        ]
    ];
}