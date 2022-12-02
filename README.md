# Hippolyte Thomas - Backend

Personal website : **[hippolyte-thomas.fr](https://hippolyte-thomas.fr/)**

## Stack
- **Framework**: [Symfony](https://symfony.com/)
- **Database**: [PostgreSQL](https://www.postgresql.org/)

## API Routes

```ApacheConf
# About Me
/api/about-me               # All data about me 

# Projects
/api/projects               # List of all projects
/api/project/{slug}         # Informations about a specific project

# Socials
/api/socials                # List of all socials links

# Technologies
/api/technologies/          # List of all technologies
/api/technology/{slug}      # Informations about a specific technology and his related projects
```

## Admin Routes
```ApacheConf
/admin/
/login
/register                   # Can be disabled by setting APP_REGISTER to false in .env file
```
For any new user you want to set as an administrator, don't forget to set his role as ["ROLE_ADMIN"].

## Running Locally


```bash
$ git clone https://github.com/hippothomas/hippolyte-thomas-v2-back back
$ cd back
# Create the .env file
$ composer i
$ symfony server:start
```

Create a `.env` file similar to [.env.example](.env.example)

### Create the db structure
```bash
$ php bin/console doctrine:database:create
$ php bin/console doctrine:migrations:migrate
```

### Load fixtures to populate the db
```bash
$ php bin/console doctrine:fixtures:load
```

The default admin user is
- Username : `admin`
- Password : `password`

## License

See the [LICENSE](LICENSE.md) file for license rights and limitations (MIT).
