
.PHONY: all build start-extractor start-etl run-etl clean

# Default-Target: Alles ausführen
all: build start-extractor start-etl run-etl

# Schritt 1: Docker-Image bauen
build:
	docker-compose -f ./docker-compose.yml build

# Schritt 2: Service "extractor" starten
start-extractor:
	docker-compose -f ./docker-compose.yml up -d extractor

# Schritt 3: Service "etl" starten
start-etl:
	docker-compose -f docker-compose.yml up -d etl

start:
	docker-compose -f docker-compose.yml up -d etl
	docker-compose -f ./docker-compose.yml up -d extractor


# Schritt 4: Die etl.py direkt ausführen
run-etl:
	docker exec -it $$(docker-compose -f ./docker-compose.yml ps -q etl)  python ./src/etl.py

# Bereinigung der laufenden Container
clean:
	docker-compose -f ./docker-compose.yml down