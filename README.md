SYMFONY 5.4.6

Pour deployer le projet:

    Intaller les dependances:
        composer intall

    Migration:
        php bin/console make:migration
        php bin/console doctrine:migrations:migrate

    Hydrater la db avec la fixture:
        php bin/console doctrine:fixtures:load

Pour se log sur la route /login

    Identifiant: test
    pass: admin

Route du back office:

    /backoffice
