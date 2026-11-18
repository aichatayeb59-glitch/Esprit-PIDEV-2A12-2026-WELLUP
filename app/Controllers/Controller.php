<?php
// Contrôleur de base : capture la vue dans $content puis inclut le layout (étape 8 de l'atelier)

abstract class Controller
{
    /** Clé de la page courante (utilisée par la sidebar / navbar pour le lien actif) */
    protected string $page = 'conseils';

    /** Titre affiché dans <title> */
    protected string $title = 'WELLUP';

    /** Layout utilisé : 'front' (utilisateur) ou 'back' (administrateur) */
    protected string $layout = 'front';

    protected function render(string $view, array $data = [], ?string $scriptsView = null): void
    {
        $content = $this->capture(__DIR__ . '/../Views/conseils/' . $view . '.php', $data);
        $extraScripts = $scriptsView
            ? $this->capture(__DIR__ . '/../Views/conseils/' . $scriptsView . '.php', $data)
            : '';

        $title = $this->title;
        $currentPage = $this->page;

        require __DIR__ . '/../Views/layout_' . $this->layout . '.php';
    }

    /** Exécute un fichier de vue et retourne son HTML sous forme de chaîne */
    private function capture(string $file, array $data): string
    {
        extract($data, EXTR_SKIP);
        ob_start();
        require $file;
        return ob_get_clean();
    }

    protected function redirect(string $page, array $params = []): void
    {
        header('Location: ' . url($page, $params));
        exit;
    }

    /** Bloque l'accès aux pages réservées à l'administrateur */
    protected function requireAdmin(): void
    {
        if (!isAdmin()) {
            http_response_code(403);
            $this->layout = 'front';
            $this->title = 'Accès refusé | WELLUP';
            $this->render('acces_refuse');
            exit;
        }
    }
}
