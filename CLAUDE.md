# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Overview

DigiWatt Blocks is a WordPress plugin that adds custom Gutenberg blocks (text domain/prefix `dwb`). It lives inside a Local by Flywheel site (`~/LocalSites/bike`), so changes can be checked in that site's block editor and front end.

## Commands

```
npm ci --ignore-scripts   # node-sass@4 fails to compile on current Node; it isn't needed for the block build
npm run build             # bundles blocks/index.js -> assets/build/blocks.js + blocks.asset.php
npm run start             # watch mode
npm run lint:js           # also lint:css, format (all @wordpress/scripts)

composer install
composer phpcs            # WordPress coding standards via phpcs.ruleset.xml (phpcbf to fix)
composer phpstan
```

`webpack.config.js` overrides the wp-scripts entry/output, so the bundle goes to `assets/build/`, not `build/`. The built files are committed, so rebuild and commit them along with any JS change. There are no tests.

## Releases

Commits and PR titles use [Conventional Commits](https://www.conventionalcommits.org/) (`feat:`, `fix:`, `chore:`, ...). PRs are squash-merged, so the PR title becomes the commit on `main`; `.github/workflows/pr-title.yml` checks it.

Releases are automated by release-please (`.github/workflows/release.yml`). Each push to `main` opens a release PR, the workflow merges it straight away, then tags the release (no `v` prefix, e.g. `0.4.0`) and attaches `digiwatt-blocks.zip`. Only `feat`, `fix`, and breaking changes trigger a release; while below 1.0, `feat` and breaking changes bump the minor version. Don't bump versions or edit `CHANGELOG.md` by hand: release-please updates `package.json`/`package-lock.json` and the lines marked `x-release-please-version` in `digiwatt-blocks.php`, `includes/class-dwb.php`, and `README.md`. The zip is `git archive` of the tag plus a `composer install --no-dev` `vendor/` (needed for plugin-update-checker, which updates installs from the release zip). Files left out of it are listed as `export-ignore` in `.gitattributes`.

## Architecture

- `digiwatt-blocks.php` defines `DWB_PLUGIN_FILE` and loads `includes/class-dwb.php`, a singleton (`dwb()`, also `$GLOBALS['dwb']`) that defines the `DWB_*` path/URL constants and `include_once`s every `blocks/<slug>.php`. **New blocks must be added to `includes()` there, and imported in `blocks/index.js`.**
- Each block is split into two parts:
  - `blocks/<slug>.php`: an `init` hook that registers the shared editor script, the block's `editor.css`/`style.css` (versioned with `filemtime`), and calls `register_block_type`. Every block file re-registers the same `dwb-block-script` handle pointing at the single `assets/build/blocks.js` bundle.
  - `blocks/<slug>/index.js` (+ `edit.js`): the `registerBlockType` call.
- **Dynamic blocks** (`dwb/post-header`, `dwb/read-time`) have no `save`; the front end is rendered by PHP `render_callback`s (`render_block_digiwatt_*`). Their attributes are declared in **both** JS and PHP, so keep the two in sync. The editor preview in `edit.js` copies the PHP markup by hand, so markup changes have to be made in both places.
- **Static blocks** (`dwb/about-block`, `dwb/tagline-block`; note the `-block` suffix) use a JS `save`. Changing `save` output invalidates existing content unless you add a `deprecated` entry.
- Reading time (words / 200, rounded up) is computed in both `blocks/read-time/edit.js` (editor) and `dwb_reading_time()` in `includes/functions.php` (front end).
