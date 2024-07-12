<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\PageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PageRepository::class)]
#[ApiResource(
    operations:[
        new Get(normalizationContext:['groups' => ['getforPage']]),
        new GetCollection(normalizationContext:['groups' => ['getforPage']]),
    ]
)]
class Page
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['getforPage'])]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    #[Groups(['getforPage'])]
    private ?string $name = null;

    #[ORM\Column(length: 150)]
    #[Groups(['getforPage'])]
    private ?string $slug = null;

    #[ORM\Column(length: 150, nullable: true)]
    #[Groups(['getforPage'])]
    private ?string $belong = null;

    /**
     * @var Collection<int, PageSection>
     */
    #[ORM\OneToMany(targetEntity: PageSection::class, mappedBy: 'page')]
    #[Groups(['getforPage'])]
    private Collection $sections;

    /**
     * @var Collection<int, Style>
     */
    #[ORM\ManyToMany(targetEntity: Style::class, inversedBy: 'pages')]
    #[Groups(['getforPage'])]
    private Collection $styles;

    /**
     * @var Collection<int, StyleGroup>
     */
    #[ORM\ManyToMany(targetEntity: StyleGroup::class, inversedBy: 'pages')]
    #[Groups(['getforPage'])]
    private Collection $class;

    public function __construct()
    {
        $this->sections = new ArrayCollection();
        $this->styles = new ArrayCollection();
        $this->class = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getBelong(): ?string
    {
        return $this->belong;
    }

    public function setBelong(?string $belong): static
    {
        $this->belong = $belong;

        return $this;
    }

    /**
     * @return Collection<int, PageSection>
     */
    public function getSections(): Collection
    {
        return $this->sections;
    }

    public function addSection(PageSection $section): static
    {
        if (!$this->sections->contains($section)) {
            $this->sections->add($section);
            $section->setPage($this);
        }

        return $this;
    }

    public function removeSection(PageSection $section): static
    {
        if ($this->sections->removeElement($section)) {
            // set the owning side to null (unless already changed)
            if ($section->getPage() === $this) {
                $section->setPage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Style>
     */
    public function getStyles(): Collection
    {
        return $this->styles;
    }

    public function addStyle(Style $style): static
    {
        if (!$this->styles->contains($style)) {
            $this->styles->add($style);
        }

        return $this;
    }

    public function removeStyle(Style $style): static
    {
        $this->styles->removeElement($style);

        return $this;
    }

    /**
     * @return Collection<int, StyleGroup>
     */
    public function getClass(): Collection
    {
        return $this->class;
    }

    public function addClass(StyleGroup $class): static
    {
        if (!$this->class->contains($class)) {
            $this->class->add($class);
        }

        return $this;
    }

    public function removeClass(StyleGroup $class): static
    {
        $this->class->removeElement($class);

        return $this;
    }

    public function getConcatPrpertyValue(): string
    {
        $stylesArray = [];
        foreach ($this->styles as $style) {
            $stylesArray[] = $style->getProperty() . ':' . $style->getValue();
        }
        return implode('; ', $stylesArray);
    }

    public function getClassName(): string
    {
        $class = [];
        foreach ($this->class as $clas) {
            $class[] = $clas->getName();
        }
        return implode(' ', $class);
    }

}
