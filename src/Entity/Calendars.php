<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Calendars
 *
 * @ORM\Table(name="calendars")
 * @ORM\Entity
 */
class Calendars
{
    /**
     * @var int
     *
     * @ORM\Column(name="idCalendar", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idcalendar;

    /**
     * @var string
     *
     * @ORM\Column(name="CalendarName", type="string", length=200, nullable=false)
     */
    private $calendarname;

    /**
     * @var int|null
     *
     * @ORM\Column(name="StartYear", type="integer", nullable=true)
     */
    private $startyear;

    /**
     * @var int|null
     *
     * @ORM\Column(name="EndYear", type="integer", nullable=true)
     */
    private $endyear;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="StartDate", type="date", nullable=true)
     */
    private $startdate;

    public function getIdcalendar(): ?int
    {
        return $this->idcalendar;
    }

    public function getCalendarname(): ?string
    {
        return $this->calendarname;
    }

    public function setCalendarname(string $calendarname): self
    {
        $this->calendarname = $calendarname;

        return $this;
    }

    public function getStartyear(): ?int
    {
        return $this->startyear;
    }

    public function setStartyear(?int $startyear): self
    {
        $this->startyear = $startyear;

        return $this;
    }

    public function getEndyear(): ?int
    {
        return $this->endyear;
    }

    public function setEndyear(?int $endyear): self
    {
        $this->endyear = $endyear;

        return $this;
    }

    public function getStartdate(): ?\DateTimeInterface
    {
        return $this->startdate;
    }

    public function setStartdate(?\DateTimeInterface $startdate): self
    {
        $this->startdate = $startdate;

        return $this;
    }


}
