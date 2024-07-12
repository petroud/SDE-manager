# SDE-Manager

## Overview

Welcome to the **SDE-Manager** repository! This project provides a web application interface for interacting with the Synopsis Data Engine (SDE). The SDE-Manager makes it easier for users to manage, and query their SDE data through a user-friendly web interface.

## Features

- **Data Management**: Easily manage your datasets with CRUD (Create, Read, Update, Delete) operations.
- **Data Streaming**: Stream easily your datasets into an SDE instance via its corresponding Kafka Cluster.
- **Query Builder**: Build, execute & review estimation queries to an SDE instance via Kafka Request Topic.

## Getting Started

### Requirements
Before you begin, ensure you have the following installed on your machine:

- docker.io
- docker-compose

## Run an on-premise instance of the manager
```sh
      git clone https://github.com/petroud/sde-manager.git
      cd sde-manager
      sudo docker-compose up
```
You should be able to see your instance running at http://localhost:8000


   
