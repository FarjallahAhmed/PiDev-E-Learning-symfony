<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LengthValidator;




/**
 * Utilisateurs
 *
 * @ORM\Table(name="utilisateurs")
 * @ORM\Entity
 * 
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="idp", type="string")
 * @DiscriminatorMap({"utilisateurs"="Utilisateurs","participants"="Participants","formateurs"="Formateurs"})

 */
class Utilisateurs
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
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Nom Is Required")
     * @Assert\Regex(
     *     pattern     = "/^[a-z]/i",
     *     htmlPattern = "^[a-zA-Z]+$",
     *     message="Your name must contain only letter"
     * )
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Prenom Is Required")
     * @Assert\Regex(
     *     pattern     = "/^[a-z]/i",
     *     htmlPattern = "^[a-zA-Z]+$",
     *     message="Your name must contain only letter"
     * )
     */
    private $prenom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateNaissance", type="date", nullable=false)
     */
    private $datenaissance;

    /**
     * @var string
     *
     * @ORM\Column(name="cin", type="string", length=8, nullable=false)
     * @Assert\NotBlank(message="CIN Is Required")
     * @Assert\Regex(
     *     pattern     = "/^[1-9]/i",
     *     htmlPattern = "^[1-9]+$",
     *     message="Your CIN must contain only Number"
     * ) 
     * @Assert\Length(
     *      min = 8,
     *      max = 8,
     *      minMessage = "Your CIN Must Have Only 8 Numbers",
     *      maxMessage = "Your CIN Must Have Only 8 Numbers",
     * )
     */
    private $cin;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Email Is Required")
     * @Assert\Email(message="The email '{{ value }}' is not Valid")
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="login", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Login Is Required")
     * @Assert\Regex(
     *     pattern     = "/^[a-z1-9]/i",
     *     htmlPattern = "^[a-z1-9]+$",
     *     message="Your Login must contain only Number and Letters"
     * ) 
     */
    private $login;

    /**
     * @var string
     *
     * @ORM\Column(name="pwd", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Password Is Required")
     * @Assert\Regex(
     *     pattern     = "/^[a-z1-9]/i",
     *     htmlPattern = "^[a-z1-9]+$",
     *     message="Your Login must contain only Number and Letters"
     * )
     * @Assert\Length(
     *      min = 8,
     *      max = 8,
     *      minMessage = "Your CIN Must Have Only 8 Characteres",
     *      maxMessage = "Your CIN Must Have Only 8 Characteres",
     * ) 
     */
    private $pwd;

    /**
     * @var string
     *
     * @ORM\Column(name="num", type="string", length=8, nullable=false)
     * @Assert\NotBlank(message="Phone Number Is Required")
     * @Assert\Regex(
     *     pattern     = "/^[1-9]/i",
     *     htmlPattern = "^[1-9]",
     *     message="Your Phone Number must contain only Number"
     * ) 
     * @Assert\Length(
     *      min = 8,
     *      max = 8,
     *      minMessage = "Your Phone Number Must Have Only 8 Numbers",
     *      maxMessage = "Your Phone Number Must Have Only 8 Numbers",
     * ) 
     */
    private $num;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=8, nullable=true)
     */
    private $image;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDatenaissance(): ?\DateTimeInterface
    {
        return $this->datenaissance;
    }

    public function setDatenaissance(\DateTimeInterface $datenaissance): self
    {
        $this->datenaissance = $datenaissance;

        return $this;
    }

    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(string $cin): self
    {
        $this->cin = $cin;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getPwd(): ?string
    {
        return $this->pwd;
    }

    public function setPwd(string $pwd): self
    {
        $this->pwd = $pwd;

        return $this;
    }

    public function getNum(): ?string
    {
        return $this->num;
    }

    public function setNum(string $num): self
    {
        $this->num = $num;

        return $this;
    }


    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }


}
