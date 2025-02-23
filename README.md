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

# Checkstyle Commands (Python)

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

# Checkstyle Commands (Laravel)

1. Code formatieren und automatisch beheben
```bash
    cd laravel && ./vendor/bin/pint
```

2. Code formatieren anzeigen
```bash
    cd laravel && ./vendor/bin/pint --test
```

# Tests

1. Um die Tests auszuführen, verwenden Sie den folgenden Befehl:

```bash
make test
```

2. Überprüfen Sie die Testabdeckung mit dem folgenden Befehl:

```bash
make test-coverage
```


## Cloud Projekt starten

Der folgende Befehl startet das Projekt über der Cloud als Docker-Container.
Navigiere im Terminal zum Python-Verzeichnis

```bash
cd Python
```
```bash
make build
make start
```
Navigiere im Terminal zum Python-Verzeichnis, wo sich die etl.py
```bash
cd src
```
Führe etl.py aus: Sobald du im richtigen Verzeichnis bist, kannst du das Skript etl.py mit folgendem Befehl starten:
```bash
python etl.py
```

Führe BgInDash.py aus: Sobald du im richtigen Verzeichnis bist, kannst du das Skript BgInDash.py mit folgendem Befehl starten:
```bash
python BgInDash.py
```
Führe app.py aus: Sobald du im richtigen Verzeichnis bist, kannst du das Skript app.py mit folgendem Befehl starten:
```bash
python app.py
```
"Dash is running on http://127.0.0.1:8050/"

