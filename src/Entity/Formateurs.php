<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LengthValidator;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Formateurs
 *
 * @ORM\Table(name="formateurs")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\FormateursRepository")
 */
class Formateurs extends Utilisateurs
{
    /**
     * @var string
     *
     * @ORM\Column(name="specialite", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Specialite Is Required")
     * @Assert\Regex(
     *     pattern     = "/^[a-z]/i",
     *     htmlPattern = "^[a-zA-Z]+$",
     *     message="Your Specialty must contain only letter"
     * )
     * @Groups("post:read")
     */
    private $specialite;

    /**
     * @var string
     *
     * @ORM\Column(name="justificatif", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Justificatif Is Required")
     * @Groups("post:read")
     */
    private $justificatif;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="etat", type="boolean", nullable=true)
     * @Groups("post:read")
     */
    private $etat = '0';

    

    public function getSpecialite(): ?string
    {
        return $this->specialite;
    }

    public function setSpecialite(string $specialite): self
    {
        $this->specialite = $specialite;

        return $this;
    }

    public function getJustificatif(): ?string
    {
        return $this->justificatif;
    }

    public function setJustificatif(string $justificatif): self
    {
        $this->justificatif = $justificatif;

        return $this;
    }

    public function getEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(?bool $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

   

}
