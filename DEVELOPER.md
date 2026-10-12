# Developer Doc

This is a guide to the various scripts and functionality that can be used to compile and build out the plugin.

## Gutenburg Blocks

Located in the `blocks` folder

### NPM Commands/Tasks

Build app `npm run-script build`

Format css/js `npm run-script format`

Lint css `npm run-script lint:css` | auto fix `npm run-script lint:css:fix`

Lint js `npm run-script lint:js` fix:  `npm run-script lint:js:fix` 

Lint markdown `npm run-script lint:md:docs`

Lint json `npm run-script lint:pkg-json`

Update packages: `npm run-script packages-update`

Start app dev `npm start`

## Composer

PHP Code Standards `composer phpcs`

PHP Beautifier `composer phpcbf`

PHP Stan `composer phpstan`

## Releases

Releases are automated with [release-please](https://github.com/googleapis/release-please). Commits and PR titles must follow [Conventional Commits](https://www.conventionalcommits.org/):

* `feat: add gallery block` - new feature, bumps the minor version
* `fix: correct read time rounding` - bug fix, bumps the patch version
* `feat!: ...` or a `BREAKING CHANGE:` footer - breaking change (bumps the minor version until 1.0)
* `chore:`, `docs:`, `refactor:`, `style:`, `ci:`, `build:`, `test:` - no release

When a PR is merged into `main`, the Release workflow opens a release PR (version bump + `CHANGELOG.md`), merges it, creates the GitHub release, and attaches `digiwatt-blocks.zip`. Don't bump version numbers by hand. To force a specific version, add a `Release-As: 1.0.0` footer to a commit.
