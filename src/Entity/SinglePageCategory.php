<?php

namespace App\Entity;

use App\Repository\SinglePageCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SinglePageCategoryRepository::class)]
class SinglePageCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;


    #[ORM\OneToMany(mappedBy: 'category', targetEntity: SinglePage::class)]
    private Collection $singlePages;

    public function __construct()
    {
        $this->singlePages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function __toString()
    {
        return $this->name; //or anything else
    }

    /**
     * @return Collection<int, SinglePage>
     */
    public function getSinglePages(): Collection
    {
        return $this->singlePages;
    }

    public function addSinglePage(SinglePage $singlePage): self
    {
        if (!$this->singlePages->contains($singlePage)) {
            $this->singlePages[] = $singlePage;
            $singlePage->setCategory($this);
        }
        return $this;
    }

    public function removeSinglePage(SinglePage $singlePage): self
    {
        if ($this->singlePages->removeElement($singlePage)) {
            // set the owning side to null (unless already changed)
            if ($singlePage->getCategory() === $this) {
                $singlePage->setCategory(null);
            }
        }
        return $this;
    }
}
