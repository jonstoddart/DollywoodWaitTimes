<?php

class DbRideWait
{
    /**
     * @var Db
     */
    protected $db;

    /**
     * DbRideWait constructor.
     * @param Db $db
     */
    public function __construct(Db $db)
    {
        $this->db = $db;
        return $this;
    }

    /**
     * @return array
     */
    public function getLatestWaits()
    {
        $sql = '
            SELECT RW.ride_name, RW.ride_status, RW.wait_time, DATE_FORMAT(DATE_SUB(RW.created_at, INTERVAL 4 HOUR), "%a %b %e, %l:%i%p") AS formatted_created_at
            FROM ride_waits RW
            INNER JOIN (
                SELECT ride_name,
                MAX(ride_wait_id) AS max_id
                FROM ride_waits
                GROUP BY ride_name
            ) RW_ID ON RW.ride_wait_id = RW_ID.max_id
        ';

        return $this->db->query($sql);
    }

    /**
     * @param DateTime $date
     * @return array
     */
    public function getDailyWaitsByRide(\DateTime $date)
    {
        $sql = '
            SELECT
                ride_name,
                ride_status,
                CASE WHEN ride_status = "OPEN" THEN wait_time ELSE "" END AS wait_time,
                DATE_FORMAT(DATE_SUB(created_at, INTERVAL 4 HOUR), "%a %b %e, %l:%i%p") AS formatted_created_at
            FROM ride_waits
            WHERE created_at BETWEEN
                (SELECT MIN(created_at) FROM ride_waits WHERE DATE(DATE_SUB(created_at, INTERVAL 4 HOUR)) = DATE(:date) AND ride_status = "OPEN")
                AND
                (SELECT MAX(created_at) FROM ride_waits WHERE DATE(DATE_SUB(created_at, INTERVAL 4 HOUR)) = DATE(:date) AND ride_status = "OPEN")
            ORDER BY ride_name ASC, created_at ASC
        ';

        $return = [
            'rides' => [],
            'times' => []
        ];
        $waits = $this->db->query($sql, [':date' => $date->format('Y-m-d H:i:s')]);
        foreach ($waits as $wait) {
            if (empty($return['rides'][$wait['ride_name']])) {
                $return['rides'][$wait['ride_name']] = [];
            }
            $return['times'][] = $wait['formatted_created_at'];
            $return['rides'][$wait['ride_name']][$wait['formatted_created_at']] = ['ride_status' => $wait['ride_status'], 'wait_time' => $wait['wait_time']];
        }

        $return['times'] = array_unique($return['times']);
        usort($return['times'], ['DbRideWait', 'sortTimes']);
        foreach ($return['times'] as $time) {
            foreach (array_keys($return['rides']) as $ride) {
                if (empty($return['rides'][$ride][$time])) {
                    $return['rides'][$ride][$time] = ['ride_status' => 'UNKNOWN', 'wait_time' => 0];
                }
            }
        }

        foreach (array_keys($return['rides']) as $ride) {
            uksort($return['rides'][$ride], ['DbRideWait', 'sortRides']);
        }

        return $return;
    }

    /**
     * @param DateTime $date
     * @param string $ride_name
     * @return array
     */
    public function getDailyWaitsForRide(\DateTime $date, $ride_name)
    {
        $sql = '
            SELECT
                ride_status,
                CASE WHEN ride_status = "OPEN" THEN wait_time ELSE "" END AS wait_time,
                DATE_FORMAT(DATE_SUB(created_at, INTERVAL 4 HOUR), "%a %b %e, %l:%i%p") AS formatted_created_at
            FROM ride_waits
            WHERE created_at BETWEEN
                (SELECT MIN(created_at) FROM ride_waits WHERE DATE(DATE_SUB(created_at, INTERVAL 4 HOUR)) = DATE(:date) AND ride_status = "OPEN")
                AND
                (SELECT MAX(created_at) FROM ride_waits WHERE DATE(DATE_SUB(created_at, INTERVAL 4 HOUR)) = DATE(:date) AND ride_status = "OPEN")
            AND ride_name = :ride_name
            ORDER BY ride_name ASC, created_at ASC
        ';

        return $this->db->query($sql, [':date' => $date->format('Y-m-d H:i:s'), ':ride_name' => $ride_name]);
    }

    /**
     * @return array
     */
    public function getAvailableDates()
    {
        $sql = '
            SELECT DISTINCT DATE_FORMAT(DATE_SUB(created_at, INTERVAL 4 HOUR), "%Y-%m-%d") AS formatted_created_at
            FROM ride_waits
            ORDER BY formatted_created_at DESC
        ';

        return $this->db->query($sql);
    }

    /**
     * @param $a
     * @param $b
     * @return int
     */
    public static function sortTimes($a, $b)
    {
        return strtotime($a) > strtotime($b) ? 1 : -1;
    }

    /**
     * @param $a
     * @param $b
     * @return int
     */
    public static function sortRides($a, $b)
    {
        return strtotime($a) > strtotime($b) ? 1 : -1;
    }
}