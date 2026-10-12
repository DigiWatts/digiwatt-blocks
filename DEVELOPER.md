# Developer Doc

This is a guide to the various scripts and functionality that can be used to compile and build out the plugin.

## Gutenburg Blocks

Located in the `blocks` folder

### NPM Commands/Tasks

Build app `npm run-script build`

Format css/js `npm run-script format`

Lint css `npm run-script lint:css` | auto fix `npm run-script lint:css:fix`

Lint js `npm run-script lint:js`

Lint markdown `npm run-script lint:md:docs`

Lint json `npm run-script lint:pkg-json`

Update packages: `npm run-script packages-update`

Start app dev `npm start`

## Composer

PHP Code Standards `composer phpcs`

PHP Beautifier `composer phpcbf`

PHP Stan `composer phpstan`

## Gulp

This is for the plugin in general. Everything is a Gulp command.

### Styles

Compile SASS `gulp sass`

Minify CSS `gulp mincss`

CSS linting with Stylelint `gulp lintcss`

### Scripts

Minify JS `gulp scripts`

JS linting with JSHint `gulp lintjs`

Make JS pretty `gulp beautifyjs`

### Other

Create plugin ZIP file for distribution `gulp zip`
