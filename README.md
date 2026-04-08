# Oracle-Based SQL Injection Lab on Oracle Database 23ai

> A Dockerized Oracle 23ai migration of a MySQL-oriented SQL injection teaching lab, preserving the original learning workflow while rebuilding the backend around Oracle initialization, FREEPDB1, OCI8-based PHP connectivity, and prepared-statement defense.

## Overview

This repository contains my MEng capstone project completed at the University of Cincinnati.

The project recreates an SQL injection lab originally designed for MySQL and ports it to **Oracle Database 23ai** while keeping the user-facing web workflow and learning objective intact. The work goes beyond a simple database swap. It required Oracle-specific environment design, schema initialization, seeded data setup, PHP-to-Oracle integration through OCI8, browser and command-line validation, and a secure countermeasure implementation using prepared statements.

The result is a reproducible Oracle-backed lab environment that demonstrates two things clearly:

1. **Database migration alone does not remove insecure query construction.**
2. **Prepared statements restore the intended separation between code and data.**
3. <img width="975" height="312" alt="image" src="https://github.com/user-attachments/assets/49fcffc8-9b36-487d-a8ba-9e9489e59949" />

## Why this project matters

Many educational security labs are tightly coupled to one database engine. When moved to another platform, the UI might still look the same, but the backend assumptions often break. Oracle differs from MySQL in ways that directly affect reproducibility and attack behavior, including pluggable database setup, schema/user initialization requirements, OCI8-based connectivity, comment syntax, and single-statement execution behavior.

This project shows how to preserve the original learning experience while adapting the implementation to Oracle correctly and responsibly.

## What this repository demonstrates

- Migration of a MySQL-based SQL injection lab to **Oracle Database 23ai**
- Dockerized environment design for more repeatable setup and validation
- Oracle-specific initialization using **FREEPDB1**, startup scripts, seeded data, and readiness checks
- PHP/Apache integration with **Oracle Instant Client** and **OCI8**
- Validation of vulnerable behavior through both browser-based and command-line request flows
- Defensive rewrite using **prepared statements / bind variables**
<img width="975" height="319" alt="image" src="https://github.com/user-attachments/assets/34adb1b9-c4bf-4657-94b8-e63e033e5ec8" />

## Project goals

The main design goals of this project were:

- **Preserve the original learning flow** instead of redesigning the lab experience
- **Make the setup reproducible** through Docker-based initialization and reduced manual configuration
- **Adapt the backend correctly to Oracle** rather than forcing MySQL assumptions into a different engine
- **Demonstrate both attack and defense** in the same Oracle-based environment

## Key engineering work

### 1) Oracle environment redesign
The runtime was updated to include an Oracle database service, persistent storage, mounted startup scripts, and health checks so that the web container starts only after the database is ready.

### 2) Database initialization and seeding
Because Oracle does not behave like the original MySQL-based environment, the lab required additional automation for schema preparation, privileges, and seeded credential data.

### 3) OCI8-based PHP integration
The web container was rebuilt to support Oracle Instant Client and the OCI8 PHP extension, allowing the existing PHP application flow to communicate with Oracle.

### 4) Validation of vulnerable and defended paths
The environment was validated for normal application behavior first, then used to reproduce vulnerable query flows and finally tested again after replacing string concatenation with prepared statements.

<img width="975" height="502" alt="image" src="https://github.com/user-attachments/assets/54963296-2214-420e-902d-63522b2d1703" />

## Technical highlights

| Area | Oracle-based implementation |
|---|---|
| Database | Oracle Database 23ai |
| Oracle service | FREEPDB1 |
| Application layer | PHP + Apache |
| Oracle connectivity | OCI8 + Oracle Instant Client |
| Environment | Docker / Docker Compose |
| Initialization | Startup scripts, schema creation, seeded data |
| Validation | Browser flow + command-line requests |
| Defense | Prepared statements with bind variables |

## High-level results

The project confirmed that the migrated lab still supports the core learning outcomes of SQL injection education, but the attack form must be adapted to Oracle-specific behavior.

At a high level, the results show that:

- Normal login and profile rendering work in the Oracle-based environment
- Vulnerable query construction remains exploitable when user input is concatenated into SQL
- Oracle changes the syntax and execution assumptions, but not the existence of the core vulnerability
- Prepared statements successfully block unauthorized data retrieval and restore intended query behavior

<img width="788" height="278" alt="image" src="https://github.com/user-attachments/assets/0ee7a5dd-8fae-4f82-bed4-42ddb7331b2a" />

## What changed from MySQL to Oracle

This project highlights an important engineering lesson: a migration can preserve features while still requiring deep backend changes.

| Topic | MySQL-oriented assumption | Oracle-based behavior observed in this project |
|---|---|---|
| Initialization | Pre-seeded lab environment | Additional schema/user setup and seed automation required |
| Connectivity | Built-in MySQL-oriented runtime path | OCI8 and Oracle Instant Client required |
| Comment behavior | MySQL-style payload assumptions | Oracle-compatible comment syntax required |
| Statement execution | Stacked-query assumptions may be attempted | OCI8 executes one statement per parse/execute call |
| Secure fix | Conceptually the same | Implemented using Oracle-compatible prepared statements |

This project is not only a security lab exercise. It also demonstrates:

- **Database migration engineering** across different execution models
- **Containerized environment design** for reproducible setup
- **Oracle integration work** in a PHP/Apache application stack
- **Debugging and validation discipline** across UI, backend, and command-line paths
- **Secure coding awareness** through bind-variable based mitigation

