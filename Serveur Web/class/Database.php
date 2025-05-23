<?php

class Database
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = new PDO('sqlite:db.sqlite', null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }

    /**
     * Envoie des requêtes à la base de données
     * @param string $request Requête SQL
     * @param mixed $args Valeurs à envoyer pour la requête
     * @param bool $onlyFirst Retourne uniquement la première valeur du tableau s'il n'y en a qu'une
     * @return array|int|string|null
     */
    public function query(string $request, ?array $args = null, bool $onlyFirst = true) : array | int | string | null
    {
        // Initialise la requête
        if ($args) {
            $query = $this->pdo->prepare($request);
            $query->execute($args);
        } else
            $query = $this->pdo->query($request);

        // Traite la réponse
        $data = $query->fetchAll();

        // Retourne null si aucune ligne n'est trouvée
        if (count($data) == 0)
            return null;

        // Retourne seulement la ligne trouvé s'il n'y en a qu'une seule
        elseif (count($data) == 1 && $onlyFirst) {
            // Retourne la première valeur de la ligne si une seule valeur est demandée ou la ligne
            return count($data[0]) == 1
                ? $data[0][array_key_first($data[0])]
                : $data[0];
        }

        // Retourne toutes les lignes trouvées
        else
            return $data;
    }
}