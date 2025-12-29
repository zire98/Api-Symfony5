<?php

namespace App\Entity;

use App\Repository\PersonaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PersonaRepository::class)
 */
class Persona
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=512, nullable=true)
     */
    private $image;

    /**
     * @ORM\ManyToMany(targetEntity=Nacionalidad::class, inversedBy="personas")
     */
    private $nacionalidades;

    public function __construct()
    {
        $this->nacionalidades = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, Nacionalidad>
     */
    public function getNacionalidades(): Collection
    {
        return $this->nacionalidades;
    }

    public function addNacionalidade(Nacionalidad $nacionalidade): self
    {
        if (!$this->nacionalidades->contains($nacionalidade)) {
            $this->nacionalidades[] = $nacionalidade;
        }

        return $this;
    }

    public function removeNacionalidade(Nacionalidad $nacionalidade): self
    {
        $this->nacionalidades->removeElement($nacionalidade);

        return $this;
    }
}
