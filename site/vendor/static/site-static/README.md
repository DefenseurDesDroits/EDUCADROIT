# Ddd Parcours `[site-static]`

> This is static sources for the project.

## Usage

* Run `npm start` to preview and watch for changes.
* Run `bower install --save <package>` to install dependencies.
* Run `npm run build` to build for production.

## Boilerplate

The directory structure:

    .
    ├── .bowerrc
    ├── .editorconfig
    ├── .gitattributes
    ├── .gitignore
    ├── .jshintrc
    ├── .yo-rc.json
    ├── Gruntfile.js
    ├── README.md
    ├── bower.json
    ├── composer.json
    ├── package.json
    └── src
        ├── fonts
        ├── images
        ├── scripts
        │   └── theme.js
        ├── styles
        │   ├── components
        │   │   └── ...
        │   ├── core
        │   │   ├── ...
        │   │   ├── _mixins.scss
        │   │   └── _variables.scss
        │   └── theme.scss
        ├── templates
        │   ├── index.hbs
        │   ├── layouts
        │   │   └── default.hbs
        │   ├── page.hbs
        │   └── partials
        │       ├── footer.hbs
        │       └── nav.hbs
        ├── theme.yml
        └── vendor
            ├── bootstrap-sass
            ├── jquery
            └── matchheight