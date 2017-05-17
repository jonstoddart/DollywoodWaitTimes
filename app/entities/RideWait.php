<?php

/**
 * @Entity @Table(name="ride_waits")
 **/
class RideWait
{
    /**
     * @Column(type="string")
     * @var string
     */
    protected $rideName;

    /**
     * @Column(type="string")
     * @var string
     */
    protected $rideStatus;

    /**
     * @Column(type="int")
     * @var int
     */
    protected $waitTime;

    /**
     * @Column(type="\DateTime")
     * @var \DateTime
     */
    protected $created_at;

    /**
     * @return string
     */
    public function getRideName()
    {
        return $this->rideName;
    }

    /**
     * @param string $rideName
     */
    public function setRideName($rideName)
    {
        $this->rideName = $rideName;
    }

    /**
     * @return string
     */
    public function getRideStatus()
    {
        return $this->rideStatus;
    }

    /**
     * @param string $rideStatus
     */
    public function setRideStatus($rideStatus)
    {
        $this->rideStatus = $rideStatus;
    }

    /**
     * @return int
     */
    public function getWaitTime()
    {
        return $this->waitTime;
    }

    /**
     * @param int $waitTime
     */
    public function setWaitTime($waitTime)
    {
        $this->waitTime = $waitTime;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return new \DateTime($this->created_at);
    }

    /**
     * @param \DateTime $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at->format('Y-m-d H:i:s');
    }
}