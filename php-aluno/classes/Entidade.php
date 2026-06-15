<?php
abstract class Entidade {
    protected int $id;

    public function getId(): int {
        return $this->id;
    }

    abstract public function getResumo(): string;

    public function __toString(): string {
        return $this->getResumo();
    }
}
