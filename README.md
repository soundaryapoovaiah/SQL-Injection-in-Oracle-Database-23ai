# Oracle-Based SQL Injection Lab on Oracle Database 23ai

> A Dockerized Oracle 23ai migration of a MySQL-oriented SQL injection teaching lab, preserving the original learning workflow while rebuilding the backend around Oracle initialization, FREEPDB1, OCI8-based PHP connectivity, and prepared-statement defense.

## Overview

This repository contains my MEng capstone project completed at the University of Cincinnati.

The project recreates an SQL injection lab originally designed for MySQL and ports it to **Oracle Database 23ai** while keeping the user-facing web workflow and learning objective intact. The work goes beyond a simple database swap. It required Oracle-specific environment design, schema initialization, seeded data setup, PHP-to-Oracle integration through OCI8, browser and command-line validation, and a secure countermeasure implementation using prepared statements.

The result is a reproducible Oracle-backed lab environment that demonstrates two things clearly:

1. **Database migration alone does not remove insecure query construction.**
2. **Prepared statements restore the intended separation between code and data.**
3. <img width="975" height="312" alt="image" src="https://github.com/user-attachments/assets/49fcffc8-9b36-487d-a8ba-9e9489e59949" />
