## Introduction
This is a custom web application that demonstrates User Authentication

## Folder Structure

The folder structure of the components is the following:

**dev_env** (Docker Compose)
**app**
 **api** (Laravel API)
 **client** (Vue SPA Admin Panel)

### 1 - Development Environment
#### Folder: dev_env
Approach: Containerization
Technologies: Docker Compose
Syntax: YAML
Language: Go

### 2 - Back-end API
#### Folder: app/api
Approach: S.O.L.I.D Principles
Technologies: Laravel
Syntax: Object-Oriented design (OOD)
Language: PHP

### 3 - Front-end Single Page Application
#### Folder: app/client

Approach: Composition API
Technologies: VueJs
Syntax: Typescript
Language: Javascript

## Summary

Each of these sections contains key details about the various layers and components of the software project.

They together provide a comprehensive view of the development approach, technologies used, programming principles, and the environment setup.

It could serve as a reference for anyone starting a custom web development project that needs to scale.

###Integration and Interoperability

This architecture seamlessly integrates two distinct frontend applications backed by a single backend API to provide a comprehensive user experience. This integrated approach allows us to maintain separation between administrative tasks and public-facing content delivery, ensuring a responsive and SEO-friendly web application for all users.

####Admin Panel (Vue.js Single Page Application)

The integration of Laravel and Vue.js begins with Laravel serving as the foundation. Laravel bootstraps the Vue.js Single Page Application (SPA) by providing the essential web routes, including user authentication and authorization mechanisms. Admin users benefit from a feature-rich Admin Panel built using Vue.js. This panel, hosted within the same domain as the Laravel API, offers an intuitive interface for managing and overseeing various aspects of the application.