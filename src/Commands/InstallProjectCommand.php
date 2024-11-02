<?php

namespace Fadildev\RapidSetup\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class InstallProjectCommand extends Command
{
    protected $signature = 'project:setup {--force}';
    protected $description = 'Automatise les étapes d\'installation pour un projet Laravel';

    public function handle()
    {
        $this->info("Début du processus d'installation...");

        $this->generateEnvFile();
        $this->installDependencies();
        $this->generateAppKey();
        $this->checkDatabaseConnection();
        $this->runMigrationsAndSeeders();
        $this->optimizeApp();

        $this->info("Installation terminée avec succès !");
    }

    protected function generateEnvFile()
    {
        if (!file_exists(base_path('.env'))) {
            $this->info("Création du fichier .env...");
            copy(base_path('.env.example'), base_path('.env'));
        } else {
            $this->info("Le fichier .env existe déjà.");
        }
    }

    protected function installDependencies()
    {
        $this->info("Installation des dépendances via Composer...");
        exec('composer install');
    }

    protected function generateAppKey()
    {
        $this->info("Génération de la clé de l'application...");
        $this->call('key:generate');
    }

    protected function checkDatabaseConnection()
    {
        $dbHost = config('database.connections.mysql.host');
        $dbName = config('database.connections.mysql.database');
        
        if (empty($dbHost) || empty($dbName)) {
            $this->error("La configuration de la base de données est manquante. Veuillez mettre à jour votre fichier .env.");
            exit;
        }
        
        $this->info("Configuration de la base de données vérifiée.");
    }

    protected function runMigrationsAndSeeders()
    {
        $this->info("Exécution des migrations et des seeders...");
        $this->call('migrate:fresh', ['--seed' => true]);
    }

    protected function optimizeApp()
    {
        $this->info("Optimisation de l'application...");
        $this->call('optimize');
    }
}
