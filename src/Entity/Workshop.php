<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Workshop
 *
 * @ORM\Table(name="workshop")
 * @ORM\Entity
 * @UniqueEntity(fields={"nomevent"},message="Must Be unique,change it please")
 */
class Workshop
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank(message="Must be filled")
     * @Assert\Regex(
     *     pattern     = "/^[a-z]+$/i",
     *     htmlPattern = "^[a-zA-Z]+$",
     *     message="{{ value }} must be String "
     * )
     * @ORM\Column(name="nameCalendar", type="string", length=255, nullable=false)
     */
    private $namecalendar;

    /**
     * @var string
     *
     * @ORM\Column(name="nomEvent", type="string", length=30, nullable=false)
     * @Assert\NotBlank(message="Must be filled")
     * @Assert\Regex(
     *     pattern     = "/^[a-z]+$/i",
     *     htmlPattern = "^[a-zA-Z]+$",
     *     message="{{ value }} must be String "
     * )
     */
    private $nomevent;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dateDebut", type="date", nullable=true)
     * @Assert\NotBlank(message="Must be filled")
     * @Assert\NotBlank(message="Must be filled")
     *
     */
    private $datedebut;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dateFin", type="date", nullable=true)
     * @Assert\NotBlank(message="Must be filled")
     */
    private $datefin;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="hDebut", type="time", nullable=true)
     * @Assert\NotBlank(message="Must be filled")
     */
    private $hdebut;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="hFin", type="time", nullable=true)
     * @Assert\NotBlank(message="Must be filled")
     */
    private $hfin;

    /**
     * @var string
     *
     * @ORM\Column(name="lieu", type="string", length=30, nullable=false)
     * @Assert\NotBlank(message="Must be filled")
     *  @Assert\Regex(
     *     pattern     = "/^[a-z\s]+$/i",
     *     htmlPattern = "^[a-zA-Z\s]+$",
     *      message="{{ value }} must be String "
     * )
     */
    private $lieu;

    /**
     * @var int
     * @Assert\Positive(message="Must Be upper than 0")
     * @Assert\NotBlank(message="Must be filled")
     * @Assert\Regex(
     *     pattern     = "/^[0-9]*$/",
     *     htmlPattern = "^[0-9]*$",
     *      message="{{ value }} must be a Number"
     * )
     * @ORM\Column(name="nbParticipant", type="integer", nullable=false)
     */
    private $nbparticipant;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=30, nullable=false)
     * @Assert\NotBlank(message="Must be filled")
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=30, nullable=false)
     * @Assert\NotBlank(message="Must be filled")
     * @Assert\Regex(
     *     pattern     = "/^[a-z]+$/i",
     *     htmlPattern = "^[a-zA-Z]+$",
     *      message="{{ value }} must be String "
     * )
     */
    private $description;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float", precision=10, scale=0, nullable=false)
     * @Assert\NotBlank(message="Must be filled")
     * @Assert\Regex(
     *     pattern     = "/^[0-9]/",
     *     htmlPattern = "^[0-9]",
     *     message="{{ value }} must be a Number"
     * )
     */
    private $prix;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="workshop")
     */
    private $comments;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $hearts = 0;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNamecalendar(): ?string
    {
        return $this->namecalendar;
    }

    public function setNamecalendar(string $namecalendar): self
    {
        $this->namecalendar = $namecalendar;

        return $this;
    }

    public function getNomevent(): ?string
    {
        return $this->nomevent;
    }

    public function setNomevent(string $nomevent): self
    {
        $this->nomevent = $nomevent;

        return $this;
    }

    public function getDatedebut(): ?\DateTimeInterface
    {
        return $this->datedebut;
    }

    public function setDatedebut(?\DateTimeInterface $datedebut): self
    {
        $this->datedebut = $datedebut;

        return $this;
    }

    public function getDatefin(): ?\DateTimeInterface
    {
        return $this->datefin;
    }

    public function setDatefin(?\DateTimeInterface $datefin): self
    {
        $this->datefin = $datefin;

        return $this;
    }

    public function getHdebut(): ?\DateTimeInterface
    {
        return $this->hdebut;
    }

    public function setHdebut(\DateTimeInterface $hdebut): self
    {
        $this->hdebut = $hdebut;

        return $this;
    }

    public function getHfin(): ?\DateTimeInterface
    {
        return $this->hfin;
    }

    public function setHfin(\DateTimeInterface $hfin): self
    {
        $this->hfin = $hfin;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getNbparticipant(): ?int
    {
        return $this->nbparticipant;
    }

    public function setNbparticipant(int $nbparticipant): self
    {
        $this->nbparticipant = $nbparticipant;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setWorkshop($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getWorkshop() === $this) {
                $comment->setWorkshop(null);
            }
        }

        return $this;
    }

    public function getHearts(): ?int
    {
        return $this->hearts;
    }

    public function setHearts(?int $hearts): self
    {
        $this->hearts = $hearts;

        return $this;
    }


}
