<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Participants
 *
 * @ORM\Table(name="participants")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\ParticipantsRepository")
 */
class Participants extends Utilisateurs
{
    /**
     * @var string
     *
     * @ORM\Column(name="niveauEtude", type="string", length=255, nullable=false)
     * @Groups("post:read")
     */
    private $niveauetude;

    /**
     * @var int
     *
     * @ORM\Column(name="certificatsObtenus", type="integer", nullable=false)
     * @Groups("post:read")
     */
    private $certificatsobtenus;

    /**
     * @var string
     *
     * @ORM\Column(name="interessePar", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="About Is Required")
     * @Groups("post:read")
     */
    private $interessepar;

    /**
     * @var int
     *
     * @ORM\Column(name="nombreDeFormation", type="integer", nullable=false)
     * @Groups("post:read")
     */
    private $nombredeformation;

   

    public function getNiveauetude(): ?string
    {
        return $this->niveauetude;
    }

    public function setNiveauetude(string $niveauetude): self
    {
        $this->niveauetude = $niveauetude;

        return $this;
    }

    public function getCertificatsobtenus(): ?int
    {
        return $this->certificatsobtenus;
    }

    public function setCertificatsobtenus(int $certificatsobtenus): self
    {
        $this->certificatsobtenus = $certificatsobtenus;

        return $this;
    }

    public function getInteressepar(): ?string
    {
        return $this->interessepar;
    }

    public function setInteressepar(string $interessepar): self
    {
        $this->interessepar = $interessepar;

        return $this;
    }

    public function getNombredeformation(): ?int
    {
        return $this->nombredeformation;
    }

    public function setNombredeformation(int $nombredeformation): self
    {
        $this->nombredeformation = $nombredeformation;

        return $this;
    }

 


}
