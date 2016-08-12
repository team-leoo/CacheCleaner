# README #

##### Warning! #####
This project is still under conceptualization / development. There is a lot of things to do and improve. Any contribution (pull-request, issues...) will be much appreciated.

### What is this repository for? ###
This Repository let you change the framework_assets_version to force client to reload assets.
You can either define specific commands (like assets:install or assetic:dump) that will be overloaded or use the new commands to do so manually.

### Installation ###
@todo: explain how to install package

Add the bundle to your AppKernel.php file:
```
$bundles = array(
    ...
    new LeooTeam\CacheCleanerBundle\LeooTeamCacheCleanerBundle(),
);
```

### Requirements ###
This bundle requires Symfony >= 2.8

### Configuration ###
Required : import the configuration file (warning: it might be missing so you must add "ignore_errors").
```
#app/config/config.yml
imports:
    - { resource: "@LeooTeamCacheCleanerBundle/Resources/config/versions.yml", ignore_errors: true }
```

Optionnal : add the commands that will trigger the event.
```
leoo_team_cache_cleaner:
    commands: ['assets:install', 'assetic:dump']
```

### Usages ###
If you have done the second step from configuration part, each time you'll call a command which is declared in leoo_team_cache_cleaner.commands, the version will be updated automatically.
You can also use the ccleaner commands:

- ccleaner:update (-c=VERSION_NUMBER) --> update the version, parameter is optional
- ccleaner:rollback (-c=VERSION_NUMBER) --> rollback the version, parameter is optional
- ccleaner:show --> display current version
