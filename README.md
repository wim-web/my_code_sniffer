## How to Install

```
composer require wim-web/phpcs-ruleset --dev
```

## How to Use

Add `phpcs.xml`

```
<?xml version="1.0"?>
<ruleset>
    <arg name="basepath" value="."/>

    <file>./src</file>
    <file>./tests</file>

    <rule ref="./vendor/wim-web/phpcs-ruleset/Wim/ruleset.xml" />
</ruleset>
```