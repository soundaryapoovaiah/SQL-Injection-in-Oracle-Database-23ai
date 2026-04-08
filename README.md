# Oracle-Based SQL Injection Lab on Oracle Database 23ai

> A Dockerized migration of a MySQL-oriented SQL injection lab to Oracle Database 23ai, rebuilt to preserve the original learning workflow while adapting the backend for Oracle initialization, FREEPDB1, OCI8-based PHP connectivity, and prepared-statement defense.

## Project snapshot

This repository contains my MEng capstone project at the University of Cincinnati.

At a high level, this project answers a practical engineering question:

**Can a hands-on SQL injection lab built for MySQL be migrated to Oracle Database 23ai without breaking the original user experience or the core security learning objective?**

The short answer is yes, but not through a simple database swap. Oracle introduced different runtime assumptions around database initialization, schema setup, PHP connectivity, SQL behavior, and statement execution. To make the lab usable again, the environment had to be redesigned around Dockerized startup, seeded Oracle data, OCI8 integration, and Oracle-aware validation. The final result is an Oracle-backed lab that demonstrates both the persistence of insecure query construction and the effectiveness of prepared statements as a defense.

<img width="975" height="312" alt="image" src="https://github.com/user-attachments/assets/ca1a49d6-8cdd-4637-a2d7-fc7634611656" />

## Why this project is interesting

Most security labs are tightly coupled to the platform they were originally built for. In this case, the original learning flow came from a MySQL-based SQL injection lab, but Oracle behaves differently in ways that matter for both functionality and security validation. According to the capstone report, the migration had to account for Oracle's pluggable database model, schema and user creation requirements, FREEPDB1 service usage, OCI8-based PHP connectivity, and Oracle-specific execution behavior. fileciteturn4file0L21-L28

That makes this repository more than a classroom exercise. It is also a project in:
- database migration
- containerized environment design
- backend integration with Oracle
- reproducibility engineering
- secure coding validation


### 1. original problem

The original SQL injection lab was designed for MySQL, but the goal of this capstone was to recreate the same lab using Oracle Database 23ai while preserving the same user-facing workflow and learning objective. The report makes clear that the purpose was not to redesign the lab, but to keep the familiar experience while replacing the backend correctly. 

### 2. Realize that Oracle changes the backend assumptions

Oracle could not be dropped in as a direct replacement. The environment had to be reworked to support Dockerized Oracle startup, persistent storage, initialization scripts, health checks, schema preparation, privilege assignment, and seeded data creation before the web layer could function reliably. 

### 3. Rebuild the PHP application around OCI8

The existing PHP application was adapted to communicate with Oracle through Oracle Instant Client and the OCI8 PHP extension. This preserved the front-end flow while replacing the connectivity layer behind it. 
<img width="975" height="502" alt="image" src="https://github.com/user-attachments/assets/166c2cca-6f16-4f87-8b2e-16f1e010cff6" />

### 4. Validate that the migrated lab still behaves like a lab

Before testing any vulnerable behavior, the environment was validated through normal Oracle SQL operations, successful login, and profile rendering. The report also notes that Oracle-specific runtime behavior, such as case-sensitive matching, had to be accounted for during validation. 

### 5. Show that migration does not remove insecure query construction

One of the most important outcomes of the project is that moving from MySQL to Oracle did **not** remove the vulnerability when queries were still built by string concatenation. The report shows that attack behavior had to be adapted to Oracle syntax and execution rules, but the underlying issue remained until the query construction method changed. 

### 6. Close the loop with a proper defense

The final phase implemented prepared statements so that user input would be treated as data rather than executable SQL. The report states that this restored the intended code/data separation and blocked the vulnerable behavior demonstrated earlier.

This project demonstrates the ability to work across multiple layers of a system, not just one isolated feature.

### Backend and platform engineering
- Migrated a MySQL-oriented workflow to **Oracle Database 23ai**
- Reworked initialization around **FREEPDB1**, schema creation, seeding, and readiness checks
- Preserved application behavior while changing core backend assumptions

### Application integration
- Adapted a PHP/Apache application to use **Oracle Instant Client** and **OCI8**
- Verified runtime behavior in both browser and command-line contexts
- Traced vulnerability behavior back to query construction logic

### Security engineering
- Demonstrated why changing database engines alone does not fix insecure code
- Compared vulnerable and defended implementations in the same environment
- Implemented prepared-statement mitigation with Oracle-compatible query handling

