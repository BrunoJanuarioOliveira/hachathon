<?php
// Classe base abstrata — todas as entidades herdam daqui
// Demonstra: Herança e Polimorfismo em PHP
abstract class Entidade {
    protected int $id = 0; // atributo compartilhado

    public function getId(): int { return $this->id; }

    // Metodo abstrato: cada subclasse OBRIGA a implementar
    // Polimorfismo: Vaga::getResumo() e Candidatura::getResumo() fazem coisas diferentes
    abstract public function getResumo(): string;

    public function __toString(): string { return $this->getResumo(); }
}
