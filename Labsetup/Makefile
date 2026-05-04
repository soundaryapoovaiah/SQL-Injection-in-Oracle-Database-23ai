# Makefile for Oracle SQL Injection Lab
# Author: Soundarya Poovaiah Kookanda
# Capstone Project – Oracle version of SEED SQL Injection Lab

PROJECT=sqlinj-oracle

.PHONY: up down rebuild clean package logs shell status help

help:
	@echo "Available commands:"
	@echo "make up        -> Start the lab containers"
	@echo "make down      -> Stop the lab containers"
	@echo "make rebuild   -> Reset containers and volumes"
	@echo "make clean     -> Remove temporary files"
	@echo "make package   -> Create submission tar file"
	@echo "make logs      -> View Oracle container logs"
	@echo "make shell     -> Access Oracle container shell"
	@echo "make status    -> Show running containers"

up:
	docker compose up -d
	@echo "Containers are starting..."
	@echo "Wait 1-2 minutes for Oracle initialization."

down:
	docker compose down

rebuild:
	docker compose down -v
	rm -rf oracle_data mysql_data
	docker compose up -d

clean:
	@echo "Cleaning temporary files..."
	find . -name "*.swp" -delete
	find . -name "*.swo" -delete
	find . -name "*.swn" -delete
	find . -name "*.swm" -delete
	rm -rf oracle_data
	rm -rf mysql_data
	rm -f *.tar *.tar.gz *.zip
	@echo "Cleanup complete."

package: clean
	@echo "Creating submission archive..."
	cd .. && tar -czvf Labsetup_clean.tar.gz Labsetup
	@echo "Archive created: Labsetup_clean.tar.gz"

logs:
	docker logs oracle-10.9.0.7

shell:
	docker exec -it oracle-10.9.0.7 bash

status:
	docker ps
