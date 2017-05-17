<?php

use Doctrine\ORM\EntityRepository;

class RideWaitRepository extends EntityRepository
{
    /**
     * @return array
     */
    public function getLatestWaits()
    {
        $dql = '
            SELECT RW.*
            FROM ride_waits RW
            INNER JOIN (
                SELECT ride_name,
                MAX(ride_wait_id) AS max_id
                FROM ride_waits
                GROUP BY ride_name
            ) RW_ID ON RW.ride_wait_id = RW_ID.max_id
        ';
        return $this->getEntityManager()->createQuery($dql)->getResult();
    }
}