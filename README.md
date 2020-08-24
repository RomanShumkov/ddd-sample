# DDD Sample App

Example application to experiemnt with:
 - [Domain Driven Design](https://en.wikipedia.org/wiki/Domain-driven_design)
 - [JSON:API Specification 1.0](https://jsonapi.org/format/)
 - [Open API Specification 3.0](https://swagger.io/docs/specification/about/) and related tools, like [swagger-ui](https://swagger.io/tools/swagger-ui/)


## Installation

Installation is a 2 step process assuming docker with docker-compose are installed already.

### Step 1: Clone the repo

```bash
$ git clone git@github.com:RomanShumkov/ddd-sample.git .
```

### Step 2: Run installer

```bash
$ ./install.sh
```

You should now be able to navigate to http://127.0.0.1:8080/api/documentation

## Architecture Overview

System consists of the following logical layers:

### Domain Layer

Pure PHP objects focused on domain logic only.

Our example domain will be built around funds transferring.

### [Service Layer](https://martinfowler.com/eaaCatalog/serviceLayer.html)

Pure PHP objects focused on coordinating domain objects and establishing a set of available operations.

### Interface Layer

Focused on exposing application functionality to external actors via:
 - Graphical user interfaces (not doing atm)
 - Application programming interfaces (JSON API in our case)
 
Interface layer could and often should have alternative representation of domain model.
With RESTful JSON API we are going to work primarily with documents/resources in Interface Layer.

As a side effect of working with documents there will also be domain layer mutations via Service layer.
There is [great talk](https://www.youtube.com/watch?v=aQVSzMV8DWc) on this topic from Jim Webber.

### Infrastructure Layer
Frameworks, libraries and the code to connect pure PHP objects from Domain and Service Layers with actual implementation
of cross-cutting concerns, like persistence, messaging, transactions etc.
We are going to use following infrastructural componenets:
 - Laravel - full-stack framework for rapid API development
 - [Doctrine ORM](https://www.doctrine-project.org/projects/orm.html) - object relational [mapper](https://martinfowler.com/eaaCatalog/dataMapper.html) for connecting
 pure PHP objects in Domain Layer with persistent storage. Laravel [Active Record](https://www.martinfowler.com/eaaCatalog/activeRecord.html)
 model will also be used, but only in Interface Layer for [Data Transfer Objects](https://martinfowler.com/eaaCatalog/dataTransferObject.html).
 - [neomerx/json-api](https://github.com/neomerx/json-api) - library for exposing Interface Layer model as a JSON:API Specification 1.0 complaint API.
 - [swagger-ui](https://github.com/zircote/swagger-php) - library for generating API documentation from [Open API 3.0](https://swagger.io/docs/specification/about) schema.
 - [zircote/swagger-php](https://github.com/zircote/swagger-php) - library for generating Open API 3.0 schema from PHP code annotations.
   
