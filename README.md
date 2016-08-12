# README #

##### Warning! #####
This project is still under conceptualization / development. There is a lot of things to do and improve. Any contirbution (pull-request, issues...) will be much appreciated.

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
Optionnal : add the commands that will trigger the event.
```
#app/config/config.yml
leoo_team_cache_cleaner:
    commands: ['cache:clear', 'assets:install', 'assetic:dump']
```