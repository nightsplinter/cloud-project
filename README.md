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

## Pre-Commit einrichten

1. Der folgende Befehl installiert den Pre-Commit-Hook

```bash
pip install -r ./etl/requirements-dev.txt && pre-commit install
```

Durch den Pre-Commit-Hook werden automatisch Formatierungen, Linting-Regeln vor jedem Commit ausgeführt.
Die Konfiguration befindet sich in der Datei [.pre-commit-config.yaml](/.pre-commit-config.yaml) und die
Regeln von [Ruff](https://docs.astral.sh/ruff/) für den Linter und Formatter in der Datei [/.ruff.toml](etl/ruff.toml).

# Checkstyle Commands

1. Linting-Regeln überprüfen
```bash
    ruff check
```

2. Linting-Regeln überprüfen und automatisch beheben
```bash
    ruff check --fix
```

3. Code formatieren
```bash
    ruff format
```
