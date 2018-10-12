[![Build Status](https://travis-ci.org/mlocati/composer-patcher.svg?branch=master)](https://travis-ci.org/mlocati/composer-patcher)

# composer-patcher

Simple patches plugin for Composer.
Applies a patch from a local or remote file to any package required with Composer.

## Usage

Example composer.json:

```json
{
    "require": {
        "mlocati/composer-patcher": "~1.0",
        "concrete5/core": "~8.4"
    },
    "extra": {
        "patches": {
            "concrete5/core": {
                "This is the patch description": "https://www.example.com/remote.patch",
                "This is another patch": "path/relative/to/this/package/local.patch"
            }
        },
        "patches-file": {
            "path/relative/to/this/package/patch-list.json"
        },
        "allow-subpatches": [
            "concrete5/core"
        ],
        "patch-errors-as-warnings": true,
        "patch-temporary-folder": "/var/tmp"
    }
}

```

If you use the `patches-file` configuration key, it must be a local or remote JSON file with this syntax:

```json
{
    "patches": {
        "vendor/project": {
            "Patch description #1": "https://www.example.com/remote.patch",
            "Patch description #2": "path/relative/to/the/defining/package/local.patch"
        }
    }
}
```

## Allowing patches to be applied from dependencies

You can use the `allow-subpatches` to let dependency packages install patches.
It can be:
- `false` [the default] to prevent dependency packages from installing patches
- `true` to allow all dependency packages installing patches
- an array of package handles to whitelist the packages that can install patches


## Using patches from HTTP URLs

Composer [blocks](https://getcomposer.org/doc/06-config.md#secure-http) you from downloading anything from HTTP URLs, you can disable this for your project by adding a `secure-http` setting in the config section of your `composer.json`. Note that the `config` section should be under the root of your `composer.json`.

```json
{
    "config": {
        "secure-http": false
    }
}
```

However, it's always advised to setup HTTPS to prevent MITM code injection.

## Patch levels

In order to specify the level of a patch, you can use the extended form of the patch path.
For example, if the patch level for a patch should be `-p4`, you can replace

```json
{
    "patches": {
        "vendor/project": {
            "Patch description": "https://www.example.com/remote.patch",
        }
    }
}
```
with
```json
{
    "patches": {
        "vendor/project": {
            "Patch description": {
                "path": "https://www.example.com/remote.patch",
                "levels": ["-p4"]
            }
        }
    }
}
```

It can be:
- `false` [the default] to prevent dependency packages from installing patches
- `true` to allow all dependency packages installing patches
- an array of package handles to whitelist the packages that can install patches


## Target packages

You can specify the package versions a patch should be applied to.
To do so, simply specify the version in the package handle:

```json
{
    "patches": {
        "vendor/project:1.1.3": {
            "Patch description": "https://www.example.com/remote.patch",
        }
    }
}
```

You can use the [Composer syntax](https://getcomposer.org/doc/articles/versions.md) to specify the applicable version(s).


## Patches containing modifications to composer.json files

Because patching occurs _after_ Composer calculates dependencies and installs packages, changes to an underlying dependency's `composer.json` file introduced in a patch will have _no effect_ on installed packages.

If you need to modify a dependency's `composer.json` or its underlying dependencies, you cannot use this plugin. Instead, you must do one of the following:
- Work to get the underlying issue resolved in the upstream package.
- Fork the package and [specify your fork as the package repository](https://getcomposer.org/doc/05-repositories.md#vcs) in your root `composer.json`
- Specify compatible package version requirements in your root `composer.json`

## Error handling

You can use the `extra.patch-errors-as-warnings` configuration option to instruct `composer-patcher` what to do in case of errors.
It can be:
- `true` [the default] to simply output an error message in case of errors
- `false` to exit composer in case of errors

## Credits

A ton of this code is adapted or taken straight from [`cweagans/composer-patcher`](https://github.com/cweagans/composer-patcher).
