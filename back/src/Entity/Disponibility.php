<?php

namespace App\Entity;

use App\Repository\DisponibilityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DisponibilityRepository::class)
 */
class Disponibility
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("apiV0_list)
     */
    private $id;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups("apiV0_list)
     */
    private $start_date;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups("apiV0_list)
     */
    private $end_date;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="disponibility")
     * @Groups("apiV0_list)
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity=Trip::class, inversedBy="disponibility")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("apiV0_list)
     */
    private $trip;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->start_date;
    }

    public function setStartDate(?\DateTimeInterface $start_date): self
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->end_date;
    }

    public function setEndDate(?\DateTimeInterface $end_date): self
    {
        $this->end_date = $end_date;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    
    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addDisponibility($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getDisponibility() === $this) {
                $user->addDisponibility(null);
            }
        }

        return $this;
    }

    public function getTrip(): ?Trip
    {
        return $this->trip;
    }

    public function setTrip(?Trip $trip): self
    {
        $this->trip = $trip;

        return $this;
    }
}