## Architecture at a glance

| Layer | What this project implemented |
|---|---|
| Database | Oracle Database 23ai |
| Service model | FREEPDB1-based Oracle setup |
| Runtime | Docker / Docker Compose |
| Web layer | PHP + Apache |
| Oracle connectivity | OCI8 + Oracle Instant Client |
| Data setup | Initialization scripts, schema creation, seeded records |
| Validation | Browser-based checks and command-line requests |
| Mitigation | Prepared statements / bind variables |


One important part of the project was preserving the familiar application experience while changing the backend. The report explicitly states that the front-end web pages and overall user experience were kept as close as possible to the original lab so students could perform the same tasks without learning a new system. 
<img width="975" height="319" alt="image" src="https://github.com/user-attachments/assets/c3584028-31e4-496b-810d-9454b7c7a9f4" />
<img width="788" height="278" alt="image" src="https://github.com/user-attachments/assets/2f867766-80ea-411c-8147-473345e0a303" />

## What changed from MySQL to Oracle

The strongest technical lesson in this repository is that the migration preserved the learning objective, but not the original backend assumptions. The report documents several Oracle-specific differences, including schema setup, OCI8-based connectivity, comment syntax differences, and single-statement execution behavior through `oci_parse()` and `oci_execute()`.

| Area | MySQL-oriented expectation | Oracle-based reality in this project |
|---|---|---|
| Initialization | Pre-seeded environment | Oracle startup, schema/user creation, and seed automation required |
| Connectivity | Existing MySQL runtime path | OCI8 and Oracle Instant Client required |
| SQL behavior | MySQL syntax assumptions | Oracle-compatible syntax and runtime handling required |
| Statement execution | Stacked-query assumptions may be attempted | OCI8 executes one statement per parse/execute call |
| Mitigation | Prepared statements conceptually solve the issue | Oracle-side bind-variable implementation confirmed the defense |

## Why the vulnerability still existed after migration

A useful part of the project is that it does not stop at “the lab works.” It also explains **why** the vulnerability survives the migration. The code-level analysis in the report shows that the Oracle connection was correctly established through OCI8, but the vulnerable query path still used string concatenation, which preserved the insecure behavior.

<img width="975" height="524" alt="image" src="https://github.com/user-attachments/assets/728ebd7a-9cec-43a6-a056-3ab6e2cb8f83" />

## Results 

The report's task summary is one of the best recruiter-facing visuals because it compresses the core project outcome into a single comparison: some MySQL-style assumptions fail in Oracle, but the vulnerability still exists when expressed in Oracle-compatible form, and prepared statements successfully block it. 

<img width="752" height="627" alt="image" src="https://github.com/user-attachments/assets/f168c4cf-0da1-4776-bdd6-9ace98645425" />

## Defense and final outcome

The report concludes that the prepared-statement phase preserved normal functionality while preventing unauthorized data retrieval and stopping user input from altering SQL logic. That final step is what makes the repository feel complete from an engineering standpoint: it shows the vulnerable system, explains the root cause, and demonstrates the fix in the same Oracle-based environment.

<img width="975" height="325" alt="image" src="https://github.com/user-attachments/assets/7e0e490e-77e2-420c-99f2-f6eda442ddf6" />

## Responsible use

This repository is intended for education, secure coding awareness, and reproducible lab development. The public README stays high-level on purpose and does not provide a step-by-step exploit guide.

## References

1. Jeff Forristal (rain.forest.puppy). 1998. *NT Web Technology Vulnerabilities*. *Phrack Magazine* 8, 54 (Dec. 25, 1998). https://phrack.org/issues/54/8
2. Wenliang Du. n.d. *SQL Injection Attack Lab*. SEED Labs. Retrieved April 7, 2026 from https://seedsecuritylabs.org/Labs_20.04/Web/Web_SQL_Injection/
3. Oracle. n.d. *SQL Injection*. In *Oracle Database PL/SQL Language Reference, 26ai*. Retrieved April 7, 2026 from https://docs.oracle.com/en/database/oracle/oracle-database/26/lnpls/sql-injection.html


## Acknowledgments

I would like to sincerely thank my advisor, **Prof. Giovani Abuaitah**, for his guidance, support, and feedback throughout this capstone project.
