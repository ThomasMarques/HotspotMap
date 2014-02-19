<?php

namespace HotspotMap\model;


class Place {

    /**
     * @var int
     */
    private $placeId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $latitude;

    /**
     * @var int
     */
    private $longitude;

    /**
     * @var string
     */
    private $schedules;

    /**
     * @var string
     */
    private $description;

    /**
     * @var int
     */
    private $hotspotType;

    /**
     * @var bool
     */
    private $coffee;

    /**
     * @var bool
     */
    private $internetAccess;

    /**
     * @var int
     */
    private $placesNumber;

    /**
     * @var int
     */
    private $comfort;

    /**
     * @var int
     */
    private $frequenting;

    /**
     * @var int
     */
    private $visitNumber;

    /**
     * @var \DateTime
     */
    private $submissionDate;

    /**
     * @var bool
     */
    private $validate;

    public function __construct()
    {
        $this->placeId = null;
        $this->name = null;
        $this->latitude = null;
        $this->longitude = null;
        $this->schedules = null;
        $this->description = null;
        $this->coffee = null;
        $this->internetAccess = null;
        $this->placesNumber = null;
        $this->comfort = null;
        $this->frequenting = null;
        $this->visitNumber = null;
        $this->validate = false;
    }

    /**
     * @param boolean $coffee
     */
    public function setCoffee($coffee)
    {
        $this->coffee = $coffee;
    }

    /**
     * @return boolean
     */
    public function getCoffee()
    {
        return $this->coffee;
    }

    /**
     * @param int $comfort
     */
    public function setComfort($comfort)
    {
        $this->comfort = $comfort;
    }

    /**
     * @return int
     */
    public function getComfort()
    {
        return $this->comfort;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param int $frequenting
     */
    public function setFrequenting($frequenting)
    {
        $this->frequenting = $frequenting;
    }

    /**
     * @return int
     */
    public function getFrequenting()
    {
        return $this->frequenting;
    }

    /**
     * @param boolean $internetAccess
     */
    public function setInternetAccess($internetAccess)
    {
        $this->internetAccess = $internetAccess;
    }

    /**
     * @return boolean
     */
    public function getInternetAccess()
    {
        return $this->internetAccess;
    }

    /**
     * @param int $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * @return int
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param int $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * @return int
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param name $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param int $placeId
     */
    public function setPlaceId($placeId)
    {
        $this->placeId = $placeId;
    }

    /**
     * @return int
     */
    public function getPlaceId()
    {
        return $this->placeId;
    }

    /**
     * @param int $placesNumber
     */
    public function setPlacesNumber($placesNumber)
    {
        $this->placesNumber = $placesNumber;
    }

    /**
     * @return int
     */
    public function getPlacesNumber()
    {
        return $this->placesNumber;
    }

    /**
     * @param string $schedules
     */
    public function setSchedules($schedules)
    {
        $this->schedules = $schedules;
    }

    /**
     * @return string
     */
    public function getSchedules()
    {
        return $this->schedules;
    }

    /**
     * @param \DateTime $submissionDate
     */
    public function setSubmissionDate($submissionDate)
    {
        $this->submissionDate = $submissionDate;
    }

    /**
     * @return \DateTime
     */
    public function getSubmissionDate()
    {
        return $this->submissionDate;
    }

    /**
     * @param boolean $validate
     */
    public function setValidate($validate)
    {
        $this->validate = $validate;
    }

    /**
     * @return boolean
     */
    public function getValidate()
    {
        return $this->validate;
    }

    /**
     * @param int $visitNumber
     */
    public function setVisitNumber($visitNumber)
    {
        $this->visitNumber = $visitNumber;
    }

    /**
     * @return int
     */
    public function getVisitNumber()
    {
        return $this->visitNumber;
    }

    /**
     * @param mixed $hotspotType
     */
    public function setHotspotType($hotspotType)
    {
        $this->hotspotType = $hotspotType;
    }

    /**
     * @return mixed
     */
    public function getHotspotType()
    {
        return $this->hotspotType;
    }
} 