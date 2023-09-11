const mix = require("laravel-mix");

if (!mix.inProduction()) {
    mix.js("resources/js/app.js", "public/js")
        .sass("resources/sass/app.scss", "public/css")
        .babelConfig({
            presets: ["@babel/preset-env"],
        })
        .options({
            processCssUrls: false,
        })
        .sourceMaps();

    mix.js("resources/js/notification.js", "public/js");
} else {
    // Production mode configuration
    mix.js("resources/js/app.js", "public/js")
        .sass("resources/sass/app.scss", "public/css")
        .babelConfig({
            presets: ["@babel/preset-env"],
        })
        .options({
            processCssUrls: false,
        });

    mix.styles(["public/assets/**/*.css"], "public/css/all.css")
        .scripts(["public/assets/**/*.js"], "public/js/all.js")
        .scripts(["public/assets/**/*.js"], "public/js/all.js")
        .scripts(["public/assets/**/**/*.js"], "public/js/all.js")
        .version();
}

// Additional mix.babel() line for your JavaScript files
mix.babel(["public/assets/**/*.js"], "public/js/all.js");
mix.babel(["public/assets/**/**/*.js"], "public/js/all.js");
