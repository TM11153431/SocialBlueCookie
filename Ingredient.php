<?php

class Ingredient {
    private int $capacity;
    private int $durability;
    private int $flavor;
    private int $texture;
    private int $calories;
    private string $type;

    /**
     * @param $type
     * @param $capacity
     * @param $durability
     * @param $flavor
     * @param $texture
     * @param $calories
     */
    public function __construct($type, $capacity, $durability, $flavor, $texture, $calories){
        $this->type = $type;
        $this->capacity = $capacity;
        $this->durability = $durability;
        $this->flavor = $flavor;
        $this->texture = $texture;
        $this->calories = $calories;
    }

    /**
     * @return int
     */
    public function getCapacity(): int
    {
        return $this->capacity;
    }

    /**
     * @param int $capacity
     */
    public function setCapacity(int $capacity): void
    {
        $this->capacity = $capacity;
    }

    /**
     * @return int
     */
    public function getDurability(): int
    {
        return $this->durability;
    }

    /**
     * @param int $durability
     */
    public function setDurability(int $durability): void
    {
        $this->durability = $durability;
    }

    /**
     * @return int
     */
    public function getFlavor(): int
    {
        return $this->flavor;
    }

    /**
     * @param int $flavor
     */
    public function setFlavor(int $flavor): void
    {
        $this->flavor = $flavor;
    }

    /**
     * @return int
     */
    public function getTexture(): int
    {
        return $this->texture;
    }

    /**
     * @param int $texture
     */
    public function setTexture(int $texture): void
    {
        $this->texture = $texture;
    }

    /**
     * @return int
     */
    public function getCalories(): int
    {
        return $this->calories;
    }

    /**
     * @param int $calories
     */
    public function setCalories(int $calories): void
    {
        $this->calories = $calories;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }
}
