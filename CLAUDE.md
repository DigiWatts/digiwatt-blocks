# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Overview

DigiWatt Blocks is a WordPress plugin that adds custom Gutenberg blocks (text domain/prefix `dwb`). It lives inside a Local by Flywheel site (`~/LocalSites/bike`), so changes can be checked in that site's block editor and front end. Active development is on `release/0.3.0` (PR #21); `main` is still at 0.2.0.

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

## Architecture

- `digiwatt-blocks.php` defines `DWB_PLUGIN_FILE` and loads `includes/class-dwb.php`, a singleton (`dwb()`, also `$GLOBALS['dwb']`) that defines the `DWB_*` path/URL constants and `include_once`s every `blocks/<slug>.php`. **New blocks must be added to `includes()` there, and imported in `blocks/index.js`.**
- Each block is split into two parts:
  - `blocks/<slug>.php`: an `init` hook that registers the shared editor script, the block's `editor.css`/`style.css` (versioned with `filemtime`), and calls `register_block_type`. Every block file re-registers the same `dwb-block-script` handle pointing at the single `assets/build/blocks.js` bundle.
  - `blocks/<slug>/index.js` (+ `edit.js`): the `registerBlockType` call.
- **Dynamic blocks** (`dwb/post-header`, `dwb/read-time`) have no `save`; the front end is rendered by PHP `render_callback`s (`render_block_digiwatt_*`). Their attributes are declared in **both** JS and PHP, so keep the two in sync. The editor preview in `edit.js` copies the PHP markup by hand, so markup changes have to be made in both places.
- **Static blocks** (`dwb/about-block`, `dwb/tagline-block`; note the `-block` suffix) use a JS `save`. Changing `save` output invalidates existing content unless you add a `deprecated` entry.
- Reading time (words / 200, rounded up) is computed in both `blocks/read-time/edit.js` (editor) and `dwb_reading_time()` in `includes/functions.php` (front end).
