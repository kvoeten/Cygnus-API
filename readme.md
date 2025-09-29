# Cygnus API

The Cygnus API is a RESTful Lumen API aimed at providing a multitude of endpoints for MapleStory private servers.

It's main features are:

  - User/ Account management.
  - WZ data enpoints (v188).
  - E-mail account verifitcaion.
  - News, Ranking, Login, Account creation and other website features. 
  - OAuth2 aimed at enabling SSO (Compatible and tested with most forum software).
  
As this API supports OAuth2 it can be used for NXL-Alike (token) logins on MapleStory private servers. 
Tokens expire in 30 minutes.

## Docker Development Setup

This project is configured to run in a Docker environment, which simplifies setup and ensures consistency.

### Prerequisites

- [Docker](https://www.docker.com/get-started)
- [Docker Compose](https://docs.docker.com/compose/install/) (usually included with Docker Desktop)

### Installation

1.  **Clone the repository:**
    ```sh
    git clone <your-repo-url>
    cd cygapi
    ```

2.  **Configure Environment:**
    Copy the example environment file.
    ```sh
    cp .env.example .env
    ```

3.  **Build and Run Containers:**
    Use Docker Compose to build the images and start the services in the background.
    ```sh
    docker-compose up -d --build
    ```
    The API will now be accessible at `http://localhost:8080`.

4.  **API Documentation:**
    The startup script (`entrypoint.sh`) automatically generates the API documentation. You can access the interactive Swagger UI to view and test the endpoints:
    - **Swagger UI:** `http://localhost:8080/api`

5.  **Database and OAuth2 Setup:**
    The startup script also runs database migrations. However, you still need to set up Laravel Passport for OAuth2 authentication.
    The startup script (`entrypoint.sh`) automatically runs `php artisan passport:install` to create the necessary encryption keys for OAuth2. This step is handled for you on the first container startup.

5.  **WZ Search Functionality:**
    For the WZ search functionality to work you must ensure the latest WZ strings are loaded into the SQL databse. 
    This should happen automatically when setting up the container, but if not, you can run the import using:
    ```sh
    docker-compose exec app php artisan wz:import --force
    ```
    Keep in mind this takes insanely long to do.

    In order to use other version WZ data you must dump it as JSON first using: https://github.com/kvoeten/Harepacker-resurrected (*I will try get this functionality into HarePacker by default for creating data APIs.*)

### Email Handling (MailHog)

The Docker environment includes [MailHog](https://github.com/mailhog/MailHog), a local email-catching server. Any email sent by the API (such as for account verification or password resets) will be intercepted by MailHog instead of being sent to a real address.

You can view the captured emails by visiting the MailHog web interface in your browser:

-   **MailHog UI:** `http://localhost:8025`

### Common Docker Commands

- **Stop containers:** `docker-compose down`
- **View logs:** `docker-compose logs -f app`
- **Run a command inside the app container:** `docker-compose exec app <command>` (e.g., `php artisan route:list`)

# Overview of TODO API links currently planned

| Method | Endpoint | Function | Parameters | Requires OAuth2 Token | Access Level |
| ------ | ------ | ------ | ------ | ------ | ------ |
| POST | /account | Edits a Cygnus game account. | Undefined. | Yes | 2+ |
| POST | /ban/{user_id} | Bans an user. | Undefined. | Yes | 3+ |
| POST | /server | Edits a Cygnus game account. | Undefined. | Yes | 5+ |
| GET | /blocklist | Gets list of banned/blocked IP's/Users. | Undefined. | Yes | 5+ |

# TODO
1. Better ranking updates.
2. Ban/ Blocklost functionality.
3. Editing user information (password resets)

Please don't hesitate to create pull requests or report any issues/ security risks. I shall do my best to incorporate any feedback.
