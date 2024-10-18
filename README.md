## Introduction
This is a custom web application that demonstrates User Authentication

## CLONE, BUILD AND RUN (AUTOMATED)

### Clone Git Repository, Run Docker Compose Development Environment, Build and Start the Application

### Prerequisites

1. Install Docker Compose, Git, and Make locally.

    ## Installation Guides

    - [Windows Setup Guide](./README-Windows.md)
    - [macOS Setup Guide](./README-macOS.md)
    - [Linux Setup Guide](./README-Linux.md)

    ## Automated Build and Run

    - [Makefile Setup Guide](./README-Automated.md)

    ## Manual Build and Run

    - [Docker Compose Setup Guide](./README-Manual.md)




2. Append the dev domain name `127.0.0.1   starter.test` in `/etc/hosts`:

    ```shell
    sudo vim /etc/hosts
    ```

### Clone the Git Repository (Public)

https://github.com/NikolovskiRatko/user-login

1. Clone the public git repository by running the following command in the terminal:

    ```shell
    git clone https://github.com/NikolovskiRatko/user-login.git
    ```
   
### Run using Makefile

1. Run all setup steps sequentially in the root folder of the project (cd user-login)

    ```shell
    make full_setup
    ```

2. Start Vue.js development server inside node container

    ```shell
    make start_client
    ```
3. Visit starter.test url in the browser

   http://starter.test/login

**Happy Coding! 🚀**

### Available Makefile targets:

**make setup_env**         # Setup environment variables by copying .env.build to .env

**make build**             # Build Docker images

**make up**                # Start Docker containers in detached mode

**make install_api**      # Install PHP dependencies and run Laravel setup inside app container

**make install_client**    # Install Node.js dependencies inside node container

**make migrate_seed**      # Run Laravel migrations and seeders inside app container

**make start_client**      # Start Vue.js development server inside node container

**make clean**            # Stop containers and prune Docker resources

**make fix_permissions**   # Fix file permissions for Laravel API

**make lint:fix**        # Run ESLint with auto-fix

**make full_setup**        # Run all setup steps sequentially


## Folder Structure

The folder structure of the components is the following:

- **dev_env** (Docker Compose)
- **app**
  - **api** (Laravel API)
  - **client** (Vue SPA Admin Panel)

### 1 - Development Environment
#### Folder: dev_env
- **Approach:** Containerization
- **Technologies:** Docker Compose
- **Syntax:** YAML
- **Language:** Go

### 2 - Back-end API
#### Folder: app/api
- **Approach:** S.O.L.I.D Principles
- **Technologies:** Laravel
- **Syntax:** Object-Oriented design (OOD)
- **Language:** PHP

### 3 - Front-end Single Page Application
#### Folder: app/client
- **Approach:** Composition API
- **Technologies:** VueJs
- **Syntax:** Typescript
- **Language:** Javascript

## Summary

Each of these sections contains key details about the various layers and components of the software project.

They together provide a comprehensive view of the development approach, technologies used, programming principles, and the environment setup.

It could serve as a reference for anyone starting a custom web development project that needs to scale.

### Integration and Interoperability

This architecture seamlessly integrates two distinct frontend applications backed by a single backend API to provide a comprehensive user experience. This integrated approach allows us to maintain separation between administrative tasks and public-facing content delivery, ensuring a responsive and SEO-friendly web application for all users.

#### Admin Panel (Vue.js Single Page Application)

The integration of Laravel and Vue.js begins with Laravel serving as the foundation. Laravel bootstraps the Vue.js Single Page Application (SPA) by providing the essential web routes, including user authentication and authorization mechanisms. Admin users benefit from a feature-rich Admin Panel built using Vue.js. This panel, hosted within the same domain as the Laravel API, offers an intuitive interface for managing and overseeing various aspects of the application.
