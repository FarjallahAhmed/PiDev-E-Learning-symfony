<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Reclamation
 *

 * @ORM\Table(name="reclamation", indexes={@ORM\Index(name="fk_reclamation_message", columns={"id_message"}), @ORM\Index(name="FK_PersonOrder", columns={"id_user"})})

 * @ORM\Table(name="reclamation", indexes={@ORM\Index(name="FK_PersonOrder", columns={"id_user"}), @ORM\Index(name="fk_reclamation_message", columns={"id_message"})})

 * @ORM\Entity(repositoryClass="App\Repository\ReclamationRepository")
 */
class Reclamation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_reclamation", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups("reclamation")
     */
    private $idReclamation;

    /**
     * @var string
     *
     * @ORM\Column(name="objet", type="string", length=255, nullable=false)
     * @Groups("reclamation")
     */
    private $objet;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date", type="date", nullable=true)
     * @Groups("reclamation")
     */
    private $date;

    /**
     * @var \Utilisateurs
     *
     * @ORM\ManyToOne(targetEntity="Utilisateurs")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     * })
     * @Groups("reclamation")
     */
    private $idUser;

    /**
     * @var \Message
     *
     * @ORM\ManyToOne(targetEntity="Message")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_message", referencedColumnName="id_message")
     * })
     * @Groups("reclamation")
     */
    private $idMessage;

    public function getIdReclamation(): ?int
    {
        return $this->idReclamation;
    }

    public function getObjet(): ?string
    {
        return $this->objet;
    }

    public function setObjet(string $objet): self
    {
        $this->objet = $objet;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getIdUser(): ?Utilisateurs
    {
        return $this->idUser;
    }

    public function setIdUser(?Utilisateurs $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getIdMessage(): ?Message
    {
        return $this->idMessage;
    }

    public function setIdMessage(?Message $idMessage): self
    {
        $this->idMessage = $idMessage;

        return $this;
    }


}
