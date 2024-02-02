# Hippolyte Thomas - Backend

This service exposes the data used by the frontend of my personal website through a REST API. The data is administered and managed in the backend Dashboard.

Built with [Symfony](https://symfony.com/) and [PostgreSQL](https://www.postgresql.org/).

Personal website : **[hippolyte-thomas.fr](https://hippolyte-thomas.fr/)**

## Running Locally
```bash
$ git clone https://github.com/hippothomas/hippolyte-thomas-back
$ cd hippolyte-thomas-back
```
Create the .env.local file based on [.env](.env)

```bash
$ composer i
$ symfony server:start
```

### Configure the project
```bash
$ git config --local core.hooksPath .githooks/
```

### Create the database structure

```bash
$ php bin/console doctrine:database:create
$ php bin/console doctrine:migrations:migrate
```

## Contributing
Contributions, suggestions, bug reports and fixes are welcome!

For new features, filters, or endpoints, please open an issue and discuss before sending a PR.

## License
See the [LICENSE](LICENSE.md) file for license rights and limitations (MIT).
