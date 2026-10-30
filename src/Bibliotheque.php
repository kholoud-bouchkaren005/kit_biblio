<?php

/**
 * kholoud Classe Bibliotheque : gere une collection de livres.
 *
 * Tableau prive de Livre. Methodes : ajouter, trouver, tous, compter, rechercher, statistiques.
 */
class Bibliotheque
{
    /** @var Livre[] */
    private array $livres = [];

    /**
     * Ajoute un livre a la bibliotheque.
     * @throws Exception si un livre avec le meme ISBN existe deja
     */
    public function ajouter(Livre $l): void
    {
        if ($this->trouver($l->getIsbn()) !== null) {
            throw new Exception("Un livre avec l'ISBN {$l->getIsbn()} existe deja.");
        }
        $this->livres[] =$l;
    }

    /**
     * Trouve un livre par son ISBN.
     * @return Livre|null Le livre trouve ou null
     */
    public function trouver(string $isbn): ?Livre
    {
        foreach ($this->livres as$livre) {
            if ($livre->getIsbn() ===$isbn) {
                return $livre;
            }
        }
        return null;
    }

    /**
     * Retourne tous les livres.
     * @return Livre[]
     */
    public function tous(): array
    {
        return $this->livres;
    }

    /**
     * Compte le nombre de livres.
     */
    public function compter(): int
    {
        return count($this->livres);
    }

    /**
     * Recherche des livres par mot-cle dans le titre ou l'auteur (insensible a la casse).
     * @return Livre[]
     */
    public function rechercher(string $mot): array
    {
        $resultats = [];
        foreach ($this->livres as$livre) {
            if (stripos($livre->getTitre(),$mot) !== false
                || stripos($livre->getAuteur(),$mot) !== false) {
                $resultats[] =$livre;
            }
        }
        return $resultats;
    }

    /**
     * Calcule et retourne les statistiques du catalogue.
     * 
     * @return array{total: int, disponibles: int, taux_disponibilite: float}
     */
    public function statistiques(): array
    {
        $total =$this->compter();
        if ($total === 0) {
            return [
                'total' => 0,
                'disponibles' => 0,
                'taux_disponibilite' => 0.0,
            ];
        }

        $disponibles = 0;
        foreach ($this->livres as$livre) {
            if (method_exists($livre, 'estDisponible')) {
                if ($livre->estDisponible()) {$disponibles++;
                }
            } else {
                $disponibles++;
            }
        }

        $taux = round(($disponibles / $total) * 100, 2);

        return [
            'total' => $total,
            'disponibles' => $disponibles,
            'taux_disponibilite' => $taux,
        ];
    }
}