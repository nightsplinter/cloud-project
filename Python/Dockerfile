# Basis-Image für Python
FROM python:3.8-slim

# Arbeitsverzeichnis im Container
WORKDIR /app

# Kopieren Sie die Abhängigkeiten (requirements.txt) ins Arbeitsverzeichnis
COPY requirements.txt requirements.txt

# Installieren Sie die Python-Abhängigkeiten
RUN pip install --no-cache-dir -r requirements.txt

# Kopieren Sie den gesamten Code ins Arbeitsverzeichnis
COPY . .

# Standardbefehl zum Ausführen des Containers
CMD ["python", "etl.py"]

