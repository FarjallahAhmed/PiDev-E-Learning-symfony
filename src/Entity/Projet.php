<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * Projet
 *
 * @ORM\Table(name="projet")
 * @ORM\Entity
 */
class Projet
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_projet", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups("post:read")
     */
    private $idProjet;

    /**
     * @var string
     *@Assert\Regex(
     *     pattern     = "/^[a-z]+$/i",
     *     htmlPattern = "^[a-zA-Z]+$",
     *     message="{{ value }} must be String "
     * )
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     * @Groups("post:read")
     */
    private $nom;

    /**
     * @var string
     *@Assert\Regex(
     *     pattern     = "/^[a-z]+$/i",
     *     htmlPattern = "^[a-zA-Z]+$",
     *     message="{{ value }} must be String "
     * )
     * @ORM\Column(name="sujet", type="string", length=255, nullable=false)
     * @Groups("post:read")
     */
    private $sujet;

    /**
     * @var string
     *@Assert\Regex(
     *     pattern     = "/^[a-z]+$/i",
     *     htmlPattern = "^[a-zA-Z]+$",
     *     message="{{ value }} must be String "
     * )
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     * @Groups("post:read")
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateC", type="date", nullable=false)
     * @Groups("post:read")
     */
    private $datec;

    public function getIdProjet(): ?int
    {
        return $this->idProjet;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getSujet(): ?string
    {
        return $this->sujet;
    }

    public function setSujet(string $sujet): self
    {
        $this->sujet = $sujet;

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

    public function getDatec(): ?\DateTimeInterface
    {
        return $this->datec;
    }

    public function setDatec(\DateTimeInterface $datec): self
    {
        $this->datec = $datec;

        return $this;
    }


}