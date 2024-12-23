# Cloud Computing und Big Data Projekt


# Projektstruktur

- `laravel` - Quellcode für die Laravel-Webanwendung

# Projekt Aufsetzen

## Voraussetzungen
Um das Projekt lokal auszuführen, benötigen Sie [Docker](https://docs.docker.com/get-docker/), [PHP](https://www.php.net/downloads) und [Composer](https://getcomposer.org/download/).

## Projekt klonen

```bash
git clone https://git.thm.de/pswl33/cloud-project.git
```

## Umgebungsvariablen setzen

Im Projektverzeichnis `laravel` kann die `.env.example`-Datei angepasst werden. Beim ersten Start des Projekts wird diese Datei in `.env` umbenannt. Weitere anpassungen müssen dann in der `.env`-Datei vorgenommen werden.

## Projekt starten

Der folgende Befehl startet das Projekt lokal als Docker-Container.

```bash
make up-new
```

Für weitere Befehle siehe [Makefile](/Makefile)
